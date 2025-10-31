<?php
namespace App\Controllers;

use App\Models\AnggotaModel;
use App\Models\QardModel;
use App\Models\MurabahahModel;
use App\Models\MudharabahModel;
use App\Models\PembayaranPendingModel;

class Cicilan extends BaseController
{
    protected $anggotaModel;
    protected $qardModel;
    protected $murabahahModel;
    protected $mudharabahModel;
    protected $pembayaranPendingModel;

    public function __construct()
    {
        $this->anggotaModel = new AnggotaModel();
        $this->qardModel = new QardModel();
        $this->murabahahModel = new MurabahahModel();
        $this->mudharabahModel = new MudharabahModel();
        $this->pembayaranPendingModel = new PembayaranPendingModel();
    }

   public function index()
{
    // Ambil data anggota dari session
   $id_anggota = session()->get('id'); // Coba dengan 'id' saja
    if (!$id_anggota) {
        $id_anggota = session()->get('id_anggota'); // Fallback ke 'id_anggota'
    }
    $db = \Config\Database::connect();

    
    // Untuk testing, jika tidak ada session, gunakan ID 1
    if (!$id_anggota) {
        $id_anggota = 1;
    }

    // Ambil data anggota
    $anggota = $this->anggotaModel->find($id_anggota);
    
    // Jika anggota tidak ditemukan, buat data dummy untuk testing
    if (!$anggota) {
        $anggota = [
            'id_anggota' => $id_anggota,
            'nomor_anggota' => 'ANG' . str_pad($id_anggota, 5, '0', STR_PAD_LEFT),
            'nama_lengkap' => 'Anggota Demo',
            'foto' => 'default.png',
            'foto_diri' => null,
            'email' => 'demo@example.com',
            'no_ktp' => '1234567890123456',
            'alamat' => 'Alamat demo'
        ];
    }

    // Ambil data pinjaman
    $pinjaman_aktif = $this->getAllPinjamanAktif($id_anggota);
    $pinjaman_lunas = $this->getAllPinjamanLunas($id_anggota);
    
    // ✅ FORCE RELOAD MODEL DENGAN CARA MANUAL
    $pembayaranPendingModel = new \App\Models\PembayaranPendingModel();
    
    // Ambil data pembayaran pending
    $pembayaran_pending = $pembayaranPendingModel->where('id_anggota', $id_anggota)
                                                ->where('status', 'pending')
                                                ->orderBy('created_at', 'DESC')
                                                ->findAll();

    // Ambil riwayat pembayaran - GUNAKAN QUERY MANUAL DULU
    $riwayat_pembayaran = $pembayaranPendingModel->where('id_anggota', $id_anggota)
                                                ->where('status !=', 'pending')
                                                ->orderBy('created_at', 'DESC')
                                                ->findAll();

    // Hitung summary
    $summary = $this->getSummaryCicilan($pinjaman_aktif);

        // Cek tenor simpanan pokok anggota
        $tenorData = $db->table('simpanan_pokok')
            ->select('tenor')
            ->where('id_anggota', $id_anggota)
            ->get()
            ->getRowArray();

        $showTenorModal = false;
        if (!$tenorData || $tenorData['tenor'] === null) {
            $showTenorModal = true;
        }

    $data = [
        'title' => 'Manajemen Cicilan',
        'anggota' => $anggota,
        'pinjaman_aktif' => $pinjaman_aktif,
        'pinjaman_lunas' => $pinjaman_lunas,
        'pembayaran_pending' => $pembayaran_pending,
        'riwayat_pembayaran' => $riwayat_pembayaran,
        'summary' => $summary
    ];

    return view('cicilan', [
        'title' => $data['title'],
        'data' => $data,
        'anggota' => $data['anggota'], // ✅ tambahkan ini
        'summary' => $data['summary'], // biar bagian totalnya gak error juga
        'showTenorModal'=> $showTenorModal
    ]);

}

public function setTenor()
{
    $session = session();
    $id_anggota = $session->get('id'); 
    $tenor = $this->request->getPost('tenor');

    $db = \Config\Database::connect();
    $simpananPokok = $db->table('simpanan_pokok')->where('id_anggota', $id_anggota)->get()->getRowArray();

    if ($simpananPokok) {
        // Update tenor kalau sudah ada datanya
        $db->table('simpanan_pokok')
            ->where('id_anggota', $id_anggota)
            ->update(['tenor' => $tenor]);
    } else {
        // Insert kalau belum ada data simpanan pokok
        $db->table('simpanan_pokok')->insert([
            'id_anggota' => $id_anggota,
            'tenor'      => $tenor,
            'tanggal'    => date('Y-m-d'),
        ]);
    }

    return redirect()->to(base_url('anggota/cicilan'))->with('success', 'Tenor berhasil disimpan.');
}
   
private function getAllPinjamanAktif($id_anggota)
{
    $pinjaman_aktif = [];

    try {
        log_message('debug', '=== DEBUG DETAIL: MENGAMBIL PINJAMAN AKTIF ===');
        log_message('debug', 'ID Anggota: ' . $id_anggota);

        $db = \Config\Database::connect();
        
        // 1. Ambil dari tabel QARD
        $qard_aktif = $db->table('qard')
                        ->where('id_anggota', $id_anggota)
                        ->where('status', 'aktif')
                        ->get()
                        ->getResult();
        
        log_message('debug', 'Jumlah Qard aktif ditemukan: ' . count($qard_aktif));
        
        foreach ($qard_aktif as $qard) {
            log_message('debug', '🔍 DETAIL QARD:');
            log_message('debug', '  - ID: ' . $qard->id_qard);
            log_message('debug', '  - Jumlah Pinjam: ' . $qard->jml_pinjam);
            log_message('debug', '  - Jumlah Terbayar: ' . $qard->jml_terbayar);
            log_message('debug', '  - Jumlah Angsuran: ' . $qard->jml_angsuran);
            log_message('debug', '  - Status: ' . $qard->status);
            log_message('debug', '  - Tanggal: ' . $qard->tanggal);
            
            // Hitung angsuran berjalan
            $angsuran_berjalan = $this->hitungAngsuranBerjalanFix(
                $qard->jml_terbayar, 
                $qard->jml_pinjam, 
                $qard->jml_angsuran
            );
            
            // Hitung angsuran per bulan
            $angsuran_per_bulan = ($qard->jml_angsuran > 0 && $qard->jml_pinjam > 0) 
                ? $qard->jml_pinjam / $qard->jml_angsuran 
                : 0;
            
            $jatuh_tempo_berikutnya = $this->hitungJatuhTempoBerikutnya($qard->tanggal, $angsuran_berjalan + 1);
            
            // LOGIKA BISA_BAYAR YANG LEBIH SEDERHANA
            $bisa_bayar = true; // Default bisa bayar
            
            // Cek jika sudah lunas
            if ($angsuran_berjalan >= $qard->jml_angsuran) {
                $bisa_bayar = false;
                log_message('debug', '  - ❌ TIDAK BISA BAYAR: Sudah mencapai tenor maksimal');
            }
            
            // Cek jika tenor 0
            if ($qard->jml_angsuran <= 0) {
                $bisa_bayar = false;
                log_message('debug', '  - ❌ TIDAK BISA BAYAR: Tenor tidak valid');
            }
            
            log_message('debug', '  - Angsuran Berjalan: ' . $angsuran_berjalan);
            log_message('debug', '  - Angsuran per Bulan: ' . $angsuran_per_bulan);
            log_message('debug', '  - Bisa Bayar: ' . ($bisa_bayar ? 'YA ✅' : 'TIDAK ❌'));
            
            $pinjaman_data = [
                'id' => $qard->id_qard,
                'jenis' => 'qard',
                'nama_pinjaman' => 'Pinjaman Al-Qord',
                'angsuran_berjalan' => $angsuran_berjalan,
                'tenor' => $qard->jml_angsuran,
                'angsuran_per_bulan' => $angsuran_per_bulan,
                'total_pinjaman' => $qard->jml_pinjam,
                'tanggal_pinjaman' => $qard->tanggal,
                'jatuh_tempo_berikutnya' => $jatuh_tempo_berikutnya,
                'status' => $qard->status,
                'total_terbayar' => floatval($qard->jml_terbayar ?? 0),
                'bisa_bayar' => $bisa_bayar,
                'angsuran_selanjutnya' => $angsuran_berjalan + 1
            ];
            
            $pinjaman_aktif[] = (object)$pinjaman_data;
        }

        // Lakukan hal yang sama untuk Murabahah dan Mudharabah...
        // 2. Ambil dari tabel MURABAHAH
        $murabahah_aktif = $db->table('murabahah')
                             ->where('id_anggota', $id_anggota)
                             ->where('status', 'aktif')
                             ->get()
                             ->getResult();
        
        log_message('debug', 'Jumlah Murabahah aktif ditemukan: ' . count($murabahah_aktif));
        
        foreach ($murabahah_aktif as $murabahah) {
            log_message('debug', '🔍 DETAIL MURABAHAH:');
            log_message('debug', '  - ID: ' . $murabahah->id_mr);
            log_message('debug', '  - Jumlah Pinjam: ' . $murabahah->jml_pinjam);
            log_message('debug', '  - Jumlah Terbayar: ' . $murabahah->jml_terbayar);
            log_message('debug', '  - Jumlah Angsuran: ' . $murabahah->jml_angsuran);
            
            $angsuran_berjalan = $this->hitungAngsuranBerjalanFix(
                $murabahah->jml_terbayar, 
                $murabahah->jml_pinjam, 
                $murabahah->jml_angsuran
            );
            
            $angsuran_per_bulan = ($murabahah->jml_angsuran > 0 && $murabahah->jml_pinjam > 0) 
                ? $murabahah->jml_pinjam / $murabahah->jml_angsuran 
                : 0;
            
            $jatuh_tempo_berikutnya = $this->hitungJatuhTempoBerikutnya($murabahah->tanggal, $angsuran_berjalan + 1);
            
            $bisa_bayar = true;
            if ($angsuran_berjalan >= $murabahah->jml_angsuran) $bisa_bayar = false;
            if ($murabahah->jml_angsuran <= 0) $bisa_bayar = false;
            
            log_message('debug', '  - Angsuran Berjalan: ' . $angsuran_berjalan);
            log_message('debug', '  - Bisa Bayar: ' . ($bisa_bayar ? 'YA ✅' : 'TIDAK ❌'));
            
            $pinjaman_data = [
                'id' => $murabahah->id_mr,
                'jenis' => 'murabahah',
                'nama_pinjaman' => 'Pinjaman Murabahah',
                'angsuran_berjalan' => $angsuran_berjalan,
                'tenor' => $murabahah->jml_angsuran,
                'angsuran_per_bulan' => $angsuran_per_bulan,
                'total_pinjaman' => $murabahah->jml_pinjam,
                'tanggal_pinjaman' => $murabahah->tanggal,
                'jatuh_tempo_berikutnya' => $jatuh_tempo_berikutnya,
                'status' => $murabahah->status,
                'total_terbayar' => floatval($murabahah->jml_terbayar ?? 0),
                'bisa_bayar' => $bisa_bayar,
                'angsuran_selanjutnya' => $angsuran_berjalan + 1
            ];
            
            $pinjaman_aktif[] = (object)$pinjaman_data;
        }

        // 3. Ambil dari tabel MUDHARABAH
        $mudharabah_aktif = $db->table('mudharabah')
                              ->where('id_anggota', $id_anggota)
                              ->where('status', 'aktif')
                              ->get()
                              ->getResult();
        
        log_message('debug', 'Jumlah Mudharabah aktif ditemukan: ' . count($mudharabah_aktif));
        
        foreach ($mudharabah_aktif as $mudharabah) {
            log_message('debug', '🔍 DETAIL MUDHARABAH:');
            log_message('debug', '  - ID: ' . $mudharabah->id_md);
            log_message('debug', '  - Jumlah Pinjam: ' . $mudharabah->jml_pinjam);
            log_message('debug', '  - Jumlah Terbayar: ' . $mudharabah->jml_terbayar);
            log_message('debug', '  - Jumlah Angsuran: ' . $mudharabah->jml_angsuran);
            
            $angsuran_berjalan = $this->hitungAngsuranBerjalanFix(
                $mudharabah->jml_terbayar, 
                $mudharabah->jml_pinjam, 
                $mudharabah->jml_angsuran
            );
            
            $angsuran_per_bulan = ($mudharabah->jml_angsuran > 0 && $mudharabah->jml_pinjam > 0) 
                ? $mudharabah->jml_pinjam / $mudharabah->jml_angsuran 
                : 0;
            
            $jatuh_tempo_berikutnya = $this->hitungJatuhTempoBerikutnya($mudharabah->tanggal, $angsuran_berjalan + 1);
            
            $bisa_bayar = true;
            if ($angsuran_berjalan >= $mudharabah->jml_angsuran) $bisa_bayar = false;
            if ($mudharabah->jml_angsuran <= 0) $bisa_bayar = false;
            
            log_message('debug', '  - Angsuran Berjalan: ' . $angsuran_berjalan);
            log_message('debug', '  - Bisa Bayar: ' . ($bisa_bayar ? 'YA ✅' : 'TIDAK ❌'));
            
            $pinjaman_data = [
                'id' => $mudharabah->id_md,
                'jenis' => 'mudharabah',
                'nama_pinjaman' => 'Pinjaman Mudharabah',
                'angsuran_berjalan' => $angsuran_berjalan,
                'tenor' => $mudharabah->jml_angsuran,
                'angsuran_per_bulan' => $angsuran_per_bulan,
                'total_pinjaman' => $mudharabah->jml_pinjam,
                'tanggal_pinjaman' => $mudharabah->tanggal,
                'jatuh_tempo_berikutnya' => $jatuh_tempo_berikutnya,
                'status' => $mudharabah->status,
                'total_terbayar' => floatval($mudharabah->jml_terbayar ?? 0),
                'bisa_bayar' => $bisa_bayar,
                'angsuran_selanjutnya' => $angsuran_berjalan + 1
            ];
            
            $pinjaman_aktif[] = (object)$pinjaman_data;
        }

        log_message('debug', '=== TOTAL SEMUA PINJAMAN AKTIF: ' . count($pinjaman_aktif) . ' ===');

    } catch (\Exception $e) {
        log_message('error', 'Error get pinjaman aktif: ' . $e->getMessage());
    }

    return $pinjaman_aktif;
}

