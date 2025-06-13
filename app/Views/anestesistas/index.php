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
                        <h1 class="page-title"><i class="bi bi-people me-2"></i><?php echo $titulo; ?></h1>
                        <div>
                            <a href="<?= site_url('anestesistas/crear') ?>" class="btn btn-primary me-2">
                                <i class="bi bi-plus-circle me-1"></i> Nuevo Anestesista
                            </a>
                            <a href="<?= site_url('anestesistas/disponibles') ?>" class="btn btn-success">
                                <i class="bi bi-check-circle me-1"></i> Ver Disponibles
                            </a>
                        </div>
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
                        <h5 class="mb-0">Listado de Anestesistas</h5>
                    </div>
                    <div class="data-card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Especialidad</th>
                                        <th>Disponibilidad</th>
                                        <th>Teléfono</th>
                                        <th>Email</th>
                                        <th class="text-end">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($anestesistas as $anestesista): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($anestesista->nombre) ?></td>
                                        <td><?= htmlspecialchars($anestesista->especialidad) ?></td>
                                        <td>
                                            <span class="badge bg-<?= 
                                                $anestesista->disponibilidad == 'disponible' ? 'success' : 
                                                ($anestesista->disponibilidad == 'en_cirugia' ? 'warning' : 'danger') 
                                            ?>">
                                                <?= ucfirst(str_replace('_', ' ', $anestesista->disponibilidad)) ?>
                                            </span>
                                        </td>
                                        <td><?= htmlspecialchars($anestesista->telefono) ?></td>
                                        <td><?= htmlspecialchars($anestesista->email) ?></td>
                                        <td class="text-end">
                                            <div class="btn-group">
                                            <a href="<?= site_url('anestesistas/ver/'.$anestesista->id) ?>" class="btn btn-sm btn-secondary">
    <i class="bi bi-eye"></i>
</a>
<a href="<?= site_url('anestesistas/editar/'.$anestesista->id) ?>" class="btn btn-sm btn-primary">
    <i class="bi bi-pencil"></i>
</a>
<button type="button" 
   class="btn btn-sm btn-danger" 
   data-bs-toggle="modal"
   data-bs-target="#modalEliminarAnestesista"
   data-id="<?= $anestesista->id ?>"
   data-nombre="<?= htmlspecialchars($anestesista->nombre) ?>">
    <i class="bi bi-trash"></i>
</button>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Modal para eliminar anestesista -->
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