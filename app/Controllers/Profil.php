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
            'nama' => $anggota['nama_lengkap'] ?? '-',
            'nomor_anggota' => $anggota['nomor_anggota'] ?? '-',
            'email' => $anggota['email'] ?? '-',
            'no_hp' => $anggota['no_hp'] ?? '-',
            'alamat' => $anggota['alamat'] ?? '-',
            'status' => $anggota['status'] ?? '-',
            'tanggal_daftar' => isset($anggota['tanggal_daftar']) ? date('d M Y', strtotime($anggota['tanggal_daftar'])) : '-',
            'jenis_anggota' => $anggota['jenis_anggota'] ?? 'Reguler',
        ];
        return view('profil', $data);
    }
}
