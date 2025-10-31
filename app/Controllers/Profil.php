<?php

namespace App\Controllers;

use App\Models\AnggotaModel;

class Profil extends BaseController
{
    public function index()
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
            'jenis_bank' => $anggota['jenis_bank'] ?? '-', // TAMBAHKAN INI
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
            'jenis_bank' => $anggota['jenis_bank'] ?? '', // TAMBAHKAN INI
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
            'jenis_bank' => 'permit_empty', // TAMBAHKAN VALIDASI
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
            'jenis_bank' => $this->request->getPost('jenis_bank') // TAMBAHKAN INI
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
            'jenis_bank' => $anggota['jenis_bank'] ?? '-', // TAMBAHKAN INI
        ];
        
        return view('cetak_kartu', $data);
    }
}