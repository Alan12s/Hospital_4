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
    <div class="wrapper">
        <!-- Sidebar -->
        <?= $this->include('includes/sidebar') ?>
        
        <!-- Main Content -->
        <div class="main-content">
            <!-- Page Header -->
            <div class="dashboard-header animate-fade-in">
                <h1>
                    <i class='bx bx-plus-medical'></i><?= esc($title) ?>
                </h1>
                <div class="d-flex gap-2">
                    <a href="<?= site_url('instrumentistas/crear') ?>" class="btn btn-primary">
                        <i class='bx bx-plus'></i> Nuevo Instrumentista
                    </a>
                    <a href="<?= site_url('instrumentistas/disponibles') ?>" class="btn btn-success">
                        <i class='bx bx-check-circle'></i> Disponibles
                    </a>
                </div>
            </div>

            <!-- Flash Messages -->
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show glass-card">
                    <div class="d-flex align-items-center">
                        <i class='bx bx-check-circle me-2'></i>
                        <?= esc(session()->getFlashdata('success')) ?>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show glass-card">
                    <div class="d-flex align-items-center">
                        <i class='bx bx-error-circle me-2'></i>
                        <?= esc(session()->getFlashdata('error')) ?>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- Instrumentistas Table -->
            <div class="glass-card animate-fade-in" style="animation-delay: 0.1s">
                <div class="card-header">
                    <h5><i class='bx bx-table me-2'></i>Listado de Instrumentistas</h5>
                    <span class="badge bg-primary"><?= count($instrumentistas) ?> registros</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Nombre Completo</th>
                                    <th>DNI</th>
                                    <th>Especialidad</th>
                                    <th>Disponibilidad</th>
                                    <th>Contacto</th>
                                    <th class="text-end">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($instrumentistas)): ?>
                                    <tr>
                                        <td colspan="6" class="empty-state">
                                            <i class='bx bx-user-x'></i>
                                            <p>No hay instrumentistas registrados</p>
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($instrumentistas as $instrumentista): ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="me-2">
                                                        <i class='bx bx-user-circle' style="font-size: 1.25rem; color: var(--primary-color);"></i>
                                                    </div>
                                                    <div>
                                                        <strong><?= esc($instrumentista['nombre']) ?></strong>
                                                        <div class="text-muted small" style="font-size: 0.75rem;">
                                                            <?= esc($instrumentista['email']) ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td><?= esc($instrumentista['dni']) ?></td>
                                            <td><?= esc($instrumentista['especialidad']) ?></td>
                                            <td>
                                                <span class="badge bg-<?= $instrumentista['disponibilidad'] ?>">
                                                    <?= ucfirst(str_replace('_', ' ', $instrumentista['disponibilidad'])) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-column">
                                                    <small><?= esc($instrumentista['telefono']) ?></small>
                                                </div>
                                            </td>
                                            <td class="text-end">
                                                <div class="d-flex justify-content-end">
                                                    <a href="<?= site_url('instrumentistas/ver/'.$instrumentista['id']) ?>" class="btn-action btn-view" title="Ver detalles">
                                                        <i class='bx bx-show'></i>
                                                    </a>
                                                    <a href="<?= site_url('instrumentistas/editar/'.$instrumentista['id']) ?>" class="btn-action btn-edit" title="Editar">
                                                        <i class='bx bx-edit'></i>
                                                    </a>
                                                    <button type="button"
                                                        class="btn-action btn-delete"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#modalEliminar"
                                                        onclick="configurarModalEliminar({
                                                            idElemento: '<?= $instrumentista['id'] ?>',
                                                            nombreElemento: '<?= esc($instrumentista['nombre']) ?>',
                                                            actionUrl: '<?= site_url('instrumentistas/eliminar/'.$instrumentista['id']) ?>',
                                                            titulo: 'Eliminar Instrumentista',
                                                            mensajeAdicional: 'Se eliminarán todos los registros asociados.',
                                                            icono: 'bx-user-x'
                                                        })"
                                                        title="Eliminar">
                                                        <i class='bx bx-trash'></i>
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

        // Función para animaciones de hover en las cards
        const cards = document.querySelectorAll('.glass-card');
        cards.forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-5px)';
                this.style.boxShadow = '0 15px 30px -5px rgba(0,0,0,0.15)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
                this.style.boxShadow = 'var(--shadow-xl)';
            });
        });
    </script>
    <?= $this->include('includes/footer') ?>
</body>
</html>