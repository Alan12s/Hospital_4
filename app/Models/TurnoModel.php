<?php
namespace App\Models;

use CodeIgniter\Model;

class TurnoModel extends Model
{
    protected $table = 'turnos_quirurgicos';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'fecha', 'hora_inicio', 'id_quirofano', 'id_cirujano', 
        'id_paciente', 'id_procedimiento', 'procedimiento_custom', 
        'duracion', 'estado', 'id_enfermero', 'id_anestesista', 
        'observaciones', 'complicaciones', 'created_at', 'updated_at'
    ];
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $returnType = 'object';

    // Listar todos los turnos con información relacionada
    public function listarTurnos($filtros = [])
    {
        $builder = $this->db->table('turnos_quirurgicos t')
            ->select('t.*, c.nombre as nombre_cirujano, p.nombre as nombre_paciente, q.nombre as nombre_quirofano,
                     proc.nombre as nombre_procedimiento, e.nombre as nombre_enfermero,
                     a.nombre as nombre_anestesista')
            ->join('cirujanos c', 't.id_cirujano = c.id', 'left')
            ->join('pacientes p', 't.id_paciente = p.id', 'left')
            ->join('quirofanos q', 't.id_quirofano = q.id', 'left')
            ->join('procedimientos proc', 't.id_procedimiento = proc.id', 'left')
            ->join('enfermeros e', 't.id_enfermero = e.id', 'left')
            ->join('anestesistas a', 't.id_anestesista = a.id', 'left')
            ->orderBy('t.fecha', 'ASC')
            ->orderBy('t.hora_inicio', 'ASC');

        // Aplicar filtros si existen
        if (!empty($filtros['fecha'])) {
            $builder->where('t.fecha', $filtros['fecha']);
        }
        if (!empty($filtros['estado'])) {
            $builder->where('t.estado', $filtros['estado']);
        }
        if (!empty($filtros['quirofano'])) {
            $builder->where('t.id_quirofano', $filtros['quirofano']);
        }
        if (!empty($filtros['cirujano'])) {
            $builder->where('t.id_cirujano', $filtros['cirujano']);
        }

        return $builder->get()->getResult();
    }

    // Obtener un turno con toda su información
    public function getTurnoCompleto($id)
    {
        return $this->db->table('turnos_quirurgicos t')
            ->select('t.*, c.nombre as nombre_cirujano, c.especialidad_id, 
                     p.nombre as nombre_paciente, p.apellido as apellido_paciente, 
                     p.historia_clinica, q.nombre as nombre_quirofano, q.descripcion as descripcion_quirofano,
                     proc.nombre as nombre_procedimiento, e.nombre as nombre_enfermero,
                     a.nombre as nombre_anestesista, esp.nombre as nombre_especialidad')
            ->join('cirujanos c', 't.id_cirujano = c.id', 'left')
            ->join('pacientes p', 't.id_paciente = p.id', 'left')
            ->join('quirofanos q', 't.id_quirofano = q.id', 'left')
            ->join('procedimientos proc', 't.id_procedimiento = proc.id', 'left')
            ->join('enfermeros e', 't.id_enfermero = e.id', 'left')
            ->join('anestesistas a', 't.id_anestesista = a.id', 'left')
            ->join('especialidades esp', 'c.especialidad_id = esp.id', 'left')
            ->where('t.id', $id)
            ->get()
            ->getRow();
    }

    // Métodos para CRUD básico
    public function crearTurno($data)
    {
        return $this->insert($data);
    }

    public function actualizarTurno($id, $data)
    {
        return $this->update($id, $data);
    }

    public function cambiarEstado($id, $estado)
    {
        $estadosValidos = ['programado', 'en_proceso', 'completado', 'cancelado'];
        if (!in_array($estado, $estadosValidos)) {
            return false;
        }
        return $this->update($id, ['estado' => $estado]);
    }

    public function eliminarTurno($id)
    {
        $this->eliminarInsumosTurno($id);
        return $this->delete($id);
    }

    // Métodos para insumos
    public function guardarInsumoTurno($data)
    {
        return $this->db->table('turnos_insumos')->insert($data);
    }

    public function getInsumosTurno($turnoId)
    {
        return $this->db->table('turnos_insumos ti')
            ->select('ti.*, i.nombre, i.tipo, i.codigo, i.descripcion, i.unidad_medida')
            ->join('insumos i', 'ti.id_insumo = i.id')
            ->where('ti.id_turno', $turnoId)
            ->get()
            ->getResult();
    }

    public function eliminarInsumosTurno($turnoId)
    {
        return $this->db->table('turnos_insumos')->where('id_turno', $turnoId)->delete();
    }

    // Métodos para datos relacionados
    public function getCirujanos()
    {
        return $this->db->table('cirujanos c')
            ->select('c.id, c.nombre, c.apellido, c.matricula, e.nombre as especialidad')
            ->join('especialidades e', 'c.especialidad_id = e.id')
            ->orderBy('c.apellido', 'ASC')
            ->orderBy('c.nombre', 'ASC')
            ->get()
            ->getResult();
    }

    public function getPacientes()
    {
        return $this->db->table('pacientes')
            ->select('id, CONCAT(nombre, " ", apellido) as nombre_completo, historia_clinica')
            ->orderBy('apellido', 'ASC')
            ->orderBy('nombre', 'ASC')
            ->get()
            ->getResult();
    }

    public function getQuirofanos()
    {
        return $this->db->table('quirofanos')
            ->where('estado', 'activo')
            ->orderBy('nombre', 'ASC')
            ->get()
            ->getResult();
    }

    public function getAnestesistas()
    {
        return $this->db->table('anestesistas')
            ->where('disponibilidad', 'disponible')
            ->orderBy('nombre', 'ASC')
            ->get()
            ->getResult();
    }

    public function getEnfermeros()
    {
        return $this->db->table('enfermeros')
            ->orderBy('nombre', 'ASC')
            ->get()
            ->getResult();
    }

    // Verificar disponibilidad de quirófano
    public function checkDisponibilidad($quirofanoId, $fecha, $horaInicio, $duracion, $excludeId = null)
    {
        $horaFin = date('H:i:s', strtotime("+{$duracion} minutes", strtotime($horaInicio)));
        
        $builder = $this->db->table('turnos_quirurgicos')
            ->where('id_quirofano', $quirofanoId)
            ->where('fecha', $fecha)
            ->where('estado !=', 'cancelado');
        
        if ($excludeId) {
            $builder->where('id !=', $excludeId);
        }
        
        $where = "(TIME(hora_inicio) < TIME('{$horaFin}') AND 
                 ADDTIME(TIME(hora_inicio), SEC_TO_TIME(duracion*60)) > TIME('{$horaInicio}'))";
        
        return $builder->where($where, null, false)
            ->countAllResults() == 0;
    }

    // Obtener procedimientos por especialidad
    public function getProcedimientosPorEspecialidad($especialidadId)
    {
        return $this->db->table('procedimientos')
            ->where('id_especialidad', $especialidadId)
            ->orderBy('nombre', 'ASC')
            ->get()
            ->getResult();
    }

    // Obtener turnos para dashboard
    public function getTurnosDashboard($limit = 5)
    {
        $hoy = date('Y-m-d');
        
        $result = $this->db->table('turnos_quirurgicos t')
            ->select('t.*, p.nombre as nombre_paciente, p.apellido, c.nombre as nombre_cirujano')
            ->join('pacientes p', 't.id_paciente = p.id', 'left')
            ->join('cirujanos c', 't.id_cirujano = c.id', 'left')
            ->where('t.estado !=', 'cancelado')
            ->orderBy('t.fecha', 'ASC')
            ->orderBy('t.hora_inicio', 'ASC')
            ->limit($limit)
            ->get()
            ->getResult();
        
        $turnos = [];
        foreach ($result as $row) {
            $prioridad = 0;
            $hoy_flag = false;
            $urgente = false;
            
            if ($row->fecha == $hoy) {
                $prioridad = 1;
                $hoy_flag = true;
            } elseif ($row->fecha < $hoy) {
                $prioridad = 2;
                $urgente = true;
            }
            
            $turnos[] = [
                'id' => $row->id,
                'paciente' => "{$row->nombre_paciente} {$row->apellido}",
                'cirujano' => $row->nombre_cirujano,
                'fecha_hora' => date('d/m/Y H:i', strtotime("{$row->fecha} {$row->hora_inicio}")),
                'estado' => ucfirst(str_replace('_', ' ', $row->estado)),
                'estado_color' => $this->getEstadoColor($row->estado),
                'hoy' => $hoy_flag,
                'urgente' => $urgente,
                'prioridad' => $prioridad
            ];
        }
        
        usort($turnos, function($a, $b) {
            if ($a['prioridad'] == $b['prioridad']) {
                return strtotime($a['fecha_hora']) - strtotime($b['fecha_hora']);
            }
            return $a['prioridad'] - $b['prioridad'];
        });
        
        return $turnos;
    }

    // Métodos auxiliares
    private function getEstadoColor($estado)
    {
        $colores = [
            'programado' => 'primary',
            'en_proceso' => 'warning',
            'completado' => 'success',
            'cancelado' => 'secondary'
        ];
        return $colores[$estado] ?? 'info';
    }

    // Estadísticas
    public function getEstadisticas()
    {
        $hoy = date('Y-m-d');
        $mes = date('Y-m');
        
        return [
            'hoy' => [
                'programados' => $this->where('fecha', $hoy)
                                    ->where('estado', 'programado')
                                    ->countAllResults(),
                'en_proceso' => $this->where('fecha', $hoy)
                                    ->where('estado', 'en_proceso')
                                    ->countAllResults(),
                'completados' => $this->where('fecha', $hoy)
                                      ->where('estado', 'completado')
                                      ->countAllResults()
            ],
            'mes' => [
                'total' => $this->like('fecha', $mes, 'after')
                               ->countAllResults(),
                'por_estado' => $this->select('estado, COUNT(*) as total')
                                    ->like('fecha', $mes, 'after')
                                    ->groupBy('estado')
                                    ->get()
                                    ->getResult()
            ]
        ];
    }
}