<?php

namespace App\Models;

use CodeIgniter\Model;

class UsuarioModel extends Model
{
    protected $table = 'usuarios';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = ['nombre', 'apellidos', 'email', 'username', 'rol', 'estado', 'password', 'ultimo_acceso'];

    protected $validationRules = [
        'nombre'     => 'required|max_length[50]|regex_match[/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/]',
        'apellidos'  => 'required|max_length[100]|regex_match[/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/]',
        'email'      => 'required|valid_email|max_length[50]',
        'username'   => 'required|max_length[50]|alpha_dash',
        'password'   => 'permit_empty|min_length[6]',
        'rol'        => 'required|in_list[administrador,cirujano,enfermero,supervisor,usuario]',
        'estado'     => 'required|in_list[0,1]'
    ];

    protected $validationMessages = [
        'nombre' => [
            'required'     => 'El nombre es obligatorio',
            'max_length'   => 'El nombre no puede exceder los 50 caracteres',
            'regex_match'  => 'El nombre solo puede contener letras y espacios'
        ],
        'apellidos' => [
            'required'     => 'Los apellidos son obligatorios',
            'max_length'   => 'Los apellidos no pueden exceder los 100 caracteres',
            'regex_match'  => 'Los apellidos solo pueden contener letras y espacios'
        ],
        'email' => [
            'required'     => 'El email es obligatorio',
            'valid_email'  => 'Ingrese un email válido',
            'max_length'   => 'El email no puede exceder los 50 caracteres'
        ],
        'username' => [
            'required'     => 'El nombre de usuario es obligatorio',
            'max_length'   => 'El nombre de usuario no puede exceder los 50 caracteres',
            'alpha_dash'   => 'El nombre de usuario solo puede contener letras, números, guiones y guiones bajos'
        ],
        'password' => [
            'min_length'   => 'La contraseña debe tener al menos 6 caracteres'
        ],
        'rol' => [
            'required'     => 'El rol es obligatorio',
            'in_list'      => 'Seleccione un rol válido'
        ],
        'estado' => [
            'required'     => 'El estado es obligatorio',
            'in_list'      => 'Seleccione un estado válido'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'fecha_registro';
    protected $updatedField = 'fecha_actualizacion';

    public function listarUsuarios()
    {
        return $this->orderBy('nombre', 'ASC')->findAll();
    }

    public function getErrorsAsString()
    {
        $errors = $this->errors();
        if (is_array($errors)) {
            return implode(', ', $errors);
        }
        return $errors;
    }

    public function isUniqueEmail($email, $id)
    {
        return $this->where('email', $email)->where('id !=', $id)->countAllResults() === 0;
    }

    public function isUniqueUsername($username, $id)
    {
        return $this->where('username', $username)->where('id !=', $id)->countAllResults() === 0;
    }
}