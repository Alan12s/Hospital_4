<?php

namespace App\Controllers;

use App\Models\EnfermerosModel;
use CodeIgniter\Controller;

class Enfermeros extends Controller
{
    protected $enfermerosModel;
    protected $session;

    public function __construct()
    {
        $this->enfermerosModel = new EnfermerosModel();
        $this->session = \Config\Services::session();
        
        helper(['form', 'url']);
        
        if (!$this->session->get('logged_in')) {
            return redirect()->to('login');
        }
    }

    public function index()
    {
        $data = [
            'enfermeros' => $this->enfermerosModel->getAllEnfermeros(),
            'title' => 'Gestión de Enfermeros'
        ];

        return view('enfermeros/index', $data);
    }

    public function disponibles()
{
    $data = [
        'enfermeros' => $this->enfermerosModel->getEnfermerosDisponibles(),
        'title' => 'Enfermeros Disponibles',
        'titulo' => 'Enfermeros Disponibles' // Añadido para compatibilidad con la vista
    ];

    return view('enfermeros/disponibles', $data);
}

    public function crear()
    {
        $data = [
            'title' => 'Agregar Enfermero',
            'especialidades' => $this->getEspecialidades()
        ];

        return view('enfermeros/crear', $data);
    }

    // Método separado para procesar el formulario de crear
    public function add()
    {
        $validation = \Config\Services::validation();
        
        $validation->setRules([
            'nombre' => 'required|max_length[100]',
            'dni' => 'required|max_length[20]|is_unique[enfermeros.dni]',
            'especialidad' => 'required|max_length[100]',
            'telefono' => 'required|max_length[20]',
            'email' => 'required|valid_email|max_length[100]|is_unique[enfermeros.email]',
            'fecha_ingreso' => 'permit_empty|valid_date',
            'disponibilidad' => 'required|in_list[disponible,no_disponible,en_cirugia]'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            $data = [
                'title' => 'Agregar Enfermero',
                'especialidades' => $this->getEspecialidades(),
                'validation' => $validation
            ];
            return view('enfermeros/crear', $data);
        }

        $postData = $this->request->getPost();
        $postData['fecha_ingreso'] = !empty($postData['fecha_ingreso']) ? $postData['fecha_ingreso'] : null;

        if ($this->enfermerosModel->addEnfermero($postData)) {
            $this->session->setFlashdata('success', 'Enfermero agregado correctamente');
            return redirect()->to('enfermeros');
        } else {
            $this->session->setFlashdata('error', 'Error al agregar el enfermero');
            return redirect()->to('enfermeros/crear');
        }
    }

    public function editar($id)
    {
        $enfermero = $this->enfermerosModel->getEnfermero($id);

        if (empty($enfermero)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data = [
            'enfermero' => $enfermero,
            'title' => 'Editar Enfermero',
            'especialidades' => $this->getEspecialidades()
        ];

        return view('enfermeros/editar', $data);
    }

    // Método separado para procesar el formulario de editar
    public function update($id)
    {
        $enfermero = $this->enfermerosModel->getEnfermero($id);

        if (empty($enfermero)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $validation = \Config\Services::validation();
        
        $validation->setRules([
            'nombre' => 'required|max_length[100]',
            'dni' => "required|max_length[20]|is_unique[enfermeros.dni,id,{$id}]",
            'especialidad' => 'required|max_length[100]',
            'telefono' => 'required|max_length[20]',
            'email' => "required|valid_email|max_length[100]|is_unique[enfermeros.email,id,{$id}]",
            'fecha_ingreso' => 'permit_empty|valid_date',
            'disponibilidad' => 'required|in_list[disponible,no_disponible,en_cirugia]'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            $data = [
                'enfermero' => $enfermero,
                'title' => 'Editar Enfermero',
                'especialidades' => $this->getEspecialidades(),
                'validation' => $validation
            ];
            return view('enfermeros/editar', $data);
        }

        $postData = $this->request->getPost();
        $postData['fecha_ingreso'] = !empty($postData['fecha_ingreso']) ? $postData['fecha_ingreso'] : null;

        if ($this->enfermerosModel->updateEnfermero($id, $postData)) {
            $this->session->setFlashdata('success', 'Enfermero actualizado correctamente');
            return redirect()->to('enfermeros');
        } else {
            $this->session->setFlashdata('error', 'Error al actualizar el enfermero');
            return redirect()->to('enfermeros/editar/' . $id);
        }
    }

    public function ver($id)
    {
        $enfermero = $this->enfermerosModel->getEnfermero($id);

        if (empty($enfermero)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data = [
            'enfermero' => $enfermero,
            'title' => 'Detalles del Enfermero: ' . $enfermero['nombre']
        ];

        return view('enfermeros/ver', $data);
    }

    public function eliminar($id)
    {
        $enfermero = $this->enfermerosModel->getEnfermero($id);

        if (!$enfermero) {
            $this->session->setFlashdata('error', 'Enfermero no encontrado');
            return redirect()->to('enfermeros');
        }

        if ($this->enfermerosModel->estaEnUso($id)) {
            $this->session->setFlashdata('error', 'No se puede eliminar el enfermero porque está asignado a turnos quirúrgicos');
        } else {
            if ($this->enfermerosModel->deleteEnfermero($id)) {
                $this->session->setFlashdata('success', 'Enfermero eliminado correctamente.');
            } else {
                $this->session->setFlashdata('error', 'Error al eliminar el enfermero.');
            }
        }

        return redirect()->to('enfermeros');
    }

    public function search()
    {
        $term = $this->request->getGet('term');
        
        $data = [
            'enfermeros' => $this->enfermerosModel->searchEnfermeros($term),
            'title' => 'Resultado de búsqueda: ' . $term
        ];

        return view('enfermeros/index', $data);
    }

    public function getDisponibles()
    {
        $enfermeros = $this->enfermerosModel->getEnfermerosDisponibles();
        return $this->response->setJSON($enfermeros);
    }

    /**
     * Obtener array de especialidades disponibles
     */
    private function getEspecialidades()
    {
        return [
            'Enfermería Pediátrica' => 'Enfermería Pediátrica',
            'Enfermería de Urgencias' => 'Enfermería de Urgencias',
            'Enfermería Quirúrgica' => 'Enfermería Quirúrgica'
        ];
    }
}