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
    protected $beforeInsert = ['generateCode'];
    protected $afterInsert = ['updateLote'];

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
            return false; // No se puede borrar
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
     * Obtener insumos con stock bajo
     */
    public function getLowStockInsumos($minQuantity = 10)
    {
        return $this->where('cantidad <', $minQuantity)->findAll();
    }

    /**
     * Generar código automático antes de insertar
     */
    protected function generateCode(array $data)
    {
        if (empty($data['data']['codigo'])) {
            $data['data']['codigo'] = 'INS-' . strtoupper(substr(md5(uniqid()), 0, 6));
        }
        
        // Generar lote temporal
        $data['data']['lote'] = 'LOTE-' . date('Ymd');
        
        return $data;
    }

    /**
     * Actualizar lote después de insertar
     */
    protected function updateLote(array $data)
    {
        if (isset($data['id'])) {
            $lote = 'LOTE-' . date('Ymd') . '-' . str_pad($data['id'], 4, '0', STR_PAD_LEFT);
            $this->update($data['id'], ['lote' => $lote]);
        }
        return $data;
    }
}