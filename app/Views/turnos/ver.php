<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title) ?> - Sistema Quirúrgico</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        /* Estilos iguales a los de editar.php */
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
            border-radius: var(--border-radius-sm);
            padding: 0.75rem 1.5rem;
        }

        .btn-primary:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .btn-secondary {
            background-color: var(--gray-600);
            border-color: var(--gray-700);
            font-weight: 600;
            letter-spacing: 0.5px;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            border-radius: var(--border-radius-sm);
            padding: 0.75rem 1.5rem;
        }

        .btn-secondary:hover {
            background-color: var(--gray-700);
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .btn-danger {
            background-color: var(--danger-color);
            border-color: var(--danger-color);
            font-weight: 600;
            letter-spacing: 0.5px;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            border-radius: var(--border-radius-sm);
            padding: 0.75rem 1.5rem;
        }

        .btn-danger:hover {
            background-color: #dc3545;
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        .btn-warning {
            background-color: var(--warning-color);
            border-color: var(--warning-color);
            font-weight: 600;
            letter-spacing: 0.5px;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            border-radius: var(--border-radius-sm);
            padding: 0.75rem 1.5rem;
        }

        .btn-warning:hover {
            background-color: #e06b1e;
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

        .card-body {
            padding: 2rem;
        }

        /* ============= DETAIL VIEW STYLES ============= */
        .detail-row {
            padding: 1rem 0;
            border-bottom: 1px solid var(--gray-200);
            display: flex;
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .detail-label {
            font-weight: 600;
            color: var(--gray-700);
            width: 30%;
        }

        .detail-value {
            color: var(--gray-800);
            width: 70%;
        }

        .badge {
            font-size: 0.8rem;
            font-weight: 600;
            padding: 0.5rem 0.75rem;
            border-radius: var(--border-radius-sm);
        }

        .badge-programado { background-color: var(--gray-600); }
        .badge-agendada { background-color: var(--info-color); }
        .badge-urgencia { background-color: var(--warning-color); color: #000; }
        .badge-en_proceso { background-color: var(--accent-color); color: #000; }
        .badge-completado { background-color: var(--success-color); }
        .badge-cancelado { background-color: var(--danger-color); }

        /* ============= BUTTON GROUPS ============= */
        .button-group {
            display: flex;
            gap: 0.75rem;
            justify-content: flex-end;
            margin-top: 2rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--gray-200);
        }

        /* ============= TABLE STYLES ============= */
        .table-responsive {
            overflow-x: auto;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1rem;
        }

        .table th, .table td {
            padding: 0.75rem;
            vertical-align: top;
            border-top: 1px solid var(--gray-200);
        }

        .table thead th {
            vertical-align: bottom;
            border-bottom: 2px solid var(--gray-200);
            background-color: var(--gray-100);
            font-weight: 600;
        }

        .table tbody + tbody {
            border-top: 2px solid var(--gray-200);
        }

        /* ============= RESPONSIVE ============= */
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
            
            .btn-primary, .btn-secondary, .btn-danger {
                width: 100%;
                justify-content: center;
            }
            
            .button-group {
                flex-direction: column;
            }
            
            .card-body {
                padding: 1.5rem;
            }
            
            .detail-row {
                flex-direction: column;
                gap: 0.5rem;
            }
            
            .detail-label, .detail-value {
                width: 100%;
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
            
            .card-body {
                padding: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <?= $this->include('includes/sidebar') ?>
        
        <!-- Main Content -->
        <div class="main-content">
            <!-- Page Header -->
            <div class="dashboard-header animate-fade-in">
                <h1>
                    <i class='bx bx-calendar'></i><?= esc($title) ?>
                </h1>
                <a href="<?= site_url('turnos') ?>" class="btn btn-secondary">
                    <i class='bx bx-arrow-left'></i> Volver a Turnos
                </a>
            </div>

            <!-- Flash Messages -->
            <?php if (session()->getFlashdata('mensaje')): ?>
                <div class="alert alert-success alert-dismissible fade show glass-card animate-fade-in">
                    <i class='bx bx-check-circle'></i>
                    <div>
                        <?= esc(session()->getFlashdata('mensaje')) ?>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show glass-card animate-fade-in">
                    <i class='bx bx-error-circle'></i>
                    <div>
                        <?= esc(session()->getFlashdata('error')) ?>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                </div>
            <?php endif; ?>

            <!-- Información Básica Card -->
            <div class="glass-card animate-fade-in" style="animation-delay: 0.1s">
                <div class="card-header">
                    <h5><i class='bx bx-info-circle me-2'></i>Información Básica</h5>
                    <span class="badge badge-<?= str_replace('_', '-', $turno['estado']) ?>">
                        <?= esc(ucfirst(str_replace('_', ' ', $turno['estado']))) ?>
                    </span>
                </div>
                <div class="card-body">
                    <div class="detail-row">
                        <div class="detail-label">ID:</div>
                        <div class="detail-value"><?= esc($turno['id']) ?></div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Fecha:</div>
                        <div class="detail-value"><?= date('d/m/Y', strtotime($turno['fecha'])) ?></div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Hora de Inicio:</div>
                        <div class="detail-value"><?= date('H:i', strtotime($turno['hora_inicio'])) ?></div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Duración:</div>
                        <div class="detail-value"><?= esc($turno['duracion']) ?> minutos</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Hora Final Estimada:</div>
                        <div class="detail-value">
                            <?php
                            $horaInicio = new DateTime($turno['hora_inicio']);
                            $horaInicio->add(new DateInterval('PT'.$turno['duracion'].'M'));
                            echo $horaInicio->format('H:i');
                            ?>
                        </div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Quirófano:</div>
                        <div class="detail-value"><?= esc($turno['nombre_quirofano']) ?></div>
                    </div>
                </div>
            </div>

            <!-- Paciente Card -->
            <div class="glass-card animate-fade-in" style="animation-delay: 0.2s">
                <div class="card-header">
                    <h5><i class='bx bx-user me-2'></i>Paciente</h5>
                </div>
                <div class="card-body">
                    <div class="detail-row">
                        <div class="detail-label">Nombre:</div>
                        <div class="detail-value"><?= esc($turno['nombre_paciente']) ?></div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">DNI:</div>
                        <div class="detail-value"><?= esc($turno['dni_paciente'] ?? 'No especificado') ?></div>
                    </div>
                </div>
            </div>

            <!-- Equipo Médico Card -->
            <div class="glass-card animate-fade-in" style="animation-delay: 0.3s">
                <div class="card-header">
                    <h5><i class='bx bx-group me-2'></i>Equipo Médico</h5>
                </div>
                <div class="card-body">
                    <div class="detail-row">
                        <div class="detail-label">Cirujano Principal:</div>
                        <div class="detail-value"><?= esc($turno['nombre_cirujano']) ?></div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Cirujano Ayudante:</div>
                        <div class="detail-value"><?= !empty($turno['id_cirujano_ayudante']) ? esc($turno['nombre_cirujano_ayudante'] ?? 'No especificado') : 'No asignado' ?></div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Anestesista:</div>
                        <div class="detail-value"><?= esc($turno['nombre_anestesista']) ?></div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Técnico Anestesista:</div>
                        <div class="detail-value"><?= esc($turno['nombre_tecnico_anestesista']) ?></div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Tipo de Anestesia:</div>
                        <div class="detail-value"><?= !empty($turno['tipo_anestesia']) ? esc($turno['tipo_anestesia']) : 'No especificado' ?></div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Instrumentador Principal:</div>
                        <div class="detail-value"><?= !empty($turno['id_instrumentador_principal']) ? esc($turno['nombre_instrumentador_principal'] ?? 'No especificado') : 'No asignado' ?></div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Instrumentador Circulante:</div>
                        <div class="detail-value"><?= !empty($turno['id_instrumentador_circulante']) ? esc($turno['nombre_instrumentador_circulante'] ?? 'No especificado') : 'No asignado' ?></div>
                    </div>
                </div>
            </div>

            <!-- Procedimiento Card -->
            <div class="glass-card animate-fade-in" style="animation-delay: 0.4s">
                <div class="card-header">
                    <h5><i class='bx bx-plus-medical me-2'></i>Procedimiento</h5>
                </div>
                <div class="card-body">
                    <div class="detail-row">
                        <div class="detail-label">Procedimiento a realizar:</div>
                        <div class="detail-value"><?= esc($turno['procedimiento']) ?></div>
                    </div>
                </div>
            </div>

            <!-- Insumos Card -->
            <?php if (!empty($insumosTurno)): ?>
            <div class="glass-card animate-fade-in" style="animation-delay: 0.5s">
                <div class="card-header">
                    <h5><i class='bx bx-package me-2'></i>Insumos Requeridos</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Insumo</th>
                                    <th>Tipo</th>
                                    <th>Cantidad</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($insumosTurno as $insumo): ?>
                                <tr>
                                    <td><?= esc($insumo->nombre) ?></td>
                                    <td><?= esc($insumo->tipo) ?></td>
                                    <td><?= $insumo->cantidad ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Observaciones Card -->
            <div class="glass-card animate-fade-in" style="animation-delay: 0.6s">
                <div class="card-header">
                    <h5><i class='bx bx-note me-2'></i>Observaciones y Complicaciones</h5>
                </div>
                <div class="card-body">
                    <div class="detail-row">
                        <div class="detail-label">Observaciones:</div>
                        <div class="detail-value"><?= !empty($turno['observaciones']) ? nl2br(esc($turno['observaciones'])) : 'Ninguna' ?></div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Complicaciones:</div>
                        <div class="detail-value"><?= !empty($turno['complicaciones']) ? nl2br(esc($turno['complicaciones'])) : 'Ninguna' ?></div>
                    </div>
                </div>
            </div>

            <!-- Botones de Acción -->
            <div class="glass-card animate-fade-in" style="animation-delay: 0.7s">
                <div class="card-body">
                    <div class="button-group">
                        <a href="<?= site_url('turnos/editar/'.$turno['id']) ?>" class="btn btn-primary">
                            <i class='bx bx-edit'></i> Editar Turno
                        </a>
                        
                        <?php if ($turno['estado'] != 'cancelado'): ?>
                            <button type="button"
                               class="btn btn-warning"
                               data-bs-toggle="modal"
                               data-bs-target="#modalCancelar"
                               onclick="configurarModalCancelar({
                                   idElemento: '<?= $turno['id'] ?>',
                                   nombreElemento: '<?= esc($turno['nombre_paciente']) ?>',
                                   actionUrl: '<?= site_url('turnos/cancelar') ?>',
                                   titulo: 'Cancelar Turno',
                                   mensajeAdicional: 'El turno será marcado como cancelado y no podrá ser reactivado.',
                                   icono: 'bx-calendar-x'
                               })">
                                <i class='bx bx-calendar-x'></i> Cancelar Turno
                            </button>
                        <?php else: ?>
                            <button type="button"
                               class="btn btn-danger"
                               data-bs-toggle="modal"
                               data-bs-target="#modalEliminar"
                               onclick="configurarModalEliminar({
                                   idElemento: '<?= $turno['id'] ?>',
                                   nombreElemento: '<?= esc($turno['nombre_paciente']) ?>',
                                   actionUrl: '<?= site_url('turnos/eliminar-definitivo') ?>',
                                   titulo: 'Eliminar Definitivamente',
                                   mensajeAdicional: 'Esta acción no se puede deshacer y se perderán todos los datos asociados.',
                                   icono: 'bx-trash'
                               })">
                                <i class='bx bx-trash'></i> Eliminar Definitivamente
                            </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Incluir modales -->
    <?= $this->include('includes/modal_cancelar') ?>
    <?= $this->include('includes/modal_eliminar') ?>

    <!-- Incluir footer del sistema -->
    <?= $this->include('includes/footer') ?>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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

        // Función para configurar el modal de cancelación
        function configurarModalCancelar(options) {
            const {
                idElemento,
                nombreElemento,
                actionUrl,
                titulo = 'Confirmar Cancelación',
                mensajeAdicional = 'El turno será marcado como cancelado.',
                icono = 'bx-calendar-x'
            } = options;

            document.getElementById('modalCancelarTitulo').textContent = titulo;
            document.getElementById('idElementoCancelar').value = idElemento;
            document.getElementById('nombreElementoCancelar').textContent = nombreElemento;
            document.getElementById('mensajeAdicionalCancelar').textContent = mensajeAdicional;
            document.getElementById('formCancelar').action = actionUrl + '/' + idElemento;
            document.querySelector('.cancel-icon-main i').className = `bx ${icono}`;
        }

        // Función para configurar el modal de eliminación
        function configurarModalEliminar(options) {
            const {
                idElemento,
                nombreElemento,
                actionUrl,
                titulo = 'Confirmar Eliminación',
                mensajeAdicional = 'Esta acción no se puede deshacer y se perderán todos los datos asociados.',
                icono = 'bx-trash'
            } = options;

            document.getElementById('modalEliminarTitulo').textContent = titulo;
            document.getElementById('idElemento').value = idElemento;
            document.getElementById('nombreElemento').textContent = nombreElemento;
            document.getElementById('mensajeAdicional').textContent = mensajeAdicional;
            document.getElementById('formEliminar').action = actionUrl + '/' + idElemento;
            document.querySelector('.delete-icon-main i').className = `bx ${icono}`;
        }
    </script>
</body>
</html>