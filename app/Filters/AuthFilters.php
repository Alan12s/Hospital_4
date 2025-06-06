<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Verificar si el usuario está logueado
        if (!session()->get('logged_in')) {
            // Si no está logueado, redirigir al login
            return redirect()->to('/login')->with('error', 'Debes iniciar sesión para acceder a esta página');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No necesitamos hacer nada después de la respuesta
    }
}