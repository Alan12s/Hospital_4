// Función para configurar el modal de eliminación
function configurarModalEliminar(options) {
    const {
        idElemento,
        nombreElemento,
        actionUrl,
        titulo = 'Confirmar Eliminación',
        mensajeAdicional = 'Esta acción no se puede deshacer y se perderán todos los datos asociados.',
        icono = 'bx-trash'
    } = options;

    document.getElementById('modalEliminarTitulo').textContent = titulo;
    document.getElementById('idElemento').value = idElemento;
    document.getElementById('nombreElemento').textContent = nombreElemento;
    document.getElementById('mensajeAdicional').textContent = mensajeAdicional;
    document.getElementById('formEliminar').action = actionUrl;
    document.querySelector('.delete-icon-main i').className = `bx ${icono}`;
}

// Función para configurar el modal de cancelación
function configurarModalCancelar(options) {
    const {
        idElemento,
        nombreElemento,
        actionUrl,
        titulo = 'Confirmar Cancelación',
        mensajeAdicional = 'El turno será marcado como cancelado y no podrá ser reactivado.',
        icono = 'bx-calendar-x'
    } = options;

    document.getElementById('modalCancelarTitulo').textContent = titulo;
    document.getElementById('idElementoCancelar').value = idElemento;
    document.getElementById('nombreElementoCancelar').textContent = nombreElemento;
    document.getElementById('mensajeAdicionalCancelar').textContent = mensajeAdicional;
    document.getElementById('formCancelar').action = actionUrl;
    document.querySelector('.cancel-icon-main i').className = `bx ${icono}`;
}

// Auto-hide alerts after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    const alertas = document.querySelectorAll('.alert');
    alertas.forEach(alerta => {
        setTimeout(() => {
            alerta.classList.remove('show');
        }, 5000);
    });
    // Función para configurar el modal de cancelación
function configurarModalCancelar(options) {
    const {
        idElemento,
        nombreElemento,
        actionUrl,
        titulo = 'Confirmar Cancelación',
        mensajeAdicional = 'El turno será marcado como cancelado y no podrá ser reactivado.',
        icono = 'bx-calendar-x'
    } = options;

    document.getElementById('modalCancelarTitulo').textContent = titulo;
    document.getElementById('idElementoCancelar').value = idElemento;
    document.getElementById('nombreElementoCancelar').textContent = nombreElemento;
    document.getElementById('mensajeAdicionalCancelar').textContent = mensajeAdicional;
    document.getElementById('formCancelar').action = actionUrl;
    document.querySelector('.cancel-icon-main i').className = `bx ${icono}`;
}
});