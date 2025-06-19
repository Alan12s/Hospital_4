<?php

namespace App\Models;

use CodeIgniter\Model;

class AnestesistasModel extends Model
{
    protected $table = 'anestesistas';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'nombre',
        'especialidad',
        'telefono',
        'email',
        'disponibilidad'
    ];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation
    protected $validationRules = [
        'nombre' => 'required|max_length[100]',
        'especialidad' => 'required|max_length[100]',
        'telefono' => 'required|max_length[20]',
        'email' => 'required|valid_email|max_length[100]',
        'disponibilidad' => 'required|in_list[disponible,no_disponible,en_cirugia]'
    ];

    protected $validationMessages = [
        'nombre' => [
            'required' => 'El nombre es obligatorio',
            'max_length' => 'El nombre no puede exceder los 100 caracteres'
        ],
        'especialidad' => [
            'required' => 'La especialidad es obligatoria',
            'max_length' => 'La especialidad no puede exceder los 100 caracteres'
        ],
        'telefono' => [
            'required' => 'El teléfono es obligatorio',
            'max_length' => 'El teléfono no puede exceder los 20 caracteres'
        ],
        'email' => [
            'required' => 'El email es obligatorio',
            'valid_email' => 'Debe ser un email válido',
            'max_length' => 'El email no puede exceder los 100 caracteres'
        ],
        'disponibilidad' => [
            'required' => 'La disponibilidad es obligatoria',
            'in_list' => 'La disponibilidad debe ser: disponible, no_disponible o en_cirugia'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;

    /**
     * Obtener todos los anestesistas ordenados por nombre
     */
    public function getAllAnestesistas()
    {
        return $this->orderBy('nombre', 'ASC')->findAll();
    }

    /**
     * Obtener un anestesista por ID
     */
    public function getAnestesista($id)
    {
        return $this->find($id);
    }

    /**
     * Agregar un nuevo anestesista
     */
    public function addAnestesista($data)
    {
        return $this->insert($data);
    }

    /**
     * Actualizar un anestesista
     */
    public function updateAnestesista($id, $data)
    {
        return $this->update($id, $data);
    }

    /**
     * Verificar si un anestesista está en uso en turnos quirúrgicos
     */
    public function estaEnUso($idAnestesista)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('turnos_quirurgicos');
        
        $builder->where('id_anestesista', $idAnestesista);
        
        $query = $builder->get();
        return $query->getNumRows() > 0;
    }

    /**
     * Eliminar anestesista verificando que no esté en uso
     */
    public function deleteAnestesista($id)
    {
        if ($this->estaEnUso($id)) {
            return false;
        }
        return $this->delete($id);
    }

    /**
     * Buscar anestesistas por término
     */
    public function searchAnestesistas($term)
    {
        return $this->groupStart()
                    ->like('nombre', $term)
                    ->orLike('especialidad', $term)
                    ->orLike('email', $term)
                    ->groupEnd()
                    ->findAll();
    }

    /**
     * Obtener anestesistas disponibles
     */
    public function getAnestesistasDisponibles()
    {
        return $this->where('disponibilidad', 'disponible')
                    ->orderBy('nombre', 'ASC')
                    ->findAll();
    }

    /**
     * Obtener anestesistas por especialidad
     */
    public function getAnestesistasPorEspecialidad($especialidad)
    {
        return $this->where('especialidad', $especialidad)
                    ->where('disponibilidad', 'disponible')
                    ->orderBy('nombre', 'ASC')
                    ->findAll();
    }

    /**
     * Cambiar disponibilidad de un anestesista
     */
    public function cambiarDisponibilidad($id, $disponibilidad)
    {
        return $this->update($id, ['disponibilidad' => $disponibilidad]);
    }

    /**
     * Contar anestesistas por disponibilidad
     */
    public function countPorDisponibilidad($disponibilidad)
    {
        return $this->where('disponibilidad', $disponibilidad)->countAllResults();
    }

    /**
     * Obtener estadísticas de anestesistas
     */
    public function getEstadisticas()
    {
        return [
            'total' => $this->countAllResults(),
            'disponibles' => $this->countPorDisponibilidad('disponible'),
            'no_disponibles' => $this->countPorDisponibilidad('no_disponible'),
            'en_cirugia' => $this->countPorDisponibilidad('en_cirugia')
        ];
    }
}