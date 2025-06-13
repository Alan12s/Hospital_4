<?php
namespace App\Models;

use CodeIgniter\Model;

class TurnoModel extends Model
{
    protected $table = 'turnos_quirurgicos';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'fecha', 'hora_inicio', 'hora_finalizacion', 'id_quirofano', 
        'id_cirujano', 'id_cirujano_ayudante', 'id_paciente', 'id_anestesista',
        'id_enfermero', 'id_instrumentador_principal', 'id_instrumentador_circulante',
        'id_tecnico_anestesista', 'tipo_anestesia', 'complicaciones', 'procedimiento',
        'estado', 'observaciones'
    ];
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function countTurnosHoy()
    {
        $hoy = date('Y-m-d');
        return $this->where('fecha', $hoy)
                   ->where('estado !=', 'cancelado')
                   ->countAllResults();
    }

    public function countTurnosSemana()
    {
        $inicioSemana = date('Y-m-d', strtotime('monday this week'));
        $finSemana = date('Y-m-d', strtotime('sunday this week'));
        
        return $this->where('fecha >=', $inicioSemana)
                   ->where('fecha <=', $finSemana)
                   ->where('estado !=', 'cancelado')
                   ->countAllResults();
    }

    public function getCirugiasSemana()
    {
        $inicioSemana = date('Y-m-d', strtotime('monday this week'));
        $finSemana = date('Y-m-d', strtotime('sunday this week'));
        
        $query = $this->select("DAYNAME(fecha) as dia, COUNT(*) as total")
                     ->where('fecha >=', $inicioSemana)
                     ->where('fecha <=', $finSemana)
                     ->where('estado !=', 'cancelado')
                     ->groupBy("DAY(fecha), DAYNAME(fecha)")
                     ->orderBy("fecha")
                     ->get();
        
        $result = $query->getResultArray();
        
        // Formatear datos para el gráfico
        $labels = [];
        $data = [];
        $diasSemana = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
        
        foreach ($diasSemana as $dia) {
            $encontrado = false;
            foreach ($result as $item) {
                if (strpos($item['dia'], $dia) !== false) {
                    $labels[] = $this->diaEspanol($dia);
                    $data[] = (int)$item['total'];
                    $encontrado = true;
                    break;
                }
            }
            if (!$encontrado) {
                $labels[] = $this->diaEspanol($dia);
                $data[] = 0;
            }
        }
        
        return ['labels' => $labels, 'data' => $data];
    }

    public function getEspecialidadStats()
    {
        $db = db_connect();
        $builder = $db->table('turnos_quirurgicos t');
        $builder->select('e.nombre as especialidad, COUNT(*) as total')
                ->join('cirujanos c', 't.id_cirujano = c.id')
                ->join('especialidades e', 'c.id_especialidad = e.id')
                ->where('t.estado !=', 'cancelado')
                ->where('t.fecha >=', date('Y-m-d', strtotime('-30 days')))
                ->groupBy('e.nombre')
                ->orderBy('total', 'DESC');
        
        $query = $builder->get();
        $result = $query->getResultArray();
        
        $labels = [];
        $data = [];
        
        foreach ($result as $row) {
            $labels[] = $row['especialidad'];
            $data[] = (int)$row['total'];
        }
        
        return ['labels' => $labels, 'data' => $data];
    }

    public function getTiempoPromedio()
    {
        $builder = $this->builder();
        $builder->select("AVG(TIMESTAMPDIFF(MINUTE, STR_TO_DATE(CONCAT(fecha, ' ', hora_inicio), '%Y-%m-%d %H:%i:%s'), STR_TO_DATE(CONCAT(fecha, ' ', hora_finalizacion), '%Y-%m-%d %H:%i:%s'))) as promedio")
               ->where('estado', 'completado')
               ->where('hora_finalizacion IS NOT NULL')
               ->where('fecha >=', date('Y-m-d', strtotime('-30 days')));
        
        $result = $builder->get()->getRow();
        return round($result->promedio ?? 0);
    }

    public function countCancelacionesSemana()
    {
        $inicioSemana = date('Y-m-d', strtotime('monday this week'));
        $finSemana = date('Y-m-d', strtotime('sunday this week'));
        
        return $this->where('fecha >=', $inicioSemana)
                   ->where('fecha <=', $finSemana)
                   ->where('estado', 'cancelado')
                   ->countAllResults();
    }

    public function countComplicacionesSemana()
    {
        $inicioSemana = date('Y-m-d', strtotime('monday this week'));
        $finSemana = date('Y-m-d', strtotime('sunday this week'));
        
        return $this->where('fecha >=', $inicioSemana)
                   ->where('fecha <=', $finSemana)
                   ->where('complicaciones IS NOT NULL')
                   ->countAllResults();
    }

 public function getTurnosProgramados($limit = 5)
{
    return $this->select('turnos_quirurgicos.*, p.nombre as paciente_nombre, c.nombre as cirujano_nombre, q.nombre as quirofano_nombre')
               ->join('pacientes p', 'turnos_quirurgicos.id_paciente = p.id')
               ->join('cirujanos c', 'turnos_quirurgicos.id_cirujano = c.id')
               ->join('quirofanos q', 'turnos_quirurgicos.id_quirofano = q.id')
               ->where('turnos_quirurgicos.fecha >=', date('Y-m-d'))
               ->where('turnos_quirurgicos.estado', 'programado')  // Especificar la tabla aquí
               ->orderBy('turnos_quirurgicos.fecha, turnos_quirurgicos.hora_inicio')
               ->limit($limit)
               ->get()
               ->getResultArray();
}

    private function diaEspanol($diaIngles)
    {
        $dias = [
            'Monday' => 'Lun',
            'Tuesday' => 'Mar',
            'Wednesday' => 'Mié',
            'Thursday' => 'Jue',
            'Friday' => 'Vie',
            'Saturday' => 'Sáb',
            'Sunday' => 'Dom'
        ];
        return $dias[$diaIngles] ?? $diaIngles;
    }
}