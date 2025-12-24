<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\AnggotaModel;

class Pinjaman extends BaseController
{
    public function index()
    {
        $session = session();
        $id_user = $session->get('id');
        $db = \Config\Database::connect();
        
        $userModel = new UserModel();
        $anggotaModel = new AnggotaModel();
        
        $user = $userModel->find($id_user);
        $anggota = $anggotaModel->find($id_user);

        $nama = $user['nama_lengkap'] ?? '-';
        $nomor_anggota = $anggota['nomor_anggota'] ?? $user['id'] ?? '-';

        $hasNoRekening = !empty($anggota['no_rek']);
        $hasActiveLoan = $this->hasActiveLoan($id_user);
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

        // CEK APAKAH SUDAH BUAT PIN
        $hasPin = false;
        $pinData = $db->table('user_pin')
            ->where('user_id', $id_user)
            ->get()
            ->getRowArray();
        
        $hasPin = !empty($pinData);

        return view('pinjaman', [
            'nama' => $nama,
            'nomor_anggota' => $nomor_anggota,
            'user' => $user,
            'anggota' => $anggota,
            'hasActiveLoan' => $hasActiveLoan,
            'hasNoRekening' => $hasNoRekening,
            'pendingLoansCount' => $pendingLoansCount,
            'showTenorModal'=> $showTenorModal,
            'hasPin' => $hasPin,
        ]);
    }

 public function ajukan()
{
    $id_user = session()->get('id');

    // Cek apakah anggota sudah mengisi nomor rekening
    $anggotaModel = new AnggotaModel();
    $anggota = $anggotaModel->find($id_user);
    
    if (empty($anggota) || empty($anggota['no_rek'])) {
        return redirect()->back()->withInput()->with('error', 'Anda belum mengisi nomor rekening. Harap lengkapi data rekening di menu Profil terlebih dahulu sebelum mengajukan pinjaman.');
    }

    // Cek apakah sudah ada pinjaman AKTIF
    if ($this->hasActiveLoan($id_user)) {
        return redirect()->back()->with('error', 'Anda sudah memiliki pinjaman yang aktif. Silakan selesaikan pinjaman terlebih dahulu sebelum mengajukan pinjaman baru.');
    }

    // Validasi input pinjaman
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

    // Simpan data pinjaman ke session untuk diproses setelah verifikasi PIN
    $session = session();
    $pinjamanData = [
        'id_anggota' => $id_user,
        'jenis' => $jenis,
        'jumlah' => $jumlah,
        'lama_cicilan' => $lama_cicilan,
        'deskripsi' => $deskripsi,
        'tanggal' => date('Y-m-d'),
        'status' => 'pending'
    ];
    
    // Debug: tampilkan data yang akan disimpan
    // var_dump($pinjamanData); die();
    
    // Simpan ke session dengan key yang unik
    $sessionKey = 'pending_pinjaman_' . $id_user . '_' . time();
    $session->set($sessionKey, $pinjamanData);
    
    // Simpan session key untuk referensi
    $session->set('current_pinjaman_key', $sessionKey);

    // Redirect ke halaman yang sama dengan flag untuk show modal PIN
    return redirect()->to('pinjaman')
                    ->with('show_pin_modal', true)
                    ->with('pinjaman_session_key', $sessionKey);
}

    // Method baru untuk proses pinjaman setelah PIN diverifikasi
  public function processAfterPin()
{
    $id_user = session()->get('id');
    
    // Validasi PIN
    $pin_input = $this->request->getPost('pin');
    
    if (empty($pin_input) || strlen($pin_input) !== 6) {
        return redirect()->to('pinjaman')->withInput()->with('error', 'PIN harus 6 digit');
    }

    // Verifikasi PIN
    if (!$this->verifyPin($id_user, $pin_input)) {
        return redirect()->to('pinjaman')->withInput()->with('error', 'PIN yang Anda masukkan salah.');
    }

    // Ambil data dari form POST (dari hidden inputs)
    $jenis = $this->request->getPost('pinjaman_jenis');
    $jumlah = preg_replace('/[^0-9]/', '', $this->request->getPost('pinjaman_jumlah'));
    $lama_cicilan = $this->request->getPost('pinjaman_lama_cicilan');
    $deskripsi = $this->request->getPost('pinjaman_deskripsi');
    
    // Validasi data
    if (!$jenis || !$jumlah || !$lama_cicilan || !$deskripsi) {
        return redirect()->to('pinjaman')->with('error', 'Data pinjaman tidak lengkap. Silakan ulangi pengajuan.');
    }

    // Validasi jumlah
    if ($jumlah == '' || $jumlah == 0) {
        return redirect()->to('pinjaman')->withInput()->with('error', 'Nominal pinjaman tidak valid');
    }

    if ($jumlah > 4000000) {
        return redirect()->to('pinjaman')->withInput()->with('error', 'Nominal pinjaman melebihi batas maksimum Rp 4.000.000');
    }

    if ($jumlah < 100000) {
        return redirect()->to('pinjaman')->withInput()->with('error', 'Nominal pinjaman minimal Rp 100.000');
    }

    $db = \Config\Database::connect();

    try {
        if ($jenis == 'qard') {
            $db->table('qard')->insert([
                'id_anggota' => $id_user,
                'jml_pinjam' => $jumlah,
                'jml_angsuran' => $lama_cicilan,
                'deskripsi' => $deskripsi,
                'tanggal' => date('Y-m-d'),
                'status' => 'pending'
            ]);
        } elseif ($jenis == 'murabahah') {
            $db->table('murabahah')->insert([
                'id_anggota' => $id_user,
                'jml_pinjam' => $jumlah,
                'jml_angsuran' => $lama_cicilan,
                'deskripsi' => $deskripsi,
                'tanggal' => date('Y-m-d'),
                'status' => 'pending'
            ]);
        } elseif ($jenis == 'mudharabah') {
            $db->table('mudharabah')->insert([
                'id_anggota' => $id_user,
                'jml_pinjam' => $jumlah,
                'jml_angsuran' => $lama_cicilan,
                'deskripsi' => $deskripsi,
                'tanggal' => date('Y-m-d'),
                'status' => 'pending'
            ]);
        }

        return redirect()->to('pinjaman')->with('pinjaman_success', true);

    } catch (\Exception $e) {
        return redirect()->to('pinjaman')->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
    }
}

