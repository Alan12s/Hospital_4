<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?> - Gestión Quirúrgica</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/styles.css'); ?>">
</head>
<body>
    <div class="app-container">
        <!-- Sidebar -->
        <?= view('includes/sidebar') ?>
        
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
                            <div class="col-md-3 detail-label">Código:</div>
                            <div class="col-md-9 detail-value">
                                <span class="badge bg-secondary"><?= $insumo['codigo'] ?: 'N/A' ?></span>
                            </div>
                        </div>
                        
                        <div class="row detail-row">
                            <div class="col-md-3 detail-label">Nombre:</div>
                            <div class="col-md-9 detail-value"><strong><?= $insumo['nombre'] ?></strong></div>
                        </div>
                        
                        <div class="row detail-row">
                            <div class="col-md-3 detail-label">Tipo:</div>
                            <div class="col-md-9 detail-value"><?= ucfirst($insumo['tipo']) ?></div>
                        </div>
                        
                        <div class="row detail-row">
                            <div class="col-md-3 detail-label">Categoría:</div>
                            <div class="col-md-9 detail-value">
                                <?php if ($insumo['categoria'] === 'descartable'): ?>
                                    <span class="badge bg-warning text-dark">
                                        <i class="bi bi-trash me-1"></i>Descartable
                                    </span>
                                <?php elseif ($insumo['categoria'] === 'instrumental'): ?>
                                    <span class="badge bg-info text-dark">
                                        <i class="bi bi-tools me-1"></i>Instrumental
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-secondary"><?= ucfirst($insumo['categoria']) ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="row detail-row">
                            <div class="col-md-3 detail-label">Cantidad:</div>
                            <div class="col-md-9 detail-value">
                                <?php 
                                $cantidad = (int)$insumo['cantidad'];
                                if ($cantidad < 5): ?>
                                    <span class="badge bg-danger fs-6">
                                        <i class="bi bi-exclamation-triangle me-1"></i><?= $cantidad ?> (Stock Crítico)
                                    </span>
                                <?php elseif ($cantidad < 10): ?>
                                    <span class="badge bg-warning text-dark fs-6">
                                        <i class="bi bi-exclamation-circle me-1"></i><?= $cantidad ?> (Stock Bajo)
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-success fs-6">
                                        <i class="bi bi-check-circle me-1"></i><?= $cantidad ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="row detail-row">
                            <div class="col-md-3 detail-label">Ubicación:</div>
                            <div class="col-md-9 detail-value">
                                <i class="bi bi-geo-alt me-1"></i><?= $insumo['ubicacion'] ?>
                            </div>
                        </div>
                        
                        <div class="row detail-row">
                            <div class="col-md-3 detail-label">Lote:</div>
                            <div class="col-md-9 detail-value">
                                <code><?= $insumo['lote'] ?: 'N/A' ?></code>
                            </div>
                        </div>
                        
                        <div class="row detail-row">
                            <div class="col-md-3 detail-label">Tiene vencimiento:</div>
                            <div class="col-md-9 detail-value">
                                <?php if ($insumo['tiene_vencimiento']): ?>
                                    <span class="badge bg-success">
                                        <i class="bi bi-check-circle me-1"></i>Sí
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">
                                        <i class="bi bi-x-circle me-1"></i>No
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <?php if($insumo['tiene_vencimiento'] && $insumo['fecha_vencimiento']): ?>
                        <div class="row detail-row">
                            <div class="col-md-3 detail-label">Fecha Vencimiento:</div>
                            <div class="col-md-9 detail-value">
                                <?php 
                                $fechaVenc = new DateTime($insumo['fecha_vencimiento']);
                                $hoy = new DateTime();
                                $diasRestantes = $hoy->diff($fechaVenc)->days;
                                $vencido = $fechaVenc < $hoy;
                                ?>
                                
                                <?php if ($vencido): ?>
                                    <span class="badge bg-danger fs-6">
                                        <i class="bi bi-exclamation-triangle me-1"></i>
                                        <?= $fechaVenc->format('d/m/Y') ?> (VENCIDO)
                                    </span>
                                <?php elseif ($diasRestantes <= 30): ?>
                                    <span class="badge bg-warning text-dark fs-6">
                                        <i class="bi bi-clock me-1"></i>
                                        <?= $fechaVenc->format('d/m/Y') ?> (<?= $diasRestantes ?> días)
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-success fs-6">
                                        <i class="bi bi-calendar-check me-1"></i>
                                        <?= $fechaVenc->format('d/m/Y') ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endif; ?>

                        <div class="d-flex justify-content-end mt-4">
                            <a href="<?= site_url('insumos/editar/'.$insumo['id_insumo']) ?>" class="btn btn-primary me-2">
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
                                <div class="alert alert-warning">
                                    <i class="bi bi-exclamation-triangle me-2"></i>
                                    <strong>Advertencia:</strong> Esta acción no se puede deshacer.
                                </div>
                                <p class="text-muted small">
                                    <i class="bi bi-info-circle me-1"></i>
                                    Si el insumo está siendo utilizado en turnos quirúrgicos, no podrá ser eliminado.
                                </p>
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