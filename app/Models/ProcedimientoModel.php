<?php
namespace App\Models;

use CodeIgniter\Model;

class ProcedimientoModel extends Model
{
    protected $table = 'procedimientos';
    protected $primaryKey = 'id';
    protected $allowedFields = ['id_especialidad', 'nombre'];
    protected $returnType = 'array';
    
    public function getProcedimientosPorEspecialidad($id_especialidad)
    {
        return $this->where('id_especialidad', $id_especialidad)->findAll();
    }
}