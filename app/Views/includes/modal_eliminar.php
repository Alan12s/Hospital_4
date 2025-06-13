<!-- Modal de Eliminación Mejorado (Reutilizable) -->
<div class="modal fade" id="modalEliminar" tabindex="-1" aria-labelledby="modalEliminarLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content delete-modal" style="background: linear-gradient(135deg, #1a1a1a 0%, #2d1b1b 100%); color: white; border: 1px solid #dc3545; border-radius: 15px; overflow: hidden; position: relative;">
            
            <!-- Ícono gigante animado de fondo -->
            <div class="delete-icon-bg">
                <i class="bx bx-trash"></i>
            </div>
            
            <!-- Efectos de partículas -->
            <div class="particles">
                <div class="particle"></div>
                <div class="particle"></div>
                <div class="particle"></div>
                <div class="particle"></div>
                <div class="particle"></div>
            </div>
            
            <div class="modal-header border-bottom" style="border-color: rgba(220, 53, 69, 0.3) !important; background: rgba(0,0,0,0.3); backdrop-filter: blur(10px);">
                <h5 class="modal-title delete-title" style="font-size: 1.2rem; font-weight: 600;">
                    <i class='bx bx-trash me-2 delete-icon-header'></i>
                    <span id="modalEliminarTitulo">Confirmar Eliminación</span>
                </h5>
                <button type="button" class="btn-close btn-close-white close-btn-animated" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form id="formEliminar" method="post" action="">
                <?= csrf_field() ?>
                <input type="hidden" id="idElemento" name="id">
                <div class="modal-body py-4 text-center" style="background: rgba(0,0,0,0.2); backdrop-filter: blur(5px);">
                    <div class="delete-content">
                        <div class="delete-icon-main mb-3">
                            <i class='bx bx-user-x'></i>
                        </div>
                        <p style="font-size: 1rem; margin-bottom: 1rem; font-weight: 500;">
                            ¿Está seguro que desea eliminar <strong><span id="nombreElemento"></span></strong>?
                        </p>
                        <p style="font-size: 0.85rem; color: #ff6b6b; margin-bottom: 0;" id="mensajeAdicional">
                            Esta acción no se puede deshacer y se perderán todos los datos asociados.
                        </p>
                    </div>
                </div>
                <div class="modal-footer border-top justify-content-center" style="border-color: rgba(220, 53, 69, 0.3) !important; background: rgba(0,0,0,0.3); backdrop-filter: blur(10px);">
                    <button type="button" class="btn btn-outline-light delete-btn-cancel me-2" data-bs-dismiss="modal">
                        <i class='bx bx-x me-1'></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-danger delete-btn-confirm">
                        <i class='bx bx-trash me-1'></i> Eliminar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    /* ================================
       ANIMACIONES DEL MODAL DE ELIMINAR
       ================================ */
    
    /* Modal con efecto de entrada suave */
    .delete-modal {
        animation: modalSlideIn 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94) forwards;
        box-shadow: 0 20px 50px rgba(220, 53, 69, 0.3), 0 0 100px rgba(220, 53, 69, 0.1);
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
    .delete-icon-bg {
        position: absolute;
        top: -20px;
        right: -20px;
        z-index: 1;
        opacity: 0.1;
        animation: iconPulse 3s ease-in-out infinite;
    }
    
    .delete-icon-bg i {
        font-size: 180px;
        color: #dc3545;
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
        background: #dc3545;
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
    .delete-title {
        animation: titleGlow 2s ease-in-out infinite alternate;
        position: relative;
        z-index: 3;
    }
    
    @keyframes titleGlow {
        0% {
            text-shadow: 0 0 5px rgba(220, 53, 69, 0.5);
        }
        100% {
            text-shadow: 0 0 20px rgba(220, 53, 69, 0.8), 0 0 30px rgba(220, 53, 69, 0.4);
        }
    }
    
    /* Ícono del header */
    .delete-icon-header {
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
    .delete-content {
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
    .delete-icon-main {
        animation: mainIconRotate 3s ease-in-out infinite;
    }
    
    .delete-icon-main i {
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
    
    /* Botones animados */
    .delete-btn-cancel, .delete-btn-confirm {
        position: relative;
        z-index: 3;
        transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        font-weight: 500;
        border-radius: 25px;
        padding: 10px 25px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }
    
    .delete-btn-cancel:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(255,255,255,0.2);
        background: rgba(255,255,255,0.1) !important;
    }
    
    .delete-btn-confirm:hover {
        transform: translateY(-2px) scale(1.05);
        box-shadow: 0 10px 30px rgba(220, 53, 69, 0.4);
        background: linear-gradient(45deg, #dc3545, #ff6b6b) !important;
    }
    
    .delete-btn-confirm:active {
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
    .delete-modal:hover .delete-icon-bg {
        animation-duration: 1.5s;
    }
    
    .delete-modal:hover .particle {
        animation-duration: 2s;
    }
    
    /* Responsive para móviles */
    @media (max-width: 768px) {
        .delete-icon-bg i {
            font-size: 120px;
        }
        
        .delete-icon-main i {
            font-size: 3rem;
        }
    }
    
    /* Efecto de cierre del modal */
    .modal.fade:not(.show) .delete-modal {
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

<script>
// Función para configurar el modal de eliminación (reutilizable)
function configurarModalEliminar(options) {
    const {
        idElemento,
        nombreElemento,
        actionUrl,
        titulo = 'Confirmar Eliminación',
        mensajeAdicional = 'Esta acción no se puede deshacer y se perderán todos los datos asociados.',
        icono = 'bx-user-x'
    } = options;

    document.getElementById('modalEliminarTitulo').textContent = titulo;
    document.getElementById('idElemento').value = idElemento;
    document.getElementById('nombreElemento').textContent = nombreElemento;
    document.getElementById('mensajeAdicional').textContent = mensajeAdicional;
    document.getElementById('formEliminar').action = actionUrl;
    document.querySelector('.delete-icon-main i').className = `bx ${icono}`;
}
</script>