<?php
namespace App\Models;

use CodeIgniter\Model;

class QuirofanoModel extends Model
{
    protected $table = 'quirofanos';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nombre', 'descripcion', 'estado'];
    protected $returnType = 'array';
    protected $useTimestamps = false;

    public function countActivos()
    {
        return $this->where('estado', 'activo')->countAllResults();
    }

    public function getOcupacion()
    {
        $db = db_connect();
        
        // Obtener ocupación por quirófano
        $builder = $db->table('quirofanos q');
        $builder->select("q.nombre, 
                         COUNT(t.id) as total_cirugias,
                         SUM(CASE WHEN t.estado = 'completado' THEN 1 ELSE 0 END) as completadas,
                         SUM(CASE WHEN t.estado = 'programado' THEN 1 ELSE 0 END) as programadas,
                         SUM(CASE WHEN t.estado = 'en_proceso' THEN 1 ELSE 0 END) as en_proceso")
                ->join('turnos_quirurgicos t', 't.id_quirofano = q.id AND t.fecha >= CURDATE() - INTERVAL 7 DAY', 'left')
                ->groupBy('q.id')
                ->orderBy('q.nombre');
        
        $result = $builder->get()->getResultArray();
        
        $labels = [];
        $data = [];
        
        foreach ($result as $row) {
            $labels[] = $row['nombre'];
            $ocupacion = 0;
            
            if ($row['total_cirugias'] > 0) {
                $ocupacion = (($row['completadas'] + $row['en_proceso']) / $row['total_cirugias']) * 100;
            }
            
            $data[] = round($ocupacion);
        }
        
        return ['labels' => $labels, 'data' => $data];
    }
}