<?php
namespace App\Controllers;

use App\Models\AnggotaModel;
use App\Models\SimpananPokokModel;
use App\Models\SimpananWajibModel;
use App\Models\SimpananSukarelaModel;
use App\Models\QardModel;
use App\Models\MurabahahModel;
use App\Models\MudharabahModel;

class AnggotaDetail extends BaseController
{
    protected $anggotaModel;
    protected $simpananPokokModel;
    protected $simpananWajibModel;
    protected $simpananSukarelaModel;
    protected $qardModel;
    protected $murabahahModel;
    protected $mudharabahModel;

    public function __construct()
    {
        $this->anggotaModel = new AnggotaModel();
        $this->simpananPokokModel = new SimpananPokokModel();
        $this->simpananWajibModel = new SimpananWajibModel();
        $this->simpananSukarelaModel = new SimpananSukarelaModel();
        $this->qardModel = new QardModel();
        $this->murabahahModel = new MurabahahModel();
        $this->mudharabahModel = new MudharabahModel();
    }

    // AdminController.php
    public function detailAnggota($id)
    {
        $anggotaModel = new \App\Models\AnggotaModel();
        $anggota = $anggotaModel->find($id);

        if (!$anggota) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("Anggota tidak ditemukan");
        }

        $data = [
            'anggota' => $anggota
        ];

