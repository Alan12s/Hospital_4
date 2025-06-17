<?php

namespace App\Models;

use CodeIgniter\Model;

class PacienteModel extends Model
{
    protected $table = 'pacientes';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = ['nombre','fecha_nacimiento','obra_social','historial_medico', 'dni','departamento', 'telefono', 'direccion', 'email'];

    // Validación simplificada (quitamos is_unique porque validamos manualmente en controlador)
    protected $validationRules = [
        'nombre'    => 'required|max_length[100]',
        'historial_medico'  => 'required|max_length[400]',
        'dni'       => 'required|numeric|max_length[20]',
        'telefono'  => 'required|max_length[20]',
        'direccion' => 'required|max_length[255]',
        'email'     => 'required|valid_email'
    ];

    protected $validationMessages = [
        'nombre' => [
            'required'    => 'El nombre es obligatorio',
            'max_length'  => 'El nombre no puede exceder los 100 caracteres'
        ],
        'dni' => [
            'required'    => 'El DNI es obligatorio',
            'numeric'     => 'El DNI debe ser numérico',
        ],
        'telefono' => [
            'required'    => 'El teléfono es obligatorio',
            'max_length'  => 'El teléfono no puede exceder los 20 caracteres'
        ],
        'direccion' => [
            'required'    => 'La dirección es obligatoria',
            'max_length'  => 'La dirección no puede exceder los 255 caracteres'
        ],
        'email' => [
            'required'     => 'El email es obligatorio',
            'valid_email'  => 'El email no es válido',
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    public function getAllPacientes()
    {
        return $this->orderBy('nombre', 'ASC')->findAll();
    }

    public function getPaciente($id)
    {
        return $this->find($id);
    }

    public function dniExists($dni, $excludeId = null)
    {
        $builder = $this->where('dni', $dni);
        if ($excludeId !== null) {
            $builder->where('id !=', $excludeId);
        }
        return $builder->countAllResults() > 0;
    }

    public function emailExists($email, $excludeId = null)
    {
        $builder = $this->where('email', $email);
        if ($excludeId !== null) {
            $builder->where('id !=', $excludeId);
        }
        return $builder->countAllResults() > 0;
    }

    public function deletePaciente($id)
    {
        return $this->delete($id);
    }
    public function countPacientes()
{
    return $this->countAll();
}

}
