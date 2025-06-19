<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($page_title) ? $page_title : 'Dashboard' ?></title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- CSS Integrado -->
    <style>
    /* ============= VARIABLES CSS ============= */
    :root {
        --primary-color: #2563eb;
        --primary-dark: #1d4ed8;
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

    /* ============= GLASSMORPHISM CARDS ============= */
    .glass-card {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: var(--border-radius);
        box-shadow: var(--shadow-lg);
    }

    .glass-card-solid {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        border-radius: var(--border-radius);
        box-shadow: var(--shadow-xl);
    }
    
    /* ============= HEADER STYLES ============= */
    .dashboard-header {
        margin-bottom: 2rem;
        padding: 2rem;
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
        border-radius: var(--border-radius);
        box-shadow: var(--shadow-lg);
    }

    .dashboard-header h1 {
        font-size: 2.5rem;
        font-weight: 800;
        color: var(--white);
        margin-bottom: 0.5rem;
        text-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .dashboard-header .subtitle {
        color: rgba(255, 255, 255, 0.8);
        font-size: 1.1rem;
        font-weight: 500;
        margin-bottom: 1rem;
    }

    .current-time {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: rgba(255, 255, 255, 0.2);
        padding: 0.5rem 1rem;
        border-radius: 50px;
        color: var(--white);
        font-weight: 600;
    }
    
    /* ============= STATS CARDS ============= */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }
    
    .stats-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        border-radius: var(--border-radius);
        padding: 2rem;
        box-shadow: var(--shadow-xl);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }

    .stats-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
    }
    
    .stats-card:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    }
    
    .stats-card-icon {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
        position: relative;
    }

    .stats-card-icon.primary {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
    }

    .stats-card-icon.success {
        background: linear-gradient(135deg, var(--success-color), var(--secondary-color));
    }

    .stats-card-icon.warning {
        background: linear-gradient(135deg, var(--warning-color), var(--accent-color));
    }

    .stats-card-icon.info {
        background: linear-gradient(135deg, var(--info-color), var(--primary-color));
    }
    
    .stats-card-icon i {
        font-size: 1.5rem;
        color: var(--white);
    }
    
    .stats-card-number {
        font-size: 2.5rem;
        font-weight: 800;
        color: var(--gray-800);
        margin-bottom: 0.5rem;
        line-height: 1;
    }
    
    .stats-card-label {
        color: var(--gray-600);
        font-size: 0.9rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .stats-card-trend {
        display: flex;
        align-items: center;
        gap: 0.25rem;
        margin-top: 0.5rem;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .trend-up {
        color: var(--success-color);
    }

    .trend-down {
        color: var(--danger-color);
    }
    
    /* ============= CHARTS SECTION ============= */
    .charts-section {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .chart-container {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        border-radius: var(--border-radius);
        padding: 1.5rem;
        box-shadow: var(--shadow-xl);
        height: 350px;
    }

    .chart-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }

    .chart-title {
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--gray-800);
    }

    /* Contenedor del canvas */
    .chart-canvas-container {
        position: relative;
        height: calc(100% - 40px);
        width: 100%;
    }

    /* Estilos para los canvas de Chart.js */
    .chart-container canvas {
        width: 100% !important;
        height: 100% !important;
    }
    
    /* ============= QUICK ACTIONS ============= */
    .quick-actions {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 2rem;
    }
    
    .action-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.3);
        border-radius: var(--border-radius);
        padding: 1.5rem;
        text-align: center;
        box-shadow: var(--shadow-lg);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        text-decoration: none;
        color: var(--gray-800);
        position: relative;
        overflow: hidden;
    }

    .action-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: left 0.5s;
    }

    .action-card:hover::before {
        left: 100%;
    }
    
    .action-card:hover {
        transform: translateY(-8px);
        box-shadow: var(--shadow-xl);
        color: var(--gray-800);
        text-decoration: none;
    }
    
    .action-card-icon {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    }

    .action-card-icon i {
        font-size: 1.2rem;
        color: var(--white);
    }

    .action-card-title {
        font-weight: 600;
        font-size: 0.9rem;
    }
    
    /* ============= NUEVOS ESTILOS PARA LAS SECCIONES INNOVADORAS ============= */
    .innovative-section {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .status-board {
        padding: 1.5rem;
        border-radius: var(--border-radius);
        box-shadow: var(--shadow-lg);
    }

    .section-title {
        font-size: 1.2rem;
        font-weight: 700;
        color: var(--gray-800);
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .status-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }

    .status-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 1rem;
        background: rgba(255, 255, 255, 0.7);
        border-radius: var(--border-radius-sm);
        transition: all 0.3s ease;
    }

    .status-item:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow-sm);
    }

    .status-indicator {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        margin-bottom: 0.5rem;
    }

    .status-indicator.active {
        background-color: var(--success-color);
        box-shadow: 0 0 10px var(--success-color);
    }

    .status-indicator.warning {
        background-color: var(--warning-color);
        box-shadow: 0 0 10px var(--warning-color);
    }

    .status-value {
        font-weight: 700;
        color: var(--gray-800);
        margin-top: 0.25rem;
    }

    /* Monitor de Recursos */
    .resource-monitor {
        padding: 1.5rem;
        border-radius: var(--border-radius);
        box-shadow: var(--shadow-lg);
    }

    .resource-bars {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .resource-item {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .progress-container {
        height: 8px;
        background: var(--gray-200);
        border-radius: 4px;
        overflow: hidden;
        position: relative;
    }

    .progress-bar {
        height: 100%;
        border-radius: 4px;
        background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
        position: relative;
    }

    .progress-bar.warning {
        background: linear-gradient(90deg, var(--warning-color), var(--accent-color));
    }

    .resource-percent {
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--gray-600);
        align-self: flex-end;
    }

    /* Live Feed */
    .live-feed {
        display: grid;
        grid-template-columns: 2fr 1fr;
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .live-feed-container {
        padding: 1.5rem;
        border-radius: var(--border-radius);
        box-shadow: var(--shadow-lg);
    }

    .feed-items {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
        max-height: 300px;
        overflow-y: auto;
        padding-right: 0.5rem;
    }

    .feed-item {
        padding: 0.75rem;
        background: rgba(255, 255, 255, 0.7);
        border-radius: var(--border-radius-sm);
        display: flex;
        align-items: center;
        gap: 0.75rem;
        animation: slideIn 0.5s ease-out;
    }

    .feed-item-icon {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 0.9rem;
    }

    .feed-item-content {
        flex: 1;
    }

    .feed-item-text {
        font-size: 0.9rem;
        color: var(--gray-700);
    }

    .feed-item-time {
        font-size: 0.7rem;
        color: var(--gray-500);
    }

    /* Animaciones */
    .pulse-animation {
        animation: pulse 2s infinite;
    }

    .animate-progress {
        animation: progressAnimation 1.5s ease-out;
    }

    @keyframes progressAnimation {
        from {
            width: 0%;
        }
        to {
            width: 100%;
        }
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateX(20px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    /* ============= ESTILOS PARA NOTIFICACIONES TOAST ============= */
    .notification-toast {
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 1rem 1.5rem;
        border-radius: var(--border-radius);
        box-shadow: var(--shadow-xl);
        display: flex;
        align-items: center;
        gap: 1rem;
        z-index: 9999;
        transform: translateX(150%);
        transition: transform 0.3s ease-out;
        max-width: 350px;
    }

    .notification-toast.show {
        transform: translateX(0);
    }

    .notification-toast.primary {
        background: var(--primary-color);
        color: white;
    }

    .notification-toast.success {
        background: var(--success-color);
        color: white;
    }

    .notification-toast.warning {
        background: var(--warning-color);
        color: white;
    }

    .notification-toast.info {
        background: var(--info-color);
        color: white;
    }

    .notification-toast.danger {
        background: var(--danger-color);
        color: white;
    }

    .toast-icon {
        font-size: 1.5rem;
    }

    .toast-message {
        flex: 1;
        font-weight: 500;
    }

    .toast-close {
        margin-left: 1rem;
        cursor: pointer;
        font-size: 1.2rem;
        opacity: 0.8;
        transition: opacity 0.2s;
    }

    .toast-close:hover {
        opacity: 1;
    }

    /* ============= RESPONSIVE ============= */
    @media (max-width: 1200px) {
        .innovative-section, .charts-section {
            grid-template-columns: 1fr;
        }
        
        .chart-container {
            height: 400px;
        }
    }

    @media (max-width: 768px) {
        .main-content {
            margin-left: 0;
            width: 100%;
            padding: 1rem;
        }

        .main-content::before {
            left: 0;
        }
        
        .stats-grid {
            grid-template-columns: 1fr;
        }

        .quick-actions {
            grid-template-columns: repeat(2, 1fr);
        }

        .dashboard-header h1 {
            font-size: 2rem;
        }
        
        .chart-container {
            height: 350px;
        }
    }

    @media (max-width: 576px) {
        .quick-actions {
            grid-template-columns: 1fr;
        }

        .dashboard-header {
            padding: 1.5rem;
        }

        .dashboard-header h1 {
            font-size: 1.8rem;
        }
        
        .chart-container {
            height: 300px;
        }
    }
    </style>
</head>
<body>
    <div class="wrapper">
        <!-- Incluir el sidebar -->
        <?= $this->include('includes/sidebar.php') ?>
        
        <!-- Contenido principal -->
        <div class="main-content">
            <div class="content-container">
                <!-- Mensajes flash -->
                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show animate-fade-in">
                        <i class="bx bx-check-circle me-2"></i>
                        <?= session()->getFlashdata('success') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show animate-fade-in">
                        <i class="bx bx-error-circle me-2"></i>
                        <?= session()->getFlashdata('error') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <!-- Encabezado del Dashboard -->
                <div class="dashboard-header animate-fade-in">
                    <h1>¡Bienvenido, <?= esc($nombre) ?>!</h1>
                    <p class="subtitle">Sistema de Gestión Hospitalaria - Panel de Control</p>
                    <div class="current-time">
                        <i class="bx bx-time-five"></i>
                        <span id="current-time"><?= date('H:i:s') ?></span>
                        <span class="ms-2"><?= $fecha_actual ?></span>
                    </div>
                </div>
                
                <!-- Estadísticas Principales -->
                <div class="stats-grid">
                    <div class="stats-card animate-fade-in" style="animation-delay: 0.1s">
                        <div class="stats-card-icon primary">
                            <i class="bx bx-calendar-check"></i>
                        </div>
                        <div class="stats-card-number" id="turnos-hoy"><?= isset($turnos_hoy) ? $turnos_hoy : '0' ?></div>
                        <div class="stats-card-label">Cirugías Hoy</div>
                        <div class="stats-card-trend trend-up">
                            <i class="bx bx-trending-up"></i>
                            <span>+12% vs ayer</span>
                        </div>
                    </div>
                    
                    <div class="stats-card animate-fade-in" style="animation-delay: 0.2s">
                        <div class="stats-card-icon success">
                            <i class="bx bx-user-plus"></i>
                        </div>
                        <div class="stats-card-number" id="cirujanos-count"><?= isset($cirujanos_count) ? $cirujanos_count : '0' ?></div>
                        <div class="stats-card-label">Cirujanos Activos</div>
                        <div class="stats-card-trend trend-up">
                            <i class="bx bx-trending-up"></i>
                            <span>+3 nuevos</span>
                        </div>
                    </div>
                    
                    <div class="stats-card animate-fade-in" style="animation-delay: 0.3s">
                        <div class="stats-card-icon warning">
                            <i class="bx bx-heart"></i>
                        </div>
                        <div class="stats-card-number" id="enfermeros-count"><?= isset($enfermeros_disponibles) ? $enfermeros_disponibles : '0' ?></div>
                        <div class="stats-card-label">Enfermeros Disponibles</div>
                        <div class="stats-card-trend trend-up">
                            <i class="bx bx-trending-up"></i>
                            <span>85% disponibilidad</span>
                        </div>
                    </div>
                    
                    <div class="stats-card animate-fade-in" style="animation-delay: 0.4s">
                        <div class="stats-card-icon info">
                            <i class="bx bx-clipboard"></i>
                        </div>
                        <div class="stats-card-number" id="pacientes-count"><?= isset($pacientes_count) ? $pacientes_count : '0' ?></div>
                        <div class="stats-card-label">Pacientes Registrados</div>
                        <div class="stats-card-trend trend-up">
                            <i class="bx bx-trending-up"></i>
                            <span>+8% este mes</span>
                        </div>
                    </div>
                </div>

                <!-- Sección de Gráficos -->
                <div class="charts-section animate-fade-in" style="animation-delay: 0.5s">
                    <div class="chart-container">
                        <div class="chart-header">
                            <h3 class="chart-title">Cirugías de la Semana</h3>
                        </div>
                        <div class="chart-canvas-container">
                            <canvas id="weeklyChart"></canvas>
                        </div>
                    </div>
                    
                    <div class="chart-container">
                        <div class="chart-header">
                            <h3 class="chart-title">Distribución por Especialidad</h3>
                        </div>
                        <div class="chart-canvas-container">
                            <canvas id="specialtyChart"></canvas>
                        </div>
                    </div>
                </div>
                
                <!-- Acciones Rápidas -->
                <div class="quick-actions animate-fade-in" style="animation-delay: 0.6s">
                    <?php if(in_array($rol, ['administrador', 'supervisor', 'cirujano', 'enfermero'])): ?>
                    <a href="<?= base_url('turnos') ?>" class="action-card">
                        <div class="action-card-icon">
                            <i class="bx bx-calendar-plus"></i>
                        </div>
                        <div class="action-card-title">Programar Cirugía</div>
                    </a>
                    <?php endif; ?>
                    
                    <?php if(in_array($rol, ['administrador', 'supervisor', 'cirujano'])): ?>
                    <a href="<?= base_url('insumos') ?>" class="action-card">
                        <div class="action-card-icon">
                            <i class="bx bx-package"></i>
                            <?php if(isset($insumos_bajo_stock) && $insumos_bajo_stock > 0): ?>
                            <span class="notification-badge"><?= $insumos_bajo_stock ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="action-card-title">Gestionar Insumos</div>
                    </a>
                    <?php endif; ?>
                    
                    <?php if(in_array($rol, ['administrador', 'supervisor', 'cirujano', 'enfermero'])): ?>
                    <a href="<?= base_url('pacientes') ?>" class="action-card">
                        <div class="action-card-icon">
                            <i class="bx bx-user-plus"></i>
                        </div>
                        <div class="action-card-title">Registrar Paciente</div>
                    </a>
                    <?php endif; ?>
                    
                    <?php if(in_array($rol, ['administrador', 'supervisor'])): ?>
                    <a href="<?= base_url('usuarios') ?>" class="action-card">
                        <div class="action-card-icon">
                            <i class="bx bx-cog"></i>
                        </div>
                        <div class="action-card-title">Administrar Sistema</div>
                    </a>
                    <?php endif; ?>

                    <a href="<?= base_url('instrumentistas') ?>" class="action-card">
                        <div class="action-card-icon">
                            <i class="bx bx-bar-chart-alt-2"></i>
                        </div>
                        <div class="action-card-title">Instrumentistas</div>
                    </a>

                    <a href="<?= base_url('Anestesistas') ?>" class="action-card">
                        <div class="action-card-icon">
                            <i class="bx bx-plus-medical"></i>
                        </div>
                        <div class="action-card-title">Anestesistas</div>
                    </a>
                </div>

                <!-- Nuevas Secciones Innovadoras -->
                <div class="innovative-section animate-fade-in" style="animation-delay: 0.7s">
                    <!-- Panel de Estado del Hospital -->
                    <div class="status-board glass-card-solid">
                        <h3 class="section-title"><i class='bx bx-pulse'></i> Estado del Hospital</h3>
                        <div class="status-grid">
                            <div class="status-item">
                                <div class="status-indicator active pulse-animation"></div>
                                <span>Emergencias</span>
                                <div class="status-value">Operativo</div>
                            </div>
                            <div class="status-item">
                                <div class="status-indicator active"></div>
                                <span>Quirófanos</span>
                                <div class="status-value">3/5 en uso</div>
                            </div>
                            <div class="status-item">
                                <div class="status-indicator warning pulse-animation"></div>
                                <span>Equipos</span>
                                <div class="status-value">1 en mantenimiento</div>
                            </div>
                            <div class="status-item">
                                <div class="status-indicator active"></div>
                                <span>Personal</span>
                                <div class="status-value">85% disponible</div>
                            </div>
                        </div>
                    </div>

                    <!-- Monitor de Recursos -->
                    <div class="resource-monitor glass-card-solid">
                        <h3 class="section-title"><i class='bx bx-trending-up'></i> Monitor de Recursos</h3>
                        <div class="resource-bars">
                            <div class="resource-item">
                                <span>Uso de Quirófanos</span>
                                <div class="progress-container">
                                    <div class="progress-bar animate-progress" style="width: 75%"></div>
                                </div>
                                <span class="resource-percent">75%</span>
                            </div>
                            <div class="resource-item">
                                <span>Disponibilidad de Personal</span>
                                <div class="progress-container">
                                    <div class="progress-bar animate-progress" style="width: 85%"></div>
                                </div>
                                <span class="resource-percent">85%</span>
                            </div>
                            <div class="resource-item">
                                <span>Insumos Críticos</span>
                                <div class="progress-container">
                                    <div class="progress-bar animate-progress warning" style="width: 35%"></div>
                                </div>
                                <span class="resource-percent">35%</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Feed de Actividad en Tiempo Real -->
                <div class="live-feed animate-fade-in" style="animation-delay: 0.8s">
                    <div class="live-feed-container glass-card-solid">
                        <h3 class="section-title"><i class='bx bx-radar'></i> Actividad en Tiempo Real</h3>
                        <div class="feed-items" id="live-feed-items">
                            <!-- Los elementos se agregarán dinámicamente via JavaScript -->
                        </div>
                    </div>

                    <!-- Widget del Clima -->
                    <div class="weather-widget glass-card-solid">
                        <div class="weather-icon"><?= $clima['icono'] ?? '🌤️' ?></div>
                        <div class="weather-temp"><?= $clima['temperatura'] ?? '22' ?>°C</div>
                        <div class="weather-desc"><?= $clima['descripcion'] ?? 'Parcialmente nublado' ?></div>
                        <div class="weather-desc"><?= $clima['ciudad'] ?? 'San Juan, Argentina' ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- JavaScript personalizado -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Dashboard JS inicializado correctamente');
        
        // 1. Reloj en tiempo real mejorado
        function updateClock() {
            const now = new Date();
            const timeElement = document.getElementById('current-time');
            if (timeElement) {
                const options = { hour: '2-digit', minute: '2-digit', second: '2-digit' };
                timeElement.textContent = now.toLocaleTimeString('es-AR', options);
            }
        }
        setInterval(updateClock, 1000);
        updateClock();

        // 2. Inicialización de gráficos con manejo de errores
        function initCharts() {
            try {
                // Gráfico semanal
                const weeklyCtx = document.getElementById('weeklyChart');
                if (weeklyCtx) {
                    window.weeklyChart = new Chart(weeklyCtx, {
                        type: 'bar',
                        data: {
                            labels: ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'],
                            datasets: [{
                                label: 'Cirugías',
                                data: [0, 0, 0, 0, 0, 0, 0],
                                backgroundColor: 'rgba(54, 162, 235, 0.7)',
                                borderColor: 'rgba(54, 162, 235, 1)',
                                borderWidth: 1,
                                borderRadius: 4
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    grid: { color: 'rgba(0, 0, 0, 0.05)' }
                                },
                                x: {
                                    grid: { display: false }
                                }
                            },
                            plugins: {
                                legend: { display: false },
                                tooltip: {
                                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                    titleFont: { size: 14, weight: 'bold' },
                                    bodyFont: { size: 12 },
                                    padding: 12,
                                    cornerRadius: 8
                                }
                            }
                        }
                    });
                }

                // Gráfico de especialidades
                const specialtyCtx = document.getElementById('specialtyChart');
                if (specialtyCtx) {
                    window.specialtyChart = new Chart(specialtyCtx, {
                        type: 'doughnut',
                        data: {
                            labels: ['Cardiovascular', 'Neurología', 'Ortopedia', 'General'],
                            datasets: [{
                                data: [0, 0, 0, 0],
                                backgroundColor: [
                                    'rgba(255, 99, 132, 0.7)',
                                    'rgba(54, 162, 235, 0.7)',
                                    'rgba(255, 206, 86, 0.7)',
                                    'rgba(75, 192, 192, 0.7)'
                                ],
                                borderColor: [
                                    'rgba(255, 99, 132, 1)',
                                    'rgba(54, 162, 235, 1)',
                                    'rgba(255, 206, 86, 1)',
                                    'rgba(75, 192, 192, 1)'
                                ],
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            cutout: '70%',
                            plugins: {
                                legend: {
                                    position: 'right',
                                    labels: {
                                        padding: 20,
                                        usePointStyle: true,
                                        pointStyle: 'circle'
                                    }
                                },
                                tooltip: {
                                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                    titleFont: { size: 14, weight: 'bold' },
                                    bodyFont: { size: 12 },
                                    padding: 12,
                                    cornerRadius: 8
                                }
                            }
                        }
                    });
                }
            } catch (error) {
                console.error('Error al inicializar gráficos:', error);
            }
        }

        // 3. Carga de datos con reintentos
        async function fetchWithRetry(url, options = {}, retries = 3) {
            try {
                const response = await fetch(url, {
                    ...options,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Content-Type': 'application/json',
                        ...options.headers
                    }
                });
                
                if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                return await response.json();
            } catch (error) {
                if (retries > 0) {
                    console.log(`Reintentando... (${retries} intentos restantes)`);
                    await new Promise(resolve => setTimeout(resolve, 1000));
                    return fetchWithRetry(url, options, retries - 1);
                }
                throw error;
            }
        }

        // 4. Carga de estadísticas con caché
        let statsCache = null;
        let lastFetchTime = 0;
        const CACHE_DURATION = 15000; // 15 segundos

        async function loadStats() {
            const now = Date.now();
            
            if (statsCache && (now - lastFetchTime) < CACHE_DURATION) {
                updateStatsUI(statsCache);
                return;
            }

            try {
                const data = await fetchWithRetry('<?= base_url('inicio/getStats') ?>');
                if (data.status === 'success') {
                    statsCache = data;
                    lastFetchTime = now;
                    updateStatsUI(data);
                    
                    // Nueva funcionalidad: Mostrar notificación si hay insumos bajos
                    if (data.insumos_bajo_stock > 0) {
                        showNotificationToast({
                            type: 'warning',
                            message: `${data.insumos_bajo_stock} insumos con stock bajo`,
                            icon: 'bx-package'
                        });
                    }
                }
            } catch (error) {
                console.error('Error al cargar estadísticas:', error);
                showErrorToast('No se pudieron cargar las estadísticas. Intentando nuevamente...');
            }
        }

        function updateStatsUI(data) {
            const elements = {
                'turnos-hoy': data.turnos_hoy,
                'cirujanos-count': data.cirujanos_count,
                'enfermeros-count': data.enfermeros_disponibles,
                'pacientes-count': data.pacientes_count
            };

            Object.entries(elements).forEach(([id, value]) => {
                const element = document.getElementById(id);
                if (element) {
                    // Animación de conteo si el valor cambió
                    const currentValue = parseInt(element.textContent) || 0;
                    if (currentValue !== value) {
                        animateValue(element, currentValue, value, 1000);
                    } else {
                        element.textContent = value;
                    }
                }
            });

            animateStatUpdate();
        }

        // 5. Animación de conteo para valores numéricos
        function animateValue(element, start, end, duration) {
            let startTimestamp = null;
            const step = (timestamp) => {
                if (!startTimestamp) startTimestamp = timestamp;
                const progress = Math.min((timestamp - startTimestamp) / duration, 1);
                element.textContent = Math.floor(progress * (end - start) + start);
                if (progress < 1) {
                    window.requestAnimationFrame(step);
                }
            };
            window.requestAnimationFrame(step);
        }

        // 6. Carga de gráficos semanales
        async function loadWeeklyStats() {
            try {
                const data = await fetchWithRetry('<?= base_url('inicio/getWeeklyStats') ?>');
                
                if (window.weeklyChart) {
                    window.weeklyChart.data.labels = data.labels;
                    window.weeklyChart.data.datasets[0].data = data.data;
                    window.weeklyChart.update();
                    
                    // Efecto visual
                    const chartContainer = document.querySelector('#weeklyChart')?.parentElement;
                    if (chartContainer) {
                        chartContainer.classList.add('chart-updated');
                        setTimeout(() => chartContainer.classList.remove('chart-updated'), 1000);
                    }
                }
            } catch (error) {
                console.error('Error al cargar estadísticas semanales:', error);
            }
        }

        // 7. Carga de estadísticas por especialidad
        async function loadSpecialtyStats() {
            try {
                const data = await fetchWithRetry('<?= base_url('inicio/getSpecialtyStats') ?>');
                
                if (window.specialtyChart) {
                    window.specialtyChart.data.labels = data.labels;
                    window.specialtyChart.data.datasets[0].data = data.data;
                    window.specialtyChart.update();
                    
                    // Efecto visual
                    const chartContainer = document.querySelector('#specialtyChart')?.parentElement;
                    if (chartContainer) {
                        chartContainer.classList.add('chart-updated');
                        setTimeout(() => chartContainer.classList.remove('chart-updated'), 1000);
                    }
                }
            } catch (error) {
                console.error('Error al cargar estadísticas por especialidad:', error);
            }
        }

        // 8. Sistema de notificaciones mejorado
        const notificationQueue = [];
        let isShowingNotification = false;

        async function loadNotifications() {
            try {
                const data = await fetchWithRetry('<?= base_url('inicio/getNotifications') ?>');
                
                if (data.status === 'success' && data.notifications.length > 0) {
                    data.notifications.forEach(notification => {
                        notificationQueue.push(notification);
                    });
                    
                    processNotificationQueue();
                }
            } catch (error) {
                console.error('Error al cargar notificaciones:', error);
            }
        }

        function processNotificationQueue() {
            if (!isShowingNotification && notificationQueue.length > 0) {
                isShowingNotification = true;
                const notification = notificationQueue.shift();
                showNotificationToast(notification);
                
                setTimeout(() => {
                    isShowingNotification = false;
                    processNotificationQueue();
                }, 5000);
            }
        }

        function showNotificationToast(notification) {
            const toast = document.createElement('div');
            toast.className = `notification-toast ${notification.type}`;
            toast.innerHTML = `
                <div class="toast-icon">
                    <i class="bx ${notification.icon}"></i>
                </div>
                <div class="toast-message">${notification.message}</div>
                <div class="toast-close">&times;</div>
            `;
            
            document.body.appendChild(toast);
            
            // Mostrar toast con animación
            setTimeout(() => {
                toast.classList.add('show');
            }, 100);
            
            // Cerrar toast al hacer click
            toast.querySelector('.toast-close').addEventListener('click', () => {
                toast.classList.remove('show');
                setTimeout(() => {
                    toast.remove();
                }, 300);
            });
            
            // Auto-ocultar después de 5 segundos
            setTimeout(() => {
                toast.classList.remove('show');
                setTimeout(() => {
                    toast.remove();
                }, 300);
            }, 5000);
        }

        function showErrorToast(message) {
            showNotificationToast({
                type: 'danger',
                message: message,
                icon: 'bx-error'
            });
        }

        // 9. Feed de actividad en tiempo real con WebSocket (simulado)
        function loadLiveFeed() {
            const feedContainer = document.getElementById('live-feed-items');
            if (!feedContainer) return;
            
            // Limpiar solo si está vacío para evitar parpadeo
            if (feedContainer.children.length === 0) {
                feedContainer.innerHTML = '';
            }
            
            // Simular datos de actividad
            const activities = [
                {
                    tipo: 'cirugia_completada',
                    mensaje: 'Cirugía de apendicectomía completada exitosamente',
                    usuario: 'Dr. Pérez',
                    tiempo: 'Hace 2 minutos',
                    icono: 'bx-check-circle',
                    color: 'success'
                },
                {
                    tipo: 'paciente_registrado',
                    mensaje: 'Nuevo paciente registrado en el sistema',
                    usuario: 'Enf. García',
                    tiempo: 'Hace 15 minutos',
                    icono: 'bx-user-plus',
                    color: 'info'
                }
            ];
            
            // Limitar a 10 elementos máximo
            if (feedContainer.children.length + activities.length > 10) {
                const excess = feedContainer.children.length + activities.length - 10;
                for (let i = 0; i < excess; i++) {
                    feedContainer.removeChild(feedContainer.lastChild);
                }
            }
            
            // Añadir nuevos elementos con animación
            activities.forEach(activity => {
                const feedItem = document.createElement('div');
                feedItem.className = 'feed-item';
                feedItem.innerHTML = `
                    <div class="feed-item-icon bg-${activity.color}">
                        <i class="bx ${activity.icono}"></i>
                    </div>
                    <div class="feed-item-content">
                        <div class="feed-item-text">${activity.mensaje}</div>
                        <div class="feed-item-time">${activity.tiempo} • ${activity.usuario}</div>
                    </div>
                `;
                feedContainer.insertBefore(feedItem, feedContainer.firstChild);
            });
        }

        // 10. Widget del clima con geolocalización
        async function updateWeather() {
            const weatherWidget = document.querySelector('.weather-widget');
            if (!weatherWidget) return;
            
            try {
                // Simulación de datos del clima
                const weatherData = {
                    icono: '🌤️',
                    temperatura: 22,
                    descripcion: 'Parcialmente nublado',
                    ciudad: 'San Juan, Argentina'
                };
                
                weatherWidget.querySelector('.weather-icon').textContent = weatherData.icono;
                weatherWidget.querySelector('.weather-temp').textContent = `${weatherData.temperatura}°C`;
                weatherWidget.querySelectorAll('.weather-desc')[0].textContent = weatherData.descripcion;
                weatherWidget.querySelectorAll('.weather-desc')[1].textContent = weatherData.ciudad;
                
            } catch (error) {
                console.error('Error al actualizar el clima:', error);
            }
        }

        // 11. Efectos de hover mejorados
        function setupHoverEffects() {
            // Efecto de elevación en tarjetas
            const cards = document.querySelectorAll('.stats-card, .action-card, .chart-container, .status-board, .resource-monitor');
            
            cards.forEach(card => {
                card.addEventListener('mouseenter', () => {
                    card.style.transform = 'translateY(-5px)';
                    card.style.boxShadow = '0 15px 30px rgba(0, 0, 0, 0.15)';
                    card.style.transition = 'all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1)';
                });
                
                card.addEventListener('mouseleave', () => {
                    card.style.transform = '';
                    card.style.boxShadow = '';
                });
            });
            
            // Efecto en botones de acción rápida
            const actionCards = document.querySelectorAll('.action-card');
            actionCards.forEach(card => {
                card.addEventListener('mouseenter', () => {
                    const icon = card.querySelector('.action-card-icon');
                    if (icon) {
                        icon.style.transform = 'scale(1.1) rotate(5deg)';
                        icon.style.transition = 'transform 0.3s ease';
                    }
                });
                
                card.addEventListener('mouseleave', () => {
                    const icon = card.querySelector('.action-card-icon');
                    if (icon) {
                        icon.style.transform = '';
                    }
                });
            });
        }

        // 12. Animación de actualización de estadísticas
        function animateStatUpdate() {
            const statCards = document.querySelectorAll('.stats-card');
            statCards.forEach(card => {
                card.classList.add('stat-updated');
                setTimeout(() => {
                    card.classList.remove('stat-updated');
                }, 1000);
            });
        }

        // 13. Inicialización con manejo de errores
        try {
            initCharts();
            loadStats();
            loadWeeklyStats();
            loadSpecialtyStats();
            loadNotifications();
            loadLiveFeed();
            updateWeather();
            setupHoverEffects();

            // Actualización periódica
            setInterval(loadStats, 30000);
            setInterval(loadLiveFeed, 15000);
            setInterval(loadNotifications, 60000);
            
            console.log('Todos los módulos se inicializaron correctamente');
        } catch (error) {
            console.error('Error en la inicialización del dashboard:', error);
            showErrorToast('Error al inicializar el dashboard. Recargue la página.');
        }
    });
    </script>
    
    <?= $this->include('includes/footer') ?>
</body>
</html>