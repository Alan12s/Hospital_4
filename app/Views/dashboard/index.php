<!DOCTYPE html>
<html lang="es" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($page_title) ? $page_title : 'Dashboard' ?></title>
    
    <!-- Bootstrap CSS - Mantenido para funcionalidad -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Boxicons CSS -->
    <link href="https://cdn.jsdelivr.net/npm/boxicons@2.0.7/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- CSS personalizado comentado temporalmente -->
    <!-- <link rel="stylesheet" href="<?= base_url('public/assets/css/styles.css') ?>"> -->
    <!-- <link rel="stylesheet" href="<?= base_url('public/assets/css/dashboard.css') ?>"> -->
    
    <style>
        /* ============= TRANSICIÓN SUAVE INICIAL ============= */
        html {
            visibility: hidden;
            opacity: 0;
            transition: visibility 0s linear 0.1s, opacity 0.1s linear;
        }

        html[data-theme="light"], 
        html[data-theme="dark"] {
            visibility: visible;
            opacity: 1;
        }

        /* ============= VARIABLES CSS ============= */
        :root {
            --sidebar-width: 220px;
            --primary-color: #3498db;
            --secondary-color: #2c3e50;
            --text-color: #333;
            --light-text: #7f8c8d;
            --bg-color: #f8f9fa;
            --card-bg-color: #ffffff;
            --card-shadow: 0 2px 15px rgba(0,0,0,0.05);
            --border-color: #dee2e6;
            --sidebar-bg-color: #2c3e50;
            --sidebar-text-color: rgba(255, 255, 255, 0.8);
            --sidebar-hover-bg: rgba(255, 255, 255, 0.1);
            --sidebar-footer-bg: #1a252f;
            --modal-header-bg: var(--primary-color);
            --modal-bg: #ffffff;
            --btn-primary-bg: var(--primary-color);
            --btn-primary-color: #ffffff;
            --btn-secondary-bg: #6c757d;
            --btn-secondary-color: #ffffff;
            --btn-danger-bg: #dc3545;
            --btn-danger-color: #ffffff;
            --table-row-hover: rgba(0, 0, 0, 0.02);
            --card-padding: 16px;
            --icon-size: 1.8rem;
            --transition-speed: 0.2s;
            --input-bg: #ffffff;
            --input-text: #495057;
            --input-border: #ced4da;
            --input-placeholder: #6c757d;
            --success-text: #198754;
            --success-border: #198754;
            --success-bg: #198754;
            --success-text-hover: white;
            --primary-text: #0d6efd;
            --primary-border: #0d6efd;
            --primary-bg: #0d6efd;
            --primary-text-hover: white;
            --info-text: #0dcaf0;
            --info-border: #0dcaf0;
            --info-bg: #0dcaf0;
            --info-text-hover: white;
            --warning-text: #ffc107;
            --warning-border: #ffc107;
            --warning-bg: #ffc107;
            --warning-text-hover: black;
            --primary-light-color: #90caf9;
            --reminder-bg: #ffffff;
            --reminder-urgent-bg: rgba(220, 53, 69, 0.1);
            --danger-color: #dc3545;
            --success-color: #28a745;
            --warning-color: #ffc107;
            --primary-dark: #0056b3;
            --primary-light: #90caf9;
            --card-bg-color-darker: color-mix(in srgb, var(--card-bg-color), black 5%);
        }

        /* ============= VARIABLES MODO OSCURO ============= */
        [data-theme="dark"] {
            --primary-color: #60a5fa;
            --secondary-color: #94a3b8;
            --text-color: #e2e8f0;
            --light-text: #cbd5e1;
            --bg-color: #1e293b;
            --card-bg-color: #334155;
            --card-shadow: 0 2px 15px rgba(0,0,0,0.2);
            --border-color: #475569;
            --sidebar-bg-color: #0f172a;
            --sidebar-text-color: #e2e8f0;
            --sidebar-hover-bg: rgba(255, 255, 255, 0.05);
            --sidebar-footer-bg: #020617;
            --modal-header-bg: #2563eb;
            --modal-bg: #334155;
            --btn-primary-bg: #3b82f6;
            --btn-primary-color: #ffffff;
            --btn-secondary-bg: #64748b;
            --btn-secondary-color: #ffffff;
            --btn-danger-bg: #ef4444;
            --btn-danger-color: #ffffff;
            --table-row-hover: rgba(255, 255, 255, 0.02);
            --input-bg: #475569;
            --input-text: #f8f9fa;
            --input-border: #64748b;
            --input-placeholder: #94a3b8;
            --success-text: #6ee7b7;
            --success-border: #10b981;
            --success-bg: #10b981;
            --success-text-hover: #ffffff;
            --primary-text: #93c5fd;
            --primary-border: #3b82f6;
            --primary-bg: #3b82f6;
            --primary-text-hover: #ffffff;
            --info-text: #67e8f9;
            --info-border: #06b6d4;
            --info-bg: #06b6d4;
            --info-text-hover: #ffffff;
            --warning-text: #fcd34d;
            --warning-border: #f59e0b;
            --warning-bg: #f59e0b;
            --warning-text-hover: #ffffff;
            --success-color: #34d399;
            --warning-color: #fbbf24;
        }

        /* ============= ESTILOS GENERALES ============= */
        body {
            background-color: var(--bg-color);
            color: var(--text-color);
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            min-height: 100vh;
            transition: background-color var(--transition-speed) ease-in-out, color var(--transition-speed) ease-in-out;
        }

        /* ============= WRAPPER Y ESTRUCTURA ============= */
        .wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* ============= SIDEBAR ============= */
        .sidebar {
            width: var(--sidebar-width);
            background-color: var(--sidebar-bg-color);
            color: var(--sidebar-text-color);
            position: fixed;
            height: 100vh;
            z-index: 100;
            display: flex;
            flex-direction: column;
            left: 0;
            top: 0;
            transition: width var(--transition-speed), background-color var(--transition-speed) ease-in-out;
        }

        .sidebar.collapsed {
            width: 70px;
        }

        /* ============= CONTENIDO PRINCIPAL ============= */
        .main-content {
            margin-left: var(--sidebar-width);
            width: calc(100% - var(--sidebar-width));
            min-height: 100vh;
            transition: margin-left var(--transition-speed), width var(--transition-speed);
        }

        .main-content.expanded {
            margin-left: 70px;
            width: calc(100% - 70px);
        }

        /* ============= NAVBAR SUPERIOR ============= */
        .navbar {
            background-color: var(--card-bg-color) !important;
            color: var(--text-color);
            border-bottom: 1px solid var(--border-color);
            transition: background-color var(--transition-speed), color var(--transition-speed);
        }

        .navbar-brand {
            color: var(--text-color) !important;
            font-weight: 600;
        }

        /* ============= TARJETAS ============= */
        .card {
            background-color: var(--card-bg-color);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            box-shadow: var(--card-shadow);
            transition: background-color var(--transition-speed), border-color var(--transition-speed), box-shadow var(--transition-speed);
        }

        .card-header {
            background-color: var(--primary-color);
            color: white;
            border-bottom: 1px solid var(--border-color);
            padding: 12px var(--card-padding);
            font-weight: 600;
        }

        .card-body {
            padding: var(--card-padding);
            color: var(--text-color);
        }

        /* ============= TARJETAS DE ESTADÍSTICAS ============= */
        .bg-primary {
            background-color: var(--primary-color) !important;
        }

        .bg-success {
            background-color: var(--success-color) !important;
        }

        .bg-warning {
            background-color: var(--warning-color) !important;
        }

        .bg-info {
            background-color: var(--info-bg) !important;
        }

        /* ============= BOTONES ============= */
        .btn {
            border-radius: 5px;
            transition: all var(--transition-speed);
            font-size: 0.9rem;
        }

        .btn-primary {
            background-color: var(--btn-primary-bg);
            border-color: var(--btn-primary-bg);
            color: var(--btn-primary-color);
        }

        .btn-primary:hover {
            background-color: var(--primary-dark);
            border-color: var(--primary-dark);
        }

        .btn-outline-primary {
            color: var(--primary-color);
            border-color: var(--primary-color);
            background-color: transparent;
        }

        .btn-outline-primary:hover {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
        }

        .btn-outline-success {
            color: var(--success-color);
            border-color: var(--success-color);
            background-color: transparent;
        }

        .btn-outline-success:hover {
            background-color: var(--success-color);
            border-color: var(--success-color);
            color: white;
        }

        .btn-outline-info {
            color: var(--info-text);
            border-color: var(--info-border);
            background-color: transparent;
        }

        .btn-outline-info:hover {
            background-color: var(--info-bg);
            border-color: var(--info-border);
            color: white;
        }

        .btn-outline-secondary {
            color: var(--secondary-color);
            border-color: var(--secondary-color);
            background-color: transparent;
        }

        .btn-outline-secondary:hover {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
            color: white;
        }

        /* ============= ALERTAS ============= */
        .alert {
            border-radius: 8px;
            margin-bottom: 16px;
            transition: all var(--transition-speed);
        }

        .alert-success {
            background-color: rgba(40, 167, 69, 0.1);
            border-color: var(--success-color);
            color: var(--success-color);
        }

        .alert-danger {
            background-color: rgba(220, 53, 69, 0.1);
            border-color: var(--danger-color);
            color: var(--danger-color);
        }

        .alert-warning {
            background-color: rgba(255, 193, 7, 0.1);
            border-color: var(--warning-color);
            color: #856404;
        }

        .alert-info {
            background-color: rgba(13, 202, 240, 0.1);
            border-color: var(--info-border);
            color: #055160;
        }

        /* ============= BADGES ============= */
        .badge {
            font-size: 0.8rem;
            padding: 4px 8px;
            border-radius: 4px;
        }

        .bg-primary.badge {
            background-color: var(--primary-color) !important;
        }

        /* ============= TEXTO Y UTILIDADES ============= */
        .text-muted {
            color: var(--light-text) !important;
        }

        .lead {
            font-size: 1.1rem;
            font-weight: 300;
        }

        h1, h2, h3, h4, h5, h6 {
            color: var(--text-color);
        }

        /* ============= DROPDOWN ============= */
        .dropdown-menu {
            background-color: var(--card-bg-color);
            border: 1px solid var(--border-color);
            box-shadow: var(--card-shadow);
        }

        .dropdown-item {
            color: var(--text-color);
            transition: all var(--transition-speed);
        }

        .dropdown-item:hover {
            background-color: var(--sidebar-hover-bg);
            color: var(--text-color);
        }

        .dropdown-toggle {
            color: var(--text-color) !important;
            border-color: var(--primary-color) !important;
        }

        /* ============= TRANSICIONES GLOBALES ============= */
        .card,
        .btn,
        .alert,
        .navbar,
        .dropdown-menu {
            transition: all var(--transition-speed) ease-in-out;
        }

        /* ============= RESPONSIVE ============= */
        @media (max-width: 992px) {
            :root {
                --card-padding: 14px;
                --sidebar-width: 200px;
            }
        }

        @media (max-width: 768px) {
            :root {
                --sidebar-width: 100%;
            }
            
            .wrapper {
                flex-direction: column;
            }
            
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }
            
            .main-content {
                margin-left: 0;
                width: 100%;
            }
            
            .main-content.expanded {
                margin-left: 0;
                width: 100%;
            }
        }

        @media (max-width: 576px) {
            :root {
                --card-padding: 10px;
            }
            
            .container-fluid {
                padding-left: 12px;
                padding-right: 12px;
            }
        }

        /* ============= ICONOS BOXICONS ============= */
        .bx {
            font-size: inherit;
            vertical-align: middle;
        }

        /* ============= MEJORAS ADICIONALES ============= */
        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }

        .btn:hover {
            transform: translateY(-1px);
        }

        /* Modo oscuro para elementos específicos */
        [data-theme="dark"] .navbar {
            background-color: var(--card-bg-color) !important;
        }

        [data-theme="dark"] .text-white {
            color: #ffffff !important;
        }

        [data-theme="dark"] .alert-warning {
            color: #fcd34d;
        }

        [data-theme="dark"] .alert-info {
            color: #67e8f9;
        }
    </style>
    
    <!-- JavaScript personalizado comentado temporalmente -->
    <!-- <script src="<?= base_url('public/assets/js/dashboard.js') ?>"></script> -->
