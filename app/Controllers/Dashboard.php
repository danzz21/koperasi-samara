<?php

namespace App\Controllers;

use App\Models\AnggotaModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $session = session();
        $id_anggota = $session->get('id_anggota'); // ID anggota disimpan saat login

        // Cek kalau belum login
        if (!$id_anggota) {
            return redirect()->to('/login'); 
        }

        $anggotaModel = new AnggotaModel();
        $anggota = $anggotaModel->find($id_anggota);

        $db = \Config\Database::connect();

        // Total simpanan
        $pokok = $db->table('simpanan_pokok')->where('id_anggota', $id_anggota)->selectSum('jumlah')->get()->getRow()->jumlah ?? 0;
        $wajib = $db->table('simpanan_wajib')->where('id_anggota', $id_anggota)->selectSum('jumlah')->get()->getRow()->jumlah ?? 0;
        $sukarela = $db->table('simpanan_sukarela')->where('id_anggota', $id_anggota)->where('status', 'aktif')->selectSum('jumlah')->get()->getRow()->jumlah ?? 0;
        $total_saldo = (int)$pokok + (int)$wajib + (int)$sukarela;

        // Total pinjaman
        $total_qard = $db->table('qard')
    ->where('id_anggota', $id_anggota)
    ->where('status', 'aktif')
    ->selectSum('jml_pinjam')
    ->get()->getRow()->jml_pinjam ?? 0;

$total_murabahah = $db->table('murabahah')
    ->where('id_anggota', $id_anggota)
    ->where('status', 'aktif')
    ->selectSum('jml_pinjam')
    ->get()->getRow()->jml_pinjam ?? 0;

$total_mudharabah = $db->table('mudharabah')
    ->where('id_anggota', $id_anggota)
    ->where('status', 'aktif')
    ->selectSum('jml_pinjam')
    ->get()->getRow()->jml_pinjam ?? 0;

$total_pinjaman = (int)$total_qard + (int)$total_murabahah + (int)$total_mudharabah;


        // Kirim ke view
        return view('dashboard', [
            'anggota' => $anggota,
            'total_saldo' => $total_saldo,
            'total_pinjaman' => $total_pinjaman
        ]);
    }
}
