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
        .btn-group .btn {
            margin: 0 2px;
        }
        .badge-categoria {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
        }
    </style>
</head>
<body>
    
    <div class="app-container">
<<<<<<< HEAD
        <!-- Comentado: Sidebar -->
=======
>>>>>>> 733de39b8424adf4032156dc94a40b6ef5062118
        <!-- Sidebar -->
        <?= view('includes/sidebar') ?>
        
        <div class="main-content">
            <div class="main-wrapper">
                <div class="welcome-section">
                    <div class="d-flex justify-content-between align-items-center">
                        <h1 class="page-title"><i class="bi bi-box-seam me-2"></i><?php echo $title; ?></h1>
                        <a href="<?= base_url('insumos/crear') ?>" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-1"></i> Nuevo Insumo
                        </a>
                    </div>
                </div>

                <?php if(session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <?= session()->getFlashdata('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>

                <?php if(session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <?= session()->getFlashdata('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php endif; ?>

                <!-- DEBUG: Mostrar información de debug temporalmente -->
                <?php if(!empty($insumos) && isset($_GET['debug'])): ?>
                <div class="alert alert-info">
                    <strong>DEBUG:</strong><br>
                    Total insumos: <?= count($insumos) ?><br>
                    Campos del primer insumo: <?= implode(', ', array_keys($insumos[0])) ?><br>
                    Categoría del primer insumo: <?= $insumos[0]['categoria'] ?? 'NO DEFINIDA' ?>
                </div>
                <?php endif; ?>

                <div class="data-card">
                    <div class="data-card-header">
                        <h5 class="mb-0">Listado de Insumos</h5>
                    </div>
                    <div class="data-card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Tipo</th>
                                        <th>Categoría</th>
                                        <th>Cantidad</th>
                                        <th>Ubicación</th>
                                        <th>Vencimiento</th>
                                        <th class="text-end">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(empty($insumos)): ?>
                                    <tr>
                                        <td colspan="7" class="text-center">No hay insumos registrados</td>
                                    </tr>
                                    <?php else: ?>
                                        <?php foreach($insumos as $insumo): ?>
                                        <tr>
                                            <td><?= esc($insumo['nombre'] ?? 'Sin nombre') ?></td>
                                            <td><?= esc($insumo['tipo'] ?? 'Sin tipo') ?></td>
                                            <td>
                                                <?php 
                                                $categoria = $insumo['categoria'] ?? 'descartable';
                                                $badgeClass = $categoria == 'descartable' ? 'bg-warning text-dark' : 'bg-info text-white';
                                                ?>
                                                <span class="badge <?= $badgeClass ?> badge-categoria">
                                                    <?= ucfirst(esc($categoria)) ?>
                                                </span>
                                            </td>
                                            <td><?= esc($insumo['cantidad'] ?? '0') ?></td>
                                            <td><?= esc($insumo['ubicacion'] ?? 'Sin ubicación') ?></td>
                                            <td>
                                                <?php 
                                                $tieneVencimiento = isset($insumo['tiene_vencimiento']) ? $insumo['tiene_vencimiento'] : 0;
                                                $fechaVencimiento = isset($insumo['fecha_vencimiento']) ? $insumo['fecha_vencimiento'] : null;
                                                ?>
                                                <?= $tieneVencimiento && $fechaVencimiento ? esc($fechaVencimiento) : 'No aplica' ?>
                                            </td>
                                            <td class="text-end">
                                                <div class="btn-group">
                                                    <a href="<?= base_url('insumos/view/'.$insumo['id_insumo']) ?>" class="btn btn-sm btn-secondary" title="Ver">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
<<<<<<< HEAD
                                                    <a href="<?= base_url('insumos/editar/'.$insumo['id_insumo']) ?>" class="btn btn-sm btn-primary">
=======
                                                    <a href="<?= base_url('insumos/editar/'.$insumo['id_insumo']) ?>" class="btn btn-sm btn-primary" title="Editar">
>>>>>>> 733de39b8424adf4032156dc94a40b6ef5062118
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    <button type="button" 
                                                       class="btn btn-sm btn-danger" 
                                                       title="Eliminar"
                                                       data-bs-toggle="modal"
                                                       data-bs-target="#modalEliminarInsumo"
                                                       data-id="<?= $insumo['id_insumo'] ?>"
                                                       data-nombre="<?= esc($insumo['nombre']) ?>">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
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
        // Modal de eliminación
        const modalEliminar = document.getElementById('modalEliminarInsumo');
        
        if (modalEliminar) {
            modalEliminar.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const id = button.getAttribute('data-id');
                const nombre = button.getAttribute('data-nombre');
                
                document.getElementById('nombreInsumoModal').textContent = nombre;
                document.getElementById('btnConfirmarEliminar').href = '<?= base_url("insumos/delete") ?>/' + id;
            });
        }

        // Auto-ocultar alertas después de 5 segundos
        const alertas = document.querySelectorAll('.alert');
        alertas.forEach(function(alerta) {
            setTimeout(function() {
                if (alerta && !alerta.classList.contains('alert-info')) { // No ocultar alertas de debug
                    alerta.style.opacity = '0';
                    setTimeout(function() {
                        if (alerta.parentNode) {
                            alerta.parentNode.removeChild(alerta);
                        }
                    }, 300);
                }
            }, 5000);
        });
    });
    </script>
</body>
</html>