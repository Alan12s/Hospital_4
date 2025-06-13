<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
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
        <?php $this->load->view('includes/sidebar'); ?>
        
        <!-- Contenido principal -->
        <div class="main-content">
            
            <div class="main-wrapper">
                <div class="welcome-section">
                    <div class="d-flex justify-content-between align-items-center">
                        <h1 class="page-title"><i class="bi bi-person-badge me-2"></i><?php echo $titulo; ?></h1>
                        <a href="<?= site_url('anestesistas') ?>" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Volver a Anestesistas
                        </a>
                    </div>
                </div>

                <?php if($this->session->flashdata('mensaje')): ?>
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
                        <h5 class="mb-0">Información del Anestesista</h5>
                    </div>
                    <div class="data-card-body">
                        <div class="row detail-row">
                            <div class="col-md-3 detail-label">ID:</div>
                            <div class="col-md-9 detail-value"><?= $anestesista->id ?></div>
                        </div>
                        <div class="row detail-row">
                            <div class="col-md-3 detail-label">Nombre:</div>
                            <div class="col-md-9 detail-value"><?= $anestesista->nombre ?></div>
                        </div>
                        <div class="row detail-row">
                            <div class="col-md-3 detail-label">Especialidad:</div>
                            <div class="col-md-9 detail-value"><?= $anestesista->especialidad ?></div>
                        </div>
                        <div class="row detail-row">
                            <div class="col-md-3 detail-label">Disponibilidad:</div>
                            <div class="col-md-9 detail-value">
                                <span class="badge bg-<?= 
                                    $anestesista->disponibilidad == 'disponible' ? 'success' : 
                                    ($anestesista->disponibilidad == 'en_cirugia' ? 'warning' : 'danger') 
                                ?>">
                                    <?= ucfirst(str_replace('_', ' ', $anestesista->disponibilidad)) ?>
                                </span>
                            </div>
                        </div>
                        <div class="row detail-row">
                            <div class="col-md-3 detail-label">Teléfono:</div>
                            <div class="col-md-9 detail-value"><?= $anestesista->telefono ?></div>
                        </div>
                        <div class="row detail-row">
                            <div class="col-md-3 detail-label">Email:</div>
                            <div class="col-md-9 detail-value"><?= $anestesista->email ?></div>
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <a href="<?= site_url('anestesistas/editar/'.$anestesista->id) ?>" class="btn btn-primary me-2">
                                <i class="bi bi-pencil"></i> Editar Anestesista
                            </a>
                            <button type="button"
                               class="btn btn-danger"
                               data-bs-toggle="modal"
                               data-bs-target="#modalEliminarAnestesista"
                               data-id="<?= $anestesista->id ?>"
                               data-nombre="<?= $anestesista->nombre ?>">
                                <i class="bi bi-trash"></i> Eliminar Anestesista
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Modal Eliminar Anestesista -->
                <div class="modal fade" id="modalEliminarAnestesista" tabindex="-1" aria-labelledby="modalEliminarAnestesistaLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header bg-danger text-white">
                                <h5 class="modal-title" id="modalEliminarAnestesistaLabel">
                                    <i class="bi bi-exclamation-triangle-fill me-2"></i>Confirmar Eliminación
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p>¿Está seguro que desea eliminar al anestesista <strong><span id="nombreAnestesistaModal"></span></strong>?</p>
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
        const modalEliminar = document.getElementById('modalEliminarAnestesista');
        
        if (modalEliminar) {
            modalEliminar.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const id = button.getAttribute('data-id') || button.getAttribute('data-bs-id');
                const nombre = button.getAttribute('data-nombre') || button.getAttribute('data-bs-nombre');
                
                document.getElementById('nombreAnestesistaModal').textContent = nombre;
                const btnEliminar = document.getElementById('btnConfirmarEliminar');
                btnEliminar.href = '<?= site_url("anestesistas/eliminar") ?>/' + id;
            });
        }
    });
    </script>
</body>
</html>