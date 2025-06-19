document.addEventListener('DOMContentLoaded', function() {
    // Actualizar hora en tiempo real
    function updateClock() {
        const now = new Date();
        document.getElementById('current-time').textContent = now.toLocaleTimeString();
    }
    setInterval(updateClock, 1000);
    updateClock();

    // Inicializar gráficos
    initCharts();
    
    // Cargar datos iniciales
    loadStats();
    loadWeeklyStats();
    loadSpecialtyStats();
    loadNotifications();
    loadLiveFeed();
    updateWeather();

    // Configurar actualización periódica
    setInterval(loadStats, 30000); // Actualizar cada 30 segundos
    setInterval(loadLiveFeed, 15000); // Actualizar feed cada 15 segundos

    // Efectos hover para tarjetas
    setupHoverEffects();
});

function initCharts() {
    // Gráfico semanal (se actualizará con datos reales)
    const weeklyCtx = document.getElementById('weeklyChart').getContext('2d');
    window.weeklyChart = new Chart(weeklyCtx, {
        type: 'bar',
        data: {
            labels: ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'],
            datasets: [{
                label: 'Cirugías',
                data: [0, 0, 0, 0, 0, 0, 0],
                backgroundColor: 'rgba(54, 162, 235, 0.7)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1,
                borderRadius: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                x: {
                    grid: {
                        display: false
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleFont: {
                        size: 14,
                        weight: 'bold'
                    },
                    bodyFont: {
                        size: 12
                    },
                    padding: 12,
                    cornerRadius: 8
                }
            }
        }
    });

    // Gráfico de especialidades (se actualizará con datos reales)
    const specialtyCtx = document.getElementById('specialtyChart').getContext('2d');
    window.specialtyChart = new Chart(specialtyCtx, {
        type: 'doughnut',
        data: {
            labels: ['Cardiovascular', 'Neurología', 'Ortopedia', 'General'],
            datasets: [{
                data: [0, 0, 0, 0],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.7)',
                    'rgba(54, 162, 235, 0.7)',
                    'rgba(255, 206, 86, 0.7)',
                    'rgba(75, 192, 192, 0.7)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '70%',
            plugins: {
                legend: {
                    position: 'right',
                    labels: {
                        padding: 20,
                        usePointStyle: true,
                        pointStyle: 'circle'
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleFont: {
                        size: 14,
                        weight: 'bold'
                    },
                    bodyFont: {
                        size: 12
                    },
                    padding: 12,
                    cornerRadius: 8
                }
            }
        }
    });
}

function loadStats() {
    fetch('/inicio/getStats', {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            // Actualizar estadísticas
            document.getElementById('turnos-hoy').textContent = data.turnos_hoy;
            document.getElementById('cirujanos-count').textContent = data.cirujanos_count;
            document.getElementById('enfermeros-count').textContent = data.enfermeros_disponibles;
            document.getElementById('pacientes-count').textContent = data.pacientes_count;
            
            // Animación de actualización
            animateStatUpdate();
        }
    })
    .catch(error => console.error('Error al cargar estadísticas:', error));
}

function loadWeeklyStats() {
    fetch('/inicio/getWeeklyStats', {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        // Actualizar gráfico semanal
        window.weeklyChart.data.labels = data.labels;
        window.weeklyChart.data.datasets[0].data = data.data;
        window.weeklyChart.update();
        
        // Efecto visual
        document.querySelector('#weeklyChart').parentElement.classList.add('chart-updated');
        setTimeout(() => {
            document.querySelector('#weeklyChart').parentElement.classList.remove('chart-updated');
        }, 1000);
    })
    .catch(error => console.error('Error al cargar estadísticas semanales:', error));
}

function loadSpecialtyStats() {
    fetch('/inicio/getSpecialtyStats', {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        // Actualizar gráfico de especialidades
        window.specialtyChart.data.labels = data.labels;
        window.specialtyChart.data.datasets[0].data = data.data;
        window.specialtyChart.update();
        
        // Efecto visual
        document.querySelector('#specialtyChart').parentElement.classList.add('chart-updated');
        setTimeout(() => {
            document.querySelector('#specialtyChart').parentElement.classList.remove('chart-updated');
        }, 1000);
    })
    .catch(error => console.error('Error al cargar estadísticas por especialidad:', error));
}

function loadNotifications() {
    fetch('/inicio/getNotifications', {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success' && data.notifications.length > 0) {
            // Mostrar notificación más reciente como toast
            const latestNotification = data.notifications[0];
            showNotificationToast(latestNotification);
        }
    })
    .catch(error => console.error('Error al cargar notificaciones:', error));
}

function showNotificationToast(notification) {
    const toast = document.createElement('div');
    toast.className = `notification-toast ${notification.type}`;
    toast.innerHTML = `
        <div class="toast-icon">
            <i class="bx ${notification.icon}"></i>
        </div>
        <div class="toast-message">${notification.message}</div>
        <div class="toast-close">&times;</div>
    `;
    
    document.body.appendChild(toast);
    
    // Mostrar toast con animación
    setTimeout(() => {
        toast.classList.add('show');
    }, 100);
    
    // Cerrar toast al hacer click
    toast.querySelector('.toast-close').addEventListener('click', () => {
        toast.classList.remove('show');
        setTimeout(() => {
            toast.remove();
        }, 300);
    });
    
    // Auto-ocultar después de 5 segundos
    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => {
            toast.remove();
        }, 300);
    }, 5000);
}

