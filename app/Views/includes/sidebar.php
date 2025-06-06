<div class="sidebar">
    <div class="sidebar-header">
        <h3 class="sidebar-title">Sistema Quirúrgico</h3>
    </div>
    
    <div class="sidebar-content">
        <ul class="nav flex-column">
            <!-- Inicio - visible para todos -->
            <li class="nav-item">
                <a href="<?= base_url('inicio') ?>" class="nav-link">
                    <i class="bx bx-home"></i>
                    <span class="nav-text">Inicio</span>
                </a>
            </li>

            <?php 
            $rol = session()->get('rol');
            
            // Mostrar Turnos para enfermeros, médicos, supervisores y administradores
            if(in_array($rol, ['administrador', 'supervisor', 'medico', 'enfermero'])): ?>
            <li class="nav-item">
                <a href="<?= base_url('turnos') ?>" class="nav-link">
                    <i class="bx bx-plus-medical"></i>
                    <span class="nav-text">Turnos</span>
                </a>
            </li>
            <?php endif; ?>

            <!-- Mostrar Insumos para administradores, supervisores y médicos -->
            <?php if(in_array($rol, ['administrador', 'supervisor', 'medico'])): ?>
            <li class="nav-item">
                <a href="<?= base_url('insumos') ?>" class="nav-link">
                    <i class="bx bx-box"></i>
                    <span class="nav-text">Insumos</span>
                </a>
            </li>
            <?php endif; ?>

            <!-- Mostrar Pacientes para enfermeros, médicos, supervisores y administradores -->
            <?php if(in_array($rol, ['administrador', 'supervisor', 'medico', 'enfermero'])): ?>
            <li class="nav-item">
                <a href="<?= base_url('pacientes') ?>" class="nav-link">
                    <i class="bx bx-clipboard"></i>
                    <span class="nav-text">Pacientes</span>
                </a>
            </li>
            <?php endif; ?>

            <!-- Menú desplegable para Equipo - solo para administradores, medicos y supervisores -->
            <?php if(in_array($rol, ['administrador', 'supervisor', 'medico'])): ?>
            <li class="nav-item">
                <button class="dropdown-toggle nav-link" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bx bx-group"></i>
                    <span class="nav-text">Equipo</span>
                    <i class="bx bx-chevron-down"></i>
                </button>
                <ul class="sidebar-dropdown">
                    <li>
                        <a href="<?= base_url('medicos') ?>" class="nav-link">
                            <i class='bx bxs-user-plus bx-tada'></i>
                            <span class="nav-text">Médicos</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url('enfermeros') ?>" class="nav-link">
                            <i class='bx bx-heart bx-tada'></i>
                            <span class="nav-text">Enfermeros</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url('anestesistas') ?>" class="nav-link">
                            <i class='bx bx-injection bx-tada'></i>
                            <span class="nav-text">Anestesistas</span>
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url('instrumentista') ?>" class="nav-link">
                            <i class='bx bx-wrench bx-tada'></i>
                            <span class="nav-text">Instrumentistas</span>
                        </a>
                    </li>
                </ul>
            </li>
            <?php endif; ?>

            <!-- Módulo de Usuarios - solo para administradores y supervisores -->
            <?php if(in_array($rol, ['administrador', 'supervisor'])): ?>
            <li class="nav-item">
                <a href="<?= base_url('usuarios') ?>" class="nav-link">
                    <i class="bx bx-user"></i>
                    <span class="nav-text">Usuarios</span>
                </a>
            </li>
            <?php endif; ?>

            <!-- Botón para cambiar tema - visible para todos -->
            <li class="nav-item">
                <button class="nav-link theme-toggle-btn" style="width: 100%; text-align: left;">
                    <i class="bx bx-palette"></i>
                    <span class="nav-text">Cambiar tema</span>
                </button>
            </li>
        </ul>
    </div>
    
    <!-- Perfil y logout en la parte inferior -->
    <div class="sidebar-footer">
        <div class="user-profile-sidebar" data-bs-toggle="modal" data-bs-target="#logoutModal">
            <img src="<?= base_url('images/logo.png') ?>" alt="Perfil" onerror="this.src='https://via.placeholder.com/40'">
            <div class="user-info-sidebar">
                <span class="user-name-sidebar"><?= esc(session()->get('nombre')) ?></span>
                <small class="user-role-sidebar"><?= esc(session()->get('rol')) ?></small>
            </div>
        </div>
    </div>
</div>

<!-- Modal para cerrar sesión -->
<div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="logoutModalLabel">Cerrar Sesión</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>¿Está seguro que desea cerrar la sesión?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <a href="<?= base_url('logout') ?>" class="btn btn-danger">Cerrar Sesión</a>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Cambio automático de tema (sin modal)
    const themeToggleBtn = document.querySelector('.theme-toggle-btn');
    if (themeToggleBtn) {
        themeToggleBtn.addEventListener('click', function() {
            const currentTheme = document.documentElement.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            document.documentElement.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
        });
    }
    
    // Manejar menú desplegable
    const dropdownToggles = document.querySelectorAll('.dropdown-toggle');
    dropdownToggles.forEach(toggle => {
        toggle.addEventListener('click', function() {
            const dropdown = this.nextElementSibling;
            if (dropdown) {
                dropdown.classList.toggle('show');
                const expanded = this.getAttribute('aria-expanded') === 'true';
                this.setAttribute('aria-expanded', !expanded);
            }
        });
    });
    
    // Cargar tema guardado
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme) {
        document.documentElement.setAttribute('data-theme', savedTheme);
    } else {
        // Detectar preferencia del sistema
        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        document.documentElement.setAttribute('data-theme', prefersDark ? 'dark' : 'light');
    }
});
</script>