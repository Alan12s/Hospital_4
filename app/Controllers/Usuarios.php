<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UsuarioModel;
use CodeIgniter\HTTP\RedirectResponse;

class Usuarios extends BaseController
{
    protected $usuarioModel;

    public function __construct()
    {
        $this->usuarioModel = new UsuarioModel();
    }

    public function index(): string
    {
        $data = [
            'titulo' => 'Gestión de Usuarios',
            'usuarios' => $this->usuarioModel->listarUsuarios()
        ];

        return view('usuarios/index', $data);
    }

    public function crear()
    {
        helper(['form']);

        if ($this->request->getMethod() === 'post') {
            $rules = [
                'nombre' => 'required|max_length[50]|regex_match[/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/]',
                'apellidos' => 'required|max_length[100]|regex_match[/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/]',
                'email' => 'required|valid_email|max_length[50]|is_unique[usuarios.email]',
                'username' => 'required|max_length[50]|alpha_dash|is_unique[usuarios.username]',
                'password' => 'required|min_length[6]',
                'password_confirm' => 'required|matches[password]',
                'rol' => 'required|in_list[administrador,cirujano,enfermero,supervisor,usuario]',
                'estado' => 'required|in_list[0,1]'
            ];

            if ($this->validate($rules)) {
                try {
                    $postData = $this->request->getPost();
                    
                    unset($postData['password_confirm']);
                    
                    $postData['password'] = password_hash($postData['password'], PASSWORD_DEFAULT);
                    
                    if ($this->usuarioModel->insert($postData)) {
                        return redirect()->to('/usuarios')->with('mensaje', 'Usuario creado exitosamente');
                    } else {
                        $errores = $this->usuarioModel->errors();
                        $mensajeError = 'Error al crear el usuario.';
                        
                        if (!empty($errores)) {
                            $mensajeError .= ' Detalles: ' . (is_array($errores) ? implode(', ', $errores) : $errores);
                        }
                        
                        return redirect()->back()->withInput()->with('error', $mensajeError);
                    }
                } catch (\Exception $e) {
                    log_message('error', 'Error al crear usuario: ' . $e->getMessage());
                    return redirect()->back()->withInput()->with('error', 'Error interno al crear el usuario: ' . $e->getMessage());
                }
            } else {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }
        }

        $data = [
            'titulo' => 'Crear Usuario',
            'validation' => $this->validator ?? null
        ];

        return view('usuarios/crear', $data);
    }

    public function editar($id = null)
{
    $usuario = $this->usuarioModel->find($id);
    if (!$usuario) {
        throw new \CodeIgniter\Exceptions\PageNotFoundException('Usuario no encontrado');
    }

    helper(['form']);

    if ($this->request->getMethod() === 'post') {
        $rules = [
            'nombre' => 'required|max_length[50]|regex_match[/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/]',
            'apellidos' => 'required|max_length[100]|regex_match[/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/]',
            'email' => "required|valid_email|max_length[50]",
            'username' => "required|max_length[50]|alpha_dash",
            'rol' => 'required|in_list[administrador,cirujano,enfermero,supervisor,usuario]',
            'estado' => 'required|in_list[0,1]'
        ];

        // Validación personalizada para email único
        $email = $this->request->getPost('email');
        if (!$this->usuarioModel->isUniqueEmail($email, $id)) {
            return redirect()->back()->withInput()->with('error', 'Este email ya está registrado por otro usuario');
        }

        // Validación personalizada para username único
        $username = $this->request->getPost('username');
        if (!$this->usuarioModel->isUniqueUsername($username, $id)) {
            return redirect()->back()->withInput()->with('error', 'Este nombre de usuario ya está en uso por otro usuario');
        }

        if ($this->request->getPost('password')) {
            $rules['password'] = 'min_length[6]';
            $rules['password_confirm'] = 'required|matches[password]';
        }

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        try {
            $data = [
                'nombre' => $this->request->getPost('nombre'),
                'apellidos' => $this->request->getPost('apellidos'),
                'email' => $email,
                'username' => $username,
                'rol' => $this->request->getPost('rol'),
                'estado' => $this->request->getPost('estado')
            ];

            if ($this->request->getPost('password')) {
                $data['password'] = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
            }

            if ($this->usuarioModel->update($id, $data)) {
                return redirect()->to('/usuarios')->with('mensaje', 'Usuario actualizado exitosamente');
            } else {
                $error = $this->usuarioModel->errors() ? implode(', ', $this->usuarioModel->errors()) : 'Error desconocido';
                return redirect()->back()->withInput()->with('error', 'Error al actualizar el usuario: '.$error);
            }
        } catch (\Exception $e) {
            log_message('error', 'Error al actualizar usuario: '.$e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Error al actualizar el usuario');
        }
    }

    $data = [
        'titulo' => 'Editar Usuario',
        'usuario' => $usuario,
        'validation' => $this->validator ?? null
    ];

    return view('usuarios/editar', $data);
}
    public function ver($id = null)
    {
        $usuario = $this->usuarioModel->find($id);
        if (!$usuario) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Usuario no encontrado');
        }

        $data = [
            'titulo' => 'Detalles del Usuario',
            'usuario' => $usuario
        ];

        return view('usuarios/ver', $data);
    }

    public function cambiarEstado($id = null): RedirectResponse
    {
        $usuario = $this->usuarioModel->find($id);
        if (!$usuario) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Usuario no encontrado');
        }

        if ($id == session()->get('user_id')) {
            return redirect()->to('/usuarios')->with('error', 'No puedes cambiar tu propio estado');
        }

        try {
            $nuevoEstado = $usuario->estado == 1 ? 0 : 1;
            
            if ($this->usuarioModel->update($id, ['estado' => $nuevoEstado])) {
                $mensaje = $nuevoEstado == 1 ? 'Usuario activado exitosamente' : 'Usuario desactivado exitosamente';
                return redirect()->to('/usuarios')->with('mensaje', $mensaje);
            } else {
                return redirect()->to('/usuarios')->with('error', 'Error al cambiar el estado del usuario');
            }
        } catch (\Exception $e) {
            log_message('error', 'Error al cambiar estado: ' . $e->getMessage());
            return redirect()->to('/usuarios')->with('error', 'Error interno al cambiar el estado');
        }
    }

    public function eliminar($id = null): RedirectResponse
    {
        $usuario = $this->usuarioModel->find($id);
        if (!$usuario) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Usuario no encontrado');
        }

        if ($id == session()->get('user_id')) {
            return redirect()->to('/usuarios')->with('error', 'No puedes eliminar tu propio usuario');
        }

        try {
            if ($this->usuarioModel->delete($id)) {
                return redirect()->to('/usuarios')->with('mensaje', 'Usuario eliminado exitosamente');
            } else {
                return redirect()->to('/usuarios')->with('error', 'Error al eliminar el usuario');
            }
        } catch (\Exception $e) {
            log_message('error', 'Error al eliminar usuario: ' . $e->getMessage());
            return redirect()->to('/usuarios')->with('error', 'Error interno al eliminar el usuario');
        }
    }
}
