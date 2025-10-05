<?php namespace App\Controllers;

use App\Models\SimpananPokokModel;
use App\Models\SimpananWajibModel;
use App\Models\SimpananSukarelaModel;
use App\Models\SimpananModel; // model utama
use CodeIgniter\Controller;

class Savings extends Controller
{
    public function index()
    {
        return view('admin/savings_form');
    }

    public function savePokok()
    {
        $simpananPokokModel = new SimpananPokokModel();
        $simpananModel = new SimpananModel();

        $data = [
            'id_anggota' => $this->request->getPost('id_anggota'),
            'jumlah'     => $this->request->getPost('jumlah'),
            'tanggal'    => $this->request->getPost('tanggal'),
            'status'     => $this->request->getPost('status'),
        ];

        // Insert ke tabel simpanan_pokok
        $simpananPokokModel->insert($data);

        // Insert juga ke tabel simpanan utama
        $simpananModel->insert([
            'id_anggota' => $data['id_anggota'],
            'jenis'      => 'pokok',  // jenis simpanan
            'jumlah'     => $data['jumlah'],
            'status'     => $data['status'],
            'tanggal'    => $data['tanggal'],
        ]);

        return redirect()->back()->with('success', 'Simpanan pokok berhasil disimpan!');
    }

    public function saveWajib()
    {
        $simpananWajibModel = new SimpananWajibModel();
        $simpananModel = new SimpananModel();

        $data = [
            'id_anggota' => $this->request->getPost('id_anggota'),
            'jumlah'     => $this->request->getPost('jumlah'),
            'tanggal'    => $this->request->getPost('tanggal'),
            'status'     => $this->request->getPost('status'),
        ];

        // Insert ke tabel simpanan_wajib
        $simpananWajibModel->insert($data);

        // Insert juga ke tabel simpanan utama
        $simpananModel->insert([
            'id_anggota' => $data['id_anggota'],
            'jenis'      => 'wajib',  // jenis simpanan
            'jumlah'     => $data['jumlah'],
            'status'     => $data['status'],
            'tanggal'    => $data['tanggal'],
        ]);

        return redirect()->back()->with('success', 'Simpanan wajib berhasil disimpan!');
    }

    public function saveSukarela()
    {
        $simpananSukarelaModel = new SimpananSukarelaModel();
        $simpananModel = new SimpananModel();

        $data = [
            'id_anggota' => $this->request->getPost('id_anggota'),
            'jumlah'     => $this->request->getPost('jumlah'),
            'tanggal'    => $this->request->getPost('tanggal'),
            'status'     => $this->request->getPost('status'),
        ];

        // Insert ke tabel simpanan_sukarela
        $simpananSukarelaModel->insert($data);

        // Insert juga ke tabel simpanan utama
        $simpananModel->insert([
            'id_anggota' => $data['id_anggota'],
            'jenis'      => 'sukarela',  // jenis simpanan
            'jumlah'     => $data['jumlah'],
            'status'     => $data['status'],
            'tanggal'    => $data['tanggal'],
        ]);

        return redirect()->back()->with('success', 'Simpanan sukarela berhasil disimpan!');
    }
}
