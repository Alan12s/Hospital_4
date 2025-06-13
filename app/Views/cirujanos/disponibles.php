<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
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
        
        <!-- Contenido principal -->
        <div class="main-content">
            <div class="main-wrapper">
                <div class="welcome-section">
                    <div class="d-flex justify-content-between align-items-center">
                        <h1 class="page-title"><i class="bi bi-check-circle me-2"></i><?= esc($titulo) ?></h1>
                        <a href="<?= site_url('cirujanos') ?>" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-1"></i> Volver
                        </a>
                    </div>
                    <p class="page-subtitle text-muted">Cirujanos actualmente disponibles para asignación</p>
                </div>

                <div class="data-card">
                    <div class="data-card-header">
                        <h5 class="mb-0"><i class="bi bi-list-check me-2"></i>Cirujanos Disponibles</h5>
                    </div>
                    <div class="data-card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Nombre</th>
                                        <th>DNI</th>
                                        <th>Teléfono</th>
                                        <th>Email</th>
                                        <th>Especialidad</th>
                                        <th>Estado</th>
                                        <th class="text-end">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($cirujanos)): ?>
                                        <?php foreach($cirujanos as $cirujano): ?>
                                        <tr>
                                            <td><?= esc($cirujano->nombre) ?></td>
                                            <td><?= esc($cirujano->dni) ?></td>
                                            <td><?= esc($cirujano->telefono) ?></td>
                                            <td><?= esc($cirujano->email) ?></td>
                                            <td><?= esc($cirujano->especialidad ?? 'Sin especialidad') ?></td>
                                            <td>
                                                <?php
                                                    switch ($cirujano->disponibilidad) {
                                                        case 'disponible':
                                                            echo '<span class="badge bg-success">Disponible</span>';
                                                            break;
                                                        case 'no_disponible':
                                                            echo '<span class="badge bg-secondary">No disponible</span>';
                                                            break;
                                                        case 'en_cirugia':
                                                            echo '<span class="badge bg-warning text-dark">En cirugía</span>';
                                                            break;
                                                        default:
                                                            echo '<span class="badge bg-light text-dark">Desconocido</span>';
                                                    }
                                                ?>
                                            </td>
                                            <td class="text-end">
                                                <div class="btn-group btn-group-sm">
                                                    <a href="<?= site_url('cirujanos/ver/'.$cirujano->id) ?>" class="btn btn-secondary" title="Ver">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <a href="<?= site_url('cirujanos/editar/'.$cirujano->id) ?>" class="btn btn-primary" title="Editar">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="7" class="text-center text-muted">No hay cirujanos disponibles.</td>
                                        </tr>
                                    <?php endif; ?>
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