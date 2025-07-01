<!-- Modal de Cancelación -->
<div class="modal fade" id="modalCancelar" tabindex="-1" aria-labelledby="modalCancelarLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content cancel-modal" style="background: linear-gradient(135deg, #2d1b1b 0%, #3a2a0a 100%); color: white; border: 1px solid #f59e0b; border-radius: 15px; overflow: hidden; position: relative;">
            
            <!-- Ícono gigante animado de fondo -->
            <div class="cancel-icon-bg">
                <i class="bx bx-calendar-x"></i>
            </div>
            
            <!-- Efectos de partículas -->
            <div class="particles-cancel">
                <div class="particle"></div>
                <div class="particle"></div>
                <div class="particle"></div>
                <div class="particle"></div>
                <div class="particle"></div>
            </div>
            
            <div class="modal-header border-bottom" style="border-color: rgba(245, 158, 11, 0.3) !important; background: rgba(0,0,0,0.3); backdrop-filter: blur(10px);">
                <h5 class="modal-title cancel-title" style="font-size: 1.2rem; font-weight: 600;">
                    <i class='bx bx-calendar-x me-2 cancel-icon-header'></i>
                    <span id="modalCancelarTitulo">Confirmar Cancelación</span>
                </h5>
                <button type="button" class="btn-close btn-close-white close-btn-animated" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form id="formCancelar" method="post" action="">
                <?= csrf_field() ?>
                <input type="hidden" id="idElementoCancelar" name="id">
                <div class="modal-body py-4 text-center" style="background: rgba(0,0,0,0.2); backdrop-filter: blur(5px);">
                    <div class="cancel-content">
                        <div class="cancel-icon-main mb-3">
                            <i class='bx bx-calendar-x'></i>
                        </div>
                        <p style="font-size: 1rem; margin-bottom: 1rem; font-weight: 500;">
                            ¿Está seguro que desea cancelar el turno de <strong><span id="nombreElementoCancelar"></span></strong>?
                        </p>
                        <p style="font-size: 0.85rem; color: #fbbf24; margin-bottom: 0;" id="mensajeAdicionalCancelar">
                            El turno será marcado como cancelado y no podrá ser reactivado.
                        </p>
                    </div>
                </div>
                <div class="modal-footer border-top justify-content-center" style="border-color: rgba(245, 158, 11, 0.3) !important; background: rgba(0,0,0,0.3); backdrop-filter: blur(10px);">
                    <button type="button" class="btn btn-outline-light cancel-btn-cancel me-2" data-bs-dismiss="modal">
                        <i class='bx bx-x me-1'></i> No Cancelar
                    </button>
                    <button type="submit" class="btn btn-warning cancel-btn-confirm">
                        <i class='bx bx-calendar-x me-1'></i> Confirmar Cancelación
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    /* ================================
       ANIMACIONES DEL MODAL DE CANCELAR
       ================================ */
    
    /* Modal con efecto de entrada suave */
    .cancel-modal {
        animation: modalSlideIn 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94) forwards;
        box-shadow: 0 20px 50px rgba(245, 158, 11, 0.3), 0 0 100px rgba(245, 158, 11, 0.1);
    }
    
    /* Ícono gigante de fondo */
    .cancel-icon-bg {
        position: absolute;
        top: -20px;
        right: -20px;
        z-index: 1;
        opacity: 0.1;
        animation: iconPulseCancel 3s ease-in-out infinite;
    }
    
    .cancel-icon-bg i {
        font-size: 180px;
        color: #f59e0b;
        transform: rotate(-15deg);
    }
    
    @keyframes iconPulseCancel {
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
    .particles-cancel {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        pointer-events: none;
        z-index: 2;
    }
    
    .particles-cancel .particle {
        position: absolute;
        background: #f59e0b;
        border-radius: 50%;
        animation: particleFloatCancel 4s ease-in-out infinite;
    }
    
    .particles-cancel .particle:nth-child(1) {
        width: 4px;
        height: 4px;
        top: 20%;
        left: 20%;
        animation-delay: 0s;
    }
    
    .particles-cancel .particle:nth-child(2) {
        width: 3px;
        height: 3px;
        top: 40%;
        left: 80%;
        animation-delay: 1s;
    }
    
    .particles-cancel .particle:nth-child(3) {
        width: 5px;
        height: 5px;
        top: 70%;
        left: 30%;
        animation-delay: 2s;
    }
    
    .particles-cancel .particle:nth-child(4) {
        width: 2px;
        height: 2px;
        top: 30%;
        left: 60%;
        animation-delay: 3s;
    }
    
    .particles-cancel .particle:nth-child(5) {
        width: 4px;
        height: 4px;
        top: 80%;
        left: 70%;
        animation-delay: 1.5s;
    }
    
    @keyframes particleFloatCancel {
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
    .cancel-title {
        animation: titleGlowCancel 2s ease-in-out infinite alternate;
        position: relative;
        z-index: 3;
    }
    
    @keyframes titleGlowCancel {
        0% {
            text-shadow: 0 0 5px rgba(245, 158, 11, 0.5);
        }
        100% {
            text-shadow: 0 0 20px rgba(245, 158, 11, 0.8), 0 0 30px rgba(245, 158, 11, 0.4);
        }
    }
    
    /* Ícono del header */
    .cancel-icon-header {
        animation: iconBounceCancel 1s ease-in-out infinite;
    }
    
    @keyframes iconBounceCancel {
        0%, 100% {
            transform: translateX(0);
        }
        50% {
            transform: translateX(5px);
        }
    }
    
    /* Contenido del modal */
    .cancel-content {
        position: relative;
        z-index: 3;
        animation: contentFadeIn 0.6s ease-out 0.2s both;
    }
    
    /* Ícono principal del contenido */
    .cancel-icon-main {
        animation: mainIconRotateCancel 3s ease-in-out infinite;
    }
    
    .cancel-icon-main i {
        font-size: 4rem;
        color: #fbbf24;
        filter: drop-shadow(0 5px 15px rgba(251, 191, 36, 0.3));
    }
    
    @keyframes mainIconRotateCancel {
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
    
    /* Botones animados */
    .cancel-btn-cancel, .cancel-btn-confirm {
        position: relative;
        z-index: 3;
        transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        font-weight: 500;
        border-radius: 25px;
        padding: 10px 25px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }
    
    .cancel-btn-cancel:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(255,255,255,0.2);
        background: rgba(255,255,255,0.1) !important;
    }
    
    .cancel-btn-confirm:hover {
        transform: translateY(-2px) scale(1.05);
        box-shadow: 0 10px 30px rgba(245, 158, 11, 0.4);
        background: linear-gradient(45deg, #f59e0b, #fbbf24) !important;
    }
    
    .cancel-btn-confirm:active {
        animation: confirmPulseCancel 0.3s ease-out;
    }
    
    @keyframes confirmPulseCancel {
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
    .cancel-modal:hover .cancel-icon-bg {
        animation-duration: 1.5s;
    }
    
    .cancel-modal:hover .particles-cancel .particle {
        animation-duration: 2s;
    }
    
    /* Responsive para móviles */
    @media (max-width: 768px) {
        .cancel-icon-bg i {
            font-size: 120px;
        }
        
        .cancel-icon-main i {
            font-size: 3rem;
        }
    }
    
    /* Efecto de cierre del modal */
    .modal.fade:not(.show) .cancel-modal {
        animation: modalSlideOut 0.3s ease-in forwards;
    }

    /* Animaciones base */
    @keyframes modalSlideIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes modalSlideOut {
        from {
            opacity: 1;
            transform: translateY(0);
        }
        to {
            opacity: 0;
            transform: translateY(20px);
        }
    }

    @keyframes contentFadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Configuración del modal de cancelación
    const modalCancelar = document.getElementById('modalCancelar');
    if (modalCancelar) {
        modalCancelar.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const id = button.getAttribute('data-id');
            const nombre = button.getAttribute('data-nombre');
            const titulo = button.getAttribute('data-titulo') || 'Confirmar Cancelación';
            const mensaje = button.getAttribute('data-mensaje') || 'El turno será marcado como cancelado y no podrá ser reactivado.';
            const action = button.getAttribute('data-action') || '<?= site_url("turnos/cancelar") ?>';
            
            document.getElementById('modalCancelarTitulo').textContent = titulo;
            document.getElementById('nombreElementoCancelar').textContent = nombre;
            document.getElementById('mensajeAdicionalCancelar').textContent = mensaje;
            document.getElementById('idElementoCancelar').value = id;
            document.getElementById('formCancelar').action = action + '/' + id;
        });
    }
});
</script>