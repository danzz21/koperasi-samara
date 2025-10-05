<?php
namespace App\Controllers;

class AdminPinjaman extends BaseController
{
    public function pendingPinjaman()
    {
        $db = \Config\Database::connect();

        $pendingQard = $db->table('qard')
            ->join('anggota', 'anggota.id = qard.id_anggota')
            ->select('qard.id_qard as id_pinjaman, "Qard" as jenis, anggota.nama_lengkap, anggota.nomor_anggota, qard.tanggal, qard.jml_pinjam, qard.status')
            ->where('qard.status', 'pending')
            ->get()->getResultArray();

        $pendingMurabahah = $db->table('murabahah')
            ->join('anggota', 'anggota.id = murabahah.id_anggota')
            ->select('murabahah.id_mr as id_pinjaman, "Murabahah" as jenis, anggota.nama_lengkap, anggota.nomor_anggota, murabahah.tanggal, murabahah.jml_pinjam, murabahah.status')
            ->where('murabahah.status', 'pending')
            ->get()->getResultArray();

        $pendingMudharabah = $db->table('mudharabah')
            ->join('anggota', 'anggota.id = mudharabah.id_anggota')
            ->select('mudharabah.id_md as id_pinjaman, "Mudharabah" as jenis, anggota.nama_lengkap, anggota.nomor_anggota, mudharabah.tanggal, mudharabah.jml_pinjam, mudharabah.status')
            ->where('mudharabah.status', 'pending')
            ->get()->getResultArray();

        $pending = array_merge($pendingQard, $pendingMurabahah, $pendingMudharabah);

        return view('dashboard_admin/pending_pinjaman', ['pending' => $pending]);
    }
}