<?php

namespace App\Models;

use CodeIgniter\Model;

class CirujanoModel extends Model
{
    protected $table            = 'cirujanos';
    protected $primaryKey       = 'id';
    protected $allowedFields    = [
        'nombre',
        'dni',
        'id_especialidad',
        'telefono',
        'email',
        'disponibilidad'
    ];
    protected $returnType       = 'object';
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    // Listar cirujanos con nombre de especialidad
    public function listarMedicos()
    {
        return $this->select('cirujanos.*, especialidades.nombre as especialidad')
                    ->join('especialidades', 'cirujanos.id_especialidad = especialidades.id', 'left')
                    ->orderBy('cirujanos.id', 'ASC')
                    ->findAll();
    }

    // Guardar nuevo cirujano
    public function saveMedico($data)
    {
        return $this->insert($data);
    }

    // Buscar cirujano por ID con especialidad
    public function buscarPorID($id)
    {
        return $this->select('cirujanos.*, especialidades.nombre as especialidad')
                    ->join('especialidades', 'cirujanos.id_especialidad = especialidades.id', 'left')
                    ->where('cirujanos.id', $id)
                    ->first();
    }

    // Editar cirujano
    public function editar($data, $id)
    {
        return $this->update($id, $data);
    }

    // Eliminar cirujano
    public function eliminar($id)
    {
        return $this->delete($id);
    }

    // Contar cirujanos
    public function contarMedicos()
    {
        return $this->countAll();
    }

    // Obtener cirujanos disponibles
    public function medicosDisponibles()
    {
        return $this->select('cirujanos.*, especialidades.nombre as especialidad')
                    ->join('especialidades', 'cirujanos.id_especialidad = especialidades.id', 'left')
                    ->where('cirujanos.disponibilidad', 'disponible')
                    ->orderBy('cirujanos.nombre', 'ASC')
                    ->findAll();
    }

    // Método para compatibilidad con el controlador
    public function getDisponibles()
    {
        return $this->medicosDisponibles();
    }

    // Verificar si el cirujano tiene turnos quirúrgicos programados
    public function tieneTurnosProgramados($cirujanoId)
    {
        // Verifica si existe la tabla antes de hacer la consulta
        if ($this->db->tableExists('turnos_quirurgicos')) {
            // Intentar con diferentes nombres de columna que podrían existir
            $columnasProbar = ['id_cirujano', 'cirujano_id', 'medico_id', 'id_medico'];
            
            foreach ($columnasProbar as $columna) {
                // Verificar si la columna existe en la tabla
                if ($this->db->fieldExists($columna, 'turnos_quirurgicos')) {
                    return $this->db->table('turnos_quirurgicos')
                                    ->where($columna, $cirujanoId)
                                    ->where('estado', 'programado')
                                    ->countAllResults() > 0;
                }
            }
            
            // Si ninguna columna existe, log para debug y retorna false
            log_message('info', 'No se encontró columna de referencia a cirujanos en turnos_quirurgicos');
            return false;
        }
        return false; // Si la tabla no existe, retorna false
    }

    // Obtener especialidades
    public function getEspecialidades()
    {
        // Verifica si existe la tabla antes de hacer la consulta
        if ($this->db->tableExists('especialidades')) {
            return $this->db->table('especialidades')
                            ->orderBy('nombre', 'ASC')
                            ->get()
                            ->getResult();
        }
        return []; // Si la tabla no existe, retorna array vacío
    }

    // Método auxiliar para verificar si una columna existe en una tabla
    private function columnExists($table, $column)
    {
        try {
            return $this->db->fieldExists($column, $table);
        } catch (\Exception $e) {
            log_message('error', 'Error verificando columna: ' . $e->getMessage());
            return false;
        }
    }
}