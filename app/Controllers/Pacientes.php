<?php

namespace App\Controllers;

use App\Models\PacienteModel;
use CodeIgniter\Controller;

class Pacientes extends Controller
{
    protected $pacienteModel;
    protected $session;

    public function __construct()
    {
        $this->pacienteModel = new PacienteModel();
        $this->session = \Config\Services::session();

        helper(['form', 'url']);
    }

    public function index()
    {
        $data = [
            'pacientes' => $this->pacienteModel->getAllPacientes(),
            'titulo' => 'Gestión de Pacientes'
        ];

        return view('pacientes/index', $data);
    }

    public function crear()
    {
        $data = ['titulo' => 'Crear Paciente'];
        return view('pacientes/crear', $data);
    }

    public function add()
    {
        $rules = [
            'nombre'            => 'required|max_length[100]|regex_match[/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/]',
            'historial_medico'  => 'required|max_length[100]',
            'dni'               => 'required|numeric|is_unique[pacientes.dni]',
            'email'             => 'required|valid_email|is_unique[pacientes.email]',
            'telefono'          => 'required|numeric|max_length[20]',
            'direccion'         => 'required|max_length[150]',
            'fecha_nacimiento'  => 'required|valid_date',
            'obra_social'       => 'required',
            'departamento'     => 'required'
        ];

        $messages = [
            'nombre' => [
                'required' => 'El nombre es obligatorio',
                'max_length' => 'El nombre no puede exceder los 100 caracteres',
                'regex_match' => 'El campo nombre solo puede contener letras y espacios.'
            ],
            'dni' => [
                'required' => 'El DNI es obligatorio',
                'numeric' => 'El DNI debe contener solo números.',
                'is_unique' => 'Este DNI ya está registrado'
            ],
            'email' => [
                'required' => 'El email es obligatorio',
                'valid_email' => 'Ingrese un email válido',
                'is_unique' => 'Este email ya está registrado'
            ],
            'telefono' => [
                'required' => 'El teléfono es obligatorio',
                'numeric' => 'El teléfono debe contener solo números.',
                'max_length' => 'El teléfono no puede exceder los 20 caracteres'
            ],
            'direccion' => [
                'required' => 'La dirección es obligatoria',
                'max_length' => 'La dirección no puede exceder los 150 caracteres'
            ],
            'fecha_nacimiento' => [
                'required' => 'La fecha de nacimiento es obligatoria',
                'valid_date' => 'Ingrese una fecha válida'
            ],
            'obra_social' => [
                'required' => 'La obra social es obligatoria'
            ],
            'departamento' => [
                'required' => 'El departamento es obligatorio'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()
                             ->withInput()
                             ->with('errors', $this->validator->getErrors());
        }

        $data = [
            'nombre'            => $this->request->getPost('nombre'),
            'fecha_nacimiento'  => $this->request->getPost('fecha_nacimiento'),
            'historial_medico'  => $this->request->getPost('historial_medico'),
            'obra_social'       => $this->request->getPost('obra_social'),
            'dni'               => $this->request->getPost('dni'),
            'email'             => $this->request->getPost('email'),
            'telefono'          => $this->request->getPost('telefono'),
            'departamento'      => $this->request->getPost('departamento'),
            'direccion'         => $this->request->getPost('direccion')
        ];

        if ($this->pacienteModel->insert($data)) {
            $this->session->setFlashdata('mensaje', 'Paciente creado exitosamente');
            return redirect()->to('pacientes');
        } else {
            $this->session->setFlashdata('error', 'No se pudo crear el paciente');
            return redirect()->back()->withInput();
        }
    }

    public function edit($id)
    {
        $paciente = $this->pacienteModel->getPaciente($id);

        if (empty($paciente)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data = [
            'titulo' => 'Editar Paciente',
            'paciente' => $paciente
        ];

        return view('pacientes/editar', $data);
    }

    public function update($id)
    {
        $rules = [
            'nombre'            => 'required|max_length[100]|regex_match[/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/]',
            'historial_medico'  => 'required|max_length[400]',
            'dni'               => 'required|numeric|max_length[20]',
            'email'             => 'required|valid_email',
            'telefono'          => 'required|numeric|max_length[20]',
            'direccion'         => 'required|max_length[150]',
            'fecha_nacimiento'  => 'required|valid_date',
            'obra_social'       => 'required',
            'departamento'      => 'required'
        ];

        $messages = [
            'nombre' => [
                'required' => 'El nombre es obligatorio',
                'max_length' => 'El nombre no puede exceder los 100 caracteres',
                'regex_match' => 'El campo nombre solo puede contener letras y espacios.'
            ],
            'dni' => [
                'required' => 'El DNI es obligatorio',
                'numeric' => 'El DNI debe contener solo números.',
                'max_length' => 'El DNI no puede exceder los 20 caracteres'
            ],
            'email' => [
                'required' => 'El email es obligatorio',
                'valid_email' => 'Ingrese un email válido'
            ],
            'telefono' => [
                'required' => 'El teléfono es obligatorio',
                'numeric' => 'El teléfono debe contener solo números.',
                'max_length' => 'El teléfono no puede exceder los 20 caracteres'
            ],
            'direccion' => [
                'required' => 'La dirección es obligatoria',
                'max_length' => 'La dirección no puede exceder los 150 caracteres'
            ],
            'fecha_nacimiento' => [
                'required' => 'La fecha de nacimiento es obligatoria',
                'valid_date' => 'Ingrese una fecha válida'
            ],
            'obra_social' => [
                'required' => 'La obra social es obligatoria'
            ],
            'departamento' => [
                'required' => 'El departamento es obligatorio'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()
                             ->withInput()
                             ->with('errors', $this->validator->getErrors());
        }

        // Validación manual para evitar duplicados
        if ($this->pacienteModel->dniExists($this->request->getPost('dni'), $id)) {
            $this->session->setFlashdata('error', 'El DNI ya está en uso');
            return redirect()->to("pacientes/edit/$id")->withInput();
        }

        if ($this->pacienteModel->emailExists($this->request->getPost('email'), $id)) {
            $this->session->setFlashdata('error', 'El email ya está en uso');
            return redirect()->to("pacientes/edit/$id")->withInput();
        }

        $data = [
            'nombre'            => $this->request->getPost('nombre'),
            'fecha_nacimiento'  => $this->request->getPost('fecha_nacimiento'),
            'historial_medico'  => $this->request->getPost('historial_medico'),
            'obra_social'       => $this->request->getPost('obra_social'),
            'dni'               => $this->request->getPost('dni'),
            'email'             => $this->request->getPost('email'),
            'telefono'          => $this->request->getPost('telefono'),
            'departamento'      => $this->request->getPost('departamento'),
            'direccion'         => $this->request->getPost('direccion')
        ];

        if ($this->pacienteModel->update($id, $data)) {
            $this->session->setFlashdata('mensaje', 'Paciente actualizado exitosamente');
            return redirect()->to('pacientes');
        } else {
            $this->session->setFlashdata('error', 'No se pudo actualizar el paciente');
            return redirect()->back()->withInput();
        }
    }

    public function view($id)
    {
        $paciente = $this->pacienteModel->getPaciente($id);

        if (empty($paciente)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data = [
            'paciente' => $paciente,
            'titulo' => 'Detalles del Paciente'
        ];

        return view('pacientes/view', $data);
    }

    public function delete($id)
    {
        $paciente = $this->pacienteModel->getPaciente($id);

        if (empty($paciente)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        if ($this->pacienteModel->delete($id)) {
            $this->session->setFlashdata('mensaje', 'Paciente eliminado exitosamente');
        } else {
            $this->session->setFlashdata('error', 'No se pudo eliminar el paciente');
        }

        return redirect()->to('pacientes');
    }
}