<?php

namespace App\Controllers;

use App\Models\UserModel;

class RegisterController extends BaseController
{
    public function index()
    {
        return view('auth/register');
    }

    public function store()
    {
        $userModel = new UserModel();

        // Validasi input wajib
        $errors = [];
        if (empty($this->request->getPost('nama_lengkap'))) {
            $errors[] = "Nama lengkap harus diisi";
        }
        if (empty($this->request->getPost('email'))) {
            $errors[] = "Email harus diisi";
        }
        if (empty($this->request->getPost('username'))) {
            $errors[] = "Username harus diisi";
        }
        if (empty($this->request->getPost('password'))) {
            $errors[] = "Password harus diisi";
        }
        if (empty($this->request->getPost('no_ktp'))) {
            $errors[] = "Nomor KTP harus diisi";
        }
        if (empty($this->request->getPost('no_hp'))) {
            $errors[] = "Nomor HP harus diisi";
        }

        if (!empty($errors)) {
            return redirect()->back()->withInput()->with('errors', $errors);
        }

        // Hanya simpan data minimal ke tabel users
        $data = [
            'nama_lengkap'      => $this->request->getPost('nama_lengkap'),
            'email'             => $this->request->getPost('email'),
            'username'          => $this->request->getPost('username'),
            'password'          => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role'              => 'anggota',
            'created_at'        => date('Y-m-d H:i:s'),
            'nomor_ktp'         => $this->request->getPost('no_ktp'),
            'foto'              => $this->request->getPost('foto_diri'),
            'nomor_hp'          => $this->request->getPost('no_hp'),
            'nomor_hp_keluarga' => $this->request->getPost('nomor_hp_keluarga'),
            'status'            => 'pending',
        ];

        try {
            if ($userModel->insert($data)) {
                // Dapatkan ID user yang baru dibuat
                $userId = $userModel->getInsertID();
                
                // Simpan data lengkap ke session untuk nanti di verifikasi admin
                $registerData = [
                    'user_id'           => $userId,
                    'nama_lengkap'      => $this->request->getPost('nama_lengkap'),
                    'email'             => $this->request->getPost('email'),
                    'no_ktp'            => $this->request->getPost('no_ktp'),
                    'foto_ktp'          => $this->request->getPost('foto_ktp'),
                    'foto_diri'         => $this->request->getPost('foto_diri'),
                    'foto_diri_ktp'     => $this->request->getPost('foto_diri_ktp'),
                    'jenis_bank'        => $this->request->getPost('jenis_bank'),
                    'atasnama_rekening' => $this->request->getPost('atasnama_rekening'),
                    'no_rek'            => $this->request->getPost('no_rekening'),
                    'alamat'            => $this->request->getPost('alamat'),
                    'jenis_kelamin'     => $this->request->getPost('jenis_kelamin'),
                    'pekerjaan'         => $this->request->getPost('pekerjaan'),
                ];

                // Simpan ke session
                session()->set('register_data_' . $userId, $registerData);

                return redirect()->to('/login')->with('success', 'Registrasi berhasil! Silakan tunggu verifikasi admin.');
            } else {
                return redirect()->back()->withInput()->with('errors', $userModel->errors());
            }
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('errors', ['Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }
}