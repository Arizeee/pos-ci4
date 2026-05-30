<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Belum login sama sekali
        if (!session()->get('logged_in')) {
            return redirect()->to('/auth');
        }

        // Cek role jika filter dipanggil dengan argumen
        // contoh: ['filter' => 'auth:owner,admin']
        if (!empty($arguments)) {
            $userRole    = session()->get('role');
            $allowedRoles = $arguments; // CI4 sudah parsing jadi array

            if (!in_array($userRole, $allowedRoles, true)) {
                return redirect()->to('/auth');
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // tidak perlu apa-apa
    }
}