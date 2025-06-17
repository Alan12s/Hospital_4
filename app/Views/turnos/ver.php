<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $titulo; ?> - Gestión Quirúrgica</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/styles.css'); ?>">
</head>
<body>
    <div class="app-container">
        <!-- Sidebar -->
        <?= view('includes/sidebar') ?>
        
        <!-- Contenido principal -->
        <div class="main-content">
            
            <div class="main-wrapper">
                <div class="welcome-section">
                    <div class="d-flex justify-content-between align-items-center">
                        <h1 class="page-title"><i class="bi bi-calendar-plus me-2"></i><?php echo $titulo; ?></h1>
                        <a href="<?= site_url('turnos') ?>" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Volver a Turnos
                        </a>
                    </div>
                </div>

               <?php if(session()->getFlashdata('error')): ?>>
                <div class="alert alert-success alert-dismissible fade show">
                    <?= $this->session->flashdata('mensaje') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>

                <?php if($this->session->flashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <?= $this->session->flashdata('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>

                <div class="data-card">
                    <div class="data-card-header">
                        <h5 class="mb-0">Detalles del Turno Quirúrgico</h5>
                    </div>
                    <div class="data-card-body">
                        <div class="row detail-row">
                            <div class="col-md-3 detail-label">ID:</div>
                            <div class="col-md-9 detail-value"><?= $turno->id ?></div>
                        </div>
                        <div class="row detail-row">
                            <div class="col-md-3 detail-label">Fecha:</div>
                            <div class="col-md-9 detail-value"><?= date('d/m/Y', strtotime($turno->fecha)) ?></div>
                        </div>
                        <div class="row detail-row">
                            <div class="col-md-3 detail-label">Hora de inicio:</div>
                            <div class="col-md-9 detail-value"><?= date('H:i', strtotime($turno->hora_inicio)) ?></div>
                        </div>
                        <div class="row detail-row">
                            <div class="col-md-3 detail-label">Duración:</div>
                            <div class="col-md-9 detail-value"><?= $turno->duracion ?> minutos</div>
                        </div>
                        <div class="row detail-row">
                            <div class="col-md-3 detail-label">Paciente:</div>
                            <div class="col-md-9 detail-value"><?= $turno->nombre_paciente ?></div>
                        </div>
                        <div class="row detail-row">
                            <div class="col-md-3 detail-label">Médico:</div>
                            <div class="col-md-9 detail-value"><?= $turno->nombre_medico ?></div>
                        </div>
                        <div class="row detail-row">
                            <div class="col-md-3 detail-label">Quirófano:</div>
                            <div class="col-md-9 detail-value"><?= $turno->nombre_quirofano ?></div>
                        </div>
                        <div class="row detail-row">
                            <div class="col-md-3 detail-label">Anestesista:</div>
                            <div class="col-md-9 detail-value"><?= $turno->nombre_anestesista ?? 'No asignado' ?></div>
                        </div>
                        <div class="row detail-row">
                            <div class="col-md-3 detail-label">Enfermero/a:</div>
                            <div class="col-md-9 detail-value"><?= $turno->nombre_enfermero ?? 'No asignado' ?></div>
                        </div>
                        <div class="row detail-row">
                            <div class="col-md-3 detail-label">Procedimiento:</div>
                            <div class="col-md-9 detail-value"><?= $turno->procedimiento ?></div>
                        </div>
                        <div class="row detail-row">
                            <div class="col-md-3 detail-label">Estado:</div>
                            <div class="col-md-9 detail-value">
                                <span class="badge 
                                    <?= $turno->estado == 'programado' ? 'bg-primary' : 
                                       ($turno->estado == 'en_proceso' ? 'bg-warning text-dark' : 
                                       ($turno->estado == 'completado' ? 'bg-success' : 'bg-secondary')) ?>">
                                    <?= ucfirst(str_replace('_', ' ', $turno->estado)) ?>
                                </span>
                            </div>
                        </div>
                        <div class="row detail-row">
                            <div class="col-md-3 detail-label">Observaciones:</div>
                            <div class="col-md-9 detail-value"><?= $turno->observaciones ?: 'Ninguna' ?></div>
                        </div>

                         <!-- Sección de Insumos -->
<div class="data-card mt-4">
    <div class="data-card-header">
        <h5 class="mb-0">Insumos Utilizados</h5>
    </div>
    <div class="data-card-body">
        <?php if(empty($insumos_turno)): ?>
            <div class="alert alert-info">
                <i class="bi bi-info-circle me-2"></i>No hay insumos asociados a este turno quirúrgico.
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Código</th>
                            <th>Insumo</th>
                            <th>Tipo</th>
                            <th>Cantidad</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($insumos_turno as $insumo): ?>
                            <tr>
                                <td><?= $insumo->codigo ?></td>
                                <td><?= $insumo->nombre ?></td>
                                <td><?= $insumo->tipo ?></td>
                                <td>
                                    <span class="badge bg-primary"><?= $insumo->cantidad ?></span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

                        <div class="d-flex justify-content-end mt-4">
                            <a href="<?= site_url('turnos/editar/'.$turno->id) ?>" class="btn btn-primary me-2">
                                <i class="bi bi-pencil"></i> Editar Turno
                            </a>
                            
                            <?php if($turno->estado != 'cancelado'): ?>
                            <button type="button"
                               class="btn btn-danger me-2"
                               data-bs-toggle="modal"
                               data-bs-target="#modalCancelarTurno"
                               data-id="<?= $turno->id ?>"
                               data-paciente="<?= $turno->nombre_paciente ?>"
                               <?= in_array($turno->estado, ['en_proceso', 'completado']) ? 'disabled' : '' ?>>
                                <i class="bi bi-x-circle"></i> Cancelar Turno
                            </button>
                            <?php else: ?>
                            <button type="button"
                               class="btn btn-outline-danger"
                               data-bs-toggle="modal"
                               data-bs-target="#modalEliminarTurno"
                               data-id="<?= $turno->id ?>"
                               data-paciente="<?= $turno->nombre_paciente ?>">
                                <i class="bi bi-trash"></i> Eliminar Definitivamente
                            </button>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Modal Cancelar Turno -->
                <div class="modal fade" id="modalCancelarTurno" tabindex="-1" aria-labelledby="modalCancelarTurnoLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header bg-danger text-white">
                                <h5 class="modal-title" id="modalCancelarTurnoLabel">
                                    <i class="bi bi-exclamation-triangle-fill me-2"></i>Confirmar Cancelación
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p>¿Está seguro que desea cancelar el turno del paciente <strong><span id="nombrePacienteCancelarModal"></span></strong>?</p>
                                <p class="fw-bold text-danger">Esta acción cambiará el estado a "cancelado".</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                    <i class="bi bi-x-circle me-1"></i> Cancelar
                                </button>
                                <a href="#" id="btnConfirmarCancelar" class="btn btn-danger">
                                    <i class="bi bi-x-circle me-1"></i> Confirmar Cancelación
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Eliminar Turno Definitivamente -->
                <div class="modal fade" id="modalEliminarTurno" tabindex="-1" aria-labelledby="modalEliminarTurnoLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header bg-danger text-white">
                                <h5 class="modal-title" id="modalEliminarTurnoLabel">
                                    <i class="bi bi-exclamation-triangle-fill me-2"></i>Confirmar Eliminación
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p>¿Está seguro que desea eliminar definitivamente el turno cancelado del paciente <strong><span id="nombrePacienteEliminarModal"></span></strong>?</p>
                                <p class="fw-bold text-danger">Esta acción no se puede deshacer y borrará permanentemente el registro.</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                    <i class="bi bi-x-circle me-1"></i> Cancelar
                                </button>
                                <a href="#" id="btnConfirmarEliminar" class="btn btn-danger">
                                    <i class="bi bi-trash me-1"></i> Eliminar Definitivamente
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Configurar modal de cancelación
        const modalCancelar = document.getElementById('modalCancelarTurno');
        if (modalCancelar) {
            modalCancelar.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const id = button.getAttribute('data-id');
                const paciente = button.getAttribute('data-paciente');
                
                document.getElementById('nombrePacienteCancelarModal').textContent = paciente;
                const btnCancelar = document.getElementById('btnConfirmarCancelar');
                btnCancelar.href = '<?= site_url("turnos/cancelar") ?>/' + id;
            });
        }

        // Configurar modal de eliminación definitiva
        const modalEliminar = document.getElementById('modalEliminarTurno');
        if (modalEliminar) {
            modalEliminar.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const id = button.getAttribute('data-id');
                const paciente = button.getAttribute('data-paciente');
                
                document.getElementById('nombrePacienteEliminarModal').textContent = paciente;
                const btnEliminar = document.getElementById('btnConfirmarEliminar');
                btnEliminar.href = '<?= site_url("turnos/eliminar_definitivo") ?>/' + id;
            });
        }
    });
    </script>
</body>
</html>