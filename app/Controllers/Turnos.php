<?php
namespace App\Controllers;

use App\Models\TurnoModel;
use App\Models\InsumosModel;
use App\Models\ProcedimientoModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class Turnos extends BaseController
{
    protected $turnoModel;
    protected $insumoModel;
    protected $procedimientoModel;
    protected $session;
    protected $validation;

    public function __construct()
    {
        $this->turnoModel = new TurnoModel();
        $this->insumoModel = new InsumosModel();
        $this->procedimientoModel = new ProcedimientoModel();
        $this->session = \Config\Services::session();
        $this->validation = \Config\Services::validation();
        
        if (!$this->session->get('logged_in')) {
            return redirect()->to('/auth/login');
        }
    }

    public function index()
    {
        $filtros = [
            'fecha' => $this->request->getGet('fecha'),
            'estado' => $this->request->getGet('estado'),
            'quirofano' => $this->request->getGet('quirofano'),
            'cirujano' => $this->request->getGet('cirujano')
        ];

        $data = [
            'titulo' => 'Gestión de Turnos Quirúrgicos',
            'turnos' => $this->turnoModel->listarTurnos($filtros),
            'quirofanos' => $this->turnoModel->getQuirofanos(),
            'cirujanos' => $this->turnoModel->getCirujanos(),
            'estados' => [
                'programado' => 'Programado',
                'en_proceso' => 'En Proceso',
                'completado' => 'Completado',
                'cancelado' => 'Cancelado'
            ],
            'filtros' => $filtros
        ];
        
        return view('turnos/index', $data) . view('includes/footer');
    }

    public function crear()
    {
        $data = [
            'titulo' => 'Crear Turno Quirúrgico',
            'cirujanos' => $this->turnoModel->getCirujanos(),
            'pacientes' => $this->turnoModel->getPacientes(),
            'quirofanos' => $this->turnoModel->getQuirofanos(),
            'anestesistas' => $this->turnoModel->getAnestesistas(),
            'enfermeros' => $this->turnoModel->getEnfermeros(),
            'procedimientos' => [],
            'insumos' => $this->insumoModel->where('cantidad >', 0)->findAll(),
            'validation' => $this->validation
        ];
        
        if ($this->request->getMethod() === 'post') {
            $rules = [
                'fecha' => 'required|valid_date',
                'hora_inicio' => 'required',
                'id_quirofano' => 'required|numeric',
                'id_cirujano' => 'required|numeric',
                'id_paciente' => 'required|numeric',
                'procedimiento' => 'required',
                'duracion' => 'required|numeric|greater_than[0]',
                'id_enfermero' => 'required|numeric',
                'id_anestesista' => 'required|numeric'
            ];
            
            if (!$this->validate($rules)) {
                return view('turnos/crear', $data) . view('includes/footer');
            }
            
            $fecha = $this->request->getPost('fecha');
            $horaInicio = $this->request->getPost('hora_inicio');
            $duracion = $this->request->getPost('duracion');
            $quirofanoId = $this->request->getPost('id_quirofano');
            
            if (!$this->turnoModel->checkDisponibilidad($quirofanoId, $fecha, $horaInicio, $duracion)) {
                $this->session->setFlashdata('error', 'El quirófano no está disponible en el horario seleccionado');
                return redirect()->to('/turnos/crear');
            }
            
            // Manejar procedimiento (puede ser seleccionado o custom)
            $procedimiento = $this->request->getPost('procedimiento');
            $procedimientoCustom = null;
            $procedimientoId = null;
            
            if ($procedimiento === 'otro') {
                $procedimientoCustom = $this->request->getPost('procedimiento_custom');
            } else {
                $procedimientoId = $procedimiento;
                $proc = $this->procedimientoModel->find($procedimientoId);
                $procedimientoCustom = $proc ? $proc->nombre : null;
            }
            
            $turnoData = [
                'fecha' => $fecha,
                'hora_inicio' => $horaInicio,
                'id_quirofano' => $quirofanoId,
                'id_cirujano' => $this->request->getPost('id_cirujano'),
                'id_paciente' => $this->request->getPost('id_paciente'),
                'id_procedimiento' => $procedimientoId,
                'procedimiento_custom' => $procedimientoCustom,
                'duracion' => $duracion,
                'estado' => 'programado',
                'id_enfermero' => $this->request->getPost('id_enfermero'),
                'id_anestesista' => $this->request->getPost('id_anestesista'),
                'observaciones' => $this->request->getPost('observaciones')
            ];
            
            // Comenzar transacción
            $db = \Config\Database::connect();
            $db->transStart();
            
            // Guardar el turno
            $turnoId = $this->turnoModel->insert($turnoData);
            
            if (!$turnoId) {
                $db->transRollback();
                $this->session->setFlashdata('error', 'Error al crear el turno');
                return redirect()->to('/turnos/crear');
            }
            
            // Procesar insumos
            $insumosSeleccionados = $this->request->getPost('insumos');
            
            if ($insumosSeleccionados) {
                foreach ($insumosSeleccionados as $insumoId => $cantidad) {
                    $cantidad = (int)$cantidad;
                    if ($cantidad > 0) {
                        $insumo = $this->insumoModel->find($insumoId);
                        
                        if (!$insumo || $insumo['cantidad'] < $cantidad) {
                            $db->transRollback();
                            $nombre = $insumo ? $insumo['nombre'] : 'ID '.$insumoId;
                            $this->session->setFlashdata('error', "No hay stock suficiente del insumo {$nombre}");
                            return redirect()->to('/turnos/crear');
                        }
                        
                        // Registrar insumo en turno
                        $this->turnoModel->guardarInsumoTurno([
                            'id_turno' => $turnoId,
                            'id_insumo' => $insumoId,
                            'cantidad' => $cantidad,
                            'fecha_registro' => date('Y-m-d H:i:s')
                        ]);
                        
                        // Actualizar stock
                        $this->insumoModel->update($insumoId, [
                            'cantidad' => $insumo['cantidad'] - $cantidad
                        ]);
                    }
                }
            }
            
            $db->transComplete();
            
            if ($db->transStatus() === false) {
                $this->session->setFlashdata('error', 'Error al crear el turno');
                return redirect()->to('/turnos/crear');
            }
            
            $this->session->setFlashdata('success', 'Turno creado exitosamente');
            return redirect()->to('/turnos');
        }
        
        return view('turnos/crear', $data) . view('includes/footer');
    }

    public function editar($id = null)
    {
        if ($id === null) {
            throw PageNotFoundException::forPageNotFound();
        }
        
        $turno = $this->turnoModel->getTurnoCompleto($id);
        
        if (empty($turno)) {
            throw PageNotFoundException::forPageNotFound();
        }
        
        // Obtener procedimientos basados en la especialidad del cirujano
        $procedimientos = $this->procedimientoModel->where('id_especialidad', $turno->especialidad_id)->findAll();
        
        $data = [
            'titulo' => 'Editar Turno Quirúrgico',
            'turno' => $turno,
            'cirujanos' => $this->turnoModel->getCirujanos(),
            'pacientes' => $this->turnoModel->getPacientes(),
            'quirofanos' => $this->turnoModel->getQuirofanos(),
            'anestesistas' => $this->turnoModel->getAnestesistas(),
            'enfermeros' => $this->turnoModel->getEnfermeros(),
            'procedimientos' => $procedimientos,
            'insumos' => $this->insumoModel->findAll(),
            'insumos_turno' => $this->turnoModel->getInsumosTurno($id),
            'validation' => $this->validation
        ];
        
        if ($this->request->getMethod() === 'post') {
            $rules = [
                'fecha' => 'required|valid_date',
                'hora_inicio' => 'required',
                'id_quirofano' => 'required|numeric',
                'id_cirujano' => 'required|numeric',
                'id_paciente' => 'required|numeric',
                'procedimiento' => 'required',
                'duracion' => 'required|numeric|greater_than[0]',
                'id_enfermero' => 'required|numeric',
                'id_anestesista' => 'required|numeric',
                'estado' => 'required'
            ];
            
            if (!$this->validate($rules)) {
                return view('turnos/editar', $data) . view('includes/footer');
            }
            
            $fecha = $this->request->getPost('fecha');
            $horaInicio = $this->request->getPost('hora_inicio');
            $duracion = $this->request->getPost('duracion');
            $quirofanoId = $this->request->getPost('id_quirofano');
            
            if (!$this->turnoModel->checkDisponibilidad($quirofanoId, $fecha, $horaInicio, $duracion, $id)) {
                $this->session->setFlashdata('error', 'El quirófano no está disponible en el horario seleccionado');
                return redirect()->to("/turnos/editar/{$id}");
            }
            
            // Manejar procedimiento
            $procedimiento = $this->request->getPost('procedimiento');
            $procedimientoCustom = null;
            $procedimientoId = null;
            
            if ($procedimiento === 'otro') {
                $procedimientoCustom = $this->request->getPost('procedimiento_custom');
            } else {
                $procedimientoId = $procedimiento;
                $proc = $this->procedimientoModel->find($procedimientoId);
                $procedimientoCustom = $proc ? $proc->nombre : null;
            }
            
            $turnoData = [
                'fecha' => $fecha,
                'hora_inicio' => $horaInicio,
                'id_quirofano' => $quirofanoId,
                'id_cirujano' => $this->request->getPost('id_cirujano'),
                'id_paciente' => $this->request->getPost('id_paciente'),
                'id_procedimiento' => $procedimientoId,
                'procedimiento_custom' => $procedimientoCustom,
                'duracion' => $duracion,
                'estado' => $this->request->getPost('estado'),
                'id_enfermero' => $this->request->getPost('id_enfermero'),
                'id_anestesista' => $this->request->getPost('id_anestesista'),
                'observaciones' => $this->request->getPost('observaciones'),
                'complicaciones' => $this->request->getPost('complicaciones')
            ];
            
            // Comenzar transacción
            $db = \Config\Database::connect();
            $db->transStart();
            
            // Actualizar turno
            if (!$this->turnoModel->update($id, $turnoData)) {
                $db->transRollback();
                $this->session->setFlashdata('error', 'Error al actualizar el turno');
                return redirect()->to("/turnos/editar/{$id}");
            }
            
            // Procesar insumos
            $insumosSeleccionados = $this->request->getPost('insumos');
            $insumosActuales = $this->turnoModel->getInsumosTurno($id);
            
            // Primero devolver todos los insumos al stock
            foreach ($insumosActuales as $insumo) {
                $this->insumoModel->set('cantidad', "cantidad + {$insumo->cantidad}", false)
                                 ->where('id', $insumo->id_insumo)
                                 ->update();
            }
            
            // Eliminar asociaciones anteriores
            $this->turnoModel->eliminarInsumosTurno($id);
            
            // Procesar nuevos insumos
            if ($insumosSeleccionados) {
                foreach ($insumosSeleccionados as $insumoId => $cantidad) {
                    $cantidad = (int)$cantidad;
                    if ($cantidad > 0) {
                        $insumo = $this->insumoModel->find($insumoId);
                        
                        if (!$insumo || $insumo['cantidad'] < $cantidad) {
                            $db->transRollback();
                            $nombre = $insumo ? $insumo['nombre'] : 'ID '.$insumoId;
                            $this->session->setFlashdata('error', "No hay stock suficiente del insumo {$nombre}");
                            return redirect()->to("/turnos/editar/{$id}");
                        }
                        
                        // Registrar insumo en turno
                        $this->turnoModel->guardarInsumoTurno([
                            'id_turno' => $id,
                            'id_insumo' => $insumoId,
                            'cantidad' => $cantidad,
                            'fecha_registro' => date('Y-m-d H:i:s')
                        ]);
                        
                        // Actualizar stock
                        $this->insumoModel->update($insumoId, [
                            'cantidad' => $insumo['cantidad'] - $cantidad
                        ]);
                    }
                }
            }
            
            $db->transComplete();
            
            if ($db->transStatus() === false) {
                $this->session->setFlashdata('error', 'Error al actualizar el turno');
                return redirect()->to("/turnos/editar/{$id}");
            }
            
            $this->session->setFlashdata('success', 'Turno actualizado exitosamente');
            return redirect()->to('/turnos');
        }
        
        return view('turnos/editar', $data) . view('includes/footer');
    }

    public function ver($id = null)
    {
        if ($id === null) {
            throw PageNotFoundException::forPageNotFound();
        }
        
        $turno = $this->turnoModel->getTurnoCompleto($id);
        
        if (empty($turno)) {
            throw PageNotFoundException::forPageNotFound();
        }
        
        $data = [
            'titulo' => 'Detalles del Turno Quirúrgico',
            'turno' => $turno,
            'insumos_turno' => $this->turnoModel->getInsumosTurno($id)
        ];
        
        return view('turnos/ver', $data) . view('includes/footer');
    }

    public function eliminar($id = null)
    {
        if ($id === null) {
            throw PageNotFoundException::forPageNotFound();
        }
        
        $turno = $this->turnoModel->find($id);
        
        if (empty($turno)) {
            throw PageNotFoundException::forPageNotFound();
        }
        
        if (in_array($turno->estado, ['en_proceso', 'completado'])) {
            $this->session->setFlashdata('error', 'No se puede eliminar un turno que ya está en proceso o completado');
            return redirect()->to('/turnos');
        }
        
        if ($this->turnoModel->cambiarEstado($id, 'cancelado')) {
            $this->session->setFlashdata('success', 'Turno cancelado exitosamente');
        } else {
            $this->session->setFlashdata('error', 'Error al cancelar el turno');
        }
        
        return redirect()->to('/turnos');
    }

    public function eliminarDefinitivo($id)
    {
        if ($this->turnoModel->eliminarTurno($id)) {
            $this->session->setFlashdata('success', 'Turno eliminado definitivamente');
        } else {
            $this->session->setFlashdata('error', 'Error al eliminar el turno');
        }
        
        return redirect()->to('/turnos');
    }

    // API: Obtener procedimientos por especialidad (para AJAX)
public function getProcedimientos($especialidadId)
{
    if (!is_numeric($especialidadId)) {  // Aquí faltaba el paréntesis de cierre
        return $this->response->setStatusCode(400)
                             ->setJSON(['error' => 'ID de especialidad inválido']);
    }
    
    $procedimientos = $this->procedimientoModel->where('id_especialidad', $especialidadId)
                                              ->orderBy('nombre', 'ASC')
                                              ->findAll();
    
    if (empty($procedimientos)) {
        return $this->response->setStatusCode(404)
                             ->setJSON(['error' => 'No hay procedimientos para esta especialidad']);
    }
    
    return $this->response->setJSON($procedimientos);
}

    // API: Buscar insumos (para AJAX)
    public function buscarInsumos()
    {
        if (!$this->request->isAJAX()) {
            throw PageNotFoundException::forPageNotFound();
        }
        
        $termino = $this->request->getPost('termino');
        if (empty($termino)) {
            return $this->response->setJSON([]);
        }
        
        $termino = esc($termino);
        
        $insumos = $this->insumoModel->like('nombre', $termino)
                                    ->orLike('codigo', $termino)
                                    ->orLike('tipo', $termino)
                                    ->limit(10)
                                    ->findAll();
        
        return $this->response->setJSON($insumos);
    }

    // API: Cambiar estado del turno (para AJAX)
    public function cambiarEstado()
    {
        if (!$this->request->isAJAX()) {
            throw PageNotFoundException::forPageNotFound();
        }
        
        $id = $this->request->getPost('id');
        $estado = $this->request->getPost('estado');
        
        $turno = $this->turnoModel->find($id);
        if (empty($turno)) {
            return $this->response->setStatusCode(404)
                                 ->setJSON(['success' => false, 'message' => 'Turno no encontrado']);
        }
        
        $estadosValidos = ['programado', 'en_proceso', 'completado', 'cancelado'];
        if (!in_array($estado, $estadosValidos)) {
            return $this->response->setStatusCode(400)
                                 ->setJSON(['success' => false, 'message' => 'Estado no válido']);
        }
        
        if ($this->turnoModel->cambiarEstado($id, $estado)) {
            return $this->response->setJSON(['success' => true, 'message' => 'Estado actualizado']);
        }
        
        return $this->response->setStatusCode(500)
                             ->setJSON(['success' => false, 'message' => 'Error al actualizar el estado']);
    }
}