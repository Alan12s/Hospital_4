<?php

namespace App\Controllers;

use App\Models\AnestesistasModel;
use CodeIgniter\Controller;

class Anestesistas extends Controller
{
    protected $anestesistasModel;
    protected $session;

    public function __construct()
    {
        $this->anestesistasModel = new AnestesistasModel();
        $this->session = \Config\Services::session();
        
        helper(['form', 'url']);
        
        if (!$this->session->get('logged_in')) {
            return redirect()->to('login');
        }
    }

    public function index()
    {
        $data = [
            'anestesistas' => $this->anestesistasModel->getAllAnestesistas(),
            'title' => 'Gestión de Anestesistas'
        ];

        return view('anestesistas/index', $data);
    }

    public function disponibles()
    {
        $data = [
            'anestesistas' => $this->anestesistasModel->getAnestesistasDisponibles(),
            'title' => 'Anestesistas Disponibles',
            'titulo' => 'Anestesistas Disponibles'
        ];

        return view('anestesistas/disponibles', $data);
    }

    public function crear()
    {
        $data = [
            'title' => 'Agregar Anestesista',
            'especialidades' => $this->getEspecialidades()
        ];

        return view('anestesistas/crear', $data);
    }

    public function add()
    {
        $validation = \Config\Services::validation();
        
        $validation->setRules([
            'nombre' => 'required|max_length[100]',
            'especialidad' => 'required|max_length[100]',
            'telefono' => 'required|max_length[20]',
            'email' => 'required|valid_email|max_length[100]|is_unique[anestesistas.email]',
            'disponibilidad' => 'required|in_list[disponible,no_disponible,en_cirugia]'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            $data = [
                'title' => 'Agregar Anestesista',
                'especialidades' => $this->getEspecialidades(),
                'validation' => $validation
            ];
            return view('anestesistas/crear', $data);
        }

        $postData = $this->request->getPost();

        if ($this->anestesistasModel->addAnestesista($postData)) {
            $this->session->setFlashdata('success', 'Anestesista agregado correctamente');
            return redirect()->to('anestesistas');
        } else {
            $this->session->setFlashdata('error', 'Error al agregar el anestesista');
            return redirect()->to('anestesistas/crear');
        }
    }

    public function editar($id)
    {
        $anestesista = $this->anestesistasModel->getAnestesista($id);

        if (empty($anestesista)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data = [
            'anestesista' => $anestesista,
            'title' => 'Editar Anestesista',
            'especialidades' => $this->getEspecialidades()
        ];

        return view('anestesistas/editar', $data);
    }

    public function update($id)
    {
        $anestesista = $this->anestesistasModel->getAnestesista($id);

        if (empty($anestesista)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $validation = \Config\Services::validation();
        
        $validation->setRules([
            'nombre' => 'required|max_length[100]',
            'especialidad' => 'required|max_length[100]',
            'telefono' => 'required|max_length[20]',
            'email' => "required|valid_email|max_length[100]|is_unique[anestesistas.email,id,{$id}]",
            'disponibilidad' => 'required|in_list[disponible,no_disponible,en_cirugia]'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            $data = [
                'anestesista' => $anestesista,
                'title' => 'Editar Anestesista',
                'especialidades' => $this->getEspecialidades(),
                'validation' => $validation
            ];
            return view('anestesistas/editar', $data);
        }

        $postData = $this->request->getPost();

        if ($this->anestesistasModel->updateAnestesista($id, $postData)) {
            $this->session->setFlashdata('success', 'Anestesista actualizado correctamente');
            return redirect()->to('anestesistas');
        } else {
            $this->session->setFlashdata('error', 'Error al actualizar el anestesista');
            return redirect()->to('anestesistas/editar/' . $id);
        }
    }

  public function ver($id) {
    $anestesistaModel = new \App\Models\AnestesistasModel(); // Asegúrate de usar el namespace correcto
    
    $anestesista = $anestesistaModel->find($id);

    if (empty($anestesista)) {
        throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
    }

    $data = [
        'titulo' => 'Detalles del Anestesista',
        'anestesista' => $anestesista // Usamos la variable ya obtenida
    ];

    return view('anestesistas/ver', $data);
}

    public function eliminar($id)
    {
        $anestesista = $this->anestesistasModel->getAnestesista($id);

        if (!$anestesista) {
            $this->session->setFlashdata('error', 'Anestesista no encontrado');
            return redirect()->to('anestesistas');
        }

        if ($this->anestesistasModel->estaEnUso($id)) {
            $this->session->setFlashdata('error', 'No se puede eliminar el anestesista porque está asignado a turnos quirúrgicos');
        } else {
            if ($this->anestesistasModel->deleteAnestesista($id)) {
                $this->session->setFlashdata('success', 'Anestesista eliminado correctamente.');
            } else {
                $this->session->setFlashdata('error', 'Error al eliminar el anestesista.');
            }
        }

        return redirect()->to('anestesistas');
    }

    public function search()
    {
        $term = $this->request->getGet('term');
        
        $data = [
            'anestesistas' => $this->anestesistasModel->searchAnestesistas($term),
            'title' => 'Resultado de búsqueda: ' . $term
        ];

        return view('anestesistas/index', $data);
    }

    public function getDisponibles()
    {
        $anestesistas = $this->anestesistasModel->getAnestesistasDisponibles();
        return $this->response->setJSON($anestesistas);
    }

    /**
     * Obtener array de especialidades disponibles para anestesistas
     */
    private function getEspecialidades()
    {
        return [
            'Anestesiología General' => 'Anestesiología General',
            'Anestesiología Pediátrica' => 'Anestesiología Pediátrica',
            'Anestesiología en Dolor Crónico' => 'Anestesiología en Dolor Crónico',
            'Anestesiología Cardiovascular' => 'Anestesiología Cardiovascular',
            'Anestesiología Obstétrica' => 'Anestesiología Obstétrica'
        ];
    }
}