    private function getAllPinjamanLunas($id_anggota)
{
    $pinjaman_lunas = [];

    try {
        log_message('debug', '=== MENGAMBIL PINJAMAN LUNAS DARI 3 TABEL ===');

        $db = \Config\Database::connect();
        
        // 1. Ambil dari tabel QARD yang lunas
        $qard_lunas = $db->table('qard')
                        ->where('id_anggota', $id_anggota)
                        ->where('status', 'lunas')
                        ->get()
                        ->getResult();
        log_message('debug', 'Data Qard lunas: ' . count($qard_lunas));
        
        foreach ($qard_lunas as $qard) {
            $pinjaman_data = [
                'id' => $qard->id_qard,
                'jenis' => 'Qard',
                'nama_pinjaman' => 'Pinjaman Qard',
                'tenor' => $qard->jml_angsuran,
                'angsuran_per_bulan' => $qard->jml_pinjam / max($qard->jml_angsuran, 1),
                'total_pinjaman' => $qard->jml_pinjam,
                'tanggal_lunas' => $qard->updated_at ?? $qard->tanggal,
                'status' => $qard->status
            ];
            $pinjaman_lunas[] = (object)$pinjaman_data;
        }

        // 2. Ambil dari tabel MURABAHAH yang lunas
        $murabahah_lunas = $db->table('murabahah')
                             ->where('id_anggota', $id_anggota)
                             ->where('status', 'lunas')
                             ->get()
                             ->getResult();
        log_message('debug', 'Data Murabahah lunas: ' . count($murabahah_lunas));
        
        foreach ($murabahah_lunas as $murabahah) {
            $pinjaman_data = [
                'id' => $murabahah->id_mr,
                'jenis' => 'Murabahah',
                'nama_pinjaman' => 'Pinjaman Murabahah',
                'tenor' => $murabahah->jml_angsuran,
                'angsuran_per_bulan' => $murabahah->jml_pinjam / max($murabahah->jml_angsuran, 1),
                'total_pinjaman' => $murabahah->jml_pinjam,
                'tanggal_lunas' => $murabahah->updated_at ?? $murabahah->tanggal,
                'status' => $murabahah->status
            ];
            $pinjaman_lunas[] = (object)$pinjaman_data;
        }

        // 3. Ambil dari tabel MUDHARABAH yang lunas
        $mudharabah_lunas = $db->table('mudharabah')
                              ->where('id_anggota', $id_anggota)
                              ->where('status', 'lunas')
                              ->get()
                              ->getResult();
        log_message('debug', 'Data Mudharabah lunas: ' . count($mudharabah_lunas));
        
        foreach ($mudharabah_lunas as $mudharabah) {
            $pinjaman_data = [
                'id' => $mudharabah->id_md,
                'jenis' => 'Mudharabah',
                'nama_pinjaman' => 'Pinjaman Mudharabah',
                'tenor' => $mudharabah->jml_angsuran,
                'angsuran_per_bulan' => $mudharabah->jml_pinjam / max($mudharabah->jml_angsuran, 1),
                'total_pinjaman' => $mudharabah->jml_pinjam,
                'tanggal_lunas' => $mudharabah->updated_at ?? $mudharabah->tanggal,
                'status' => $mudharabah->status
            ];
            $pinjaman_lunas[] = (object)$pinjaman_data;
        }

        log_message('debug', 'Total semua pinjaman lunas: ' . count($pinjaman_lunas));

    } catch (\Exception $e) {
        log_message('error', 'Error get pinjaman lunas: ' . $e->getMessage());
    }

    return $pinjaman_lunas;
}

  
private function hitungAngsuranBerjalanFix($total_terbayar, $total_pinjaman, $tenor)
{
    // Handle null values
    $total_terbayar = floatval($total_terbayar ?? 0);
    $total_pinjaman = floatval($total_pinjaman ?? 0);
    $tenor = intval($tenor ?? 0);
    
    log_message('debug', "hitungAngsuranBerjalanFix: Terbayar={$total_terbayar}, Pinjam={$total_pinjaman}, Tenor={$tenor}");
    
    // Jika tidak ada pinjaman atau tenor 0, return 0
    if ($total_pinjaman <= 0 || $tenor <= 0) {
        return 0;
    }
    
    // Hitung angsuran per bulan
    $angsuran_per_bulan = $total_pinjaman / $tenor;
    
    // Jika belum ada pembayaran, return 0
    if ($total_terbayar <= 0) {
        return 0;
    }
    
    // PERBAIKAN: Hitung angsuran berjalan dengan pembulatan yang lebih akurat
    $angsuran_berjalan = round($total_terbayar / $angsuran_per_bulan, 2);
    
    // Untuk keamanan, gunakan floor tapi dengan toleransi
    if (($angsuran_berjalan - floor($angsuran_berjalan)) > 0.9) {
        $angsuran_berjalan = ceil($angsuran_berjalan);
    } else {
        $angsuran_berjalan = floor($angsuran_berjalan);
    }
    
    // Pastikan tidak melebihi tenor
    $angsuran_berjalan = min($angsuran_berjalan, $tenor);
    
    log_message('debug', "hitungAngsuranBerjalanFix: AngsuranPerBulan={$angsuran_per_bulan}, Hasil={$angsuran_berjalan}");
    
    return intval($angsuran_berjalan);
}
    private function hitungJatuhTempoBerikutnya($tanggal_pinjaman, $angsuran_ke)
    {
        return date('Y-m-d', strtotime($tanggal_pinjaman . " +" . $angsuran_ke . " month"));
    }