    // Method untuk membuat PIN baru
    public function createPin()
    {
        $id_user = session()->get('id');
        
        $validation = \Config\Services::validation();
        $validation->setRules([
            'new_pin' => 'required|numeric|exact_length[6]',
            'confirm_pin' => 'required|matches[new_pin]'
        ]);
        
        if (!$validation->withRequest($this->request)->run()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => implode(', ', $validation->getErrors())
            ]);
        }
        
        $new_pin = $this->request->getPost('new_pin');
        
        $db = \Config\Database::connect();
        
        try {
            // Hash PIN sebelum disimpan
            $pin_hash = password_hash($new_pin, PASSWORD_DEFAULT);
            
            // Cek apakah sudah ada PIN
            $existing = $db->table('user_pin')
                ->where('user_id', $id_user)
                ->get()
                ->getRowArray();
            
            if ($existing) {
                // Update PIN
                $db->table('user_pin')
                    ->where('user_id', $id_user)
                    ->update([
                        'pin_hash' => $pin_hash,
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
            } else {
                // Insert PIN baru
                $db->table('user_pin')->insert([
                    'user_id' => $id_user,
                    'pin_hash' => $pin_hash,
                    'created_at' => date('Y-m-d H:i:s')
                ]);
            }
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'PIN berhasil dibuat'
            ]);
            
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    // Method untuk validasi PIN via AJAX
    public function verifyPinAjax()
    {
        $id_user = session()->get('id');
        $pin_input = $this->request->getPost('pin');
        
        if (empty($pin_input) || strlen($pin_input) !== 6) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'PIN harus 6 digit'
            ]);
        }
        
        if ($this->verifyPin($id_user, $pin_input)) {
            return $this->response->setJSON([
                'success' => true,
                'message' => 'PIN valid'
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'PIN salah'
            ]);
        }
    }

    public function checkNoRekening()
    {
        $id_user = session()->get('id');
        $anggotaModel = new AnggotaModel();
        $anggota = $anggotaModel->find($id_user);

        return $this->response->setJSON([
            'hasNoRekening' => !empty($anggota['no_rek']),
            'no_rek' => $anggota['no_rek'] ?? null
        ]);
    }

    public function validateBeforeSubmit()
    {
        $id_user = session()->get('id');
        
        // Cek apakah sudah buat PIN
        $hasPin = $this->hasPinCreated($id_user);
        
        // Cek nomor rekening dari table anggota
        $anggotaModel = new AnggotaModel();
        $anggota = $anggotaModel->find($id_user);
        $hasNoRekening = !empty($anggota['no_rek']);
        
        // Cek pinjaman aktif (hanya status 'aktif')
        $hasActiveLoan = $this->hasActiveLoan($id_user);
        
        $canSubmit = $hasNoRekening && !$hasActiveLoan;
        
        $messages = [];
        if (!$hasNoRekening) $messages[] = 'Nomor rekening belum diisi';
        if ($hasActiveLoan) $messages[] = 'Ada pinjaman aktif';
        
        return $this->response->setJSON([
            'success' => true,
            'hasPin' => $hasPin, // Info apakah sudah punya PIN
            'hasNoRekening' => $hasNoRekening,
            'hasActiveLoan' => $hasActiveLoan,
            'canSubmit' => $canSubmit,
            'messages' => $messages
        ]);
    }

    /**
     * Cek apakah ada pinjaman aktif
     */
    private function hasActiveLoan($id_anggota)
    {
        $db = \Config\Database::connect();
        
        $qard = $db->table('qard')
            ->where('id_anggota', $id_anggota)
            ->where('status', 'aktif')
            ->countAllResults();
        
        $murabahah = $db->table('murabahah')
            ->where('id_anggota', $id_anggota)
            ->where('status', 'aktif')
            ->countAllResults();
        
        $mudharabah = $db->table('mudharabah')
            ->where('id_anggota', $id_anggota)
            ->where('status', 'aktif')
            ->countAllResults();

        return ($qard > 0 || $murabahah > 0 || $mudharabah > 0);
    }

    /**
     * Cek total pinjaman pending
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
        
        $tables = ['qard', 'murabahah', 'mudharabah'];
        
        foreach ($tables as $table) {
            $loan = $db->table($table)
                ->where('id_anggota', $id_user)
                ->where('status', 'aktif')
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

    /**
     * Cek apakah user sudah membuat PIN
     */
    private function hasPinCreated($user_id)
    {
        $db = \Config\Database::connect();
        
        $pinData = $db->table('user_pin')
            ->where('user_id', $user_id)
            ->get()
            ->getRowArray();
        
        return !empty($pinData);
    }

    /**
     * Verifikasi PIN
     */
    private function verifyPin($user_id, $pin_input)
    {
        $db = \Config\Database::connect();
        
        $pinData = $db->table('user_pin')
            ->where('user_id', $user_id)
            ->get()
            ->getRowArray();
        
        if (empty($pinData)) {
            return false;
        }
        
        // Verifikasi PIN yang di-hash
        if (isset($pinData['pin_hash']) && password_verify($pin_input, $pinData['pin_hash'])) {
            return true;
        }
        
        return false;
    }
    
}
