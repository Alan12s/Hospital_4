// dashboard.js - Funcionalidades específicas para el dashboard

document.addEventListener('DOMContentLoaded', function() {
    // Inicializar el gráfico de estadísticas
    initStatsChart();
    
    // Configurar la actualización automática de datos
    setupAutoRefresh();
    
    // Aplicar clases de transición para el tema
    applyThemeTransitions();
    
    // Aplicar estilos específicos para el tema actual
    applyThemeStyles();
    
    // Inicializar y actualizar la fecha y hora
    initDateTime();
});

function initDateTime() {
    // Función para formatear la fecha y hora en español
    function formatDateTime(date) {
        const opcionesFecha = { 
            weekday: 'long', 
            day: 'numeric', 
            month: 'long', 
            year: 'numeric',
            timeZone: 'America/Argentina/Buenos_Aires'
        };
        
        const opcionesHora = {
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            timeZone: 'America/Argentina/Buenos_Aires'
        };
        
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

    // Actualizar la fecha y hora cada segundo
    function updateDateTime() {
        const ahora = new Date();
        const fechaHoraElement = document.getElementById('fecha-hora-actual');
        if (fechaHoraElement) {
            fechaHoraElement.textContent = formatDateTime(ahora);
        }
    }

    // Actualizar inmediatamente y luego cada segundo
    updateDateTime();
    setInterval(updateDateTime, 1000);
}

function initStatsChart() {
    const ctx = document.createElement('canvas');
    ctx.id = 'statsChart';
    const container = document.createElement('div');
    container.className = 'chart-container dashboard-transition';
    container.appendChild(ctx);
    document.querySelector('.stats-section').appendChild(container);
    
    // Configurar colores según el tema actual
    const isDarkTheme = document.documentElement.getAttribute('data-theme') === 'dark';
    const bgColors = isDarkTheme ? [
        'rgba(96, 165, 250, 0.7)',
        'rgba(74, 222, 128, 0.7)',
        'rgba(45, 212, 191, 0.7)',
        'rgba(251, 191, 36, 0.7)'
    ] : [
        'rgba(13, 110, 253, 0.7)',
        'rgba(25, 135, 84, 0.7)',
        'rgba(13, 202, 240, 0.7)',
        'rgba(255, 193, 7, 0.7)'
    ];
    
    const borderColors = isDarkTheme ? [
        'rgba(96, 165, 250, 1)',
        'rgba(74, 222, 128, 1)',
        'rgba(45, 212, 191, 1)',
        'rgba(251, 191, 36, 1)'
    ] : [
        'rgba(13, 110, 253, 1)',
        'rgba(25, 135, 84, 1)',
        'rgba(13, 202, 240, 1)',
        'rgba(255, 193, 7, 1)'
    ];
    
    const textColor = isDarkTheme ? '#e2e8f0' : '#333';
    const gridColor = isDarkTheme ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)';
    
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Turnos Hoy', 'Médicos', 'Enfermeros Disp.', 'Pacientes'],
            datasets: [{
                label: 'Resumen del Sistema',
                data: [
                    parseInt(document.getElementById('turnos-hoy').textContent),
                    parseInt(document.getElementById('medicos-activos').textContent),
                    parseInt(document.getElementById('enfermeros-disponibles').textContent),
                    parseInt(document.getElementById('pacientes-activos').textContent)
                ],
                backgroundColor: bgColors,
                borderColor: borderColors,
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    labels: {
                        color: textColor
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: gridColor
                    },
                    ticks: {
                        color: textColor
                    }
                },
                x: {
                    grid: {
                        color: gridColor
                    },
                    ticks: {
                        color: textColor
                    }
                }
            }
        }
    });
}

function setupAutoRefresh() {
    // Actualizar datos cada 60 segundos
    setInterval(() => {
        fetch(document.querySelector('body').getAttribute('data-stats-url'))
            .then(response => response.json())
            .then(data => {
                document.getElementById('turnos-hoy').textContent = data.turnos_hoy;
                document.getElementById('medicos-activos').textContent = data.medicos_count;
                document.getElementById('enfermeros-disponibles').textContent = data.enfermeros_disponibles;
                document.getElementById('pacientes-activos').textContent = data.pacientes_count;
                
                // Actualizar también la fecha y hora del servidor si está disponible
                if (data.fecha_actual && data.hora_actual) {
                    document.getElementById('fecha-hora-actual').textContent = 
                        `${data.fecha_actual} - ${data.hora_actual}`;
                }
            });
    }, 60000);
}

function applyThemeTransitions() {
    // Aplicar clase de transición a todos los elementos relevantes
    const elements = [
        ...document.querySelectorAll('.stats-card'),
        ...document.querySelectorAll('.module-card'),
        ...document.querySelectorAll('.welcome-section'),
        ...document.querySelectorAll('.alert'),
        ...document.querySelectorAll('.list-group-item'),
        ...document.querySelectorAll('.card'),
        ...document.querySelectorAll('.table')
    ];
    
    elements.forEach(el => {
        el.classList.add('dashboard-transition');
    });
}

function applyThemeStyles() {
    // Aplicar estilos específicos según el tema actual
    const isDarkTheme = document.documentElement.getAttribute('data-theme') === 'dark';
    
    if (isDarkTheme) {
        // Asegurar que los números en las estadísticas sean blancos
        document.querySelectorAll('.stats-number').forEach(el => {
            el.style.color = '#ffffff';
        });
        
        // Asegurar que las etiquetas sean claras pero no blancas
        document.querySelectorAll('.stats-label').forEach(el => {
            el.style.color = '#cbd5e1';
        });
        
        // Asegurar que la fecha y hora sean visibles
        const fechaHoraElement = document.getElementById('fecha-hora-actual');
        if (fechaHoraElement) {
            fechaHoraElement.style.color = '#e2e8f0';
        }
    } else {
        const fechaHoraElement = document.getElementById('fecha-hora-actual');
        if (fechaHoraElement) {
            fechaHoraElement.style.color = '#333';
        }
    }
}

// Escuchar cambios de tema para actualizar estilos
document.addEventListener('ThemeChanged', function() {
    applyThemeStyles();
    // Recargar el gráfico para aplicar nuevos colores
    const chartContainer = document.querySelector('.chart-container');
    if (chartContainer) {
        chartContainer.remove();
        initStatsChart();
    }
});

// Manejar el interruptor de tema
document.addEventListener('DOMContentLoaded', function() {
    const themeCheckbox = document.getElementById('theme-checkbox');
    const currentTheme = localStorage.getItem('theme') || 'light';
    
    // Establecer estado inicial del interruptor
    if (currentTheme === 'dark') {
        themeCheckbox.checked = true;
    }
    
    // Manejar cambio de tema
    themeCheckbox.addEventListener('change', function() {
        const newTheme = this.checked ? 'dark' : 'light';
        document.documentElement.setAttribute('data-theme', newTheme);
        localStorage.setItem('theme', newTheme);
        
        // Disparar evento personalizado para el cambio de tema
        document.dispatchEvent(new Event('ThemeChanged'));
        
        // Efecto visual adicional
        const slider = document.querySelector('.slider');
        if (slider) {
            slider.style.transform = 'scale(1.05)';
            setTimeout(() => {
                slider.style.transform = 'scale(1)';
            }, 200);
        }
    });
});