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
        <?= view('includes/sidebar') ?>

        <div class="main-content">
            <div class="main-wrapper">
                <div class="welcome-section">
                    <h1 class="page-title"><?php echo $title; ?></h1>

                    <?php if(session()->getFlashdata('errors')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?php foreach(session()->getFlashdata('errors') as $error): ?>
                                <div><?= $error ?></div>
                            <?php endforeach; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
                        </div>
                    <?php endif; ?>

                    <div class="data-card">
                        <div class="data-card-header">
                            <h5 class="mb-0">Editar Insumo</h5>
                        </div>
                        <div class="data-card-body">
                            <?= form_open('insumos/update/'.$insumo['id_insumo']); ?>
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="nombre" class="form-label">Nombre del Insumo <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control <?php echo (session()->getFlashdata('errors')['nombre'] ?? false) ? 'is-invalid' : ''; ?>" id="nombre" name="nombre" value="<?php echo old('nombre', $insumo['nombre']); ?>" required>
                                        <?php if(session()->getFlashdata('errors')['nombre'] ?? false): ?>
                                            <div class="invalid-feedback"><?php echo session()->getFlashdata('errors')['nombre']; ?></div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="col-md-6">
<<<<<<< HEAD
                                        <label for="tipo" class="form-label">Tipo <span class="text-danger">*</span></label>
                                        <select class="form-select <?php echo (session()->getFlashdata('errors')['tipo'] ?? false) ? 'is-invalid' : ''; ?>" id="tipo" name="tipo" required>
                                            <option value="">Seleccionar Tipo</option>
                                            <option value="Consumible" <?php echo (old('tipo', $insumo['tipo']) == 'Consumible') ? 'selected' : ''; ?>>Consumible</option>
                                            <option value="Material quirúrgico" <?php echo (old('tipo', $insumo['tipo']) == 'Material quirúrgico') ? 'selected' : ''; ?>>Material quirúrgico</option>
                                            <option value="Protección personal" <?php echo (old('tipo', $insumo['tipo']) == 'Protección personal') ? 'selected' : ''; ?>>Protección personal</option>
                                            <option value="Instrumental" <?php echo (old('tipo', $insumo['tipo']) == 'Instrumental') ? 'selected' : ''; ?>>Instrumental</option>
                                            <option value="Medicamento" <?php echo (old('tipo', $insumo['tipo']) == 'Medicamento') ? 'selected' : ''; ?>>Medicamento</option>
                                        </select>
=======
                                        <label for="categoria" class="form-label">Categoría <span class="text-danger">*</span></label>
                                        <select class="form-select <?php echo (session()->getFlashdata('errors')['categoria'] ?? false) ? 'is-invalid' : ''; ?>" id="categoria" name="categoria" required>
                                            <option value="">Seleccionar Categoría</option>
                                            <option value="descartable" <?php echo (old('categoria', $insumo['categoria']) == 'descartable') ? 'selected' : ''; ?>>Descartable</option>
                                            <option value="instrumental" <?php echo (old('categoria', $insumo['categoria']) == 'instrumental') ? 'selected' : ''; ?>>Instrumental</option>
                                        </select>
                                        <?php if(session()->getFlashdata('errors')['categoria'] ?? false): ?>
                                            <div class="invalid-feedback"><?php echo session()->getFlashdata('errors')['categoria']; ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <label for="tipo" class="form-label">Tipo <span class="text-danger">*</span></label>
                                        <select class="form-select <?php echo (session()->getFlashdata('errors')['tipo'] ?? false) ? 'is-invalid' : ''; ?>" id="tipo" name="tipo" required>
                                            <option value="">Seleccionar Tipo</option>
                                            <!-- Opciones para Descartables -->
                                            <optgroup label="Descartables" id="descartables-group" style="display: none;">
                                                <option value="Guantes">Guantes</option>
                                                <option value="Jeringas">Jeringas</option>
                                                <option value="Gasas">Gasas</option>
                                                <option value="Compresas">Compresas</option>
                                                <option value="Mascarillas">Mascarillas</option>
                                                <option value="Batas">Batas</option>
                                                <option value="Gorros">Gorros</option>
                                                <option value="Cubrezapatos">Cubrezapatos</option>
                                                <option value="Campos quirúrgicos">Campos quirúrgicos</option>
                                                <option value="Suturas">Suturas</option>
                                            </optgroup>
                                            <!-- Opciones para Instrumentales -->
                                            <optgroup label="Instrumentales" id="instrumentales-group" style="display: none;">
                                                <option value="Bisturí">Bisturí</option>
                                                <option value="Tijeras">Tijeras</option>
                                                <option value="Pinzas">Pinzas</option>
                                                <option value="Hemostatos">Hemostatos</option>
                                                <option value="Retractores">Retractores</option>
                                                <option value="Separadores">Separadores</option>
                                                <option value="Sondas">Sondas</option>
                                                <option value="Especulums">Especulums</option>
                                                <option value="Fórceps">Fórceps</option>
                                                <option value="Instrumental de medición">Instrumental de medición</option>
                                            </optgroup>
                                        </select>
>>>>>>> 733de39b8424adf4032156dc94a40b6ef5062118
                                        <?php if(session()->getFlashdata('errors')['tipo'] ?? false): ?>
                                            <div class="invalid-feedback"><?php echo session()->getFlashdata('errors')['tipo']; ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="cantidad" class="form-label">Cantidad <span class="text-danger">*</span></label>
                                        <input type="number" class="form-control <?php echo (session()->getFlashdata('errors')['cantidad'] ?? false) ? 'is-invalid' : ''; ?>" id="cantidad" name="cantidad" value="<?php echo old('cantidad', $insumo['cantidad']); ?>" required>
                                        <?php if(session()->getFlashdata('errors')['cantidad'] ?? false): ?>
                                            <div class="invalid-feedback"><?php echo session()->getFlashdata('errors')['cantidad']; ?></div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="ubicacion" class="form-label">Ubicación <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control <?php echo (session()->getFlashdata('errors')['ubicacion'] ?? false) ? 'is-invalid' : ''; ?>" id="ubicacion" name="ubicacion" value="<?php echo old('ubicacion', $insumo['ubicacion']); ?>" required>
                                        <?php if(session()->getFlashdata('errors')['ubicacion'] ?? false): ?>
                                            <div class="invalid-feedback"><?php echo session()->getFlashdata('errors')['ubicacion']; ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="lote" class="form-label">Lote</label>
                                        <input type="text" class="form-control" id="lote" name="lote" value="<?php echo old('lote', $insumo['lote']); ?>">
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-check mt-4">
                                            <input class="form-check-input" type="checkbox" id="tiene_vencimiento" name="tiene_vencimiento" value="1" <?php echo (old('tiene_vencimiento', $insumo['tiene_vencimiento']) == 1) ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="tiene_vencimiento">
                                                ¿Tiene fecha de vencimiento?
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-3" id="fecha_vencimiento_container" style="display: <?php echo (old('tiene_vencimiento', $insumo['tiene_vencimiento']) == 1) ? 'block' : 'none'; ?>;">
                                    <div class="col-md-6">
                                        <label for="fecha_vencimiento" class="form-label">Fecha de Vencimiento</label>
                                        <input type="date" class="form-control" id="fecha_vencimiento" name="fecha_vencimiento" value="<?php echo old('fecha_vencimiento', $insumo['fecha_vencimiento']); ?>">
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end">
                                    <a href="<?php echo site_url('insumos'); ?>" class="btn btn-secondary me-2">Cancelar</a>
                                    <button type="submit" class="btn btn-primary">Actualizar Insumo</button>
                                </div>
                            <?= form_close(); ?>
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
        const categoriaSelect = document.getElementById('categoria');
        const tipoSelect = document.getElementById('tipo');
        const descartablesGroup = document.getElementById('descartables-group');
        const instrumentalesGroup = document.getElementById('instrumentales-group');

        // Mostrar/ocultar campo de fecha según checkbox
        tieneVencimientoCheckbox.addEventListener('change', function() {
            fechaVencimientoContainer.style.display = this.checked ? 'block' : 'none';
        });

        // Función para mostrar opciones de tipo según categoría
        function updateTipoOptions() {
            const categoria = categoriaSelect.value;
            const tipoActual = tipoSelect.value;
            
            // Ocultar todos los grupos
            descartablesGroup.style.display = 'none';
            instrumentalesGroup.style.display = 'none';
            
            // Limpiar selección si no coincide con la nueva categoría
            let mantenerSeleccion = false;
            
            if (categoria === 'descartable') {
                descartablesGroup.style.display = 'block';
                // Verificar si el tipo actual pertenece a descartables
                const opcionesDescartables = descartablesGroup.querySelectorAll('option');
                for (let option of opcionesDescartables) {
                    if (option.value === tipoActual) {
                        mantenerSeleccion = true;
                        break;
                    }
                }
            } else if (categoria === 'instrumental') {
                instrumentalesGroup.style.display = 'block';
                // Verificar si el tipo actual pertenece a instrumentales
                const opcionesInstrumentales = instrumentalesGroup.querySelectorAll('option');
                for (let option of opcionesInstrumentales) {
                    if (option.value === tipoActual) {
                        mantenerSeleccion = true;
                        break;
                    }
                }
            }
            
            // Si el tipo actual no pertenece a la nueva categoría, limpiar selección
            if (!mantenerSeleccion) {
                tipoSelect.value = '';
            }
        }

        // Configurar el tipo inicial basado en la categoría actual
        const tipoActual = '<?php echo old('tipo', $insumo['tipo']); ?>';
        if (tipoActual) {
            // Crear una opción temporal para el valor actual si no existe
            let optionExists = false;
            const allOptions = tipoSelect.querySelectorAll('option');
            for (let option of allOptions) {
                if (option.value === tipoActual) {
                    optionExists = true;
                    option.selected = true;
                    break;
                }
            }
            
            if (!optionExists) {
                // Agregar opción temporal para el valor actual
                const tempOption = document.createElement('option');
                tempOption.value = tipoActual;
                tempOption.textContent = tipoActual;
                tempOption.selected = true;
                tipoSelect.appendChild(tempOption);
            }
        }

        // Mostrar opciones iniciales
        updateTipoOptions();

        // Escuchar cambios en categoría
        categoriaSelect.addEventListener('change', updateTipoOptions);
    });
    </script>
</body>
</html>