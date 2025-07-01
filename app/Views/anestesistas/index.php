<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($titulo) ?> - Sistema Quirúrgico</title>
    
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

        /* ================================
           ANIMACIONES DEL MODAL DE ELIMINAR
           ================================ */
        
        /* Modal con efecto de entrada suave */
        .delete-modal {
            animation: modalSlideIn 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94) forwards;
            box-shadow: 0 20px 50px rgba(220, 53, 69, 0.3), 0 0 100px rgba(220, 53, 69, 0.1);
        }
        
        @keyframes modalSlideIn {
            0% {
                opacity: 0;
                transform: scale(0.8) translateY(-50px) rotateX(15deg);
            }
            100% {
                opacity: 1;
                transform: scale(1) translateY(0) rotateX(0deg);
            }
        }
        
        /* Ícono gigante de fondo */
        .delete-icon-bg {
            position: absolute;
            top: -20px;
            right: -20px;
            z-index: 1;
            opacity: 0.1;
            animation: iconPulse 3s ease-in-out infinite;
        }
        
        .delete-icon-bg i {
            font-size: 180px;
            color: #dc3545;
            transform: rotate(-15deg);
        }
        
        @keyframes iconPulse {
            0%, 100% {
                transform: rotate(-15deg) scale(1);
                opacity: 0.1;
            }
            50% {
                transform: rotate(-10deg) scale(1.1);
                opacity: 0.2;
            }
        }
        
        /* Partículas flotantes */
        .particles {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            pointer-events: none;
            z-index: 2;
        }
        
        .particle {
            position: absolute;
            background: #dc3545;
            border-radius: 50%;
            animation: particleFloat 4s ease-in-out infinite;
        }
        
        .particle:nth-child(1) {
            width: 4px;
            height: 4px;
            top: 20%;
            left: 20%;
            animation-delay: 0s;
        }
        
        .particle:nth-child(2) {
            width: 3px;
            height: 3px;
            top: 40%;
            left: 80%;
            animation-delay: 1s;
        }
        
        .particle:nth-child(3) {
            width: 5px;
            height: 5px;
            top: 70%;
            left: 30%;
            animation-delay: 2s;
        }
        
        .particle:nth-child(4) {
            width: 2px;
            height: 2px;
            top: 30%;
            left: 60%;
            animation-delay: 3s;
        }
        
        .particle:nth-child(5) {
            width: 4px;
            height: 4px;
            top: 80%;
            left: 70%;
            animation-delay: 1.5s;
        }
        
        @keyframes particleFloat {
            0%, 100% {
                transform: translateY(0px) rotate(0deg);
                opacity: 0.7;
            }
            50% {
                transform: translateY(-20px) rotate(180deg);
                opacity: 1;
            }
        }
        
        /* Título del modal */
        .delete-title {
            animation: titleGlow 2s ease-in-out infinite alternate;
            position: relative;
            z-index: 3;
        }
        
        @keyframes titleGlow {
            0% {
                text-shadow: 0 0 5px rgba(220, 53, 69, 0.5);
            }
            100% {
                text-shadow: 0 0 20px rgba(220, 53, 69, 0.8), 0 0 30px rgba(220, 53, 69, 0.4);
            }
        }
        
        /* Ícono del header */
        .delete-icon-header {
            animation: iconBounce 1s ease-in-out infinite;
        }
        
        @keyframes iconBounce {
            0%, 100% {
                transform: translateX(0);
            }
            50% {
                transform: translateX(5px);
            }
        }
        
        /* Contenido del modal */
        .delete-content {
            position: relative;
            z-index: 3;
            animation: contentFadeIn 0.6s ease-out 0.2s both;
        }
        
        @keyframes contentFadeIn {
            0% {
                opacity: 0;
                transform: translateY(20px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* Ícono principal del contenido */
        .delete-icon-main {
            animation: mainIconRotate 3s ease-in-out infinite;
        }
        
        .delete-icon-main i {
            font-size: 4rem;
            color: #ff6b6b;
            filter: drop-shadow(0 5px 15px rgba(255, 107, 107, 0.3));
        }
        
        @keyframes mainIconRotate {
            0%, 100% {
                transform: rotate(0deg) scale(1);
            }
            25% {
                transform: rotate(-5deg) scale(1.05);
            }
            75% {
                transform: rotate(5deg) scale(1.05);
            }
        }
        
        /* Botones animados */
        .delete-btn-cancel, .delete-btn-confirm {
            position: relative;
            z-index: 3;
            transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
            font-weight: 500;
            border-radius: 25px;
            padding: 10px 25px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        }
        
        .delete-btn-cancel:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(255,255,255,0.2);
            background: rgba(255,255,255,0.1) !important;
        }
        
        .delete-btn-confirm:hover {
            transform: translateY(-2px) scale(1.05);
            box-shadow: 0 10px 30px rgba(220, 53, 69, 0.4);
            background: linear-gradient(45deg, #dc3545, #ff6b6b) !important;
        }
        
        .delete-btn-confirm:active {
            animation: confirmPulse 0.3s ease-out;
        }
        
        @keyframes confirmPulse {
            0% {
                transform: scale(1.05);
            }
            50% {
                transform: scale(0.95);
            }
            100% {
                transform: scale(1.05);
            }
        }
        
        /* Efecto de hover en el modal */
        .delete-modal:hover .delete-icon-bg {
            animation-duration: 1.5s;
        }
        
        .delete-modal:hover .particle {
            animation-duration: 2s;
        }
        
        /* Responsive para móviles */
        @media (max-width: 768px) {
            .delete-icon-bg i {
                font-size: 120px;
            }
            
            .delete-icon-main i {
                font-size: 3rem;
            }
        }
        
        /* Efecto de cierre del modal */
        .modal.fade:not(.show) .delete-modal {
            animation: modalSlideOut 0.3s ease-in forwards;
        }
        
        @keyframes modalSlideOut {
            0% {
                opacity: 1;
                transform: scale(1) translateY(0) rotateX(0deg);
            }
            100% {
                opacity: 0;
                transform: scale(0.8) translateY(50px) rotateX(-15deg);
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
                    <i class='bx bx-plus-medical'></i><?= esc($titulo) ?>
                </h1>
                <div class="d-flex gap-2">
                    <a href="<?= site_url('anestesistas/crear') ?>" class="btn btn-primary">
                        <i class='bx bx-plus'></i> Nuevo Anestesista
                    </a>
                    <a href="<?= site_url('anestesistas/disponibles') ?>" class="btn btn-success">
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

            <!-- Anestesistas Table -->
            <div class="glass-card animate-fade-in" style="animation-delay: 0.1s">
                <div class="card-header">
                    <h5><i class='bx bx-table me-2'></i>Listado de Anestesistas</h5>
                    <span class="badge bg-primary"><?= count($anestesistas) ?> registros</span>
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
                                <?php if (empty($anestesistas)): ?>
                                    <tr>
                                        <td colspan="6" class="empty-state">
                                            <i class='bx bx-user-x'></i>
                                            <p>No hay anestesistas registrados</p>
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($anestesistas as $anestesista): ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="me-2">
                                                        <i class='bx bx-user-circle' style="font-size: 1.25rem; color: var(--primary-color);"></i>
                                                    </div>
                                                    <div>
                                                        <strong><?= esc($anestesista['nombre']) ?></strong>
                                                        <div class="text-muted small" style="font-size: 0.75rem;">
                                                            <?= esc($anestesista['email']) ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td><?= esc($anestesista['dni']) ?></td>
                                            <td><?= esc($anestesista['especialidad']) ?></td>
                                            <td>
                                                <span class="badge bg-<?= $anestesista['disponibilidad'] ?>">
                                                    <?= ucfirst(str_replace('_', ' ', $anestesista['disponibilidad'])) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="d-flex flex-column">
                                                    <small><?= esc($anestesista['telefono']) ?></small>
                                                    <small class="text-muted"><?= esc($anestesista['direccion'] ?? 'Sin dirección') ?></small>
                                                </div>
                                            </td>
                                            <td class="text-end">
                                                <div class="d-flex justify-content-end">
                                                    <a href="<?= site_url('anestesistas/ver/'.$anestesista['id']) ?>" class="btn-action btn-view" title="Ver detalles">
                                                        <i class='bx bx-show'></i>
                                                    </a>
                                                    <a href="<?= site_url('anestesistas/editar/'.$anestesista['id']) ?>" class="btn-action btn-edit" title="Editar">
                                                        <i class='bx bx-edit'></i>
                                                    </a>
                                                    <button type="button"
                                                        class="btn-action btn-delete"
                                                        onclick="confirmarEliminacion('<?= $anestesista['id'] ?>', '<?= esc($anestesista['nombre']) ?>')"
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

    <!-- Modal de Eliminación -->
    <div class="modal fade" id="modalEliminar" tabindex="-1" aria-labelledby="modalEliminarLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content delete-modal" style="background: linear-gradient(135deg, #1a1a1a 0%, #2d1b1b 100%); color: white; border: 1px solid #dc3545; border-radius: 15px; overflow: hidden; position: relative;">
                
                <!-- Ícono gigante animado de fondo -->
                <div class="delete-icon-bg">
                    <i class="bx bx-trash"></i>
                </div>
                
                <!-- Efectos de partículas -->
                <div class="particles">
                    <div class="particle"></div>
                    <div class="particle"></div>
                    <div class="particle"></div>
                    <div class="particle"></div>
                    <div class="particle"></div>
                </div>
                
                <div class="modal-header border-bottom" style="border-color: rgba(220, 53, 69, 0.3) !important; background: rgba(0,0,0,0.3); backdrop-filter: blur(10px);">
                    <h5 class="modal-title delete-title" style="font-size: 1.2rem; font-weight: 600;">
                        <i class='bx bx-trash me-2 delete-icon-header'></i>
                        <span id="modalEliminarTitulo">Confirmar Eliminación</span>
                    </h5>
                    <button type="button" class="btn-close btn-close-white close-btn-animated" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <form id="formEliminar" method="post" action="">
                    <?= csrf_field() ?>
                    <input type="hidden" id="idElemento" name="id">
                    <div class="modal-body py-4 text-center" style="background: rgba(0,0,0,0.2); backdrop-filter: blur(5px);">
                        <div class="delete-content">
                            <div class="delete-icon-main mb-3">
                                <i class='bx bx-user-x'></i>
                            </div>
                            <p style="font-size: 1rem; margin-bottom: 1rem; font-weight: 500;">
                                ¿Está seguro que desea eliminar <strong><span id="nombreElemento"></span></strong>?
                            </p>
                            <p style="font-size: 0.85rem; color: #ff6b6b; margin-bottom: 0;" id="mensajeAdicional">
                                Esta acción no se puede deshacer y se perderán todos los datos asociados.
                            </p>
                        </div>
                    </div>
                    <div class="modal-footer border-top justify-content-center" style="border-color: rgba(220, 53, 69, 0.3) !important; background: rgba(0,0,0,0.3); backdrop-filter: blur(10px);">
                        <button type="button" class="btn btn-outline-light delete-btn-cancel me-2" data-bs-dismiss="modal">
                            <i class='bx bx-x me-1'></i> Cancelar
                        </button>
                        <button type="submit" class="btn btn-danger delete-btn-confirm">
                            <i class='bx bx-trash me-1'></i> Eliminar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

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

        // Función para configurar el modal de eliminación
        function configurarModalEliminar(options) {
            const {
                idElemento,
                nombreElemento,
                actionUrl,
                titulo = 'Confirmar Eliminación',
                mensajeAdicional = 'Esta acción no se puede deshacer y se perderán todos los datos asociados.',
                icono = 'bx-user-x'
            } = options;

            document.getElementById('modalEliminarTitulo').textContent = titulo;
            document.getElementById('idElemento').value = idElemento;
            document.getElementById('nombreElemento').textContent = nombreElemento;
            document.getElementById('mensajeAdicional').textContent = mensajeAdicional;
            document.getElementById('formEliminar').action = actionUrl;
            document.querySelector('.delete-icon-main i').className = `bx ${icono}`;
        }

        // Función para eliminar anestesistas
        function confirmarEliminacion(id, nombre) {
            configurarModalEliminar({
                idElemento: id,
                nombreElemento: nombre,
                actionUrl: `<?= site_url('anestesistas/eliminar') ?>`,
                titulo: 'Confirmar Eliminación de Anestesista',
                mensajeAdicional: 'Esta acción no se puede deshacer. Todos los datos del anestesista se perderán permanentemente.',
                icono: 'bx-user-x'
            });
            
            // Mostrar el modal
            const modal = new bootstrap.Modal(document.getElementById('modalEliminar'));
            modal.show();
        }

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