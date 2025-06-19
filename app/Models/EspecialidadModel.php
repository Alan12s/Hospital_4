<?php
namespace App\Models;

use CodeIgniter\Model;

class EspecialidadModel extends Model
{
    protected $table = 'especialidades';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nombre', 'descripcion'];
    protected $returnType = 'array';
    
    public function getEspecialidadesConCirujanos()
    {
        return $this->select('especialidades.*, COUNT(cirujanos.id) as total_cirujanos')
                    ->join('cirujanos', 'cirujanos.id_especialidad = especialidades.id', 'left')
                    ->groupBy('especialidades.id')
                    ->findAll();
    }
}