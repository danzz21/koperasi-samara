<?php

namespace App\Controllers;

use App\Models\AnggotaModel;
use App\Models\UserModel;

class Profil extends BaseController
{
    public function index()
    {
        $session = session();
        $id = $session->get('id');
        $anggotaModel = new AnggotaModel();
        
        $anggota = $anggotaModel->find($id);

        // Cek apakah sudah punya PIN
        $db = \Config\Database::connect();
        $pinData = $db->table('user_pin')
            ->where('user_id', $id)
            ->get()
            ->getRowArray();
        
        $hasPin = !empty($pinData);

        $data = [
            'anggota' => $anggota,
            'nama' => $anggota['nama_lengkap'] ?? '-',
            'nomor_anggota' => $anggota['nomor_anggota'] ?? '-',
            'email' => $anggota['email'] ?? '-',
            'no_hp' => $anggota['no_hp'] ?? '-',
            'alamat' => $anggota['alamat'] ?? '-',
            'status' => $anggota['status'] ?? '-',
            'tanggal_daftar' => isset($anggota['tanggal_daftar']) ? date('d M Y', strtotime($anggota['tanggal_daftar'])) : '-',
            'jenis_anggota' => $anggota['jenis_anggota'] ?? 'Reguler',
            'photo' => $anggota['photo'] ?? null,
            'no_rek' => $anggota['no_rek'] ?? '-',
            'atasnama_rekening' => $anggota['atasnama_rekening'] ?? '-',
            'jenis_bank' => $anggota['jenis_bank'] ?? '-',
            'hasPin' => $hasPin, // TAMBAHKAN STATUS PIN
        ];
        return view('profil', $data);
    }

    public function updateFoto()
    {
        $session = session();
        $id = $session->get('id');
        $anggotaModel = new AnggotaModel();

        $file = $this->request->getFile('photo');

        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(FCPATH . 'uploads/profile', $newName);

            $anggotaModel->update($id, [
                'photo' => $newName
            ]);

            $session->set('photo', $newName);
        }

