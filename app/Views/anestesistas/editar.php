<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $titulo; ?> - Gestión Quirúrgica</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/styles.css'); ?>">
</head>
<body>
    <div class="app-container">
        <!-- Sidebar -->
        <?php $this->load->view('includes/sidebar'); ?>

        <div class="main-content">
            <div class="main-wrapper">
                <div class="welcome-section">
                    <h1 class="page-title"><?php echo $titulo; ?></h1>

                    <?php if($this->session->flashdata('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?php echo $this->session->flashdata('error'); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                        </div>
                    <?php endif; ?>

                    <div class="data-card">
                        <div class="data-card-header">
                            <h5 class="mb-0">Formulario de Anestesista</h5>
                        </div>
                        <div class="data-card-body">
                            <?php echo validation_errors('<div class="alert alert-danger">', '</div>'); ?>
                            <?php if(isset($error)): ?>
                                <div class="alert alert-danger"><?php echo $error; ?></div>
                            <?php endif; ?>

                            <?php echo form_open("anestesistas/editar/{$anestesista->id}"); ?>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="nombre" class="form-label">Nombre <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control <?php echo form_error('nombre') ? 'is-invalid' : ''; ?>" id="nombre" name="nombre" value="<?php echo set_value('nombre', $anestesista->nombre); ?>" required>
                                        <?php if(form_error('nombre')): ?>
                                            <div class="invalid-feedback"><?php echo form_error('nombre'); ?></div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="especialidad" class="form-label">Especialidad <span class="text-danger">*</span></label>
                                        <select class="form-control <?php echo form_error('especialidad') ? 'is-invalid' : ''; ?>" id="especialidad" name="especialidad" required>
                                            <option value="">Seleccione una especialidad...</option>
                                            <option value="Anestesiología General" <?= set_select('especialidad', 'Anestesiología General', $anestesista->especialidad == 'Anestesiología General') ?>>Anestesiología General</option>
                                            <option value="Anestesiología en Dolor Crónico" <?= set_select('especialidad', 'Anestesiología en Dolor Crónico', $anestesista->especialidad == 'Anestesiología en Dolor Crónico') ?>>Anestesiología en Dolor Crónico</option>
                                            <option value="Anestesiología Pediátrica" <?= set_select('especialidad', 'Anestesiología Pediátrica', $anestesista->especialidad == 'Anestesiología Pediátrica') ?>>Anestesiología Pediátrica</option>
                                            <option value="Anestesiología Obstétrica" <?= set_select('especialidad', 'Anestesiología Obstétrica', $anestesista->especialidad == 'Anestesiología Obstétrica') ?>>Anestesiología Obstétrica</option>
                                            <option value="Anestesiología Regional" <?= set_select('especialidad', 'Anestesiología Regional', $anestesista->especialidad == 'Anestesiología Regional') ?>>Anestesiología Regional</option>
                                        </select>
                                        <?php if(form_error('especialidad')): ?>
                                            <div class="invalid-feedback"><?php echo form_error('especialidad'); ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <!-- Resto del formulario se mantiene igual -->
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="disponibilidad" class="form-label">Disponibilidad <span class="text-danger">*</span></label>
                                        <select class="form-control <?php echo form_error('disponibilidad') ? 'is-invalid' : ''; ?>" id="disponibilidad" name="disponibilidad" required>
                                            <option value="">Seleccione...</option>
                                            <option value="disponible" <?= set_select('disponibilidad', 'disponible', $anestesista->disponibilidad == 'disponible') ?>>Disponible</option>
                                            <option value="no_disponible" <?= set_select('disponibilidad', 'no_disponible', $anestesista->disponibilidad == 'no_disponible') ?>>No Disponible</option>
                                            <option value="en_cirugia" <?= set_select('disponibilidad', 'en_cirugia', $anestesista->disponibilidad == 'en_cirugia') ?>>En Cirugía</option>
                                        </select>
                                        <?php if(form_error('disponibilidad')): ?>
                                            <div class="invalid-feedback"><?php echo form_error('disponibilidad'); ?></div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="telefono" class="form-label">Teléfono <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control <?php echo form_error('telefono') ? 'is-invalid' : ''; ?>" id="telefono" name="telefono" value="<?php echo set_value('telefono', $anestesista->telefono); ?>" required>
                                        <?php if(form_error('telefono')): ?>
                                            <div class="invalid-feedback"><?php echo form_error('telefono'); ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control <?php echo form_error('email') ? 'is-invalid' : ''; ?>" id="email" name="email" value="<?php echo set_value('email', $anestesista->email); ?>" required>
                                        <?php if(form_error('email')): ?>
                                            <div class="invalid-feedback"><?php echo form_error('email'); ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end">
                                    <a href="<?php echo site_url('anestesistas'); ?>" class="btn btn-secondary me-2">Cancelar</a>
                                    <button type="submit" class="btn btn-primary">Actualizar Anestesista</button>
                                </div>
                            <?php echo form_close(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>