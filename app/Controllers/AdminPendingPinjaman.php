<?php
namespace App\Controllers;

class AdminPendingPinjaman extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();

        // ========== QUERY UTAMA DENGAN ERROR HANDLING ==========
        try {
            // Gabungkan semua pinjaman pending dengan INFORMASI REKENING
            $pendingQard = $db->table('qard')
                ->join('anggota', 'anggota.id = qard.id_anggota')
                ->select('qard.id_qard as id, "qard" as jenis, anggota.nama_lengkap, anggota.nomor_anggota, 
                         qard.jml_pinjam, qard.jml_angsuran, qard.tanggal, qard.status,
                         anggota.jenis_bank, anggota.no_rek, anggota.atasnama_rekening, anggota.no_hp')
                ->where('qard.status', 'pending')
                ->get()->getResultArray();
        } catch (\Exception $e) {
            // Jika join error, coba alternatif
            $pendingQard = [];
            $qards = $db->table('qard')->where('status', 'pending')->get()->getResultArray();
            
            foreach ($qards as $qard) {
                $anggota = $db->table('anggota')
                    ->where('id', $qard['id_anggota'])
                    ->get()->getRowArray();
                
                if ($anggota) {
                    $pendingQard[] = [
                        'id' => $qard['id_qard'],
                        'jenis' => 'qard',
                        'nama_lengkap' => $anggota['nama_lengkap'] ?? 'N/A',
                        'nomor_anggota' => $anggota['nomor_anggota'] ?? 'N/A',
                        'jml_pinjam' => $qard['jml_pinjam'] ?? 0,
                        'jml_angsuran' => $qard['jml_angsuran'] ?? 0,
                        'tanggal' => $qard['tanggal'] ?? date('Y-m-d'),
                        'status' => $qard['status'] ?? 'pending',
                        'jenis_bank' => $anggota['jenis_bank'] ?? null,
                        'no_rek' => $anggota['no_rek'] ?? null,
                        'atasnama_rekening' => $anggota['atasnama_rekening'] ?? null,
                        'no_hp' => $anggota['no_hp'] ?? null
                    ];
                }
            }
        }

        try {
            $pendingMurabahah = $db->table('murabahah')
                ->join('anggota', 'anggota.id = murabahah.id_anggota')
                ->select('murabahah.id_mr as id, "murabahah" as jenis, anggota.nama_lengkap, anggota.nomor_anggota, 
                         murabahah.jml_pinjam, murabahah.jml_angsuran, murabahah.tanggal, murabahah.status,
                         anggota.jenis_bank, anggota.no_rek, anggota.atasnama_rekening, anggota.no_hp')
                ->where('murabahah.status', 'pending')
                ->get()->getResultArray();
        } catch (\Exception $e) {
            $pendingMurabahah = [];
            $murabahahs = $db->table('murabahah')->where('status', 'pending')->get()->getResultArray();
            
            foreach ($murabahahs as $murabahah) {
                $anggota = $db->table('anggota')
                    ->where('id', $murabahah['id_anggota'])
                    ->get()->getRowArray();
                
                if ($anggota) {
                    $pendingMurabahah[] = [
                        'id' => $murabahah['id_mr'],
                        'jenis' => 'murabahah',
                        'nama_lengkap' => $anggota['nama_lengkap'] ?? 'N/A',
                        'nomor_anggota' => $anggota['nomor_anggota'] ?? 'N/A',
                        'jml_pinjam' => $murabahah['jml_pinjam'] ?? 0,
                        'jml_angsuran' => $murabahah['jml_angsuran'] ?? 0,
                        'tanggal' => $murabahah['tanggal'] ?? date('Y-m-d'),
                        'status' => $murabahah['status'] ?? 'pending',
                        'jenis_bank' => $anggota['jenis_bank'] ?? null,
                        'no_rek' => $anggota['no_rek'] ?? null,
                        'atasnama_rekening' => $anggota['atasnama_rekening'] ?? null,
                        'no_hp' => $anggota['no_hp'] ?? null
                    ];
                }
            }
        }

        try {
            $pendingMudharabah = $db->table('mudharabah')
                ->join('anggota', 'anggota.id = mudharabah.id_anggota')
                ->select('mudharabah.id_md as id, "mudharabah" as jenis, anggota.nama_lengkap, anggota.nomor_anggota, 
                         mudharabah.jml_pinjam, mudharabah.jml_angsuran, mudharabah.tanggal, mudharabah.status,
                         anggota.jenis_bank, anggota.no_rek, anggota.atasnama_rekening, anggota.no_hp')
                ->where('mudharabah.status', 'pending')
                ->get()->getResultArray();
        } catch (\Exception $e) {
            $pendingMudharabah = [];
            $mudharabahs = $db->table('mudharabah')->where('status', 'pending')->get()->getResultArray();
            
            foreach ($mudharabahs as $mudharabah) {
                $anggota = $db->table('anggota')
                    ->where('id', $mudharabah['id_anggota'])
                    ->get()->getRowArray();
                
                if ($anggota) {
                    $pendingMudharabah[] = [
                        'id' => $mudharabah['id_md'],
                        'jenis' => 'mudharabah',
                        'nama_lengkap' => $anggota['nama_lengkap'] ?? 'N/A',
                        'nomor_anggota' => $anggota['nomor_anggota'] ?? 'N/A',
                        'jml_pinjam' => $mudharabah['jml_pinjam'] ?? 0,
                        'jml_angsuran' => $mudharabah['jml_angsuran'] ?? 0,
                        'tanggal' => $mudharabah['tanggal'] ?? date('Y-m-d'),
                        'status' => $mudharabah['status'] ?? 'pending',
                        'jenis_bank' => $anggota['jenis_bank'] ?? null,
                        'no_rek' => $anggota['no_rek'] ?? null,
                        'atasnama_rekening' => $anggota['atasnama_rekening'] ?? null,
                        'no_hp' => $anggota['no_hp'] ?? null
                    ];
                }
            }
        }

        // Gabungkan semua data
        $pending = array_merge($pendingQard, $pendingMurabahah, $pendingMudharabah);

        // Hitung total pending
        $pendingQardCount = $db->table('qard')->where('status', 'pending')->countAllResults();
        $pendingMurabahahCount = $db->table('murabahah')->where('status', 'pending')->countAllResults();
        $pendingMudharabahCount = $db->table('mudharabah')->where('status', 'pending')->countAllResults();
        $pendingPinjamanCount = $pendingQardCount + $pendingMurabahahCount + $pendingMudharabahCount;

        return view('dashboard_admin/pending_pinjaman', [
            'pending' => $pending,
            'pendingPinjamanCount' => $pendingPinjamanCount,
        ]);
    }

    public function verifikasi($jenis, $id)
    {
        $db = \Config\Database::connect();
        
        // Ambil data rekening sebelum verifikasi untuk konfirmasi
        $anggotaData = [];
        
        if ($jenis == 'qard') {
            $pinjaman = $db->table('qard')
                ->join('anggota', 'anggota.id = qard.id_anggota')
                ->select('qard.*, anggota.nama_lengkap, anggota.jenis_bank, anggota.no_rek, anggota.no_hp')
                ->where('qard.id_qard', $id)
                ->get()->getRowArray();
            $anggotaData = $pinjaman;
        } elseif ($jenis == 'murabahah') {
            $pinjaman = $db->table('murabahah')
                ->join('anggota', 'anggota.id = murabahah.id_anggota')
                ->select('murabahah.*, anggota.nama_lengkap, anggota.jenis_bank, anggota.no_rek, anggota.no_hp')
                ->where('murabahah.id_mr', $id)
                ->get()->getRowArray();
            $anggotaData = $pinjaman;
        } elseif ($jenis == 'mudharabah') {
            $pinjaman = $db->table('mudharabah')
                ->join('anggota', 'anggota.id = mudharabah.id_anggota')
                ->select('mudharabah.*, anggota.nama_lengkap, anggota.jenis_bank, anggota.no_rek, anggota.no_hp')
                ->where('mudharabah.id_md', $id)
                ->get()->getRowArray();
            $anggotaData = $pinjaman;
        }
        
        // Update status pinjaman
        if ($jenis == 'qard') {
            $db->table('qard')->where('id_qard', $id)->update(['status' => 'aktif']);
        } elseif ($jenis == 'murabahah') {
            $db->table('murabahah')->where('id_mr', $id)->update(['status' => 'aktif']);
        } elseif ($jenis == 'mudharabah') {
            $db->table('mudharabah')->where('id_md', $id)->update(['status' => 'aktif']);
        }
        
        // Tambahkan pesan sukses dengan informasi rekening
        $bankInfo = '';
        if (!empty($anggotaData['jenis_bank']) && !empty($anggotaData['no_rek'])) {
            $bankInfo = " Dana akan ditransfer ke {$anggotaData['jenis_bank']} - {$anggotaData['no_rek']}";
        }
        
        return redirect()->back()->with('success', 'Pinjaman berhasil diverifikasi!' . $bankInfo);
    }

    // Method baru untuk detail pinjaman (AJAX)
    public function detail($jenis, $id)
    {
        $db = \Config\Database::connect();
        $data = [];
        
        if ($jenis == 'qard') {
            try {
                $data = $db->table('qard')
                    ->join('anggota', 'anggota.id = qard.id_anggota')
                    ->select('qard.*, anggota.nama_lengkap, anggota.nomor_anggota, 
                             anggota.jenis_bank, anggota.no_rek, anggota.atasnama_rekening, 
                             anggota.no_hp, anggota.email')
                    ->where('qard.id_qard', $id)
                    ->get()->getRowArray();
            } catch (\Exception $e) {
                $qard = $db->table('qard')->where('id_qard', $id)->get()->getRowArray();
                if ($qard) {
                    $anggota = $db->table('anggota')->where('id', $qard['id_anggota'])->get()->getRowArray();
                    $data = array_merge($qard, $anggota ?: []);
                }
            }
        } elseif ($jenis == 'murabahah') {
            try {
                $data = $db->table('murabahah')
                    ->join('anggota', 'anggota.id = murabahah.id_anggota')
                    ->select('murabahah.*, anggota.nama_lengkap, anggota.nomor_anggota, 
                             anggota.jenis_bank, anggota.no_rek, anggota.atasnama_rekening, 
                             anggota.no_hp, anggota.email')
                    ->where('murabahah.id_mr', $id)
                    ->get()->getRowArray();
            } catch (\Exception $e) {
                $murabahah = $db->table('murabahah')->where('id_mr', $id)->get()->getRowArray();
                if ($murabahah) {
                    $anggota = $db->table('anggota')->where('id', $murabahah['id_anggota'])->get()->getRowArray();
                    $data = array_merge($murabahah, $anggota ?: []);
                }
            }
        } elseif ($jenis == 'mudharabah') {
            try {
                $data = $db->table('mudharabah')
                    ->join('anggota', 'anggota.id = mudharabah.id_anggota')
                    ->select('mudharabah.*, anggota.nama_lengkap, anggota.nomor_anggota, 
                             anggota.jenis_bank, anggota.no_rek, anggota.atasnama_rekening, 
                             anggota.no_hp, anggota.email')
                    ->where('mudharabah.id_md', $id)
                    ->get()->getRowArray();
            } catch (\Exception $e) {
                $mudharabah = $db->table('mudharabah')->where('id_md', $id)->get()->getRowArray();
                if ($mudharabah) {
                    $anggota = $db->table('anggota')->where('id', $mudharabah['id_anggota'])->get()->getRowArray();
                    $data = array_merge($mudharabah, $anggota ?: []);
                }
            }
        }
        
        if ($data) {
            return $this->response->setJSON([
                'success' => true,
                'data' => $data
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Data pinjaman tidak ditemukan'
            ]);
        }
    }

    // Tambahkan method untuk tolak pinjaman
    public function tolak($jenis, $id)
    {
        $db = \Config\Database::connect();
        
        if ($jenis == 'qard') {
            $db->table('qard')->where('id_qard', $id)->update(['status' => 'ditolak']);
        } elseif ($jenis == 'murabahah') {
            $db->table('murabahah')->where('id_mr', $id)->update(['status' => 'ditolak']);
        } elseif ($jenis == 'mudharabah') {
            $db->table('mudharabah')->where('id_md', $id)->update(['status' => 'ditolak']);
        }
        
        return redirect()->back()->with('success', 'Pinjaman berhasil ditolak!');
    }
}