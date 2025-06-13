<style>
/* ============= FOOTER STYLES ============= */
.footer {
    background-color: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    padding: 1rem 0;
    position: fixed;
    bottom: 0;
    right: 0;
    left: 220px; /* Ancho del sidebar */
    z-index: 99;
    border-top: 1px solid rgba(255, 255, 255, 0.2) !important;
    transition: all 0.3s ease;
}

.footer .container-fluid {
    padding-left: 2rem;
    padding-right: 2rem;
}

#fecha-hora-actual {
    font-size: 0.85rem;
    color: rgba(255, 255, 255, 0.8);
}

.footer .text-muted {
    color: rgba(255, 255, 255, 0.8) !important;
}

/* Efecto de hover en el footer */
.footer:hover {
    background-color: rgba(255, 255, 255, 0.15);
}

/* Responsive para móviles */
@media (max-width: 768px) {
    .footer {
        left: 0;
        padding-left: 1rem;
        padding-right: 1rem;
    }
    
    .footer .text-md-start, 
    .footer .text-md-end {
        text-align: center !important;
    }
    
    .footer .col-md-6 {
        margin-bottom: 0.5rem;
    }
}
</style>

<footer class="footer">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-md-6 text-center text-md-start">
                <span class="text-muted">&copy; <?= date('Y') ?> Sistema de Gestión Quirúrgica</span>
            </div>
            <div class="col-md-6 text-center text-md-end">
                <span id="fecha-hora-actual" class="text-muted"></span>
            </div>
        </div>
    </div>
</footer>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
// Función para formatear fecha y hora en español
function formatDateTime(date) {
    const dias = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
    const meses = ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'];
    
    const diaSemana = dias[date.getDay()];
    const dia = date.getDate();
    const mes = meses[date.getMonth()];
    const año = date.getFullYear();
    
    const hora = date.getHours().toString().padStart(2, '0');
    const minutos = date.getMinutes().toString().padStart(2, '0');
    const segundos = date.getSeconds().toString().padStart(2, '0');
    
    return `${diaSemana}, ${dia} de ${mes} de ${año} - ${hora}:${minutos}:${segundos}`;
}

// Actualizar fecha y hora cada segundo
function updateDateTime() {
    const ahora = new Date();
    const options = { timeZone: 'America/Argentina/Buenos_Aires' };
    const fechaHoraArgentina = new Date(ahora.toLocaleString('en-US', options));
    
    const fechaHoraElement = document.getElementById('fecha-hora-actual');
    if (fechaHoraElement) {
        fechaHoraElement.textContent = formatDateTime(fechaHoraArgentina);
    }
}

// Función para actualizar disponibilidad
function actualizarDisponibilidad() {
    $.ajax({
        url: '<?= base_url('actualizacion_automatica/actualizar_disponibilidad') ?>',
        type: 'POST',
        dataType: 'json',
        success: function(response) {
            console.log('Actualización:', response);
            if(response.success && response.updated > 0) {
                location.reload();
            }
        },
        error: function(xhr) {
            console.error('Error en actualización:', xhr.responseText);
        }
    });
}

// Inicialización al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    updateDateTime();
    setInterval(updateDateTime, 1000);
    
    actualizarDisponibilidad();
    setInterval(actualizarDisponibilidad, 300000);
    
    // Ajustar el footer cuando el sidebar se contrae/expande
    const sidebar = document.querySelector('.sidebar');
    const footer = document.querySelector('.footer');
    
    if (sidebar && footer) {
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.attributeName === 'style') {
                    const sidebarWidth = sidebar.offsetWidth;
                    footer.style.left = `${sidebarWidth}px`;
                }
            });
        });
        
        observer.observe(sidebar, {
            attributes: true,
            attributeFilter: ['style']
        });
    }
});
</script>