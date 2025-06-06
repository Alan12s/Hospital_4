<?php

namespace App\Controllers;

use App\Models\UsuarioModel;

class Home extends BaseController
{
    protected $usuarioModel;

    public function __construct()
    {
        $this->usuarioModel = new UsuarioModel();
        helper(['form', 'url']);
    }

    public function index()
    {
        // Si ya está logueado, redirigir al inicio
        if (session()->get('logged_in')) {
            return redirect()->to('/inicio');
        }
        
        return view('login');
    }

    public function login()
    {
        $session = session();
        
        // Validación
        $validation = \Config\Services::validation();
        $validation->setRules([
            'username' => 'required|min_length[3]',
            'password' => 'required|min_length[6]'
        ], [
            'username' => [
                'required' => 'El nombre de usuario es obligatorio',
                'min_length' => 'El nombre de usuario debe tener al menos 3 caracteres'
            ],
            'password' => [
                'required' => 'La contraseña es obligatoria',
                'min_length' => 'La contraseña debe tener al menos 6 caracteres'
            ]
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return view('login', ['validation' => $validation]);
        }

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        // Buscar usuario
        $user = $this->usuarioModel->where('username', $username)
                                   ->where('estado', 1)
                                   ->first();

        if ($user && password_verify($password, $user['password'])) {
            // Actualizar último acceso
            $this->usuarioModel->update($user['id'], [
                'ultimo_acceso' => date('Y-m-d H:i:s')
            ]);

            // Establecer sesión
            $sessionData = [
                'user_id' => $user['id'],
                'username' => $user['username'],
                'nombre' => $user['nombre'],
                'apellidos' => $user['apellidos'],
                'email' => $user['email'],
                'rol' => $user['rol'],
                'logged_in' => true
            ];
            
            $session->set($sessionData);
            
            return redirect()->to('/inicio')->with('success', '¡Bienvenido ' . $user['nombre'] . '!');
        } else {
            return view('login', [
                'error' => 'Credenciales incorrectas. Verifique su usuario y contraseña.',
                'username' => $username
            ]);
        }
    }

    public function inicio()
    {
        // Verificar si está logueado
        if (!session()->get('logged_in')) {
            return redirect()->to('/')->with('error', 'Debe iniciar sesión para acceder.');
        }

        return view('inicio');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/')->with('success', 'Sesión cerrada correctamente.');
    }
}