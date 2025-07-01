document.addEventListener('DOMContentLoaded', function() {
    // Configuración inicial
    const config = {
        refreshIntervals: {
            stats: 30000,       // 30 segundos
            liveFeed: 15000,    // 15 segundos
            notifications: 60000 // 1 minuto
        },
        apiEndpoints: {
            stats: '/inicio/getStats',
            weeklyStats: '/inicio/getWeeklyStats',
            specialtyStats: '/inicio/getSpecialtyStats',
            notifications: '/inicio/getNotifications',
            weather: '/inicio/getWeather'
        }
    };

    // 1. Reloj en tiempo real mejorado
    function initClock() {
        function updateClock() {
            const now = new Date();
            const options = { 
                hour: '2-digit', 
                minute: '2-digit', 
                second: '2-digit',
                hour12: false
            };
            document.getElementById('current-time').textContent = now.toLocaleTimeString('es-AR', options);
        }
        updateClock();
        setInterval(updateClock, 1000);
    }

    // 2. Sistema de gráficos mejorado
    function initCharts() {
        // Gráfico semanal mejorado
        const weeklyCtx = document.getElementById('weeklyChart').getContext('2d');
        window.weeklyChart = new Chart(weeklyCtx, {
            type: 'bar',
            data: {
                labels: ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'],
                datasets: [{
                    label: 'Cirugías',
                    data: [],
                    backgroundColor: 'rgba(59, 130, 246, 0.7)',
                    borderColor: 'rgba(59, 130, 246, 1)',
                    borderWidth: 1,
                    borderRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: 'rgba(30, 41, 59, 0.95)',
                        titleColor: '#fff',
                        bodyColor: '#e2e8f0',
                        borderColor: 'rgba(255, 255, 255, 0.1)',
                        borderWidth: 1,
                        padding: 12,
                        callbacks: {
                            label: function(context) {
                                return `${context.parsed.y} cirugías`;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(226, 232, 240, 0.2)',
                            drawBorder: false
                        },
                        ticks: {
                            color: '#64748b'
                        }
                    },
                    x: {
                        grid: {
                            display: false,
                            drawBorder: false
                        },
                        ticks: {
                            color: '#64748b'
                        }
                    }
                }
            }
        });

        // Gráfico de especialidades mejorado
        const specialtyCtx = document.getElementById('specialtyChart').getContext('2d');
        window.specialtyChart = new Chart(specialtyCtx, {
            type: 'doughnut',
            data: {
                labels: [],
                datasets: [{
                    data: [],
                    backgroundColor: [
                        'rgba(239, 68, 68, 0.7)',
                        'rgba(59, 130, 246, 0.7)',
                        'rgba(245, 158, 11, 0.7)',
                        'rgba(16, 185, 129, 0.7)',
                        'rgba(139, 92, 246, 0.7)'
                    ],
                    borderColor: [
                        'rgba(239, 68, 68, 1)',
                        'rgba(59, 130, 246, 1)',
                        'rgba(245, 158, 11, 1)',
                        'rgba(16, 185, 129, 1)',
                        'rgba(139, 92, 246, 1)'
                    ],
                    borderWidth: 1,
                    hoverOffset: 10
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '75%',
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            padding: 16,
                            usePointStyle: true,
                            pointStyle: 'circle',
                            color: '#334155'
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(30, 41, 59, 0.95)',
                        titleColor: '#fff',
                        bodyColor: '#e2e8f0',
                        borderColor: 'rgba(255, 255, 255, 0.1)',
                        borderWidth: 1,
                        padding: 12,
                        callbacks: {
                            label: function(context) {
                                const label = context.label || '';
                                const value = context.raw || 0;
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = Math.round((value / total) * 100);
                                return `${label}: ${value} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });
    }

    // 3. Sistema de carga de datos con caché y reintentos
    async function fetchData(url, options = {}, retries = 3) {
        try {
            const response = await fetch(url, {
                ...options,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/json',
                    ...options.headers
                }
            });
            
            if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
            return await response.json();
        } catch (error) {
            if (retries > 0) {
                console.warn(`Reintentando (${retries} intentos restantes)...`, error);
                await new Promise(resolve => setTimeout(resolve, 1000));
                return fetchData(url, options, retries - 1);
            }
            throw error;
        }
    }

    // 4. Carga de estadísticas principales
    let statsCache = null;
    let lastStatsFetch = 0;
    const STATS_CACHE_DURATION = 15000; // 15 segundos

    async function loadStats() {
        const now = Date.now();
        
        // Usar caché si está disponible y no ha expirado
        if (statsCache && (now - lastStatsFetch) < STATS_CACHE_DURATION) {
            updateStatsUI(statsCache);
            return;
        }

        try {
            const data = await fetchData(config.apiEndpoints.stats);
            if (data.status === 'success') {
                statsCache = data;
                lastStatsFetch = now;
                updateStatsUI(data);
                
                // Mostrar notificación si hay insumos bajos
                if (data.insumos_bajo_stock > 0) {
                    showNotificationToast({
                        type: 'warning',
                        message: `Alerta: ${data.insumos_bajo_stock} insumos con stock bajo`,
                        icon: 'bx-package'
                    });
                }
            }
        } catch (error) {
            console.error('Error al cargar estadísticas:', error);
            showErrorToast('Error al actualizar estadísticas. Intentando nuevamente...');
        }
    }

    function updateStatsUI(data) {
        // Actualizar cada estadística con animación
        const statsElements = {
            'turnos-hoy': data.turnos_hoy,
            'cirujanos-count': data.cirujanos_count,
            'enfermeros-count': data.enfermeros_disponibles,
            'pacientes-count': data.pacientes_count
        };

        Object.entries(statsElements).forEach(([id, value]) => {
            const element = document.getElementById(id);
            if (element) {
                const currentValue = parseInt(element.textContent) || 0;
                if (currentValue !== value) {
                    animateValue(element, currentValue, value, 800);
                }
            }
        });

        // Efecto visual de actualización
        animateStatUpdate();
    }

    // 5. Animación de conteo para valores numéricos
    function animateValue(element, start, end, duration) {
        let startTimestamp = null;
        const step = (timestamp) => {
            if (!startTimestamp) startTimestamp = timestamp;
            const progress = Math.min((timestamp - startTimestamp) / duration, 1);
            element.textContent = Math.floor(progress * (end - start) + start);
            if (progress < 1) {
                window.requestAnimationFrame(step);
            }
        };
        window.requestAnimationFrame(step);
    }

    // 6. Carga de datos semanales para gráfico
    async function loadWeeklyStats() {
        try {
            const data = await fetchData(config.apiEndpoints.weeklyStats);
            
            if (window.weeklyChart) {
                window.weeklyChart.data.labels = data.labels;
                window.weeklyChart.data.datasets[0].data = data.data;
                window.weeklyChart.update();
                
                // Efecto visual de actualización
                highlightChartUpdate('weeklyChart');
            }
        } catch (error) {
            console.error('Error al cargar estadísticas semanales:', error);
        }
    }

    // 7. Carga de estadísticas por especialidad
    async function loadSpecialtyStats() {
        try {
            const data = await fetchData(config.apiEndpoints.specialtyStats);
            
            if (window.specialtyChart) {
                window.specialtyChart.data.labels = data.labels;
                window.specialtyChart.data.datasets[0].data = data.data;
                window.specialtyChart.update();
                
                // Efecto visual de actualización
                highlightChartUpdate('specialtyChart');
            }
        } catch (error) {
            console.error('Error al cargar estadísticas por especialidad:', error);
        }
    }

    // 8. Efecto visual para actualización de gráficos
    function highlightChartUpdate(chartId) {
        const chartContainer = document.querySelector(`#${chartId}`)?.parentElement;
        if (chartContainer) {
            chartContainer.classList.add('chart-updated');
            setTimeout(() => chartContainer.classList.remove('chart-updated'), 1000);
        }
    }

    // 9. Sistema de notificaciones con cola
    const notificationQueue = [];
    let isShowingNotification = false;

    async function loadNotifications() {
        try {
            const data = await fetchData(config.apiEndpoints.notifications);
            
            if (data.status === 'success' && data.notifications.length > 0) {
                // Agregar nuevas notificaciones a la cola
                data.notifications.forEach(notification => {
                    notificationQueue.push(notification);
                });
                
                // Procesar cola si no hay notificación mostrándose
                if (!isShowingNotification) {
                    processNotificationQueue();
                }
            }
        } catch (error) {
            console.error('Error al cargar notificaciones:', error);
        }
    }

    function processNotificationQueue() {
        if (notificationQueue.length > 0 && !isShowingNotification) {
            isShowingNotification = true;
            const notification = notificationQueue.shift();
            showNotificationToast(notification);
            
            // Programar siguiente notificación después de 5 segundos
            setTimeout(() => {
                isShowingNotification = false;
                processNotificationQueue();
            }, 5000);
        }
    }

    // 10. Mostrar notificación toast
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
        
        // Mostrar con animación
        setTimeout(() => toast.classList.add('show'), 100);
        
        // Configurar cierre
        const closeToast = () => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 300);
        };
        
        // Cerrar al hacer click en el botón
        toast.querySelector('.toast-close').addEventListener('click', closeToast);
        
        // Auto-ocultar después de 5 segundos
        setTimeout(closeToast, 5000);
    }

    function showErrorToast(message) {
        showNotificationToast({
            type: 'danger',
            message: message,
            icon: 'bx-error'
        });
    }

    // 11. Feed de actividad en tiempo real
    async function loadLiveFeed() {
        try {
            // En una implementación real, esto haría una petición al servidor
            // Por ahora simulamos datos
            const activities = await simulateLiveFeedData();
            
            const feedContainer = document.getElementById('live-feed-items');
            if (!feedContainer) return;
            
            // Limpiar solo si está vacío para evitar parpadeo
            if (feedContainer.children.length === 0) {
                feedContainer.innerHTML = '';
            }
            
            // Limitar a 10 elementos máximo
            if (feedContainer.children.length + activities.length > 10) {
                const excess = feedContainer.children.length + activities.length - 10;
                for (let i = 0; i < excess; i++) {
                    feedContainer.removeChild(feedContainer.lastChild);
                }
            }
            
            // Añadir nuevos elementos con animación
            activities.forEach(activity => {
                const feedItem = document.createElement('div');
                feedItem.className = 'feed-item';
                feedItem.dataset.type = activity.tipo;
                feedItem.innerHTML = `
                    <div class="feed-item-icon bg-${activity.color}">
                        <i class="bx ${activity.icono}"></i>
                    </div>
                    <div class="feed-item-content">
                        <div class="feed-item-text">${activity.mensaje}</div>
                        <div class="feed-item-time">${activity.tiempo} • ${activity.usuario}</div>
                    </div>
                `;
                feedContainer.insertBefore(feedItem, feedContainer.firstChild);
            });
        } catch (error) {
            console.error('Error al cargar el feed de actividad:', error);
        }
    }

    // Función para simular datos del feed (en producción vendría del servidor)
    async function simulateLiveFeedData() {
        // Tipos de actividad posibles
        const activityTypes = [
            {
                tipo: 'cirugia',
                mensajes: [
                    'Cirugía de %s completada exitosamente',
                    'Cirugía de %s programada para %s',
                    'Cirugía de %s en progreso'
                ],
                icono: 'bx-check-circle',
                color: 'success'
            },
            {
                tipo: 'paciente',
                mensajes: [
                    'Nuevo paciente registrado: %s',
                    'Paciente %s dado de alta',
                    'Historial médico actualizado para %s'
                ],
                icono: 'bx-user-plus',
                color: 'info'
            },
            {
                tipo: 'sistema',
                mensajes: [
                    'Alerta: %s con stock bajo',
                    'Mantenimiento programado para %s',
                    'Actualización del sistema completada'
                ],
                icono: 'bx-package',
                color: 'warning'
            }
        ];

        // Generar 1-3 actividades aleatorias
        const count = Math.floor(Math.random() * 3) + 1;
        const activities = [];
        
        for (let i = 0; i < count; i++) {
            const type = activityTypes[Math.floor(Math.random() * activityTypes.length)];
            const messageTemplate = type.mensajes[Math.floor(Math.random() * type.mensajes.length)];
            
            // Datos de ejemplo para rellenar los placeholders
            const placeholderData = {
                cirugia: ['apendicectomía', 'hernia', 'vesícula', 'bypass cardíaco', 'reemplazo de cadera'],
                paciente: ['Juan Pérez', 'María González', 'Carlos Sánchez', 'Ana Rodríguez'],
                sistema: ['guantes quirúrgicos', 'bisturís', 'anestésicos', 'equipo de rayos X']
            };
            
            // Reemplazar placeholders
            let message = messageTemplate;
            if (message.includes('%s')) {
                const dataSet = placeholderData[type.tipo] || [];
                message = message.replace('%s', dataSet[Math.floor(Math.random() * dataSet.length)]);
            }
            
            // Generar tiempo aleatorio (1-30 minutos)
            const minutesAgo = Math.floor(Math.random() * 30) + 1;
            const timeText = minutesAgo === 1 ? 'Hace 1 minuto' : `Hace ${minutesAgo} minutos`;
            
            // Usuario aleatorio
            const users = ['Dr. Pérez', 'Enf. García', 'Dr. Rodríguez', 'Técnico López', 'Sistema'];
            const user = users[Math.floor(Math.random() * users.length)];
            
            activities.push({
                tipo: type.tipo,
                mensaje: message,
                usuario: user,
                tiempo: timeText,
                icono: type.icono,
                color: type.color
            });
        }
        
        return activities;
    }

    // 12. Widget del clima
    async function updateWeather() {
        try {
            // En producción, esto haría una petición a una API del clima
            const weatherData = await simulateWeatherData();
            
            const weatherWidget = document.querySelector('.weather-widget');
            if (weatherWidget) {
                weatherWidget.querySelector('.weather-icon').textContent = weatherData.icono;
                weatherWidget.querySelector('.weather-temp').textContent = `${weatherData.temperatura}°C`;
                weatherWidget.querySelector('.weather-desc').textContent = weatherData.descripcion;
                weatherWidget.querySelector('.weather-location').textContent = weatherData.ciudad;
                
                // Actualizar fondo según condiciones climáticas
                updateWeatherBackground(weatherData.condicion);
            }
        } catch (error) {
            console.error('Error al actualizar el clima:', error);
        }
    }

    // Función para simular datos del clima (en producción vendría de una API)
    async function simulateWeatherData() {
        const conditions = [
            { icon: '☀️', desc: 'Soleado', tempRange: [25, 35], bgClass: 'weather-sunny' },
            { icon: '🌤️', desc: 'Parcialmente nublado', tempRange: [20, 25], bgClass: 'weather-partly-cloudy' },
            { icon: '⛅', desc: 'Nublado', tempRange: [18, 22], bgClass: 'weather-cloudy' },
            { icon: '🌧️', desc: 'Lluvioso', tempRange: [15, 20], bgClass: 'weather-rainy' },
            { icon: '🌩️', desc: 'Tormenta eléctrica', tempRange: [18, 25], bgClass: 'weather-stormy' }
        ];
        
        const condition = conditions[Math.floor(Math.random() * conditions.length)];
        const temp = Math.floor(Math.random() * (condition.tempRange[1] - condition.tempRange[0] + 1)) + condition.tempRange[0];
        
        return {
            icono: condition.icon,
            temperatura: temp,
            descripcion: condition.desc,
            ciudad: 'San Juan, Argentina',
            condicion: condition.bgClass
        };
    }

    // Actualizar fondo del widget según condiciones climáticas
    function updateWeatherBackground(conditionClass) {
        const weatherWidget = document.querySelector('.weather-widget');
        if (!weatherWidget) return;
        
        // Remover todas las clases de condición previas
        const weatherClasses = ['weather-sunny', 'weather-partly-cloudy', 'weather-cloudy', 'weather-rainy', 'weather-stormy'];
        weatherWidget.classList.remove(...weatherClasses);
        
        // Añadir la nueva clase
        if (conditionClass) {
            weatherWidget.classList.add(conditionClass);
        }
    }

    // 13. Efectos hover mejorados
    function setupHoverEffects() {
        // Efecto en tarjetas de estadísticas
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
        
        // Efecto especial en botones de acción rápida
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

    // 14. Animación de actualización de estadísticas
    function animateStatUpdate() {
        const statCards = document.querySelectorAll('.stats-card');
        statCards.forEach(card => {
            card.classList.add('stat-updated');
            setTimeout(() => {
                card.classList.remove('stat-updated');
            }, 1000);
        });
    }

    // 15. Filtrado del feed de actividades
    function setupFeedFilters() {
        const filterButtons = document.querySelectorAll('.feed-filter-btn');
        filterButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                // Remover clase active de todos los botones
                filterButtons.forEach(b => b.classList.remove('active'));
                // Añadir clase active al botón clickeado
                this.classList.add('active');
                
                const filter = this.dataset.filter;
                filterFeedItems(filter);
            });
        });
    }

    function filterFeedItems(filter) {
        const feedItems = document.querySelectorAll('.feed-item');
        feedItems.forEach(item => {
            if (filter === 'all' || item.dataset.type === filter) {
                item.style.display = 'flex';
            } else {
                item.style.display = 'none';
            }
        });
    }

    // 16. Búsqueda en tiempo real
    function setupLiveSearch() {
        const searchInput = document.getElementById('dashboard-search');
        if (searchInput) {
            let searchTimeout;
            
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    const searchTerm = this.value.toLowerCase();
                    searchInDashboard(searchTerm);
                }, 300);
            });
        }
    }

    function searchInDashboard(term) {
        const searchableElements = document.querySelectorAll('.searchable');
        let hasResults = false;
        
        searchableElements.forEach(el => {
            const card = el.closest('.card, .stats-card, .activity-card, .chart-container');
            if (card) {
                const text = el.textContent.toLowerCase();
                if (text.includes(term)) {
                    card.style.display = 'block';
                    el.classList.add('highlight');
                    hasResults = true;
                } else {
                    card.style.display = 'none';
                    el.classList.remove('highlight');
                }
            }
        });
        
        // Mostrar mensaje si no hay resultados
        const noResultsMsg = document.getElementById('no-results-message');
        if (noResultsMsg) {
            noResultsMsg.style.display = hasResults ? 'none' : 'block';
        }
    }

    // Inicialización de todos los módulos
    function initializeDashboard() {
        try {
            initClock();
            initCharts();
            setupHoverEffects();
            setupFeedFilters();
            setupLiveSearch();
            
            // Carga inicial de datos
            loadStats();
            loadWeeklyStats();
            loadSpecialtyStats();
            loadNotifications();
            loadLiveFeed();
            updateWeather();
            
            // Configurar actualización periódica
            setInterval(loadStats, config.refreshIntervals.stats);
            setInterval(loadLiveFeed, config.refreshIntervals.liveFeed);
            setInterval(loadNotifications, config.refreshIntervals.notifications);
            setInterval(updateWeather, 3600000); // Actualizar clima cada hora
            
            console.log('Dashboard inicializado correctamente');
        } catch (error) {
            console.error('Error al inicializar el dashboard:', error);
            showErrorToast('Error al inicializar el dashboard. Por favor recargue la página.');
        }
    }

    // Iniciar el dashboard
    initializeDashboard();
});