    private function getSummaryCicilan($pinjaman_aktif)
{
    $total_pinjaman_aktif = count($pinjaman_aktif);
    $total_angsuran_bulanan = 0;
    $total_qard = 0;
    $total_murabahah = 0;
    $total_mudharabah = 0;
    $jatuh_tempo_terdekat = null;

    log_message('debug', '=== MENGHITUNG SUMMARY CICILAN ===');
    log_message('debug', 'Total pinjaman aktif: ' . $total_pinjaman_aktif);

    foreach ($pinjaman_aktif as $pinjaman) {
        log_message('debug', 'Processing: ' . $pinjaman->jenis . ' - ' . $pinjaman->nama_pinjaman . 
                            ', Bisa Bayar: ' . ($pinjaman->bisa_bayar ? 'Ya' : 'Tidak'));
        
        $total_angsuran_bulanan += $pinjaman->angsuran_per_bulan;
        
        // PERBAIKAN: Gunakan lowercase untuk konsistensi
        switch (strtolower($pinjaman->jenis)) {
            case 'qard': 
                $total_qard += $pinjaman->total_pinjaman; 
                break;
            case 'murabahah': 
                $total_murabahah += $pinjaman->total_pinjaman; 
                break;
            case 'mudharabah': 
                $total_mudharabah += $pinjaman->total_pinjaman; 
                break;
        }

        // Cari jatuh tempo terdekat yang belum lewat
        if ($pinjaman->bisa_bayar && !empty($pinjaman->jatuh_tempo_berikutnya)) {
            $today = date('Y-m-d');
            $jatuh_tempo = $pinjaman->jatuh_tempo_berikutnya;
            
            log_message('debug', 'Jatuh tempo: ' . $jatuh_tempo . ', Today: ' . $today);
            
            if (strtotime($jatuh_tempo) >= strtotime($today)) {
                if (!$jatuh_tempo_terdekat || strtotime($jatuh_tempo) < strtotime($jatuh_tempo_terdekat)) {
                    $jatuh_tempo_terdekat = $jatuh_tempo;
                    log_message('debug', 'Jatuh tempo terdekat diupdate: ' . $jatuh_tempo_terdekat);
                }
            }
        }
    }

    $summary = [
        'total_pinjaman_aktif' => $total_pinjaman_aktif,
        'total_angsuran_bulanan' => $total_angsuran_bulanan,
        'jatuh_tempo_terdekat' => $jatuh_tempo_terdekat,
        'total_qard' => $total_qard,
        'total_murabahah' => $total_murabahah,
        'total_mudharabah' => $total_mudharabah
    ];

    log_message('debug', 'Summary result: ' . print_r($summary, true));

    return $summary;
}
// Di file app/Controllers/Cicilan.php, tambahkan method:

public function riwayatCicilan()
{
    // Ambil data anggota dari session
    $id_anggota = session()->get('id') ?? session()->get('id_anggota') ?? 1;
    
    $db = \Config\Database::connect();
    
    // Ambil data anggota
    $anggota = $this->anggotaModel->find($id_anggota);
    
    if (!$anggota) {
        $anggota = [
            'id_anggota' => $id_anggota,
            'nomor_anggota' => 'ANG' . str_pad($id_anggota, 5, '0', STR_PAD_LEFT),
            'nama_lengkap' => 'Anggota Demo',
            'foto' => 'default.png'
        ];
    }

    // Ambil semua riwayat pembayaran (yang sudah diproses)
    $pembayaranPendingModel = new \App\Models\PembayaranPendingModel();
    
    $riwayat_pembayaran = $pembayaranPendingModel->where('id_anggota', $id_anggota)
                                                ->where('status !=', 'pending')
                                                ->orderBy('created_at', 'DESC')
                                                ->findAll();

    // Ambil juga pinjaman lunas untuk riwayat lengkap
    $pinjaman_lunas = $this->getAllPinjamanLunas($id_anggota);

    $data = [
        'title' => 'Riwayat Cicilan',
        'anggota' => $anggota,
        'riwayat_pembayaran' => $riwayat_pembayaran,
        'pinjaman_lunas' => $pinjaman_lunas
    ];

    return view('riwayat_cicilan', $data);
}
    private function hitungAngsuranPerBulan($total_pinjaman, $tenor)
{
    // Handle null values dan pastikan nilai numerik
    $total_pinjaman = floatval($total_pinjaman ?? 0);
    $tenor = intval($tenor ?? 0);
    
    // Validasi untuk menghindari division by zero
    if ($tenor <= 0 || $total_pinjaman <= 0) {
        log_message('debug', "hitungAngsuranPerBulan: Invalid input - Pinjam: {$total_pinjaman}, Tenor: {$tenor}");
        return 0;
    }
    
    $angsuran_per_bulan = $total_pinjaman / $tenor;
    
    log_message('debug', "hitungAngsuranPerBulan: Pinjam={$total_pinjaman}, Tenor={$tenor}, Hasil={$angsuran_per_bulan}");
    
    return $angsuran_per_bulan;
}

