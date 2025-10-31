<?php

namespace App\Controllers;

use App\Models\AnggotaModel;

class Simpanan extends BaseController
{
    public function index()
    {
        $session = session();
        $id_anggota = $session->get('id');

        $db = \Config\Database::connect();

        // Ambil data anggota
        $anggota = $db->table('anggota')
            ->select('id_anggota, nama_lengkap, nomor_anggota, photo')
            ->where('id_anggota', $id_anggota)
            ->get()
            ->getRowArray();

        // **AMBIL TENOR DARI SIMPANAN_POKOK**
        $tenorData = $db->table('simpanan_pokok')
            ->select('tenor')
            ->where('id_anggota', $id_anggota)
            ->get()
            ->getRowArray();

        // **FILTER: hanya ambil simpanan pokok yang jumlah > 0**
        $pokok = $db->table('simpanan_pokok')
            ->where('id_anggota', $id_anggota)
            ->where('jumlah >', 0) // **INI YANG PENTING**
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

        // Cek apakah sudah punya tenor
        $showTenorModal = false;
        if (!$tenorData || $tenorData['tenor'] === null) {
            $showTenorModal = true;
        }

        return view('simpanan', [
            'nama'          => $anggota['nama_lengkap'] ?? '-',
            'nomor_anggota' => $anggota['nomor_anggota'] ?? '-',
            'foto_diri'     => $anggota['photo'] ?? null,
            'pokok'         => $pokok,
            'wajib'         => $wajib,
            'sukarela'      => $sukarela,
            'anggota'       => $anggota,
            'tenor_anggota' => $tenorData['tenor'] ?? null, // **Tenor dari simpanan_pokok**
            'showTenorModal'=> $showTenorModal,
        ]);
    }

    public function setTenor()
    {
        $session = session();
        $id_anggota = $session->get('id'); 
        $tenor = $this->request->getPost('tenor');

        $db = \Config\Database::connect();
        
        // **CEK APAKAH SUDAH ADA DATA DI SIMPANAN_POKOK**
        $existingData = $db->table('simpanan_pokok')
            ->where('id_anggota', $id_anggota)
            ->get()
            ->getRowArray();

        if ($existingData) {
            // **UPDATE TENOR SAJA tanpa buat record baru**
            $db->table('simpanan_pokok')
                ->where('id_anggota', $id_anggota)
                ->update(['tenor' => $tenor]);
        } else {
            // **INSERT HANYA TENOR, TANPA JUMLAH & TANPA STATUS**
            $db->table('simpanan_pokok')->insert([
                'id_anggota' => $id_anggota,
                'tenor'      => $tenor,
                'tanggal'    => date('Y-m-d')
                // **TIDAK ADA 'jumlah' dan 'status'**
            ]);
        }

        return redirect()->to(base_url('anggota/simpanan'))->with('success', 'Tenor berhasil disimpan.');
    }
}