<?php

namespace App\Controllers;
use App\Models\AnestesistaModel;
use CodeIgniter\Controller;

class Anestesistas extends BaseController
{
    protected $anestesistaModel;
    protected $session;
    protected $usuarioRol;

    public function __construct()
    {
        helper(['form', 'url']);
        $this->session = session();
        $this->anestesistaModel = new AnestesistaModel();

        // Verificar sesión
        if (!$this->session->get('id')) {
            return redirect()->to(base_url('auth/login'))->send();
        }

        // Rol
        $this->usuarioRol = $this->session->get('rol');
        if (!in_array($this->usuarioRol, ['administrador', 'supervisor'])) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('No tiene permisos para acceder a esta sección.');
        }

        // Actualizar disponibilidad
        $this->anestesistaModel->actualizarDisponibilidadAutomatica();
    }

    public function index()
    {
        $data = [
            'titulo' => 'Gestión de Anestesistas',
            'anestesistas' => $this->anestesistaModel->listarAnestesistas(),
            'permisos' => $this->usuarioRol
        ];
        return view('anestesistas/index', $data)
            . view('includes/footer');
    }

    public function crear()
    {
        if (!in_array($this->usuarioRol, ['administrador', 'supervisor'])) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('No tiene permisos para esta acción.');
        }

        $validation = \Config\Services::validation();

        if ($this->request->getMethod() === 'post') {
            $rules = [
                'nombre'        => 'required|max_length[100]',
                'especialidad'  => 'required|max_length[100]',
                'disponibilidad'=> 'required',
                'telefono'      => 'required|max_length[20]',
                'email'         => 'required|valid_email|max_length[100]|is_unique[anestesistas.email]'
            ];

            if (!$this->validate($rules)) {
                return view('anestesistas/crear', ['titulo' => 'Crear Anestesista', 'validation' => $validation])
                    . view('includes/footer');
            }

            $this->anestesistaModel->save([
                'nombre'        => $this->request->getPost('nombre'),
                'especialidad'  => $this->request->getPost('especialidad'),
                'disponibilidad'=> $this->request->getPost('disponibilidad'),
                'telefono'      => $this->request->getPost('telefono'),
                'email'         => $this->request->getPost('email')
            ]);

            $this->session->setFlashdata('mensaje', 'Anestesista creado exitosamente');
            return redirect()->to('/anestesistas');
        }

        return view('anestesistas/crear', ['titulo' => 'Crear Anestesista'])
            . view('includes/footer');
    }

    public function editar($id = null)
    {
        if (!in_array($this->usuarioRol, ['administrador', 'supervisor']) || !$id) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $anestesista = $this->anestesistaModel->find($id);
        if (!$anestesista) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $validation = \Config\Services::validation();

        if ($this->request->getMethod() === 'post') {
            $emailRule = 'required|valid_email|max_length[100]';
            if ($this->request->getPost('email') != $anestesista['email']) {
                $emailRule .= '|is_unique[anestesistas.email]';
            }

            $rules = [
                'nombre'        => 'required|max_length[100]',
                'especialidad'  => 'required|max_length[100]',
                'disponibilidad'=> 'required',
                'telefono'      => 'required|max_length[20]',
                'email'         => $emailRule
            ];

            if (!$this->validate($rules)) {
                return view('anestesistas/editar', ['titulo' => 'Editar Anestesista', 'anestesista' => $anestesista, 'validation' => $validation])
                    . view('includes/footer');
            }

            $this->anestesistaModel->update($id, [
                'nombre'        => $this->request->getPost('nombre'),
                'especialidad'  => $this->request->getPost('especialidad'),
                'disponibilidad'=> $this->request->getPost('disponibilidad'),
                'telefono'      => $this->request->getPost('telefono'),
                'email'         => $this->request->getPost('email')
            ]);

            $this->session->setFlashdata('mensaje', 'Anestesista actualizado exitosamente');
            return redirect()->to('/anestesistas');
        }

        return view('anestesistas/editar', ['titulo' => 'Editar Anestesista', 'anestesista' => $anestesista])
            . view('includes/footer');
    }

    public function ver($id = null)
    {
        $anestesista = $this->anestesistaModel->find($id);
        if (!$anestesista) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return view('anestesistas/ver', [
            'titulo' => 'Detalles del Anestesista',
            'anestesista' => $anestesista,
            'permisos' => $this->usuarioRol
        ])->render() . view('includes/footer');
    }

    public function eliminar($id = null)
    {
        if ($this->usuarioRol !== 'administrador') {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('No tiene permisos para esta acción.');
        }

        $anestesista = $this->anestesistaModel->find($id);
        if (!$anestesista) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        if ($this->anestesistaModel->tieneTurnosProgramados($id)) {
            $this->session->setFlashdata('error', 'No se puede eliminar el anestesista porque tiene turnos programados.');
            return redirect()->to('/anestesistas');
        }

        $this->anestesistaModel->delete($id);
        $this->session->setFlashdata('mensaje', 'Anestesista eliminado exitosamente');
        return redirect()->to('/anestesistas');
    }

    public function disponibles()
    {
        $data = [
            'titulo' => 'Anestesistas Disponibles',
            'anestesistas' => $this->anestesistaModel->verificarDisponibilidad('disponible'),
            'permisos' => $this->usuarioRol
        ];
        return view('anestesistas/disponibles', $data)
            . view('includes/footer');
    }
}
