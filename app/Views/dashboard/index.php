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
    <!-- CSS personalizado -->
    <link href="<?= base_url('assets/css/dashboard.css') ?>" rel="stylesheet">
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
                        <div class="weather-icon">🌤️</div>
                        <div class="weather-temp">22°C</div>
                        <div class="weather-desc">Parcialmente nublado</div>
                        <div class="weather-desc">San Juan, Argentina</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- JavaScript personalizado -->
    <script src="<?php echo base_url('assets/js/dashboard.js'); ?>"></script>
    <?= $this->include('includes/footer') ?>
</body>
</html>