<?php

namespace App\Controllers;

use App\Models\SimpananSukarelaModel;

class AdminSimpanan extends BaseController
{
    protected $simpananModel;

    public function __construct()
    {
        $this->simpananModel = new SimpananSukarelaModel();
    }

    // Halaman daftar setoran pending
    public function pending()
    {
        $pending = $this->simpananModel
    ->select('simpanan_sukarela.*, anggota.nama_lengkap, anggota.nomor_anggota')
    ->join('anggota', 'anggota.id = simpanan_sukarela.id_anggota', 'left')
    ->where('simpanan_sukarela.status', 'pending')
    ->findAll();

        return view('dashboard_admin/pending_sukarela', [
            'pending' => $pending
        ]);
    }

    // Approve
    public function approve($id)
{
    $this->simpananModel->update($id, [
        'status' => 'aktif',
        'keterangan' => 'Terverifikasi'
    ]);

    return redirect()->to(base_url('admin/pending-sukarela'))->with('success', 'Setoran disetujui');
}


    // Reject
    public function reject($id)
    {
        $this->simpananModel->update($id, ['status' => 'rejected']);
        return redirect()->to(base_url('admin/pending-sukarela'))->with('error', 'Setoran ditolak');
    }
}
