<?php

namespace App\Controllers;

use App\Models\InstrumentistasModel;
use CodeIgniter\Controller;

class Instrumentistas extends Controller
{
    protected $instrumentistasModel;
    protected $session;

    public function __construct()
    {
        $this->instrumentistasModel = new InstrumentistasModel();
        $this->session = \Config\Services::session();
        
        helper(['form', 'url']);
        
        if (!$this->session->get('logged_in')) {
            return redirect()->to('login');
        }
    }

    public function index()
    {
        $data = [
            'instrumentistas' => $this->instrumentistasModel->getAllInstrumentistas(),
            'title' => 'Gestión de Instrumentistas'
        ];

        return view('instrumentistas/index', $data);
    }

    public function disponibles()
    {
        $data = [
            'instrumentistas' => $this->instrumentistasModel->getInstrumentistasDisponibles(),
            'title' => 'Instrumentistas Disponibles',
            'titulo' => 'Instrumentistas Disponibles'
        ];

        return view('instrumentistas/disponibles', $data);
    }

    public function crear()
    {
        $data = [
            'title' => 'Agregar Instrumentista',
            'especialidades' => $this->getEspecialidades()
        ];

        return view('instrumentistas/crear', $data);
    }

    public function add()
    {
        $validation = \Config\Services::validation();
        
        $validation->setRules([
            'nombre' => 'required|max_length[100]',
            'dni' => 'required|max_length[20]|is_unique[instrumentistas.dni]',
            'especialidad' => 'required|max_length[100]',
            'telefono' => 'required|max_length[20]',
            'email' => 'required|valid_email|max_length[100]|is_unique[instrumentistas.email]',
            'fecha_ingreso' => 'permit_empty|valid_date',
            'disponibilidad' => 'required|in_list[disponible,no_disponible,en_cirugia]'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            $data = [
                'title' => 'Agregar Instrumentista',
                'especialidades' => $this->getEspecialidades(),
                'validation' => $validation
            ];
            return view('instrumentistas/crear', $data);
        }

        $postData = $this->request->getPost();
        $postData['fecha_ingreso'] = !empty($postData['fecha_ingreso']) ? $postData['fecha_ingreso'] : null;

        if ($this->instrumentistasModel->addInstrumentista($postData)) {
            $this->session->setFlashdata('success', 'Instrumentista agregado correctamente');
            return redirect()->to('instrumentistas');
        } else {
            $this->session->setFlashdata('error', 'Error al agregar el instrumentista');
            return redirect()->to('instrumentistas/crear');
        }
    }

    public function editar($id)
    {
        $instrumentista = $this->instrumentistasModel->getInstrumentista($id);

        if (empty($instrumentista)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data = [
            'instrumentista' => $instrumentista,
            'title' => 'Editar Instrumentista',
            'especialidades' => $this->getEspecialidades()
        ];

        return view('instrumentistas/editar', $data);
    }

    public function update($id)
    {
        $instrumentista = $this->instrumentistasModel->getInstrumentista($id);

        if (empty($instrumentista)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $validation = \Config\Services::validation();
        
        $validation->setRules([
            'nombre' => 'required|max_length[100]',
            'dni' => "required|max_length[20]|is_unique[instrumentistas.dni,id,{$id}]",
            'especialidad' => 'required|max_length[100]',
            'telefono' => 'required|max_length[20]',
            'email' => "required|valid_email|max_length[100]|is_unique[instrumentistas.email,id,{$id}]",
            'fecha_ingreso' => 'permit_empty|valid_date',
            'disponibilidad' => 'required|in_list[disponible,no_disponible,en_cirugia]'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            $data = [
                'instrumentista' => $instrumentista,
                'title' => 'Editar Instrumentista',
                'especialidades' => $this->getEspecialidades(),
                'validation' => $validation
            ];
            return view('instrumentistas/editar', $data);
        }

        $postData = $this->request->getPost();
        $postData['fecha_ingreso'] = !empty($postData['fecha_ingreso']) ? $postData['fecha_ingreso'] : null;

        if ($this->instrumentistasModel->updateInstrumentista($id, $postData)) {
            $this->session->setFlashdata('success', 'Instrumentista actualizado correctamente');
            return redirect()->to('instrumentistas');
        } else {
            $this->session->setFlashdata('error', 'Error al actualizar el instrumentista');
            return redirect()->to('instrumentistas/editar/' . $id);
        }
    }

    public function ver($id)
    {
        $instrumentista = $this->instrumentistasModel->getInstrumentista($id);

        if (empty($instrumentista)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data = [
            'instrumentista' => $instrumentista,
            'title' => 'Detalles del Instrumentista: ' . $instrumentista['nombre']
        ];

        return view('instrumentistas/ver', $data);
    }

    public function eliminar($id)
    {
        $instrumentista = $this->instrumentistasModel->getInstrumentista($id);

        if (!$instrumentista) {
            $this->session->setFlashdata('error', 'Instrumentista no encontrado');
            return redirect()->to('instrumentistas');
        }

        if ($this->instrumentistasModel->estaEnUso($id)) {
            $this->session->setFlashdata('error', 'No se puede eliminar el instrumentista porque está asignado a turnos quirúrgicos');
        } else {
            if ($this->instrumentistasModel->deleteInstrumentista($id)) {
                $this->session->setFlashdata('success', 'Instrumentista eliminado correctamente.');
            } else {
                $this->session->setFlashdata('error', 'Error al eliminar el instrumentista.');
            }
        }

        return redirect()->to('instrumentistas');
    }

    public function search()
    {
        $term = $this->request->getGet('term');
        
        $data = [
            'instrumentistas' => $this->instrumentistasModel->searchInstrumentistas($term),
            'title' => 'Resultado de búsqueda: ' . $term
        ];

        return view('instrumentistas/index', $data);
    }

    public function getDisponibles()
    {
        $instrumentistas = $this->instrumentistasModel->getInstrumentistasDisponibles();
        return $this->response->setJSON($instrumentistas);
    }

    /**
     * Obtener array de especialidades disponibles
     */
  private function getEspecialidades()
{
    return [
        'Instrumentista Quirúrgico General' => 'Instrumentista Quirúrgico General',
        'Instrumentista en Cirugía General' => 'Instrumentista en Cirugía General',
        'Instrumentista en Cirugía Traumatológica y Ortopédica' => 'Instrumentista en Cirugía Traumatológica y Ortopédica',
        'Instrumentista en Cirugía Ginecológica y Obstétrica' => 'Instrumentista en Cirugía Ginecológica y Obstétrica',
        'Instrumentista en Cirugía Urológica' => 'Instrumentista en Cirugía Urológica',
        'Instrumentista en Cirugía Odontológica' => 'Instrumentista en Cirugía Odontológica',
        'Instrumentista en Cirugía Cardiovascular' => 'Instrumentista en Cirugía Cardiovascular',
        'Instrumentista en Neurocirugía' => 'Instrumentista en Neurocirugía',
        'Instrumentista en Cirugía Plástica' => 'Instrumentista en Cirugía Plástica',
        'Instrumentista en Cirugía Pediátrica' => 'Instrumentista en Cirugía Pediátrica',
        'Instrumentista en Cirugía Laparoscópica' => 'Instrumentista en Cirugía Laparoscópica',
        'Instrumentista en Cirugía Oncológica' => 'Instrumentista en Cirugía Oncológica'
    ];
}
}