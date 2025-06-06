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
            'nombre'    => 'required|max_length[100]',
            'dni'       => 'required|is_unique[pacientes.dni]',
            'email'     => 'required|valid_email|is_unique[pacientes.email]',
            'telefono'  => 'required|max_length[20]',
            'direccion' => 'required|max_length[150]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()
                             ->withInput()
                             ->with('errors', $this->validator->getErrors());
        }

        $data = [
            'nombre'    => $this->request->getPost('nombre'),
            'dni'       => $this->request->getPost('dni'),
            'email'     => $this->request->getPost('email'),
            'telefono'  => $this->request->getPost('telefono'),
            'direccion' => $this->request->getPost('direccion')
        ];

        if ($this->pacienteModel->insert($data)) {
            $this->session->setFlashdata('success', 'Paciente creado exitosamente');
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
            'nombre'    => 'required|max_length[100]',
            'dni'       => 'required|numeric|max_length[20]',
            'email'     => 'required|valid_email',
            'telefono'  => 'required|max_length[20]',
            'direccion' => 'required|max_length[150]'
        ];

        if (!$this->validate($rules)) {
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
            'nombre'    => $this->request->getPost('nombre'),
            'dni'       => $this->request->getPost('dni'),
            'email'     => $this->request->getPost('email'),
            'telefono'  => $this->request->getPost('telefono'),
            'direccion' => $this->request->getPost('direccion')
        ];

        if ($this->pacienteModel->update($id, $data)) {
            $this->session->setFlashdata('success', 'Paciente actualizado exitosamente');
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

        if ($this->pacienteModel->deletePaciente($id)) {
            $this->session->setFlashdata('success', 'Paciente eliminado exitosamente');
        } else {
            $this->session->setFlashdata('error', 'No se pudo eliminar el paciente');
        }

        return redirect()->to('pacientes');
    }
}
