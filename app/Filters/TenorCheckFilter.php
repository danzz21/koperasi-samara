<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use App\Models\SimpananPokokModel;

class TenorCheckFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();
        $idAnggota = $session->get('id_anggota');

        if (!$idAnggota) {
            return redirect()->to('/login');
        }

        $simpananPokokModel = new SimpananPokokModel();
        $simpanan = $simpananPokokModel->where('id_anggota', $idAnggota)->first();

        if (!$simpanan || $simpanan['tenor'] === null) {
            $session->setFlashdata('error', 'Anda belum memilih tenor Simpanan Pokok. Silakan tentukan terlebih dahulu sebelum mengakses fitur ini.');
            return redirect()->to('/dashboard');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Tidak perlu digunakan
    }
}
