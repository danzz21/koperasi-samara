<?php
namespace App\Controllers;

class AdminPendingPinjaman extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();

        // Gabungkan semua pinjaman pending
        $pendingQard = $db->table('qard')
            ->join('anggota', 'anggota.id = qard.id_anggota')
            ->select('qard.id_qard as id_pinjaman, "qard" as jenis, anggota.nama_lengkap, anggota.nomor_anggota, qard.jml_pinjam, qard.jml_angsuran, qard.tanggal, qard.status')
            ->where('qard.status', 'pending')
            ->get()->getResultArray();

        $pendingMurabahah = $db->table('murabahah')
            ->join('anggota', 'anggota.id = murabahah.id_anggota')
            ->select('murabahah.id_mr as id_pinjaman, "murabahah" as jenis, anggota.nama_lengkap, anggota.nomor_anggota, murabahah.jml_pinjam, murabahah.jml_angsuran, murabahah.tanggal, murabahah.status')
            ->where('murabahah.status', 'pending')
            ->get()->getResultArray();

        $pendingMudharabah = $db->table('mudharabah')
            ->join('anggota', 'anggota.id = mudharabah.id_anggota')
            ->select('mudharabah.id_md as id_pinjaman, "mudharabah" as jenis, anggota.nama_lengkap, anggota.nomor_anggota, mudharabah.jml_pinjam, mudharabah.jml_angsuran, mudharabah.tanggal, mudharabah.status')
            ->where('mudharabah.status', 'pending')
            ->get()->getResultArray();

        $pending = array_merge($pendingQard, $pendingMurabahah, $pendingMudharabah);

        $pendingQardCount = $db->table('qard')->where('status', 'pending')->countAllResults();
        $pendingMurabahahCount = $db->table('murabahah')->where('status', 'pending')->countAllResults();
        $pendingMudharabahCount = $db->table('mudharabah')->where('status', 'pending')->countAllResults();
        $pendingPinjamanCount = $pendingQardCount + $pendingMurabahahCount + $pendingMudharabahCount;

        return view('dashboard_admin/pending_pinjaman', [
            'pending' => $pending,
            'pendingPinjamanCount' => $pendingPinjamanCount,
        ]);
    }

    public function verifikasi($jenis, $id)
    {
        $db = \Config\Database::connect();
        if ($jenis == 'qard') {
            $db->table('qard')->where('id', $id)->update(['status' => 'aktif']);
        } elseif ($jenis == 'murabahah') {
            $db->table('murabahah')->where('id', $id)->update(['status' => 'aktif']);
        } elseif ($jenis == 'mudharabah') {
            $db->table('mudharabah')->where('id', $id)->update(['status' => 'aktif']);
        }
        return redirect()->back()->with('success', 'Pinjaman berhasil diverifikasi!');
    }
}