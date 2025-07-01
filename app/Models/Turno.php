<?php
namespace App\Models;

use CodeIgniter\Model;

class Turno extends Model
{
    protected $table = 'turnos_quirurgicos';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'fecha', 'hora_inicio', 'hora_finalizacion', 'duracion', 'id_quirofano', 
        'id_cirujano', 'id_cirujano_ayudante', 'id_paciente', 'id_anestesista', 
        'id_tecnico_anestesista', 'id_instrumentador_principal', 'id_instrumentador_circulante',
        'procedimiento', 'tipo_anestesia', 'estado', 'observaciones', 'complicaciones'
    ];

    public function listarTurnos()
    {
        return $this->select('turnos_quirurgicos.*, 
                            pacientes.nombre as nombre_paciente, 
                            cirujanos.nombre as nombre_cirujano, 
                            quirofanos.nombre as nombre_quirofano')
                   ->join('pacientes', 'pacientes.id = turnos_quirurgicos.id_paciente')
                   ->join('cirujanos', 'cirujanos.id = turnos_quirurgicos.id_cirujano')
                   ->join('quirofanos', 'quirofanos.id = turnos_quirurgicos.id_quirofano')
                   ->orderBy('fecha', 'ASC')
                   ->orderBy('hora_inicio', 'ASC')
                   ->findAll();
    }

    public function obtenerTurno($id)
    {
        return $this->find($id);
    }

    public function verificarDisponibilidad($quirofanoId, $fecha, $horaInicio, $duracion, $excludeId = null)
    {
        $horaFin = date('H:i:s', strtotime("+{$duracion} minutes", strtotime($horaInicio)));
        
        $query = $this->where('id_quirofano', $quirofanoId)
                      ->where('fecha', $fecha)
                      ->where('estado !=', 'cancelado');
        
        if ($excludeId) {
            $query->where('id !=', $excludeId);
        }
        
        $query->where("(TIME(hora_inicio) < TIME('{$horaFin}') AND 
                      ADDTIME(TIME(hora_inicio), SEC_TO_TIME(duracion*60)) > TIME('{$horaInicio}'))");
        
        return $query->countAllResults() == 0;
    }

    public function obtenerCirujanos()
    {
        $db = \Config\Database::connect();
        return $db->table('cirujanos')
                 ->select('cirujanos.*, especialidades.nombre as especialidad')
                 ->join('especialidades', 'especialidades.id = cirujanos.id_especialidad')
                 ->where('disponibilidad', 'disponible')
                 ->orderBy('nombre', 'ASC')
                 ->get()
                 ->getResult();
    }

    public function obtenerPacientes()
    {
        $db = \Config\Database::connect();
        return $db->table('pacientes')
                 ->orderBy('nombre', 'ASC')
                 ->get()
                 ->getResult();
    }

    public function obtenerQuirofanos()
    {
        $db = \Config\Database::connect();
        return $db->table('quirofanos')
                 ->where('estado', 'activo')
                 ->orderBy('nombre', 'ASC')
                 ->get()
                 ->getResult();
    }

    public function obtenerAnestesistas()
    {
        $db = \Config\Database::connect();
        return $db->table('anestesistas')
                 ->where('disponibilidad', 'disponible')
                 ->orderBy('nombre', 'ASC')
                 ->get()
                 ->getResult();
    }

    public function obtenerTecnicosAnestesistas()
    {
        $db = \Config\Database::connect();
        return $db->table('enfermeros')
                 ->where('disponibilidad', 'disponible')
                 ->orderBy('nombre', 'ASC')
                 ->get()
                 ->getResult();
    }

    public function obtenerInstrumentistas()
    {
        $db = \Config\Database::connect();
        return $db->table('instrumentistas')
                 ->where('disponibilidad', 'disponible')
                 ->orderBy('nombre', 'ASC')
                 ->get()
                 ->getResult();
    }

    public function obtenerProcedimientosPorEspecialidad($especialidadId)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('procedimientos');
        
        if ($especialidadId) {
            $builder->where('id_especialidad', $especialidadId);
        }
        
        return $builder->orderBy('nombre', 'ASC')
                     ->get()
                     ->getResult();
    }

    public function obtenerInsumosTurno($turnoId)
    {
        $db = \Config\Database::connect();
        return $db->table('turnos_insumos')
                 ->select('turnos_insumos.*, insumos.nombre, insumos.tipo, insumos.codigo')
                 ->join('insumos', 'insumos.id_insumo = turnos_insumos.id_insumo')
                 ->where('id_turno', $turnoId)
                 ->get()
                 ->getResult();
    }

    public function guardarInsumoTurno($turnoId, $insumoId, $cantidad)
    {
        $db = \Config\Database::connect();
        return $db->table('turnos_insumos')
                 ->insert([
                     'id_turno' => $turnoId,
                     'id_insumo' => $insumoId,
                     'cantidad' => $cantidad,
                     'fecha_registro' => date('Y-m-d H:i:s')
                 ]);
    }

    public function eliminarInsumosTurno($turnoId)
    {
        $db = \Config\Database::connect();
        return $db->table('turnos_insumos')
                 ->where('id_turno', $turnoId)
                 ->delete();
    }

    public function obtenerTurnoCompleto($id)
    {
        $db = \Config\Database::connect();
        return $db->table('turnos_quirurgicos t')
                 ->select('t.*, 
                         pacientes.nombre as nombre_paciente, 
                         pacientes.dni as dni_paciente,
                         cirujanos.nombre as nombre_cirujano,
                         cirujanos.id_especialidad as id_especialidad,
                         cirujanos_ayudantes.nombre as nombre_cirujano_ayudante,
                         quirofanos.nombre as nombre_quirofano,
                         anestesistas.nombre as nombre_anestesista,
                         enfermeros.nombre as nombre_tecnico_anestesista,
                         i1.nombre as nombre_instrumentador_principal,
                         i2.nombre as nombre_instrumentador_circulante')
                 ->join('pacientes', 'pacientes.id = t.id_paciente')
                 ->join('cirujanos', 'cirujanos.id = t.id_cirujano')
                 ->join('cirujanos as cirujanos_ayudantes', 'cirujanos_ayudantes.id = t.id_cirujano_ayudante', 'left')
                 ->join('quirofanos', 'quirofanos.id = t.id_quirofano')
                 ->join('anestesistas', 'anestesistas.id = t.id_anestesista', 'left')
                 ->join('enfermeros', 'enfermeros.id = t.id_tecnico_anestesista', 'left')
                 ->join('instrumentistas as i1', 'i1.id = t.id_instrumentador_principal', 'left')
                 ->join('instrumentistas as i2', 'i2.id = t.id_instrumentador_circulante', 'left')
                 ->where('t.id', $id)
                 ->get()
                 ->getRowArray();
    }
    // Contar turnos para hoy
public function countTurnosHoy()
{
    return $this->where('fecha', date('Y-m-d'))
               ->where('estado !=', 'cancelado')
               ->countAllResults();
}

// Obtener turnos programados
public function getTurnosProgramados($limit = 5)
{
    return $this->select('turnos_quirurgicos.*, pacientes.nombre as paciente_nombre')
               ->join('pacientes', 'pacientes.id = turnos_quirurgicos.id_paciente')
               ->where('fecha >=', date('Y-m-d'))
               ->where('estado', 'programado')
               ->orderBy('fecha, hora_inicio', 'ASC')
               ->limit($limit)
               ->findAll();
}

// Obtener cirugías de la semana
public function getCirugiasSemana()
{
    $startOfWeek = date('Y-m-d', strtotime('monday this week'));
    $endOfWeek = date('Y-m-d', strtotime('sunday this week'));
    
    $result = $this->select("DAYNAME(fecha) as dia, COUNT(*) as cantidad")
                  ->where('fecha >=', $startOfWeek)
                  ->where('fecha <=', $endOfWeek)
                  ->groupBy("DAYNAME(fecha)")
                  ->orderBy("fecha")
                  ->findAll();
    
    $diasSemana = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
    $labels = ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'];
    $data = array_fill(0, 7, 0);
    
    foreach ($result as $row) {
        $index = array_search($row->dia, $diasSemana);
        if ($index !== false) {
            $data[$index] = $row->cantidad;
        }
    }
    
    return [
        'labels' => $labels,
        'data' => $data
    ];
}

// Obtener estadísticas por especialidad
public function getEspecialidadStats()
{
    $result = $this->select('especialidades.nombre as especialidad, COUNT(*) as cantidad')
                  ->join('cirujanos', 'cirujanos.id = turnos_quirurgicos.id_cirujano')
                  ->join('especialidades', 'especialidades.id = cirujanos.id_especialidad')
                  ->where('fecha >=', date('Y-m-d', strtotime('-1 month')))
                  ->groupBy('especialidades.nombre')
                  ->orderBy('cantidad', 'DESC')
                  ->findAll();
    
    $labels = [];
    $data = [];
    
    foreach ($result as $row) {
        $labels[] = $row->especialidad;
        $data[] = $row->cantidad;
    }
    
    return [
        'labels' => $labels,
        'data' => $data
    ];
}

// Obtener próximas cirugías
public function getProximasCirugias()
{
    $builder = $this->builder();
    return $builder
        ->select('turnos_quirurgicos.*, pacientes.nombre as paciente_nombre, cirujanos.nombre as cirujano_nombre, procedimiento, estado')
        ->join('pacientes', 'pacientes.id = turnos_quirurgicos.id_paciente')
        ->join('cirujanos', 'cirujanos.id = turnos_quirurgicos.id_cirujano')
        ->where('fecha >=', date('Y-m-d'))
        ->orderBy('fecha, hora_inicio', 'ASC')
        ->get()
        ->getResult();
}
}