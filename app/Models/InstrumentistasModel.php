<?php

namespace App\Models;

use CodeIgniter\Model;

class InstrumentistasModel extends Model
{
    protected $table = 'instrumentistas';
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
     * Obtener todos los instrumentistas ordenados por nombre
     */
    public function getAllInstrumentistas()
    {
        return $this->orderBy('nombre', 'ASC')->findAll();
    }

    /**
     * Obtener un instrumentista por ID
     */
    public function getInstrumentista($id)
    {
        return $this->find($id);
    }

    /**
     * Agregar un nuevo instrumentista
     */
    public function addInstrumentista($data)
    {
        return $this->insert($data);
    }

    /**
     * Actualizar un instrumentista
     */
    public function updateInstrumentista($id, $data)
    {
        return $this->update($id, $data);
    }

    /**
     * Verificar si un instrumentista está en uso
     */
    public function estaEnUso($idInstrumentista)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('turnos_quirurgicos');
        
        // Verificar en todas las columnas donde puede estar asignado un instrumentista
        $builder->groupStart()
                ->where('id_instrumentador_principal', $idInstrumentista)
                ->orWhere('id_instrumentador_circulante', $idInstrumentista)
                ->groupEnd();
        
        $query = $builder->get();
        return $query->getNumRows() > 0;
    }

    /**
     * Eliminar instrumentista verificando que no esté en uso
     */
    public function deleteInstrumentista($id)
    {
        if ($this->estaEnUso($id)) {
            return false;
        }
        return $this->delete($id);
    }

    /**
     * Buscar instrumentistas por término
     */
    public function searchInstrumentistas($term)
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
     * Obtener instrumentistas disponibles
     */
    public function getInstrumentistasDisponibles()
    {
        return $this->where('disponibilidad', 'disponible')
                    ->orderBy('nombre', 'ASC')
                    ->findAll();
    }

    /**
     * Obtener instrumentistas por especialidad
     */
    public function getInstrumentistasPorEspecialidad($especialidad)
    {
        return $this->where('especialidad', $especialidad)
                    ->where('disponibilidad', 'disponible')
                    ->orderBy('nombre', 'ASC')
                    ->findAll();
    }

    /**
     * Cambiar disponibilidad de un instrumentista
     */
    public function cambiarDisponibilidad($id, $disponibilidad)
    {
        return $this->update($id, ['disponibilidad' => $disponibilidad]);
    }

    /**
     * Contar instrumentistas por disponibilidad
     */
    public function countPorDisponibilidad($disponibilidad)
    {
        return $this->where('disponibilidad', $disponibilidad)->countAllResults();
    }

    /**
     * Obtener estadísticas de instrumentistas
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