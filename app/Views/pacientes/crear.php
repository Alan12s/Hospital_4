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

        .alert-success {
            background-color: rgba(34, 197, 94, 0.1);
            color: var(--success-color);
            border-left: 4px solid var(--success-color);
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
                    <i class='bx bx-user-plus'></i><?= esc($titulo) ?>
                </h1>
                <a href="<?= site_url('pacientes') ?>" class="btn btn-secondary">
                    <i class='bx bx-arrow-back'></i> Volver a Pacientes
                </a>
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

            <!-- Form Card -->
            <div class="glass-card animate-fade-in" style="animation-delay: 0.1s">
                <div class="card-header">
                    <h5><i class='bx bx-user-plus me-2'></i>Nuevo Paciente</h5>
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

                    <?= form_open('pacientes/add') ?>
                        <?= csrf_field() ?>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="nombre" class="form-label required-field">
                                    <i class='bx bx-user me-1'></i>Nombre Completo
                                </label>
                                <input type="text" 
                                       class="form-control <?= (isset($validation) && $validation->hasError('nombre')) ? 'is-invalid' : '' ?>" 
                                       id="nombre" 
                                       name="nombre" 
                                       value="<?= old('nombre') ?>" 
                                       placeholder="Ingrese el nombre completo"
                                       required>
                                <?php if (isset($validation) && $validation->hasError('nombre')): ?>
                                    <div class="invalid-feedback"><?= esc($validation->getError('nombre')) ?></div>
                                <?php endif; ?>
                            </div>

                            <div class="col-md-6">
                                <label for="dni" class="form-label required-field">
                                    <i class='bx bx-id-card me-1'></i>DNI
                                </label>
                                <input type="text" 
                                       class="form-control <?= (isset($validation) && $validation->hasError('dni')) ? 'is-invalid' : '' ?>" 
                                       id="dni" 
                                       name="dni" 
                                       value="<?= old('dni') ?>" 
                                       placeholder="Ej: 12345678"
                                       required>
                                <?php if (isset($validation) && $validation->hasError('dni')): ?>
                                    <div class="invalid-feedback"><?= esc($validation->getError('dni')) ?></div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="email" class="form-label required-field">
                                    <i class='bx bx-envelope me-1'></i>Email
                                </label>
                                <input type="email" 
                                       class="form-control <?= (isset($validation) && $validation->hasError('email')) ? 'is-invalid' : '' ?>" 
                                       id="email" 
                                       name="email" 
                                       value="<?= old('email') ?>" 
                                       placeholder="paciente@ejemplo.com"
                                       required>
                                <?php if (isset($validation) && $validation->hasError('email')): ?>
                                    <div class="invalid-feedback"><?= esc($validation->getError('email')) ?></div>
                                <?php endif; ?>
                            </div>

                            <div class="col-md-6">
                                <label for="telefono" class="form-label required-field">
                                    <i class='bx bx-phone me-1'></i>Teléfono
                                </label>
                                <input type="text" 
                                       class="form-control <?= (isset($validation) && $validation->hasError('telefono')) ? 'is-invalid' : '' ?>" 
                                       id="telefono" 
                                       name="telefono" 
                                       value="<?= old('telefono') ?>" 
                                       placeholder="Ej: 1122334455"
                                       required>
                                <?php if (isset($validation) && $validation->hasError('telefono')): ?>
                                    <div class="invalid-feedback"><?= esc($validation->getError('telefono')) ?></div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="fecha_nacimiento" class="form-label required-field">
                                    <i class='bx bx-calendar me-1'></i>Fecha de Nacimiento
                                </label>
                                <input type="date" 
                                       class="form-control <?= (isset($validation) && $validation->hasError('fecha_nacimiento')) ? 'is-invalid' : '' ?>" 
                                       id="fecha_nacimiento" 
                                       name="fecha_nacimiento" 
                                       value="<?= old('fecha_nacimiento') ?>" 
                                       required>
                                <?php if (isset($validation) && $validation->hasError('fecha_nacimiento')): ?>
                                    <div class="invalid-feedback"><?= esc($validation->getError('fecha_nacimiento')) ?></div>
                                <?php endif; ?>
                            </div>

                            <div class="col-md-6">
                                <label for="obra_social" class="form-label required-field">
                                    <i class='bx bx-health me-1'></i>Obra Social
                                </label>
                                <input type="text" 
                                       class="form-control <?= (isset($validation) && $validation->hasError('obra_social')) ? 'is-invalid' : '' ?>" 
                                       id="obra_social" 
                                       name="obra_social" 
                                       value="<?= old('obra_social') ?>" 
                                       placeholder="Nombre de la obra social"
                                       required>
                                <?php if (isset($validation) && $validation->hasError('obra_social')): ?>
                                    <div class="invalid-feedback"><?= esc($validation->getError('obra_social')) ?></div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="departamento" class="form-label required-field">
                                    <i class='bx bx-building me-1'></i>Departamento
                                </label>
                                <select class="form-select <?= (isset($validation) && $validation->hasError('departamento')) ? 'is-invalid' : '' ?>" 
                                        id="departamento" 
                                        name="departamento" 
                                        required>
                                    <option value="">Seleccione un departamento</option>
                                    <option value="caucete" <?= old('departamento') == 'caucete' ? 'selected' : '' ?>>Caucete</option>
                                    <option value="25 de mayo" <?= old('departamento') == '25 de mayo' ? 'selected' : '' ?>>25 de Mayo</option>
                                    <option value="santa rosa" <?= old('departamento') == 'santa rosa' ? 'selected' : '' ?>>Santa Rosa</option>
                                    <option value="sarmiento" <?= old('departamento') == 'sarmiento' ? 'selected' : '' ?>>Sarmiento</option>
                                    <option value="san martin" <?= old('departamento') == 'san martin' ? 'selected' : '' ?>>San Martín</option>
                                    <option value="santa lucia" <?= old('departamento') == 'santa lucia' ? 'selected' : '' ?>>Santa Lucía</option>
                                    <option value="capital" <?= old('departamento') == 'capital' ? 'selected' : '' ?>>Capital</option>
                                </select>
                                <?php if (isset($validation) && $validation->hasError('departamento')): ?>
                                    <div class="invalid-feedback"><?= esc($validation->getError('departamento')) ?></div>
                                <?php endif; ?>
                            </div>

                            <div class="col-md-6">
                                <label for="direccion" class="form-label required-field">
                                    <i class='bx bx-map me-1'></i>Dirección
                                </label>
                                <input type="text" 
                                       class="form-control <?= (isset($validation) && $validation->hasError('direccion')) ? 'is-invalid' : '' ?>" 
                                       id="direccion" 
                                       name="direccion" 
                                       value="<?= old('direccion') ?>" 
                                       placeholder="Dirección completa"
                                       required>
                                <?php if (isset($validation) && $validation->hasError('direccion')): ?>
                                    <div class="invalid-feedback"><?= esc($validation->getError('direccion')) ?></div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-12">
                                <label for="historial_medico" class="form-label required-field">
                                    <i class='bx bx-clipboard me-1'></i>Historial Médico
                                </label>
                                <textarea class="form-control <?= (isset($validation) && $validation->hasError('historial_medico')) ? 'is-invalid' : '' ?>" 
                                          id="historial_medico" 
                                          name="historial_medico" 
                                          rows="3" 
                                          placeholder="Información relevante del historial médico"
                                          required><?= old('historial_medico') ?></textarea>
                                <?php if (isset($validation) && $validation->hasError('historial_medico')): ?>
                                    <div class="invalid-feedback"><?= esc($validation->getError('historial_medico')) ?></div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="<?= site_url('pacientes') ?>" class="btn btn-secondary">
                                <i class='bx bx-x me-1'></i>Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class='bx bx-save me-1'></i>Registrar Paciente
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

            // Validación DNI solo números
            const dniInput = document.getElementById('dni');
            if (dniInput) {
                dniInput.addEventListener('input', function() {
                    this.value = this.value.replace(/[^0-9]/g, '');
                });
            }

            // Validación teléfono solo números
            const telefonoInput = document.getElementById('telefono');
            if (telefonoInput) {
                telefonoInput.addEventListener('input', function() {
                    this.value = this.value.replace(/[^0-9]/g, '');
                });
            }
        });
    </script>
    <?= $this->include('includes/footer') ?>
</body>
</html>