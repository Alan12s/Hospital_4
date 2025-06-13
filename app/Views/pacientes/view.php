<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($titulo ?? 'Detalle Paciente') ?> - Sistema Quirúrgico</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        /* ============= VARIABLES CSS ============= */
        :root {
            --primary-color: #6a1b9a;
            --primary-light: #9c4dcc;
            --primary-dark: #38006b;
            --primary-border: #4a148c;
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

        /* ============= BASE STYLES ============= */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: var(--gray-800);
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* ============= LAYOUT STRUCTURE ============= */
        .wrapper {
            display: flex;
            min-height: 100vh;
        }
        
        .main-content {
            flex: 1;
            margin-left: 220px;
            padding: 2rem;
            min-height: 100vh;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            width: calc(100% - 220px);
            position: relative;
        }

        .main-content::before {
            content: '';
            position: fixed;
            top: 0;
            left: 220px;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            z-index: -1;
        }
        
        .content-container {
            max-width: 100%;
            padding: 0;
            margin: 0;
            position: relative;
            z-index: 1;
        }

        /* ============= HEADER STYLES ============= */
        .dashboard-header {
            margin-bottom: 2rem;
            padding: 2rem;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid var(--primary-border);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-lg);
        }

        .dashboard-header h1 {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--white);
            margin-bottom: 0.5rem;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .dashboard-header h1 i {
            font-size: 1.8rem;
            color: var(--accent-color);
        }

        .dashboard-header .subtitle {
            color: rgba(255, 255, 255, 0.8);
            font-size: 1.1rem;
            font-weight: 500;
            margin-bottom: 1rem;
        }

        /* ============= GLASSMORPHISM CARDS ============= */
        .glass-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid var(--primary-border);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-lg);
        }

        .glass-card-solid {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid var(--primary-border);
            border-radius: var(--border-radius);
            box-shadow: var(--shadow-xl);
        }
        
        .card-header {
            background: var(--primary-color);
            color: white;
            padding: 1.5rem;
            border-bottom: 2px solid var(--primary-border);
            border-radius: var(--border-radius) var(--border-radius) 0 0 !important;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .card-header h3 {
            margin: 0;
            font-weight: 600;
            font-size: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .card-header h3 i {
            font-size: 1.2rem;
        }

        /* ============= DETAIL STYLES ============= */
        .detail-container {
            padding: 2rem;
        }

        .detail-row {
            margin-bottom: 1.5rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid var(--gray-200);
            display: flex;
        }

        .detail-row:last-child {
            border-bottom: none;
            margin-bottom: 0;
            padding-bottom: 0;
        }

        .detail-label {
            font-weight: 600;
            color: var(--gray-700);
            width: 200px;
            flex-shrink: 0;
        }

        .detail-value {
            color: var(--gray-800);
            flex-grow: 1;
        }

        /* ============= BUTTON STYLES ============= */
        .btn {
            border-radius: var(--border-radius-sm);
            font-weight: 600;
            padding: 0.75rem 1.5rem;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            border: 1px solid transparent;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-dark);
        }

        .btn-primary:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
            border-color: var(--primary-border);
        }

        .btn-secondary {
            background-color: var(--gray-300);
            border-color: var(--gray-400);
            color: var(--gray-800);
        }

        .btn-secondary:hover {
            background-color: var(--gray-400);
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
            border-color: var(--gray-500);
        }

        .btn-danger {
            background-color: var(--danger-color);
            border-color: var(--danger-color);
        }

        .btn-danger:hover {
            background-color: #dc3545;
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        /* ============= BADGE STYLES ============= */
        .badge {
            font-weight: 500;
            padding: 0.35em 0.65em;
            border-radius: var(--border-radius-sm);
        }

        .badge-primary {
            background-color: rgba(106, 27, 154, 0.1);
            color: var(--primary-color);
        }

        /* ============= ALERT STYLES ============= */
        .alert {
            border-radius: var(--border-radius-sm);
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
            border: 1px solid transparent;
            box-shadow: var(--shadow-sm);
        }

        .alert-success {
            border-color: var(--success-color);
        }

        .alert-danger {
            border-color: var(--danger-color);
        }

        /* ============= RESPONSIVE ============= */
        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
                width: 100%;
                padding: 1rem;
            }

            .main-content::before {
                left: 0;
            }
            
            .dashboard-header h1 {
                font-size: 2rem;
            }
            
            .detail-container {
                padding: 1.5rem;
            }

            .detail-row {
                flex-direction: column;
                gap: 0.5rem;
            }

            .detail-label {
                width: 100%;
            }
        }

        @media (max-width: 576px) {
            .dashboard-header {
                padding: 1.5rem;
            }

            .dashboard-header h1 {
                font-size: 1.8rem;
            }
            
            .detail-container {
                padding: 1rem;
            }
        }

        /* ============= ANIMATIONS ============= */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fadeInUp 0.6s ease-out;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <?= $this->include('includes/sidebar') ?>
        
        <!-- Main Content -->
        <div class="main-content">
            <div class="content-container">
                <!-- Page Header -->
                <div class="dashboard-header animate-fade-in">
                    <div class="d-flex justify-content-between align-items-center">
                        <h1>
                            <i class='bx bx-user-circle'></i><?= esc($titulo ?? 'Detalle Paciente') ?>
                        </h1>
                        <a href="<?= site_url('pacientes') ?>" class="btn btn-secondary">
                            <i class='bx bx-arrow-back'></i> Volver a Pacientes
                        </a>
                    </div>
                    <p class="subtitle">Información detallada del paciente registrado en el sistema</p>
                </div>

                <!-- Flash Messages -->
                <?php if (session()->getFlashdata('mensaje')): ?>
                    <div class="alert alert-success alert-dismissible fade show animate-fade-in">
                        <div class="d-flex align-items-center">
                            <i class='bx bx-check-circle me-2'></i>
                            <?= esc(session()->getFlashdata('mensaje')) ?>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show animate-fade-in">
                        <div class="d-flex align-items-center">
                            <i class='bx bx-error-circle me-2'></i>
                            <?= esc(session()->getFlashdata('error')) ?>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <!-- Patient Detail Card -->
                <div class="glass-card-solid animate-fade-in" style="animation-delay: 0.1s">
                    <div class="card-header">
                        <h3><i class='bx bx-id-card me-2'></i>Información del Paciente</h3>
                        <span class="badge bg-light">ID: <?= esc($paciente->id) ?></span>
                    </div>
                    <div class="detail-container">
                        <div class="detail-row">
                            <div class="detail-label">Nombre Completo:</div>
                            <div class="detail-value"><?= esc($paciente->nombre) ?></div>
                        </div>
                        
                        <div class="detail-row">
                            <div class="detail-label">DNI:</div>
                            <div class="detail-value"><?= esc($paciente->dni) ?></div>
                        </div>
                        
                        <div class="detail-row">
                            <div class="detail-label">Edad:</div>
                            <div class="detail-value">
                                <?php
                                    $fechaNacimiento = new \DateTime($paciente->fecha_nacimiento);
                                    $hoy = new \DateTime();
                                    $edad = $hoy->diff($fechaNacimiento)->y;
                                    echo esc($edad) . ' años';
                                ?>
                            </div>
                        </div>

                        <div class="detail-row">
                            <div class="detail-label">Fecha de Nacimiento:</div>
                            <div class="detail-value"><?= esc($paciente->fecha_nacimiento) ?></div>
                        </div>

                        <div class="detail-row">
                            <div class="detail-label">Historial Médico:</div>
                            <div class="detail-value"><?= esc($paciente->historial_medico) ?></div>
                        </div>
                        
                        <div class="detail-row">
                            <div class="detail-label">Obra Social:</div>
                            <div class="detail-value">
                                <span class="badge badge-primary"><?= esc($paciente->obra_social) ?></span>
                            </div>
                        </div>

                        <div class="detail-row">
                            <div class="detail-label">Teléfono:</div>
                            <div class="detail-value"><?= esc($paciente->telefono) ?></div>
                        </div>
                        
                        <div class="detail-row">
                            <div class="detail-label">Dirección:</div>
                            <div class="detail-value"><?= esc($paciente->direccion) ?></div>
                        </div>
                        
                        <div class="detail-row">
                            <div class="detail-label">Departamento:</div>
                            <div class="detail-value"><?= esc($paciente->departamento) ?></div>
                        </div>
                        
                        <div class="detail-row">
                            <div class="detail-label">Email:</div>
                            <div class="detail-value"><?= esc($paciente->email) ?></div>
                        </div>

                        <div class="d-flex justify-content-end mt-4">
                            <a href="<?= site_url('pacientes/editar/' . $paciente->id) ?>" class="btn btn-primary me-2">
                                <i class='bx bx-edit'></i> Editar Paciente
                            </a>
                            <button type="button"
                                class="btn btn-danger"
                                data-bs-toggle="modal"
                                data-bs-target="#modalEliminar"
                                onclick="configurarModalEliminar({
                                    idElemento: '<?= $paciente->id ?>',
                                    nombreElemento: '<?= esc($paciente->nombre) ?>',
                                    actionUrl: '<?= site_url('pacientes/delete/'.$paciente->id) ?>',
                                    titulo: 'Eliminar Paciente',
                                    mensajeAdicional: 'Se eliminarán todos los registros médicos asociados.',
                                    icono: 'bx-user-x'
                                })">
                                <i class='bx bx-trash'></i> Eliminar Paciente
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Incluir modal de eliminación reutilizable -->
    <?= $this->include('includes/modal_eliminar') ?>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-hide alerts after 5 seconds
            const alertas = document.querySelectorAll('.alert');
            alertas.forEach(alerta => {
                setTimeout(() => {
                    alerta.classList.remove('show');
                }, 5000);
            });

            // Aplicar animaciones escalonadas
            const elementosAnimados = document.querySelectorAll('.animate-fade-in');
            elementosAnimados.forEach((elemento, index) => {
                elemento.style.animationDelay = `${index * 0.1}s`;
            });
        });
    </script>
</body>
</html>