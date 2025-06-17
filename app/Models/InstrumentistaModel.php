<?php

namespace App\Models;

use CodeIgniter\Model;

class InstrumentistaModel extends Model
{
    protected $table = 'instrumentistas'; // Nombre de la tabla en la base de datos
    protected $primaryKey = 'id';
    protected $allowedFields = ['nombre', 'apellido', 'especialidad'];
    protected $returnType = 'array';
}
