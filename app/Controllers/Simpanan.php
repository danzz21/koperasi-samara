<?php

namespace App\Controllers;

use App\Models\AnggotaModel;

class Simpanan extends BaseController
{
    public function index()
    {
        $session = session();
        $id_anggota = $session->get('id'); // atau sesuai session login kamu

        $db = \Config\Database::connect();

        // Simpanan Pokok
        $pokok = $db->table('simpanan_pokok')
            ->where('id_anggota', $id_anggota)
            ->orderBy('tanggal', 'ASC')
            ->get()->getResultArray();

        // Simpanan Wajib
        $wajib = $db->table('simpanan_wajib')
            ->where('id_anggota', $id_anggota)
            ->orderBy('tanggal', 'ASC')
            ->get()->getResultArray();

        // Simpanan Sukarela
        $sukarela = $db->table('simpanan_sukarela')
            ->where('id_anggota', $id_anggota)
            ->orderBy('tanggal', 'DESC')
            ->get()->getResultArray();

        // Data profil anggota
        // Ambil data anggota berdasarkan id_anggota
        $anggota = $db->table('anggota')
            ->select('id_anggota, nama_lengkap, nomor_anggota, photo')
            ->where('id_anggota', $id_anggota) // <- ini yang dipake
            ->get()
            ->getRowArray();

        return view('simpanan', [
            'nama'          => $anggota['nama_lengkap'] ?? '-',
            'nomor_anggota' => $anggota['nomor_anggota'] ?? '-',
            'foto_diri'     => $anggota['foto_diri'] ?? null,
            'pokok'         => $pokok,
            'wajib'         => $wajib,
            'sukarela'      => $sukarela,
            'anggota' => $anggota
        ]);
    }
}
