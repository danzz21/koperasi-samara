<?php
namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;

class Auth extends BaseController
{
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }

    public function login()
{
    $uri = $this->request->getUri();
    $segment = '';

    if ($uri->getTotalSegments() >= 2) {
        $segment = $uri->getSegment(2);
    }

    if ($segment == 'admin') {
        return view('auth/login_admin');
    } else {
        return view('auth/login'); // login anggota
    }
}



    public function doLogin()
{
    $username = $this->request->getPost('username');
    $password = $this->request->getPost('password');

    $userModel = new UserModel();
    $user = $userModel->where('username', $username)->first();

    if ($user) {
        if (password_verify($password, $user['password'])) {

            if ($user['role'] == 'anggota' && $user['status'] != 'verified') {
                session()->setFlashdata('error', 'Akun Anda belum disetujui admin.');
                return redirect()->back()->withInput();
            }

            // Ambil data anggota
            $anggotaModel = new \App\Models\AnggotaModel();
            $anggota = $anggotaModel->where('id_anggota', $user['id'])->first();

            if (!$anggota && $user['role'] == 'anggota') {
                session()->setFlashdata('error', 'Data anggota tidak ditemukan.');
                return redirect()->back()->withInput();
            }

            // Set session
            $sessionData = [
                'id'         => $user['id'],
                'username'   => $user['username'],
                'role'       => $user['role'],
                'isLoggedIn' => true,
            ];

            if ($user['role'] == 'anggota') {
                $sessionData['id_anggota'] = $anggota['id_anggota'];
            }

            session()->set($sessionData);

            // Redirect sesuai role
            if ($user['role'] == 'admin') {
                return redirect()->to('/admin/dashboard');
            } elseif ($user['role'] == 'anggota') {
                return redirect()->to('/anggota/dashboard'); // ✅ PERBAIKAN DI SINI
            } else {
                session()->setFlashdata('error', 'Role tidak dikenali');
                return redirect()->back();
            }
        } else {
            session()->setFlashdata('error', 'Password salah');
            return redirect()->back()->withInput();
        }
    } else {
        session()->setFlashdata('error', 'Username tidak ditemukan');
        return redirect()->back()->withInput();
    }
}




}
