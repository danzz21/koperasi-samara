<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class RoleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $session = session();

        // ✅ Cek login dulu
        if (! $session->get('isLoggedIn')) {
            return redirect()->to('/login');
        }

        // ✅ Cek role sesuai parameter filter
        if ($arguments) {
            $allowedRoles = $arguments; // contoh: ['admin'] atau ['anggota']
            if (! in_array($session->get('role'), $allowedRoles)) {
                // Kalau role ga sesuai, lempar balik ke dashboard role masing-masing
                if ($session->get('role') == 'admin') {
                    return redirect()->to('/admin/dashboard');
                } elseif ($session->get('role') == 'anggota') {
                    return redirect()->to('/anggota/dashboard');
                } else {
                    return redirect()->to('/login');
                }
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // ga dipakai
    }
}