        return redirect()->to('/anggota/profil');
    }

    public function edit()
    {
        $session = session();
        $id = $session->get('id');
        $anggotaModel = new AnggotaModel();
        
        $anggota = $anggotaModel->find($id);

        // Cek apakah sudah punya PIN
        $db = \Config\Database::connect();
        $pinData = $db->table('user_pin')
            ->where('user_id', $id)
            ->get()
            ->getRowArray();
        
        $hasPin = !empty($pinData);

        $data = [
            'anggota' => $anggota,
            'nama' => $anggota['nama_lengkap'] ?? '-',
            'nomor_anggota' => $anggota['nomor_anggota'] ?? '-',
            'email' => $anggota['email'] ?? '-',
            'no_hp' => $anggota['no_hp'] ?? '-',
            'alamat' => $anggota['alamat'] ?? '-',
            'status' => $anggota['status'] ?? '-',
            'tanggal_daftar' => isset($anggota['tanggal_daftar']) ? date('d M Y', strtotime($anggota['tanggal_daftar'])) : '-',
            'jenis_anggota' => $anggota['jenis_anggota'] ?? 'Reguler',
            'photo' => $anggota['photo'] ?? null,
            'no_rek' => $anggota['no_rek'] ?? '',
            'atasnama_rekening' => $anggota['atasnama_rekening'] ?? '',
            'jenis_bank' => $anggota['jenis_bank'] ?? '',
            'hasPin' => $hasPin, // TAMBAHKAN STATUS PIN
        ];
        return view('profil_edit', $data);
    }

    public function update()
    {
        $session = session();
        $id = $session->get('id');
        $anggotaModel = new AnggotaModel();

        $validation = \Config\Services::validation();
        $validation->setRules([
            'nama_lengkap' => 'required|min_length[3]',
            'email' => 'required|valid_email',
            'no_hp' => 'required',
            'alamat' => 'required',
            'no_rek' => 'permit_empty|min_length[10]',
            'atasnama_rekening' => 'permit_empty|min_length[3]',
            'jenis_bank' => 'permit_empty',
            'password' => 'permit_empty|min_length[6]',
            'confirm_password' => 'matches[password]'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        // Update data anggota
        $dataAnggota = [
            'nama_lengkap' => $this->request->getPost('nama_lengkap'),
            'email' => $this->request->getPost('email'),
            'no_hp' => $this->request->getPost('no_hp'),
            'alamat' => $this->request->getPost('alamat'),
            'no_rek' => $this->request->getPost('no_rek'),
            'atasnama_rekening' => $this->request->getPost('atasnama_rekening'),
            'jenis_bank' => $this->request->getPost('jenis_bank')
        ];

        // Jika password diisi, update password di table users
        $password = $this->request->getPost('password');
        if (!empty($password)) {
            $userModel = new \App\Models\UserModel();
            $userModel->update($id, [
                'password' => password_hash($password, PASSWORD_DEFAULT)
            ]);
        }

        if ($anggotaModel->update($id, $dataAnggota)) {
            $session->setFlashdata('success', 'Profil berhasil diperbarui');
            $session->set('nama_lengkap', $dataAnggota['nama_lengkap']);
            $session->set('email', $dataAnggota['email']);
        } else {
            $session->setFlashdata('error', 'Gagal memperbarui profil');
        }

        return redirect()->to('/anggota/profil');
    }

    // Method untuk membuat PIN baru
    public function updatePin()
    {
        $id_user = session()->get('id');
        
        $validation = \Config\Services::validation();
        $validation->setRules([
            'new_pin' => 'required|numeric|exact_length[6]',
            'confirm_new_pin' => 'required|matches[new_pin]'
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
                // Update PIN yang sudah ada
                $db->table('user_pin')
                    ->where('user_id', $id_user)
                    ->update([
                        'pin_hash' => $pin_hash,
                        'updated_at' => date('Y-m-d H:i:s')
                    ]);
                    
                $message = 'PIN berhasil diubah';
            } else {
                // Insert PIN baru
                $db->table('user_pin')->insert([
                    'user_id' => $id_user,
                    'pin_hash' => $pin_hash,
                    'created_at' => date('Y-m-d H:i:s')
                ]);
                
                $message = 'PIN berhasil dibuat';
            }
            
            return $this->response->setJSON([
                'success' => true,
                'message' => $message
            ]);
            
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    // Method untuk mengubah PIN (butuh PIN lama)
    public function changePin()
    {
        $id_user = session()->get('id');
        
        $validation = \Config\Services::validation();
        $validation->setRules([
            'old_pin' => 'required|numeric|exact_length[6]',
            'new_pin' => 'required|numeric|exact_length[6]',
            'confirm_new_pin' => 'required|matches[new_pin]'
        ]);
        
        if (!$validation->withRequest($this->request)->run()) {
            return $this->response->setJSON([
                'success' => false,
                'message' => implode(', ', $validation->getErrors())
            ]);
        }
        
        $old_pin = $this->request->getPost('old_pin');
        $new_pin = $this->request->getPost('new_pin');
        
        $db = \Config\Database::connect();
        
        try {
            // Ambil data PIN lama
            $existing = $db->table('user_pin')
                ->where('user_id', $id_user)
                ->get()
                ->getRowArray();
            
            if (!$existing) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Anda belum memiliki PIN. Silakan buat PIN terlebih dahulu.'
                ]);
            }
            
            // Verifikasi PIN lama
            if (!password_verify($old_pin, $existing['pin_hash'])) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'PIN lama salah'
                ]);
            }
            
            // Hash PIN baru
            $pin_hash = password_hash($new_pin, PASSWORD_DEFAULT);
            
            // Update PIN
            $db->table('user_pin')
                ->where('user_id', $id_user)
                ->update([
                    'pin_hash' => $pin_hash,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            
            return $this->response->setJSON([
                'success' => true,
                'message' => 'PIN berhasil diubah'
            ]);
            
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    // Method untuk verifikasi PIN (digunakan di Pinjaman controller)
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
        
        $db = \Config\Database::connect();
        $pinData = $db->table('user_pin')
            ->where('user_id', $id_user)
            ->get()
            ->getRowArray();
        
        if (empty($pinData)) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Anda belum memiliki PIN'
            ]);
        }
        
        if (password_verify($pin_input, $pinData['pin_hash'])) {
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

    public function cetakKartu()
    {
        $session = session();
        $id = $session->get('id');
        $anggotaModel = new AnggotaModel();
        
        $anggota = $anggotaModel->find($id);

        $data = [
            'anggota' => $anggota,
            'nama' => $anggota['nama_lengkap'] ?? '-',
            'nomor_anggota' => $anggota['nomor_anggota'] ?? '-',
            'email' => $anggota['email'] ?? '-',
            'no_hp' => $anggota['no_hp'] ?? '-',
            'alamat' => $anggota['alamat'] ?? '-',
            'status' => $anggota['status'] ?? '-',
            'tanggal_daftar' => isset($anggota['tanggal_daftar']) ? date('d M Y', strtotime($anggota['tanggal_daftar'])) : '-',
            'jenis_anggota' => $anggota['jenis_anggota'] ?? 'Reguler',
            'photo' => $anggota['photo'] ?? null,
            'no_rek' => $anggota['no_rek'] ?? '-',
            'atasnama_rekening' => $anggota['atasnama_rekening'] ?? '-',
            'jenis_bank' => $anggota['jenis_bank'] ?? '-',
        ];
        
        return view('cetak_kartu', $data);
    }
}