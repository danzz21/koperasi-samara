<?php
namespace App\Controllers;

use App\Models\UserModel;
use App\Models\AnggotaModel;
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

            // CEK JIKA ROLE ANGGOTA
            if ($user['role'] == 'anggota') {
                // Cek status verifikasi akun
                if ($user['status'] != 'verified') {
                    session()->setFlashdata('error', 'Akun Anda belum disetujui admin.');
                    return redirect()->back()->withInput();
                }

                // Ambil data anggota - GUNAKAN id_anggota sebagai foreign key
                $anggotaModel = new AnggotaModel();
                $anggota = $anggotaModel->where('id_anggota', $user['id'])->first();

                // Jika tidak menemukan data anggota
                if (!$anggota) {
                    session()->setFlashdata('error', 'Data anggota tidak ditemukan.');
                    return redirect()->back()->withInput();
                }

                // CEK STATUS AKTIF/NONAKTIF ANGGOTA
                if ($anggota['status'] != 'aktif') {
                    session()->setFlashdata('error', 'Akun Anda telah dinonaktifkan. Silakan hubungi admin.');
                    return redirect()->back()->withInput();
                }
            }

            // Set session data
            $sessionData = [
                'id'         => $user['id'],
                'username'   => $user['username'],
                'role'       => $user['role'],
                'isLoggedIn' => true,
            ];

            // Tambahkan data anggota ke session jika role anggota
            if ($user['role'] == 'anggota') {
                $sessionData['id_anggota'] = $anggota['id_anggota'];
                $sessionData['nama_anggota'] = $anggota['nama_lengkap'];
                $sessionData['status_anggota'] = $anggota['status'];
            }

            session()->set($sessionData);

            // Redirect sesuai role
            if ($user['role'] == 'admin') {
                return redirect()->to('/admin/dashboard');
            } elseif ($user['role'] == 'anggota') {
                return redirect()->to('/anggota/dashboard');
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

    // TAMBAHKAN METHOD INI UNTUK FORGOT PASSWORD
    public function forgotPassword()
    {
        return view('auth/forgot_password');
    }

    public function processForgotPassword()
    {
        $phone = $this->request->getPost('phone');
        $username = $this->request->getPost('username');

        $userModel = new UserModel();
        $anggotaModel = new AnggotaModel();

        // Cari user berdasarkan username
        $user = $userModel->where('username', $username)->first();

        if ($user) {
            // Cari anggota berdasarkan id user dan nomor handphone
            $anggota = $anggotaModel->where('id_anggota', $user['id'])
                                   ->where('no_hp', $phone)
                                   ->first();

            if ($anggota) {
                // Generate new password
                $newPassword = substr(md5(uniqid()), 0, 8);
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

                // Update password di tabel users
                $userModel->update($user['id'], ['password' => $hashedPassword]);

                // Tampilkan password baru
                return redirect()->to('/auth/resetSuccess')->with('new_password', $newPassword);
            } else {
                return redirect()->back()->with('error', 'Nomor handphone tidak sesuai dengan username');
            }
        } else {
            return redirect()->back()->with('error', 'Username tidak ditemukan');
        }
    }

    public function resetSuccess()
    {
        return view('auth/reset_success');
    }
}