</head>
<body>
    <div class="wrapper">
        <!-- Incluir el sidebar -->
        <?= $this->include('includes/sidebar.php') ?>
        
        <!-- Contenido principal -->
        <div class="main-content">
            <!-- Header/Navbar superior -->
            <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
                <div class="container-fluid">
                    <button class="btn btn-outline-secondary me-3" id="sidebar-toggle">
                        <i class="bx bx-menu"></i>
                    </button>
                    <h1 class="navbar-brand mb-0">Dashboard - Gestión Quirúrgica</h1>
                    
                    <div class="ms-auto d-flex align-items-center">
                        <span class="me-3">
                            <i class="bx bx-time"></i>
                            <?= isset($fecha_actual) ? $fecha_actual : date('d/m/Y') ?> - 
                            <?= isset($hora_actual) ? $hora_actual : date('H:i') ?>
                        </span>
                        <div class="dropdown">
                            <button class="btn btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                <?= esc($nombre . ' ' . $apellidos) ?> (<?= esc($rol) ?>)
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="<?= base_url('perfil') ?>">Mi Perfil</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="<?= base_url('logout') ?>">Cerrar Sesión</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Contenido del dashboard -->
            <div class="container-fluid p-4">
                <!-- Mostrar mensajes flash -->
                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <?= session()->getFlashdata('success') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?= session()->getFlashdata('error') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <!-- Bienvenida -->
                <div class="row mb-4">
                    <div class="col-12">
                        <h2>Bienvenido, <?= esc($nombre) ?>!</h2>
                        <p class="lead text-muted">Panel de control del sistema hospitalario</p>
                    </div>
                </div>

                <!-- Tarjetas de estadísticas -->
                <div class="row mb-4">
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="card text-white bg-primary">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <span>Turnos Hoy</span>
                                <i class="bx bx-plus-medical bx-lg"></i>
                            </div>
                            <div class="card-body">
                                <h4 class="card-title" id="turnos-hoy"><?= isset($turnos_hoy) ? $turnos_hoy : '0' ?></h4>
                                <p class="card-text">Turnos programados para hoy</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="card text-white bg-success">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <span>Médicos</span>
                                <i class="bx bxs-user-plus bx-lg"></i>
                            </div>
                            <div class="card-body">
                                <h4 class="card-title" id="medicos-count"><?= isset($medicos_count) ? $medicos_count : '0' ?></h4>
                                <p class="card-text">Médicos disponibles</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="card text-white bg-warning">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <span>Enfermeros</span>
                                <i class="bx bx-heart bx-lg"></i>
                            </div>
                            <div class="card-body">
                                <h4 class="card-title" id="enfermeros-disponibles"><?= isset($enfermeros_disponibles) ? $enfermeros_disponibles : '0' ?></h4>
                                <p class="card-text">Enfermeros disponibles</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-3 col-sm-6 mb-3">
                        <div class="card text-white bg-info">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <span>Pacientes</span>
                                <i class="bx bx-clipboard bx-lg"></i>
                            </div>
                            <div class="card-body">
                                <h4 class="card-title" id="pacientes-count"><?= isset($pacientes_count) ? $pacientes_count : '0' ?></h4>
                                <p class="card-text">Total de pacientes</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <!-- Accesos Rápidos -->
                    <div class="col-md-6 mb-4">
                        <div class="card h-100">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0"><i class="bx bx-zap me-2"></i>Accesos Rápidos</h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-2">
                                    <?php if(in_array($rol, ['administrador', 'supervisor', 'medico', 'enfermero'])): ?>
                                    <div class="col-6">
                                        <a href="<?= base_url('turnos') ?>" class="btn btn-outline-primary w-100 mb-2">
                                            <i class="bx bx-plus-medical me-1"></i>Turnos
                                        </a>
                                    </div>
                                    <?php endif; ?>
                                    
                                    <?php if(in_array($rol, ['administrador', 'supervisor', 'medico'])): ?>
                                    <div class="col-6">
                                        <a href="<?= base_url('insumos') ?>" class="btn btn-outline-success w-100 mb-2">
                                            <i class="bx bx-box me-1"></i>Insumos
                                        </a>
                                    </div>
                                    <?php endif; ?>
                                    
                                    <?php if(in_array($rol, ['administrador', 'supervisor', 'medico', 'enfermero'])): ?>
                                    <div class="col-6">
                                        <a href="<?= base_url('pacientes') ?>" class="btn btn-outline-info w-100 mb-2">
                                            <i class="bx bx-clipboard me-1"></i>Pacientes
                                        </a>
                                    </div>
                                    <?php endif; ?>
                                    
                                    <?php if(in_array($rol, ['administrador', 'supervisor'])): ?>
                                    <div class="col-6">
                                        <a href="<?= base_url('usuarios') ?>" class="btn btn-outline-secondary w-100 mb-2">
                                            <i class="bx bx-user me-1"></i>Usuarios
                                        </a>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Información del Usuario -->
                    <div class="col-md-6 mb-4">
                        <div class="card h-100">
                            <div class="card-header bg-info text-white">
                                <h5 class="mb-0"><i class="bx bx-user-circle me-2"></i>Información del Usuario</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-4"><strong>Usuario:</strong></div>
                                    <div class="col-sm-8"><?= esc($username) ?></div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-4"><strong>Nombre:</strong></div>
                                    <div class="col-sm-8"><?= esc($nombre . ' ' . $apellidos) ?></div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-4"><strong>Email:</strong></div>
                                    <div class="col-sm-8"><?= esc($email) ?></div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-4"><strong>Rol:</strong></div>
                                    <div class="col-sm-8">
                                        <span class="badge bg-primary"><?= esc($rol) ?></span>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-4"><strong>ID Sesión:</strong></div>
                                    <div class="col-sm-8"><?= esc($user_id) ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recordatorios si existen -->
                <?php if(isset($recordatorios) && !empty($recordatorios)): ?>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header bg-warning text-dark">
                                <h5 class="mb-0"><i class="bx bx-bell me-2"></i>Recordatorios</h5>
                            </div>
                            <div class="card-body">
                                <?php foreach($recordatorios as $recordatorio): ?>
                                <div class="alert <?= $recordatorio['urgente'] ? 'alert-danger' : 'alert-info' ?> d-flex align-items-center">
                                    <i class="bx <?= $recordatorio['urgente'] ? 'bx-error-circle' : 'bx-info-circle' ?> me-2"></i>
                                    <div>
                                        <strong><?= esc($recordatorio['fecha']) ?>:</strong>
                                        <?= esc($recordatorio['mensaje']) ?>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle sidebar
            const sidebarToggle = document.getElementById('sidebar-toggle');
            const sidebar = document.querySelector('.sidebar');
            const mainContent = document.querySelector('.main-content');
            
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('collapsed');
                    mainContent.classList.toggle('expanded');
                });
            }
            
            // Actualizar estadísticas cada 30 segundos
            setInterval(function() {
                updateStats();
            }, 30000);
            
            function updateStats() {
                fetch('<?= base_url("inicio/getStats") ?>', {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    document.getElementById('turnos-hoy').textContent = data.turnos_hoy;
                    document.getElementById('medicos-count').textContent = data.medicos_count;
                    document.getElementById('enfermeros-disponibles').textContent = data.enfermeros_disponibles;
                    document.getElementById('pacientes-count').textContent = data.pacientes_count;
                })
                .catch(error => console.error('Error actualizando estadísticas:', error));
            }
        });
    </script>
</body>
</html>