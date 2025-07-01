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
// ============= FUNCIONES BÁSICAS DEL FOOTER =============
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

// Función para actualizar disponibilidad - MEJORADA
function actualizarDisponibilidad() {
    // Solo ejecutar en páginas específicas para evitar interferencias
    const paginasPermitidas = ['turnos', 'quirofanos', 'dashboard'];
    const paginaActual = window.location.pathname.split('/').pop();
    
    if (!paginasPermitidas.some(pagina => paginaActual.includes(pagina))) {
        return; // No ejecutar en otras páginas
    }
    
    $.ajax({
        url: '<?= base_url('actualizacion_automatica/actualizar_disponibilidad') ?>',
        type: 'POST',
        dataType: 'json',
        success: function(response) {
            console.log('Actualización:', response);
            if(response.success && response.updated > 0) {
                // Solo recargar si no hay modales abiertos o formularios siendo enviados
                if (!$('.modal.show').length && !$('form[data-submitting="true"]').length) {
                    // Mostrar notificación antes de recargar
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            title: 'Actualizando datos...',
                            text: 'Se detectaron cambios en el sistema',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload();
                        });
                    } else {
                        location.reload();
                    }
                }
            }
        },
        error: function(xhr) {
            console.error('Error en actualización:', xhr.responseText);
        }
    });
}

