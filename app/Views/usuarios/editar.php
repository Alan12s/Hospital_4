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

        /* ============= FORM STYLES ============= */
        .form-container {
            padding: 2rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            font-weight: 600;
            color: var(--gray-700);
            margin-bottom: 0.5rem;
            display: block;
        }

        .form-control {
            border-radius: var(--border-radius-sm);
            padding: 0.75rem 1rem;
            border: 1px solid var(--gray-300);
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(106, 27, 154, 0.25);
        }

        .form-text {
            font-size: 0.8rem;
            color: var(--gray-500);
            margin-top: 0.25rem;
        }

        .is-invalid {
            border-color: var(--danger-color);
        }

        .invalid-feedback {
            color: var(--danger-color);
            font-size: 0.8rem;
            margin-top: 0.25rem;
        }

        .btn-action {
            padding: 0.5rem 1rem;
            border-radius: var(--border-radius-sm);
            margin-right: 0.5rem;
            transition: all 0.2s ease;
            font-size: 0.9rem;
            border: none;
            box-shadow: var(--shadow-sm);
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-back {
            background-color: var(--gray-200);
            color: var(--gray-700);
        }

        .btn-back:hover {
            background-color: var(--gray-300);
            transform: translateY(-2px);
            box-shadow: var(--shadow);
        }

        .btn-save {
            background-color: var(--primary-color);
            color: white;
        }

        .btn-save:hover {
            background-color: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: var(--shadow);
        }

        /* ============= RESPONSIVE ============= */
        @media (max-width: 992px) {
            .form-container {
                padding: 1.5rem;
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
            
            .form-container {
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
                    <i class='bx bx-user'></i><?= esc($titulo) ?>
                </h1>
                <a href="<?= site_url('usuarios') ?>" class="btn btn-primary">
                    <i class='bx bx-arrow-back'></i> Volver
                </a>
            </div>

            <!-- Flash Messages -->
            <?php if (session()->getFlashdata('mensaje')): ?>
                <div class="alert alert-success alert-dismissible fade show glass-card">
                    <div class="d-flex align-items-center">
                        <i class='bx bx-check-circle me-2'></i>
                        <?= esc(session()->getFlashdata('mensaje')) ?>
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

            <!-- Mostrar errores de validación -->
            <?php if (session()->getFlashdata('errors')): ?>
                <div class="alert alert-danger alert-dismissible fade show glass-card">
                    <div class="d-flex align-items-center">
                        <i class='bx bx-error-circle me-2'></i>
                        <div>
                            <strong>Por favor corrige los siguientes errores:</strong>
                            <ul class="mb-0 mt-2">
                                <?php foreach (session()->getFlashdata('errors') as $error): ?>
                                    <li><?= esc($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- Form Card -->
            <div class="glass-card animate-fade-in" style="animation-delay: 0.1s">
                <div class="card-header">
                    <h5><i class='bx bx-edit me-2'></i>Formulario de Edición</h5>
                    <span class="badge bg-primary">ID: <?= esc($usuario->id) ?></span>
                </div>
                <div class="card-body">
                    <form action="<?= site_url('usuarios/editar/' . $usuario->id) ?>" method="post" class="form-container">
                        <?= csrf_field() ?>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nombre" class="form-label">Nombre</label>
                                    <input type="text" class="form-control <?= session('errors.nombre') ? 'is-invalid' : '' ?>" 
                                           id="nombre" name="nombre" value="<?= old('nombre', $usuario->nombre) ?>" required>
                                    <?php if (session('errors.nombre')): ?>
                                        <div class="invalid-feedback"><?= session('errors.nombre') ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="apellidos" class="form-label">Apellidos</label>
                                    <input type="text" class="form-control <?= session('errors.apellidos') ? 'is-invalid' : '' ?>" 
                                           id="apellidos" name="apellidos" value="<?= old('apellidos', $usuario->apellidos) ?>" required>
                                    <?php if (session('errors.apellidos')): ?>
                                        <div class="invalid-feedback"><?= session('errors.apellidos') ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email" class="form-label">Correo electrónico</label>
                                    <input type="email" class="form-control <?= session('errors.email') ? 'is-invalid' : '' ?>" 
                                           id="email" name="email" value="<?= old('email', $usuario->email) ?>" required>
                                    <?php if (session('errors.email')): ?>
                                        <div class="invalid-feedback"><?= session('errors.email') ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="username" class="form-label">Nombre de usuario</label>
                                    <input type="text" class="form-control <?= session('errors.username') ? 'is-invalid' : '' ?>" 
                                           id="username" name="username" value="<?= old('username', $usuario->username) ?>" required>
                                    <?php if (session('errors.username')): ?>
                                        <div class="invalid-feedback"><?= session('errors.username') ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password" class="form-label">Nueva contraseña (opcional)</label>
                                    <input type="password" class="form-control <?= session('errors.password') ? 'is-invalid' : '' ?>" 
                                           id="password" name="password">
                                    <?php if (session('errors.password')): ?>
                                        <div class="invalid-feedback"><?= session('errors.password') ?></div>
                                    <?php endif; ?>
                                    <small class="form-text">Dejar en blanco para mantener la contraseña actual</small>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password_confirm" class="form-label">Confirmar contraseña</label>
                                    <input type="password" class="form-control <?= session('errors.password_confirm') ? 'is-invalid' : '' ?>" 
                                           id="password_confirm" name="password_confirm">
                                    <?php if (session('errors.password_confirm')): ?>
                                        <div class="invalid-feedback"><?= session('errors.password_confirm') ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="rol" class="form-label">Rol</label>
                                    <select class="form-control <?= session('errors.rol') ? 'is-invalid' : '' ?>" 
                                            id="rol" name="rol" required>
                                        <option value="administrador" <?= old('rol', $usuario->rol) == 'administrador' ? 'selected' : '' ?>>Administrador</option>
                                        <option value="cirujano" <?= old('rol', $usuario->rol) == 'cirujano' ? 'selected' : '' ?>>Cirujano</option>
                                        <option value="enfermero" <?= old('rol', $usuario->rol) == 'enfermero' ? 'selected' : '' ?>>Enfermero</option>
                                        <option value="supervisor" <?= old('rol', $usuario->rol) == 'supervisor' ? 'selected' : '' ?>>Supervisor</option>
                                        <option value="usuario" <?= old('rol', $usuario->rol) == 'usuario' ? 'selected' : '' ?>>Usuario</option>
                                    </select>
                                    <?php if (session('errors.rol')): ?>
                                        <div class="invalid-feedback"><?= session('errors.rol') ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label">Estado</label>
                                    <div class="d-flex align-items-center">
                                        <div class="form-check me-3">
                                            <input class="form-check-input" type="radio" name="estado" id="estado_activo" 
                                                   value="1" <?= old('estado', $usuario->estado) == 1 ? 'checked' : '' ?> required>
                                            <label class="form-check-label" for="estado_activo">Activo</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="estado" id="estado_inactivo" 
                                                   value="0" <?= old('estado', $usuario->estado) == 0 ? 'checked' : '' ?> required>
                                            <label class="form-check-label" for="estado_inactivo">Inactivo</label>
                                        </div>
                                    </div>
                                    <?php if (session('errors.estado')): ?>
                                        <div class="invalid-feedback d-block"><?= session('errors.estado') ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-end mt-4">
                            <a href="<?= site_url('usuarios') ?>" class="btn-action btn-back">
                                <i class='bx bx-arrow-back'></i> Cancelar
                            </a>
                            <button type="submit" class="btn-action btn-save">
                                <i class='bx bx-save'></i> Guardar Cambios
                            </button>
                        </div>
                    </form>
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
        });
    </script>
    <?= $this->include('includes/footer') ?>
</body>
</html>