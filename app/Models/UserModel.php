<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'usuarios';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'nombre', 'apellidos', 'email', 'username', 
        'password', 'rol', 'estado', 'ultimo_acceso'
    ];
    protected $useTimestamps = false;

    /**
     * Buscar usuario por username o email
     */
    public function findUserByUsernameOrEmail($identifier)
    {
        return $this->where('(username = "' . $identifier . '" OR email = "' . $identifier . '")')
                    ->where('estado', 1)
                    ->first();
    }

    /**
     * Actualizar último acceso del usuario
     */
    public function updateLastAccess($userId)
    {
        return $this->update($userId, ['ultimo_acceso' => date('Y-m-d H:i:s')]);
    }

    /**
     * Verificar credenciales
     */
    public function verifyCredentials($identifier, $password)
    {
        $user = $this->findUserByUsernameOrEmail($identifier);
        
        if ($user && password_verify($password, $user['password'])) {
            $this->updateLastAccess($user['id']);
            return $user;
        }
        
        return false;
    }
}