<?php
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CirujanoModel;
use CodeIgniter\HTTP\RedirectResponse;

class Cirujanos extends BaseController
{
    protected $cirujanoModel;

    public function __construct()
    {
        $this->cirujanoModel = new CirujanoModel();
    }

    public function index(): string
    {
        $data = [
            'titulo' => 'Gestión de Cirujanos',
            'cirujanos' => $this->cirujanoModel->listarMedicos() // Usar el método correcto
        ];

        return view('cirujanos/index', $data);
    }

    public function disponibles(): string
    {
        $data = [
            'titulo' => 'Cirujanos Disponibles',
            'cirujanos' => $this->cirujanoModel->medicosDisponibles()
        ];

        return view('cirujanos/disponibles', $data);
    }

    public function crear()
    {
        helper(['form']);

        if ($this->request->getMethod() === 'post' && $this->validate([
            'nombre' => 'required|max_length[100]',
            'dni' => 'required|max_length[20]|is_unique[cirujanos.dni]',
            'id_especialidad' => 'required|integer',
            'telefono' => 'required|max_length[20]',
            'email' => 'required|valid_email|max_length[100]|is_unique[cirujanos.email]',
            'disponibilidad' => 'required'
        ])) {
            $this->cirujanoModel->saveMedico($this->request->getPost());
            return redirect()->to('/cirujanos')->with('mensaje', 'Cirujano creado exitosamente');
        }

        $data = [
            'titulo' => 'Crear Cirujano',
            'especialidades' => $this->cirujanoModel->getEspecialidades(),
            'validation' => $this->validator
        ];

        return view('cirujanos/crear', $data);
    }

    public function editar($id = null)
    {
        $cirujano = $this->cirujanoModel->buscarPorID($id);
        if (!$cirujano) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Cirujano no encontrado');
        }

        helper(['form']);

        if ($this->request->getMethod() === 'post' && $this->validate([
            'nombre' => 'required|max_length[100]',
            'dni' => "required|max_length[20]|is_unique[cirujanos.dni,id,{$id}]",
            'id_especialidad' => 'required|integer',
            'telefono' => 'required|max_length[20]',
            'email' => "required|valid_email|max_length[100]|is_unique[cirujanos.email,id,{$id}]",
            'disponibilidad' => 'required'
        ])) {
            $this->cirujanoModel->editar($this->request->getPost(), $id);
            return redirect()->to('/cirujanos')->with('mensaje', 'Cirujano actualizado exitosamente');
        }

        $data = [
            'titulo' => 'Editar Cirujano',
            'cirujano' => $cirujano,
            'especialidades' => $this->cirujanoModel->getEspecialidades(),
            'validation' => $this->validator
        ];

        return view('cirujanos/editar', $data);
    }

    public function ver($id = null): string
    {
        $cirujano = $this->cirujanoModel->buscarPorID($id);
        if (!$cirujano) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Cirujano no encontrado');
        }

        $data = [
            'titulo' => 'Detalles del Cirujano',
            'cirujano' => $cirujano
        ];

        return view('cirujanos/ver', $data);
    }

    public function eliminar($id = null)
    {
        $cirujano = $this->cirujanoModel->buscarPorID($id);
        if (!$cirujano) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Cirujano no encontrado');
        }

        if ($this->cirujanoModel->tieneTurnosProgramados($id)) {
            return redirect()->to('/cirujanos')->with('error', 'No se puede eliminar el cirujano porque tiene turnos quirúrgicos programados');
        }

        $this->cirujanoModel->eliminar($id);
        return redirect()->to('/cirujanos')->with('mensaje', 'Cirujano eliminado exitosamente');
    }
}