// ============= FUNCIONES ESPECÍFICAS PARA TURNOS QUIRÚRGICOS =============
// Namespace para funciones de turnos
window.TurnosQuirurgicos = {
    // Función para cargar procedimientos
    cargarProcedimientos: function(idEspecialidad, baseUrl) {
        if (!idEspecialidad) {
            $('#procedimiento').html('<option value="">Seleccione un cirujano</option><option value="otro">Otro (especificar)</option>');
            return;
        }

        $('#procedimiento').html('<option value="">Cargando procedimientos...</option>');

        $.ajax({
            url: baseUrl + '/turnos/obtener-procedimientos/' + idEspecialidad,
            type: 'GET',
            dataType: 'json',
            beforeSend: function() {
                $('#procedimiento').prop('disabled', true);
            },
            complete: function() {
                $('#procedimiento').prop('disabled', false);
            },
            success: function(response) {
                if(response.error) {
                    $('#procedimiento').html('<option value="">Error: '+response.error+'</option><option value="otro">Otro (especificar)</option>');
                    return;
                }

                var options = '<option value="">Seleccionar procedimiento</option>';
                if (response && response.length > 0) {
                    $.each(response, function(i, proc) {
                        options += '<option value="'+proc.nombre+'">'+proc.nombre+'</option>';
                    });
                }
                options += '<option value="otro">Otro (especificar)</option>';
                
                $('#procedimiento').html(options);
                
                // Si hay un valor seleccionado previamente (por error de validación)
                var selected = $('#procedimiento').data('old-value');
                if(selected && selected !== 'otro') {
                    $('#procedimiento').val(selected);
                }
            },
            error: function(xhr, status, error) {
                console.error('Error AJAX:', status, error);
                $('#procedimiento').html('<option value="">Error al cargar procedimientos</option><option value="otro">Otro (especificar)</option>');
            }
        });
    },

    // Función para calcular hora de fin
    calcularHoraFin: function() {
        const horaInicio = $('#hora_inicio').val();
        const duracion = parseInt($('#duracion').val());
        
        if (horaInicio && !isNaN(duracion)) {
            const [horas, minutos] = horaInicio.split(':').map(Number);
            const fechaInicio = new Date();
            fechaInicio.setHours(horas, minutos, 0, 0);
            
            const fechaFin = new Date(fechaInicio.getTime() + duracion * 60000);
            const horaFin = fechaFin.toTimeString().substring(0, 5);
            
            $('#hora_fin_calculada').text(`Hora estimada de finalización: ${horaFin}`);
            $('#hora_finalizacion').val(horaFin);
        }
    },

    // Validación específica para formularios de turnos
    validarFormularioTurnos: function() {
        // Solo aplicar en páginas de turnos
        if (!window.location.pathname.includes('turnos')) {
            return true;
        }
        
        let valido = true;
        let errores = [];

        // Validar fecha
        const fecha = document.getElementById('fecha');
        if (fecha && !fecha.value) {
            errores.push('La fecha es obligatoria');
            valido = false;
        } else if (fecha) {
            const fechaSeleccionada = new Date(fecha.value);
            const fechaHoy = new Date();
            fechaHoy.setHours(0, 0, 0, 0);
            
            if (fechaSeleccionada < fechaHoy) {
                errores.push('La fecha no puede ser anterior a hoy');
                valido = false;
            }
        }

        // Validar hora
        const horaInicio = document.getElementById('hora_inicio');
        if (horaInicio && !horaInicio.value) {
            errores.push('La hora de inicio es obligatoria');
            valido = false;
        }

        // Validar duración
        const duracion = document.getElementById('duracion');
        if (duracion && (!duracion.value || duracion.value < 15 || duracion.value > 240)) {
            errores.push('La duración debe estar entre 15 y 240 minutos');
            valido = false;
        }

        // Validar campos obligatorios
        const camposObligatorios = ['id_paciente', 'id_cirujano', 'id_quirofano', 'id_anestesista', 'id_tecnico_anestesista', 'procedimiento'];
        camposObligatorios.forEach(campo => {
            const elemento = document.getElementById(campo);
            if (elemento && !elemento.value) {
                errores.push(`El campo ${campo.replace('id_', '').replace('_', ' ')} es obligatorio`);
                valido = false;
            }
        });

        // Validar procedimiento "otro"
        const procedimiento = document.getElementById('procedimiento');
        const otroProcedimiento = document.getElementById('otro_procedimiento');
        if (procedimiento && otroProcedimiento && procedimiento.value === 'otro' && !otroProcedimiento.value.trim()) {
            errores.push('Debe especificar el procedimiento');
            valido = false;
        }

        // Mostrar errores si los hay
        if (!valido) {
            alert('Errores encontrados:\n' + errores.join('\n'));
        }

        return valido;
    },

    // Inicialización específica para formularios de turnos
    inicializarFormularioTurnos: function(baseUrl) {
        // Solo inicializar en páginas de turnos
        if (!window.location.pathname.includes('turnos')) {
            return;
        }

        const self = this;

        // Evento cambio de cirujano
        $('#id_cirujano').off('change.turnos').on('change.turnos', function() {
            var especialidad = $(this).find(':selected').data('especialidad');
            self.cargarProcedimientos(especialidad, baseUrl);
        });

        // Evento cambio de procedimiento
        $('#procedimiento').off('change.turnos').on('change.turnos', function() {
            if($(this).val() == 'otro') {
                $('#otro_procedimiento').removeClass('d-none').prop('required', true);
            } else {
                $('#otro_procedimiento').addClass('d-none').prop('required', false).val('');
            }
        });

        // Eventos para calcular hora de fin
        $('#hora_inicio, #duracion').off('change.turnos').on('change.turnos', self.calcularHoraFin);

        // Validación específica para formularios de turnos
        $('form[data-form-type="turnos"]').off('submit.turnos').on('submit.turnos', function(e) {
            if (!self.validarFormularioTurnos()) {
                e.preventDefault();
                return false;
            }
            
            // Marcar formulario como enviándose para evitar interrupciones
            $(this).attr('data-submitting', 'true');
            
            // Calcular hora de fin antes de enviar
            self.calcularHoraFin();
            
            // Mostrar indicador de carga
            const submitButton = this.querySelector('button[type="submit"]');
            const textoOriginal = submitButton.textContent;
            submitButton.textContent = 'Procesando...';
            submitButton.disabled = true;
            
            // Si hay un error, restaurar el botón después de un tiempo
            setTimeout(function() {
                submitButton.textContent = textoOriginal;
                submitButton.disabled = false;
                $(this).removeAttr('data-submitting');
            }.bind(this), 5000);
        });
    }
};

// ============= INICIALIZACIÓN SEGURA =============
document.addEventListener('DOMContentLoaded', function() {
    // Funciones básicas que siempre deben ejecutarse
    updateDateTime();
    setInterval(updateDateTime, 1000);
    
    // Actualización con menor frecuencia y más segura
    actualizarDisponibilidad();
    setInterval(actualizarDisponibilidad, 300000); // 5 minutos
    
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
    
    // Inicializar funciones específicas de turnos solo si estamos en esa sección
    if (window.location.pathname.includes('turnos') && $('#fecha').length) {
        const baseUrl = $('meta[name="base-url"]').attr('content') || '';
        window.TurnosQuirurgicos.inicializarFormularioTurnos(baseUrl);
    }
});
</script>