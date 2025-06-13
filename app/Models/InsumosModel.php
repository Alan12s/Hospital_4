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
        'nombre', 
        'tipo', 
        'cantidad', 
        'ubicacion', 
        'lote', 
        'codigo',
        'tiene_vencimiento', 
        'fecha_vencimiento'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation
    protected $validationRules = [
        'nombre' => 'required|max_length[70]',
        'tipo' => 'required|max_length[50]',
        'cantidad' => 'required|numeric',
        'ubicacion' => 'required|max_length[100]'
    ];

    protected $validationMessages = [
        'nombre' => [
            'required' => 'El nombre es obligatorio',
            'max_length' => 'El nombre no puede exceder los 70 caracteres'
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
        return $this->orderBy('nombre', 'ASC')->findAll();
    }

    /**
     * Obtener un insumo por ID
     */
    public function getInsumo($id)
    {
        return $this->find($id);
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
        
        // Generar lote temporal
        $data['lote'] = 'LOTE-' . date('Ymd');
        
        $id = $this->insert($data);
        
        if ($id) {
            // Actualizar el lote con el ID generado
            $lote = 'LOTE-' . date('Ymd') . '-' . str_pad($id, 4, '0', STR_PAD_LEFT);
            $this->update($id, ['lote' => $lote]);
        }
        
        return $id;
    }

    /**
     * Actualizar un insumo
     */
    public function updateInsumo($id, $data)
    {
        return $this->update($id, $data);
    }

    /**
     * Verificar si un insumo está en uso
     */
    public function estaEnUso($idInsumo)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('turnos_insumos');
        $query = $builder->where('id_insumo', $idInsumo)->get();
        return $query->getNumRows() > 0;
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
        return $this->like('nombre', $term)->findAll();
    }

    /**
     * Obtener insumos con stock bajo (umbral normal)
     */
    public function getLowStockInsumos($minQuantity = 10)
    {
        return $this->where('cantidad <', $minQuantity)->findAll();
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
        return $this->where('cantidad <', $criticalQuantity)->findAll();
    }

    /**
     * Contar insumos con stock crítico (umbral más bajo)
     */
    public function countBajoStockCritico($criticalQuantity = 5)
    {
        return $this->where('cantidad <', $criticalQuantity)->countAllResults();
    }
}