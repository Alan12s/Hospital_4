<div class="sidebar position-fixed h-100" style="width: 220px; background: #1a1a1a; z-index: 1000; box-shadow: 2px 0 10px rgba(0,0,0,0.3); left: 0; top: 0;">
    <div class="d-flex flex-column h-100">
        <!-- Logo y título -->
        <div class="p-3 text-center border-bottom" style="border-color: #333 !important;">
            <h3 class="m-0 text-white" style="font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 1.1rem; letter-spacing: 0.5px;">Sistema Quirúrgico</h3>
        </div>
        
        <!-- Menú principal -->
        <div class="flex-grow-1 overflow-auto py-2">
            <ul class="nav flex-column">
                <!-- Inicio -->
                <li class="nav-item">
                    <a href="<?= base_url('inicio') ?>" class="nav-link text-white d-flex align-items-center py-2 px-3" style="font-size: 0.9rem;">
                        <i class="bx bx-home me-2" style="font-size: 1rem;"></i>
                        <span>Inicio</span>
                    </a>
                </li>

                <?php $rol = session()->get('rol'); ?>
                
                <!-- Turnos -->
                <?php if(in_array($rol, ['administrador', 'supervisor', 'cirujanos', 'enfermero'])): ?>
                <li class="nav-item">
                    <a href="<?= base_url('turnos') ?>" class="nav-link text-white d-flex align-items-center py-2 px-3" style="font-size: 0.9rem;">
                        <i class="bx bx-calendar me-2" style="font-size: 1rem;"></i>
                        <span>Turnos</span>
                    </a>
                </li>
                <?php endif; ?>

                <!-- Insumos -->
                <?php if(in_array($rol, ['administrador', 'supervisor', 'medico'])): ?>
                <li class="nav-item">
                    <a href="<?= base_url('insumos') ?>" class="nav-link text-white d-flex align-items-center py-2 px-3" style="font-size: 0.9rem;">
                        <i class="bx bx-box me-2" style="font-size: 1rem;"></i>
                        <span>Insumos</span>
                    </a>
                </li>
                <?php endif; ?>

                <!-- Pacientes -->
                <?php if(in_array($rol, ['administrador', 'supervisor', 'medico', 'enfermero'])): ?>
                <li class="nav-item">
                    <a href="<?= base_url('pacientes') ?>" class="nav-link text-white d-flex align-items-center py-2 px-3" style="font-size: 0.9rem;">
                        <i class="bx bx-user-circle me-2" style="font-size: 1rem;"></i>
                        <span>Pacientes</span>
                    </a>
                </li>
                <?php endif; ?>

                <!-- Equipo (dropdown) -->
                <?php if(in_array($rol, ['administrador', 'supervisor', 'cirujanos'])): ?>
                <li class="nav-item">
                    <a class="nav-link text-white d-flex align-items-center py-2 px-3 collapsed" 
                       data-bs-toggle="collapse" 
                       href="#equipoMenu"
                       role="button"
                       aria-expanded="false"
                       aria-controls="equipoMenu"
                       style="font-size: 0.9rem;">
                        <i class="bx bx-group me-2" style="font-size: 1rem;"></i>
                        <span class="flex-grow-1">Equipo</span>
                        <i class="bx bx-chevron-down" style="font-size: 0.8rem;"></i>
                    </a>
                    <div id="equipoMenu" class="collapse" style="background: #252525;">
                        <ul class="nav flex-column">
                            <li class="nav-item">
                                <a href="<?= base_url('cirujanos') ?>" class="nav-link text-white d-flex align-items-center py-2 ps-3" style="font-size: 0.85rem;">
                                    <i class='bx bxs-user-plus bx-tada me-2' style="font-size: 0.9rem;"></i>
                                    <span>Cirujanos</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url('enfermeros') ?>" class="nav-link text-white d-flex align-items-center py-2 ps-3" style="font-size: 0.85rem;">
                                    <i class='bx bx-heart bx-tada me-2' style="font-size: 0.9rem;"></i>
                                    <span>Enfermeros</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url('anestesistas') ?>" class="nav-link text-white d-flex align-items-center py-2 ps-3" style="font-size: 0.85rem;">
                                    <i class='bx bx-injection bx-tada me-2' style="font-size: 0.9rem;"></i>
                                    <span>Anestesistas</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="<?= base_url('instrumentistas') ?>" class="nav-link text-white d-flex align-items-center py-2 ps-3" style="font-size: 0.85rem;">
                                    <i class='bx bx-wrench bx-tada me-2' style="font-size: 0.9rem;"></i>
                                    <span>Instrumentistas</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <?php endif; ?>
                
                <!-- Usuarios -->
                <?php if(in_array($rol, ['administrador', 'supervisor'])): ?>
                <li class="nav-item">
                    <a href="<?= base_url('usuarios') ?>" class="nav-link text-white d-flex align-items-center py-2 px-3" style="font-size: 0.9rem;">
                        <i class="bx bx-user me-2" style="font-size: 1rem;"></i>
                        <span>Usuarios</span>
                    </a>
                </li>
                <?php endif; ?>
            </ul>
        </div>

        <!-- Perfil usuario -->
        <div class="p-3 border-top" style="border-color: #333 !important;">
            <div class="d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#logoutModal" style="cursor: pointer;">
                <img src="<?= base_url('images/logo.png') ?>" 
                     alt="Usuario" 
                     class="rounded-circle me-2" 
                     width="36" 
                     height="36"
                     style="object-fit: cover;"
                     onerror="this.src='https://cdn-icons-png.flaticon.com/512/3135/3135715.png'">
                <div>
                    <div class="text-white" style="font-size: 0.85rem; font-weight: 500;"><?= esc(session()->get('nombre')) ?></div>
                    <small class="text-light" style="font-size: 0.75rem; color: #b8b8b8 !important; font-weight: 500;"><?= esc(session()->get('rol')) ?></small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Cerrar Sesión con Animaciones -->
