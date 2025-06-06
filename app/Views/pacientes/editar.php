<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><?= esc($title ?? 'Editar Paciente') ?> - Gestión Quirúrgica</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="<?= base_url('assets/css/styles.css') ?>" />
</head>
<body>
    <div class="app-container">
        <div class="main-content">
            <div class="main-wrapper">
                <div class="welcome-section">
                    <h1 class="page-title"><?= esc($title ?? 'Editar Paciente') ?></h1>

                    <?php if (session()->getFlashdata('errors')) : ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0">
                                <?php foreach (session()->getFlashdata('errors') as $error) : ?>
                                    <li><?= esc($error) ?></li>
                                <?php endforeach ?>
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                        </div>
                    <?php endif; ?>

                    <?php if (session()->getFlashdata('error')) : ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?= esc(session()->getFlashdata('error')) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                        </div>
                    <?php endif; ?>

                    <div class="data-card">
                        <div class="data-card-header mb-4">
                            <h5 class="mb-0">Formulario de Edición de Paciente</h5>
                        </div>
                        <div class="data-card-body">
                            <?= form_open("pacientes/update/{$paciente->id}") ?>
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
                                    <div class="col-md-6">
                                        <label for="dni" class="form-label">DNI <span class="text-danger">*</span></label>
                                        <input type="text" 
                                               class="form-control <?= session()->getFlashdata('errors')['dni'] ?? false ? 'is-invalid' : '' ?>" 
                                               id="dni" 
                                               name="dni" 
                                               value="<?= old('dni', $paciente->dni) ?>" 
                                               required>
                                        <?php if (session()->getFlashdata('errors')['dni'] ?? false): ?>
                                            <div class="invalid-feedback"><?= esc(session()->getFlashdata('errors')['dni']) ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="telefono" class="form-label">Teléfono <span class="text-danger">*</span></label>
                                        <input type="text" 
                                               class="form-control <?= session()->getFlashdata('errors')['telefono'] ?? false ? 'is-invalid' : '' ?>" 
                                               id="telefono" 
                                               name="telefono" 
                                               value="<?= old('telefono', $paciente->telefono) ?>" 
                                               required>
                                        <?php if (session()->getFlashdata('errors')['telefono'] ?? false): ?>
                                            <div class="invalid-feedback"><?= esc(session()->getFlashdata('errors')['telefono']) ?></div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="direccion" class="form-label">Dirección <span class="text-danger">*</span></label>
                                        <input type="text" 
                                               class="form-control <?= session()->getFlashdata('errors')['direccion'] ?? false ? 'is-invalid' : '' ?>" 
                                               id="direccion" 
                                               name="direccion" 
                                               value="<?= old('direccion', $paciente->direccion) ?>" 
                                               required>
                                        <?php if (session()->getFlashdata('errors')['direccion'] ?? false): ?>
                                            <div class="invalid-feedback"><?= esc(session()->getFlashdata('errors')['direccion']) ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                        <input type="email" 
                                               class="form-control <?= session()->getFlashdata('errors')['email'] ?? false ? 'is-invalid' : '' ?>" 
                                               id="email" 
                                               name="email" 
                                               value="<?= old('email', $paciente->email) ?>" 
                                               required>
                                        <?php if (session()->getFlashdata('errors')['email'] ?? false): ?>
                                            <div class="invalid-feedback"><?= esc(session()->getFlashdata('errors')['email']) ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end">
                                    <a href="<?= site_url('pacientes') ?>" class="btn btn-secondary me-2">Cancelar</a>
                                    <button type="submit" class="btn btn-primary">Actualizar Paciente</button>
                                </div>
                            <?= form_close() ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
