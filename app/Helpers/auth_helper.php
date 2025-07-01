<?php

if (!function_exists('auth_check')) {
    function auth_check(): bool
    {
        return session()->get('logged_in') === true;
    }
}

if (!function_exists('auth_user')) {
    function auth_user(?string $field = null)
    {
        $user = [
            'id' => session()->get('user_id'),
            'username' => session()->get('username'),
            'name' => session()->get('nombre'),
            'lastname' => session()->get('apellidos'),
            'email' => session()->get('email'),
            'role' => session()->get('rol')
        ];

        return $field ? ($user[$field] ?? null) : $user;
    }
}

if (!function_exists('auth_redirect')) {
    function auth_redirect(string $message = 'Debe iniciar sesión para acceder a esta página')
    {
        return redirect()->to('/auth/login')
            ->with('error', $message);
    }
}

if (!function_exists('role_check')) {
    function role_check($requiredRoles): bool
    {
        $userRole = session()->get('rol');
        
        if (is_array($requiredRoles)) {
            return in_array($userRole, $requiredRoles);
        }
        
        return $userRole === $requiredRoles;
    }
}

if (!function_exists('role_redirect')) {
    function role_redirect($requiredRoles, string $redirectTo = '/inicio', string $message = 'No tiene permisos para acceder a esta sección')
    {
        if (!role_check($requiredRoles)) {
            return redirect()->to($redirectTo)
                ->with('error', $message);
        }
        return null;
    }
}

if (!function_exists('is_admin')) {
    function is_admin(): bool
    {
        return session()->get('rol') === 'admin';
    }
}

if (!function_exists('is_medico')) {
    function is_medico(): bool
    {
        return session()->get('rol') === 'medico';
    }
}

if (!function_exists('is_recepcion')) {
    function is_recepcion(): bool
    {
        return session()->get('rol') === 'recepcion';
    }
}

if (!function_exists('is_enfermero')) {
    function is_enfermero(): bool
    {
        return session()->get('rol') === 'enfermero';
    }
}