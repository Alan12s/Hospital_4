<!-- Modal de Cambio de Estado -->
<div class="modal fade" id="modalEstado" tabindex="-1" aria-labelledby="modalEstadoLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content status-modal" style="background: linear-gradient(135deg, #1a1a1a 0%, #2d251b 100%); color: white; border: 1px solid #f59e0b; border-radius: 15px; overflow: hidden; position: relative;">
            
            <!-- Ícono gigante animado de fondo -->
            <div class="status-icon-bg">
                <i class="bx bx-toggle-right"></i>
            </div>
            
            <!-- Efectos de partículas -->
            <div class="status-particles">
                <div class="particle"></div>
                <div class="particle"></div>
                <div class="particle"></div>
                <div class="particle"></div>
                <div class="particle"></div>
            </div>
            
            <div class="modal-header border-bottom" style="border-color: rgba(245, 158, 11, 0.3) !important; background: rgba(0,0,0,0.3); backdrop-filter: blur(10px);">
                <h5 class="modal-title status-title" style="font-size: 1.2rem; font-weight: 600;">
                    <i class='bx bx-toggle-right me-2 status-icon-header'></i>
                    <span id="modalEstadoTitulo">Cambiar Estado</span>
                </h5>
                <button type="button" class="btn-close btn-close-white close-btn-animated" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form id="formEstado" method="post" action="">
                <?= csrf_field() ?>
                <input type="hidden" id="idElementoEstado" name="id">
                <div class="modal-body py-4 text-center" style="background: rgba(0,0,0,0.2); backdrop-filter: blur(5px);">
                    <div class="status-content">
                        <div class="status-icon-main mb-3">
                            <i class='bx bx-toggle-right'></i>
                        </div>
                        <p style="font-size: 1rem; margin-bottom: 1rem; font-weight: 500;">
                            ¿Está seguro que desea <strong><span id="accionEstado"></span></strong> al usuario <strong><span id="nombreElementoEstado"></span></strong>?
                        </p>
                        <p style="font-size: 0.85rem; color: #f59e0b; margin-bottom: 0;" id="mensajeAdicionalEstado">
                            Esta acción cambiará la capacidad de acceso del usuario al sistema.
                        </p>
                    </div>
                </div>
                <div class="modal-footer border-top justify-content-center" style="border-color: rgba(245, 158, 11, 0.3) !important; background: rgba(0,0,0,0.3); backdrop-filter: blur(10px);">
                    <button type="button" class="btn btn-outline-light status-btn-cancel me-2" data-bs-dismiss="modal">
                        <i class='bx bx-x me-1'></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-warning status-btn-confirm">
                        <i class='bx bx-check me-1'></i> Confirmar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    /* ================================
       ANIMACIONES DEL MODAL DE ESTADO
       ================================ */
    
    /* Modal con efecto de entrada suave */
    .status-modal {
        animation: modalSlideIn 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94) forwards;
        box-shadow: 0 20px 50px rgba(245, 158, 11, 0.3), 0 0 100px rgba(245, 158, 11, 0.1);
    }
    
    /* Ícono gigante de fondo */
    .status-icon-bg {
        position: absolute;
        top: -20px;
        right: -20px;
        z-index: 1;
        opacity: 0.1;
        animation: iconPulse 3s ease-in-out infinite;
    }
    
    .status-icon-bg i {
        font-size: 180px;
        color: #f59e0b;
        transform: rotate(-15deg);
    }
    
    /* Partículas flotantes */
    .status-particles {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        pointer-events: none;
        z-index: 2;
    }
    
    .status-particles .particle {
        position: absolute;
        background: #f59e0b;
        border-radius: 50%;
        animation: particleFloat 4s ease-in-out infinite;
    }
    
    .status-particles .particle:nth-child(1) {
        width: 4px;
        height: 4px;
        top: 20%;
        left: 20%;
        animation-delay: 0s;
    }
    
    .status-particles .particle:nth-child(2) {
        width: 3px;
        height: 3px;
        top: 40%;
        left: 80%;
        animation-delay: 1s;
    }
    
    .status-particles .particle:nth-child(3) {
        width: 5px;
        height: 5px;
        top: 70%;
        left: 30%;
        animation-delay: 2s;
    }
    
    .status-particles .particle:nth-child(4) {
        width: 2px;
        height: 2px;
        top: 30%;
        left: 60%;
        animation-delay: 3s;
    }
    
    .status-particles .particle:nth-child(5) {
        width: 4px;
        height: 4px;
        top: 80%;
        left: 70%;
        animation-delay: 1.5s;
    }
    
    /* Título del modal */
    .status-title {
        animation: statusTitleGlow 2s ease-in-out infinite alternate;
        position: relative;
        z-index: 3;
    }
    
    @keyframes statusTitleGlow {
        0% {
            text-shadow: 0 0 5px rgba(245, 158, 11, 0.5);
        }
        100% {
            text-shadow: 0 0 20px rgba(245, 158, 11, 0.8), 0 0 30px rgba(245, 158, 11, 0.4);
        }
    }
    
    /* Ícono del header */
    .status-icon-header {
        animation: iconBounce 1s ease-in-out infinite;
    }
    
    /* Contenido del modal */
    .status-content {
        position: relative;
        z-index: 3;
        animation: contentFadeIn 0.6s ease-out 0.2s both;
    }
    
    /* Ícono principal del contenido */
    .status-icon-main {
        animation: statusIconPulse 1.5s ease-in-out infinite;
    }
    
    .status-icon-main i {
        font-size: 4rem;
        color: #f59e0b;
        filter: drop-shadow(0 5px 15px rgba(245, 158, 11, 0.3));
    }
    
    @keyframes statusIconPulse {
        0%, 100% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.1);
        }
    }
    
    /* Botones animados */
    .status-btn-cancel, .status-btn-confirm {
        position: relative;
        z-index: 3;
        transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        font-weight: 500;
        border-radius: 25px;
        padding: 10px 25px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }
    
    .status-btn-cancel:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(255,255,255,0.2);
        background: rgba(255,255,255,0.1) !important;
    }
    
    .status-btn-confirm:hover {
        transform: translateY(-2px) scale(1.05);
        box-shadow: 0 10px 30px rgba(245, 158, 11, 0.4);
        background: linear-gradient(45deg, #f59e0b, #f97316) !important;
    }
    
    .status-btn-confirm:active {
        animation: confirmPulse 0.3s ease-out;
    }
    
    /* Efecto de hover en el modal */
    .status-modal:hover .status-icon-bg {
        animation-duration: 1.5s;
    }
    
    .status-modal:hover .status-particles .particle {
        animation-duration: 2s;
    }
    
    /* Responsive para móviles */
    @media (max-width: 768px) {
        .status-icon-bg i {
            font-size: 120px;
        }
        
        .status-icon-main i {
            font-size: 3rem;
        }
    }
</style>

<script>
// Función para configurar el modal de cambio de estado
function configurarModalEstado(options) {
    const {
        idElemento,
        nombreElemento,
        actionUrl,
        estadoActual,
        titulo = 'Cambiar Estado'
    } = options;

    const accion = estadoActual == 1 ? 'desactivar' : 'activar';
    const mensajeAdicional = estadoActual == 1 
        ? 'El usuario no podrá acceder al sistema hasta que sea reactivado.' 
        : 'El usuario podrá acceder nuevamente al sistema.';

    document.getElementById('modalEstadoTitulo').textContent = titulo;
    document.getElementById('idElementoEstado').value = idElemento;
    document.getElementById('nombreElementoEstado').textContent = nombreElemento;
    document.getElementById('accionEstado').textContent = accion;
    document.getElementById('mensajeAdicionalEstado').textContent = mensajeAdicional;
    document.getElementById('formEstado').action = actionUrl;
    
    // Cambiar ícono según el estado
    const icono = document.querySelector('.status-icon-main i');
    icono.className = estadoActual == 1 ? 'bx bx-toggle-left' : 'bx bx-toggle-right';
}
</script>