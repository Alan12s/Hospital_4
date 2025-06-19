<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?> - Sistema Quirúrgico</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
     <style>
        /* ============= VARIABLES CSS ============= */
        :root {
            --primary-color: #6a1b9a;
            --primary-light: #9c4dcc;
            --primary-dark: #38006b;
            --secondary-color: #10b981;
            --accent-color: #f59e0b;
            --danger-color: #ef4444;
            --warning-color: #f97316;
            --success-color: #22c55e;
            --info-color: #06b6d4;
            --dark-color: #1f2937;
            --light-color: #f8fafc;
            --white: #ffffff;
            --gray-100: #f1f5f9;
            --gray-200: #e2e8f0;
            --gray-300: #cbd5e1;
            --gray-400: #94a3b8;
            --gray-500: #64748b;
            --gray-600: #475569;
            --gray-700: #334155;
            --gray-800: #1e293b;
            --border-radius: 12px;
            --border-radius-sm: 8px;
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow: 0 1px 3px 0 rgb(0 0 0 / 0.1), 0 1px 2px -1px rgb(0 0 0 / 0.1);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
            --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: var(--gray-800);
            min-height: 100vh;
            overflow-x: hidden;
            margin-left: 220px;
        }

        .wrapper {
            display: flex;
            min-height: 100vh;
        }

        .main-content {
            flex: 1;
            padding: 2rem;
            min-height: 100vh;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            width: calc(100% - 220px);
            position: relative;
        }

        /* ============= HEADER STYLES ============= */
        .dashboard-header {
            margin-bottom: 2rem;
            padding: 1.5rem;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-lg);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .dashboard-header h1 {
            font-size: 1.8rem;
            font-weight: 800;
            color: var(--white);
            margin-bottom: 0;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .dashboard-header h1 i {
            font-size: 1.5rem;
            color: var(--accent-color);
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-dark);
            font-weight: 600;
            letter-spacing: 0.5px;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .btn-success {
            background-color: var(--success-color);
            border-color: #16a34a;
            font-weight: 600;
            letter-spacing: 0.5px;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
        }

        .btn-success:hover {
            background-color: #16a34a;
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        /* ============= GLASSMORPHISM CARDS ============= */
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-xl);
            margin-bottom: 2rem;
            transition: all 0.3s ease;
        }

        .glass-card:hover {
            box-shadow: 0 15px 30px -5px rgba(0,0,0,0.15);
        }

        .card-header {
            background: var(--primary-color);
            color: white;
            padding: 1rem 1.5rem;
            border-bottom: none;
            border-radius: var(--border-radius) var(--border-radius) 0 0 !important;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .card-header h5 {
            margin: 0;
            font-weight: 600;
            font-size: 1.1rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .card-header h5 i {
            font-size: 1.2rem;
        }

        /* ============= TABLE STYLES ============= */
        .table-responsive {
            border-radius: 0 0 var(--border-radius) var(--border-radius);
            overflow: hidden;
        }

        .table {
            margin-bottom: 0;
            font-size: 0.9rem;
            width: 100%;
        }

        .table thead th {
            background-color: rgba(106, 27, 154, 0.05);
            border-bottom-width: 1px;
            font-weight: 600;
            color: var(--gray-700);
            padding: 0.75rem 1rem;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
            white-space: nowrap;
        }

        .table tbody tr {
            transition: all 0.2s ease;
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }

        .table tbody tr:last-child {
            border-bottom: none;
        }

        .table tbody tr:hover {
            background-color: rgba(106, 27, 154, 0.03);
        }

        .table td {
            vertical-align: middle;
            padding: 0.75rem 1rem;
            white-space: nowrap;
            color: var(--gray-700);
        }

        /* ============= BUTTON STYLES ============= */
        .btn-action {
            width: 32px;
            height: 32px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            margin: 0 2px;
            transition: all 0.2s ease;
            font-size: 0.9rem;
            border: none;
            box-shadow: var(--shadow-sm);
        }

        .btn-view {
            background-color: rgba(13, 110, 253, 0.1);
            color: #0d6efd;
        }

        .btn-view:hover {
            background-color: rgba(13, 110, 253, 0.2);
            color: #0d6efd;
            transform: translateY(-2px);
            box-shadow: var(--shadow);
        }

        .btn-edit {
            background-color: rgba(106, 27, 154, 0.1);
            color: var(--primary-color);
        }

        .btn-edit:hover {
            background-color: rgba(106, 27, 154, 0.2);
            color: var(--primary-color);
            transform: translateY(-2px);
            box-shadow: var(--shadow);
        }

        .btn-delete {
            background-color: rgba(220, 53, 69, 0.1);
            color: var(--danger-color);
        }

        .btn-delete:hover {
            background-color: rgba(220, 53, 69, 0.2);
            color: var(--danger-color);
            transform: translateY(-2px);
            box-shadow: var(--shadow);
        }

        /* ============= EMPTY STATE ============= */
        .empty-state {
            padding: 2rem;
            text-align: center;
            color: var(--gray-500);
        }

        .empty-state i {
            font-size: 2rem;
            margin-bottom: 1rem;
            color: var(--gray-400);
            opacity: 0.7;
        }

        .empty-state p {
            margin-bottom: 0;
            font-size: 0.95rem;
        }

        /* ============= ALERT STYLES ============= */
        .alert {
            border-radius: var(--border-radius-sm);
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
            border: none;
            box-shadow: var(--shadow-sm);
        }

        /* ============= BADGE STYLES ============= */
        .badge {
            font-weight: 500;
            padding: 0.35rem 0.65rem;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
        }

        .bg-disponible {
            background-color: var(--success-color) !important;
        }

        .bg-en_cirugia {
            background-color: var(--warning-color) !important;
        }

        .bg-no_disponible {
            background-color: var(--danger-color) !important;
        }

        /* ============= RESPONSIVE ============= */
        @media (max-width: 992px) {
            .table td, .table th {
                padding: 0.65rem 0.75rem;
            }
        }

        @media (max-width: 768px) {
            body {
                margin-left: 0;
            }
            
            .main-content {
                width: 100%;
                padding: 1rem;
            }
            
            .dashboard-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
                padding: 1.25rem;
            }
            
            .dashboard-header h1 {
                font-size: 1.5rem;
            }
            
            .btn-primary, .btn-success {
                width: 100%;
                justify-content: center;
            }
            
            .table-responsive {
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }
        }

        @media (max-width: 576px) {
            .main-content {
                padding: 0.75rem;
            }
            
            .card-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.75rem;
                padding: 1rem;
            }
            
            .table td, .table th {
                padding: 0.5rem;
                font-size: 0.8rem;
            }
            
            .btn-action {
                width: 28px;
                height: 28px;
                font-size: 0.8rem;
            }
        }
    </style>
