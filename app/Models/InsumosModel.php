<?php

namespace App\Models;

use CodeIgniter\Model;

class InsumosModel extends Model
{
    protected $table = 'insumos';
    protected $primaryKey = 'id_insumo';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'codigo',
        'nombre', 
        'categoria',  // AGREGADO: Campo categoria
        'tipo', 
        'cantidad', 
        'lote', 
        'fecha_vencimiento',
        'tiene_vencimiento', 
        'ubicacion'
    ];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation
    protected $validationRules = [
        'nombre' => 'required|max_length[70]',
        'categoria' => 'required|in_list[descartable,instrumental]', // AGREGADO: Validación categoria
        'tipo' => 'required|max_length[50]',
        'cantidad' => 'required|numeric',
        'ubicacion' => 'required|max_length[100]',
        'codigo' => 'permit_empty|max_length[100]' // CAMBIADO: codigo no es obligatorio
    ];

    protected $validationMessages = [
        'nombre' => [
            'required' => 'El nombre es obligatorio',
            'max_length' => 'El nombre no puede exceder los 70 caracteres'
        ],
        'categoria' => [
            'required' => 'La categoría es obligatoria',
            'in_list' => 'La categoría debe ser descartable o instrumental'
        ],
        'tipo' => [
            'required' => 'El tipo es obligatorio',
            'max_length' => 'El tipo no puede exceder los 50 caracteres'
        ],
        'cantidad' => [
            'required' => 'La cantidad es obligatoria',
            'numeric' => 'La cantidad debe ser un número'
        ],
        'ubicacion' => [
            'required' => 'La ubicación es obligatoria',
            'max_length' => 'La ubicación no puede exceder los 100 caracteres'
        ],
        'codigo' => [
            'max_length' => 'El código no puede exceder los 100 caracteres'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;

    /**
     * Obtener todos los insumos ordenados por nombre
     */
    public function getAllInsumos()
    {
        return $this->select('*')->orderBy('nombre', 'ASC')->findAll();
    }

    /**
     * Obtener un insumo por ID - Asegurando que se seleccionen todos los campos
     */
    public function getInsumo($id)
    {
        return $this->select('*')->find($id);
    }

    /**
     * Agregar un nuevo insumo
     */
    public function addInsumo($data)
    {
        // Generar código automático si no se provee
        if (empty($data['codigo'])) {
            $data['codigo'] = 'INS-' . strtoupper(substr(md5(uniqid()), 0, 6));
        }
        
        // Asegurar que tiene_vencimiento sea 0 o 1
        $data['tiene_vencimiento'] = isset($data['tiene_vencimiento']) && $data['tiene_vencimiento'] ? 1 : 0;
        
        // Si no tiene vencimiento, limpiar fecha_vencimiento
        if (!$data['tiene_vencimiento']) {
            $data['fecha_vencimiento'] = null;
        }
        
        // Generar lote si no se provee
        if (empty($data['lote'])) {
            $data['lote'] = 'LOTE-' . date('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        }
        
        // AGREGADO: Validar categoria por defecto
        if (empty($data['categoria'])) {
            $data['categoria'] = 'descartable';
        }
        
        $id = $this->insert($data);
        
        return $id;
    }

    /**
     * Actualizar un insumo
     */
    public function updateInsumo($id, $data)
    {
        // Asegurar que tiene_vencimiento sea 0 o 1
        $data['tiene_vencimiento'] = isset($data['tiene_vencimiento']) && $data['tiene_vencimiento'] ? 1 : 0;
        
        // Si no tiene vencimiento, limpiar fecha_vencimiento
        if (!$data['tiene_vencimiento']) {
            $data['fecha_vencimiento'] = null;
        }
        
        // AGREGADO: Validar categoria por defecto
        if (empty($data['categoria'])) {
            $data['categoria'] = 'descartable';
        }
        
        return $this->update($id, $data);
    }

    /**
     * Verificar si un insumo está en uso
     */
    public function estaEnUso($idInsumo)
    {
        $db = \Config\Database::connect();
        
        // Verificar en turnos_insumos
        $builder1 = $db->table('turnos_insumos');
        $count1 = $builder1->where('id_insumo', $idInsumo)->countAllResults();
        
        // Verificar en insumos_cirugia
        $builder2 = $db->table('insumos_cirugia');
        $count2 = $builder2->where('id_insumo', $idInsumo)->countAllResults();
        
        return ($count1 > 0 || $count2 > 0);
    }

    /**
     * Eliminar insumo verificando que no esté en uso
     */
    public function deleteInsumo($id)
    {
        if ($this->estaEnUso($id)) {
            return false;
        }
        return $this->delete($id);
    }

    /**
     * Buscar insumos por término
     */
    public function searchInsumos($term)
    {
        return $this->select('*')
                    ->like('nombre', $term)
                    ->orLike('tipo', $term)
                    ->orLike('codigo', $term)
                    ->orLike('ubicacion', $term)
                    ->orLike('categoria', $term) // AGREGADO: Buscar por categoria
                    ->findAll();
    }

    /**
     * Obtener insumos con stock bajo (umbral normal)
     */
    public function getLowStockInsumos($minQuantity = 10)
    {
        return $this->select('*')->where('cantidad <', $minQuantity)->findAll();
    }

    /**
     * Contar insumos con stock bajo (umbral normal)
     */
    public function countBajoStock($minQuantity = 10)
    {
        return $this->where('cantidad <', $minQuantity)->countAllResults();
    }

    /**
     * Obtener insumos con stock crítico (umbral más bajo)
     */
    public function getBajoStockCritico($criticalQuantity = 5)
    {
        return $this->select('*')->where('cantidad <', $criticalQuantity)->findAll();
    }

    /**
     * Contar insumos con stock crítico (umbral más bajo)
     */
    public function countBajoStockCritico($criticalQuantity = 5)
    {
        return $this->where('cantidad <', $criticalQuantity)->countAllResults();
    }

    /**
     * Obtener insumos por tipo
     */
    public function getInsumosPorTipo($tipo)
    {
        return $this->select('*')->where('tipo', $tipo)->findAll();
    }

    /**
     * AGREGADO: Obtener insumos por categoria
     */
    public function getInsumosPorCategoria($categoria)
    {
        return $this->select('*')->where('categoria', $categoria)->findAll();
    }

    /**
     * Obtener estadísticas por tipo
     */
    public function getEstadisticasPorTipo()
    {
        return $this->select('tipo, COUNT(*) as total, SUM(cantidad) as cantidad_total')
                    ->groupBy('tipo')
                    ->findAll();
    }

    /**
     * AGREGADO: Obtener estadísticas por categoria
     */
    public function getEstadisticasPorCategoria()
    {
        return $this->select('categoria, COUNT(*) as total, SUM(cantidad) as cantidad_total')
                    ->groupBy('categoria')
                    ->findAll();
    }

    /**
     * Obtener insumos próximos a vencer
     */
    public function getInsumosProximosVencer($dias = 30)
    {
        $fechaLimite = date('Y-m-d', strtotime("+{$dias} days"));
        
        return $this->select('*')
                    ->where('tiene_vencimiento', 1)
                    ->where('fecha_vencimiento IS NOT NULL')
                    ->where('fecha_vencimiento <=', $fechaLimite)
                    ->orderBy('fecha_vencimiento', 'ASC')
                    ->findAll();
    }

    /**
     * Obtener insumos vencidos
     */
    public function getInsumosVencidos()
    {
        $fechaHoy = date('Y-m-d');
        
        return $this->select('*')
                    ->where('tiene_vencimiento', 1)
                    ->where('fecha_vencimiento IS NOT NULL')
                    ->where('fecha_vencimiento <', $fechaHoy)
                    ->orderBy('fecha_vencimiento', 'ASC')
                    ->findAll();
    }
 public function getInsumosBajoStock($limit = 10)
{
    return $this->db->table('insumos')
        ->select('id_insumo, nombre, cantidad as stock, tipo, ubicacion')
        ->where('cantidad <', 50) // Umbral crítico (ajusta según necesidad)
        ->orderBy('cantidad', 'ASC')
        ->limit($limit)
        ->get()
        ->getResult();
}
}