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

        $data = [
            'nama_lengkap'      => $this->request->getPost('nama_lengkap'),
            'email'             => $this->request->getPost('email'),
            'username'          => $this->request->getPost('username'),
            'password'          => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'nomor_ktp'         => $this->request->getPost('no_ktp'),
            'nomor_hp'          => $this->request->getPost('no_hp'),
            'nomor_hp_keluarga' => $this->request->getPost('no_hp_keluarga'),
            'foto'              => $this->request->getPost('foto_diri'),
            'role'              => 'anggota',
            'status'            => 'pending', // 👈 default pending
            'created_at'        => date('Y-m-d H:i:s'),
        ];

        if ($userModel->insert($data)) {
            return redirect()->to('/login')->with('success', 'Registrasi berhasil, silakan tunggu verifikasi admin.');
        } else {
            return redirect()->back()->withInput()->with('errors', $userModel->errors());
        }
    }
}