</head>
<body>
    <!-- Barra de navegación -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?= base_url() ?>">
                <i class="fas fa-hospital me-2"></i>Sistema Quirúrgico
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('turnos') ?>">Turnos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('cirujanos') ?>">Cirujanos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('enfermeros') ?>">Enfermeros</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('quirofanos') ?>">Quirófanos</a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle me-1"></i>Usuario
                        </a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i>Perfil</a>
                            <a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i>Configuración</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="<?= base_url('auth/logout') ?>"><i class="fas fa-sign-out-alt me-2"></i>Cerrar sesión</a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Contenido principal -->
    <div class="container-fluid mt-4">
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-calendar-alt me-2"></i><?= esc($title) ?>
            </h1>
            <div>
                <a href="<?= base_url('turnos/crear') ?>" class="btn btn-primary btn-icon-split">
                    <span class="icon text-white-50">
                        <i class="fas fa-plus"></i>
                    </span>
                    <span class="text">Nuevo Turno</span>
                </a>
            </div>
        </div>

        <!-- Mensajes flash -->
        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle me-2"></i>
                <?= session()->getFlashdata('success') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="fas fa-exclamation-circle me-2"></i>
                <?= session()->getFlashdata('error') ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Filtros -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-filter me-2"></i>Filtros
                </h6>
            </div>
            <div class="card-body">
                <form method="get" action="<?= base_url('turnos') ?>">
                    <div class="row">
                        <div class="col-md-3">
                            <label for="fecha" class="form-label">Fecha</label>
                            <input type="date" class="form-control" id="fecha" name="fecha" value="<?= $filtros['fecha'] ?? '' ?>">
                        </div>
                        <div class="col-md-2">
                            <label for="estado" class="form-label">Estado</label>
                            <select class="form-control" id="estado" name="estado">
                                <option value="">Todos</option>
                                <option value="programado" <?= ($filtros['estado'] ?? '') == 'programado' ? 'selected' : '' ?>>Programado</option>
                                <option value="en_proceso" <?= ($filtros['estado'] ?? '') == 'en_proceso' ? 'selected' : '' ?>>En proceso</option>
                                <option value="completado" <?= ($filtros['estado'] ?? '') == 'completado' ? 'selected' : '' ?>>Completado</option>
                                <option value="cancelado" <?= ($filtros['estado'] ?? '') == 'cancelado' ? 'selected' : '' ?>>Cancelado</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="quirofano" class="form-label">Quirófano</label>
                            <select class="form-control" id="quirofano" name="quirofano">
                                <option value="">Todos</option>
                                <?php foreach ($quirofanos as $quirofano): ?>
                                    <option value="<?= $quirofano->id ?>" <?= ($filtros['quirofano'] ?? '') == $quirofano->id ? 'selected' : '' ?>>
                                        <?= esc($quirofano->nombre) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="cirujano" class="form-label">Cirujano</label>
                            <select class="form-control" id="cirujano" name="cirujano">
                                <option value="">Todos</option>
                                <?php foreach ($cirujanos as $cirujano): ?>
                                    <option value="<?= $cirujano->id ?>" <?= ($filtros['cirujano'] ?? '') == $cirujano->id ? 'selected' : '' ?>>
                                        <?= esc("{$cirujano->apellido}, {$cirujano->nombre}") ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-1 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-filter me-1"></i>Filtrar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tabla de turnos -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-table me-2"></i>Listado de Turnos
                </h6>
                <span class="badge bg-primary"><?= count($turnos) ?> registros</span>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                        <thead class="thead-light">
                            <tr>
                                <th>Fecha</th>
                                <th>Hora</th>
                                <th>Paciente</th>
                                <th>Procedimiento</th>
                                <th>Cirujano</th>
                                <th>Quirófano</th>
                                <th>Enfermero</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($turnos)): ?>
                                <tr>
                                    <td colspan="9" class="text-center py-4">
                                        <i class="fas fa-calendar-times fa-2x mb-3 text-muted"></i>
                                        <p class="text-muted">No se encontraron turnos</p>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($turnos as $turno): ?>
                                    <tr>
                                        <td><?= date('d/m/Y', strtotime($turno->fecha)) ?></td>
                                        <td><?= substr($turno->hora_inicio, 0, 5) ?></td>
                                        <td><?= esc($turno->nombre_paciente ?? 'N/A') ?></td>
                                        <td><?= esc($turno->procedimiento_custom ?? ($turno->nombre_procedimiento ?? 'N/A')) ?></td>
                                        <td><?= esc($turno->nombre_cirujano ?? 'N/A') ?></td>
                                        <td><?= esc($turno->nombre_quirofano ?? 'N/A') ?></td>
                                        <td><?= esc($turno->nombre_enfermero ?? 'N/A') ?></td>
                                        <td>
                                            <span class="badge bg-<?= 
                                                $turno->estado === 'programado' ? 'primary' : 
                                                ($turno->estado === 'completado' ? 'success' : 
                                                ($turno->estado === 'cancelado' ? 'danger' : 'warning')) ?>">
                                                <?= ucfirst(str_replace('_', ' ', $turno->estado)) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-center">
                                                <a href="<?= base_url("turnos/ver/{$turno->id}") ?>" class="btn btn-sm btn-info mx-1" title="Ver">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="<?= base_url("turnos/editar/{$turno->id}") ?>" class="btn btn-sm btn-warning mx-1" title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <?php if ($turno->estado !== 'cancelado'): ?>
                                                    <button class="btn btn-sm btn-danger mx-1 cancelar-turno" 
                                                            data-id="<?= $turno->id ?>"
                                                            data-nombre="Turno del <?= date('d/m/Y', strtotime($turno->fecha)) ?>"
                                                            title="Cancelar">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                <?php endif; ?>
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
    </div>

    <!-- Modal para cancelar turno -->
    <div class="modal fade" id="cancelarModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Cancelar Turno</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>¿Está seguro que desea cancelar el turno de <span id="turnoNombre"></span>?</p>
                    <p class="text-danger">Esta acción no se puede deshacer.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <a href="#" id="confirmarCancelar" class="btn btn-danger">Confirmar Cancelación</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Configurar modal de cancelación
            $('.cancelar-turno').click(function() {
                const id = $(this).data('id');
                const nombre = $(this).data('nombre');
                
                $('#turnoNombre').text(nombre);
                $('#confirmarCancelar').attr('href', `<?= base_url('turnos/cancelar/') ?>${id}`);
                $('#cancelarModal').modal('show');
            });

            // Auto-ocultar mensajes flash después de 5 segundos
            setTimeout(() => {
                $('.alert').fadeOut('slow');
            }, 5000);
        });
    </script>
</body>
</html>