function loadLiveFeed() {
    // En una implementación real, esto haría una petición AJAX
    // Por ahora simulamos datos del controlador
    
    const feedContainer = document.getElementById('live-feed-items');
    feedContainer.innerHTML = '';
    
    // Datos simulados (en producción vendrían del servidor)
    const activities = [
        {
            tipo: 'cirugia_completada',
            mensaje: 'Cirugía de apendicectomía completada exitosamente',
            usuario: 'Dr. Pérez',
            tiempo: 'Hace 2 horas',
            icono: 'bx-check-circle',
            color: 'success'
        },
        {
            tipo: 'paciente_registrado',
            mensaje: 'Nuevo paciente registrado en el sistema',
            usuario: 'Enf. García',
            tiempo: 'Hace 4 horas',
            icono: 'bx-user-plus',
            color: 'info'
        },
        {
            tipo: 'insumo_bajo',
            mensaje: 'Stock bajo detectado en insumos quirúrgicos',
            usuario: 'Sistema',
            tiempo: 'Hace 6 horas',
            icono: 'bx-package',
            color: 'warning'
        },
        {
            tipo: 'mantenimiento',
            mensaje: 'Mantenimiento programado de equipos completado',
            usuario: 'Técnico',
            tiempo: 'Hace 8 horas',
            icono: 'bx-wrench',
            color: 'secondary'
        }
    ];
    
    activities.forEach(activity => {
        const feedItem = document.createElement('div');
        feedItem.className = 'feed-item';
        feedItem.innerHTML = `
            <div class="feed-item-icon bg-${activity.color}">
                <i class="bx ${activity.icono}"></i>
            </div>
            <div class="feed-item-content">
                <div class="feed-item-text">${activity.mensaje}</div>
                <div class="feed-item-time">${activity.tiempo} • ${activity.usuario}</div>
            </div>
        `;
        feedContainer.appendChild(feedItem);
    });
}

function updateWeather() {
    // En una implementación real, esto haría una petición a una API del clima
    // Por ahora usamos los datos simulados del controlador
    
    const weatherData = {
        icono: '🌤️',
        temperatura: 22,
        descripcion: 'Parcialmente nublado',
        ciudad: 'San Juan, Argentina'
    };
    
    const weatherWidget = document.querySelector('.weather-widget');
    if (weatherWidget) {
        weatherWidget.querySelector('.weather-icon').textContent = weatherData.icono;
        weatherWidget.querySelector('.weather-temp').textContent = `${weatherData.temperatura}°C`;
        weatherWidget.querySelectorAll('.weather-desc')[0].textContent = weatherData.descripcion;
        weatherWidget.querySelectorAll('.weather-desc')[1].textContent = weatherData.ciudad;
    }
}

function animateStatUpdate() {
    const statCards = document.querySelectorAll('.stats-card');
    statCards.forEach(card => {
        card.classList.add('stat-updated');
        setTimeout(() => {
            card.classList.remove('stat-updated');
        }, 1000);
    });
}

function setupHoverEffects() {
    // Efecto de elevación en tarjetas
    const cards = document.querySelectorAll('.stats-card, .action-card, .chart-container, .status-board, .resource-monitor');
    
    cards.forEach(card => {
        card.addEventListener('mouseenter', () => {
            card.style.transform = 'translateY(-5px)';
            card.style.boxShadow = '0 15px 30px rgba(0, 0, 0, 0.15)';
        });
        
        card.addEventListener('mouseleave', () => {
            card.style.transform = '';
            card.style.boxShadow = '';
        });
    });
    
    // Efecto en botones de acción rápida
    const actionCards = document.querySelectorAll('.action-card');
    actionCards.forEach(card => {
        card.addEventListener('mouseenter', () => {
            const icon = card.querySelector('.action-card-icon');
            if (icon) {
                icon.style.transform = 'scale(1.1) rotate(5deg)';
            }
        });
        
        card.addEventListener('mouseleave', () => {
            const icon = card.querySelector('.action-card-icon');
            if (icon) {
                icon.style.transform = '';
            }
        });
    });
}
