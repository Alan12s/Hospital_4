<?php // No necesitas el defined('BASEPATH') en CI4 ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><?= esc($titulo ?? 'Detalle Paciente') ?> - Gestión Quirúrgica</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet" />
    <style>
        .app-container {
            min-height: 100vh;
            background-color: #f8f9fa;
        }
        .main-content {
            padding: 20px;
        }
        .welcome-section {
            margin-bottom: 20px;
        }
        .page-title {
            color: #2c3e50;
            font-weight: 600;
        }
        .data-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .data-card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 20px;
        }
        .data-card-body {
            padding: 20px;
        }
        .detail-row {
            margin-bottom: 10px;
        }
        .detail-label {
            font-weight: 600;
            color: #555;
        }
        .btn-group .btn {
            margin-left: 5px;
        }
    </style>
</head>
<body>
    <div class="app-container">


            <div class="main-wrapper">
                <div class="welcome-section">
                    <div class="d-flex justify-content-between align-items-center">
                        <h1 class="page-title">
                            <i class="bi bi-person-badge me-2"></i><?= esc($titulo ?? 'Detalle Paciente') ?>
                        </h1>
                        <a href="<?= site_url('pacientes') ?>" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Volver a Pacientes
                        </a>
                    </div>
                </div>

                <?php if (session()->getFlashdata('mensaje')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= esc(session()->getFlashdata('mensaje')) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= esc(session()->getFlashdata('error')) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                </div>
                <?php endif; ?>

                <div class="data-card">
                    <div class="data-card-header">
                        <h5 class="mb-0">Información del Paciente</h5>
                    </div>
                    <div class="data-card-body">
                        <div class="row detail-row">
                            <div class="col-md-3 detail-label">ID:</div>
                            <div class="col-md-9"><?= esc($paciente->id) ?></div>
                        </div>
                        <div class="row detail-row">
                            <div class="col-md-3 detail-label">Nombre:</div>
                            <div class="col-md-9"><?= esc($paciente->nombre) ?></div>
                        </div>
                        <div class="row detail-row">
                            <div class="col-md-3 detail-label">DNI:</div>
                            <div class="col-md-9"><?= esc($paciente->dni) ?></div>
                        </div>
                        <div class="row detail-row">
                            <div class="col-md-3 detail-label">Teléfono:</div>
                            <div class="col-md-9"><?= esc($paciente->telefono) ?></div>
                        </div>
                        <div class="row detail-row">
                            <div class="col-md-3 detail-label">Dirección:</div>
                            <div class="col-md-9"><?= esc($paciente->direccion) ?></div>
                        </div>
                        <div class="row detail-row">
                            <div class="col-md-3 detail-label">Email:</div>
                            <div class="col-md-9"><?= esc($paciente->email) ?></div>
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <a href="<?= site_url('pacientes/editar/' . $paciente->id) ?>" class="btn btn-primary me-2">
                                <i class="bi bi-pencil"></i> Editar Paciente
                            </a>
                            <button type="button"
                                class="btn btn-danger"
                                data-bs-toggle="modal"
                                data-bs-target="#modalEliminarPaciente"
                                data-id="<?= esc($paciente->id) ?>"
                                data-nombre="<?= esc($paciente->nombre) ?>">
                                <i class="bi bi-trash"></i> Eliminar Paciente
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Modal Eliminar Paciente -->
                <div class="modal fade" id="modalEliminarPaciente" tabindex="-1" aria-labelledby="modalEliminarPacienteLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header bg-danger text-white">
                                <h5 class="modal-title" id="modalEliminarPacienteLabel">
                                    <i class="bi bi-exclamation-triangle-fill me-2"></i>Confirmar Eliminación
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                            </div>
                            <div class="modal-body">
                                <p>¿Está seguro que desea eliminar al paciente <strong><span id="nombrePacienteModal"></span></strong>?</p>
                                <p class="fw-bold text-danger">Esta acción no se puede deshacer.</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                    <i class="bi bi-x-circle me-1"></i> Cancelar
                                </button>
                                <a href="#" id="btnConfirmarEliminar" class="btn btn-danger">
                                    <i class="bi bi-trash me-1"></i> Eliminar
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
        const modalEliminar = document.getElementById('modalEliminarPaciente');

        if (modalEliminar) {
            modalEliminar.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const id = button.getAttribute('data-id');
                const nombre = button.getAttribute('data-nombre');

                document.getElementById('nombrePacienteModal').textContent = nombre;
                document.getElementById('btnConfirmarEliminar').href = '<?= site_url("pacientes/eliminar") ?>/' + id;
            });
        }
    });
    </script>
</body>
</html>
