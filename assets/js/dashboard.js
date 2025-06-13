document.addEventListener('DOMContentLoaded', function() {
    // Actualizar hora en tiempo real
    updateTime();
    setInterval(updateTime, 1000);
    
    // Actualizar estadísticas cada 30 segundos
    setInterval(updateStats, 30000);
    
    // Inicializar gráficos
    initCharts();
    
    // Aplicar animaciones escalonadas
    applyStaggeredAnimations();
    
    // Inicializar Live Feed
    initLiveFeed();
    
    // Simular cambios de estado
    simulateStatusChanges();
    
    function updateTime() {
        const now = new Date();
        const timeString = now.toLocaleTimeString('es-AR', {
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit'
        });
        const timeElement = document.getElementById('current-time');
        if (timeElement) {
            timeElement.textContent = timeString;
        }
    }
    
    function updateStats() {
        fetch('<?= base_url("inicio/getStats") ?>')
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    // Actualizar estadísticas con animación
                    animateCounter('turnos-hoy', data.turnos_hoy);
                    animateCounter('cirujanos-count', data.cirujanos_count);
                    animateCounter('enfermeros-count', data.enfermeros_disponibles);
                    animateCounter('pacientes-count', data.pacientes_count);
                    
                    // Actualizar fecha y hora si es necesario
                    const timeElement = document.getElementById('current-time');
                    if (timeElement) {
                        timeElement.textContent = data.hora_actual;
                    }
                }
            })
            .catch(error => console.error('Error al actualizar estadísticas:', error));
    }
    
    function animateCounter(elementId, targetValue) {
        const element = document.getElementById(elementId);
        if (!element) return;
        
        targetValue = Math.max(0, parseInt(targetValue) || 0);
        const currentValue = Math.max(0, parseInt(element.textContent) || 0);
        
        if (currentValue === targetValue) return;
        
        const difference = targetValue - currentValue;
        const increment = difference > 0 ? 1 : -1;
        const steps = Math.abs(difference);
        const duration = Math.min(1000, steps * 50);
        
        const stepTime = duration / steps;
        
        let current = currentValue;
        const timer = setInterval(() => {
            current += increment;
            element.textContent = Math.max(0, current);
            
            if ((increment > 0 && current >= targetValue) || 
                (increment < 0 && current <= targetValue)) {
                clearInterval(timer);
                element.textContent = targetValue;
            }
        }, stepTime);
    }
    
    function initCharts() {
        // Gráfico semanal de cirugías
        const weeklyCtx = document.getElementById('weeklyChart');
        if (weeklyCtx) {
            Chart.defaults.font.family = 'Inter, sans-serif';
            Chart.defaults.color = '#64748b';
            
            fetch('<?= base_url("inicio/getWeeklyStats") ?>')
                .then(response => response.json())
                .then(data => {
                    new Chart(weeklyCtx, {
                        type: 'line',
                        data: {
                            labels: data.labels || ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'],
                            datasets: [{
                                label: 'Cirugías Realizadas',
                                data: data.data || [12, 19, 8, 15, 22, 13, 7],
                                borderColor: 'rgb(37, 99, 235)',
                                backgroundColor: 'rgba(37, 99, 235, 0.1)',
                                borderWidth: 3,
                                fill: true,
                                tension: 0.4,
                                pointBackgroundColor: 'rgb(37, 99, 235)',
                                pointBorderColor: '#fff',
                                pointBorderWidth: 2,
                                pointRadius: 6
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    grid: {
                                        color: 'rgba(0,0,0,0.1)'
                                    }
                                },
                                x: {
                                    grid: {
                                        color: 'rgba(0,0,0,0.1)'
                                    }
                                }
                            }
                        }
                    });
                })
                .catch(error => {
                    console.error('Error al cargar datos semanales:', error);
                    createDefaultWeeklyChart(weeklyCtx);
                });
        }
        
        // Gráfico de distribución por especialidad
        const specialtyCtx = document.getElementById('specialtyChart');
        if (specialtyCtx) {
            fetch('<?= base_url("inicio/getSpecialtyStats") ?>')
                .then(response => response.json())
                .then(data => {
                    new Chart(specialtyCtx, {
                        type: 'doughnut',
                        data: {
                            labels: data.labels || ['Traumatologia', 'Urologia', 'Ginecologia', 'General'],
                            datasets: [{
                                data: data.data || [15, 20, 25, 30],
                                backgroundColor: [
                                    'rgb(37, 99, 235)',
                                    'rgb(16, 185, 129)',
                                    'rgb(245, 158, 11)',
                                    'rgb(239, 68, 68)'
                                ],
                                borderWidth: 0,
                                hoverOffset: 10
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                    labels: {
                                        padding: 20,
                                        usePointStyle: true
                                    }
                                }
                            }
                        }
                    });
                })
                .catch(error => {
                    console.error('Error al cargar datos por especialidad:', error);
                    createDefaultSpecialtyChart(specialtyCtx);
                });
        }
    }
    
    function createDefaultWeeklyChart(ctx) {
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'],
                datasets: [{
                    label: 'Cirugías Realizadas',
                    data: [12, 19, 8, 15, 22, 13, 7],
                    borderColor: 'rgb(37, 99, 235)',
                    backgroundColor: 'rgba(37, 99, 235, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: 'rgb(37, 99, 235)',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0,0,0,0.1)'
                        }
                    },
                    x: {
                        grid: {
                            color: 'rgba(0,0,0,0.1)'
                        }
                    }
                }
            }
        });
    }
    
    function createDefaultSpecialtyChart(ctx) {
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Cardiovascular', 'Neurología', 'Ortopedia', 'General'],
                datasets: [{
                    data: [30, 25, 25, 20],
                    backgroundColor: [
                        'rgb(37, 99, 235)',
                        'rgb(16, 185, 129)',
                        'rgb(245, 158, 11)',
                        'rgb(239, 68, 68)'
                    ],
                    borderWidth: 0,
                    hoverOffset: 10
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true
                        }
                    }
                }
            }
        });
    }
    
    function applyStaggeredAnimations() {
        const elements = document.querySelectorAll('.animate-fade-in');
        elements.forEach((element, index) => {
            element.style.animationDelay = `${index * 0.1}s`;
        });
    }
    
    // Efectos de hover mejorados
    document.querySelectorAll('.stats-card').forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-8px) scale(1.02)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });
    
    // Live Feed Functions
    function initLiveFeed() {
        const feedItems = [
            {
                icon: 'bx-user-plus',
                color: 'var(--info-color)',
                text: 'Nuevo paciente registrado en el sistema',
                time: 'Hace 2 minutos'
            },
            {
                icon: 'bx-calendar-check',
                color: 'var(--primary-color)',
                text: 'Cirugía programada para mañana a las 08:00',
                time: 'Hace 15 minutos'
            },
            {
                icon: 'bx-package',
                color: 'var(--warning-color)',
                text: 'Stock bajo en insumos quirúrgicos',
                time: 'Hace 30 minutos'
            },
            {
                icon: 'bx-check-circle',
                color: 'var(--success-color)',
                text: 'Cirugía de apendicectomía completada con éxito',
                time: 'Hace 1 hora'
            },
            {
                icon: 'bx-user',
                color: 'var(--secondary-color)',
                text: 'Nuevo cirujano agregado al equipo',
                time: 'Hace 2 horas'
            }
        ];
        
        const feedContainer = document.getElementById('live-feed-items');
        
        // Mostrar items iniciales
        feedItems.forEach(item => {
            addFeedItem(feedContainer, item);
        });
        
        // Simular nuevos items cada cierto tiempo
        setInterval(() => {
            const randomItems = [
                {
                    icon: 'bx-calendar',
                    color: 'var(--primary-color)',
                    text: 'Nueva cirugía programada para ' + randomDay() + ' a las ' + randomTime(),
                    time: 'Ahora mismo'
                },
                {
                    icon: 'bx-heart',
                    color: 'var(--danger-color)',
                    text: 'Paciente en sala de emergencias',
                    time: 'Ahora mismo'
                },
                {
                    icon: 'bx-check',
                    color: 'var(--success-color)',
                    text: 'Cirugía completada satisfactoriamente',
                    time: 'Ahora mismo'
                },
                {
                    icon: 'bx-alarm',
                    color: 'var(--warning-color)',
                    text: 'Recordatorio: Reunión de equipo en 15 minutos',
                    time: 'Ahora mismo'
                }
            ];
            
            const randomItem = randomItems[Math.floor(Math.random() * randomItems.length)];
            addFeedItem(feedContainer, randomItem);
            
            // Mantener máximo 8 items en el feed
            if (feedContainer.children.length > 8) {
                feedContainer.removeChild(feedContainer.children[0]);
            }
        }, 10000); // Cada 10 segundos
    }
    
    function addFeedItem(container, item) {
        const feedItem = document.createElement('div');
        feedItem.className = 'feed-item';
        
        feedItem.innerHTML = `
            <div class="feed-item-icon" style="background: ${item.color}">
                <i class="bx ${item.icon}"></i>
            </div>
            <div class="feed-item-content">
                <div class="feed-item-text">${item.text}</div>
                <div class="feed-item-time">${item.time}</div>
            </div>
        `;
        
        container.appendChild(feedItem);
    }
    
    function randomDay() {
        const days = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];
        return days[Math.floor(Math.random() * days.length)];
    }
    
    function randomTime() {
        const hours = Math.floor(Math.random() * 12) + 1;
        const minutes = Math.floor(Math.random() * 60);
        const ampm = Math.random() > 0.5 ? 'AM' : 'PM';
        return `${hours}:${minutes < 10 ? '0' + minutes : minutes} ${ampm}`;
    }
    
    // Simular cambios de estado
    function simulateStatusChanges() {
        setInterval(() => {
            const statusIndicators = document.querySelectorAll('.status-indicator');
            statusIndicators.forEach(indicator => {
                // 10% de probabilidad de cambiar el estado
                if (Math.random() < 0.1) {
                    indicator.classList.toggle('active');
                    indicator.classList.toggle('warning');
                    
                    // Agregar animación de pulso temporal
                    indicator.classList.add('pulse-animation');
                    setTimeout(() => {
                        indicator.classList.remove('pulse-animation');
                    }, 2000);
                }
            });
        }, 5000); // Cada 5 segundos
    }
});