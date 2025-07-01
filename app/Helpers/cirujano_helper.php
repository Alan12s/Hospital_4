<?php

use App\Models\CirujanoModel;

if (!function_exists('get_disponibilidad_cirujano')) {
    /**
     * Obtiene la disponibilidad actual de un cirujano
     */
    function get_disponibilidad_cirujano($cirujanoId)
    {
        $model = new CirujanoModel();
        return $model->getDisponibilidadActual($cirujanoId);
    }
}

if (!function_exists('actualizar_disponibilidad_cirujanos')) {
    /**
     * Actualiza la disponibilidad de todos los cirujanos
     */
    function actualizar_disponibilidad_cirujanos()
    {
        $model = new CirujanoModel();
        $model->actualizarDisponibilidadAutomatica();
    }
}