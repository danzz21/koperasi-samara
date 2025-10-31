<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\AnggotaModel;

class Pinjaman extends BaseController
{
    public function index()
    {
        $session = session();
        $id_user = $session->get('id'); // ID dari session (users table)
        $db = \Config\Database::connect();
        
        // Ambil data dari kedua tabel
        $userModel = new UserModel();
        $anggotaModel = new AnggotaModel();
        
        $user = $userModel->find($id_user);
        
        // Cari data anggota berdasarkan id_anggota (asumsi id_anggota sama dengan id_user)
        // Atau jika ada relasi yang berbeda, sesuaikan di sini
        $anggota = $anggotaModel->find($id_user); // Asumsi id_anggota = id_user

        $nama = $user['nama_lengkap'] ?? '-';
        $nomor_anggota = $anggota['nomor_anggota'] ?? $user['id'] ?? '-';

        // Cek apakah anggota sudah mengisi nomor rekening (dari table anggota)
        $hasNoRekening = !empty($anggota['no_rek']);

        // Cek apakah ada pinjaman aktif (hanya status 'aktif' yang menghalangi)
        $hasActiveLoan = $this->hasActiveLoan($id_user);

        // Cek jumlah pinjaman pending
        $pendingLoansCount = $this->getPendingLoans($id_user);

        // Cek tenor simpanan pokok anggota
        $tenorData = $db->table('simpanan_pokok')
            ->select('tenor')
            ->where('id_anggota', $id_user)
            ->get()
            ->getRowArray();

        $showTenorModal = false;
        if (!$tenorData || $tenorData['tenor'] === null) {
            $showTenorModal = true;
        }

        return view('pinjaman', [
            'nama' => $nama,
            'nomor_anggota' => $nomor_anggota,
            'user' => $user,
            'anggota' => $anggota,
            'hasActiveLoan' => $hasActiveLoan,
            'hasNoRekening' => $hasNoRekening,
            'pendingLoansCount' => $pendingLoansCount,
            'showTenorModal'=> $showTenorModal,
        ]);
    }

