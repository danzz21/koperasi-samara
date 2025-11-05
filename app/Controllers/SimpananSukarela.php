<?php
namespace App\Controllers;

use App\Models\SimpananSukarelaModel;

class SimpananSukarela extends BaseController
{
    public function index()
    {
        $session = session();
        $id = $session->get('id'); // id anggota login

        $model = new SimpananSukarelaModel();
        $data['sukarela'] = $model->where('id_anggota', $id)->orderBy('tanggal','DESC')->findAll();

        return view('anggota/simpanan_sukarela', $data);
    }

    public function store()
    {
        $session = session();
        $id = $session->get('id');

        $file = $this->request->getFile('bukti');
        $newName = null;

        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(FCPATH . 'uploads/', $newName);
        }

        $model = new SimpananSukarelaModel();
        $model->insert([
            'id_anggota' => $id,
            'jumlah'     => $this->request->getPost('jumlah'),
            'bukti' => $newName,
            'tanggal'    => date('Y-m-d'),
            'status'     => 'pending',
            'keterangan' => 'Menunggu verifikasi admin'
        ]);

        return redirect()->to(base_url('anggota/simpanan'))
            ->with('success', 'Setoran berhasil dikirim, menunggu verifikasi admin.');
    }
}
