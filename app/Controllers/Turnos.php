<?php

namespace App\Controllers;

use App\Models\TurnoModel;
use App\Models\InsumosModel;
use CodeIgniter\Controller;

class Turnos extends BaseController
{
    protected $turnoModel;
    protected $insumosModel;
    protected $session;
    protected $validation;

    public function __construct()
    {
        $this->turnoModel = new TurnoModel();
        $this->insumosModel = new InsumosModel();
        $this->session = \Config\Services::session();
        $this->validation = \Config\Services::validation();
        
        // Verificar autenticación
        if (!$this->session->get('logged_in')) {
            return redirect()->to('auth/login');
        }
    }

    public function index()
    {
        $data = [
            'titulo' => 'Gestión de Turnos Quirúrgicos',
            'turnos' => $this->turnoModel->listarTurnos()
        ];

        return view('turnos/index', $data);
    }

    public function crear()
    {
        $data = [
            'titulo' => 'Crear Turno Quirúrgico',
            'medicos' => $this->turnoModel->getMedicos(),
            'pacientes' => $this->turnoModel->getPacientes(),
            'quirofanos' => $this->turnoModel->getQuirofanos(),
            'anestesistas' => $this->turnoModel->getAnestesistas(),
            'enfermeros' => $this->turnoModel->getEnfermeros(),
            'procedimientos' => [],
            'insumos' => $this->turnoModel->getInsumos(),
            'validation' => $this->validation
        ];

        if ($this->request->getMethod() === 'POST') {
            $rules = [
                'fecha' => 'required|valid_date',
                'hora_inicio' => 'required',
                'id_quirofano' => 'required|numeric',
                'id_cirujano' => 'required|numeric',
                'id_paciente' => 'required|numeric',
                'procedimiento' => 'required',
                'duracion' => 'required|numeric',
                'id_enfermero' => 'required|numeric',
                'id_anestesista' => 'required|numeric'
            ];

            if (!$this->validate($rules)) {
                $data['validation'] = $this->validation;
                return view('turnos/crear', $data);
            }

            $fecha = $this->request->getPost('fecha');
            $hora_inicio = $this->request->getPost('hora_inicio');
            $duracion = (int)$this->request->getPost('duracion');
            $quirofano_id = $this->request->getPost('id_quirofano');
            $cirujano_id = $this->request->getPost('id_cirujano');

            // Calcular hora de finalización correctamente
            $hora_inicio_obj = new \DateTime($hora_inicio);
            $hora_fin_obj = clone $hora_inicio_obj;
            $hora_fin_obj->add(new \DateInterval('PT' . $duracion . 'M'));
            $hora_fin = $hora_fin_obj->format('H:i:s');

            // Verificar disponibilidad usando el método correcto del modelo
            if (!$this->turnoModel->checkDisponibilidad($quirofano_id, $fecha, $hora_inicio, $duracion)) {
                $this->session->setFlashdata('error', 'El quirófano no está disponible en el horario seleccionado');
                $data['validation'] = $this->validation;
                return view('turnos/crear', $data);
            }

            $procedimiento = $this->request->getPost('procedimiento') == 'otro' 
                ? $this->request->getPost('otro_procedimiento') 
                : $this->request->getPost('procedimiento');

            // Preparar datos del turno
            $turno_data = [
                'fecha' => $fecha,
                'hora_inicio' => $hora_inicio,
                'hora_finalizacion' => $hora_fin,
                'duracion' => $duracion,
                'id_quirofano' => $quirofano_id,
                'id_cirujano' => $cirujano_id,
                'id_paciente' => $this->request->getPost('id_paciente'),
                'procedimiento' => $procedimiento,
                'estado' => 'programado',
                'id_enfermero' => $this->request->getPost('id_enfermero'),
                'id_anestesista' => $this->request->getPost('id_anestesista'),
                'id_cirujano_ayudante' => $this->request->getPost('id_cirujano_ayudante'),
                'id_instrumentador_principal' => $this->request->getPost('id_instrumentador_principal'),
                'id_instrumentador_circulante' => $this->request->getPost('id_instrumentador_circulante'),
                'id_tecnico_anestesista' => $this->request->getPost('id_tecnico_anestesista'),
                'observaciones' => $this->request->getPost('observaciones'),
                'tipo_anestesia' => $this->request->getPost('tipo_anestesia') ?? 'general'
            ];

            // Preparar insumos
            $insumos = [];
            $insumos_seleccionados = $this->request->getPost('insumos');
            
            if ($insumos_seleccionados) {
                foreach ($insumos_seleccionados as $insumo_id => $cantidad) {
                    if ((int)$cantidad > 0) {
                        $insumos[$insumo_id] = (int)$cantidad;
                    }
                }
            }

            try {
                // Usar el método del modelo que maneja transacciones
                $turno_id = $this->turnoModel->crearTurnoConInsumos($turno_data, $insumos);
                
                $this->session->setFlashdata('mensaje', 'Turno creado exitosamente');
                return redirect()->to('turnos');

            } catch (\Exception $e) {
                $this->session->setFlashdata('error', $e->getMessage());
                $data['validation'] = $this->validation;
                return view('turnos/crear', $data);
            }
        }

        return view('turnos/crear', $data);
    }

