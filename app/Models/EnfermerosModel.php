<?php

namespace App\Models;

use CodeIgniter\Model;

class EnfermerosModel extends Model
{
    protected $table = 'enfermeros';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'nombre',
        'dni',
        'especialidad',
        'telefono',
        'email',
        'fecha_ingreso',
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
        'dni' => 'required|max_length[20]',
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
        'dni' => [
            'required' => 'El DNI es obligatorio',
            'max_length' => 'El DNI no puede exceder los 20 caracteres'
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
     * Obtener todos los enfermeros ordenados por nombre
     */
    public function getAllEnfermeros()
    {
        return $this->orderBy('nombre', 'ASC')->findAll();
    }

    /**
     * Obtener un enfermero por ID
     */
    public function getEnfermero($id)
    {
        return $this->find($id);
    }

    /**
     * Agregar un nuevo enfermero
     */
    public function addEnfermero($data)
    {
        return $this->insert($data);
    }

    /**
     * Actualizar un enfermero
     */
    public function updateEnfermero($id, $data)
    {
        return $this->update($id, $data);
    }

    /**
     * Verificar si un enfermero está en uso
     */
    public function estaEnUso($idEnfermero)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('turnos_quirurgicos');
        
        // Verificar en todas las columnas donde puede estar asignado un enfermero
        $builder->groupStart()
                ->where('id_enfermero', $idEnfermero)
                ->orWhere('id_instrumentador_principal', $idEnfermero)
                ->orWhere('id_instrumentador_circulante', $idEnfermero)
                ->orWhere('id_tecnico_anestesista', $idEnfermero)
                ->groupEnd();
        
        $query = $builder->get();
        return $query->getNumRows() > 0;
    }

    /**
     * Eliminar enfermero verificando que no esté en uso
     */
    public function deleteEnfermero($id)
    {
        if ($this->estaEnUso($id)) {
            return false;
        }
        return $this->delete($id);
    }

    /**
     * Buscar enfermeros por término
     */
    public function searchEnfermeros($term)
    {
        return $this->groupStart()
                    ->like('nombre', $term)
                    ->orLike('dni', $term)
                    ->orLike('especialidad', $term)
                    ->orLike('email', $term)
                    ->groupEnd()
                    ->findAll();
    }

    /**
     * Obtener enfermeros disponibles
     */
    public function getEnfermerosDisponibles()
    {
        return $this->where('disponibilidad', 'disponible')
                    ->orderBy('nombre', 'ASC')
                    ->findAll();
    }

    /**
     * Obtener enfermeros por especialidad
     */
    public function getEnfermerosPorEspecialidad($especialidad)
    {
        return $this->where('especialidad', $especialidad)
                    ->where('disponibilidad', 'disponible')
                    ->orderBy('nombre', 'ASC')
                    ->findAll();
    }

    /**
     * Cambiar disponibilidad de un enfermero
     */
    public function cambiarDisponibilidad($id, $disponibilidad)
    {
        return $this->update($id, ['disponibilidad' => $disponibilidad]);
    }

    /**
     * Contar enfermeros por disponibilidad
     */
    public function countPorDisponibilidad($disponibilidad)
    {
        return $this->where('disponibilidad', $disponibilidad)->countAllResults();
    }

    /**
     * Obtener estadísticas de enfermeros
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

    /**
     * Obtener enfermeros con turnos programados
     */
    public function getEnfermerosConTurnos()
    {
        $db = \Config\Database::connect();
        $builder = $db->table('enfermeros e');
        $builder->select('e.*, COUNT(tq.id) as total_turnos')
                ->join('turnos_quirurgicos tq', 'e.id = tq.id_enfermero OR e.id = tq.id_instrumentador_principal OR e.id = tq.id_instrumentador_circulante OR e.id = tq.id_tecnico_anestesista', 'left')
                ->where('tq.estado !=', 'completado')
                ->groupBy('e.id')
                ->orderBy('total_turnos', 'DESC');
        
        return $builder->get()->getResultArray();
    }
}