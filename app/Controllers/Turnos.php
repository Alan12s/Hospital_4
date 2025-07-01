<?php
namespace App\Controllers;

use App\Models\Turno;
use App\Models\InsumosModel;
use App\Models\InstrumentistaModel;

class Turnos extends BaseController
{
    protected $turnoModel;
    protected $insumoModel;

    public function __construct()
    {
        $this->turnoModel = new Turno();
        $this->insumoModel = new InsumosModel();
        
        if (!session('logged_in')) {
            return redirect()->to('/auth/login');
        }
    }

    public function index()
    {
        $data = [
            'title' => 'Gestión de Turnos Quirúrgicos',
            'turnos' => $this->turnoModel->listarTurnos()
        ];

        return view('turnos/index', $data);
    }

    public function crear()
    {
        $db = \Config\Database::connect();
        
        $data = [
            'titulo' => 'Crear Turno Quirúrgico',
            'cirujanos' => $this->turnoModel->obtenerCirujanos(),
            'pacientes' => $this->turnoModel->obtenerPacientes(),
            'quirofanos' => $this->turnoModel->obtenerQuirofanos(),
            'anestesistas' => $this->turnoModel->obtenerAnestesistas(),
            'tecnicos_anestesistas' => $this->turnoModel->obtenerTecnicosAnestesistas(),
            'instrumentistas' => $db->table('instrumentistas')->where('disponibilidad', 'disponible')->get()->getResult(),
            'insumos' => $this->insumoModel->findAll()
        ];

        return view('turnos/crear', $data);
    }