        return view('admin/detail_anggota', $data);
    }


    public function detail($id_anggota)
    {
        // Ambil data anggota
        $anggota = $this->anggotaModel->find($id_anggota);
        
        if (!$anggota) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Anggota tidak ditemukan');
        }

        // 1. TOTAL SIMPANAN (dari 3 tabel)
        $simpanan_pokok = $this->simpananPokokModel->where('id_anggota', $id_anggota)->first();
        $simpanan_wajib = $this->simpananWajibModel->where('id_anggota', $id_anggota)->first();
        $simpanan_sukarela = $this->simpananSukarelaModel->where('id_anggota', $id_anggota)->first();

        // 2. TOTAL PEMBIAYAAN (dari 3 tabel)
        $total_qard = $this->qardModel->selectSum('jml_pinjam')->where('id_anggota', $id_anggota)->first();
        $total_murabahah = $this->murabahahModel->selectSum('jml_pinjam')->where('id_anggota', $id_anggota)->first();
        $total_mudharabah = $this->mudharabahModel->selectSum('jml_pinjam')->where('id_anggota', $id_anggota)->first();

        // 3. SISA ANGSURAN (dari sisa_tenor 3 tabel)
        $sisa_tenor_qard = $this->qardModel->selectSum('sisa_tenor')->where('id_anggota', $id_anggota)->first();
        $sisa_tenor_murabahah = $this->murabahahModel->selectSum('sisa_tenor')->where('id_anggota', $id_anggota)->first();
        $sisa_tenor_mudharabah = $this->mudharabahModel->selectSum('sisa_tenor')->where('id_anggota', $id_anggota)->first();

        // 4. DATA PEMBIAYAAN (dari 3 tabel)
        $data_pembiayaan = [];
        
        // Qard
        $qard_data = $this->qardModel->where('id_anggota', $id_anggota)->findAll();
        foreach ($qard_data as $qard) {
            $data_pembiayaan[] = [
                'jenis_pembiayaan' => 'Pinjaman Qard',
                'akad' => 'Qard',
                'nomor_pembiayaan' => 'QARD-' . $qard['id_qard'],
                'status' => $qard['status'],
                'jumlah_pembiayaan' => $qard['jml_pinjam'],
                'margin' => 0, // Qard tanpa margin
                'jangka_waktu' => $qard['jml_angsuran'],
                'angsuran_per_bulan' => $qard['jml_pinjam'] / $qard['jml_angsuran'],
                'sisa_tenor' => $qard['sisa_tenor'],
                'total_dibayar' => $qard['jml_terbayar']
            ];
        }

        // Murabahah
        $murabahah_data = $this->murabahahModel->where('id_anggota', $id_anggota)->findAll();
        foreach ($murabahah_data as $murabahah) {
            $data_pembiayaan[] = [
                'jenis_pembiayaan' => 'Pembiayaan Murabahah',
                'akad' => 'Murabahah',
                'nomor_pembiayaan' => 'MRB-' . $murabahah['id_mr'],
                'status' => $murabahah['status'],
                'jumlah_pembiayaan' => $murabahah['jml_pinjam'],
                'margin' => 10, // Contoh 10%
                'jangka_waktu' => $murabahah['jml_angsuran'],
                'angsuran_per_bulan' => $murabahah['jml_pinjam'] / $murabahah['jml_angsuran'],
                'sisa_tenor' => $murabahah['sisa_tenor'],
                'total_dibayar' => $murabahah['jml_terbayar']
            ];
        }

        // Mudharabah
        $mudharabah_data = $this->mudharabahModel->where('id_anggota', $id_anggota)->findAll();
        foreach ($mudharabah_data as $mudharabah) {
            $data_pembiayaan[] = [
                'jenis_pembiayaan' => 'Pembiayaan Mudharabah',
                'akad' => 'Mudharabah',
                'nomor_pembiayaan' => 'MDH-' . $mudharabah['id_md'],
                'status' => $mudharabah['status'],
                'jumlah_pembiayaan' => $mudharabah['jml_pinjam'],
                'margin' => 10, // Contoh 10%
                'jangka_waktu' => $mudharabah['jml_angsuran'],
                'angsuran_per_bulan' => $mudharabah['jml_pinjam'] / $mudharabah['jml_angsuran'],
                'sisa_tenor' => $mudharabah['sisa_tenor'],
                'total_dibayar' => $mudharabah['jml_terbayar']
            ];
        }

        // 5. RIWAYAT TRANSAKSI (gabungan dari semua tabel)
        $riwayat_transaksi = $this->getRiwayatTransaksi($id_anggota);

        // 6. JADWAL ANGSURAN
        $jadwal_angsuran = $this->getJadwalAngsuran($id_anggota);

        $data = [
            'title' => 'Detail Anggota - ' . $anggota['nama_lengkap'],
            'anggota' => $anggota,
            
            // Data Simpanan
            'simpanan_pokok' => [
                'total' => $simpanan_pokok['jumlah'] ?? 0,
                'tanggal_terakhir' => $simpanan_pokok['tanggal'] ?? null
            ],
            'simpanan_wajib' => [
                'total' => $simpanan_wajib['jumlah'] ?? 0,
                'setoran_bulanan' => 100000, // Contoh
                'tanggal_terakhir' => $simpanan_wajib['tanggal'] ?? null
            ],
            'simpanan_sukarela' => [
                'total' => $simpanan_sukarela['jumlah'] ?? 0,
                'tanggal_terakhir' => $simpanan_sukarela['tanggal'] ?? null
            ],
            
            // Data Pembiayaan
            'total_qard' => ['total' => $total_qard['jml_pinjam'] ?? 0],
            'total_murabahah' => ['total' => $total_murabahah['jml_pinjam'] ?? 0],
            'total_mudharabah' => ['total' => $total_mudharabah['jml_pinjam'] ?? 0],
            
            // Sisa Tenor
            'sisa_tenor_qard' => $sisa_tenor_qard['sisa_tenor'] ?? 0,
            'sisa_tenor_murabahah' => $sisa_tenor_murabahah['sisa_tenor'] ?? 0,
            'sisa_tenor_mudharabah' => $sisa_tenor_mudharabah['sisa_tenor'] ?? 0,
            
            // Data Lainnya
            'bagi_hasil' => 0, // Sesuaikan dengan data bagi hasil
            'bagi_hasil_bulan_ini' => 0,
            'bagi_hasil_tahun_ini' => 0,
            'data_pembiayaan' => $data_pembiayaan,
            'riwayat_transaksi' => $riwayat_transaksi,
            'jadwal_angsuran' => $jadwal_angsuran
        ];

        return view('detail_anggota', $data);
    }

    private function getRiwayatTransaksi($id_anggota)
    {
        $riwayat = [];

        // Ambil dari simpanan pokok
        $simpanan_pokok = $this->simpananPokokModel->where('id_anggota', $id_anggota)->findAll();
        foreach ($simpanan_pokok as $sp) {
            $riwayat[] = [
                'type' => 'pemasukan',
                'keterangan' => 'Setoran Simpanan Pokok',
                'tanggal' => $sp['tanggal'],
                'jumlah' => $sp['jumlah'],
                'status' => 'berhasil'
            ];
        }

        // Ambil dari simpanan wajib
        $simpanan_wajib = $this->simpananWajibModel->where('id_anggota', $id_anggota)->findAll();
        foreach ($simpanan_wajib as $sw) {
            $riwayat[] = [
                'type' => 'pemasukan',
                'keterangan' => 'Setoran Simpanan Wajib',
                'tanggal' => $sw['tanggal'],
                'jumlah' => $sw['jumlah'],
                'status' => 'berhasil'
            ];
        }

        // Ambil dari simpanan sukarela
        $simpanan_sukarela = $this->simpananSukarelaModel->where('id_anggota', $id_anggota)->findAll();
        foreach ($simpanan_sukarela as $ss) {
            $riwayat[] = [
                'type' => 'pemasukan',
                'keterangan' => 'Setoran Simpanan Sukarela',
                'tanggal' => $ss['tanggal'],
                'jumlah' => $ss['jumlah'],
                'status' => 'berhasil'
            ];
        }

        // Urutkan berdasarkan tanggal terbaru
        usort($riwayat, function($a, $b) {
            return strtotime($b['tanggal']) - strtotime($a['tanggal']);
        });

        return $riwayat;
    }

    private function getJadwalAngsuran($id_anggota)
    {
        $jadwal = [];

        // Qard
        $qard_data = $this->qardModel->where('id_anggota', $id_anggota)->where('status', 'aktif')->findAll();
        foreach ($qard_data as $qard) {
            $jadwal[] = [
                'nama_pembiayaan' => 'Pinjaman Qard',
                'sisa_tenor' => $qard['sisa_tenor'],
                'angsuran_per_bulan' => $qard['jml_pinjam'] / $qard['jml_angsuran'],
                'tanggal_pembiayaan' => $qard['tanggal']
            ];
        }

        // Murabahah
        $murabahah_data = $this->murabahahModel->where('id_anggota', $id_anggota)->where('status', 'aktif')->findAll();
        foreach ($murabahah_data as $murabahah) {
            $jadwal[] = [
                'nama_pembiayaan' => 'Pembiayaan Murabahah',
                'sisa_tenor' => $murabahah['sisa_tenor'],
                'angsuran_per_bulan' => $murabahah['jml_pinjam'] / $murabahah['jml_angsuran'],
                'tanggal_pembiayaan' => $murabahah['tanggal']
            ];
        }

        // Mudharabah
        $mudharabah_data = $this->mudharabahModel->where('id_anggota', $id_anggota)->where('status', 'aktif')->findAll();
        foreach ($mudharabah_data as $mudharabah) {
            $jadwal[] = [
                'nama_pembiayaan' => 'Pembiayaan Mudharabah',
                'sisa_tenor' => $mudharabah['sisa_tenor'],
                'angsuran_per_bulan' => $mudharabah['jml_pinjam'] / $mudharabah['jml_angsuran'],
                'tanggal_pembiayaan' => $mudharabah['tanggal']
            ];
        }

        return $jadwal;
    }
}