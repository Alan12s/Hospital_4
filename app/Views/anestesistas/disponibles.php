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
                        <h1 class="page-title"><i class="bi bi-check-circle me-2"></i><?php echo $titulo; ?></h1>
                        <a href="<?= site_url('anestesistas') ?>" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Volver a Anestesistas
                        </a>
                    </div>
                </div>

                <div class="data-card">
                    <div class="data-card-header">
                        <h5 class="mb-0">Anestesistas Disponibles</h5>
                    </div>
                    <div class="data-card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Especialidad</th>
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
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>