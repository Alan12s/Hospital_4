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
            'cirujanos' => $this->cirujanoModel->listarMedicos()
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
            'nombre' => [
                'label' => 'Nombre',
                'rules' => 'required|min_length[2]|max_length[100]|alpha_space',
                'errors' => [
                    'required' => 'El nombre es obligatorio',
                    'min_length' => 'El nombre debe tener al menos 2 caracteres',
                    'max_length' => 'El nombre no puede exceder 100 caracteres',
                    'alpha_space' => 'El nombre solo puede contener letras, espacios y tildes'
                ]
            ],
            'dni' => [
                'label' => 'DNI',
                'rules' => 'required|numeric|min_length[7]|max_length[8]|is_unique[cirujanos.dni]',
                'errors' => [
                    'required' => 'El DNI es obligatorio',
                    'numeric' => 'El DNI solo puede contener números',
                    'min_length' => 'El DNI debe tener al menos 7 dígitos',
                    'max_length' => 'El DNI no puede exceder 8 dígitos',
                    'is_unique' => 'Este DNI ya está registrado en el sistema'
                ]
            ],
            'id_especialidad' => [
                'label' => 'Especialidad',
                'rules' => 'required|integer|greater_than[0]',
                'errors' => [
                    'required' => 'Debe seleccionar una especialidad',
                    'integer' => 'Especialidad inválida',
                    'greater_than' => 'Debe seleccionar una especialidad válida'
                ]
            ],
            'telefono' => [
                'label' => 'Teléfono',
                'rules' => 'required|min_length[8]|max_length[15]|regex_match[/^[0-9\s\-\(\)]+$/]',
                'errors' => [
                    'required' => 'El teléfono es obligatorio',
                    'min_length' => 'El teléfono debe tener al menos 8 caracteres',
                    'max_length' => 'El teléfono no puede exceder 15 caracteres',
                    'regex_match' => 'El teléfono solo puede contener números, espacios, guiones y paréntesis'
                ]
            ],
            'email' => [
                'label' => 'Email',
                'rules' => 'required|valid_email|max_length[100]|is_unique[cirujanos.email]',
                'errors' => [
                    'required' => 'El email es obligatorio',
                    'valid_email' => 'Debe ingresar un email válido',
                    'max_length' => 'El email no puede exceder 100 caracteres',
                    'is_unique' => 'Este email ya está registrado en el sistema'
                ]
            ],
            'disponibilidad' => [
                'label' => 'Disponibilidad',
                'rules' => 'required|in_list[disponible,no_disponible,vacaciones]',
                'errors' => [
                    'required' => 'Debe seleccionar la disponibilidad',
                    'in_list' => 'Debe seleccionar una opción de disponibilidad válida'
                ]
            ]
        ])) {
            try {
                // Limpiar y formatear datos antes de guardar
                $datosLimpios = $this->limpiarDatosCirujano($this->request->getPost());
                
                $this->cirujanoModel->saveMedico($datosLimpios);
                return redirect()->to('/cirujanos')->with('success', 'Cirujano creado exitosamente');
            } catch (\Exception $e) {
                log_message('error', 'Error al crear cirujano: ' . $e->getMessage());
                return redirect()->back()->withInput()->with('error', 'Error al crear el cirujano. Inténtelo de nuevo.');
            }
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
        // Validar ID
        if (!$id || !is_numeric($id)) {
            return redirect()->to('/cirujanos')->with('error', 'ID de cirujano inválido');
        }

        $cirujano = $this->cirujanoModel->buscarPorID($id);
        if (!$cirujano) {
            return redirect()->to('/cirujanos')->with('error', 'Cirujano no encontrado');
        }

        helper(['form']);

        if ($this->request->getMethod() === 'post' && $this->validate([
            'nombre' => [
                'label' => 'Nombre',
                'rules' => 'required|min_length[2]|max_length[100]|alpha_space',
                'errors' => [
                    'required' => 'El nombre es obligatorio',
                    'min_length' => 'El nombre debe tener al menos 2 caracteres',
                    'max_length' => 'El nombre no puede exceder 100 caracteres',
                    'alpha_space' => 'El nombre solo puede contener letras, espacios y tildes'
                ]
            ],
            'dni' => [
                'label' => 'DNI',
                'rules' => "required|numeric|min_length[7]|max_length[8]|is_unique[cirujanos.dni,id,{$id}]",
                'errors' => [
                    'required' => 'El DNI es obligatorio',
                    'numeric' => 'El DNI solo puede contener números',
                    'min_length' => 'El DNI debe tener al menos 7 dígitos',
                    'max_length' => 'El DNI no puede exceder 8 dígitos',
                    'is_unique' => 'Este DNI ya está registrado en el sistema'
                ]
            ],
            'id_especialidad' => [
                'label' => 'Especialidad',
                'rules' => 'required|integer|greater_than[0]',
                'errors' => [
                    'required' => 'Debe seleccionar una especialidad',
                    'integer' => 'Especialidad inválida',
                    'greater_than' => 'Debe seleccionar una especialidad válida'
                ]
            ],
            'telefono' => [
                'label' => 'Teléfono',
                'rules' => 'required|min_length[8]|max_length[15]|regex_match[/^[0-9\s\-\(\)]+$/]',
                'errors' => [
                    'required' => 'El teléfono es obligatorio',
                    'min_length' => 'El teléfono debe tener al menos 8 caracteres',
                    'max_length' => 'El teléfono no puede exceder 15 caracteres',
                    'regex_match' => 'El teléfono solo puede contener números, espacios, guiones y paréntesis'
                ]
            ],
            'email' => [
                'label' => 'Email',
                'rules' => "required|valid_email|max_length[100]|is_unique[cirujanos.email,id,{$id}]",
                'errors' => [
                    'required' => 'El email es obligatorio',
                    'valid_email' => 'Debe ingresar un email válido',
                    'max_length' => 'El email no puede exceder 100 caracteres',
                    'is_unique' => 'Este email ya está registrado en el sistema'
                ]
            ],
            'disponibilidad' => [
                'label' => 'Disponibilidad',
                'rules' => 'required|in_list[disponible,no_disponible,vacaciones]',
                'errors' => [
                    'required' => 'Debe seleccionar la disponibilidad',
                    'in_list' => 'Debe seleccionar una opción de disponibilidad válida'
                ]
            ]
        ])) {
            try {
                // Limpiar y formatear datos antes de guardar
                $datosLimpios = $this->limpiarDatosCirujano($this->request->getPost());
                
                $this->cirujanoModel->editar($datosLimpios, $id);
                return redirect()->to('/cirujanos')->with('success', 'Cirujano actualizado exitosamente');
            } catch (\Exception $e) {
                log_message('error', 'Error al editar cirujano: ' . $e->getMessage());
                return redirect()->back()->withInput()->with('error', 'Error al actualizar el cirujano. Inténtelo de nuevo.');
            }
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
        // Validar ID
        if (!$id || !is_numeric($id)) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('ID de cirujano inválido');
        }

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

    /**
     * Eliminar cirujano - Método principal
     * Acepta tanto GET como POST para compatibilidad
     */
    public function eliminar($id = null): RedirectResponse
    {
        // Validar ID
        if (!$id || !is_numeric($id)) {
            return redirect()->to('/cirujanos')->with('error', 'ID de cirujano inválido');
        }

        // Buscar el cirujano
        $cirujano = $this->cirujanoModel->buscarPorID($id);
        if (!$cirujano) {
            return redirect()->to('/cirujanos')->with('error', 'Cirujano no encontrado');
        }

        // Verificar método de petición - Preferir POST para seguridad
        $method = $this->request->getMethod();
        if ($method !== 'post' && $method !== 'get') {
            return redirect()->to('/cirujanos')->with('error', 'Método no permitido');
        }

        // Verificar si tiene turnos programados o dependencias
        try {
            if (method_exists($this->cirujanoModel, 'tieneTurnosProgramados') && 
                $this->cirujanoModel->tieneTurnosProgramados($id)) {
                return redirect()->to('/cirujanos')->with('error', 
                    'No se puede eliminar el cirujano porque tiene turnos quirúrgicos programados');
            }

            // Verificar otras dependencias si existen
            if (method_exists($this->cirujanoModel, 'tieneDependencias') && 
                $this->cirujanoModel->tieneDependencias($id)) {
                return redirect()->to('/cirujanos')->with('error', 
                    'No se puede eliminar el cirujano porque tiene registros asociados');
            }

        } catch (\Exception $e) {
            log_message('error', 'Error al verificar dependencias del cirujano: ' . $e->getMessage());
            return redirect()->to('/cirujanos')->with('error', 
                'Error al verificar dependencias. Inténtelo de nuevo.');
        }

        // Intentar eliminar
        try {
            if (method_exists($this->cirujanoModel, 'eliminar')) {
                $resultado = $this->cirujanoModel->eliminar($id);
            } else {
                $resultado = $this->cirujanoModel->delete($id);
            }

            if ($resultado) {
                return redirect()->to('/cirujanos')->with('success', 
                    "Cirujano '{$cirujano->nombre}' eliminado exitosamente");
            } else {
                return redirect()->to('/cirujanos')->with('error', 
                    'No se pudo eliminar el cirujano. Inténtelo de nuevo.');
            }

        } catch (\Exception $e) {
            log_message('error', "Error al eliminar cirujano ID {$id}: " . $e->getMessage());
            return redirect()->to('/cirujanos')->with('error', 
                'Error al eliminar el cirujano. Inténtelo de nuevo.');
        }
    }

    /**
     * Alias para eliminar - Mantener compatibilidad
     */
    public function delete($id = null): RedirectResponse
    {
        return $this->eliminar($id);
    }

    /**
     * Método para obtener datos JSON (opcional para AJAX)
     */
    public function obtenerCirujano($id = null)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON(['error' => 'Solo peticiones AJAX']);
        }

        if (!$id || !is_numeric($id)) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'ID inválido']);
        }

        $cirujano = $this->cirujanoModel->buscarPorID($id);
        if (!$cirujano) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Cirujano no encontrado']);
        }

        return $this->response->setJSON($cirujano);
    }

    /**
     * Método para cambiar estado de disponibilidad (opcional)
     */
    public function cambiarDisponibilidad($id = null)
    {
        if (!$this->request->isAJAX() || $this->request->getMethod() !== 'post') {
            return $this->response->setStatusCode(403)->setJSON(['error' => 'Método no permitido']);
        }

        if (!$id || !is_numeric($id)) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'ID inválido']);
        }

        $cirujano = $this->cirujanoModel->buscarPorID($id);
        if (!$cirujano) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Cirujano no encontrado']);
        }

        try {
            $nuevaDisponibilidad = $cirujano->disponibilidad === 'disponible' ? 'no_disponible' : 'disponible';
            
            if (method_exists($this->cirujanoModel, 'cambiarDisponibilidad')) {
                $resultado = $this->cirujanoModel->cambiarDisponibilidad($id, $nuevaDisponibilidad);
            } else {
                $resultado = $this->cirujanoModel->update($id, ['disponibilidad' => $nuevaDisponibilidad]);
            }

            if ($resultado) {
                return $this->response->setJSON([
                    'success' => true,
                    'mensaje' => 'Disponibilidad actualizada',
                    'nueva_disponibilidad' => $nuevaDisponibilidad
                ]);
            } else {
                return $this->response->setStatusCode(500)->setJSON(['error' => 'Error al actualizar']);
            }

        } catch (\Exception $e) {
            log_message('error', 'Error al cambiar disponibilidad: ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON(['error' => 'Error interno del servidor']);
        }
    }

    /**
     * Método privado para limpiar y formatear datos del cirujano
     */
    private function limpiarDatosCirujano(array $datos): array
    {
        return [
            'nombre' => trim(ucwords(strtolower($datos['nombre']))),
            'dni' => preg_replace('/[^0-9]/', '', $datos['dni']),
            'id_especialidad' => (int)$datos['id_especialidad'],
            'telefono' => preg_replace('/[^0-9\s\-\(\)]/', '', $datos['telefono']),
            'email' => trim(strtolower($datos['email'])),
            'disponibilidad' => $datos['disponibilidad']
        ];
    }

    /**
     * Método para validar datos por AJAX
     */
    public function validarCampo()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON(['error' => 'Solo peticiones AJAX']);
        }

        $campo = $this->request->getPost('campo');
        $valor = $this->request->getPost('valor');
        $id = $this->request->getPost('id'); // Para edición

        $reglas = [];
        
        switch ($campo) {
            case 'nombre':
                $reglas = ['nombre' => 'required|min_length[2]|max_length[100]|alpha_space'];
                break;
            case 'dni':
                $regla = 'required|numeric|min_length[7]|max_length[8]|is_unique[cirujanos.dni';
                if ($id) $regla .= ",id,{$id}";
                $regla .= ']';
                $reglas = ['dni' => $regla];
                break;
            case 'telefono':
                $reglas = ['telefono' => 'required|min_length[8]|max_length[15]|regex_match[/^[0-9\s\-\(\)]+$/]'];
                break;
            case 'email':
                $regla = 'required|valid_email|max_length[100]|is_unique[cirujanos.email';
                if ($id) $regla .= ",id,{$id}";
                $regla .= ']';
                $reglas = ['email' => $regla];
                break;
            default:
                return $this->response->setStatusCode(400)->setJSON(['error' => 'Campo no válido']);
        }

        $datos = [$campo => $valor];
        
        if ($this->validate($reglas, $datos)) {
            return $this->response->setJSON(['valido' => true]);
        } else {
            return $this->response->setJSON([
                'valido' => false,
                'errores' => $this->validator->getErrors()
            ]);
        }
    }
}