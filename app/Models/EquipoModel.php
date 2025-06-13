<?php
namespace App\Models;

use CodeIgniter\Model;

class EquipoModel extends Model
{
    // Este modelo ahora manejará los miembros del equipo quirúrgico
    // en lugar de equipos físicos
    
    public function getCirujanosDisponibles()
    {
        $db = db_connect();
        return $db->table('cirujanos')
                 ->where('disponibilidad', 'disponible')
                 ->orderBy('nombre')
                 ->get()
                 ->getResultArray();
    }

    public function getAnestesistasDisponibles()
    {
        $db = db_connect();
        return $db->table('anestesistas')
                 ->where('disponibilidad', 'disponible')
                 ->orderBy('nombre')
                 ->get()
                 ->getResultArray();
    }

    public function getEnfermerosDisponibles()
    {
        $db = db_connect();
        return $db->table('enfermeros')
                 ->where('disponibilidad', 'disponible')
                 ->orderBy('nombre')
                 ->get()
                 ->getResultArray();
    }

    public function countDisponibles()
    {
        $db = db_connect();
        
        $cirujanos = $db->table('cirujanos')
                       ->where('disponibilidad', 'disponible')
                       ->countAllResults();
        
        $anestesistas = $db->table('anestesistas')
                          ->where('disponibilidad', 'disponible')
                          ->countAllResults();
        
        $enfermeros = $db->table('enfermeros')
                        ->where('disponibilidad', 'disponible')
                        ->countAllResults();
        
        return $cirujanos + $anestesistas + $enfermeros;
    }
}