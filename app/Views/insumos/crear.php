<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?>
<?= $title ?> - Gestión Quirúrgica
<?= $this->endSection() ?>

<?= $this->section('content') ?>
    <link rel="stylesheet" href="<?php echo base_url('assets/css/styles.css'); ?>">

<div class="app-container">
    <!-- Sidebar -->
            <?= view('includes/sidebar') ?>


    <div class="main-content">
        <div class="main-wrapper">
            <div class="welcome-section">
                <h1 class="page-title"><?= esc($title) ?></h1>

                <?php 
                // COMENTADO: Validación de sesión/login - En CI4 esto se manejaría así:
                // if (!session()->get('logged_in')) {
                //     return redirect()->to('/login');
                // }
                // O usando filtros de autenticación en las rutas
                ?>

                <?php if (session()->getFlashdata('errors')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?php foreach (session()->getFlashdata('errors') as $error): ?>
                            <div><?= esc($error) ?></div>
                        <?php endforeach; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <?= session()->getFlashdata('error') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                    </div>
                <?php endif; ?>

                <div class="data-card">
                    <div class="data-card-header">
                        <h5 class="mb-0">Formulario de Insumo</h5>
                    </div>
                    <div class="data-card-body">
                        <?= form_open('insumos/add') ?>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="nombre" class="form-label">Nombre del Insumo <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control <?= session()->getFlashdata('errors')['nombre'] ?? false ? 'is-invalid' : '' ?>" 
                                           id="nombre" 
                                           name="nombre" 
                                           value="<?= old('nombre') ?>" 
                                           required>
                                    <?php if (isset(session()->getFlashdata('errors')['nombre'])): ?>
                                        <div class="invalid-feedback"><?= session()->getFlashdata('errors')['nombre'] ?></div>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-6">
                                    <label for="tipo" class="form-label">Tipo <span class="text-danger">*</span></label>
                                    <select class="form-select <?= session()->getFlashdata('errors')['tipo'] ?? false ? 'is-invalid' : '' ?>" 
                                            id="tipo" 
                                            name="tipo" 
                                            required>
                                        <option value="">Seleccionar Tipo</option>
                                        <option value="Consumible" <?= old('tipo') === 'Consumible' ? 'selected' : '' ?>>Consumible</option>
                                        <option value="Material quirúrgico" <?= old('tipo') === 'Material quirúrgico' ? 'selected' : '' ?>>Material quirúrgico</option>
                                        <option value="Protección personal" <?= old('tipo') === 'Protección personal' ? 'selected' : '' ?>>Protección personal</option>
                                        <option value="Instrumental" <?= old('tipo') === 'Instrumental' ? 'selected' : '' ?>>Instrumental</option>
                                        <option value="Medicamento" <?= old('tipo') === 'Medicamento' ? 'selected' : '' ?>>Medicamento</option>
                                    </select>
                                    <?php if (isset(session()->getFlashdata('errors')['tipo'])): ?>
                                        <div class="invalid-feedback"><?= session()->getFlashdata('errors')['tipo'] ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="cantidad" class="form-label">Cantidad <span class="text-danger">*</span></label>
                                    <input type="number" 
                                           class="form-control <?= session()->getFlashdata('errors')['cantidad'] ?? false ? 'is-invalid' : '' ?>" 
                                           id="cantidad" 
                                           name="cantidad" 
                                           value="<?= old('cantidad') ?>" 
                                           required>
                                    <?php if (isset(session()->getFlashdata('errors')['cantidad'])): ?>
                                        <div class="invalid-feedback"><?= session()->getFlashdata('errors')['cantidad'] ?></div>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-6">
                                    <label for="ubicacion" class="form-label">Ubicación <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control <?= session()->getFlashdata('errors')['ubicacion'] ?? false ? 'is-invalid' : '' ?>" 
                                           id="ubicacion" 
                                           name="ubicacion" 
                                           value="<?= old('ubicacion') ?>" 
                                           required>
                                    <?php if (isset(session()->getFlashdata('errors')['ubicacion'])): ?>
                                        <div class="invalid-feedback"><?= session()->getFlashdata('errors')['ubicacion'] ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" 
                                               type="checkbox" 
                                               id="tiene_vencimiento" 
                                               name="tiene_vencimiento" 
                                               value="1" 
                                               <?= old('tiene_vencimiento') ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="tiene_vencimiento">
                                            ¿Tiene fecha de vencimiento?
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6" id="fecha_vencimiento_container" style="display: none;">
                                    <label for="fecha_vencimiento" class="form-label">Fecha de Vencimiento</label>
                                    <input type="date" 
                                           class="form-control" 
                                           id="fecha_vencimiento" 
                                           name="fecha_vencimiento" 
                                           value="<?= old('fecha_vencimiento') ?>">
                                </div>
                            </div>

                            <div class="d-flex justify-content-end">
                                <a href="<?= base_url('insumos') ?>" class="btn btn-secondary me-2">Cancelar</a>
                                <button type="submit" class="btn btn-primary">Guardar Insumo</button>
                            </div>
                        <?= form_close() ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tieneVencimientoCheckbox = document.getElementById('tiene_vencimiento');
    const fechaVencimientoContainer = document.getElementById('fecha_vencimiento_container');

    // Mostrar/ocultar campo de fecha según checkbox
    tieneVencimientoCheckbox.addEventListener('change', function() {
        fechaVencimientoContainer.style.display = this.checked ? 'block' : 'none';
    });

    // Mostrar campo si ya estaba marcado al recargar por validación
    if(tieneVencimientoCheckbox.checked) {
        fechaVencimientoContainer.style.display = 'block';
    }
});
</script>
<?= $this->endSection() ?>