    public function editar($id = null)
    {
        if ($id === null) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $turno = $this->turnoModel->find($id);
        
        if (empty($turno)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data = [
            'titulo' => 'Editar Turno Quirúrgico',
            'turno' => $turno,
            'medicos' => $this->turnoModel->getMedicos(),
            'pacientes' => $this->turnoModel->getPacientes(),
            'quirofanos' => $this->turnoModel->getQuirofanos(),
            'anestesistas' => $this->turnoModel->getAnestesistas(),
            'enfermeros' => $this->turnoModel->getEnfermeros(),
            'insumos' => $this->turnoModel->getInsumos(),
            'insumos_turno' => $this->turnoModel->getInsumosTurno($id),
            'validation' => $this->validation
        ];

        // Obtener procedimientos basados en la especialidad del médico
        $medico = $this->turnoModel->getMedicoById($turno['id_cirujano']);
        if ($medico) {
            $data['procedimientos'] = $this->turnoModel->getProcedimientosPorEspecialidad($medico['id_especialidad']);
        } else {
            $data['procedimientos'] = [];
        }

        if ($this->request->getMethod() === 'POST') {
            $rules = [
                'fecha' => 'required|valid_date',
                'hora_inicio' => 'required',
                'id_quirofano' => 'required|numeric',
                'id_cirujano' => 'required|numeric',
                'id_paciente' => 'required|numeric',
                'procedimiento' => 'required',
                'duracion' => 'required|numeric',
                'id_enfermero' => 'required|numeric',
                'id_anestesista' => 'required|numeric',
                'estado' => 'required'
            ];

            if (!$this->validate($rules)) {
                $data['validation'] = $this->validation;
                // Preparar insumos seleccionados para la vista
                if (!empty($data['insumos_turno'])) {
                    $data['insumos_seleccionados'] = [];
                    foreach ($data['insumos_turno'] as $insumo) {
                        $data['insumos_seleccionados'][$insumo['id_insumo']] = $insumo['cantidad'];
                    }
                }
                return view('turnos/editar', $data);
            }

            $fecha = $this->request->getPost('fecha');
            $hora_inicio = $this->request->getPost('hora_inicio');
            $duracion = (int)$this->request->getPost('duracion');
            $quirofano_id = $this->request->getPost('id_quirofano');

            // Calcular hora de finalización
            $hora_inicio_obj = new \DateTime($hora_inicio);
            $hora_fin_obj = clone $hora_inicio_obj;
            $hora_fin_obj->add(new \DateInterval('PT' . $duracion . 'M'));
            $hora_fin = $hora_fin_obj->format('H:i:s');

            // Verificar disponibilidad (excluyendo el turno actual)
            if (!$this->turnoModel->checkDisponibilidad($quirofano_id, $fecha, $hora_inicio, $duracion, $id)) {
                $this->session->setFlashdata('error', 'El quirófano no está disponible en el horario seleccionado');
                return redirect()->to('turnos/editar/' . $id);
            }

            $procedimiento = $this->request->getPost('procedimiento') == 'otro' 
                ? $this->request->getPost('otro_procedimiento') 
                : $this->request->getPost('procedimiento');

            $turno_data = [
                'fecha' => $fecha,
                'hora_inicio' => $hora_inicio,
                'hora_finalizacion' => $hora_fin,
                'duracion' => $duracion,
                'id_quirofano' => $quirofano_id,
                'id_cirujano' => $this->request->getPost('id_cirujano'),
                'id_paciente' => $this->request->getPost('id_paciente'),
                'procedimiento' => $procedimiento,
                'estado' => $this->request->getPost('estado'),
                'id_enfermero' => $this->request->getPost('id_enfermero'),
                'id_anestesista' => $this->request->getPost('id_anestesista'),
                'id_cirujano_ayudante' => $this->request->getPost('id_cirujano_ayudante'),
                'id_instrumentador_principal' => $this->request->getPost('id_instrumentador_principal'),
                'id_instrumentador_circulante' => $this->request->getPost('id_instrumentador_circulante'),
                'id_tecnico_anestesista' => $this->request->getPost('id_tecnico_anestesista'),
                'observaciones' => $this->request->getPost('observaciones'),
                'tipo_anestesia' => $this->request->getPost('tipo_anestesia') ?? 'general'
            ];

            // Preparar insumos
            $insumos = [];
            $insumos_seleccionados = $this->request->getPost('insumos');
            
            if ($insumos_seleccionados) {
                foreach ($insumos_seleccionados as $insumo_id => $cantidad) {
                    if ((int)$cantidad > 0) {
                        $insumos[$insumo_id] = (int)$cantidad;
                    }
                }
            }

            try {
                // Usar el método del modelo que maneja transacciones
                $this->turnoModel->actualizarTurnoConInsumos($id, $turno_data, $insumos);
                
                $this->session->setFlashdata('mensaje', 'Turno actualizado exitosamente');
                return redirect()->to('turnos');

            } catch (\Exception $e) {
                $this->session->setFlashdata('error', $e->getMessage());
                return redirect()->to('turnos/editar/' . $id);
            }
        }

        // Preparar insumos seleccionados para la vista
        if (!empty($data['insumos_turno'])) {
            $data['insumos_seleccionados'] = [];
            foreach ($data['insumos_turno'] as $insumo) {
                $data['insumos_seleccionados'][$insumo['id_insumo']] = $insumo['cantidad'];
            }
        }

        return view('turnos/editar', $data);
    }

    public function ver($id = null)
    {
        if ($id === null) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $turno = $this->turnoModel->getTurnoCompleto($id);
        
        if (empty($turno)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data = [
            'titulo' => 'Detalles del Turno Quirúrgico',
            'turno' => $turno,
            'insumos_turno' => $this->turnoModel->getInsumosTurno($id)
        ];

        return view('turnos/ver', $data);
    }

    public function eliminar($id = null)
    {
        if ($id === null) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $turno = $this->turnoModel->find($id);
        
        if (empty($turno)) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        if (in_array($turno['estado'], ['en_proceso', 'completado'])) {
            $this->session->setFlashdata('error', 'No se puede eliminar un turno que ya está en proceso o completado');
            return redirect()->to('turnos');
        }

        try {
            // Usar el método de cancelar del modelo que devuelve insumos
            $this->turnoModel->cancelarTurno($id, 'Cancelado por el usuario');
            $this->session->setFlashdata('mensaje', 'Turno cancelado exitosamente');
        } catch (\Exception $e) {
            $this->session->setFlashdata('error', 'Error al cancelar el turno: ' . $e->getMessage());
        }

        return redirect()->to('turnos');
    }

    public function eliminarDefinitivo($id)
    {
        try {
            // Primero cancelar para devolver insumos, luego eliminar
            $this->turnoModel->cancelarTurno($id, 'Eliminado definitivamente');
            $this->turnoModel->delete($id);
            $this->session->setFlashdata('mensaje', 'Turno eliminado definitivamente');
        } catch (\Exception $e) {
            $this->session->setFlashdata('error', 'Error al eliminar el turno: ' . $e->getMessage());
        }
        
        return redirect()->to('turnos');
    }

    public function cambiarEstado()
    {
        if (!$this->request->isAJAX()) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $id = $this->request->getPost('id');
        $estado = $this->request->getPost('estado');

        $turno = $this->turnoModel->find($id);
        if (empty($turno)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Turno no encontrado']);
        }

        $estados_validos = ['programado', 'en_proceso', 'completado', 'cancelado'];
        if (!in_array($estado, $estados_validos)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Estado no válido']);
        }

        try {
            if ($estado === 'cancelado') {
                // Si se cancela, usar el método que devuelve insumos
                $this->turnoModel->cancelarTurno($id, 'Cancelado desde la interfaz');
            } else {
                $this->turnoModel->update($id, ['estado' => $estado]);
            }
            return $this->response->setJSON(['success' => true, 'message' => 'Estado actualizado']);
        } catch (\Exception $e) {
            return $this->response->setJSON(['success' => false, 'message' => 'Error al actualizar el estado: ' . $e->getMessage()]);
        }
    }

    public function getProcedimientos($id_especialidad)
    {
        if (!is_numeric($id_especialidad) || $id_especialidad <= 0) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'ID de especialidad inválido']);
        }

