<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\UserModel;

class AuthController extends Controller
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        // Cargar el Form Helper
        helper('form');
    }

    public function login()
    {
        // Mostrar la vista de login si es una solicitud GET
        if ($this->request->getMethod() === 'get') {
            return view('login');
        }

        // Procesar el formulario de login si es una solicitud POST
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        // Validar que los campos no estén vacíos
        if (empty($username) || empty($password)) {
            session()->setFlashdata('error', 'Usuario y contraseña son obligatorios');
            return redirect()->back();
        }

        // Verificar credenciales usando el modelo
        $user = $this->userModel->verifyCredentials($username, $password);

        if ($user) {
            // Iniciar sesión (guardar datos en la sesión)
            session()->set([
                'user_id' => $user['id'],
                'username' => $user['username'],
                'nombre' => $user['nombre'],
                'apellidos' => $user['apellidos'],
                'email' => $user['email'],
                'rol' => $user['rol'],
                'logged_in' => true
            ]);
            
            // Redirigir a la página de inicio
            return redirect()->to('/inicio');
            
        } else {
            // Si las credenciales son incorrectas, mostrar error
            session()->setFlashdata('error', 'Usuario o contraseña incorrectos');
            return redirect()->back();
        }
    }

    public function home()
    {
        // Verificar si el usuario está logueado
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }

        // Cargar el Form Helper también aquí si la vista lo necesita
        helper('form');
        
        // Cargar la vista de home
        return view('home');
    }

    public function logout()
    {
        // Cerrar sesión
        session()->destroy();
        return redirect()->to('/login');
    }
}