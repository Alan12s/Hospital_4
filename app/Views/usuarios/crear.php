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

        .btn-secondary {
            background-color: var(--gray-600);
            border-color: var(--gray-700);
            font-weight: 600;
            letter-spacing: 0.5px;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
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
            gap: 0.75rem;
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

        /* ============= FORM STYLES ============= */
        .form-control {
            border: 2px solid var(--gray-300);
            border-radius: var(--border-radius-sm);
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background-color: var(--white);
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(106, 27, 154, 0.25);
        }

        .form-label {
            font-weight: 600;
            color: var(--gray-700);
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }

        .form-select {
            border: 2px solid var(--gray-300);
            border-radius: var(--border-radius-sm);
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background-color: var(--white);
        }

        .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(106, 27, 154, 0.25);
        }

        .invalid-feedback {
            display: block;
            font-size: 0.85rem;
            color: var(--danger-color);
            margin-top: 0.25rem;
        }

        .form-control.is-invalid {
            border-color: var(--danger-color);
            box-shadow: 0 0 0 0.2rem rgba(239, 68, 68, 0.25);
        }

        /* ============= BUTTON STYLES ============= */
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-dark);
            font-weight: 600;
            letter-spacing: 0.5px;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
            padding: 0.75rem 1.5rem;
        }

        .btn-primary:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }

        /* ============= ALERT STYLES ============= */
        .alert {
            border-radius: var(--border-radius-sm);
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
            border: none;
            box-shadow: var(--shadow-sm);
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
            
            .btn-secondary {
                width: 100%;
                justify-content: center;
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
                <a href="<?= site_url('usuarios') ?>" class="btn btn-secondary">
                    <i class='bx bx-arrow-back'></i> Volver al Listado
                </a>
            </div>

            <!-- Flash Messages -->
            <?php if (session()->get('errors')): ?>
                <div class="alert alert-danger alert-dismissible fade show glass-card">
                    <div class="d-flex align-items-start">
                        <i class='bx bx-error-circle me-2 mt-1'></i>
                        <div>
                            <strong>Errores de validación:</strong>
                            <ul class="mb-0 mt-2">
                                <?php foreach (session()->get('errors') as $error): ?>
                                    <li><?= esc($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
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
                    <h5><i class='bx bx-user-plus'></i>Información del Usuario</h5>
                </div>
                <div class="card-body p-4">
                    <?= form_open('usuarios/add', ['class' => 'needs-validation', 'novalidate' => '']) ?>
                        <div class="row">
                            <!-- Información Personal -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="nombre" class="form-label">
                                        <i class='bx bx-user me-1'></i>Nombre *
                                    </label>
                                    <input type="text" 
                                           class="form-control <?= session()->get('errors.nombre') ? 'is-invalid' : '' ?>" 
                                           id="nombre" 
                                           name="nombre" 
                                           value="<?= old('nombre') ?>" 
                                           required
                                           placeholder="Ingrese el nombre">
                                    <?php if (session()->get('errors.nombre')): ?>
                                        <div class="invalid-feedback">
                                            <?= esc(session()->get('errors.nombre')) ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="apellidos" class="form-label">
                                        <i class='bx bx-user me-1'></i>Apellidos *
                                    </label>
                                    <input type="text" 
                                           class="form-control <?= session()->get('errors.apellidos') ? 'is-invalid' : '' ?>" 
                                           id="apellidos" 
                                           name="apellidos" 
                                           value="<?= old('apellidos') ?>" 
                                           required
                                           placeholder="Ingrese los apellidos">
                                    <?php if (session()->get('errors.apellidos')): ?>
                                        <div class="invalid-feedback">
                                            <?= esc(session()->get('errors.apellidos')) ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">
                                        <i class='bx bx-envelope me-1'></i>Email *
                                    </label>
                                    <input type="email" 
                                           class="form-control <?= session()->get('errors.email') ? 'is-invalid' : '' ?>" 
                                           id="email" 
                                           name="email" 
                                           value="<?= old('email') ?>" 
                                           required
                                           placeholder="ejemplo@correo.com">
                                    <?php if (session()->get('errors.email')): ?>
                                        <div class="invalid-feedback">
                                            <?= esc(session()->get('errors.email')) ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="username" class="form-label">
                                        <i class='bx bx-at me-1'></i>Nombre de Usuario *
                                    </label>
                                    <input type="text" 
                                           class="form-control <?= session()->get('errors.username') ? 'is-invalid' : '' ?>" 
                                           id="username" 
                                           name="username" 
                                           value="<?= old('username') ?>" 
                                           required
                                           placeholder="nombre_usuario">
                                    <?php if (session()->get('errors.username')): ?>
                                        <div class="invalid-feedback">
                                            <?= esc(session()->get('errors.username')) ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Credenciales -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="password" class="form-label">
                                        <i class='bx bx-lock me-1'></i>Contraseña *
                                    </label>
                                    <input type="password" 
                                           class="form-control <?= session()->get('errors.password') ? 'is-invalid' : '' ?>" 
                                           id="password" 
                                           name="password" 
                                           required
                                           placeholder="Mínimo 6 caracteres">
                                    <?php if (session()->get('errors.password')): ?>
                                        <div class="invalid-feedback">
                                            <?= esc(session()->get('errors.password')) ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="password_confirm" class="form-label">
                                        <i class='bx bx-lock-alt me-1'></i>Confirmar Contraseña *
                                    </label>
                                    <input type="password" 
                                           class="form-control <?= session()->get('errors.password_confirm') ? 'is-invalid' : '' ?>" 
                                           id="password_confirm" 
                                           name="password_confirm" 
                                           required
                                           placeholder="Repita la contraseña">
                                    <?php if (session()->get('errors.password_confirm')): ?>
                                        <div class="invalid-feedback">
                                            <?= esc(session()->get('errors.password_confirm')) ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Configuración -->
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="rol" class="form-label">
                                        <i class='bx bx-shield me-1'></i>Rol *
                                    </label>
                                    <select class="form-select <?= session()->get('errors.rol') ? 'is-invalid' : '' ?>" 
                                            id="rol" 
                                            name="rol" 
                                            required>
                                        <option value="">Seleccione un rol</option>
                                        <option value="administrador" <?= old('rol') == 'administrador' ? 'selected' : '' ?>>Administrador</option>
                                        <option value="medico" <?= old('rol') == 'medico' ? 'selected' : '' ?>>Médico</option>
                                        <option value="enfermero" <?= old('rol') == 'enfermero' ? 'selected' : '' ?>>Enfermero</option>
                                        <option value="supervisor" <?= old('rol') == 'supervisor' ? 'selected' : '' ?>>Supervisor</option>
                                        <option value="usuario" <?= old('rol') == 'usuario' ? 'selected' : '' ?>>Usuario</option>
                                    </select>
                                    <?php if (session()->get('errors.rol')): ?>
                                        <div class="invalid-feedback">
                                            <?= esc(session()->get('errors.rol')) ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="estado" class="form-label">
                                        <i class='bx bx-check-circle me-1'></i>Estado *
                                    </label>
                                    <select class="form-select <?= session()->get('errors.estado') ? 'is-invalid' : '' ?>" 
                                            id="estado" 
                                            name="estado" 
                                            required>
                                        <option value="">Seleccione el estado</option>
                                        <option value="1" <?= old('estado') == '1' ? 'selected' : '' ?>>Activo</option>
                                        <option value="0" <?= old('estado') == '0' ? 'selected' : '' ?>>Inactivo</option>
                                    </select>
                                    <?php if (session()->get('errors.estado')): ?>
                                        <div class="invalid-feedback">
                                            <?= esc(session()->get('errors.estado')) ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="<?= site_url('usuarios') ?>" class="btn btn-secondary">
                                <i class='bx bx-x'></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class='bx bx-save'></i> Crear Usuario
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

            // Validación de confirmación de contraseña
            const password = document.getElementById('password');
            const passwordConfirm = document.getElementById('password_confirm');

            function validatePassword() {
                if (password.value !== passwordConfirm.value) {
                    passwordConfirm.setCustomValidity('Las contraseñas no coinciden');
                } else {
                    passwordConfirm.setCustomValidity('');
                }
            }

            password.addEventListener('change', validatePassword);
            passwordConfirm.addEventListener('keyup', validatePassword);

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