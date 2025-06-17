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
            return $this->db->table('turnos_quirurgicos')
                            ->where('id_medico', $cirujanoId)
                            ->where('estado', 'programado')
                            ->countAllResults() > 0;
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
    public function countCirujanos()
{
    return $this->countAll();
}

}