        $procedimientos = $this->turnoModel->getProcedimientosPorEspecialidad($id_especialidad);

        if (empty($procedimientos)) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'No hay procedimientos para esta especialidad']);
        }

        $response = [];
        foreach ($procedimientos as $proc) {
            $response[] = [
                'id' => $proc['id'],
                'nombre' => $proc['nombre']
            ];
        }

        return $this->response->setJSON($response);
    }

    public function buscarInsumos()
    {
        if (!$this->request->isAJAX()) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $termino = $this->request->getPost('termino');
        if (empty($termino)) {
            return $this->response->setJSON([]);
        }

        $termino = esc($termino);
        $insumos = $this->turnoModel->buscarInsumos($termino);

        return $this->response->setJSON($insumos);
    }

    public function iniciarCirugia($id)
    {
        try {
            $this->turnoModel->iniciarCirugia($id);
            $this->session->setFlashdata('mensaje', 'Cirugía iniciada exitosamente');
        } catch (\Exception $e) {
            $this->session->setFlashdata('error', 'Error al iniciar la cirugía: ' . $e->getMessage());
        }
        
        return redirect()->to('turnos');
    }

    public function finalizarCirugia($id)
    {
        $observaciones = $this->request->getPost('observaciones');
        
        try {
            $this->turnoModel->finalizarCirugia($id, $observaciones);
            $this->session->setFlashdata('mensaje', 'Cirugía finalizada exitosamente');
        } catch (\Exception $e) {
            $this->session->setFlashdata('error', 'Error al finalizar la cirugía: ' . $e->getMessage());
        }
        
        return redirect()->to('turnos');
    }

    public function calendario()
    {
        $fecha = $this->request->getGet('fecha') ?? date('Y-m-d');
        
        $data = [
            'titulo' => 'Calendario de Turnos',
            'fecha' => $fecha,
            'turnos' => $this->turnoModel->getTurnosPorFecha($fecha),
            'quirofanos' => $this->turnoModel->getQuirofanos()
        ];

        return view('turnos/calendario', $data);
    }

    public function reportes()
    {
        $fecha_inicio = $this->request->getGet('fecha_inicio');
        $fecha_fin = $this->request->getGet('fecha_fin');
        
        $data = [
            'titulo' => 'Reportes de Turnos',
            'fecha_inicio' => $fecha_inicio,
            'fecha_fin' => $fecha_fin
        ];

        if ($fecha_inicio && $fecha_fin) {
            $data['turnos'] = $this->turnoModel->getTurnosPorRangoFecha($fecha_inicio, $fecha_fin);
        }

        return view('turnos/reportes', $data);
    }
}