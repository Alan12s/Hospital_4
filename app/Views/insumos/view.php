<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?> - Gestión Quirúrgica</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/styles.css'); ?>">
</head>
<body>
    <div class="app-container">
        <!-- Sidebar -->
        <?php $this->load->view('includes/sidebar'); ?>
        
        <div class="main-content">
            <div class="main-wrapper">
                <div class="welcome-section">
                    <div class="d-flex justify-content-between align-items-center">
                        <h1 class="page-title"><i class="bi bi-box-seam me-2"></i><?php echo $title; ?></h1>
                        <a href="<?= site_url('insumos') ?>" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Volver a Insumos
                        </a>
                    </div>
                </div>

                <div class="data-card">
                    <div class="data-card-header">
                        <h5 class="mb-0">Detalles del Insumo</h5>
                    </div>
                    <div class="data-card-body">
                        <div class="row detail-row">
                            <div class="col-md-3 detail-label">Nombre:</div>
                            <div class="col-md-9 detail-value"><?= $insumo['nombre'] ?></div>
                        </div>
                        <div class="row detail-row">
                            <div class="col-md-3 detail-label">Tipo:</div>
                            <div class="col-md-9 detail-value"><?= ucfirst($insumo['tipo']) ?></div>
                        </div>
                        <div class="row detail-row">
                            <div class="col-md-3 detail-label">Cantidad:</div>
                            <div class="col-md-9 detail-value"><?= $insumo['cantidad'] ?></div>
                        </div>
                        <div class="row detail-row">
                            <div class="col-md-3 detail-label">Ubicación:</div>
                            <div class="col-md-9 detail-value"><?= $insumo['ubicacion'] ?></div>
                        </div>
                        <div class="row detail-row">
                            <div class="col-md-3 detail-label">Lote:</div>
                            <div class="col-md-9 detail-value"><?= $insumo['lote'] ?: 'N/A' ?></div>
                        </div>
                        <div class="row detail-row">
                            <div class="col-md-3 detail-label">Tiene vencimiento:</div>
                            <div class="col-md-9 detail-value"><?= $insumo['tiene_vencimiento'] ? 'Sí' : 'No' ?></div>
                        </div>
                        <?php if($insumo['tiene_vencimiento']): ?>
                        <div class="row detail-row">
                            <div class="col-md-3 detail-label">Fecha Vencimiento:</div>
                            <div class="col-md-9 detail-value"><?= $insumo['fecha_vencimiento'] ?></div>
                        </div>
                        <?php endif; ?>

                        <div class="d-flex justify-content-end mt-4">
                            <a href="<?= site_url('insumos/edit/'.$insumo['id_insumo']) ?>" class="btn btn-primary me-2">
                                <i class="bi bi-pencil"></i> Editar
                            </a>
                            <button type="button"
                               class="btn btn-danger"
                               data-bs-toggle="modal"
                               data-bs-target="#modalEliminarInsumo"
                               data-id="<?= $insumo['id_insumo'] ?>"
                               data-nombre="<?= $insumo['nombre'] ?>">
                                <i class="bi bi-trash"></i> Eliminar
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Modal Eliminar Insumo -->
                <div class="modal fade" id="modalEliminarInsumo" tabindex="-1" aria-labelledby="modalEliminarInsumoLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header bg-danger text-white">
                                <h5 class="modal-title" id="modalEliminarInsumoLabel">
                                    <i class="bi bi-exclamation-triangle-fill me-2"></i>Confirmar Eliminación
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p>¿Está seguro que desea eliminar el insumo <strong><span id="nombreInsumoModal"></span></strong>?</p>
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
        const modalEliminar = document.getElementById('modalEliminarInsumo');
        
        if (modalEliminar) {
            modalEliminar.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const id = button.getAttribute('data-id');
                const nombre = button.getAttribute('data-nombre');
                
                document.getElementById('nombreInsumoModal').textContent = nombre;
                document.getElementById('btnConfirmarEliminar').href = '<?= site_url("insumos/delete") ?>/' + id;
            });
        }
    });
    </script>
</body>
</html>