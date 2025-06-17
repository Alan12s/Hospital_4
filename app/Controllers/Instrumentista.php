<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\InstrumentistaModel;

class Instrumentista extends BaseController
{
    protected $instrumentistaModel;

    public function __construct()
    {
        $this->instrumentistaModel = new InstrumentistaModel();
        helper(['form']);
    }

    public function index()
    {
        $data['titulo'] = 'Lista de Instrumentistas';
        $data['instrumentistas'] = $this->instrumentistaModel->findAll();

        return view('instrumentista/index', $data);
    }

    public function crear()
    {
        $data['titulo'] = 'Crear Instrumentista';
        return view('instrumentista/crear', $data);
    }

    public function guardar()
    {
        if ($this->request->getMethod() === 'post' && $this->validate([
            'nombre' => 'required|min_length[3]',
            'apellido' => 'required|min_length[3]',
            'especialidad' => 'required|min_length[3]'
        ])) {
            $this->instrumentistaModel->save([
                'nombre' => $this->request->getPost('nombre'),
                'apellido' => $this->request->getPost('apellido'),
                'especialidad' => $this->request->getPost('especialidad'),
            ]);
            return redirect()->to('/instrumentista')->with('mensaje', 'Instrumentista creado correctamente');
        } else {
            return redirect()->back()->withInput()->with('error', 'Por favor, corrige los errores');
        }
    }

    public function editar($id)
    {
        $data['titulo'] = 'Editar Instrumentista';
        $data['instrumentista'] = $this->instrumentistaModel->find($id);

        if (!$data['instrumentista']) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException("No se encontró al instrumentista con ID $id");
        }

        return view('instrumentista/editar', $data);
    }

    public function actualizar($id)
    {
        if ($this->request->getMethod() === 'post' && $this->validate([
            'nombre' => 'required|min_length[3]',
            'apellido' => 'required|min_length[3]',
            'especialidad' => 'required|min_length[3]'
        ])) {
            $this->instrumentistaModel->update($id, [
                'nombre' => $this->request->getPost('nombre'),
                'apellido' => $this->request->getPost('apellido'),
                'especialidad' => $this->request->getPost('especialidad'),
            ]);
            return redirect()->to('/instrumentista')->with('mensaje', 'Instrumentista actualizado correctamente');
        } else {
            return redirect()->back()->withInput()->with('error', 'Por favor, corrige los errores');
        }
    }

    public function eliminar($id)
    {
        $this->instrumentistaModel->delete($id);
        return redirect()->to('/instrumentista')->with('mensaje', 'Instrumentista eliminado');
    }
}