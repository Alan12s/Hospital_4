<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class InicioController extends BaseController
{
    public function __construct()
    {
        // Inicializar helpers necesarios
        helper(['url', 'date']);
        
        // Establecer la zona horaria de Argentina
        date_default_timezone_set('America/Argentina/Buenos_Aires');
    }

    public function index()
    {
        // Verificar si el usuario está logueado
        if (!session()->get('logged_in')) {
            return redirect()->to('/login')->with('error', 'Debes iniciar sesión para acceder');
        }

        // Obtener datos básicos del usuario
        $data = [
            'user_id' => session()->get('user_id'),
            'username' => session()->get('username'),
            'nombre' => session()->get('nombre'),
            'apellidos' => session()->get('apellidos'),
            'email' => session()->get('email'),
            'rol' => session()->get('rol'),
            'page_title' => 'Dashboard - Gestión Quirúrgica'
        ];

        // Inicializar contadores con valores por defecto
        $data['turnos_hoy'] = 0;
        $data['medicos_count'] = 0;
        $data['enfermeros_disponibles'] = 0;
        $data['pacientes_count'] = 0;

        // Intentar cargar modelos si existen
        try {
            // Cargar modelo de turnos si existe
            if (class_exists('\App\Models\TurnoModel')) {
                $turnoModel = new \App\Models\TurnoModel();
                $data['turnos_hoy'] = $turnoModel->countTurnosHoy();
                $data['turnos_programados'] = $turnoModel->getTurnosProgramados();
            } else {
                $data['turnos_programados'] = [];
            }
        } catch (\Exception $e) {
            log_message('error', 'Error al cargar modelo de turnos: ' . $e->getMessage());
            $data['turnos_programados'] = [];
        }

        try {
            // Cargar modelo de médicos si existe
            if (class_exists('\App\Models\MedicoModel')) {
                $medicoModel = new \App\Models\MedicoModel();
                $data['medicos_count'] = $medicoModel->countMedicos();
            }
        } catch (\Exception $e) {
            log_message('error', 'Error al cargar modelo de médicos: ' . $e->getMessage());
        }

        try {
            // Cargar modelo de enfermeros si existe
            if (class_exists('\App\Models\EnfermeroModel')) {
                $enfermeroModel = new \App\Models\EnfermeroModel();
                $data['enfermeros_disponibles'] = $enfermeroModel->countDisponibles();
            }
        } catch (\Exception $e) {
            log_message('error', 'Error al cargar modelo de enfermeros: ' . $e->getMessage());
        }

        try {
            // Cargar modelo de pacientes si existe
            if (class_exists('\App\Models\PacienteModel')) {
                $pacienteModel = new \App\Models\PacienteModel();
                $data['pacientes_count'] = $pacienteModel->countPacientes();
            }
        } catch (\Exception $e) {
            log_message('error', 'Error al cargar modelo de pacientes: ' . $e->getMessage());
        }

        // Recordatorios de ejemplo
        $data['recordatorios'] = [
            [
                'fecha' => date('d/m/Y'),
                'mensaje' => 'Revisar inventario de insumos quirúrgicos',
                'urgente' => false
            ],
            [
                'fecha' => date('d/m/Y', strtotime('+1 day')),
                'mensaje' => 'Reunión con equipo quirúrgico a las 10:00',
                'urgente' => true
            ]
        ];

        // Pasar la fecha y hora formateada a la vista
        $data['fecha_actual'] = $this->formatearFecha(date('Y-m-d'));
        $data['hora_actual'] = date('H:i');

        // Cargar la vista de dashboard
        return view('dashboard/index', $data);
    }

    public function dashboard()
    {
        // Este método es un alias para index()
        return $this->index();
    }

    public function getStats()
    {
        // Verificar si es una petición AJAX
        if (!$this->request->isAJAX()) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // Inicializar respuesta con valores por defecto
        $response = [
            'turnos_hoy' => 0,
            'medicos_count' => 0,
            'enfermeros_disponibles' => 0,
            'pacientes_count' => 0,
            'fecha_actual' => $this->formatearFecha(date('Y-m-d')),
            'hora_actual' => date('H:i')
        ];

        // Obtener estadísticas actualizadas
        try {
            if (class_exists('\App\Models\TurnoModel')) {
                $turnoModel = new \App\Models\TurnoModel();
                $response['turnos_hoy'] = $turnoModel->countTurnosHoy();
            }
        } catch (\Exception $e) {
            log_message('error', 'Error al obtener turnos: ' . $e->getMessage());
        }

        try {
            if (class_exists('\App\Models\MedicoModel')) {
                $medicoModel = new \App\Models\MedicoModel();
                $response['medicos_count'] = $medicoModel->countMedicos();
            }
        } catch (\Exception $e) {
            log_message('error', 'Error al obtener médicos: ' . $e->getMessage());
        }

        try {
            if (class_exists('\App\Models\EnfermeroModel')) {
                $enfermeroModel = new \App\Models\EnfermeroModel();
                $response['enfermeros_disponibles'] = $enfermeroModel->countDisponibles();
            }
        } catch (\Exception $e) {
            log_message('error', 'Error al obtener enfermeros: ' . $e->getMessage());
        }

        try {
            if (class_exists('\App\Models\PacienteModel')) {
                $pacienteModel = new \App\Models\PacienteModel();
                $response['pacientes_count'] = $pacienteModel->countPacientes();
            }
        } catch (\Exception $e) {
            log_message('error', 'Error al obtener pacientes: ' . $e->getMessage());
        }

        return $this->response->setJSON($response);
    }

    /**
     * Formatear fecha en español
     */
    private function formatearFecha($fecha)
    {
        $dias = [
            'Monday' => 'Lunes',
            'Tuesday' => 'Martes',
            'Wednesday' => 'Miércoles',
            'Thursday' => 'Jueves',
            'Friday' => 'Viernes',
            'Saturday' => 'Sábado',
            'Sunday' => 'Domingo'
        ];

        $meses = [
            'January' => 'Enero',
            'February' => 'Febrero',
            'March' => 'Marzo',
            'April' => 'Abril',
            'May' => 'Mayo',
            'June' => 'Junio',
            'July' => 'Julio',
            'August' => 'Agosto',
            'September' => 'Septiembre',
            'October' => 'Octubre',
            'November' => 'Noviembre',
            'December' => 'Diciembre'
        ];

        $timestamp = strtotime($fecha);
        $dia_semana = $dias[date('l', $timestamp)];
        $dia = date('j', $timestamp);
        $mes = $meses[date('F', $timestamp)];
        $año = date('Y', $timestamp);

        return "$dia_semana, $dia de $mes de $año";
    }
}