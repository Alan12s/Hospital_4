<?php

namespace App\Controllers;

use App\Models\InsumosModel;
use CodeIgniter\Controller;

class Insumos extends Controller
{
    protected $insumosModel;
    protected $session;

    public function __construct()
    {
        $this->insumosModel = new InsumosModel();
        $this->session = \Config\Services::session();
        
        // Cargar el helper de formularios
        helper(['form', 'url']);
        
        // Verificación de login
        if (!$this->session->get('logged_in')) {
            return redirect()->to('login');
        }
    }

    public function index()
    {
        $data = [
            'insumos' => $this->insumosModel->getAllInsumos(),
            'title' => 'Gestión de Insumos'
        ];

        return view('insumos/index', $data);
    }

    public function crear()
    {
        $data = [
            'title' => 'Agregar Insumo'
        ];

        return view('insumos/crear', $data);
    }

    public function add()
    {
        $validation = \Config\Services::validation();
        
        $validation->setRules([
            'nombre' => 'required|max_length[70]',
            'tipo' => 'required|max_length[50]',
            'categoria' => 'required|in_list[descartable,instrumental]',
            'cantidad' => 'required|numeric',
            'ubicacion' => 'required|max_length[100]'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            // Si la validación falla, volver a mostrar el formulario con errores
            return $this->crear();
        }

        $data = [
            'nombre' => $this->request->getPost('nombre'),
            'tipo' => $this->request->getPost('tipo'),
            'categoria' => $this->request->getPost('categoria'),
            'cantidad' => $this->request->getPost('cantidad'),
            'ubicacion' => $this->request->getPost('ubicacion'),
            'lote' => $this->request->getPost('lote'),
            'tiene_vencimiento' => $this->request->getPost('tiene_vencimiento') ? 1 : 0,
            'fecha_vencimiento' => $this->request->getPost('tiene_vencimiento') ? 
                                 $this->request->getPost('fecha_vencimiento') : null
        ];

        if ($this->insumosModel->addInsumo($data)) {
            $this->session->setFlashdata('success', 'Insumo agregado correctamente');
        } else {
            $this->session->setFlashdata('error', 'Error al agregar el insumo');
        }

        return redirect()->to('insumos');
    }

    public function edit($id)
    {
        $insumo = $this->insumosModel->getInsumo($id);

        if (empty($insumo)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data = [
            'insumo' => $insumo,
            'title' => 'Editar Insumo'
        ];

        return view('insumos/editar', $data);
    }

    public function update($id)
    {
        $validation = \Config\Services::validation();
        
        $validation->setRules([
            'nombre' => 'required|max_length[70]',
            'tipo' => 'required|max_length[50]',
            'categoria' => 'required|in_list[descartable,instrumental]',
            'cantidad' => 'required|numeric',
            'ubicacion' => 'required|max_length[100]'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            // Si la validación falla, volver a mostrar el formulario de edición
            $data = [
                'insumo' => $this->insumosModel->getInsumo($id),
                'title' => 'Editar Insumo'
            ];
            return view('insumos/editar', $data);
        }

        $data = [
            'nombre' => $this->request->getPost('nombre'),
            'tipo' => $this->request->getPost('tipo'),
            'categoria' => $this->request->getPost('categoria'),
            'cantidad' => $this->request->getPost('cantidad'),
            'ubicacion' => $this->request->getPost('ubicacion'),
            'lote' => $this->request->getPost('lote'),
            'tiene_vencimiento' => $this->request->getPost('tiene_vencimiento') ? 1 : 0,
            'fecha_vencimiento' => $this->request->getPost('tiene_vencimiento') ? 
                                 $this->request->getPost('fecha_vencimiento') : null
        ];

        if ($this->insumosModel->updateInsumo($id, $data)) {
            $this->session->setFlashdata('success', 'Insumo actualizado correctamente');
        } else {
            $this->session->setFlashdata('error', 'Error al actualizar el insumo');
        }

        return redirect()->to('insumos');
    }

    /**
     * Método para eliminar insumo
     */
    public function delete($id)
    {
        // Verificar que el insumo existe
        $insumo = $this->insumosModel->getInsumo($id);

        if (!$insumo) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // Verificar si el insumo está en uso
        if ($this->insumosModel->estaEnUso($id)) {
            $this->session->setFlashdata('error', 'No se puede eliminar el insumo porque está siendo utilizado en turnos quirúrgicos');
        } else {
            // Intentar eliminar el insumo
            if ($this->insumosModel->deleteInsumo($id)) {
                $this->session->setFlashdata('success', 'Insumo eliminado correctamente.');
            } else {
                $this->session->setFlashdata('error', 'Error al eliminar el insumo.');
            }
        }

        // Redirigir al índice de insumos
        return redirect()->to('insumos');
    }

    /**
     * Método alternativo para eliminar (si usas la ruta 'eliminar')
     */
    public function eliminar($id)
    {
        return $this->delete($id);
    }

    public function view($id)
    {
        $insumo = $this->insumosModel->getInsumo($id);

        if (empty($insumo)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data = [
            'insumo' => $insumo,
            'title' => $insumo['nombre']
        ];

        return view('insumos/view', $data);
    }

    public function search()
    {
        $term = $this->request->getGet('term');
        
        $data = [
            'insumos' => $this->insumosModel->searchInsumos($term),
            'title' => 'Resultado de búsqueda: ' . $term
        ];

        return view('insumos/index', $data);
    }
}