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
            'titulo' => 'Enfermeros Disponibles'
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

    public function add()
    {
        $validation = \Config\Services::validation();
        
        $validation->setRules([
            'nombre' => 'required|max_length[100]|regex_match[/^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/]',
            'dni' => 'required|max_length[20]|regex_match[/^[0-9]+$/]|is_unique[enfermeros.dni]',
            'especialidad' => 'required|max_length[100]',
            'telefono' => 'required|max_length[20]|regex_match[/^[0-9+\s()-]+$/]',
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

    public function update($id)
    {
        $enfermero = $this->enfermerosModel->getEnfermero($id);

        if (empty($enfermero)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // Obtener los datos del POST
        $postData = $this->request->getPost();
        
        // Debug: Agregar logs para ver qué datos llegan
        log_message('debug', 'Datos POST recibidos: ' . json_encode($postData));
        log_message('debug', 'ID a actualizar: ' . $id);
        
        // Preparar datos para actualizar (mantener valores existentes si no se envían nuevos)
        $updateData = [];
        
        // Solo actualizar campos que se envían y no están vacíos
        if (!empty($postData['nombre'])) {
            $updateData['nombre'] = $postData['nombre'];
        }
        
        if (!empty($postData['dni'])) {
            $updateData['dni'] = $postData['dni'];
        }
        
        if (!empty($postData['especialidad'])) {
            $updateData['especialidad'] = $postData['especialidad'];
        }
        
        if (!empty($postData['telefono'])) {
            $updateData['telefono'] = $postData['telefono'];
        }
        
        if (!empty($postData['email'])) {
            $updateData['email'] = $postData['email'];
        }
        
        if (isset($postData['fecha_ingreso'])) {
            $updateData['fecha_ingreso'] = !empty($postData['fecha_ingreso']) ? $postData['fecha_ingreso'] : null;
        }
        
        if (!empty($postData['disponibilidad'])) {
            $updateData['disponibilidad'] = $postData['disponibilidad'];
        }

        // Debug: Ver qué datos se van a actualizar
        log_message('debug', 'Datos para actualizar: ' . json_encode($updateData));

        // Si no hay datos para actualizar
        if (empty($updateData)) {
            $this->session->setFlashdata('error', 'No se detectaron cambios para actualizar');
            return redirect()->to('enfermeros/editar/' . $id);
        }

        // Validar solo los campos que se van a actualizar
        $validation = \Config\Services::validation();
        $rules = [];
        
        if (isset($updateData['nombre'])) {
            $rules['nombre'] = 'required|max_length[100]|regex_match[/^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/]';
        }
        
        if (isset($updateData['dni'])) {
            $rules['dni'] = "required|max_length[20]|regex_match[/^[0-9]+$/]|is_unique[enfermeros.dni,id,{$id}]";
        }
        
        if (isset($updateData['especialidad'])) {
            $rules['especialidad'] = 'required|max_length[100]';
        }
        
        if (isset($updateData['telefono'])) {
            $rules['telefono'] = 'required|max_length[20]|regex_match[/^[0-9+\s()-]+$/]';
        }
        
        if (isset($updateData['email'])) {
            $rules['email'] = "required|valid_email|max_length[100]|is_unique[enfermeros.email,id,{$id}]";
        }
        
        if (isset($updateData['fecha_ingreso'])) {
            $rules['fecha_ingreso'] = 'permit_empty|valid_date';
        }
        
        if (isset($updateData['disponibilidad'])) {
            $rules['disponibilidad'] = 'required|in_list[disponible,no_disponible,en_cirugia]';
        }

        // Aplicar validación solo si hay reglas
        if (!empty($rules)) {
            $validation->setRules($rules);
            
            if (!$validation->withRequest($this->request)->run()) {
                log_message('debug', 'Errores de validación: ' . json_encode($validation->getErrors()));
                $data = [
                    'enfermero' => $enfermero,
                    'title' => 'Editar Enfermero',
                    'especialidades' => $this->getEspecialidades(),
                    'validation' => $validation
                ];
                return view('enfermeros/editar', $data);
            }
        }

        // Intentar actualizar
        try {
            $result = $this->enfermerosModel->updateEnfermero($id, $updateData);
            log_message('debug', 'Resultado de actualización: ' . ($result ? 'exitoso' : 'fallido'));
            
            if ($result) {
                $this->session->setFlashdata('success', 'Enfermero actualizado correctamente');
                return redirect()->to('enfermeros');
            } else {
                log_message('error', 'Error en updateEnfermero - No se pudo actualizar');
                $this->session->setFlashdata('error', 'Error al actualizar el enfermero - No se realizaron cambios');
                return redirect()->to('enfermeros/editar/' . $id);
            }
        } catch (\Exception $e) {
            log_message('error', 'Excepción en update: ' . $e->getMessage());
            $this->session->setFlashdata('error', 'Error al actualizar: ' . $e->getMessage());
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