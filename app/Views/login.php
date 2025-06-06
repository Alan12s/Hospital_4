<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hospital Management - Login</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        
        .login-container {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.95);
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 25px 45px rgba(0, 0, 0, 0.1);
        }
        
        .form-control:focus {
            border-color: #667eea;
            box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            transition: all 0.3s ease;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
        }
        
        .hospital-icon {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .floating-animation {
            animation: float 6s ease-in-out infinite;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        
        .pulse-animation {
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }
        
        @media (max-width: 768px) {
            .login-container {
                margin: 1rem;
                padding: 2rem 1rem;
            }
        }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center min-vh-100">
    
    <!-- Animated Background Elements -->
    <div class="position-fixed top-0 start-0 w-100 h-100 overflow-hidden" style="z-index: -1;">
        <div class="position-absolute floating-animation" style="top: 10%; left: 10%; opacity: 0.1;">
            <i class="fas fa-heartbeat text-white" style="font-size: 3rem;"></i>
        </div>
        <div class="position-absolute floating-animation" style="top: 20%; right: 15%; opacity: 0.1; animation-delay: -2s;">
            <i class="fas fa-stethoscope text-white" style="font-size: 2.5rem;"></i>
        </div>
        <div class="position-absolute floating-animation" style="bottom: 30%; left: 20%; opacity: 0.1; animation-delay: -4s;">
            <i class="fas fa-user-md text-white" style="font-size: 3.5rem;"></i>
        </div>
        <div class="position-absolute floating-animation" style="bottom: 20%; right: 25%; opacity: 0.1; animation-delay: -3s;">
            <i class="fas fa-hospital text-white" style="font-size: 2.8rem;"></i>
        </div>
    </div>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-sm-8 col-md-6 col-lg-4">
                <div class="login-container rounded-4 shadow-lg p-4 p-md-5">
                    
                    <!-- Header -->
                    <div class="text-center mb-4">
                        <div class="mb-3">
                            <i class="fas fa-hospital-alt hospital-icon pulse-animation" style="font-size: 3rem;"></i>
                        </div>
                        <h2 class="fw-bold text-dark mb-2">Hospital Management</h2>
                        <p class="text-muted mb-0">Sistema de Gestión Hospitalaria</p>
                    </div>

                    <!-- Alertas -->
                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <?= session()->getFlashdata('error') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <?php if (session()->getFlashdata('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            <?= session()->getFlashdata('success') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <?= $error ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <!-- Formulario de Login - CORREGIDO -->
                    <?= form_open('login', ['class' => 'needs-validation', 'novalidate' => true]) ?>
                        
                        <!-- Campo Username -->
                        <div class="mb-3">
                            <label for="username" class="form-label fw-semibold text-dark">
                                <i class="fas fa-user me-2 text-primary"></i>Usuario
                            </label>
                            <input type="text" 
                                   class="form-control form-control-lg rounded-3 <?= isset($validation) && $validation->hasError('username') ? 'is-invalid' : '' ?>" 
                                   id="username" 
                                   name="username" 
                                   placeholder="Ingrese su nombre de usuario"
                                   value="<?= old('username', isset($username) ? $username : '') ?>"
                                   required>
                            <?php if (isset($validation) && $validation->hasError('username')): ?>
                                <div class="invalid-feedback">
                                    <?= $validation->getError('username') ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Campo Password -->
                        <div class="mb-4">
                            <label for="password" class="form-label fw-semibold text-dark">
                                <i class="fas fa-lock me-2 text-primary"></i>Contraseña
                            </label>
                            <div class="input-group">
                                <input type="password" 
                                       class="form-control form-control-lg rounded-start-3 <?= isset($validation) && $validation->hasError('password') ? 'is-invalid' : '' ?>" 
                                       id="password" 
                                       name="password" 
                                       placeholder="Ingrese su contraseña"
                                       required>
                                <button class="btn btn-outline-secondary rounded-end-3" type="button" id="togglePassword">
                                    <i class="fas fa-eye" id="eyeIcon"></i>
                                </button>
                                <?php if (isset($validation) && $validation->hasError('password')): ?>
                                    <div class="invalid-feedback">
                                        <?= $validation->getError('password') ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Recordar sesión -->
                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="remember" name="remember">
                                <label class="form-check-label text-muted" for="remember">
                                    Recordar mi sesión
                                </label>
                            </div>
                        </div>

                        <!-- Botón de Login -->
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg rounded-3 fw-semibold py-3">
                                <i class="fas fa-sign-in-alt me-2"></i>
                                Iniciar Sesión
                            </button>
                        </div>

                    <?= form_close() ?>

                    <!-- Footer -->
                    <div class="text-center mt-4 pt-3 border-top">
                        <small class="text-muted">
                            © <?= date('Y') ?> Hospital Management System
                        </small>
                    </div>

                    <!-- Información de usuarios demo -->
                    <div class="mt-4 p-3 bg-light rounded-3">
                        <h6 class="fw-semibold text-dark mb-2">
                            <i class="fas fa-info-circle me-2 text-info"></i>Usuarios de Prueba
                        </h6>
                        <div class="row g-2">
                            <div class="col-6">
                                <small class="text-muted d-block">
                                    <strong>Admin:</strong> admin<br>
                                    <strong>Pass:</strong> admin123
                                </small>
                            </div>
                            <div class="col-6">
                                <small class="text-muted d-block">
                                    <strong>Médico:</strong> medico<br>
                                    <strong>Pass:</strong> medico123
                                </small>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Toggle password visibility
        document.getElementById('togglePassword').addEventListener('click', function() {
            const password = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            
            if (password.type === 'password') {
                password.type = 'text';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                password.type = 'password';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        });

        // Bootstrap form validation
        (function() {
            'use strict';
            const forms = document.querySelectorAll('.needs-validation');
            
            Array.from(forms).forEach(function(form) {
                form.addEventListener('submit', function(event) {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        })();

        // Auto dismiss alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert-dismissible');
            alerts.forEach(function(alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    </script>

</body>
</html>