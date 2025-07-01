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

    // Validation - Solo para INSERT, no para UPDATE
    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = true; // Deshabilitamos validación automática
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
        // Activar validación solo para INSERT
        $this->skipValidation = false;
        $this->validationRules = [
            'nombre' => 'required|max_length[100]|regex_match[/^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]+$/]',
            'dni' => 'required|max_length[20]|regex_match[/^[0-9]+$/]|is_unique[enfermeros.dni]',
            'especialidad' => 'required|max_length[100]',
            'telefono' => 'required|max_length[20]|regex_match[/^[0-9\s()+-]+$/]',
            'email' => 'required|valid_email|max_length[100]',
            'disponibilidad' => 'required|in_list[disponible,no_disponible,en_cirugia]'
        ];
        
        $result = $this->insert($data);
        
        // Resetear validación
        $this->skipValidation = true;
        $this->validationRules = [];
        
        return $result;
    }

    /**
     * Actualizar un enfermero - Método mejorado
     */
    public function updateEnfermero($id, $data)
    {
        try {
            // Log para debug
            log_message('debug', 'EnfermerosModel::updateEnfermero - ID: ' . $id);
            log_message('debug', 'EnfermerosModel::updateEnfermero - Data: ' . json_encode($data));
            
            // Verificar que el ID existe
            $existe = $this->find($id);
            if (!$existe) {
                log_message('error', 'EnfermerosModel::updateEnfermero - Enfermero no encontrado con ID: ' . $id);
                return false;
            }
            
            // Verificar que hay datos para actualizar
            if (empty($data)) {
                log_message('error', 'EnfermerosModel::updateEnfermero - No hay datos para actualizar');
                return false;
            }
            
            // Filtrar solo campos permitidos
            $allowedData = [];
            foreach ($data as $key => $value) {
                if (in_array($key, $this->allowedFields)) {
                    $allowedData[$key] = $value;
                }
            }
            
            if (empty($allowedData)) {
                log_message('error', 'EnfermerosModel::updateEnfermero - No hay campos válidos para actualizar');
                return false;
            }
            
            // Desactivar validación automática para UPDATE
            $this->skipValidation = true;
            
            // Realizar la actualización
            $result = $this->update($id, $allowedData);
            
            log_message('debug', 'EnfermerosModel::updateEnfermero - Resultado: ' . ($result ? 'exitoso' : 'fallido'));
            log_message('debug', 'EnfermerosModel::updateEnfermero - Affected rows: ' . $this->db->affectedRows());
            
            return $result;
            
        } catch (\Exception $e) {
            log_message('error', 'EnfermerosModel::updateEnfermero - Excepción: ' . $e->getMessage());
            return false;
        }
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
        // ->where('id_enfermero', $idEnfermero) // Esta línea está de más y causa el error
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
    // Método para contar enfermeros disponibles
public function countDisponibles()
{
    return $this->where('disponibilidad', 'disponible')->countAllResults();
}
public function countAll()
{
    return $this->builder()->countAllResults();
}
}