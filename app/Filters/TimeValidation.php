<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class TimeValidation implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // No necesitamos implementar nada aquí para este caso
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No necesitamos implementar nada aquí para este caso
    }

    public static function validate_time($str): bool
    {
        if (empty($str)) {
            return false;
        }

        // Formato HH:MM con horas de 00-23 y minutos de 00-59
        return (bool) preg_match('/^([01]?[0-9]|2[0-3]):[0-5][0-9]$/', $str);
    }
}