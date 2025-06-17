<?php
namespace App\Models;

use CodeIgniter\Model;

class TurnoModel extends Model
{
    protected $table = 'turnos_quirurgicos';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'fecha', 'hora_inicio', 'hora_finalizacion', 'duracion', 'id_quirofano', 
        'id_cirujano', 'id_cirujano_ayudante', 'id_paciente', 'id_anestesista',
        'id_enfermero', 'id_instrumentador_principal', 'id_instrumentador_circulante',
        'id_tecnico_anestesista', 'tipo_anestesia', 'complicaciones', 'procedimiento',
        'estado', 'observaciones'
    ];
    protected $returnType = 'array';
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    /**
     * Método para contar turnos de hoy
     */
    public function countTurnosHoy()
    {
        return $this->where('fecha', date('Y-m-d'))
                   ->where('estado !=', 'cancelado')
                   ->countAllResults();
    }

    /**
     * Método para contar turnos por estado
     */
    public function countTurnosPorEstado($estado = null)
    {
        $builder = $this->where('fecha', date('Y-m-d'));
        
        if ($estado) {
            $builder->where('estado', $estado);
        }
        
        return $builder->countAllResults();
    }

    /**
     * Método para obtener estadísticas del día
     */
    public function getEstadisticasHoy()
    {
        $hoy = date('Y-m-d');
        
        return [
            'total' => $this->where('fecha', $hoy)->countAllResults(),
            'programados' => $this->where('fecha', $hoy)->where('estado', 'programado')->countAllResults(),
            'en_proceso' => $this->where('fecha', $hoy)->where('estado', 'en_proceso')->countAllResults(),
            'completados' => $this->where('fecha', $hoy)->where('estado', 'completado')->countAllResults(),
            'cancelados' => $this->where('fecha', $hoy)->where('estado', 'cancelado')->countAllResults()
        ];
    }

    // Método principal para crear turno con insumos
    public function crearTurnoConInsumos($datosTurno, $insumos = [])
    {
        $db = db_connect();
        $db->transStart();
        
        try {
            // 1. Validar disponibilidad antes de crear
            if (!$this->checkDisponibilidad(
                $datosTurno['id_quirofano'], 
                $datosTurno['fecha'], 
                $datosTurno['hora_inicio'], 
                $datosTurno['duracion'] ?? 60
            )) {
                throw new \Exception('El quirófano no está disponible en el horario seleccionado');
            }

            // 2. Crear el turno
            $turnoId = $this->insert($datosTurno);
            
            if (!$turnoId) {
                throw new \Exception('Error al crear el turno: ' . implode(', ', $this->errors()));
            }
            
            // 3. Procesar insumos si existen
            if (!empty($insumos)) {
                $this->procesarInsumosTurno($turnoId, $insumos);
            }
            
            $db->transComplete();
            
            if ($db->transStatus() === false) {
                throw new \Exception('Error en la transacción de base de datos');
            }
            
            return $turnoId;
            
        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', 'Error al crear turno: ' . $e->getMessage());
            throw $e;
        }
    }

    // Método auxiliar para procesar insumos
    private function procesarInsumosTurno($turnoId, $insumos)
    {
        foreach ($insumos as $insumoId => $cantidad) {
            if ($cantidad > 0) {
                // Verificar stock disponible
                $insumo = $this->getInsumoById($insumoId);
                if (!$insumo) {
                    throw new \Exception("Insumo con ID $insumoId no encontrado");
                }
                
                if ($insumo['cantidad'] < $cantidad) {
                    throw new \Exception("Stock insuficiente para el insumo {$insumo['nombre']}. Disponible: {$insumo['cantidad']}, Solicitado: $cantidad");
                }
                
                // Reservar insumo (descontar del stock)
                $nuevoStock = $insumo['cantidad'] - $cantidad;
                if (!$this->actualizarStockInsumo($insumoId, $nuevoStock)) {
                    throw new \Exception("Error al actualizar stock del insumo {$insumo['nombre']}");
                }
                
                // Guardar relación turno-insumo
                if (!$this->guardarInsumoTurno($turnoId, $insumoId, $cantidad)) {
                    throw new \Exception("Error al guardar insumo en turno");
                }
            }
        }
    }

    // Método para cancelar turno y devolver insumos
    public function cancelarTurno($turnoId, $motivo = null)
    {
        $db = db_connect();
        $db->transStart();
        
        try {
            // Verificar que el turno existe y puede ser cancelado
            $turno = $this->find($turnoId);
            if (!$turno) {
                throw new \Exception('Turno no encontrado');
            }

            if ($turno['estado'] === 'completado') {
                throw new \Exception('No se puede cancelar un turno completado');
            }

            // Obtener insumos del turno
            $insumosTurno = $this->getInsumosTurno($turnoId);
            
            // Devolver insumos al stock
            foreach ($insumosTurno as $insumoTurno) {
                $insumo = $this->getInsumoById($insumoTurno['id_insumo']);
                if ($insumo) {
                    $nuevoStock = $insumo['cantidad'] + $insumoTurno['cantidad'];
                    $this->actualizarStockInsumo($insumoTurno['id_insumo'], $nuevoStock);
                }
            }
            
            // Eliminar relaciones de insumos
            $this->eliminarInsumosTurno($turnoId);
            
            // Actualizar estado del turno
            $datosActualizacion = [
                'estado' => 'cancelado',
                'updated_at' => date('Y-m-d H:i:s')
            ];
            
            if ($motivo) {
                $datosActualizacion['observaciones'] = $motivo;
            }
            
            $this->update($turnoId, $datosActualizacion);
            
            $db->transComplete();
            
            if ($db->transStatus() === false) {
                throw new \Exception('Error al cancelar turno');
            }
            
            return true;
            
        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', 'Error al cancelar turno: ' . $e->getMessage());
            throw $e;
        }
    }

    // Método para actualizar turno con insumos
    public function actualizarTurnoConInsumos($turnoId, $datosTurno, $insumos = [])
    {
        $db = db_connect();
        $db->transStart();
        
        try {
            // Verificar disponibilidad si se cambió el horario o quirófano
            if (isset($datosTurno['id_quirofano']) || isset($datosTurno['fecha']) || 
                isset($datosTurno['hora_inicio']) || isset($datosTurno['duracion'])) {
                
                $turnoActual = $this->find($turnoId);
                $quirofano = $datosTurno['id_quirofano'] ?? $turnoActual['id_quirofano'];
                $fecha = $datosTurno['fecha'] ?? $turnoActual['fecha'];
                $horaInicio = $datosTurno['hora_inicio'] ?? $turnoActual['hora_inicio'];
                $duracion = $datosTurno['duracion'] ?? $turnoActual['duracion'] ?? 60;

                if (!$this->checkDisponibilidad($quirofano, $fecha, $horaInicio, $duracion, $turnoId)) {
                    throw new \Exception('El quirófano no está disponible en el horario seleccionado');
                }
            }

            // Obtener insumos actuales del turno
            $insumosActuales = $this->getInsumosTurno($turnoId);
            
            // Devolver insumos actuales al stock
            foreach ($insumosActuales as $insumoActual) {
                $insumo = $this->getInsumoById($insumoActual['id_insumo']);
                if ($insumo) {
                    $nuevoStock = $insumo['cantidad'] + $insumoActual['cantidad'];
                    $this->actualizarStockInsumo($insumoActual['id_insumo'], $nuevoStock);
                }
            }
            
            // Eliminar insumos actuales
            $this->eliminarInsumosTurno($turnoId);
            
            // Actualizar datos del turno
            $datosTurno['updated_at'] = date('Y-m-d H:i:s');
            $this->update($turnoId, $datosTurno);
            
            // Procesar nuevos insumos
            if (!empty($insumos)) {
                $this->procesarInsumosTurno($turnoId, $insumos);
            }
            
            $db->transComplete();
            
            if ($db->transStatus() === false) {
                throw new \Exception('Error al actualizar turno');
            }
            
            return true;
            
        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', 'Error al actualizar turno: ' . $e->getMessage());
            throw $e;
        }
    }

    public function listarTurnos()
    {
        return $this->select('
                turnos_quirurgicos.*,
                p.nombre as nombre_paciente,
                p.dni as dni_paciente,
                c.nombre as nombre_medico,
                q.nombre as nombre_quirofano,
                a.nombre as nombre_anestesista,
                e.nombre as nombre_enfermero,
                ep.nombre as nombre_instrumentador_principal,
                ec.nombre as nombre_instrumentador_circulante,
                et.nombre as nombre_tecnico_anestesista,
                ca.nombre as nombre_cirujano_ayudante
            ')
            ->join('pacientes p', 'turnos_quirurgicos.id_paciente = p.id', 'left')
            ->join('cirujanos c', 'turnos_quirurgicos.id_cirujano = c.id', 'left')
            ->join('quirofanos q', 'turnos_quirurgicos.id_quirofano = q.id', 'left')
            ->join('anestesistas a', 'turnos_quirurgicos.id_anestesista = a.id', 'left')
            ->join('enfermeros e', 'turnos_quirurgicos.id_enfermero = e.id', 'left')
            ->join('enfermeros ep', 'turnos_quirurgicos.id_instrumentador_principal = ep.id', 'left')
            ->join('enfermeros ec', 'turnos_quirurgicos.id_instrumentador_circulante = ec.id', 'left')
            ->join('enfermeros et', 'turnos_quirurgicos.id_tecnico_anestesista = et.id', 'left')
            ->join('cirujanos ca', 'turnos_quirurgicos.id_cirujano_ayudante = ca.id', 'left')
            ->orderBy('turnos_quirurgicos.fecha', 'DESC')
            ->orderBy('turnos_quirurgicos.hora_inicio', 'DESC')
            ->findAll();
    }

    public function getMedicos()
    {
        $db = db_connect();
        return $db->table('cirujanos')
                 ->select('id, nombre, dni, id_especialidad')
                 ->where('disponibilidad !=', 'no_disponible')
                 ->orderBy('nombre')
                 ->get()
                 ->getResultArray();
    }

    public function getPacientes()
    {
        $db = db_connect();
        return $db->table('pacientes')
                 ->select('id, nombre, dni, telefono, email')
                 ->orderBy('nombre')
                 ->get()
                 ->getResultArray();
    }

    public function getQuirofanos()
    {
        $db = db_connect();
        return $db->table('quirofanos')
                 ->select('id, nombre, descripcion')
                 ->where('estado', 'activo')
                 ->orderBy('nombre')
                 ->get()
                 ->getResultArray();
    }

    public function getAnestesistas()
    {
        $db = db_connect();
        return $db->table('anestesistas')
                 ->select('id, nombre, especialidad, telefono, email')
                 ->where('disponibilidad !=', 'no_disponible')
                 ->orderBy('nombre')
                 ->get()
                 ->getResultArray();
    }

    public function getEnfermeros()
    {
        $db = db_connect();
        return $db->table('enfermeros')
                 ->select('id, nombre, dni, especialidad, telefono, email')
                 ->where('disponibilidad !=', 'no_disponible')
                 ->orderBy('nombre')
                 ->get()
                 ->getResultArray();
    }

    public function getInstrumentistas()
    {
        $db = db_connect();
        return $db->table('instrumentista')
                 ->select('id, nombre, apellido, especialidad, telefono, email, CONCAT(nombre, " ", apellido) as nombre_completo')
                 ->where('activo', 1)
                 ->orderBy('nombre')
                 ->get()
                 ->getResultArray();
    }

    public function checkDisponibilidad($quirofano_id, $fecha, $hora_inicio, $duracion, $excluir_id = null)
    {
        // Calcular hora de finalización
        $hora_inicio_obj = new \DateTime($hora_inicio);
        $hora_fin_obj = clone $hora_inicio_obj;
        $hora_fin_obj->add(new \DateInterval('PT' . $duracion . 'M'));
        $hora_finalizacion = $hora_fin_obj->format('H:i:s');
        
        $builder = $this->builder();
        $builder->where('id_quirofano', $quirofano_id)
                ->where('fecha', $fecha)
                ->where('estado !=', 'cancelado')
                ->groupStart()
                    ->where('hora_inicio <', $hora_finalizacion)
                    ->where('TIME_ADD(hora_inicio, INTERVAL COALESCE(duracion, 60) MINUTE) >', $hora_inicio)
                ->groupEnd();
        
        if ($excluir_id) {
            $builder->where('id !=', $excluir_id);
        }
        
        return $builder->countAllResults() == 0;
    }

    public function getTurnoCompleto($id)
    {
        return $this->select('
                turnos_quirurgicos.*,
                p.nombre as nombre_paciente,
                p.dni as dni_paciente,
                p.telefono as telefono_paciente,
                p.email as email_paciente,
                p.obra_social as obra_social_paciente,
                c.nombre as nombre_medico,
                c.dni as dni_medico,
                q.nombre as nombre_quirofano,
                q.descripcion as descripcion_quirofano,
                a.nombre as nombre_anestesista,
                a.especialidad as especialidad_anestesista,
                e.nombre as nombre_enfermero,
                e.especialidad as especialidad_enfermero,
                ep.nombre as nombre_instrumentador_principal,
                ec.nombre as nombre_instrumentador_circulante,
                et.nombre as nombre_tecnico_anestesista,
                ca.nombre as nombre_cirujano_ayudante,
                esp.nombre as nombre_especialidad
            ')
            ->join('pacientes p', 'turnos_quirurgicos.id_paciente = p.id', 'left')
            ->join('cirujanos c', 'turnos_quirurgicos.id_cirujano = c.id', 'left')
            ->join('especialidades esp', 'c.id_especialidad = esp.id', 'left')
            ->join('quirofanos q', 'turnos_quirurgicos.id_quirofano = q.id', 'left')
            ->join('anestesistas a', 'turnos_quirurgicos.id_anestesista = a.id', 'left')
            ->join('enfermeros e', 'turnos_quirurgicos.id_enfermero = e.id', 'left')
            ->join('enfermeros ep', 'turnos_quirurgicos.id_instrumentador_principal = ep.id', 'left')
            ->join('enfermeros ec', 'turnos_quirurgicos.id_instrumentador_circulante = ec.id', 'left')
            ->join('enfermeros et', 'turnos_quirurgicos.id_tecnico_anestesista = et.id', 'left')
            ->join('cirujanos ca', 'turnos_quirurgicos.id_cirujano_ayudante = ca.id', 'left')
            ->find($id);
    }

    public function getMedicoById($id)
    {
        $db = db_connect();
        return $db->table('cirujanos')
                 ->select('id, nombre, dni, id_especialidad, telefono, email')
                 ->where('id', $id)
                 ->get()
                 ->getRowArray();
    }

    public function getProcedimientosPorEspecialidad($id_especialidad)
    {
        $db = db_connect();
        return $db->table('procedimientos')
                 ->select('id, nombre')
                 ->where('id_especialidad', $id_especialidad)
                 ->orderBy('nombre')
                 ->get()
                 ->getResultArray();
    }

    public function getEspecialidades()
    {
        $db = db_connect();
        return $db->table('especialidades')
                 ->select('id, nombre, descripcion')
                 ->orderBy('nombre')
                 ->get()
                 ->getResultArray();
    }

    public function getInsumos()
    {
        $db = db_connect();
        return $db->table('insumos')
                 ->select('id_insumo as id, codigo, nombre, tipo, categoria, cantidad, ubicacion')
                 ->where('cantidad >', 0)
                 ->orderBy('nombre')
                 ->get()
                 ->getResultArray();
    }

    public function getInsumosTurno($turno_id)
    {
        $db = db_connect();
        return $db->table('turnos_insumos ti')
                 ->select('ti.*, i.nombre, i.codigo, i.tipo, i.categoria')
                 ->join('insumos i', 'ti.id_insumo = i.id_insumo')
                 ->where('ti.id_turno', $turno_id)
                 ->get()
                 ->getResultArray();
    }

    public function guardarInsumoTurno($turno_id, $insumo_id, $cantidad)
    {
        $db = db_connect();
        
        // Verificar si ya existe el insumo para este turno
        $existente = $db->table('turnos_insumos')
                        ->where('id_turno', $turno_id)
                        ->where('id_insumo', $insumo_id)
                        ->get()
                        ->getRowArray();
        
        if ($existente) {
            // Actualizar cantidad
            return $db->table('turnos_insumos')
                     ->where('id_turno', $turno_id)
                     ->where('id_insumo', $insumo_id)
                     ->update(['cantidad' => $cantidad]);
        } else {
            // Insertar nuevo registro
            return $db->table('turnos_insumos')->insert([
                'id_turno' => $turno_id,
                'id_insumo' => $insumo_id,
                'cantidad' => $cantidad,
                'fecha_registro' => date('Y-m-d H:i:s')
            ]);
        }
    }

    public function eliminarInsumosTurno($turno_id)
    {
        $db = db_connect();
        return $db->table('turnos_insumos')
                 ->where('id_turno', $turno_id)
                 ->delete();
    }

    public function eliminarInsumoTurno($turno_id, $insumo_id)
    {
        $db = db_connect();
        return $db->table('turnos_insumos')
                 ->where('id_turno', $turno_id)
                 ->where('id_insumo', $insumo_id)
                 ->delete();
    }

    public function getTurnosPorFecha($fecha)
    {
        return $this->select('
                turnos_quirurgicos.*,
                p.nombre as nombre_paciente,
                c.nombre as nombre_medico,
                q.nombre as nombre_quirofano
            ')
            ->join('pacientes p', 'turnos_quirurgicos.id_paciente = p.id', 'left')
            ->join('cirujanos c', 'turnos_quirurgicos.id_cirujano = c.id', 'left')
            ->join('quirofanos q', 'turnos_quirurgicos.id_quirofano = q.id', 'left')
            ->where('fecha', $fecha)
            ->where('estado !=', 'cancelado')
            ->orderBy('hora_inicio')
            ->findAll();
    }

    public function getTurnosPorQuirofano($quirofano_id, $fecha = null)
    {
        $builder = $this->select('
                turnos_quirurgicos.*,
                p.nombre as nombre_paciente,
                c.nombre as nombre_medico
            ')
            ->join('pacientes p', 'turnos_quirurgicos.id_paciente = p.id', 'left')
            ->join('cirujanos c', 'turnos_quirurgicos.id_cirujano = c.id', 'left')
            ->where('id_quirofano', $quirofano_id)
            ->where('estado !=', 'cancelado')
            ->orderBy('fecha', 'DESC')
            ->orderBy('hora_inicio');

        if ($fecha) {
            $builder->where('fecha', $fecha);
        }

        return $builder->findAll();
    }

    public function actualizarEstadoTurno($id, $estado)
    {
        return $this->update($id, [
            'estado' => $estado,
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }

    public function iniciarCirugia($id)
    {
        return $this->update($id, [
            'estado' => 'en_proceso',
            'hora_inicio' => date('H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }

    public function finalizarCirugia($id, $observaciones = null)
    {
        $data = [
            'estado' => 'completado',
            'hora_finalizacion' => date('H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        if ($observaciones) {
            $data['observaciones'] = $observaciones;
        }

        return $this->update($id, $data);
    }

    public function getInsumoById($id)
    {
        $db = db_connect();
        return $db->table('insumos')
                 ->select('id_insumo as id, codigo, nombre, tipo, categoria, cantidad, ubicacion')
                 ->where('id_insumo', $id)
                 ->get()
                 ->getRowArray();
    }

    public function actualizarStockInsumo($insumo_id, $nueva_cantidad)
    {
        $db = db_connect();
        return $db->table('insumos')
                 ->where('id_insumo', $insumo_id)
                 ->update(['cantidad' => $nueva_cantidad]);
    }

    public function buscarInsumos($termino)
    {
        $db = db_connect();
        return $db->table('insumos')
                 ->select('id_insumo as id, codigo, nombre, tipo, categoria, cantidad, ubicacion')
                 ->like('nombre', $termino)
                 ->orLike('codigo', $termino)
                 ->orLike('tipo', $termino)
                 ->where('cantidad >', 0)
                 ->orderBy('nombre')
                 ->get()
                 ->getResultArray();
    }

    /**
     * Métodos adicionales útiles
     */
    
    public function getTurnosProximos($dias = 7)
    {
        $fechaInicio = date('Y-m-d');
        $fechaFin = date('Y-m-d', strtotime("+$dias days"));
        
        return $this->select('
                turnos_quirurgicos.*,
                p.nombre as nombre_paciente,
                c.nombre as nombre_medico,
                q.nombre as nombre_quirofano
            ')
            ->join('pacientes p', 'turnos_quirurgicos.id_paciente = p.id', 'left')
            ->join('cirujanos c', 'turnos_quirurgicos.id_cirujano = c.id', 'left')
            ->join('quirofanos q', 'turnos_quirurgicos.id_quirofano = q.id', 'left')
            ->where('fecha >=', $fechaInicio)
            ->where('fecha <=', $fechaFin)
            ->where('estado !=', 'cancelado')
            ->orderBy('fecha')
            ->orderBy('hora_inicio')
            ->findAll();
    }

    public function validarDatosTurno($datos)
    {
        $errores = [];
        
        // Validaciones básicas
        if (empty($datos['fecha'])) {
            $errores[] = 'La fecha es requerida';
        } elseif (strtotime($datos['fecha']) < strtotime(date('Y-m-d'))) {
            $errores[] = 'La fecha no puede ser anterior a hoy';
        }
        
        if (empty($datos['hora_inicio'])) {
            $errores[] = 'La hora de inicio es requerida';
        }
        
        if (empty($datos['id_quirofano'])) {
            $errores[] = 'El quirófano es requerido';
        }
        
        if (empty($datos['id_cirujano'])) {
            $errores[] = 'El cirujano es requerido';
        }
        
        if (empty($datos['id_paciente'])) {
            $errores[] = 'El paciente es requerido';
        }
        
        if (empty($datos['procedimiento'])) {
            $errores[] = 'El procedimiento es requerido';
        }
        
        return $errores;
    }
}