    public function guardar()
    {
        $rules = [
            'fecha' => 'required|valid_date',
            'hora_inicio' => 'required|regex_match[/^([01][0-9]|2[0-3]):[0-5][0-9]$/]',
            'id_quirofano' => 'required|numeric',
            'id_cirujano' => 'required|numeric',
            'id_cirujano_ayudante' => 'required|numeric',
            'id_paciente' => 'required|numeric',
            'procedimiento' => 'required',
            'duracion' => 'required|numeric|greater_than[14]|less_than[241]',
            'id_tecnico_anestesista' => 'required|numeric',
            'id_anestesista' => 'required|numeric',
            'id_instrumentador_principal' => 'required|numeric',
            'id_instrumentador_circulante' => 'required|numeric',
            'tipo_anestesia' => 'required|in_list[General,Regional,Local,Sedación]',
            'estado' => 'required|in_list[programado,agendada,urgencia]'
        ];

        $messages = [
            'fecha' => [
                'required' => 'La fecha es obligatoria',
                'valid_date' => 'La fecha no es válida'
            ],
            'hora_inicio' => [
                'required' => 'La hora de inicio es obligatoria',
                'regex_match' => 'La hora debe estar en formato HH:MM (24 horas)'
            ],
            'id_quirofano' => [
                'required' => 'El quirófano es obligatorio',
                'numeric' => 'El quirófano no es válido'
            ],
            'id_cirujano' => [
                'required' => 'El cirujano principal es obligatorio',
                'numeric' => 'El cirujano no es válido'
            ],
            'id_cirujano_ayudante' => [
                'required' => 'El cirujano ayudante es obligatorio',
                'numeric' => 'El cirujano ayudante no es válido'
            ],
            'id_paciente' => [
                'required' => 'El paciente es obligatorio',
                'numeric' => 'El paciente no es válido'
            ],
            'procedimiento' => [
                'required' => 'El procedimiento es obligatorio'
            ],
            'duracion' => [
                'required' => 'La duración es obligatoria',
                'numeric' => 'La duración debe ser un número',
                'greater_than' => 'La duración mínima es 15 minutos',
                'less_than' => 'La duración máxima es 240 minutos'
            ],
            'id_tecnico_anestesista' => [
                'required' => 'El técnico anestesista es obligatorio',
                'numeric' => 'El técnico anestesista no es válido'
            ],
            'id_anestesista' => [
                'required' => 'El anestesista es obligatorio',
                'numeric' => 'El anestesista no es válido'
            ],
            'id_instrumentador_principal' => [
                'required' => 'El instrumentador principal es obligatorio',
                'numeric' => 'El instrumentador principal no es válido'
            ],
            'id_instrumentador_circulante' => [
                'required' => 'El instrumentador circulante es obligatorio',
                'numeric' => 'El instrumentador circulante no es válido'
            ],
            'tipo_anestesia' => [
                'required' => 'El tipo de anestesia es obligatorio',
                'in_list' => 'El tipo de anestesia no es válido'
            ],
            'estado' => [
                'required' => 'El estado es obligatorio',
                'in_list' => 'El estado no es válido'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $fecha = $this->request->getPost('fecha');
        $horaInicio = $this->request->getPost('hora_inicio');
        $duracion = $this->request->getPost('duracion');
        $quirofanoId = $this->request->getPost('id_quirofano');
        
        if (!$this->turnoModel->verificarDisponibilidad($quirofanoId, $fecha, $horaInicio, $duracion)) {
            return redirect()->back()->withInput()->with('error', 'El quirófano no está disponible en el horario seleccionado');
        }

        $procedimiento = $this->request->getPost('procedimiento') == 'otro' 
            ? $this->request->getPost('otro_procedimiento') 
            : $this->request->getPost('procedimiento');

        $turnoData = [
            'fecha' => $fecha,
            'hora_inicio' => $horaInicio,
            'hora_finalizacion' => $this->request->getPost('hora_finalizacion'),
            'id_quirofano' => $quirofanoId,
            'id_cirujano' => $this->request->getPost('id_cirujano'),
            'id_cirujano_ayudante' => $this->request->getPost('id_cirujano_ayudante') ?: null,
            'id_paciente' => $this->request->getPost('id_paciente'),
            'procedimiento' => $procedimiento,
            'duracion' => $duracion,
            'estado' => $this->request->getPost('estado'),
            'id_tecnico_anestesista' => $this->request->getPost('id_tecnico_anestesista'),
            'id_anestesista' => $this->request->getPost('id_anestesista'),
            'id_instrumentador_principal' => $this->request->getPost('id_instrumentador_principal'),
            'id_instrumentador_circulante' => $this->request->getPost('id_instrumentador_circulante'),
            'tipo_anestesia' => $this->request->getPost('tipo_anestesia'),
            'observaciones' => $this->request->getPost('observaciones') ?: null,
            'complicaciones' => $this->request->getPost('complicaciones') ?: null,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            $turnoId = $this->turnoModel->insert($turnoData);
            
            if (!$turnoId) {
                throw new \RuntimeException('Error al crear el turno');
            }

            $insumosSeleccionados = $this->request->getPost('insumos');
            
            if ($insumosSeleccionados) {
                foreach ($insumosSeleccionados as $insumoId => $cantidad) {
                    $cantidad = (int)$cantidad;
                    
                    if ($cantidad > 0) {
                        $insumo = $this->insumoModel->find($insumoId);
                        
                        if (!$insumo) {
                            throw new \RuntimeException("Insumo con ID $insumoId no encontrado");
                        }
                        
                        if ($insumo['cantidad'] < $cantidad) {
                            throw new \RuntimeException("No hay stock suficiente del insumo {$insumo['nombre']}");
                        }
                        
                        if (!$this->turnoModel->guardarInsumoTurno($turnoId, $insumoId, $cantidad)) {
                            throw new \RuntimeException("Error al guardar el insumo {$insumo['nombre']}");
                        }
                        
                        $nuevoStock = $insumo['cantidad'] - $cantidad;
                        if (!$this->insumoModel->update($insumoId, ['cantidad' => $nuevoStock])) {
                            throw new \RuntimeException("Error al actualizar el stock del insumo {$insumo['nombre']}");
                        }
                    }
                }
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \RuntimeException('Error en la transacción de base de datos');
            }

            return redirect()->to('/turnos')->with('message', 'Turno creado exitosamente');

        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function editar($id)
    {
        $turno = $this->turnoModel->obtenerTurnoCompleto($id);

        if (!$turno) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $insumosTurno = $this->turnoModel->obtenerInsumosTurno($id);
        $insumosSeleccionados = [];
        
        foreach ($insumosTurno as $insumo) {
            $insumosSeleccionados[$insumo->id_insumo] = $insumo->cantidad;
        }

        $data = [
            'title' => 'Editar Turno Quirúrgico',
            'turno' => $turno,
            'cirujanos' => $this->turnoModel->obtenerCirujanos(),
            'pacientes' => $this->turnoModel->obtenerPacientes(),
            'quirofanos' => $this->turnoModel->obtenerQuirofanos(),
            'anestesistas' => $this->turnoModel->obtenerAnestesistas(),
            'tecnicos_anestesistas' => $this->turnoModel->obtenerTecnicosAnestesistas(),
            'instrumentistas' => $this->turnoModel->obtenerInstrumentistas(),
            'procedimientos' => $this->turnoModel->obtenerProcedimientosPorEspecialidad($turno['id_especialidad']),
            'insumos' => $this->insumoModel->findAll(),
            'insumosSeleccionados' => $insumosSeleccionados
        ];

        return view('turnos/editar', $data);
    }

    public function actualizar($id)
    {
        $turno = $this->turnoModel->obtenerTurno($id);
        
        if (!$turno) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $rules = [
            'fecha' => 'required|valid_date',
            'hora_inicio' => 'required|regex_match[/^([01][0-9]|2[0-3]):[0-5][0-9]$/]',
            'id_quirofano' => 'required|numeric',
            'id_cirujano' => 'required|numeric',
            'id_cirujano_ayudante' => 'required|numeric',
            'id_paciente' => 'required|numeric',
            'procedimiento' => 'required',
            'duracion' => 'required|numeric|greater_than[14]|less_than[241]',
            'id_tecnico_anestesista' => 'required|numeric',
            'id_anestesista' => 'required|numeric',
            'id_instrumentador_principal' => 'required|numeric',
            'id_instrumentador_circulante' => 'required|numeric',
            'tipo_anestesia' => 'required|in_list[General,Regional,Local,Sedación]',
            'estado' => 'required|in_list[programado,agendada,urgencia,en_proceso,completado,cancelado]'
        ];

        $messages = [
            'fecha' => [
                'required' => 'La fecha es obligatoria',
                'valid_date' => 'La fecha no es válida'
            ],
            'hora_inicio' => [
                'required' => 'La hora de inicio es obligatoria',
                'regex_match' => 'La hora debe estar en formato HH:MM (24 horas)'
            ],
            'id_quirofano' => [
                'required' => 'El quirófano es obligatorio',
                'numeric' => 'El quirófano no es válido'
            ],
            'id_cirujano' => [
                'required' => 'El cirujano principal es obligatorio',
                'numeric' => 'El cirujano no es válido'
            ],
            'id_cirujano_ayudante' => [
                'required' => 'El cirujano ayudante es obligatorio',
                'numeric' => 'El cirujano ayudante no es válido'
            ],
            'id_paciente' => [
                'required' => 'El paciente es obligatorio',
                'numeric' => 'El paciente no es válido'
            ],
            'procedimiento' => [
                'required' => 'El procedimiento es obligatorio'
            ],
            'duracion' => [
                'required' => 'La duración es obligatoria',
                'numeric' => 'La duración debe ser un número',
                'greater_than' => 'La duración mínima es 15 minutos',
                'less_than' => 'La duración máxima es 240 minutos'
            ],
            'id_tecnico_anestesista' => [
                'required' => 'El técnico anestesista es obligatorio',
                'numeric' => 'El técnico anestesista no es válido'
            ],
            'id_anestesista' => [
                'required' => 'El anestesista es obligatorio',
                'numeric' => 'El anestesista no es válido'
            ],
            'id_instrumentador_principal' => [
                'required' => 'El instrumentador principal es obligatorio',
                'numeric' => 'El instrumentador principal no es válido'
            ],
            'id_instrumentador_circulante' => [
                'required' => 'El instrumentador circulante es obligatorio',
                'numeric' => 'El instrumentador circulante no es válido'
            ],
            'tipo_anestesia' => [
                'required' => 'El tipo de anestesia es obligatorio',
                'in_list' => 'El tipo de anestesia no es válido'
            ],
            'estado' => [
                'required' => 'El estado es obligatorio',
                'in_list' => 'El estado no es válido'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $fecha = $this->request->getPost('fecha');
        $horaInicio = $this->request->getPost('hora_inicio');
        $duracion = $this->request->getPost('duracion');
        $quirofanoId = $this->request->getPost('id_quirofano');
        
        if (!$this->turnoModel->verificarDisponibilidad($quirofanoId, $fecha, $horaInicio, $duracion, $id)) {
            return redirect()->back()->withInput()->with('error', 'El quirófano no está disponible en el horario seleccionado');
        }

        $procedimiento = $this->request->getPost('procedimiento') == 'otro' 
            ? $this->request->getPost('otro_procedimiento') 
            : $this->request->getPost('procedimiento');

        $turnoData = [
            'fecha' => $fecha,
            'hora_inicio' => $horaInicio,
            'hora_finalizacion' => $this->request->getPost('hora_finalizacion'),
            'id_quirofano' => $quirofanoId,
            'id_cirujano' => $this->request->getPost('id_cirujano'),
            'id_cirujano_ayudante' => $this->request->getPost('id_cirujano_ayudante') ?: null,
            'id_paciente' => $this->request->getPost('id_paciente'),
            'procedimiento' => $procedimiento,
            'duracion' => $duracion,
            'estado' => $this->request->getPost('estado'),
            'id_tecnico_anestesista' => $this->request->getPost('id_tecnico_anestesista'),
            'id_anestesista' => $this->request->getPost('id_anestesista'),
            'id_instrumentador_principal' => $this->request->getPost('id_instrumentador_principal'),
            'id_instrumentador_circulante' => $this->request->getPost('id_instrumentador_circulante'),
            'tipo_anestesia' => $this->request->getPost('tipo_anestesia'),
            'observaciones' => $this->request->getPost('observaciones') ?: null,
            'complicaciones' => $this->request->getPost('complicaciones') ?: null,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            if (!$this->turnoModel->update($id, $turnoData)) {
                throw new \RuntimeException('Error al actualizar el turno');
            }

            $insumosActuales = $this->turnoModel->obtenerInsumosTurno($id);
            
            foreach ($insumosActuales as $insumo) {
                $insumoActual = $this->insumoModel->find($insumo->id_insumo);
                if ($insumoActual) {
                    $nuevoStock = $insumoActual['cantidad'] + $insumo->cantidad;
                    if (!$this->insumoModel->update($insumo->id_insumo, ['cantidad' => $nuevoStock])) {
                        throw new \RuntimeException("Error al devolver el stock del insumo {$insumoActual['nombre']}");
                    }
                }
            }

            if (!$this->turnoModel->eliminarInsumosTurno($id)) {
                throw new \RuntimeException('Error al eliminar los insumos anteriores');
            }

            $insumosSeleccionados = $this->request->getPost('insumos');
            if ($insumosSeleccionados) {
                foreach ($insumosSeleccionados as $insumoId => $cantidad) {
                    $cantidad = (int)$cantidad;
                    
                    if ($cantidad > 0) {
                        $insumo = $this->insumoModel->find($insumoId);
                        
                        if (!$insumo) {
                            throw new \RuntimeException("Insumo con ID $insumoId no encontrado");
                        }
                        
                        if ($insumo['cantidad'] < $cantidad) {
                            throw new \RuntimeException("No hay stock suficiente del insumo {$insumo['nombre']}");
                        }
                        
                        if (!$this->turnoModel->guardarInsumoTurno($id, $insumoId, $cantidad)) {
                            throw new \RuntimeException("Error al guardar el insumo {$insumo['nombre']}");
                        }
                        
                        $nuevoStock = $insumo['cantidad'] - $cantidad;
                        if (!$this->insumoModel->update($insumoId, ['cantidad' => $nuevoStock])) {
                            throw new \RuntimeException("Error al actualizar el stock del insumo {$insumo['nombre']}");
                        }
                    }
                }
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \RuntimeException('Error en la transacción de base de datos');
            }

            return redirect()->to('/turnos')->with('message', 'Turno actualizado exitosamente');

        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->back()->withInput()->with('error', $e->getMessage());
        }
    }

    public function ver($id)
    {
        $turno = $this->turnoModel->obtenerTurnoCompleto($id);

        if (!$turno) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data = [
            'title' => 'Detalles del Turno Quirúrgico',
            'turno' => $turno,
            'insumosTurno' => $this->turnoModel->obtenerInsumosTurno($id)
        ];

        return view('turnos/ver', $data);
    }

    public function eliminar($id)
    {
        $turno = $this->turnoModel->obtenerTurno($id);
        
        if (!$turno) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        if (in_array($turno['estado'], ['en_proceso', 'completado'])) {
            return redirect()->to('/turnos')->with('error', 'No se puede eliminar un turno que ya está en proceso o completado');
        }

        if ($this->turnoModel->update($id, ['estado' => 'cancelado'])) {
            return redirect()->to('/turnos')->with('message', 'Turno cancelado exitosamente');
        }

        return redirect()->to('/turnos')->with('error', 'Error al cancelar el turno');
    }

    public function eliminarDefinitivo($id)
    {
        $turno = $this->turnoModel->obtenerTurno($id);
        
        if (!$turno) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        if ($turno['estado'] != 'cancelado') {
            return redirect()->to('/turnos')->with('error', 'Solo se pueden eliminar definitivamente turnos cancelados');
        }

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            $this->turnoModel->eliminarInsumosTurno($id);
            $this->turnoModel->delete($id);

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \RuntimeException('Error al eliminar el turno');
            }

            return redirect()->to('/turnos')->with('message', 'Turno eliminado definitivamente');

        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->to('/turnos')->with('error', $e->getMessage());
        }
    }

    public function cambiarEstado()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON(['success' => false, 'message' => 'Acceso no permitido']);
        }

        $id = $this->request->getPost('id');
        $estado = $this->request->getPost('estado');

        $turno = $this->turnoModel->obtenerTurno($id);
        if (!$turno) {
            return $this->response->setStatusCode(404)->setJSON(['success' => false, 'message' => 'Turno no encontrado']);
        }

        $estadosValidos = ['programado', 'en_proceso', 'completado', 'cancelado'];
        if (!in_array($estado, $estadosValidos)) {
            return $this->response->setStatusCode(400)->setJSON(['success' => false, 'message' => 'Estado no válido']);
        }

        if ($this->turnoModel->update($id, ['estado' => $estado])) {
            return $this->response->setJSON(['success' => true, 'message' => 'Estado actualizado']);
        }

        return $this->response->setStatusCode(500)->setJSON(['success' => false, 'message' => 'Error al actualizar el estado']);
    }

     public function obtenerProcedimientos($especialidadId)
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON(['error' => 'Acceso no permitido']);
        }

        if (!is_numeric($especialidadId)) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'ID de especialidad inválido']);
        }

        $procedimientos = $this->turnoModel->obtenerProcedimientosPorEspecialidad($especialidadId);

        $response = [];
        foreach ($procedimientos as $proc) {
            $response[] = [
                'id' => $proc->id,
                'nombre' => $proc->nombre
            ];
        }

        return $this->response->setJSON($response);
    }

    public function cancelar($id)
    {
        $turno = $this->turnoModel->obtenerTurno($id);
        
        if (!$turno) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        if (!in_array($turno['estado'], ['programado', 'agendada', 'urgencia'])) {
            return redirect()->to('/turnos')->with('error', 'Solo se pueden cancelar turnos programados, agendados o de urgencia');
        }

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            if (!$this->turnoModel->update($id, ['estado' => 'cancelado'])) {
                throw new \RuntimeException('Error al cancelar el turno');
            }

            $insumosTurno = $this->turnoModel->obtenerInsumosTurno($id);
            foreach ($insumosTurno as $insumo) {
                $insumoActual = $this->insumoModel->find($insumo->id_insumo);
                if ($insumoActual) {
                    $nuevoStock = $insumoActual['cantidad'] + $insumo->cantidad;
                    if (!$this->insumoModel->update($insumo->id_insumo, ['cantidad' => $nuevoStock])) {
                        throw new \RuntimeException("Error al devolver el stock del insumo {$insumoActual['nombre']}");
                    }
                }
            }

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \RuntimeException('Error en la transacción de base de datos');
            }

            return redirect()->to('/turnos')->with('message', 'Turno cancelado exitosamente');

        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->to('/turnos')->with('error', $e->getMessage());
        }
    }

    public function buscarInsumos()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON([]);
        }

        $termino = $this->request->getPost('termino');
        if (empty($termino)) {
            return $this->response->setJSON([]);
        }

        $termino = esc($termino);

        $db = \Config\Database::connect();
        $builder = $db->table('insumos');
        $builder->like('nombre', $termino)
               ->orLike('codigo', $termino)
               ->orLike('tipo', $termino)
               ->limit(10);

        $query = $builder->get();
        
        return $this->response->setJSON($query->getResult());
    }
}