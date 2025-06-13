<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hospital Dr.Cesar Aguilar - Login</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
<link href="<?= base_url('assets/css/login.css') ?>" rel="stylesheet">

<style>
.logo-container {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 15px;
    margin-bottom: 20px;
}

.custom-logo {
    display: flex;
    align-items: center;
    justify-content: center;
}

.hospital-logo-svg {
    animation: logoFloat 3s ease-in-out infinite;
    transition: all 0.3s ease;
    filter: drop-shadow(0 8px 20px rgba(21, 101, 192, 0.3));
}

.hospital-logo-svg:hover {
    transform: scale(1.05);
    filter: drop-shadow(0 12px 30px rgba(21, 101, 192, 0.4));
}

.hospital-logo {
    width: 120px;
    height: 120px;
}

.hospital-logo:hover {
    transform: scale(1.05);
    box-shadow: 0 12px 30px rgba(106, 27, 154, 0.4);
}

@keyframes logoFloat {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-5px); }
}

.logo-and-icon {
    display: flex;
    align-items: center;
    gap: 15px;
}

.hospital-icon {
    font-size: 2.5rem;
    color: #6a1b9a;
    text-shadow: 0 0 20px rgba(106, 27, 154, 0.5);
}

@media (max-width: 576px) {
    .logo-container {
        flex-direction: column;
        gap: 10px;
    }
    
    .logo-and-icon {
        flex-direction: column;
        gap: 10px;
    }
    
    .hospital-logo {
        width: 100px;
        height: 100px;
    }
    
    .hospital-logo-svg {
        width: 100px;
        height: 100px;
    }
    
    .hospital-icon {
        font-size: 2rem;
    }
}
</style>
</head>
<body class="d-flex align-items-center justify-content-center min-vh-100">
    
    <!-- Animated Background -->
    <div class="animated-bg"></div>
    
    <!-- Particles System -->
    <div class="particles" id="particles"></div>
    
    <!-- Light Rays -->
    <div class="light-rays">
        <div class="ray"></div>
        <div class="ray"></div>
        <div class="ray"></div>
        <div class="ray"></div>
        <div class="ray"></div>
        <div class="ray"></div>
    </div>
    
    <!-- Glowing Orbs -->
    <div class="particles">
        <div class="orb"></div>
        <div class="orb"></div>
        <div class="orb"></div>
    </div>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-sm-8 col-md-6 col-lg-4">
                <div class="login-container rounded-4 p-4 p-md-5">
                    
                    <!-- Header -->
                    <div class="text-center mb-4">
                        <div class="logo-container">
                            <div class="logo-and-icon">
                                <div class="custom-logo">
                                    <svg width="120" height="120" viewBox="0 0 120 120" class="hospital-logo-svg">
                                        <defs>
                                            <filter id="shadow" x="-50%" y="-50%" width="200%" height="200%">
                                                <feDropShadow dx="0" dy="4" stdDeviation="8" flood-color="#1565c0" flood-opacity="0.3"/>
                                            </filter>
                                        </defs>
                                        
                                        <!-- Borde celeste sin fondo -->
                                        <rect x="3" y="3" width="114" height="114" rx="18" ry="18" 
                                              fill="transparent" filter="url(#shadow)" 
                                              stroke="#00bcd4" stroke-width="6"/>
                                        
                                        <!-- Letra H grande en azul -->
                                        <text x="18" y="80" font-family="Arial, sans-serif" font-size="54" font-weight="900" fill="#1a237e">H</text>
                                        
                                        <!-- Cruz roja más grande -->
                                        <g transform="translate(50, 35)">
                                            <rect x="0" y="8" width="20" height="8" fill="#e53935" rx="2"/>
                                            <rect x="6" y="0" width="8" height="24" fill="#e53935" rx="2"/>
                                        </g>
                                        
                                        <!-- Letra C más pequeña arriba -->
                                        <text x="78" y="58" font-family="Arial, sans-serif" font-size="26" font-weight="900" fill="#1a237e">C</text>
                                        
                                        <!-- Letra A más pequeña abajo -->
                                        <text x="78" y="85" font-family="Arial, sans-serif" font-size="26" font-weight="900" fill="#1a237e">A</text>
                                        
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <h2 class="fw-bold text-white mb-2">Sistema Quirúrgico</h2>
                        <p class="text-muted mb-0">Acceso al panel de control</p>
                    </div>

                    <!-- Alertas -->
                    <?php if (session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class='bx bx-error-circle me-2'></i>
                            <?= session()->getFlashdata('error') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <?php if (session()->getFlashdata('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class='bx bx-check-circle me-2'></i>
                            <?= session()->getFlashdata('success') ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class='bx bx-error-circle me-2'></i>
                            <?= $error ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <!-- Formulario de Login -->
                    <?= form_open('login', ['class' => 'needs-validation', 'novalidate' => true]) ?>
                        
                        <!-- Campo Username -->
                        <div class="mb-3">
                            <label for="username" class="form-label fw-semibold text-white">
                                <i class='bx bx-user me-2' style="color: #6a1b9a;"></i>Usuario
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
                            <label for="password" class="form-label fw-semibold text-white">
                                <i class='bx bx-lock-alt me-2' style="color: #6a1b9a;"></i>Contraseña
                            </label>
                            <div class="input-group">
                                <input type="password" 
                                       class="form-control form-control-lg rounded-start-3 <?= isset($validation) && $validation->hasError('password') ? 'is-invalid' : '' ?>" 
                                       id="password" 
                                       name="password" 
                                       placeholder="Ingrese su contraseña"
                                       required>
                                <button class="btn btn-outline-secondary rounded-end-3" type="button" id="togglePassword">
                                    <i class='bx bx-show' id="eyeIcon"></i>
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
                                <i class='bx bx-log-in me-2'></i>
                                Iniciar Sesión
                            </button>
                        </div>

                    <?= form_close() ?>

                    <!-- Footer -->
                    <div class="text-center mt-4 pt-3 border-top" style="border-color: rgba(255,255,255,0.1) !important;">
                        <small class="text-muted">
                            © <?= date('Y') ?> Sistema Quirúrgico
                        </small>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        // Create floating particles
        function createParticles() {
            const particlesContainer = document.getElementById('particles');
            const particleCount = window.innerWidth < 768 ? 20 : 40;
            
            for (let i = 0; i < particleCount; i++) {
                const particle = document.createElement('div');
                particle.classList.add('particle');
                particle.style.left = Math.random() * 100 + '%';
                particle.style.animationDelay = Math.random() * 20 + 's';
                particle.style.animationDuration = (15 + Math.random() * 10) + 's';
                particlesContainer.appendChild(particle);
            }
        }
        
        // Create floating medical icons
        function createMedicalIcons() {
            const icons = ['bx-heart', 'bx-injection', 'bx-wrench', 'bx-user-plus', 'bx-cross'];
            const particlesContainer = document.getElementById('particles');
            
            setInterval(() => {
                if (Math.random() > 0.7) { // 30% chance every interval
                    const icon = document.createElement('i');
                    icon.className = `bx ${icons[Math.floor(Math.random() * icons.length)]} bg-medical-icon`;
                    icon.style.left = Math.random() * 90 + '%';
                    icon.style.animationDelay = '0s';
                    icon.style.animationDuration = (10 + Math.random() * 5) + 's';
                    particlesContainer.appendChild(icon);
                    
                    // Remove icon after animation
                    setTimeout(() => {
                        if (icon.parentNode) {
                            icon.parentNode.removeChild(icon);
                        }
                    }, 15000);
                }
            }, 3000);
        }
        
        // Initialize animations
        document.addEventListener('DOMContentLoaded', function() {
            createParticles();
            createMedicalIcons();
        });
        
        // Toggle password visibility
        document.getElementById('togglePassword').addEventListener('click', function() {
            const password = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            
            if (password.type === 'password') {
                password.type = 'text';
                eyeIcon.classList.remove('bx-show');
                eyeIcon.classList.add('bx-hide');
            } else {
                password.type = 'password';
                eyeIcon.classList.remove('bx-hide');
                eyeIcon.classList.add('bx-show');
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
        
        // Handle window resize for particles
        window.addEventListener('resize', function() {
            const particlesContainer = document.getElementById('particles');
            const particles = particlesContainer.querySelectorAll('.particle');
            particles.forEach(particle => particle.remove());
            createParticles();
        });
    </script>
</body>
</html>