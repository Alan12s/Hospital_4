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
        'created_at',
        'updated_at',
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
        $this->actualizarDisponibilidadAutomatica();
        
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
        if (!$this->db->tableExists('turnos_quirurgicos')) {
            return false;
        }

        // Verificar como cirujano principal o ayudante
        $query = $this->db->table('turnos_quirurgicos')
            ->groupStart()
                ->where('id_cirujano', $cirujanoId)
                ->orWhere('id_cirujano_ayudante', $cirujanoId)
            ->groupEnd()
            ->whereIn('estado', ['programado', 'agendada', 'en_proceso'])
            ->limit(1);

        return $query->countAllResults() > 0;
    }

    // Verificar otras dependencias si existen
    public function tieneDependencias($cirujanoId)
    {
        return false;
    }

    // Obtener especialidades
    public function getEspecialidades()
    {
        if ($this->db->tableExists('especialidades')) {
            return $this->db->table('especialidades')
                            ->orderBy('nombre', 'ASC')
                            ->get()
                            ->getResult();
        }
        return [];
    }

    // Método para contar cirujanos
    public function countCirujanos()
    {
        return $this->countAll();
    }

    /**
     * Actualiza automáticamente la disponibilidad de los cirujanos
     * basado en sus turnos quirúrgicos
     */
    public function actualizarDisponibilidadAutomatica()
    {
        if (!$this->db->tableExists('turnos_quirurgicos')) {
            return;
        }

        // Obtener la fecha y hora actual
        $now = date('Y-m-d H:i:s');
        $halfHourAgo = date('Y-m-d H:i:s', strtotime('-30 minutes'));
        $halfHourLater = date('Y-m-d H:i:s', strtotime('+30 minutes'));

        // 1. Primero resetear todos los que están "en_cirugia" pero ya no deberían estarlo
        $this->set('disponibilidad', 'disponible')
             ->where('disponibilidad', 'en_cirugia')
             ->whereNotIn('id', function($builder) use ($now, $halfHourAgo, $halfHourLater) {
                 $builder->select('id_cirujano')
                         ->from('turnos_quirurgicos')
                         ->where("CONCAT(fecha, ' ', hora_inicio) BETWEEN '$halfHourAgo' AND '$halfHourLater'")
                         ->whereIn('estado', ['programado', 'en_proceso', 'agendada'])
                         ->unionAll(
                             $this->db->table('turnos_quirurgicos')
                                      ->select('id_cirujano_ayudante')
                                      ->where("CONCAT(fecha, ' ', hora_inicio) BETWEEN '$halfHourAgo' AND '$halfHourLater'")
                                      ->whereIn('estado', ['programado', 'en_proceso', 'agendada'])
                                      ->where('id_cirujano_ayudante IS NOT NULL')
                         );
             })
             ->update();

        // 2. Actualizar a "en_cirugia" los que tienen turnos en el rango de tiempo
        $cirujanosEnCirugia = $this->db->table('turnos_quirurgicos')
            ->select('id_cirujano as id')
            ->where("CONCAT(fecha, ' ', hora_inicio) BETWEEN '$halfHourAgo' AND '$halfHourLater'")
            ->whereIn('estado', ['programado', 'en_proceso', 'agendada'])
            ->unionAll(
                $this->db->table('turnos_quirurgicos')
                         ->select('id_cirujano_ayudante as id')
                         ->where("CONCAT(fecha, ' ', hora_inicio) BETWEEN '$halfHourAgo' AND '$halfHourLater'")
                         ->whereIn('estado', ['programado', 'en_proceso', 'agendada'])
                         ->where('id_cirujano_ayudante IS NOT NULL')
            )
            ->get()
            ->getResult();

        if (!empty($cirujanosEnCirugia)) {
            $ids = array_column($cirujanosEnCirugia, 'id');
            $this->whereIn('id', $ids)
                 ->set('disponibilidad', 'en_cirugia')
                 ->update();
        }
    }

    /**
     * Obtiene el estado actual de disponibilidad considerando los turnos
     */
    public function getDisponibilidadActual($cirujanoId)
    {
        $this->actualizarDisponibilidadAutomatica();
        $cirujano = $this->find($cirujanoId);
        return $cirujano ? $cirujano->disponibilidad : null;
    }
}