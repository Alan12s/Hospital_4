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
        }

        .btn-secondary {
            background-color: var(--gray-600);
            border-color: var(--gray-700);
        }

        .btn-secondary:hover {
            background-color: var(--gray-700);
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
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
            padding: 0.35rem 0.65rem;
            font-size: 0.8rem;
            letter-spacing: 0.5px;
            border-radius: var(--border-radius-sm);
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
        }

        /* ============= ALERT STYLES ============= */
        .alert {
            border-radius: var(--border-radius-sm);
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
            border: none;
            box-shadow: var(--shadow-sm);
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 1rem 1.25rem;
        }

        .alert-success {
            background-color: rgba(34, 197, 94, 0.1);
            color: var(--success-color);
            border-left: 4px solid var(--success-color);
        }

        .alert-danger {
            background-color: rgba(239, 68, 68, 0.1);
            color: var(--danger-color);
            border-left: 4px solid var(--danger-color);
        }

        .alert-warning {
            background-color: rgba(247, 115, 22, 0.1);
            color: var(--warning-color);
            border-left: 4px solid var(--warning-color);
        }

        /* ============= ANIMATIONS ============= */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fadeIn 0.6s ease-out forwards;
            opacity: 0;
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
                    <i class='bx bx-box'></i><?= esc($title) ?>
                </h1>
                <a href="<?= site_url('insumos') ?>" class="btn btn-secondary">
                    <i class='bx bx-arrow-left'></i> Volver a Insumos
                </a>
            </div>

            <!-- Flash Messages -->
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show glass-card animate-fade-in">
                    <i class='bx bx-check-circle'></i>
                    <div><?= esc(session()->getFlashdata('success')) ?></div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show glass-card animate-fade-in">
                    <i class='bx bx-error-circle'></i>
                    <div><?= esc(session()->getFlashdata('error')) ?></div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                </div>
            <?php endif; ?>

            <!-- Detail Card -->
            <div class="glass-card animate-fade-in" style="animation-delay: 0.1s">
                <div class="card-header">
                    <h5><i class='bx bx-detail me-2'></i>Detalles del Insumo</h5>
                </div>
                <div class="card-body">
                    <div class="detail-row">
                        <div class="detail-label">Código:</div>
                        <div class="detail-value">
                            <span class="badge bg-secondary">
                                <i class='bx bx-barcode'></i><?= esc($insumo['codigo'] ?? 'N/A') ?>
                            </span>
                        </div>
                    </div>
                    
                    <div class="detail-row">
                        <div class="detail-label">Nombre:</div>
                        <div class="detail-value"><strong><?= esc($insumo['nombre'] ?? 'N/A') ?></strong></div>
                    </div>
                    
                    <div class="detail-row">
                        <div class="detail-label">Tipo:</div>
                        <div class="detail-value"><?= isset($insumo['tipo']) ? ucfirst($insumo['tipo']) : 'N/A' ?></div>
                    </div>
                    
                    <div class="detail-row">
                        <div class="detail-label">Categoría:</div>
                        <div class="detail-value">
                            <?php 
                            $categoria = isset($insumo['categoria']) ? $insumo['categoria'] : 'descartable';
                            ?>
                            <?php if ($categoria === 'descartable'): ?>
                                <span class="badge bg-warning text-dark">
                                    <i class='bx bx-trash'></i> Descartable
                                </span>
                            <?php elseif ($categoria === 'instrumental'): ?>
                                <span class="badge bg-info text-dark">
                                    <i class='bx bx-wrench'></i> Instrumental
                                </span>
                            <?php else: ?>
                                <span class="badge bg-secondary"><?= ucfirst($categoria) ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="detail-row">
                        <div class="detail-label">Cantidad:</div>
                        <div class="detail-value">
                            <?php 
                            $cantidad = isset($insumo['cantidad']) ? (int)$insumo['cantidad'] : 0;
                            if ($cantidad < 5): ?>
                                <span class="badge bg-danger">
                                    <i class='bx bx-error'></i> <?= $cantidad ?> (Stock Crítico)
                                </span>
                            <?php elseif ($cantidad < 10): ?>
                                <span class="badge bg-warning text-dark">
                                    <i class='bx bx-error-circle'></i> <?= $cantidad ?> (Stock Bajo)
                                </span>
                            <?php else: ?>
                                <span class="badge bg-success">
                                    <i class='bx bx-check'></i> <?= $cantidad ?>
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="detail-row">
                        <div class="detail-label">Ubicación:</div>
                        <div class="detail-value">
                            <i class='bx bx-map'></i> <?= esc($insumo['ubicacion'] ?? 'N/A') ?>
                        </div>
                    </div>
                    
                    <div class="detail-row">
                        <div class="detail-label">Lote:</div>
                        <div class="detail-value">
                            <code><?= isset($insumo['lote']) && !empty($insumo['lote']) ? esc($insumo['lote']) : 'N/A' ?></code>
                        </div>
                    </div>
                    
                    <div class="detail-row">
                        <div class="detail-label">Tiene vencimiento:</div>
                        <div class="detail-value">
                            <?php $tieneVencimiento = isset($insumo['tiene_vencimiento']) ? (bool)$insumo['tiene_vencimiento'] : false; ?>
                            <?php if ($tieneVencimiento): ?>
                                <span class="badge bg-success">
                                    <i class='bx bx-check-circle'></i> Sí
                                </span>
                            <?php else: ?>
                                <span class="badge bg-secondary">
                                    <i class='bx bx-x-circle'></i> No
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <?php 
                    $tieneVencimiento = isset($insumo['tiene_vencimiento']) ? (bool)$insumo['tiene_vencimiento'] : false;
                    $fechaVencimiento = isset($insumo['fecha_vencimiento']) ? $insumo['fecha_vencimiento'] : null;
                    ?>
                    <?php if($tieneVencimiento && $fechaVencimiento): ?>
                    <div class="detail-row">
                        <div class="detail-label">Fecha Vencimiento:</div>
                        <div class="detail-value">
                            <?php 
                            try {
                                $fechaVenc = new DateTime($fechaVencimiento);
                                $hoy = new DateTime();
                                $diasRestantes = $hoy->diff($fechaVenc)->days;
                                $vencido = $fechaVenc < $hoy;
                            } catch (Exception $e) {
                                $fechaVenc = null;
                                $vencido = false;
                                $diasRestantes = 0;
                            }
                            ?>
                            
                            <?php if ($fechaVenc === null): ?>
                                <span class="badge bg-secondary">Fecha inválida</span>
                            <?php elseif ($vencido): ?>
                                <span class="badge bg-danger">
                                    <i class='bx bx-error-alt'></i> <?= $fechaVenc->format('d/m/Y') ?> (VENCIDO)
                                </span>
                            <?php elseif ($diasRestantes <= 30): ?>
                                <span class="badge bg-warning text-dark">
                                    <i class='bx bx-time'></i> <?= $fechaVenc->format('d/m/Y') ?> (<?= $diasRestantes ?> días)
                                </span>
                            <?php else: ?>
                                <span class="badge bg-success">
                                    <i class='bx bx-calendar-check'></i> <?= $fechaVenc->format('d/m/Y') ?>
                                </span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>

                    <div class="button-group">
                        <a href="<?= site_url('insumos/editar/'.$insumo['id_insumo']) ?>" class="btn btn-primary">
                            <i class='bx bx-edit'></i> Editar Insumo
                        </a>
                        <button type="button"
                           class="btn btn-danger"
                           data-bs-toggle="modal"
                           data-bs-target="#modalEliminar"
                           onclick="configurarModalEliminar({
                               idElemento: '<?= $insumo['id_insumo'] ?>',
                               nombreElemento: '<?= esc($insumo['nombre'] ?? 'Insumo') ?>',
                               actionUrl: '<?= site_url('insumos/delete/'.$insumo['id_insumo']) ?>',
                               titulo: 'Eliminar Insumo',
                               mensajeAdicional: 'Si el insumo está siendo utilizado en turnos quirúrgicos, no podrá ser eliminado.',
                               icono: 'bx-trash'
                           })">
                            <i class='bx bx-trash'></i> Eliminar Insumo
                        </button>
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
        });
    </script>
    <?= $this->include('includes/footer') ?>
</body>
</html>