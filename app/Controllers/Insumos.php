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
        $insumos = $this->insumosModel->getAllInsumos();
        
        // Verificar uso para cada insumo
        $insumosConUso = array_map(function($insumo) {
            $insumo['en_uso'] = $this->insumosModel->estaEnUso($insumo['id_insumo']);
            // AGREGADO: Verificar si el insumo está vencido
            $insumo['esta_vencido'] = $this->verificarVencimiento($insumo);
            return $insumo;
        }, $insumos);

        // AGREGADO: Verificar si hay insumos vencidos y mostrar mensaje
        $insumosVencidos = array_filter($insumosConUso, function($insumo) {
            return $insumo['esta_vencido'];
        });

        if (!empty($insumosVencidos)) {
            $nombreVencidos = array_column($insumosVencidos, 'nombre');
            $mensaje = 'ATENCIÓN: Los siguientes insumos están vencidos: ' . implode(', ', $nombreVencidos);
            $this->session->setFlashdata('warning', $mensaje);
        }

        $data = [
            'insumos' => $insumosConUso,
            'title' => 'Gestión de Insumos'
        ];

        return view('insumos/index', $data);
    }

    public function crear()
    {
        $data = [
            'title' => 'Agregar Insumo',
            'validation' => \Config\Services::validation()
        ];

        return view('insumos/crear', $data);
    }

    public function add()
    {
        $validation = \Config\Services::validation();
        
        $validation->setRules([
            'codigo' => 'permit_empty|max_length[100]',
            'nombre' => 'required|max_length[70]',
            'categoria' => 'required|in_list[descartable,instrumental]',
            'tipo' => 'required|max_length[50]',
            'cantidad' => 'required|numeric|greater_than_equal_to[0]',
            'ubicacion' => 'required|max_length[100]',
            'lote' => 'permit_empty|max_length[100]',
            'tiene_vencimiento' => 'permit_empty|in_list[0,1]',
            'fecha_vencimiento' => 'permit_empty|valid_date'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('validation', $validation);
        }

        // Obtener datos del formulario
        $data = [
            'codigo' => $this->request->getPost('codigo'),
            'nombre' => $this->request->getPost('nombre'),
            'categoria' => $this->request->getPost('categoria'),
            'tipo' => $this->request->getPost('tipo'),
            'cantidad' => (int)$this->request->getPost('cantidad'),
            'ubicacion' => $this->request->getPost('ubicacion'),
            'lote' => $this->request->getPost('lote'),
            'tiene_vencimiento' => $this->request->getPost('tiene_vencimiento') ? 1 : 0
        ];

        // Solo agregar fecha_vencimiento si tiene_vencimiento está marcado
        if ($data['tiene_vencimiento'] && $this->request->getPost('fecha_vencimiento')) {
            $data['fecha_vencimiento'] = $this->request->getPost('fecha_vencimiento');
        } else {
            $data['fecha_vencimiento'] = null;
        }

        // Validación adicional: si tiene vencimiento, debe tener fecha
        if ($data['tiene_vencimiento'] && empty($data['fecha_vencimiento'])) {
            $this->session->setFlashdata('error', 'Si el insumo tiene vencimiento, debe especificar la fecha de vencimiento');
            return redirect()->back()->withInput();
        }

        // AGREGADO: Validación para no permitir crear insumos con fecha vencida
        if ($data['tiene_vencimiento'] && !empty($data['fecha_vencimiento'])) {
            if ($this->esFechaVencida($data['fecha_vencimiento'])) {
                $this->session->setFlashdata('error', 'No se puede crear un insumo con fecha de vencimiento ya vencida. La fecha debe ser posterior a hoy.');
                return redirect()->back()->withInput();
            }
        }

        try {
            if ($this->insumosModel->addInsumo($data)) {
                $this->session->setFlashdata('success', 'Insumo agregado correctamente');
                return redirect()->to('insumos');
            } else {
                $this->session->setFlashdata('error', 'Error al agregar el insumo');
                return redirect()->back()->withInput();
            }
        } catch (\Exception $e) {
            log_message('error', 'Error al crear insumo: ' . $e->getMessage());
            $this->session->setFlashdata('error', 'Error interno al agregar el insumo: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function edit($id)
    {
        $insumo = $this->insumosModel->getInsumo($id);

        if (empty($insumo)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // Debug: Verificar qué datos estamos recibiendo
        log_message('debug', 'Datos del insumo en edit: ' . json_encode($insumo));

        // Asegurar que todos los campos necesarios existan con valores por defecto
        $insumo = $this->asegurarCamposInsumo($insumo);

        // AGREGADO: Verificar si el insumo está vencido y mostrar advertencia
        if ($this->verificarVencimiento($insumo)) {
            $this->session->setFlashdata('warning', 'ATENCIÓN: Este insumo está vencido (Fecha de vencimiento: ' . $insumo['fecha_vencimiento'] . ')');
        }

        $data = [
            'insumo' => $insumo,
            'title' => 'Editar Insumo',
            'validation' => \Config\Services::validation()
        ];

        return view('insumos/editar', $data);
    }

    public function update($id)
    {
        $validation = \Config\Services::validation();
        
        $validation->setRules([
            'codigo' => 'permit_empty|max_length[100]',
            'nombre' => 'required|max_length[70]',
            'categoria' => 'required|in_list[descartable,instrumental]',
            'tipo' => 'required|max_length[50]',
            'cantidad' => 'required|numeric|greater_than_equal_to[0]',
            'ubicacion' => 'required|max_length[100]',
            'lote' => 'permit_empty|max_length[100]',
            'tiene_vencimiento' => 'permit_empty|in_list[0,1]',
            'fecha_vencimiento' => 'permit_empty|valid_date'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('validation', $validation);
        }

        // Obtener datos del formulario
        $data = [
            'codigo' => $this->request->getPost('codigo'),
            'nombre' => $this->request->getPost('nombre'),
            'categoria' => $this->request->getPost('categoria'),
            'tipo' => $this->request->getPost('tipo'),
            'cantidad' => (int)$this->request->getPost('cantidad'),
            'ubicacion' => $this->request->getPost('ubicacion'),
            'lote' => $this->request->getPost('lote'),
            'tiene_vencimiento' => $this->request->getPost('tiene_vencimiento') ? 1 : 0
        ];

        // Solo agregar fecha_vencimiento si tiene_vencimiento está marcado
        if ($data['tiene_vencimiento'] && $this->request->getPost('fecha_vencimiento')) {
            $data['fecha_vencimiento'] = $this->request->getPost('fecha_vencimiento');
        } else {
            $data['fecha_vencimiento'] = null;
        }

        // Validación adicional: si tiene vencimiento, debe tener fecha
        if ($data['tiene_vencimiento'] && empty($data['fecha_vencimiento'])) {
            $this->session->setFlashdata('error', 'Si el insumo tiene vencimiento, debe especificar la fecha de vencimiento');
            return redirect()->back()->withInput();
        }

        // AGREGADO: Validación para no permitir actualizar con fecha vencida (solo para nuevas fechas)
        if ($data['tiene_vencimiento'] && !empty($data['fecha_vencimiento'])) {
            // Obtener la fecha actual del insumo para comparar
            $insumoActual = $this->insumosModel->getInsumo($id);
            
            // Solo validar si se está cambiando la fecha de vencimiento
            if ($insumoActual['fecha_vencimiento'] !== $data['fecha_vencimiento']) {
                if ($this->esFechaVencida($data['fecha_vencimiento'])) {
                    $this->session->setFlashdata('error', 'No se puede actualizar con una fecha de vencimiento ya vencida. La fecha debe ser posterior a hoy.');
                    return redirect()->back()->withInput();
                }
            }
        }

        try {
            if ($this->insumosModel->updateInsumo($id, $data)) {
                $this->session->setFlashdata('success', 'Insumo actualizado correctamente');
                return redirect()->to('insumos');
            } else {
                $this->session->setFlashdata('error', 'Error al actualizar el insumo');
                return redirect()->back()->withInput();
            }
        } catch (\Exception $e) {
            log_message('error', 'Error al actualizar insumo: ' . $e->getMessage());
            $this->session->setFlashdata('error', 'Error interno al actualizar el insumo: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

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
            try {
                // Intentar eliminar el insumo
                if ($this->insumosModel->deleteInsumo($id)) {
                    $this->session->setFlashdata('success', 'Insumo eliminado correctamente.');
                } else {
                    $this->session->setFlashdata('error', 'Error al eliminar el insumo.');
                }
            } catch (\Exception $e) {
                log_message('error', 'Error al eliminar insumo: ' . $e->getMessage());
                $this->session->setFlashdata('error', 'Error interno al eliminar el insumo.');
            }
        }

        // Redirigir al índice de insumos
        return redirect()->to('insumos');
    }

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

        // Asegurar que todos los campos necesarios existan con valores por defecto
        $insumo = $this->asegurarCamposInsumo($insumo);

        // AGREGADO: Verificar si el insumo está vencido y mostrar advertencia
        if ($this->verificarVencimiento($insumo)) {
            $this->session->setFlashdata('warning', 'ATENCIÓN: Este insumo está vencido (Fecha de vencimiento: ' . $insumo['fecha_vencimiento'] . ')');
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
        
        if (empty($term)) {
            return redirect()->to('insumos');
        }
        
        $insumos = $this->insumosModel->searchInsumos($term);
        
        // Verificar uso para cada insumo
        $insumosConUso = array_map(function($insumo) {
            $insumo['en_uso'] = $this->insumosModel->estaEnUso($insumo['id_insumo']);
            // AGREGADO: Verificar si el insumo está vencido
            $insumo['esta_vencido'] = $this->verificarVencimiento($insumo);
            return $insumo;
        }, $insumos);

        // AGREGADO: Verificar si hay insumos vencidos en la búsqueda y mostrar mensaje
        $insumosVencidos = array_filter($insumosConUso, function($insumo) {
            return $insumo['esta_vencido'];
        });

        if (!empty($insumosVencidos)) {
            $nombreVencidos = array_column($insumosVencidos, 'nombre');
            $mensaje = 'ATENCIÓN: En los resultados de búsqueda, los siguientes insumos están vencidos: ' . implode(', ', $nombreVencidos);
            $this->session->setFlashdata('warning', $mensaje);
        }

        $data = [
            'insumos' => $insumosConUso,
            'title' => 'Resultado de búsqueda: ' . $term,
            'search_term' => $term
        ];

        return view('insumos/index', $data);
    }

    /**
     * Obtener información del insumo vía AJAX
     */
    public function getInsumoInfo($id)
    {
        $insumo = $this->insumosModel->getInsumo($id);
        
        if ($insumo) {
            // AGREGADO: Incluir información de vencimiento en la respuesta AJAX
            $insumo['esta_vencido'] = $this->verificarVencimiento($insumo);
            
            return $this->response->setJSON([
                'success' => true,
                'data' => $insumo
            ]);
        } else {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Insumo no encontrado'
            ]);
        }
    }

    /**
     * Obtener estadísticas de insumos
     */
    public function estadisticas()
    {
        $totalInsumos = $this->insumosModel->countAll();
        $bajoStock = $this->insumosModel->countBajoStock(10);
        $stockCritico = $this->insumosModel->countBajoStockCritico(5);
        $proximosVencer = count($this->insumosModel->getInsumosProximosVencer(30));
        $vencidos = count($this->insumosModel->getInsumosVencidos());

        $data = [
            'total_insumos' => $totalInsumos,
            'bajo_stock' => $bajoStock,
            'stock_critico' => $stockCritico,
            'proximos_vencer' => $proximosVencer,
            'vencidos' => $vencidos,
            'estadisticas_tipo' => $this->insumosModel->getEstadisticasPorTipo(),
            'estadisticas_categoria' => $this->insumosModel->getEstadisticasPorCategoria(), // AGREGADO
            'title' => 'Estadísticas de Insumos'
        ];

        return view('insumos/estadisticas', $data);
    }

    /**
     * AGREGADO: Obtener insumos por categoría
     */
    public function categoria($categoria)
    {
        if (!in_array($categoria, ['descartable', 'instrumental'])) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $insumos = $this->insumosModel->getInsumosPorCategoria($categoria);
        
        // Verificar uso para cada insumo
        $insumosConUso = array_map(function($insumo) {
            $insumo['en_uso'] = $this->insumosModel->estaEnUso($insumo['id_insumo']);
            // AGREGADO: Verificar si el insumo está vencido
            $insumo['esta_vencido'] = $this->verificarVencimiento($insumo);
            return $insumo;
        }, $insumos);

        // AGREGADO: Verificar si hay insumos vencidos en la categoría y mostrar mensaje
        $insumosVencidos = array_filter($insumosConUso, function($insumo) {
            return $insumo['esta_vencido'];
        });

        if (!empty($insumosVencidos)) {
            $nombreVencidos = array_column($insumosVencidos, 'nombre');
            $mensaje = 'ATENCIÓN: En la categoría ' . ucfirst($categoria) . ', los siguientes insumos están vencidos: ' . implode(', ', $nombreVencidos);
            $this->session->setFlashdata('warning', $mensaje);
        }

        $data = [
            'insumos' => $insumosConUso,
            'title' => 'Insumos - Categoría: ' . ucfirst($categoria),
            'categoria_filtro' => $categoria
        ];

        return view('insumos/index', $data);
    }

    /**
     * AGREGADO: Método para verificar si un insumo está vencido
     * @param array $insumo - Array con los datos del insumo
     * @return bool - true si está vencido, false si no
     */
    private function verificarVencimiento($insumo)
    {
        // Si no tiene vencimiento o no tiene fecha de vencimiento, no está vencido
        if (!$insumo['tiene_vencimiento'] || empty($insumo['fecha_vencimiento'])) {
            return false;
        }

        // Comparar la fecha de vencimiento con la fecha actual
        $fechaVencimiento = new \DateTime($insumo['fecha_vencimiento']);
        $fechaActual = new \DateTime();
        
        // Está vencido si la fecha de vencimiento es menor que la fecha actual
        return $fechaVencimiento < $fechaActual;
    }

    /**
     * AGREGADO: Método para verificar si una fecha ya está vencida (para validaciones)
     * @param string $fecha - Fecha en formato Y-m-d
     * @return bool - true si la fecha ya pasó, false si no
     */
    private function esFechaVencida($fecha)
    {
        if (empty($fecha)) {
            return false;
        }

        $fechaVencimiento = new \DateTime($fecha);
        $fechaActual = new \DateTime();
        
        // Resetear las horas para comparar solo fechas
        $fechaVencimiento->setTime(0, 0, 0);
        $fechaActual->setTime(0, 0, 0);
        
        // La fecha está vencida si es menor que la fecha actual
        return $fechaVencimiento < $fechaActual;
    }

    /**
     * Método privado para asegurar que todos los campos necesarios existan
     */
    private function asegurarCamposInsumo($insumo)
    {
        // Campos con valores por defecto basados en la estructura de la BD
        $camposDefecto = [
            'id_insumo' => 0,
            'codigo' => '',
            'nombre' => '',
            'categoria' => 'descartable', // Campo categoria con valor por defecto
            'tipo' => '',
            'cantidad' => 0,
            'lote' => '',
            'fecha_vencimiento' => null,
            'tiene_vencimiento' => 0,
            'ubicacion' => ''
        ];

        // Fusionar con valores por defecto solo si el campo no existe
        foreach ($camposDefecto as $campo => $valorDefecto) {
            if (!isset($insumo[$campo]) || $insumo[$campo] === null) {
                $insumo[$campo] = $valorDefecto;
            }
        }

        // Asegurar que tiene_vencimiento sea 0 o 1
        $insumo['tiene_vencimiento'] = (int)$insumo['tiene_vencimiento'];

        // REMOVIDO: La lógica de inferir categoría ya que ahora es un campo obligatorio
        // Si no tiene categoria definida, usar valor por defecto
        if (empty($insumo['categoria'])) {
            $insumo['categoria'] = 'descartable';
        }

        return $insumo;
    }

    /**
     * REMOVIDO: Método inferirCategoriaPorTipo ya no es necesario
     * La categoría ahora es un campo independiente del tipo
     */
}