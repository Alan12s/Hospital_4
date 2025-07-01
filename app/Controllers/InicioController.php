<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class InicioController extends BaseController
{
    public function __construct()
    {
        helper(['url', 'date', 'form']);
        date_default_timezone_set('America/Argentina/Buenos_Aires');
    }

    public function index()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/login')->with('error', 'Debes iniciar sesión para acceder');
        }

        $data = [
            'user_id' => session()->get('user_id'),
            'username' => session()->get('username'),
            'nombre' => session()->get('nombre'),
            'apellidos' => session()->get('apellidos'),
            'email' => session()->get('email'),
            'rol' => session()->get('rol'),
            'page_title' => 'Dashboard - Sistema de Gestión Hospitalaria'
        ];

        // Obtener estadísticas reales de la base de datos
        $data = array_merge($data, $this->getRealStatistics());
        
        // Datos adicionales
        $data['fecha_actual'] = $this->formatearFecha(date('Y-m-d'));
        $data['hora_actual'] = date('H:i:s');
        $data['clima'] = $this->getClimaInfo();
        $data['recordatorios'] = $this->getRecordatoriosPorRol($data['rol']);
        $data['actividad_reciente'] = $this->getActividadReciente();
        $data['estadisticas_avanzadas'] = $this->getEstadisticasAvanzadas();

        return view('dashboard/index', $data);
    }

    public function getStats()
    {
        if (!$this->request->isAJAX()) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        // Obtener estadísticas actualizadas
        $stats = $this->getRealStatistics();

        $response = [
            'turnos_hoy' => $stats['turnos_hoy'],
            'cirujanos_count' => $stats['cirujanos_count'],
            'enfermeros_disponibles' => $stats['enfermeros_disponibles'],
            'pacientes_count' => $stats['pacientes_count'],
            'insumos_bajo_stock' => $stats['insumos_bajo_stock'],
            'fecha_actual' => $this->formatearFecha(date('Y-m-d')),
            'hora_actual' => date('H:i:s'),
            'status' => 'success'
        ];

        return $this->response->setJSON($response);
    }

    public function getWeeklyStats()
    {
        if (!$this->request->isAJAX()) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        try {
            if (class_exists('\App\Models\TurnoModel')) {
                $turnoModel = new \App\Models\Turno();
                $data = $turnoModel->getCirugiasSemana();
            } else {
                $data = $this->getMockWeeklyData();
            }
        } catch (\Exception $e) {
            log_message('error', 'Error al obtener estadísticas semanales: ' . $e->getMessage());
            $data = $this->getMockWeeklyData();
        }

        return $this->response->setJSON($data);
    }

    public function getSpecialtyStats()
    {
        if (!$this->request->isAJAX()) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        try {
            if (class_exists('\App\Models\TurnoModel')) {
                $turnoModel = new \App\Models\Turno();
                $data = $turnoModel->getEspecialidadStats();
            } else {
                $data = [
                    'labels' => ['Cardiovascular', 'Neurología', 'Ortopedia', 'General'],
                    'data' => [30, 25, 25, 20]
                ];
            }
        } catch (\Exception $e) {
            log_message('error', 'Error al obtener estadísticas por especialidad: ' . $e->getMessage());
            $data = [
                'labels' => ['Cardiovascular', 'Neurología', 'Ortopedia', 'General'],
                'data' => [30, 25, 25, 20]
            ];
        }

        return $this->response->setJSON($data);
    }

    public function getAnestesistasCount()
    {
        if (!$this->request->isAJAX()) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        try {
            $anestesistasModel = new \App\Models\AnestesistasModel();
            $count = $anestesistasModel->countAll();

            return $this->response->setJSON([
                'status' => 'success',
                'count' => $count
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error al contar anestesistas: ' . $e->getMessage());
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Error al obtener conteo de anestesistas',
                'count' => 0
            ]);
        }
    }

    public function getInstrumentistasCount()
    {
        if (!$this->request->isAJAX()) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        try {
            $instrumentistasModel = new \App\Models\InstrumentistasModel();
            $count = $instrumentistasModel->countAll();

            return $this->response->setJSON([
                'status' => 'success',
                'count' => $count
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Error al contar instrumentistas: ' . $e->getMessage());
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Error al obtener conteo de instrumentistas',
                'count' => 0
            ]);
        }
    }

   public function getUpcomingSurgeries()
{
    // Desactivar temporalmente la verificación AJAX para pruebas
    // if (!$this->request->isAJAX()) {
    //     return $this->response->setJSON([
    //         'status' => 'error',
    //         'message' => 'Solo se permiten peticiones AJAX'
    //     ])->setStatusCode(403);
    // }

    try {
        $db = \Config\Database::connect();
        $turnoModel = new \App\Models\Turno($db);
        
        $builder = $turnoModel->builder();
        $surgeries = $builder
            ->select('turnos_quirurgicos.*, pacientes.nombre as paciente_nombre, cirujanos.nombre as cirujano_nombre, procedimiento, estado')
            ->join('pacientes', 'pacientes.id = turnos_quirurgicos.id_paciente')
            ->join('cirujanos', 'cirujanos.id = turnos_quirurgicos.id_cirujano')
            ->where('fecha >=', date('Y-m-d'))
            ->orderBy('fecha, hora_inicio', 'ASC')
            ->get()
            ->getResult();

        if (!$surgeries) {
            return $this->response->setJSON([
                'status' => 'success',
                'surgeries' => [],
                'message' => 'No se encontraron cirugías programadas'
            ]);
        }

        $formattedSurgeries = [];
        foreach ($surgeries as $surgery) {
            $formattedSurgeries[] = [
                'id' => $surgery->id,
                'title' => $surgery->paciente_nombre . ' - ' . $surgery->procedimiento,
                'date' => $this->formatearFecha($surgery->fecha),
                'time' => date('H:i', strtotime($surgery->hora_inicio)),
                'surgeon' => $surgery->cirujano_nombre,
                'status' => $surgery->estado
            ];
        }

        return $this->response->setJSON([
            'status' => 'success',
            'surgeries' => $formattedSurgeries
        ]);

    } catch (\Exception $e) {
        log_message('error', 'Error en getUpcomingSurgeries: ' . $e->getMessage());
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Error interno del servidor',
            'debug' => (ENVIRONMENT === 'development') ? [
                'message' => $e->getMessage(),
                'trace' => $e->getTrace()
            ] : null
        ])->setStatusCode(500);
    }
}

   public function getCriticalSupplies()
{
    if (!$this->request->isAJAX()) {
        return $this->response->setStatusCode(403)->setJSON([
            'status' => 'error',
            'message' => 'Acceso no autorizado'
        ]);
    }

    try {
        $db = db_connect();
        $builder = $db->table('insumos');
        
        $supplies = $builder
            ->select('id_insumo, codigo, nombre, cantidad, tipo, ubicacion')
            ->where('cantidad <', 50) // Ajusta este valor según tu criterio
            ->orderBy('cantidad', 'ASC')
            ->get()
            ->getResult();

        return $this->response->setJSON([
            'status' => 'success',
            'supplies' => $supplies
        ]);

    } catch (\Exception $e) {
        log_message('error', 'Error en getCriticalSupplies: '.$e->getMessage());
        return $this->response->setStatusCode(500)->setJSON([
            'status' => 'error',
            'message' => 'Error al obtener insumos críticos'
        ]);
    }
}

    public function getNotifications()
    {
        if (!$this->request->isAJAX()) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $notifications = [];

        // Verificar si hay cirugías programadas para hoy
        try {
            if (class_exists('\App\Models\Turno')) {
                $turnoModel = new \App\Models\Turno();
                $cirugiasHoy = $turnoModel->countTurnosHoy();
                
                if ($cirugiasHoy > 0) {
                    $notifications[] = [
                        'type' => 'info',
                        'message' => "Tienes $cirugiasHoy cirugías programadas para hoy",
                        'icon' => 'bx-calendar-check',
                        'timestamp' => time()
                    ];
                }
            }
        } catch (\Exception $e) {
            log_message('error', 'Error al obtener notificaciones de turnos: ' . $e->getMessage());
        }

        // Verificar insumos bajos
        try {
            if (class_exists('\App\Models\InsumoModel')) {
                $insumoModel = new \App\Models\InsumosModel();
                $insumosBajos = $insumoModel->countBajoStock();
                
                if ($insumosBajos > 0) {
                    $notifications[] = [
                        'type' => 'warning',
                        'message' => "$insumosBajos insumos con stock bajo",
                        'icon' => 'bx-package',
                        'timestamp' => time()
                    ];
                }
            }
        } catch (\Exception $e) {
            log_message('error', 'Error al obtener notificaciones de insumos: ' . $e->getMessage());
        }

        // Notificaciones específicas por rol
        $rol = session()->get('rol');
        
        if ($rol === 'cirujano') {
            $notifications[] = [
                'type' => 'primary',
                'message' => 'Revisa tus cirugías programadas para esta semana',
                'icon' => 'bx-clipboard',
                'timestamp' => time()
            ];
        } elseif ($rol === 'enfermero') {
            $notifications[] = [
                'type' => 'info',
                'message' => 'Verifica tus turnos asignados',
                'icon' => 'bx-time-five',
                'timestamp' => time()
            ];
        }

        return $this->response->setJSON([
            'status' => 'success',
            'notifications' => $notifications
        ]);
    }

    private function getRealStatistics()
{
    $stats = [
        'turnos_hoy' => 0,
        'cirujanos_count' => 0,
       'enfermeros_count' => 0,
        'anestesistas_count' => 0,  // Nuevo campo
        'instrumentistas_count' => 0, // Nuevo campo
        'pacientes_count' => 0,
        'insumos_bajo_stock' => 0,
        'turnos_programados' => [],
        'cirugias_semana' => $this->getMockWeeklyData()
    ];

        try {
            // Modelo de Turnos
            if (class_exists('\App\Models\Turno')) {
                $turnoModel = new \App\Models\Turno();
                $stats['turnos_hoy'] = $turnoModel->countTurnosHoy();
                $stats['turnos_programados'] = $turnoModel->getTurnosProgramados(5);
                
                // Obtener datos reales para el gráfico semanal
                $semanaData = $turnoModel->getCirugiasSemana();
                if (!empty($semanaData)) {
                    $stats['cirugias_semana'] = $semanaData;
                }
            }
        } catch (\Exception $e) {
            log_message('error', 'Error al obtener estadísticas de turnos: ' . $e->getMessage());
        }

        try {
            // Modelo de Cirujanos
            if (class_exists('\App\Models\CirujanoModel')) {
                $cirujanoModel = new \App\Models\CirujanoModel();
                $stats['cirujanos_count'] = $cirujanoModel->countCirujanos();
            } elseif (class_exists('\App\Models\MedicoModel')) {
                $medicoModel = new \App\Models\MedicoModel();
                $stats['cirujanos_count'] = $medicoModel->countMedicos();
            }
        } catch (\Exception $e) {
            log_message('error', 'Error al obtener estadísticas de cirujanos: ' . $e->getMessage());
        }

         try {
        // Modelo de Enfermeros
        if (class_exists('\App\Models\EnfermerosModel')) {
            $enfermerosModel = new \App\Models\EnfermerosModel();
            $stats['enfermeros_count'] = $enfermerosModel->countAll(); // Contar todos, no solo disponibles
        }
    } catch (\Exception $e) {
        log_message('error', 'Error al obtener estadísticas de enfermeros: '.$e->getMessage());
    }

        try {
            // Modelo de Pacientes
            if (class_exists('\App\Models\PacienteModel')) {
                $pacienteModel = new \App\Models\PacienteModel();
                $stats['pacientes_count'] = $pacienteModel->countPacientes();
            }
        } catch (\Exception $e) {
            log_message('error', 'Error al obtener estadísticas de pacientes: ' . $e->getMessage());
        }

        try {
            // Modelo de Insumos
            if (class_exists('\App\Models\InsumoModel')) {
                $insumoModel = new \App\Models\InsumosModel();
                $stats['insumos_bajo_stock'] = $insumoModel->countBajoStock();
            }
        } catch (\Exception $e) {
            log_message('error', 'Error al obtener estadísticas de insumos: ' . $e->getMessage());
        }
        
    try {
        // Modelo de Anestesistas
        if (class_exists('\App\Models\AnestesistasModel')) {
            $anestesistasModel = new \App\Models\AnestesistasModel();
            $stats['anestesistas_count'] = $anestesistasModel->countAll();
        }
    } catch (\Exception $e) {
        log_message('error', 'Error al obtener estadísticas de anestesistas: ' . $e->getMessage());
    }

    try {
        // Modelo de Instrumentistas
        if (class_exists('\App\Models\InstrumentistasModel')) {
            $instrumentistasModel = new \App\Models\InstrumentistasModel();
            $stats['instrumentistas_count'] = $instrumentistasModel->countAll();
        }
    } catch (\Exception $e) {
        log_message('error', 'Error al obtener estadísticas de instrumentistas: ' . $e->getMessage());
    }

    return $stats;
}


    private function getMockWeeklyData()
    {
        return [
            'labels' => ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'],
            'data' => [12, 19, 8, 15, 22, 13, 7]
        ];
    }

    private function getEstadisticasAvanzadas()
    {
        return [
            'ocupacion_quirofanos' => 85,
            'tiempo_promedio_cirugia' => 120, // minutos
            'satisfaccion_pacientes' => 92,
            'eficiencia_personal' => 88
        ];
    }

    private function getActividadReciente()
    {
        return [
            [
                'tipo' => 'cirugia_completada',
                'mensaje' => 'Cirugía de apendicectomía completada exitosamente',
                'usuario' => 'Dr. ' . session()->get('nombre'),
                'tiempo' => 'Hace 2 horas',
                'icono' => 'bx-check-circle',
                'color' => 'success'
            ],
            [
                'tipo' => 'paciente_registrado',
                'mensaje' => 'Nuevo paciente registrado en el sistema',
                'usuario' => session()->get('nombre'),
                'tiempo' => 'Hace 4 horas',
                'icono' => 'bx-user-plus',
                'color' => 'info'
            ],
            [
                'tipo' => 'insumo_bajo',
                'mensaje' => 'Stock bajo detectado en insumos quirúrgicos',
                'usuario' => 'Sistema',
                'tiempo' => 'Hace 6 horas',
                'icono' => 'bx-package',
                'color' => 'warning'
            ],
            [
                'tipo' => 'mantenimiento',
                'mensaje' => 'Mantenimiento programado de equipos completado',
                'usuario' => 'Técnico',
                'tiempo' => 'Hace 8 horas',
                'icono' => 'bx-wrench',
                'color' => 'secondary'
            ]
        ];
    }

    private function getRecordatoriosPorRol($rol)
    {
        $recordatorios_base = [
            [
                'fecha' => date('d/m/Y'),
                'mensaje' => 'Revisar inventario de insumos quirúrgicos',
                'urgente' => false,
                'tipo' => 'inventario'
            ]
        ];

        switch ($rol) {
            case 'administrador':
                $recordatorios_base[] = [
                    'fecha' => date('d/m/Y', strtotime('+1 day')),
                    'mensaje' => 'Reunión administrativa programada a las 10:00',
                    'urgente' => true,
                    'tipo' => 'reunion'
                ];
                $recordatorios_base[] = [
                    'fecha' => date('d/m/Y', strtotime('+2 days')),
                    'mensaje' => 'Revisión mensual de presupuestos',
                    'urgente' => false,
                    'tipo' => 'revision'
                ];
                break;

            case 'cirujano':
                $recordatorios_base[] = [
                    'fecha' => date('d/m/Y', strtotime('+1 day')),
                    'mensaje' => 'Cirugía cardíaca programada para las 08:00',
                    'urgente' => true,
                    'tipo' => 'cirugia'
                ];
                $recordatorios_base[] = [
                    'fecha' => date('d/m/Y'),
                    'mensaje' => 'Revisar historiales pre-operatorios',
                    'urgente' => false,
                    'tipo' => 'revision'
                ];
                break;

            case 'enfermero':
                $recordatorios_base[] = [
                    'fecha' => date('d/m/Y'),
                    'mensaje' => 'Verificar suministros en sala de recuperación',
                    'urgente' => false,
                    'tipo' => 'verificacion'
                ];
                break;
        }

        return $recordatorios_base;
    }

    private function getClimaInfo()
    {
        // Simular diferentes condiciones climáticas basadas en la hora del día
        $hora_actual = date('H');
        
        if ($hora_actual >= 6 && $hora_actual < 12) {
            // Mañana
            return [
                'icono' => '🌤️',
                'temperatura' => rand(18, 22),
                'descripcion' => 'Parcialmente nublado',
                'ciudad' => 'San Juan, Argentina',
                'humedad' => rand(50, 70),
                'viento' => rand(5, 15),
                'nubosidad' => rand(20, 50)
            ];
        } elseif ($hora_actual >= 12 && $hora_actual < 18) {
            // Tarde
            return [
                'icono' => '☀️',
                'temperatura' => rand(25, 30),
                'descripcion' => 'Soleado',
                'ciudad' => 'San Juan, Argentina',
                'humedad' => rand(30, 50),
                'viento' => rand(10, 20),
                'nubosidad' => rand(10, 30)
            ];
        } elseif ($hora_actual >= 18 && $hora_actual < 22) {
            // Tarde-noche
            return [
                'icono' => '🌇',
                'temperatura' => rand(20, 25),
                'descripcion' => 'Despejado',
                'ciudad' => 'San Juan, Argentina',
                'humedad' => rand(40, 60),
                'viento' => rand(5, 10),
                'nubosidad' => rand(10, 40)
            ];
        } else {
            // Noche/madrugada
            return [
                'icono' => '🌙',
                'temperatura' => rand(15, 20),
                'descripcion' => 'Noche clara',
                'ciudad' => 'San Juan, Argentina',
                'humedad' => rand(60, 80),
                'viento' => rand(0, 5),
                'nubosidad' => rand(0, 20)
            ];
        }
    }

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

    private function registrarActividad($tipo, $mensaje)
    {
        try {
            if (class_exists('\App\Models\ActividadModel')) {
                $actividadModel = new \App\Models\ActividadModel();
                
                $actividadModel->insert([
                    'usuario_id' => session()->get('user_id'),
                    'tipo' => $tipo,
                    'mensaje' => $mensaje,
                    'fecha_hora' => date('Y-m-d H:i:s')
                ]);
            }
        } catch (\Exception $e) {
            log_message('error', 'Error al registrar actividad: ' . $e->getMessage());
        }
    }
}