    public function ajukan()
    {
        $id_user = session()->get('id');

        // Cek apakah anggota sudah mengisi nomor rekening - PERBAIKI INI
        $anggotaModel = new AnggotaModel();
        $anggota = $anggotaModel->find($id_user); // Gunakan find() dengan id_anggota
        
        if (empty($anggota) || empty($anggota['no_rek'])) {
            return redirect()->back()->withInput()->with('error', 'Anda belum mengisi nomor rekening. Harap lengkapi data rekening di menu Profil terlebih dahulu sebelum mengajukan pinjaman.');
        }

        // Cek apakah sudah ada pinjaman AKTIF (hanya status 'aktif' yang menghalangi)
        if ($this->hasActiveLoan($id_user)) {
            return redirect()->back()->with('error', 'Anda sudah memiliki pinjaman yang aktif. Silakan selesaikan pinjaman terlebih dahulu sebelum mengajukan pinjaman baru.');
        }

        // Validasi input
        $validation = \Config\Services::validation();
        $validation->setRules([
            'jenis' => 'required|in_list[qard,murabahah,mudharabah]',
            'jumlah' => 'required',
            'lama_cicilan' => 'required|numeric|greater_than[0]|less_than_equal_to[12]',
            'deskripsi' => 'required|min_length[10]|max_length[500]'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $jenis = $this->request->getPost('jenis');
        $jumlah = preg_replace('/[^0-9]/', '', $this->request->getPost('jumlah'));
        $lama_cicilan = $this->request->getPost('lama_cicilan');
        $deskripsi = $this->request->getPost('deskripsi');
        $tanggal = date('Y-m-d');
        $status = 'pending'; // Status awal selalu pending

        // Validasi jumlah setelah di-parse
        if ($jumlah == '' || $jumlah == 0) {
            return redirect()->back()->withInput()->with('error', 'Nominal pinjaman tidak valid');
        }

        // Validasi maksimal pinjaman
        if ($jumlah > 4000000) {
            return redirect()->back()->withInput()->with('error', 'Nominal pinjaman melebihi batas maksimum Rp 4.000.000');
        }

        // Validasi minimal pinjaman
        if ($jumlah < 100000) {
            return redirect()->back()->withInput()->with('error', 'Nominal pinjaman minimal Rp 100.000');
        }

        $db = \Config\Database::connect();

        try {
            if ($jenis == 'qard') {
                $db->table('qard')->insert([
                    'id_anggota' => $id_user,
                    'jml_pinjam' => $jumlah,
                    'jml_angsuran' => $lama_cicilan,
                    'deskripsi' => $deskripsi,
                    'tanggal' => $tanggal,
                    'status' => $status
                ]);
            } elseif ($jenis == 'murabahah') {
                $db->table('murabahah')->insert([
                    'id_anggota' => $id_user,
                    'jml_pinjam' => $jumlah,
                    'jml_angsuran' => $lama_cicilan,
                    'deskripsi' => $deskripsi,
                    'tanggal' => $tanggal,
                    'status' => $status
                ]);
            } elseif ($jenis == 'mudharabah') {
                $db->table('mudharabah')->insert([
                    'id_anggota' => $id_user,
                    'jml_pinjam' => $jumlah,
                    'jml_angsuran' => $lama_cicilan,
                    'deskripsi' => $deskripsi,
                    'tanggal' => $tanggal,
                    'status' => $status
                ]);
            }

            return redirect()->back()->with('success', 'Pengajuan pinjaman berhasil, menunggu verifikasi admin.');

        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function checkNoRekening()
    {
        $id_user = session()->get('id');
        $anggotaModel = new AnggotaModel();
        $anggota = $anggotaModel->find($id_user); // Gunakan find()

        return $this->response->setJSON([
            'hasNoRekening' => !empty($anggota['no_rek']),
            'no_rek' => $anggota['no_rek'] ?? null
        ]);
    }

    public function validateBeforeSubmit()
    {
        $id_user = session()->get('id');
        
        // Cek nomor rekening dari table anggota
        $anggotaModel = new AnggotaModel();
        $anggota = $anggotaModel->find($id_user);
        $hasNoRekening = !empty($anggota['no_rek']);
        
        // Cek pinjaman aktif (hanya status 'aktif')
        $hasActiveLoan = $this->hasActiveLoan($id_user);
        
        return $this->response->setJSON([
            'success' => true,
            'hasNoRekening' => $hasNoRekening,
            'hasActiveLoan' => $hasActiveLoan,
            'canSubmit' => $hasNoRekening && !$hasActiveLoan,
            'messages' => [
                !$hasNoRekening ? 'Nomor rekening belum diisi' : '',
                $hasActiveLoan ? 'Ada pinjaman aktif' : ''
            ]
        ]);
    }

    /**
     * Cek apakah ada pinjaman aktif
     * Hanya status 'aktif' yang menghalangi pengajuan baru
     */
    private function hasActiveLoan($id_anggota)
    {
        $db = \Config\Database::connect();
        
        // Hanya cek status 'aktif' saja
        $qard = $db->table('qard')
            ->where('id_anggota', $id_anggota)
            ->where('status', 'aktif') // Hanya status aktif
            ->countAllResults();
        
        $murabahah = $db->table('murabahah')
            ->where('id_anggota', $id_anggota)
            ->where('status', 'aktif') // Hanya status aktif
            ->countAllResults();
        
        $mudharabah = $db->table('mudharabah')
            ->where('id_anggota', $id_anggota)
            ->where('status', 'aktif') // Hanya status aktif
            ->countAllResults();

        return ($qard > 0 || $murabahah > 0 || $mudharabah > 0);
    }

    /**
     * Cek total pinjaman pending (untuk info saja)
     */
    private function getPendingLoans($id_anggota)
    {
        $db = \Config\Database::connect();
        
        $qard = $db->table('qard')
            ->where('id_anggota', $id_anggota)
            ->where('status', 'pending')
            ->countAllResults();
        
        $murabahah = $db->table('murabahah')
            ->where('id_anggota', $id_anggota)
            ->where('status', 'pending')
            ->countAllResults();
        
        $mudharabah = $db->table('mudharabah')
            ->where('id_anggota', $id_anggota)
            ->where('status', 'pending')
            ->countAllResults();

        return ($qard + $murabahah + $mudharabah);
    }

    // Method untuk mendapatkan data pinjaman aktif
    public function getActiveLoan()
    {
        $id_user = session()->get('id');
        $db = \Config\Database::connect();
        
        $activeLoans = [];
        
        // Cek di semua tabel pinjaman - hanya yang status 'aktif'
        $tables = ['qard', 'murabahah', 'mudharabah'];
        
        foreach ($tables as $table) {
            $loan = $db->table($table)
                ->where('id_anggota', $id_user)
                ->where('status', 'aktif') // Hanya ambil yang aktif
                ->get()
                ->getRowArray();
                
            if ($loan) {
                $loan['jenis'] = $table;
                $activeLoans[] = $loan;
            }
        }
        
        return $this->response->setJSON([
            'success' => true,
            'data' => $activeLoans
        ]);
    }
}