<div class="modal fade" id="logoutModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content logout-modal" style="background: linear-gradient(135deg, #1a1a1a 0%, #2d1b2d 100%); color: white; border: 1px solid #6a1b9a; border-radius: 15px; overflow: hidden; position: relative;">
            
            <!-- Ícono gigante animado de fondo -->
            <div class="logout-icon-bg">
                <i class="bx bx-log-out"></i>
            </div>
            
            <!-- Efectos de partículas -->
            <div class="particles">
                <div class="particle"></div>
                <div class="particle"></div>
                <div class="particle"></div>
                <div class="particle"></div>
                <div class="particle"></div>
            </div>
            
            <div class="modal-header border-bottom" style="border-color: rgba(106, 27, 154, 0.3) !important; background: rgba(0,0,0,0.3); backdrop-filter: blur(10px);">
                <h5 class="modal-title logout-title" style="font-size: 1.2rem; font-weight: 600;">
                    <i class="bx bx-log-out me-2 logout-icon-header"></i>
                    Cerrar Sesión
                </h5>
                <button type="button" class="btn-close btn-close-white close-btn-animated" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body py-4 text-center" style="background: rgba(0,0,0,0.2); backdrop-filter: blur(5px);">
                <div class="logout-content">
                    <div class="logout-icon-main mb-3">
                        <i class="bx bx-user-x"></i>
                    </div>
                    <p style="font-size: 1rem; margin-bottom: 1rem; font-weight: 500;">¿Está seguro que desea cerrar la sesión?</p>
                    <p style="font-size: 0.85rem; color: #ccc; margin-bottom: 0;">Se cerrará su sesión actual y deberá volver a iniciar sesión.</p>
                </div>
            </div>
            
            <div class="modal-footer border-top justify-content-center" style="border-color: rgba(106, 27, 154, 0.3) !important; background: rgba(0,0,0,0.3); backdrop-filter: blur(10px);">
                <button type="button" class="btn btn-outline-light logout-btn-cancel me-2" data-bs-dismiss="modal">
                    <i class="bx bx-x me-1"></i> Cancelar
                </button>
                <a href="<?= base_url('logout') ?>" class="btn btn-danger logout-btn-confirm">
                    <i class="bx bx-log-out me-1"></i> Cerrar Sesión
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Estilos adicionales -->
<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap');
    
    .sidebar {
        font-family: 'Poppins', sans-serif;
        position: fixed !important;
        left: 0 !important;
        top: 0 !important;
        bottom: 0 !important;
    }
    
    .nav-link {
        transition: all 0.2s ease;
        border-left: 2px solid transparent;
    }
    
    .nav-link:hover, .nav-link.active {
        background: #333 !important;
        border-left: 2px solid #6a1b9a;
    }
    
    .nav-link:hover i, .nav-link.active i {
        color: #6a1b9a;
    }
    
    /* ================================
       ANIMACIONES DEL MODAL DE LOGOUT
       ================================ */
    
    /* Modal con efecto de entrada suave */
    .logout-modal {
        animation: modalSlideIn 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94) forwards;
        box-shadow: 0 20px 50px rgba(106, 27, 154, 0.3), 0 0 100px rgba(106, 27, 154, 0.1);
    }
    
    @keyframes modalSlideIn {
        0% {
            opacity: 0;
            transform: scale(0.8) translateY(-50px) rotateX(15deg);
        }
        100% {
            opacity: 1;
            transform: scale(1) translateY(0) rotateX(0deg);
        }
    }
    
    /* Ícono gigante de fondo */
    .logout-icon-bg {
        position: absolute;
        top: -20px;
        right: -20px;
        z-index: 1;
        opacity: 0.1;
        animation: iconPulse 3s ease-in-out infinite;
    }
    
    .logout-icon-bg i {
        font-size: 180px;
        color: #6a1b9a;
        transform: rotate(-15deg);
    }
    
    @keyframes iconPulse {
        0%, 100% {
            transform: rotate(-15deg) scale(1);
            opacity: 0.1;
        }
        50% {
            transform: rotate(-10deg) scale(1.1);
            opacity: 0.2;
        }
    }
    
    /* Partículas flotantes */
    .particles {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        pointer-events: none;
        z-index: 2;
    }
    
    .particle {
        position: absolute;
        background: #6a1b9a;
        border-radius: 50%;
        animation: particleFloat 4s ease-in-out infinite;
    }
    
    .particle:nth-child(1) {
        width: 4px;
        height: 4px;
        top: 20%;
        left: 20%;
        animation-delay: 0s;
    }
    
    .particle:nth-child(2) {
        width: 3px;
        height: 3px;
        top: 40%;
        left: 80%;
        animation-delay: 1s;
    }
    
    .particle:nth-child(3) {
        width: 5px;
        height: 5px;
        top: 70%;
        left: 30%;
        animation-delay: 2s;
    }
    
    .particle:nth-child(4) {
        width: 2px;
        height: 2px;
        top: 30%;
        left: 60%;
        animation-delay: 3s;
    }
    
    .particle:nth-child(5) {
        width: 4px;
        height: 4px;
        top: 80%;
        left: 70%;
        animation-delay: 1.5s;
    }
    
    @keyframes particleFloat {
        0%, 100% {
            transform: translateY(0px) rotate(0deg);
            opacity: 0.7;
        }
        50% {
            transform: translateY(-20px) rotate(180deg);
            opacity: 1;
        }
    }
    
    /* Título del modal */
    .logout-title {
        animation: titleGlow 2s ease-in-out infinite alternate;
        position: relative;
        z-index: 3;
    }
    
    @keyframes titleGlow {
        0% {
            text-shadow: 0 0 5px rgba(106, 27, 154, 0.5);
        }
        100% {
            text-shadow: 0 0 20px rgba(106, 27, 154, 0.8), 0 0 30px rgba(106, 27, 154, 0.4);
        }
    }
    
    /* Ícono del header */
    .logout-icon-header {
        animation: iconBounce 1s ease-in-out infinite;
    }
    
    @keyframes iconBounce {
        0%, 100% {
            transform: translateX(0);
        }
        50% {
            transform: translateX(5px);
        }
    }
    
    /* Contenido del modal */
    .logout-content {
        position: relative;
        z-index: 3;
        animation: contentFadeIn 0.6s ease-out 0.2s both;
    }
    
    @keyframes contentFadeIn {
        0% {
            opacity: 0;
            transform: translateY(20px);
        }
        100% {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    /* Ícono principal del contenido */
    .logout-icon-main {
        animation: mainIconRotate 3s ease-in-out infinite;
    }
    
    .logout-icon-main i {
        font-size: 4rem;
        color: #ff6b6b;
        filter: drop-shadow(0 5px 15px rgba(255, 107, 107, 0.3));
    }
    
    @keyframes mainIconRotate {
        0%, 100% {
            transform: rotate(0deg) scale(1);
        }
        25% {
            transform: rotate(-5deg) scale(1.05);
        }
        75% {
            transform: rotate(5deg) scale(1.05);
        }
    }
    
    .nav-link[data-bs-toggle="collapse"] .bx-chevron-down {
        transition: transform 0.3s ease;
    }
    
    .nav-link[data-bs-toggle="collapse"]:not(.collapsed) .bx-chevron-down {
        transform: rotate(180deg);
    }
    
    .nav-link[data-bs-toggle="collapse"].collapsed .bx-chevron-down {
        transform: rotate(0deg);
    }
    
    /* Botón de cerrar (X) animado */
    .close-btn-animated {
        transition: all 0.3s ease;
        border-radius: 50%;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        z-index: 10;
        background: rgba(255,255,255,0.1);
    }
    
    .close-btn-animated:hover {
        background: rgba(255,255,255,0.2);
        transform: rotate(90deg) scale(1.1);
        box-shadow: 0 5px 15px rgba(255,255,255,0.2);
    }
    
    .close-btn-animated:focus {
        box-shadow: 0 0 0 2px rgba(106, 27, 154, 0.5);
        outline: none;
    }
    
    /* Botones animados */
    .logout-btn-cancel, .logout-btn-confirm {
        position: relative;
        z-index: 3;
        transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        font-weight: 500;
        border-radius: 25px;
        padding: 10px 25px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }
    
    .logout-btn-cancel:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(255,255,255,0.2);
        background: rgba(255,255,255,0.1) !important;
    }
    
    .logout-btn-confirm:hover {
        transform: translateY(-2px) scale(1.05);
        box-shadow: 0 10px 30px rgba(220, 53, 69, 0.4);
        background: linear-gradient(45deg, #dc3545, #ff6b6b) !important;
    }
    
    .logout-btn-confirm:active {
        animation: confirmPulse 0.3s ease-out;
    }
    
    @keyframes confirmPulse {
        0% {
            transform: scale(1.05);
        }
        50% {
            transform: scale(0.95);
        }
        100% {
            transform: scale(1.05);
        }
    }
    
    /* Efecto de hover en el modal */
    .logout-modal:hover .logout-icon-bg {
        animation-duration: 1.5s;
    }
    
    .logout-modal:hover .particle {
        animation-duration: 2s;
    }
    
    /* Responsive para móviles */
    @media (max-width: 768px) {
        .sidebar {
            transform: translateX(-100%);
            transition: transform 0.3s ease;
        }
        .sidebar.show {
            transform: translateX(0);
        }
        
        .logout-icon-bg i {
            font-size: 120px;
        }
        
        .logout-icon-main i {
            font-size: 3rem;
        }
    }
    
    /* Efecto de cierre del modal */
    .modal.fade .modal-dialog {
        transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    }
    
    .modal.fade:not(.show) .logout-modal {
        animation: modalSlideOut 0.3s ease-in forwards;
    }
    
    @keyframes modalSlideOut {
        0% {
            opacity: 1;
            transform: scale(1) translateY(0) rotateX(0deg);
        }
        100% {
            opacity: 0;
            transform: scale(0.8) translateY(50px) rotateX(-15deg);
        }
    }
</style>