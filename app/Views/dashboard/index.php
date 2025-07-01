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
    
    /* ============= NUEVAS SECCIONES ============= */
    .innovative-section {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    /* Widget de Personal Médico */
    .staff-widget {
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

    .staff-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }

    .staff-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 1rem;
        background: rgba(255, 255, 255, 0.7);
        border-radius: var(--border-radius-sm);
        transition: all 0.3s ease;
    }

    .staff-item:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow-sm);
    }

    .staff-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 0.5rem;
        color: white;
        font-size: 1.2rem;
    }

    .staff-value {
        font-weight: 700;
        color: var(--gray-800);
        margin-top: 0.25rem;
    }

    /* Monitor de Cirugías */
    .surgery-monitor {
        padding: 1.5rem;
        border-radius: var(--border-radius);
        box-shadow: var(--shadow-lg);
    }

    .surgery-list {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .surgery-item {
        display: flex;
        align-items: center;
        padding: 0.75rem;
        background: rgba(255, 255, 255, 0.7);
        border-radius: var(--border-radius-sm);
        transition: all 0.3s ease;
    }

    .surgery-item:hover {
        transform: translateX(5px);
        background: rgba(255, 255, 255, 0.9);
    }

    .surgery-status {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        margin-right: 0.75rem;
    }

    .surgery-status.scheduled {
        background-color: var(--info-color);
    }

    .surgery-status.in-progress {
        background-color: var(--warning-color);
    }

    .surgery-status.completed {
        background-color: var(--success-color);
    }

    .surgery-content {
        flex: 1;
    }

    .surgery-title {
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--gray-800);
    }

    .surgery-time {
        font-size: 0.8rem;
        color: var(--gray-500);
    }

    /* Widget del Clima */
    .weather-widget {
        padding: 1.5rem;
        border-radius: var(--border-radius);
        box-shadow: var(--shadow-lg);
        text-align: center;
        transition: all 0.3s ease;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.3);
    }

    .weather-widget:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
    }

    .weather-icon {
        font-size: 3rem;
        margin-bottom: 0.5rem;
        animation: float 3s ease-in-out infinite;
    }

    .weather-temp {
        font-size: 2rem;
        font-weight: 700;
        color: var(--gray-800);
        margin-bottom: 0.25rem;
    }

    .weather-desc {
        color: var(--gray-600);
        font-size: 0.9rem;
    }

    .weather-location {
        color: var(--gray-500);
        font-size: 0.8rem;
        margin-top: 0.5rem;
    }

    .weather-details {
        display: flex;
        justify-content: space-around;
        margin-top: 1rem;
        font-size: 0.8rem;
    }

    .weather-detail {
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .weather-detail i {
        margin-bottom: 0.25rem;
    }

    @keyframes float {
        0%, 100% {
            transform: translateY(0);
        }
        50% {
            transform: translateY(-5px);
        }
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
                    
                    <?php if(in_array($rol, ['administrador', 'supervisor', 'enfermero', 'cirujano'])): ?>
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

                    <?php if(in_array($rol, ['administrador', 'supervisor', 'enfermero', 'cirujano'])): ?>
                    <a href="<?= base_url('instrumentistas') ?>" class="action-card">
                        <div class="action-card-icon">
                            <i class="bx bx-bar-chart-alt-2"></i>
                        </div>
                        <div class="action-card-title">Instrumentistas</div>
                    </a>
                    <?php endif; ?>

                    <?php if(in_array($rol, ['administrador', 'supervisor','cirujano'])): ?>
                    <a href="<?= base_url('anestesistas') ?>" class="action-card">
                        <div class="action-card-icon">
                            <i class="bx bx-plus-medical"></i>
                        </div>
                        <div class="action-card-title">Anestesistas</div>
                    </a>
                    <?php endif; ?>
                </div>

                <!-- Nuevas Secciones Innovadoras -->
                <div class="innovative-section animate-fade-in" style="animation-delay: 0.7s">
                   <!-- Widget de Personal Médico -->
<div class="staff-widget glass-card-solid">
    <h3 class="section-title"><i class='bx bx-user'></i> Personal Médico</h3>
    <div class="staff-grid">
        <div class="staff-item">
            <div class="staff-icon bg-primary">
                <i class='bx bx-user-md'></i>
            </div>
            <span>Cirujanos</span>
            <div class="staff-value" id="staff-cirujanos"><?= $cirujanos_count ?? '0' ?></div>
        </div>
        <div class="staff-item">
            <div class="staff-icon bg-success">
                <i class='bx bx-plus-medical'></i>
            </div>
            <span>Anestesistas</span>
            <div class="staff-value" id="staff-anestesistas"><?= $anestesistas_count ?? '0' ?></div>
        </div>
        <div class="staff-item">
            <div class="staff-icon bg-info">
                <i class='bx bx-heart'></i>
            </div>
            <span>Enfermeros</span>
             <div class="staff-value" id="staff-enfermeros"><?= $enfermeros_count ?? '0' ?></div>
        </div>
        <div class="staff-item">
            <div class="staff-icon bg-secondary">
                <i class='bx bx-briefcase'></i>
            </div>
            <span>Instrumentistas</span>
            <div class="staff-value" id="staff-instrumentistas"><?= $instrumentistas_count ?? '0' ?></div>
        </div>
    </div>
</div>

                    <!-- Monitor de Cirugías -->
                    <div class="surgery-monitor glass-card-solid">
                        <h3 class="section-title"><i class='bx bx-calendar'></i> Próximas Cirugías</h3>
                        <div class="surgery-list" id="surgery-list">
                            <!-- Las cirugías se cargarán dinámicamente -->
                            <div class="surgery-item">
                                <div class="surgery-status scheduled"></div>
                                <div class="surgery-content">
                                    <div class="surgery-title">Cargando cirugías...</div>
                                    <div class="surgery-time">Por favor espere</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sección Inferior -->
                <div class="innovative-section animate-fade-in" style="animation-delay: 0.8s">
                    <!-- Widget del Clima -->
                    <div class="weather-widget glass-card-solid">
                        <div class="weather-icon" id="weather-icon"><?= $clima['icono'] ?? '🌤️' ?></div>
                        <div class="weather-temp" id="weather-temp"><?= $clima['temperatura'] ?? '22' ?>°C</div>
                        <div class="weather-desc" id="weather-desc"><?= $clima['descripcion'] ?? 'Parcialmente nublado' ?></div>
                        <div class="weather-location" id="weather-location"><?= $clima['ciudad'] ?? 'San Juan, Argentina' ?></div>
                        <div class="weather-details">
                            <div class="weather-detail">
                                <i class='bx bx-water'></i>
                                <span id="weather-humidity">65%</span>
                            </div>
                            <div class="weather-detail">
                                <i class='bx bx-wind'></i>
                                <span id="weather-wind">15 km/h</span>
                            </div>
                            <div class="weather-detail">
                                <i class='bx bx-cloud'></i>
                                <span id="weather-clouds">25%</span>
                            </div>
                        </div>
                    </div>

                    <!-- Widget de Insumos Críticos -->
                    <div class="surgery-monitor glass-card-solid">
                        <h3 class="section-title"><i class='bx bx-package'></i> Insumos Críticos</h3>
                        <div class="surgery-list" id="critical-supplies">
                            <div class="surgery-item">
                                <div class="surgery-status warning"></div>
                                <div class="surgery-content">
                                    <div class="surgery-title">Cargando información...</div>
                                    <div class="surgery-time">Por favor espere</div>
                                </div>
                            </div>
                        </div>
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
                            labels: ['Traumotologia', 'Urologia', 'Odontologia', 'General'],
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
                'pacientes-count': data.pacientes_count,
                'staff-cirujanos': data.cirujanos_count,
                'staff-enfermeros': data.enfermeros_disponibles
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

        // 9. Carga de información de personal médico
        async function loadStaffInfo() {
            try {
                // Cargar anestesistas
                const anestesistasResponse = await fetchWithRetry('<?= base_url('anestesistas/getCount') ?>');
                if (anestesistasResponse.status === 'success') {
                    document.getElementById('staff-anestesistas').textContent = anestesistasResponse.count;
                }

                // Cargar instrumentistas
                const instrumentistasResponse = await fetchWithRetry('<?= base_url('instrumentistas/getCount') ?>');
                if (instrumentistasResponse.status === 'success') {
                    document.getElementById('staff-instrumentistas').textContent = instrumentistasResponse.count;
                }
            } catch (error) {
                console.error('Error al cargar información de personal:', error);
            }
        }

        async function loadUpcomingSurgeries() {
    const surgeryList = document.getElementById('surgery-list');
    if (!surgeryList) return;

    // Mostrar estado de carga
    surgeryList.innerHTML = `
        <div class="surgery-item">
            <div class="surgery-status in-progress"></div>
            <div class="surgery-content">
                <div class="surgery-title">Cargando cirugías...</div>
                <div class="surgery-time">Por favor espere</div>
            </div>
        </div>
    `;

    try {
        const response = await fetch('<?= site_url('inicio/getUpcomingSurgeries') ?>', {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        const data = await response.json();

        if (!response.ok || data.status !== 'success') {
            throw new Error(data.message || 'Error en la respuesta del servidor');
        }

        if (data.surgeries.length > 0) {
            surgeryList.innerHTML = '';
            data.surgeries.forEach(surgery => {
                const statusClass = getSurgeryStatusClass(surgery.status);
                const statusText = getSurgeryStatusText(surgery.status);
                
                const surgeryItem = document.createElement('div');
                surgeryItem.className = 'surgery-item';
                surgeryItem.innerHTML = `
                    <div class="surgery-status ${statusClass}"></div>
                    <div class="surgery-content">
                        <div class="surgery-title">${surgery.title}</div>
                        <div class="surgery-time">${surgery.date} • ${surgery.time} • Dr. ${surgery.surgeon} • ${statusText}</div>
                    </div>
                `;
                surgeryList.appendChild(surgeryItem);
            });
        } else {
            surgeryList.innerHTML = `
                <div class="surgery-item">
                    <div class="surgery-status scheduled"></div>
                    <div class="surgery-content">
                        <div class="surgery-title">No hay cirugías programadas</div>
                        <div class="surgery-time">No se encontraron cirugías próximas</div>
                    </div>
                </div>
            `;
        }
    } catch (error) {
        console.error('Error al cargar cirugías:', error);
        surgeryList.innerHTML = `
            <div class="surgery-item">
                <div class="surgery-status warning"></div>
                <div class="surgery-content">
                    <div class="surgery-title">Error al cargar cirugías</div>
                    <div class="surgery-time">${error.message}</div>
                </div>
            </div>
        `;
    }
}

// Funciones auxiliares
function getSurgeryStatusClass(status) {
    switch(status) {
        case 'programado': return 'scheduled';
        case 'en_progreso': return 'in-progress';
        case 'completado': return 'completed';
        case 'cancelado': return 'cancelled';
        default: return 'scheduled';
    }
}

function getSurgeryStatusText(status) {
    switch(status) {
        case 'programado': return 'Programado';
        case 'en_progreso': return 'En progreso';
        case 'completado': return 'Completado';
        case 'cancelado': return 'Cancelado';
        default: return status;
    }
}

function getSurgeryStatusText(status) {
    switch(status) {
        case 'programado': return 'Programado';
        case 'en_progreso': return 'En progreso';
        case 'completado': return 'Completado';
        case 'cancelado': return 'Cancelado';
        default: return status;
    }
}

        // 11. Carga de insumos críticos
        // Función para cargar insumos críticos
async function loadCriticalSupplies() {
    const container = document.getElementById('critical-supplies');
    if (!container) return;

    try {
        container.innerHTML = `
            <div class="surgery-item">
                <div class="surgery-status in-progress"></div>
                <div class="surgery-content">
                    <div class="surgery-title">Cargando insumos...</div>
                    <div class="surgery-time">Por favor espere</div>
                </div>
            </div>
        `;

        const response = await fetch('<?= site_url('inicio/getCriticalSupplies') ?>', {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });

        if (!response.ok) throw new Error(`Error HTTP! Estado: ${response.status}`);

        const data = await response.json();
        
        if (data.status !== 'success') {
            throw new Error(data.message || 'Respuesta inesperada del servidor');
        }

        container.innerHTML = data.supplies.length ? '' : `
            <div class="surgery-item">
                <div class="surgery-status success"></div>
                <div class="surgery-content">
                    <div class="surgery-title">No hay insumos críticos</div>
                    <div class="surgery-time">Todos los insumos están con cantidad suficiente</div>
                </div>
            </div>
        `;

        data.supplies.forEach(item => {
            const urgency = item.cantidad < 20 ? 'danger' : item.cantidad < 35 ? 'warning' : 'info';
            
            container.innerHTML += `
                <div class="surgery-item">
                    <div class="surgery-status ${urgency}"></div>
                    <div class="surgery-content">
                        <div class="surgery-title">${item.nombre} (${item.codigo})</div>
                        <div class="surgery-time">
                            <span class="badge bg-${urgency}">${item.cantidad} unidades</span>
                            Tipo: ${item.tipo} | Ubicación: ${item.ubicacion}
                        </div>
                    </div>
                </div>
            `;
        });

    } catch (error) {
        console.error('Error:', error);
        container.innerHTML = `
            <div class="surgery-item">
                <div class="surgery-status danger"></div>
                <div class="surgery-content">
                    <div class="surgery-title">Error al cargar insumos</div>
                    <div class="surgery-time">
                        ${error.message}
                        <button onclick="loadCriticalSupplies()" class="btn btn-sm btn-outline-primary mt-2">
                            <i class="bx bx-refresh"></i> Reintentar
                        </button>
                    </div>
                </div>
            </div>
        `;
    }
}

        // 12. Widget del clima con API real (simulada aquí)
        async function updateWeather() {
            try {
                // En un entorno real, aquí harías una llamada a una API del clima
                // Por simplicidad, usaremos datos simulados
                const weatherData = {
                    icon: '🌤️',
                    temp: 22,
                    description: 'Parcialmente nublado',
                    city: 'San Juan, Argentina',
                    humidity: Math.floor(Math.random() * 30) + 50, // 50-80%
                    wind: Math.floor(Math.random() * 10) + 10, // 10-20 km/h
                    clouds: Math.floor(Math.random() * 50) // 0-50%
                };

                // Actualizar UI
                document.getElementById('weather-icon').textContent = weatherData.icon;
                document.getElementById('weather-temp').textContent = `${weatherData.temp}°C`;
                document.getElementById('weather-desc').textContent = weatherData.description;
                document.getElementById('weather-location').textContent = weatherData.city;
                document.getElementById('weather-humidity').textContent = `${weatherData.humidity}%`;
                document.getElementById('weather-wind').textContent = `${weatherData.wind} km/h`;
                document.getElementById('weather-clouds').textContent = `${weatherData.clouds}%`;

                // Cambiar el icono según las condiciones
                updateWeatherIcon(weatherData.description, weatherData.temp);
                
            } catch (error) {
                console.error('Error al actualizar el clima:', error);
            }
        }

        function updateWeatherIcon(description, temp) {
            const iconElement = document.getElementById('weather-icon');
            if (!iconElement) return;

            const desc = description.toLowerCase();
            
            if (desc.includes('soleado') || desc.includes('despejado')) {
                iconElement.textContent = '☀️';
            } else if (desc.includes('nublado')) {
                iconElement.textContent = '☁️';
            } else if (desc.includes('lluvia')) {
                iconElement.textContent = '🌧️';
            } else if (desc.includes('tormenta')) {
                iconElement.textContent = '⛈️';
            } else if (desc.includes('nieve')) {
                iconElement.textContent = '❄️';
            } else if (desc.includes('niebla') || desc.includes('neblina')) {
                iconElement.textContent = '🌫️';
            } else if (temp > 30) {
                iconElement.textContent = '🔥';
            } else if (temp < 10) {
                iconElement.textContent = '❄️';
            } else {
                iconElement.textContent = '🌤️';
            }
        }

        // 13. Efectos de hover mejorados
        function setupHoverEffects() {
            // Efecto de elevación en tarjetas
            const cards = document.querySelectorAll('.stats-card, .action-card, .chart-container, .staff-widget, .surgery-monitor');
            
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

        // 14. Animación de actualización de estadísticas
        function animateStatUpdate() {
            const statCards = document.querySelectorAll('.stats-card');
            statCards.forEach(card => {
                card.classList.add('stat-updated');
                setTimeout(() => {
                    card.classList.remove('stat-updated');
                }, 1000);
            });
        }

        // 15. Inicialización con manejo de errores
        try {
            initCharts();
            loadStats();
            loadWeeklyStats();
            loadSpecialtyStats();
            loadNotifications();
            loadStaffInfo();
            loadUpcomingSurgeries();
            loadCriticalSupplies();
            updateWeather();
            setupHoverEffects();

            // Actualización periódica
            setInterval(loadStats, 30000);
            setInterval(loadUpcomingSurgeries, 60000);
            setInterval(loadCriticalSupplies, 60000);
            setInterval(loadNotifications, 60000);
            setInterval(updateWeather, 3600000); // Actualizar clima cada hora
            
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