   public function bayarCicilan()
{
    try {
        log_message('debug', '=== MEMULAI PROSES BAYAR CICILAN ===');
        
        $id_anggota = session()->get('id_anggota') ?? 1;
        log_message('debug', 'ID Anggota: ' . $id_anggota);
        
        // Get POST data untuk debugging
        $postData = $this->request->getPost();
        log_message('debug', 'POST Data: ' . print_r($postData, true));
        
        // Validasi input
        $validation = \Config\Services::validation();
        $validation->setRules([
            'jenis_pinjaman' => 'required',
            'id_pinjaman' => 'required|numeric',
            'angsuran_ke' => 'required|numeric|greater_than[0]',
            'jumlah_bayar' => 'required',
            'bukti_bayar' => 'uploaded[bukti_bayar]|max_size[bukti_bayar,2048]|mime_in[bukti_bayar,image/jpg,image/jpeg,image/png,application/pdf]'
        ]);

        // Format jumlah_bayar
        $jumlah_bayar_input = $this->request->getPost('jumlah_bayar');
        $jumlah_bayar = $this->formatAngkaToFloat($jumlah_bayar_input);
        
        log_message('debug', 'Jumlah bayar input: ' . $jumlah_bayar_input . ', Formatted: ' . $jumlah_bayar);

        // Validasi manual untuk jumlah bayar
        if ($jumlah_bayar <= 0) {
            log_message('debug', 'Jumlah bayar invalid: ' . $jumlah_bayar);
            return $this->response->setJSON([
                'status' => 'error', 
                'message' => 'Jumlah bayar harus lebih dari 0'
            ])->setStatusCode(400);
        }

        if (!$validation->withRequest($this->request)->run()) {
            $errors = $validation->getErrors();
            log_message('debug', 'Validation errors: ' . print_r($errors, true));
            
            return $this->response->setJSON([
                'status' => 'error', 
                'message' => 'Validasi gagal: ' . implode(', ', $errors)
            ])->setStatusCode(400);
        }

        // Check file upload
        $buktiFile = $this->request->getFile('bukti_bayar');
        if (!$buktiFile || !$buktiFile->isValid()) {
            log_message('debug', 'File upload invalid');
            return $this->response->setJSON([
                'status' => 'error', 
                'message' => 'Bukti bayar tidak valid'
            ])->setStatusCode(400);
        }

        $data = [
            'id_anggota' => $id_anggota,
            'jenis_pinjaman' => $this->request->getPost('jenis_pinjaman'),
            'id_pinjaman' => $this->request->getPost('id_pinjaman'),
            'angsuran_ke' => $this->request->getPost('angsuran_ke'),
            'jumlah_bayar' => $jumlah_bayar,
            'tanggal_bayar' => date('Y-m-d H:i:s'),
            'status' => 'pending',
            'created_at' => date('Y-m-d H:i:s')
        ];

        log_message('debug', 'Data pembayaran: ' . print_r($data, true));

        // Handle upload bukti bayar
        $uploadPath = ROOTPATH . 'public/uploads/bukti_bayar';
        if (!is_dir($uploadPath)) {
            mkdir($uploadPath, 0755, true);
        }

        $newName = $buktiFile->getRandomName();
        log_message('debug', 'Uploading file to: ' . $uploadPath . '/' . $newName);
        
        if ($buktiFile->move($uploadPath, $newName)) {
            $data['bukti_bayar'] = $newName;
            log_message('debug', 'File uploaded successfully: ' . $newName);
        } else {
            log_message('error', 'File upload failed: ' . $buktiFile->getErrorString());
            return $this->response->setJSON([
                'status' => 'error', 
                'message' => 'Gagal mengupload bukti bayar: ' . $buktiFile->getErrorString()
            ])->setStatusCode(500);
        }

        // Simpan ke database
        $db = \Config\Database::connect();
        $db->transStart();
        
        try {
            $pembayaranPendingModel = new \App\Models\PembayaranPendingModel();
            $saved = $pembayaranPendingModel->insert($data);
            
            log_message('debug', 'Save result: ' . ($saved ? 'Success' : 'Failed'));
            
            if ($saved) {
                $db->transCommit();
                log_message('debug', 'Payment saved successfully, ID: ' . $pembayaranPendingModel->getInsertID());
                
                return $this->response->setJSON([
                    'status' => 'success', 
                    'message' => 'Pembayaran berhasil diajukan! Menunggu verifikasi admin.'
                ])->setStatusCode(200);
            } else {
                throw new \Exception('Failed to save payment data');
            }
            
        } catch (\Exception $e) {
            $db->transRollback();
            
            // Hapus file yang sudah diupload jika gagal simpan
            if (isset($data['bukti_bayar'])) {
                @unlink($uploadPath . '/' . $data['bukti_bayar']);
                log_message('debug', 'Deleted uploaded file due to error');
            }
            
            log_message('error', 'Error saving payment: ' . $e->getMessage());
            return $this->response->setJSON([
                'status' => 'error', 
                'message' => 'Gagal menyimpan data pembayaran: ' . $e->getMessage()
            ])->setStatusCode(500);
        }

    } catch (\Exception $e) {
        log_message('error', 'Error in bayarCicilan: ' . $e->getMessage());
        log_message('error', 'Stack trace: ' . $e->getTraceAsString());
        
        return $this->response->setJSON([
            'status' => 'error', 
            'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
        ])->setStatusCode(500);
    }
}

private function formatAngkaToFloat($angka)
{
    if (is_numeric($angka)) {
        return floatval($angka);
    }
    
    // Hapus karakter non-digit kecuali titik dan koma
    $angka = preg_replace('/[^\d.,]/', '', $angka);
    
    // Jika ada titik sebagai pemisah ribuan dan koma sebagai desimal
    if (strpos($angka, '.') !== false && strpos($angka, ',') !== false) {
        // Format: 1.000,00 -> 1000.00
        $angka = str_replace('.', '', $angka);
        $angka = str_replace(',', '.', $angka);
    } 
    // Jika hanya koma sebagai pemisah
    elseif (strpos($angka, ',') !== false) {
        // Format: 1000,00 -> 1000.00
        $angka = str_replace(',', '.', $angka);
    }
    // Jika hanya titik sebagai pemisah ribuan
    else {
        // Format: 1.000 -> 1000
        $angka = str_replace('.', '', $angka);
    }
    
    return floatval($angka);
}
    // Method untuk admin
    public function verifikasiPembayaran($id_pembayaran)
    {
        try {
            if ($this->request->getMethod() !== 'post') {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Method tidak diizinkan'
                ]);
            }

