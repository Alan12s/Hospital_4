<?php

namespace App\Controllers;

use App\Models\UsuarioModel;
use CodeIgniter\Controller;

class Usuarios extends Controller
{
    protected $usuarioModel;
    protected $session;

    public function __construct()
    {
        $this->usuarioModel = new UsuarioModel();
        $this->session = \Config\Services::session();

        helper(['form', 'url']);
    }

    public function index()
    {
        $data = [
            'usuarios' => $this->usuarioModel->getAllUsuarios(),
            'titulo' => 'Gestión de Usuarios'
        ];

        return view('usuarios/index', $data);
    }

    public function crear()
    {
        $data = ['titulo' => 'Crear Usuario'];
        return view('usuarios/crear', $data);
    }

    public function add()
    {
        $rules = [
            'nombre'                => 'required|max_length[50]|regex_match[/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/]',
            'apellidos'             => 'required|max_length[100]|regex_match[/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/]',
            'email'                 => 'required|valid_email|max_length[50]|is_unique[usuarios.email]',
            'username'              => 'required|max_length[50]|alpha_dash|is_unique[usuarios.username]',
            'password'              => 'required|min_length[6]',
            'password_confirm'      => 'required|matches[password]',
            'rol'                   => 'required|in_list[administrador,medico,enfermero,supervisor,usuario]',
            'estado'                => 'required|in_list[0,1]'
        ];

        $messages = [
            'nombre' => [
                'required' => 'El nombre es obligatorio',
                'max_length' => 'El nombre no puede exceder los 50 caracteres',
                'regex_match' => 'El nombre solo puede contener letras y espacios'
            ],
            'apellidos' => [
                'required' => 'Los apellidos son obligatorios',
                'max_length' => 'Los apellidos no pueden exceder los 100 caracteres',
                'regex_match' => 'Los apellidos solo pueden contener letras y espacios'
            ],
            'email' => [
                'required' => 'El email es obligatorio',
                'valid_email' => 'Ingrese un email válido',
                'max_length' => 'El email no puede exceder los 50 caracteres',
                'is_unique' => 'Este email ya está registrado'
            ],
            'username' => [
                'required' => 'El nombre de usuario es obligatorio',
                'max_length' => 'El nombre de usuario no puede exceder los 50 caracteres',
                'alpha_dash' => 'El nombre de usuario solo puede contener letras, números, guiones y guiones bajos',
                'is_unique' => 'Este nombre de usuario ya está en uso'
            ],
            'password' => [
                'required' => 'La contraseña es obligatoria',
                'min_length' => 'La contraseña debe tener al menos 6 caracteres'
            ],
            'password_confirm' => [
                'required' => 'La confirmación de contraseña es obligatoria',
                'matches' => 'Las contraseñas no coinciden'
            ],
            'rol' => [
                'required' => 'El rol es obligatorio',
                'in_list' => 'Seleccione un rol válido'
            ],
            'estado' => [
                'required' => 'El estado es obligatorio',
                'in_list' => 'Seleccione un estado válido'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()
                             ->withInput()
                             ->with('errors', $this->validator->getErrors());
        }

        $data = [
            'nombre'           => $this->request->getPost('nombre'),
            'apellidos'        => $this->request->getPost('apellidos'),
            'email'            => $this->request->getPost('email'),
            'username'         => $this->request->getPost('username'),
            'password'         => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'rol'              => $this->request->getPost('rol'),
            'estado'           => $this->request->getPost('estado'),
            'fecha_registro'   => date('Y-m-d H:i:s')
        ];

        if ($this->usuarioModel->insert($data)) {
            $this->session->setFlashdata('mensaje', 'Usuario creado exitosamente');
            return redirect()->to('usuarios');
        } else {
            $this->session->setFlashdata('error', 'No se pudo crear el usuario');
            return redirect()->back()->withInput();
        }
    }

    public function edit($id)
    {
        $usuario = $this->usuarioModel->getUsuario($id);

        if (empty($usuario)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data = [
            'titulo' => 'Editar Usuario',
            'usuario' => $usuario
        ];

        return view('usuarios/editar', $data);
    }

    public function update($id)
    {
        $rules = [
            'nombre'                => 'required|max_length[50]|regex_match[/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/]',
            'apellidos'             => 'required|max_length[100]|regex_match[/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/]',
            'email'                 => 'required|valid_email|max_length[50]',
            'username'              => 'required|max_length[50]|alpha_dash',
            'rol'                   => 'required|in_list[administrador,medico,enfermero,supervisor,usuario]',
            'estado'                => 'required|in_list[0,1]'
        ];

        $messages = [
            'nombre' => [
                'required' => 'El nombre es obligatorio',
                'max_length' => 'El nombre no puede exceder los 50 caracteres',
                'regex_match' => 'El nombre solo puede contener letras y espacios'
            ],
            'apellidos' => [
                'required' => 'Los apellidos son obligatorios',
                'max_length' => 'Los apellidos no pueden exceder los 100 caracteres',
                'regex_match' => 'Los apellidos solo pueden contener letras y espacios'
            ],
            'email' => [
                'required' => 'El email es obligatorio',
                'valid_email' => 'Ingrese un email válido',
                'max_length' => 'El email no puede exceder los 50 caracteres'
            ],
            'username' => [
                'required' => 'El nombre de usuario es obligatorio',
                'max_length' => 'El nombre de usuario no puede exceder los 50 caracteres',
                'alpha_dash' => 'El nombre de usuario solo puede contener letras, números, guiones y guiones bajos'
            ],
            'rol' => [
                'required' => 'El rol es obligatorio',
                'in_list' => 'Seleccione un rol válido'
            ],
            'estado' => [
                'required' => 'El estado es obligatorio',
                'in_list' => 'Seleccione un estado válido'
            ]
        ];

        // Validaciones para cambio de contraseña
        $password = $this->request->getPost('password');
        if (!empty($password)) {
            $rules['password'] = 'min_length[6]';
            $rules['password_confirm'] = 'matches[password]';
            $messages['password'] = [
                'min_length' => 'La contraseña debe tener al menos 6 caracteres'
            ];
            $messages['password_confirm'] = [
                'matches' => 'Las contraseñas no coinciden'
            ];
        }

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()
                             ->withInput()
                             ->with('errors', $this->validator->getErrors());
        }

        // Validación manual para evitar duplicados
        if ($this->usuarioModel->emailExists($this->request->getPost('email'), $id)) {
            $this->session->setFlashdata('error', 'El email ya está en uso');
            return redirect()->to("usuarios/edit/$id")->withInput();
        }

        if ($this->usuarioModel->usernameExists($this->request->getPost('username'), $id)) {
            $this->session->setFlashdata('error', 'El nombre de usuario ya está en uso');
            return redirect()->to("usuarios/edit/$id")->withInput();
        }

        $data = [
            'nombre'        => $this->request->getPost('nombre'),
            'apellidos'     => $this->request->getPost('apellidos'),
            'email'         => $this->request->getPost('email'),
            'username'      => $this->request->getPost('username'),
            'rol'           => $this->request->getPost('rol'),
            'estado'        => $this->request->getPost('estado')
        ];

        // Solo actualizar contraseña si se proporcionó una nueva
        if (!empty($password)) {
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        if ($this->usuarioModel->update($id, $data)) {
            $this->session->setFlashdata('mensaje', 'Usuario actualizado exitosamente');
            return redirect()->to('usuarios');
        } else {
            $this->session->setFlashdata('error', 'No se pudo actualizar el usuario');
            return redirect()->back()->withInput();
        }
    }

    public function view($id)
    {
        $usuario = $this->usuarioModel->getUsuario($id);

        if (empty($usuario)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data = [
            'usuario' => $usuario,
            'titulo' => 'Detalles del Usuario'
        ];

        return view('usuarios/view', $data);
    }

    public function delete($id)
    {
        $usuario = $this->usuarioModel->getUsuario($id);

        if (empty($usuario)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // Validar que no se elimine el usuario actual (opcional)
        // $currentUserId = session()->get('user_id');
        // if ($id == $currentUserId) {
        //     $this->session->setFlashdata('error', 'No puedes eliminar tu propio usuario');
        //     return redirect()->to('usuarios');
        // }

        if ($this->usuarioModel->delete($id)) {
            $this->session->setFlashdata('mensaje', 'Usuario eliminado exitosamente');
        } else {
            $this->session->setFlashdata('error', 'No se pudo eliminar el usuario');
        }

        return redirect()->to('usuarios');
    }

  public function toggle_status($id)
{
    $usuario = $this->usuarioModel->getUsuario($id);

    if (empty($usuario)) {
        throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
    }

    // Validar que no se cambie el estado del usuario actual (opcional)
     $currentUserId = session()->get('user_id');
 if ($id == $currentUserId) {
         $this->session->setFlashdata('error', 'No puedes cambiar tu propio estado');
         return redirect()->to('usuarios');
     }

    $newStatus = $usuario->estado == 1 ? 0 : 1;
    $statusText = $newStatus == 1 ? 'activado' : 'desactivado';

    if ($this->usuarioModel->update($id, ['estado' => $newStatus])) {
        $this->session->setFlashdata('mensaje', "Usuario $statusText exitosamente");
    } else {
        $this->session->setFlashdata('error', 'No se pudo cambiar el estado del usuario');
    }

    return redirect()->to('usuarios');
}
}
