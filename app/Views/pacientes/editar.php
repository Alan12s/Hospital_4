<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title><?= esc($title ?? 'Editar Paciente') ?> - Sistema Quirúrgico</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet' />
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />
    <!-- Estilos personalizados -->
    <link href="styles.css" rel="stylesheet" />
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <?= $this->include('includes/sidebar') ?>
        
        <!-- Main Content -->
        <div class="main-content">
            <div class="content-container">
                <!-- Page Header -->
                <div class="dashboard-header animate-fade-in">
                    <h1>
                        <i class='bx bx-user-plus'></i><?= esc($title ?? 'Editar Paciente') ?>
                    </h1>
                    <p class="subtitle">Complete el formulario para actualizar los datos del paciente</p>
                </div>

                <!-- Flash Messages -->
                <?php if (session()->getFlashdata('error')) : ?>
                    <div class="alert alert-danger alert-dismissible fade show animate-fade-in">
                        <div class="d-flex align-items-center">
                            <i class='bx bx-error-circle me-2'></i>
                            <?= esc(session()->getFlashdata('error')) ?>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                    </div>
                <?php endif; ?>

                <!-- Form Card -->
                <div class="glass-card-solid animate-fade-in" style="animation-delay: 0.1s">
                    <div class="card-header">
                        <h3><i class='bx bx-edit me-2'></i>Formulario de Edición</h3>
                    </div>
                    <div class="form-container">
                        <?php if (session()->getFlashdata('errors')) : ?>
                            <div class="alert alert-danger alert-dismissible fade show">
                                <ul class="mb-0">
                                    <?php foreach (session()->getFlashdata('errors') as $error) : ?>
                                        <li><?= esc($error) ?></li>
                                    <?php endforeach ?>
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                            </div>
                        <?php endif; ?>

                        <?= form_open("pacientes/update/{$paciente->id}") ?>
                            <!-- Form fields as original, unchanged -->
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="nombre" class="form-label">Nombre <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control <?= session()->getFlashdata('errors')['nombre'] ?? false ? 'is-invalid' : '' ?>" 
                                           id="nombre" 
                                           name="nombre" 
                                           value="<?= old('nombre', $paciente->nombre) ?>" 
                                           required>
                                    <?php if (session()->getFlashdata('errors')['nombre'] ?? false): ?>
                                        <div class="invalid-feedback"><?= esc(session()->getFlashdata('errors')['nombre']) ?></div>
                                    <?php endif; ?>
                                </div>
                                <!-- (rest of inputs unchanged) -->
                            </div>

                            <div class="d-flex justify-content-end mt-4">
                                <a href="<?= site_url('pacientes') ?>" class="btn btn-secondary me-2">
                                    <i class='bx bx-x'></i> Cancelar
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class='bx bx-save'></i> Actualizar Paciente
                                </button>
                            </div>
                        <?= form_close() ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-hide alerts after 5 seconds
            const alertas = document.querySelectorAll('.alert');
            alertas.forEach(alerta => {
                setTimeout(() => {
                    alerta.classList.remove('show');
                }, 5000);
            });

            // Validación en tiempo real del DNI (solo números)
            const dniInput = document.getElementById('dni');
            if (dniInput) {
                dniInput.addEventListener('input', function() {
                    this.value = this.value.replace(/[^0-9]/g, '');
                });
            }

            // Validación en tiempo real del teléfono (solo números)
            const telefonoInput = document.getElementById('telefono');
            if (telefonoInput) {
                telefonoInput.addEventListener('input', function() {
                    this.value = this.value.replace(/[^0-9]/g, '');
                });
            }
        });
    </script>
</body>
</html>
