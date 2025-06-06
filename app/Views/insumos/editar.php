<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?> - Gestión Quirúrgica</title>
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
                    <h1 class="page-title"><?php echo $title; ?></h1>

                    <?php if(validation_errors()): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?php echo validation_errors(); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                        </div>
                    <?php endif; ?>

                    <div class="data-card">
                        <div class="data-card-header">
                            <h5 class="mb-0">Editar Insumo</h5>
                        </div>
                        <div class="data-card-body">
                            <?php echo form_open('insumos/update/'.$insumo['id_insumo']); ?>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="nombre" class="form-label">Nombre del Insumo <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control <?php echo form_error('nombre') ? 'is-invalid' : ''; ?>" id="nombre" name="nombre" value="<?php echo set_value('nombre', $insumo['nombre']); ?>" required>
                                        <?php if(form_error('nombre')): ?>
                                            <div class="invalid-feedback"><?php echo form_error('nombre'); ?></div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="tipo" class="form-label">Tipo <span class="text-danger">*</span></label>
                                        <select class="form-select <?php echo form_error('tipo') ? 'is-invalid' : ''; ?>" id="tipo" name="tipo" required>
                                            <option value="">Seleccionar Tipo</option>
                                            <option value="Consumible" <?php echo set_select('tipo', 'Consumible', $insumo['tipo'] == 'Consumible'); ?>>Consumible</option>
                                            <option value="Material quirúrgico" <?php echo set_select('tipo', 'Material quirúrgico', $insumo['tipo'] == 'Material quirúrgico'); ?>>Material quirúrgico</option>
                                            <option value="Protección personal" <?php echo set_select('tipo', 'Protección personal', $insumo['tipo'] == 'Protección personal'); ?>>Protección personal</option>
                                            <option value="Instrumental" <?php echo set_select('tipo', 'Instrumental', $insumo['tipo'] == 'Instrumental'); ?>>Instrumental</option>
                                            <option value="Medicamento" <?php echo set_select('tipo', 'Medicamento', $insumo['tipo'] == 'Medicamento'); ?>>Medicamento</option>
                                        </select>
                                        <?php if(form_error('tipo')): ?>
                                            <div class="invalid-feedback"><?php echo form_error('tipo'); ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="cantidad" class="form-label">Cantidad <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control <?php echo form_error('cantidad') ? 'is-invalid' : ''; ?>" id="cantidad" name="cantidad" value="<?php echo set_value('cantidad', $insumo['cantidad']); ?>" required>
                                        <?php if(form_error('cantidad')): ?>
                                            <div class="invalid-feedback"><?php echo form_error('cantidad'); ?></div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="ubicacion" class="form-label">Ubicación <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control <?php echo form_error('ubicacion') ? 'is-invalid' : ''; ?>" id="ubicacion" name="ubicacion" value="<?php echo set_value('ubicacion', $insumo['ubicacion']); ?>" required>
                                        <?php if(form_error('ubicacion')): ?>
                                            <div class="invalid-feedback"><?php echo form_error('ubicacion'); ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="tiene_vencimiento" name="tiene_vencimiento" value="1" <?php echo set_checkbox('tiene_vencimiento', '1', $insumo['tiene_vencimiento'] == 1); ?>>
                                            <label class="form-check-label" for="tiene_vencimiento">
                                                ¿Tiene fecha de vencimiento?
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6" id="fecha_vencimiento_container" style="display: <?php echo ($insumo['tiene_vencimiento'] == 1 || set_value('tiene_vencimiento')) ? 'block' : 'none'; ?>;">
                                        <label for="fecha_vencimiento" class="form-label">Fecha de Vencimiento</label>
                                        <input type="date" class="form-control" id="fecha_vencimiento" name="fecha_vencimiento" value="<?php echo set_value('fecha_vencimiento', $insumo['fecha_vencimiento']); ?>">
                                    </div>
                                </div>


                                <div class="d-flex justify-content-end">
                                    <a href="<?php echo site_url('insumos'); ?>" class="btn btn-secondary me-2">Cancelar</a>
                                    <button type="submit" class="btn btn-primary">Actualizar Insumo</button>
                                </div>
                            <?php echo form_close(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const tieneVencimientoCheckbox = document.getElementById('tiene_vencimiento');
        const fechaVencimientoContainer = document.getElementById('fecha_vencimiento_container');

        // Mostrar/ocultar campo de fecha según checkbox
        tieneVencimientoCheckbox.addEventListener('change', function() {
            fechaVencimientoContainer.style.display = this.checked ? 'block' : 'none';
        });
    });
    </script>
</body>
</html>