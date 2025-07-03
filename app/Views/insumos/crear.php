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
            border-color: var(--gray-600);
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

        /* ============= FORM STYLES ============= */
        .form-label {
            font-weight: 600;
            color: var(--gray-700);
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .form-label.required-field:after {
            content: '*';
            color: var(--danger-color);
            margin-left: 0.25rem;
        }

        .form-control, .form-select {
            border: 2px solid var(--gray-200);
            border-radius: var(--border-radius-sm);
            padding: 0.75rem 1rem;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            background-color: var(--white);
            color: var(--gray-800);
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(106, 27, 154, 0.15);
            background-color: var(--white);
        }

        .form-control.is-invalid {
            border-color: var(--danger-color);
        }

        .invalid-feedback {
            display: block;
            font-size: 0.8rem;
            color: var(--danger-color);
            margin-top: 0.25rem;
        }

        /* ============= CHECKBOX STYLES ============= */
        .form-check {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1rem;
        }

        .form-check-input {
            width: 1.2em;
            height: 1.2em;
            margin-top: 0;
        }

        .form-check-label {
            font-weight: 500;
            color: var(--gray-700);
        }

        /* ============= ALERT STYLES ============= */
        .alert {
            border-radius: var(--border-radius-sm);
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
            border: none;
            box-shadow: var(--shadow-sm);
            padding: 1rem 1.25rem;
        }

        .alert-danger {
            background-color: rgba(239, 68, 68, 0.1);
            color: var(--danger-color);
            border-left: 4px solid var(--danger-color);
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
        }

        @media (max-width: 576px) {
            .main-content {
                padding: 0.75rem;
            }
            
            .card-body {
                padding: 1.5rem;
            }
        }

        /* ============= ANIMATIONS ============= */
        .animate-fade-in {
            animation: fadeIn 0.5s ease-out forwards;
            opacity: 0;
        }

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
                    <i class='bx bx-package'></i><?= esc($title) ?>
                </h1>
                <a href="<?= site_url('insumos') ?>" class="btn btn-secondary">
                    <i class='bx bx-arrow-back'></i> Volver a Insumos
                </a>
            </div>

            <!-- Flash Messages -->
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show glass-card">
                    <div class="d-flex align-items-center">
                        <i class='bx bx-error-circle me-2'></i>
                        <?= esc(session()->getFlashdata('error')) ?>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- Form Card -->
            <div class="glass-card animate-fade-in" style="animation-delay: 0.1s">
                <div class="card-header">
                    <h5><i class='bx bx-plus-circle me-2'></i>Nuevo Insumo</h5>
                </div>
                <div class="card-body">
                    <!-- Mostrar todos los errores de validación -->
                    <?php if (isset($validation) && $validation->getErrors()): ?>
                        <div class="alert alert-danger">
                            <div class="d-flex align-items-center mb-2">
                                <i class='bx bx-error-circle me-2'></i>
                                <strong>Por favor, corrija los siguientes errores:</strong>
                            </div>
                            <ul class="mb-0">
                                <?php foreach ($validation->getErrors() as $error): ?>
                                    <li><?= esc($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <?= form_open('insumos/add') ?>
                        <?= csrf_field() ?>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="codigo" class="form-label">
                                    <i class='bx bx-qr me-1'></i>Código
                                </label>
                                <input type="text" 
                                       class="form-control <?= (isset($validation) && $validation->hasError('codigo')) ? 'is-invalid' : '' ?>" 
                                       id="codigo" 
                                       name="codigo" 
                                       value="<?= old('codigo') ?>" 
                                       placeholder="Ingrese el código del insumo">
                                <?php if (isset($validation) && $validation->hasError('codigo')): ?>
                                    <div class="invalid-feedback"><?= esc($validation->getError('codigo')) ?></div>
                                <?php endif; ?>
                            </div>

                            <div class="col-md-6">
                                <label for="nombre" class="form-label required-field">
                                    <i class='bx bx-package me-1'></i>Nombre del Insumo
                                </label>
                                <input type="text" 
                                       class="form-control <?= (isset($validation) && $validation->hasError('nombre')) ? 'is-invalid' : '' ?>" 
                                       id="nombre" 
                                       name="nombre" 
                                       value="<?= old('nombre') ?>" 
                                       placeholder="Ingrese el nombre del insumo"
                                       required>
                                <?php if (isset($validation) && $validation->hasError('nombre')): ?>
                                    <div class="invalid-feedback"><?= esc($validation->getError('nombre')) ?></div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="categoria" class="form-label required-field">
                                    <i class='bx bx-category me-1'></i>Categoría
                                </label>
                                <select class="form-select <?= (isset($validation) && $validation->hasError('categoria')) ? 'is-invalid' : '' ?>" 
                                        id="categoria" 
                                        name="categoria" 
                                        required>
                                    <option value="">Seleccionar categoría</option>
                                    <option value="descartable" <?= (old('categoria') == 'descartable') ? 'selected' : '' ?>>Descartable</option>
                                    <option value="instrumental" <?= (old('categoria') == 'instrumental') ? 'selected' : '' ?>>Instrumental</option>
                                </select>
                                <?php if (isset($validation) && $validation->hasError('categoria')): ?>
                                    <div class="invalid-feedback"><?= esc($validation->getError('categoria')) ?></div>
                                <?php endif; ?>
                            </div>

                            <div class="col-md-6">
                                <label for="tipo" class="form-label required-field">
                                    <i class='bx bx-cube me-1'></i>Tipo
                                </label>
                                <select class="form-select <?= (isset($validation) && $validation->hasError('tipo')) ? 'is-invalid' : '' ?>" 
                                        id="tipo" 
                                        name="tipo" 
                                        required>
                                    <option value="">Seleccionar tipo</option>
                                    <!-- Opciones generales de tipo -->
                                    <option value="Consumible" <?= (old('tipo') == 'Consumible') ? 'selected' : '' ?>>Consumible</option>
                                    <option value="Material quirúrgico" <?= (old('tipo') == 'Material quirúrgico') ? 'selected' : '' ?>>Material quirúrgico</option>
                                    <option value="Protección personal" <?= (old('tipo') == 'Protección personal') ? 'selected' : '' ?>>Protección personal</option>
                                    <option value="Instrumental" <?= (old('tipo') == 'Instrumental') ? 'selected' : '' ?>>Instrumental</option>
                                    <option value="Dispositivo médico" <?= (old('tipo') == 'Dispositivo médico') ? 'selected' : '' ?>>Dispositivo médico</option>
                                    <option value="Medicamento" <?= (old('tipo') == 'Medicamento') ? 'selected' : '' ?>>Medicamento</option>
                                    <option value="Material de curación" <?= (old('tipo') == 'Material de curación') ? 'selected' : '' ?>>Material de curación</option>
                                    <option value="Equipo quirúrgico" <?= (old('tipo') == 'Equipo quirúrgico') ? 'selected' : '' ?>>Equipo quirúrgico</option>
                                    <option value="Sutura" <?= (old('tipo') == 'Sutura') ? 'selected' : '' ?>>Sutura</option>
                                    <option value="Antiséptico" <?= (old('tipo') == 'Antiséptico') ? 'selected' : '' ?>>Antiséptico</option>
                                </select>
                                <?php if (isset($validation) && $validation->hasError('tipo')): ?>
                                    <div class="invalid-feedback"><?= esc($validation->getError('tipo')) ?></div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="cantidad" class="form-label required-field">
                                    <i class='bx bx-layer me-1'></i>Cantidad
                                </label>
                                <input type="number" 
                                       class="form-control <?= (isset($validation) && $validation->hasError('cantidad')) ? 'is-invalid' : '' ?>" 
                                       id="cantidad" 
                                       name="cantidad" 
                                       value="<?= old('cantidad') ?>" 
                                       placeholder="Ingrese la cantidad disponible"
                                       min="0"
                                       required>
                                <?php if (isset($validation) && $validation->hasError('cantidad')): ?>
                                    <div class="invalid-feedback"><?= esc($validation->getError('cantidad')) ?></div>
                                <?php endif; ?>
                            </div>

                            <div class="col-md-6">
                                <label for="ubicacion" class="form-label required-field">
                                    <i class='bx bx-map me-1'></i>Ubicación
                                </label>
                                <input type="text" 
                                       class="form-control <?= (isset($validation) && $validation->hasError('ubicacion')) ? 'is-invalid' : '' ?>" 
                                       id="ubicacion" 
                                       name="ubicacion" 
                                       value="<?= old('ubicacion') ?>" 
                                       placeholder="Ingrese la ubicación del insumo"
                                       required>
                                <?php if (isset($validation) && $validation->hasError('ubicacion')): ?>
                                    <div class="invalid-feedback"><?= esc($validation->getError('ubicacion')) ?></div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="lote" class="form-label">
                                    <i class='bx bx-barcode me-1'></i>Lote
                                </label>
                                <input type="text" 
                                       class="form-control" 
                                       id="lote" 
                                       name="lote" 
                                       value="<?= old('lote') ?>" 
                                       placeholder="Ingrese el número de lote">
                            </div>

                            <div class="col-md-6">
                                <div class="form-check mt-4">
                                    <input class="form-check-input" 
                                           type="checkbox" 
                                           id="tiene_vencimiento" 
                                           name="tiene_vencimiento" 
                                           value="1" 
                                           <?= old('tiene_vencimiento') ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="tiene_vencimiento">
                                        ¿Tiene fecha de vencimiento?
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3" id="fecha_vencimiento_container" style="display: <?= old('tiene_vencimiento') ? 'block' : 'none' ?>;">
                            <div class="col-md-6">
                                <label for="fecha_vencimiento" class="form-label">
                                    <i class='bx bx-calendar me-1'></i>Fecha de Vencimiento
                                </label>
                                <input type="date" 
                                       class="form-control <?= (isset($validation) && $validation->hasError('fecha_vencimiento')) ? 'is-invalid' : '' ?>" 
                                       id="fecha_vencimiento" 
                                       name="fecha_vencimiento" 
                                       value="<?= old('fecha_vencimiento') ?>">
                                <?php if (isset($validation) && $validation->hasError('fecha_vencimiento')): ?>
                                    <div class="invalid-feedback"><?= esc($validation->getError('fecha_vencimiento')) ?></div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="<?= site_url('insumos') ?>" class="btn btn-secondary">
                                <i class='bx bx-x me-1'></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class='bx bx-save me-1'></i> Crear Insumo
                            </button>
                        </div>
                    <?= form_close() ?>
                </div>
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

            // Mostrar/ocultar campo de fecha de vencimiento
            const checkVencimiento = document.getElementById('tiene_vencimiento');
            const fechaVencimientoContainer = document.getElementById('fecha_vencimiento_container');

            checkVencimiento.addEventListener('change', function() {
                fechaVencimientoContainer.style.display = this.checked ? 'block' : 'none';
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
</body>
</html>