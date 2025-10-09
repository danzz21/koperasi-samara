<?php

namespace App\Controllers;

use App\Models\AnggotaModel;

class Pinjaman extends BaseController
{
    public function index()
    {
        $session = session();
        $id = $session->get('id');
        $anggotaModel = new AnggotaModel();
        $anggota = $anggotaModel->find($id);

        $nama = $anggota['nama_lengkap'] ?? '-';
        $nomor_anggota = $anggota['nomor_anggota'] ?? '-';

        return view('pinjaman', [
            'nama' => $nama,
            'nomor_anggota' => $nomor_anggota,
            'anggota' => $anggota
        ]);
    }

    public function ajukan()
    {
        $jenis = $this->request->getPost('jenis');
        $jumlah = preg_replace('/[^0-9]/', '', $this->request->getPost('jumlah'));
        $lama_cicilan = $this->request->getPost('lama_cicilan');
        $id_anggota = session()->get('id');
        $tanggal = date('Y-m-d');
        $status = 'pending';

        $db = \Config\Database::connect();

        if ($jenis == 'qard') {
            $db->table('qard')->insert([
                'id_anggota' => $id_anggota,
                'jml_pinjam' => $jumlah,
                'jml_angsuran' => $lama_cicilan,
                'tanggal' => $tanggal,
                'status' => $status
            ]);
        } elseif ($jenis == 'murabahah') {
            $db->table('murabahah')->insert([
                'id_anggota' => $id_anggota,
                'jml_pinjam' => $jumlah,
                'jml_angsuran' => $lama_cicilan,
                'tanggal' => $tanggal,
                'status' => $status
            ]);
        } elseif ($jenis == 'mudharabah') {
            $db->table('mudharabah')->insert([
                'id_anggota' => $id_anggota,
                'jml_pinjam' => $jumlah,
                'jml_angsuran' => $lama_cicilan,
                'tanggal' => $tanggal,
                'status' => $status
            ]);
        }

        return redirect()->back()->with('success', 'Pengajuan pinjaman berhasil, menunggu verifikasi admin.');
    }
}
