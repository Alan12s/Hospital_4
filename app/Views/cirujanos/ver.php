<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($titulo) ?> - Gestión Quirúrgica</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/css/styles.css') ?>">
</head>
<body>
    <div class="app-container">
        <!-- Sidebar removido - acceso absoluto -->
        
        <div class="main-content">
            <div class="main-wrapper">
                <div class="welcome-section">
                    <div class="d-flex justify-content-between align-items-center">
                        <h1 class="page-title"><i class="bi bi-person-badge me-2"></i><?= esc($titulo) ?></h1>
                        <a href="<?= site_url('cirujanos') ?>" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Volver a Cirujanos
                        </a>
                    </div>
                </div>

                <?php if (session()->getFlashdata('mensaje')): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <?= esc(session()->getFlashdata('mensaje')) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <?= esc(session()->getFlashdata('error')) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>

                <div class="data-card">
                    <div class="data-card-header">
                        <h5 class="mb-0">Información del Cirujano</h5>
                    </div>
                    <div class="data-card-body">
                        <div class="row detail-row">
                            <div class="col-md-3 detail-label">ID:</div>
                            <div class="col-md-9 detail-value"><?= esc($cirujano->id) ?></div>
                        </div>
                        <div class="row detail-row">
                            <div class="col-md-3 detail-label">Nombre:</div>
                            <div class="col-md-9 detail-value"><?= esc($cirujano->nombre) ?></div>
                        </div>
                        <div class="row detail-row">
                            <div class="col-md-3 detail-label">DNI:</div>
                            <div class="col-md-9 detail-value"><?= esc($cirujano->dni) ?></div>
                        </div>
                        <div class="row detail-row">
                            <div class="col-md-3 detail-label">Especialidad:</div>
                            <div class="col-md-9 detail-value"><?= esc($cirujano->especialidad ?? 'Sin especialidad') ?></div>
                        </div>
                        <div class="row detail-row">
                            <div class="col-md-3 detail-label">Disponibilidad:</div>
                            <div class="col-md-9 detail-value">
                                <span class="badge bg-<?= 
                                    $cirujano->disponibilidad == 'disponible' ? 'success' : 
                                    ($cirujano->disponibilidad == 'en_cirugia' ? 'warning' : 'danger') 
                                ?>">
                                    <?= esc(ucfirst(str_replace('_', ' ', $cirujano->disponibilidad))) ?>
                                </span>
                            </div>
                        </div>
                        <div class="row detail-row">
                            <div class="col-md-3 detail-label">Teléfono:</div>
                            <div class="col-md-9 detail-value"><?= esc($cirujano->telefono) ?></div>
                        </div>
                        <div class="row detail-row">
                            <div class="col-md-3 detail-label">Email:</div>
                            <div class="col-md-9 detail-value"><?= esc($cirujano->email) ?></div>
                        </div>
                        
                        <?php if (isset($cirujano->created_at) && $cirujano->created_at): ?>
                        <div class="row detail-row">
                            <div class="col-md-3 detail-label">Creado el:</div>
                            <div class="col-md-9 detail-value"><?= esc($cirujano->created_at) ?></div>
                        </div>
                        <?php endif; ?>
                        
                        <?php if (isset($cirujano->updated_at) && $cirujano->updated_at): ?>
                        <div class="row detail-row">
                            <div class="col-md-3 detail-label">Actualizado el:</div>
                            <div class="col-md-9 detail-value"><?= esc($cirujano->updated_at) ?></div>
                        </div>
                        <?php endif; ?>

                        <div class="d-flex justify-content-end mt-4">
                            <a href="<?= site_url('cirujanos/editar/'.$cirujano->id) ?>" class="btn btn-primary me-2">
                                <i class="bi bi-pencil"></i> Editar Cirujano
                            </a>
                            <button type="button"
                               class="btn btn-danger"
                               data-bs-toggle="modal"
                               data-bs-target="#modalEliminarCirujano"
                               data-id="<?= esc($cirujano->id) ?>"
                               data-nombre="<?= esc($cirujano->nombre) ?>">
                                <i class="bi bi-trash"></i> Eliminar Cirujano
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Modal Eliminar Cirujano -->
                <div class="modal fade" id="modalEliminarCirujano" tabindex="-1" aria-labelledby="modalEliminarCirujanoLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header bg-danger text-white">
                                <h5 class="modal-title" id="modalEliminarCirujanoLabel">
                                    <i class="bi bi-exclamation-triangle-fill me-2"></i>Confirmar Eliminación
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p>¿Está seguro que desea eliminar al cirujano <strong><span id="nombreCirujanoModal"></span></strong>?</p>
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
        const modalEliminar = document.getElementById('modalEliminarCirujano');
        
        if (modalEliminar) {
            modalEliminar.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const id = button.getAttribute('data-id') || button.getAttribute('data-bs-id');
                const nombre = button.getAttribute('data-nombre') || button.getAttribute('data-bs-nombre');
                
                document.getElementById('nombreCirujanoModal').textContent = nombre;
                const btnEliminar = document.getElementById('btnConfirmarEliminar');
                btnEliminar.href = '<?= site_url("cirujanos/eliminar") ?>/' + id;
            });
        }
    });
    </script>
</body>
</html>