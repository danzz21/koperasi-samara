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

    public function detail($id_anggota)
    {
        // Ambil data anggota
        $anggota = $this->anggotaModel->find($id_anggota);
        
        if (!$anggota) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Anggota tidak ditemukan');
        }

                // 1. TOTAL SIMPANAN (Menggunakan selectSum)
        $sumPokok = $this->simpananPokokModel->builder()->selectSum('jumlah', 'total')->where('id_anggota', $id_anggota)->get()->getRowArray();
        $sumWajib = $this->simpananWajibModel->builder()->selectSum('jumlah', 'total')->where('id_anggota', $id_anggota)->get()->getRowArray();
        $sumSukarela = $this->simpananSukarelaModel->builder()->selectSum('jumlah', 'total')->where('id_anggota', $id_anggota)->get()->getRowArray();

        $totalPokok = (float) ($sumPokok['total'] ?? 0);
        $totalWajib = (float) ($sumWajib['total'] ?? 0);
        $totalSukarela = (float) ($sumSukarela['total'] ?? 0);
        $totalSimpanan = $totalPokok + $totalWajib + $totalSukarela;


        // Tanggal Transaksi Terakhir
        $lastPokok = $this->simpananPokokModel->where('id_anggota', $id_anggota)->orderBy('tanggal', 'DESC')->first();
        $lastWajib = $this->simpananWajibModel->where('id_anggota', $id_anggota)->orderBy('tanggal', 'DESC')->first();
        $lastSukarela = $this->simpananSukarelaModel->where('id_anggota', $id_anggota)->orderBy('tanggal', 'DESC')->first();

                // 2. TOTAL PEMBIAYAAN
        $total_qard_row = $this->qardModel->builder()->selectSum('jml_pinjam', 'total')->where('id_anggota', $id_anggota)->get()->getRowArray();
        $total_murabahah_row = $this->murabahahModel->builder()->selectSum('jml_pinjam', 'total')->where('id_anggota', $id_anggota)->get()->getRowArray();
        $total_mudharabah_row = $this->mudharabahModel->builder()->selectSum('jml_pinjam', 'total')->where('id_anggota', $id_anggota)->get()->getRowArray();
        $totalPembiayaan = (float)($total_qard_row['total'] ?? 0) + (float)($total_murabahah_row['total'] ?? 0) + (float)($total_mudharabah_row['total'] ?? 0);

        // 3. SISA ANGSURAN
        $sisa_tenor_qard_row = $this->qardModel->builder()->selectSum('sisa_tenor', 'total')->where('id_anggota', $id_anggota)->get()->getRowArray();
        $sisa_tenor_murabahah_row = $this->murabahahModel->builder()->selectSum('sisa_tenor', 'total')->where('id_anggota', $id_anggota)->get()->getRowArray();
        $sisa_tenor_mudharabah_row = $this->mudharabahModel->builder()->selectSum('sisa_tenor', 'total')->where('id_anggota', $id_anggota)->get()->getRowArray();
        $sisaAngsuran = (int)($sisa_tenor_qard_row['total'] ?? 0) + (int)($sisa_tenor_murabahah_row['total'] ?? 0) + (int)($sisa_tenor_mudharabah_row['total'] ?? 0);


        // 4. DATA PEMBIAYAAN
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
                'margin' => 0,
                'jangka_waktu' => $qard['jml_angsuran'],
                'angsuran_per_bulan' => $qard['jml_pinjam'] / max($qard['jml_angsuran'], 1),
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
                'margin' => 10,
                'jangka_waktu' => $murabahah['jml_angsuran'],
                'angsuran_per_bulan' => $murabahah['jml_pinjam'] / max($murabahah['jml_angsuran'], 1),
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
                'margin' => 10,
                'jangka_waktu' => $mudharabah['jml_angsuran'],
                'angsuran_per_bulan' => $mudharabah['jml_pinjam'] / max($mudharabah['jml_angsuran'], 1),
                'sisa_tenor' => $mudharabah['sisa_tenor'],
                'total_dibayar' => $mudharabah['jml_terbayar']
            ];
        }

        // 5. RIWAYAT TRANSAKSI & JADWAL ANGSURAN
        $riwayat_transaksi = $this->getRiwayatTransaksi($id_anggota);
        $jadwal_angsuran = $this->getJadwalAngsuran($id_anggota);

        $data = [
            'title' => 'Detail Anggota - ' . $anggota['nama_lengkap'],
            'anggota' => $anggota,
            
            // Akumulasi Card Utama
            'totalSimpanan' => $totalSimpanan,
            'totalPembiayaan' => $totalPembiayaan,
            'sisaAngsuran' => $sisaAngsuran,

            // Data Simpanan Rinci
            'simpanan_pokok' => [
                'total' => $totalPokok,
                'tanggal_terakhir' => $lastPokok['tanggal'] ?? null
            ],
            'simpanan_wajib' => [
                'total' => $totalWajib,
                'setoran_bulanan' => 100000,
                'tanggal_terakhir' => $lastWajib['tanggal'] ?? null
            ],
            'simpanan_sukarela' => [
                'total' => $totalSukarela,
                'tanggal_terakhir' => $lastSukarela['tanggal'] ?? null
            ],
            
                        // Data Pembiayaan
            'total_qard' => ['total' => $total_qard_row['total'] ?? 0],
            'total_murabahah' => ['total' => $total_murabahah_row['total'] ?? 0],
            'total_mudharabah' => ['total' => $total_mudharabah_row['total'] ?? 0],
            
            // Sisa Tenor Spesifik
            'sisa_tenor_qard' => $sisa_tenor_qard_row['total'] ?? 0,
            'sisa_tenor_murabahah' => $sisa_tenor_murabahah_row['total'] ?? 0,
            'sisa_tenor_mudharabah' => $sisa_tenor_mudharabah_row['total'] ?? 0,

            
            // Data Lainnya
            'bagi_hasil' => 0,
            'bagi_hasil_bulan_ini' => 0,
            'bagi_hasil_tahun_ini' => 0,
            'data_pembiayaan' => $data_pembiayaan,
            'riwayat_transaksi' => $riwayat_transaksi,
            'jadwal_angsuran' => $jadwal_angsuran
        ];

                return view('dashboard_admin/detail_anggota', $data);
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

        usort($riwayat, function ($a, $b) {
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
                'angsuran_per_bulan' => $qard['jml_pinjam'] / max($qard['jml_angsuran'], 1),
                'tanggal_pembiayaan' => $qard['tanggal']
            ];
        }

        // Murabahah
        $murabahah_data = $this->murabahahModel->where('id_anggota', $id_anggota)->where('status', 'aktif')->findAll();
        foreach ($murabahah_data as $murabahah) {
            $jadwal[] = [
                'nama_pembiayaan' => 'Pembiayaan Murabahah',
                'sisa_tenor' => $murabahah['sisa_tenor'],
                'angsuran_per_bulan' => $murabahah['jml_pinjam'] / max($murabahah['jml_angsuran'], 1),
                'tanggal_pembiayaan' => $murabahah['tanggal']
            ];
        }

        // Mudharabah
        $mudharabah_data = $this->mudharabahModel->where('id_anggota', $id_anggota)->where('status', 'aktif')->findAll();
        foreach ($mudharabah_data as $mudharabah) {
            $jadwal[] = [
                'nama_pembiayaan' => 'Pembiayaan Mudharabah',
                'sisa_tenor' => $mudharabah['sisa_tenor'],
                'angsuran_per_bulan' => $mudharabah['jml_pinjam'] / max($mudharabah['jml_angsuran'], 1),
                'tanggal_pembiayaan' => $mudharabah['tanggal']
            ];
        }

        return $jadwal;
    }
}