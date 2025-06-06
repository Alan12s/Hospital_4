<?php

namespace App\Models;

use CodeIgniter\Model;

class UsuarioModel extends Model
{
    protected $table = 'usuarios';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    
    protected $allowedFields = [
        'nombre',
        'apellidos',
        'email',
        'username',
        'password',
        'rol',
        'estado',
        'ultimo_acceso',
        'remember_token',
        'reset_token',
        'reset_expires'
    ];

    protected $useTimestamps = false;
    protected $dateFormat = 'datetime';
    protected $createdField = 'fecha_registro';
    protected $updatedField = '';
    protected $deletedField = '';
    
    // Validaciones
    protected $validationRules = [
        'nombre' => 'required|min_length[2]|max_length[50]',
        'apellidos' => 'required|min_length[2]|max_length[100]',
        'email' => 'required|valid_email|is_unique[usuarios.email,id,{id}]',
        'username' => 'required|min_length[3]|max_length[50]|is_unique[usuarios.username,id,{id}]',
        'password' => 'required|min_length[6]',
        'rol' => 'required|in_list[administrador,medico,enfermero,supervisor]'
    ];

    protected $validationMessages = [
        'nombre' => [
            'required' => 'El nombre es obligatorio',
            'min_length' => 'El nombre debe tener al menos 2 caracteres',
            'max_length' => 'El nombre no puede tener más de 50 caracteres'
        ],
        'apellidos' => [
            'required' => 'Los apellidos son obligatorios',
            'min_length' => 'Los apellidos deben tener al menos 2 caracteres',
            'max_length' => 'Los apellidos no pueden tener más de 100 caracteres'
        ],
        'email' => [
            'required' => 'El email es obligatorio',
            'valid_email' => 'Debe ingresar un email válido',
            'is_unique' => 'Este email ya está registrado'
        ],
        'username' => [
            'required' => 'El nombre de usuario es obligatorio',
            'min_length' => 'El nombre de usuario debe tener al menos 3 caracteres',
            'max_length' => 'El nombre de usuario no puede tener más de 50 caracteres',
            'is_unique' => 'Este nombre de usuario ya existe'
        ],
        'password' => [
            'required' => 'La contraseña es obligatoria',
            'min_length' => 'La contraseña debe tener al menos 6 caracteres'
        ],
        'rol' => [
            'required' => 'El rol es obligatorio',
            'in_list' => 'Debe seleccionar un rol válido'
        ]
    ];

    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    // Métodos personalizados
    public function getUserByUsername($username)
    {
        return $this->where('username', $username)
                    ->where('estado', 1)
                    ->first();
    }

    public function getUserByEmail($email)
    {
        return $this->where('email', $email)
                    ->where('estado', 1)
                    ->first();
    }

    public function updateLastAccess($userId)
    {
        return $this->update($userId, [
            'ultimo_acceso' => date('Y-m-d H:i:s')
        ]);
    }

    public function getRoleUsers($role)
    {
        return $this->where('rol', $role)
                    ->where('estado', 1)
                    ->findAll();
    }

    // Callback para encriptar contraseña antes de insertar/actualizar
    protected function hashPassword(array $data)
    {
        if (isset($data['data']['password'])) {
            $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
        }
        return $data;
    }

    protected $beforeInsert = ['hashPassword'];
    protected $beforeUpdate = ['hashPassword'];
}