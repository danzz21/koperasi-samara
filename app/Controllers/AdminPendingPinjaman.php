<?php
namespace App\Controllers;

class AdminPendingPinjaman extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();

        // Query untuk semua jenis pinjaman dengan join ke anggota
        // QARD
        $pendingQard = $db->table('qard')
            ->join('anggota', 'anggota.id_anggota = qard.id_anggota')
            ->select('qard.*, 
                     anggota.nama_lengkap, 
                     anggota.nomor_anggota, 
                     anggota.jenis_bank, 
                     anggota.no_rek, 
                     anggota.atasnama_rekening, 
                     anggota.no_hp, 
                     anggota.email, 
                     anggota.alamat')
            ->where('qard.status', 'pending')
            ->get()->getResultArray();

        // MURABAHAH
        $pendingMurabahah = $db->table('murabahah')
            ->join('anggota', 'anggota.id_anggota = murabahah.id_anggota')
            ->select('murabahah.*, 
                     anggota.nama_lengkap, 
                     anggota.nomor_anggota, 
                     anggota.jenis_bank, 
                     anggota.no_rek, 
                     anggota.atasnama_rekening, 
                     anggota.no_hp, 
                     anggota.email, 
                     anggota.alamat')
            ->where('murabahah.status', 'pending')
            ->get()->getResultArray();

        // MUDHARABAH
        $pendingMudharabah = $db->table('mudharabah')
            ->join('anggota', 'anggota.id_anggota = mudharabah.id_anggota')
            ->select('mudharabah.*, 
                     anggota.nama_lengkap, 
                     anggota.nomor_anggota, 
                     anggota.jenis_bank, 
                     anggota.no_rek, 
                     anggota.atasnama_rekening, 
                     anggota.no_hp, 
                     anggota.email, 
                     anggota.alamat')
            ->where('mudharabah.status', 'pending')
            ->get()->getResultArray();

        // Gabungkan semua data
        $pending = array_merge($pendingQard, $pendingMurabahah, $pendingMudharabah);

        // Format data untuk view
        foreach ($pending as &$item) {
            // Set ID dan jenis berdasarkan tabel asal
            if (isset($item['id_qard'])) {
                $item['id'] = $item['id_qard'];
                $item['jenis'] = 'qard';
            } elseif (isset($item['id_mr'])) {
                $item['id'] = $item['id_mr'];
                $item['jenis'] = 'murabahah';
            } elseif (isset($item['id_md'])) {
                $item['id'] = $item['id_md'];
                $item['jenis'] = 'mudharabah';
            }
            
            // Set default values
            $item['jml_pinjam'] = $item['jml_pinjam'] ?? 0;
            $item['jml_angsuran'] = $item['jml_angsuran'] ?? 0;
            $item['tanggal'] = $item['tanggal'] ?? date('Y-m-d');
            $item['status'] = $item['status'] ?? 'pending';
        }

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

    public function detail($jenis, $id)
    {
        $db = \Config\Database::connect();
        $data = [];
        
        if ($jenis == 'qard') {
            $data = $db->table('qard')
                ->join('anggota', 'anggota.id_anggota = qard.id_anggota')
                ->select('qard.*, anggota.nama_lengkap, anggota.nomor_anggota, 
                         anggota.jenis_bank, anggota.no_rek, anggota.atasnama_rekening, 
                         anggota.no_hp, anggota.email, anggota.alamat')
                ->where('qard.id_qard', $id)
                ->get()->getRowArray();
        } elseif ($jenis == 'murabahah') {
            $data = $db->table('murabahah')
                ->join('anggota', 'anggota.id_anggota = murabahah.id_anggota')
                ->select('murabahah.*, anggota.nama_lengkap, anggota.nomor_anggota, 
                         anggota.jenis_bank, anggota.no_rek, anggota.atasnama_rekening, 
                         anggota.no_hp, anggota.email, anggota.alamat')
                ->where('murabahah.id_mr', $id)
                ->get()->getRowArray();
        } elseif ($jenis == 'mudharabah') {
            $data = $db->table('mudharabah')
                ->join('anggota', 'anggota.id_anggota = mudharabah.id_anggota')
                ->select('mudharabah.*, anggota.nama_lengkap, anggota.nomor_anggota, 
                         anggota.jenis_bank, anggota.no_rek, anggota.atasnama_rekening, 
                         anggota.no_hp, anggota.email, anggota.alamat')
                ->where('mudharabah.id_md', $id)
                ->get()->getRowArray();
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

    public function verifikasi($jenis, $id)
    {
        $db = \Config\Database::connect();
        
        // Ambil data sebelum verifikasi untuk pesan konfirmasi
        $anggotaData = [];
        
        if ($jenis == 'qard') {
            $pinjaman = $db->table('qard')
                ->join('anggota', 'anggota.id_anggota = qard.id_anggota')
                ->select('qard.*, anggota.nama_lengkap, anggota.jenis_bank, anggota.no_rek, anggota.no_hp')
                ->where('qard.id_qard', $id)
                ->get()->getRowArray();
            $anggotaData = $pinjaman;
            
            // Update status
            $db->table('qard')->where('id_qard', $id)->update(['status' => 'aktif']);
        } elseif ($jenis == 'murabahah') {
            $pinjaman = $db->table('murabahah')
                ->join('anggota', 'anggota.id_anggota = murabahah.id_anggota')
                ->select('murabahah.*, anggota.nama_lengkap, anggota.jenis_bank, anggota.no_rek, anggota.no_hp')
                ->where('murabahah.id_mr', $id)
                ->get()->getRowArray();
            $anggotaData = $pinjaman;
            
            // Update status
            $db->table('murabahah')->where('id_mr', $id)->update(['status' => 'aktif']);
        } elseif ($jenis == 'mudharabah') {
            $pinjaman = $db->table('mudharabah')
                ->join('anggota', 'anggota.id_anggota = mudharabah.id_anggota')
                ->select('mudharabah.*, anggota.nama_lengkap, anggota.jenis_bank, anggota.no_rek, anggota.no_hp')
                ->where('mudharabah.id_md', $id)
                ->get()->getRowArray();
            $anggotaData = $pinjaman;
            
            // Update status
            $db->table('mudharabah')->where('id_md', $id)->update(['status' => 'aktif']);
        }
        
        // Buat pesan sukses dengan info bank jika ada
        $bankInfo = '';
        if (!empty($anggotaData['jenis_bank']) && !empty($anggotaData['no_rek'])) {
            $bankInfo = " Dana akan ditransfer ke {$anggotaData['jenis_bank']} - {$anggotaData['no_rek']}";
        }
        
        return redirect()->back()->with('success', 'Pinjaman berhasil diverifikasi!' . $bankInfo);
    }

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