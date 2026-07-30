<?php

namespace App\Controllers;

use App\Models\AnggotaModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $session = session();
        $id_anggota = $session->get('id_anggota');

        if (!$id_anggota) {
            return redirect()->to('/login'); 
        }

        $anggotaModel = new AnggotaModel();
        $anggota = $anggotaModel->find($id_anggota);

        $db = \Config\Database::connect();

        // Daftar status transaksi simpanan yang dianggap sah/ACC
        $statusValid = ['aktif', 'lunas', 'approved', 'disetujui'];

        // 1. SIMPANAN POKOK (Hanya hitung transaksi yang sah)
        $sim_pokok = (float)($db->table('simpanan_pokok')
            ->where('id_anggota', $id_anggota)
            ->whereIn('status', $statusValid)
            ->selectSum('jumlah')
            ->get()->getRow()->jumlah ?? 0);

        // 2. SIMPANAN WAJIB (Hanya hitung transaksi yang sah)
        $sim_wajib = (float)($db->table('simpanan_wajib')
            ->where('id_anggota', $id_anggota)
            ->whereIn('status', $statusValid)
            ->selectSum('jumlah')
            ->get()->getRow()->jumlah ?? 0);

        // 3. SIMPANAN SUKARELA (Hanya hitung transaksi yang sah)
        $sim_sukarela = (float)($db->table('simpanan_sukarela')
            ->where('id_anggota', $id_anggota)
            ->whereIn('status', $statusValid)
            ->selectSum('jumlah')
            ->get()->getRow()->jumlah ?? 0);

        $total_saldo = $sim_pokok + $sim_wajib + $sim_sukarela;

        // 4. RINCIAN PINJAMAN (Hanya Status Aktif)
        $pinj_qard = $db->table('qard')
            ->where('id_anggota', $id_anggota)
            ->where('status', 'aktif')
            ->selectSum('jml_pinjam')
            ->selectSum('jml_terbayar')
            ->get()->getRow();

        $pinj_murabahah = $db->table('murabahah')
            ->where('id_anggota', $id_anggota)
            ->where('status', 'aktif')
            ->selectSum('jml_pinjam')
            ->selectSum('jml_terbayar')
            ->get()->getRow();

        $pinj_mudharabah = $db->table('mudharabah')
            ->where('id_anggota', $id_anggota)
            ->where('status', 'aktif')
            ->selectSum('jml_pinjam')
            ->selectSum('jml_terbayar')
            ->get()->getRow();

        $qard_total      = (float)($pinj_qard->jml_pinjam ?? 0);
        $qard_terbayar   = (float)($pinj_qard->jml_terbayar ?? 0);

        $muro_total      = (float)($pinj_murabahah->jml_pinjam ?? 0);
        $muro_terbayar   = (float)($pinj_murabahah->jml_terbayar ?? 0);

        $mudh_total      = (float)($pinj_mudharabah->jml_pinjam ?? 0);
        $mudh_terbayar   = (float)($pinj_mudharabah->jml_terbayar ?? 0);

        $total_pinjaman  = $qard_total + $muro_total + $mudh_total;
        $total_terbayar  = $qard_terbayar + $muro_terbayar + $mudh_terbayar;
        $sisa_kewajiban  = max(0, $total_pinjaman - $total_terbayar);

        return view('dashboard', [
            'anggota'         => $anggota,
            'total_saldo'     => $total_saldo,
            'sim_pokok'       => $sim_pokok,
            'sim_wajib'       => $sim_wajib,
            'sim_sukarela'    => $sim_sukarela,
            'total_pinjaman'  => $total_pinjaman,
            'qard_total'      => $qard_total,
            'muro_total'      => $muro_total,
            'mudh_total'      => $mudh_total,
            'sisa_kewajiban'  => $sisa_kewajiban
        ]);
    }
}