            $pembayaran = $this->pembayaranPendingModel->find($id_pembayaran);
            
            if (!$pembayaran) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Data pembayaran tidak ditemukan'
                ]);
            }

            // Update status pembayaran
            $this->pembayaranPendingModel->verifikasiPembayaran($id_pembayaran);

            // Update jumlah terbayar di tabel pinjaman
            $this->updatePinjamanTerbayar(
                $pembayaran['jenis_pinjaman'],
                $pembayaran['id_pinjaman'],
                $pembayaran['jumlah_bayar']
            );

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Pembayaran berhasil diverifikasi'
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error verifikasiPembayaran: ' . $e->getMessage());
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ]);
        }
    }

    public function tolakPembayaran($id_pembayaran)
    {
        try {
            if ($this->request->getMethod() !== 'post') {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Method tidak diizinkan'
                ]);
            }

            $alasan = $this->request->getPost('alasan');

            $this->pembayaranPendingModel->tolakPembayaran($id_pembayaran, $alasan);

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Pembayaran berhasil ditolak'
            ]);

        } catch (\Exception $e) {
            log_message('error', 'Error tolakPembayaran: ' . $e->getMessage());
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ]);
        }
    }

    private function updatePinjamanTerbayar($jenis, $id_pinjaman, $jumlah_bayar)
    {
        switch ($jenis) {
            case 'Qard':
                $this->qardModel->updateTerbayar($id_pinjaman, $jumlah_bayar);
                break;
            case 'Murabahah':
                $this->murabahahModel->updateTerbayar($id_pinjaman, $jumlah_bayar);
                break;
            case 'Mudharabah':
                $this->mudharabahModel->updateTerbayar($id_pinjaman, $jumlah_bayar);
                break;
        }
    }

    // Data dummy untuk testing
    private function getDataDummyAktif()
    {
        return [
            (object)[
                'id' => 1,
                'jenis' => 'Qard',
                'nama_pinjaman' => 'Pinjaman Qard',
                'angsuran_berjalan' => 3,
                'tenor' => 12,
                'angsuran_per_bulan' => 850000,
                'total_pinjaman' => 10200000,
                'tanggal_pinjaman' => '2024-01-15',
                'jatuh_tempo_berikutnya' => '2024-05-15',
                'status' => 'aktif',
                'total_terbayar' => 2550000,
                'bisa_bayar' => true
            ]
        ];
    }

    private function getDataDummyLunas()
    {
        return [
            (object)[
                'id' => 2,
                'jenis' => 'Murabahah',
                'nama_pinjaman' => 'Pinjaman Murabahah',
                'tenor' => 6,
                'angsuran_per_bulan' => 650000,
                'total_pinjaman' => 3900000,
                'tanggal_lunas' => '2024-06-20',
                'status' => 'lunas'
            ]
        ];
    }
}