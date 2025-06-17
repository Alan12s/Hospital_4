<?php helper('form'); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($titulo) ?> - Gestión Quirúrgica</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="<?= base_url('assets/css/styles.css') ?>">
</head>
<body>
    <div class="app-container">
        <?= view('includes/sidebar') ?>
        <div class="main-content">
            <div class="main-wrapper">
                <div class="welcome-section">
                    <h1 class="page-title"><?= esc($titulo) ?></h1>

                    <?php if(session()->getFlashdata('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?= esc(session()->getFlashdata('error')) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <?php if(session()->getFlashdata('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?= esc(session()->getFlashdata('success')) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <div class="data-card">
                        <div class="data-card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Editar Turno Quirúrgico</h5>
                            <div>
                                <span class="badge bg-<?= $turno['estado'] == 'programado' ? 'primary' : ($turno['estado'] == 'en_curso' ? 'warning' : ($turno['estado'] == 'completado' ? 'success' : 'danger')) ?>">
                                    <?= ucfirst(str_replace('_', ' ', $turno['estado'])) ?>
                                </span>
                            </div>
                        </div>
                        <div class="data-card-body">
                            <?php if (session()->getFlashdata('validation')): ?>
                                <div class="alert alert-danger"><?= session()->getFlashdata('validation')->listErrors() ?></div>
                            <?php endif; ?>

                            <?= form_open('turnos/actualizar/' . $turno['id']) ?>
                                <!-- Fecha, Hora y Duración -->
                                <div class="row mb-3">
                                    <?php 
                                    $campos_fecha_hora = [
                                        ['fecha', 'Fecha', 'date', old('fecha', $turno['fecha']), ['min' => date('Y-m-d')]],
                                        ['hora_inicio', 'Hora de inicio', 'time', old('hora_inicio', $turno['hora_inicio']), ['min' => '08:00', 'max' => '20:00']],
                                        ['duracion', 'Duración (minutos)', 'number', old('duracion', $turno['duracion']), ['min' => '15', 'max' => '240', 'step' => '15']]
                                    ];
                                    foreach($campos_fecha_hora as $campo): ?>
                                        <div class="col-md-4">
                                            <label for="<?= $campo[0] ?>" class="form-label"><?= $campo[1] ?> <span class="text-danger">*</span></label>
                                            <input type="<?= $campo[2] ?>" class="form-control <?= session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError($campo[0]) ? 'is-invalid' : '' ?>" 
                                                   id="<?= $campo[0] ?>" name="<?= $campo[0] ?>" value="<?= $campo[3] ?>" required
                                                   <?php if($turno['estado'] != 'programado'): ?>disabled<?php endif; ?>
                                                   <?php foreach($campo[4] ?? [] as $attr => $val): ?>
                                                       <?= $attr ?>="<?= $val ?>"
                                                   <?php endforeach; ?>>
                                            <?php if(session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError($campo[0])): ?>
                                                <div class="invalid-feedback"><?= session()->getFlashdata('validation')->getError($campo[0]) ?></div>
                                            <?php endif; ?>
                                        </div>
                                    <?php endforeach; ?>
                                </div>

                                <!-- Personal y Quirófano -->
                                <div class="row mb-3">
                                    <?php 
                                    $selects_personal = [
                                        ['id_paciente', 'Paciente', $pacientes ?? [], 'nombre', 'dni', old('id_paciente', $turno['id_paciente'])],
                                        ['id_cirujano', 'Médico Cirujano', $medicos ?? [], 'nombre', 'dni', old('id_cirujano', $turno['id_cirujano'])],
                                        ['id_quirofano', 'Quirófano', $quirofanos ?? [], 'nombre', '', old('id_quirofano', $turno['id_quirofano'])]
                                    ];
                                    foreach($selects_personal as $select): ?>
                                        <div class="col-md-4">
                                            <label for="<?= $select[0] ?>" class="form-label"><?= $select[1] ?> <span class="text-danger">*</span></label>
                                            <select class="form-select <?= session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError($select[0]) ? 'is-invalid' : '' ?>" 
                                                    id="<?= $select[0] ?>" name="<?= $select[0] ?>" required
                                                    <?php if($turno['estado'] == 'completado'): ?>disabled<?php endif; ?>>
                                                <option value="">Seleccionar <?= strtolower($select[1]) ?></option>
                                                <?php foreach($select[2] as $item): ?>
                                                    <option value="<?= $item['id'] ?>" 
                                                            <?= isset($item['id_especialidad']) ? 'data-especialidad="'.$item['id_especialidad'].'"' : '' ?>
                                                            <?= $select[5] == $item['id'] ? 'selected' : '' ?>>
                                                        <?= esc($item[$select[3]]) ?><?= $select[4] ? ' ('.esc($item[$select[4]]).')' : '' ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                            <?php if(session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError($select[0])): ?>
                                                <div class="invalid-feedback"><?= session()->getFlashdata('validation')->getError($select[0]) ?></div>
                                            <?php endif; ?>
                                        </div>
                                    <?php endforeach; ?>
                                </div>

                                <!-- Anestesista, Enfermero y Procedimiento -->
                                <div class="row mb-3">
                                    <?php 
                                    $selects_adicionales = [
                                        ['id_anestesista', 'Anestesista', $anestesistas ?? [], old('id_anestesista', $turno['id_anestesista'])],
                                        ['id_enfermero', 'Enfermero/a', $enfermeros ?? [], old('id_enfermero', $turno['id_enfermero'])]
                                    ];
                                    foreach($selects_adicionales as $select): ?>
                                        <div class="col-md-4">
                                            <label for="<?= $select[0] ?>" class="form-label"><?= $select[1] ?> <span class="text-danger">*</span></label>
                                            <select class="form-select <?= session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError($select[0]) ? 'is-invalid' : '' ?>" 
                                                    id="<?= $select[0] ?>" name="<?= $select[0] ?>" required
                                                    <?php if($turno['estado'] == 'completado'): ?>disabled<?php endif; ?>>
                                                <option value="">Seleccionar <?= strtolower($select[1]) ?></option>
                                                <?php foreach($select[2] as $item): ?>
                                                    <option value="<?= $item['id'] ?>" <?= $select[3] == $item['id'] ? 'selected' : '' ?>>
                                                        <?= esc($item['nombre']) ?> (<?= esc($item['especialidad']) ?>)
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                            <?php if(session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError($select[0])): ?>
                                                <div class="invalid-feedback"><?= session()->getFlashdata('validation')->getError($select[0]) ?></div>
                                            <?php endif; ?>
                                        </div>
                                    <?php endforeach; ?>
                                    
                                    <div class="col-md-4">
                                        <label for="procedimiento" class="form-label">Procedimiento <span class="text-danger">*</span></label>
                                        <select class="form-select <?= session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('procedimiento') ? 'is-invalid' : '' ?>" 
                                                id="procedimiento" name="procedimiento"
                                                <?php if($turno['estado'] == 'completado'): ?>disabled<?php endif; ?>>
                                            <option value="">Seleccionar procedimiento</option>
                                            <?php foreach($procedimientos ?? [] as $proc): ?>
                                                <option value="<?= $proc['id'] ?>" <?= old('procedimiento', $turno['procedimiento']) == $proc['id'] ? 'selected' : '' ?>>
                                                    <?= esc($proc['nombre']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                            <option value="otro" <?= old('procedimiento', $turno['procedimiento']) == 'otro' || (!in_array($turno['procedimiento'], array_column($procedimientos ?? [], 'id')) && !empty($turno['procedimiento'])) ? 'selected' : '' ?>>Otro (especificar)</option>
                                        </select>
                                        <input type="text" class="form-control mt-2 <?= old('procedimiento', $turno['procedimiento']) == 'otro' || (!in_array($turno['procedimiento'], array_column($procedimientos ?? [], 'id')) && !empty($turno['procedimiento'])) ? '' : 'd-none' ?>" 
                                               id="otro_procedimiento" name="otro_procedimiento" 
                                               placeholder="Especificar otro procedimiento" 
                                               value="<?= old('otro_procedimiento', (!in_array($turno['procedimiento'], array_column($procedimientos ?? [], 'id')) && !empty($turno['procedimiento'])) ? $turno['procedimiento'] : '') ?>"
                                               <?php if($turno['estado'] == 'completado'): ?>disabled<?php endif; ?>>
                                        <?php if(session()->getFlashdata('validation') && session()->getFlashdata('validation')->hasError('procedimiento')): ?>
                                            <div class="invalid-feedback"><?= session()->getFlashdata('validation')->getError('procedimiento') ?></div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <!-- Estado -->
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <label for="estado" class="form-label">Estado <span class="text-danger">*</span></label>
                                        <select class="form-select" id="estado" name="estado" required>
                                            <option value="programado" <?= old('estado', $turno['estado']) == 'programado' ? 'selected' : '' ?>>Programado</option>
                                            <option value="en_curso" <?= old('estado', $turno['estado']) == 'en_curso' ? 'selected' : '' ?>>En Curso</option>
                                            <option value="completado" <?= old('estado', $turno['estado']) == 'completado' ? 'selected' : '' ?>>Completado</option>
                                            <option value="cancelado" <?= old('estado', $turno['estado']) == 'cancelado' ? 'selected' : '' ?>>Cancelado</option>
                                        </select>
                                    </div>
                                    <?php if($turno['estado'] == 'completado' || $turno['estado'] == 'cancelado'): ?>
                                    <div class="col-md-4">
                                        <label for="fecha_actualizacion" class="form-label">Fecha de actualización</label>
                                        <input type="text" class="form-control" value="<?= date('d/m/Y H:i', strtotime($turno['updated_at'] ?? $turno['created_at'])) ?>" disabled>
                                    </div>
                                    <?php endif; ?>
                                </div>

                                <!-- Observaciones -->
                                <div class="row mb-3">
                                    <div class="col-md-12">
                                        <label for="observaciones" class="form-label">Observaciones</label>
                                        <textarea class="form-control" id="observaciones" name="observaciones" rows="3"
                                                  <?php if($turno['estado'] == 'completado'): ?>disabled<?php endif; ?>><?= old('observaciones', $turno['observaciones']) ?></textarea>
                                    </div>
                                </div>

                                <!-- Insumos -->
                                <div class="row mt-4">
                                    <div class="col-md-12">
                                        <div class="data-card">
                                            <div class="data-card-header">
                                                <h5 class="mb-0">Insumos Requeridos</h5>
                                            </div>
                                            <div class="data-card-body">
                                                <?php if($turno['estado'] != 'completado'): ?>
                                                <div class="row mb-3">
                                                    <div class="col-md-6">
                                                        <div class="input-group">
                                                            <input type="text" class="form-control" id="buscador-insumos" placeholder="Buscar por nombre del insumo...">
                                                            <button class="btn btn-outline-secondary" type="button" id="btn-buscar-insumos"><i class="bx bx-search"></i></button>
                                                            <button class="btn btn-outline-secondary" type="button" id="btn-limpiar-busqueda"><i class="bx bx-x"></i></button>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <select class="form-select" id="filtro-stock">
                                                            <option value="">Todos</option>
                                                            <option value="bajo">Stock bajo (< 10)</option>
                                                            <option value="normal">Stock normal (≥ 10)</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div id="resultados-busqueda" class="mb-3" style="display: none;">
                                                    <div class="list-group" id="lista-resultados"></div>
                                                </div>
                                                <?php endif; ?>
                                                <div class="table-responsive">
                                                    <table class="table table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <?php if($turno['estado'] != 'completado'): ?><th>Seleccionar</th><?php endif; ?>
                                                                <th>Insumo</th>
                                                                <th>Stock</th>
                                                                <th>Cantidad <?= $turno['estado'] == 'completado' ? 'Utilizada' : 'Requerida' ?></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="tabla-insumos">
                                                        <?php foreach ($insumos ?? [] as $insumo): 
                                                            $cantidadSeleccionada = 0;
                                                            if(isset($turno_insumos)) {
                                                                foreach($turno_insumos as $ti) {
                                                                    if($ti['id_insumo'] == $insumo['id']) {
                                                                        $cantidadSeleccionada = $ti['cantidad'];
                                                                        break;
                                                                    }
                                                                }
                                                            }
                                                        ?>
                                                            <tr data-insumo-id="<?= $insumo['id'] ?>" class="fila-insumo <?= $cantidadSeleccionada > 0 ? 'table-success' : '' ?>">
                                                                <?php if($turno['estado'] != 'completado'): ?>
                                                                <td>
                                                                    <input class="form-check-input insumo-check" type="checkbox" 
                                                                           value="<?= $insumo['id'] ?>" 
                                                                           id="insumo<?= $insumo['id'] ?>" 
                                                                           <?= $cantidadSeleccionada > 0 ? 'checked' : '' ?>
                                                                           onchange="toggleCantidadCorregido(this)">
                                                                </td>
                                                                <?php endif; ?>
                                                                <td><?= esc($insumo['nombre']) ?> - <?= esc($insumo['tipo']) ?></td>
                                                                <td><span class="badge <?= ($insumo['cantidad'] < 10) ? 'bg-danger' : 'bg-success' ?>"><?= $insumo['cantidad'] ?></span></td>
                                                                <td>
                                                                    <input type="number" class="form-control cantidad-insumo" 
                                                                           name="insumos[<?= $insumo['id'] ?>]" 
                                                                           min="1" max="<?= $insumo['cantidad'] + $cantidadSeleccionada ?>" 
                                                                           value="<?= $cantidadSeleccionada ?>" 
                                                                           <?= $cantidadSeleccionada > 0 ? '' : 'disabled' ?>
                                                                           <?= $turno['estado'] == 'completado' ? 'disabled' : '' ?>
                                                                           onchange="actualizarCantidadCorregido(<?= $insumo['id'] ?>, this.value)">
                                                                </td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <input type="hidden" name="turno_id" value="<?= $turno['id'] ?>">
                                <div class="d-flex justify-content-end">
                                    <a href="<?= site_url('turnos') ?>" class="btn btn-secondary me-2">Cancelar</a>
                                    <?php if($turno['estado'] != 'completado'): ?>
                                        <button type="submit" class="btn btn-primary">Actualizar Turno</button>
                                    <?php endif; ?>
                                    <?php if($turno['estado'] == 'programado'): ?>
                                        <button type="button" class="btn btn-warning ms-2" onclick="cambiarEstado('en_curso')">Iniciar Cirugía</button>
                                    <?php elseif($turno['estado'] == 'en_curso'): ?>
                                        <button type="button" class="btn btn-success ms-2" onclick="cambiarEstado('completado')">Completar Cirugía</button>
                                    <?php endif; ?>
                                </div>
                            <?= form_close() ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        
$(document).ready(function() {
    if(typeof jQuery == 'undefined') {
        console.error('jQuery no se cargó correctamente');
        return;
    }

    let timeoutBusqueda, todosLosInsumos = [];
    const estadoActual = '<?= $turno['estado'] ?>';

    // Cargar procedimientos
    function cargarProcedimientos(idEspecialidad) {
        if (!idEspecialidad) {
            $('#procedimiento').html('<option value="">Seleccione un cirujano</option><option value="otro">Otro (especificar)</option>');
            return;
        }
        
        $.ajax({
            url: '<?= site_url("turnos/get_procedimientos") ?>/' + idEspecialidad,
            type: 'GET',
            dataType: 'json',
            beforeSend: () => $('#procedimiento').prop('disabled', true).html('<option value="">Cargando...</option>'),
            complete: () => $('#procedimiento').prop('disabled', estadoActual === 'completado'),
            success: function(response) {
                if(response.error) {
                    $('#procedimiento').html('<option value="">Error: '+response.error+'</option><option value="otro">Otro (especificar)</option>');
                    return;
                }
                let options = '<option value="">Seleccionar procedimiento</option>';
                $.each(response, (i, proc) => options += `<option value="${proc.nombre}">${proc.nombre}</option>`);
                options += '<option value="otro">Otro (especificar)</option>';
                $('#procedimiento').html(options);
                
                const selected = '<?= old("procedimiento", $turno["procedimiento"]) ?>';
                if(selected) {
                    $('#procedimiento').val(selected);
                    if(selected === 'otro' || !$('#procedimiento option[value="' + selected + '"]').length) {
                        $('#procedimiento').val('otro');
                        $('#otro_procedimiento').removeClass('d-none').prop('required', true);
                    }
                }
            },
            error: () => $('#procedimiento').html('<option value="">Error al cargar</option><option value="otro">Otro (especificar)</option>')
        });
    }

    // Eventos principales
    $('#id_cirujano').change(function() {
        const especialidad = $(this).find(':selected').data('especialidad');
        cargarProcedimientos(especialidad);
    });

    $('#procedimiento').change(function() {
        const isOtro = $(this).val() == 'otro';
        $('#otro_procedimiento').toggleClass('d-none', !isOtro).prop('required', isOtro);
        if(!isOtro) $('#otro_procedimiento').val('');
    });

    // Control de estado
    $('#estado').change(function() {
        const nuevoEstado = $(this).val();
        const camposEditables = $('#fecha, #hora_inicio, #duracion, #id_paciente, #id_cirujano, #id_quirofano, #id_anestesista, #id_enfermero, #procedimiento, #otro_procedimiento, #observaciones');
        const insumosEditables = $('.insumo-check, .cantidad-insumo');
        
        if (nuevoEstado === 'completado') {
            camposEditables.prop('disabled', true);
            insumosEditables.prop('disabled', true);
            $('#buscador-insumos, #btn-buscar-insumos, #btn-limpiar-busqueda, #filtro-stock').prop('disabled', true);
        } else {
            camposEditables.prop('disabled', false);
            insumosEditables.each(function() {
                if($(this).hasClass('cantidad-insumo')) {
                    const checkbox = $(this).closest('tr').find('.insumo-check');
                    $(this).prop('disabled', !checkbox.is(':checked'));
                } else {
                    $(this).prop('disabled', false);
                }
            });
            $('#buscador-insumos, #btn-buscar-insumos, #btn-limpiar-busqueda, #filtro-stock').prop('disabled', false);
        }
    });

    // Inicializar insumos solo si el estado no es completado
    if (estadoActual !== 'completado') {
        function inicializarInsumos() {
            $('#tabla-insumos .fila-insumo').each(function() {
                const fila = $(this);
                todosLosInsumos.push({
                    id: fila.data('insumo-id'),
                    nombre: fila.find('td:eq(1)').text().toLowerCase(),
                    stock: parseInt(fila.find('td:eq(2) .badge').text()),
                    elemento: fila
                });
            });
        }
        inicializarInsumos();

        // Búsqueda de insumos
        function buscarInsumosLocal(termino) {
            const terminoLower = termino.toLowerCase();
            let resultados = [], filasEncontradas = 0;
            
            $('#tabla-insumos .fila-insumo').hide();
            
            todosLosInsumos.forEach(insumo => {
                if (insumo.nombre.includes(terminoLower)) {
                    insumo.elemento.show();
                    filasEncontradas++;
                    resultados.push({
                        id: insumo.id,
                        nombre: insumo.elemento.find('td:eq(1)').text(),
                        stock: insumo.stock
                    });
                }
            });
            
            mostrarResultados(resultados, terminoLower);
        }

        function mostrarResultados(resultados, termino) {
            let html = resultados.length === 0 ? 
                `<div class="alert alert-info mb-0">No se encontraron insumos con "${termino}"</div>` :
                `<div class="alert alert-success mb-2">Se encontraron ${resultados.length} insumo(s)</div>`;
            
            resultados.forEach(insumo => {
                const badgeClass = insumo.stock < 10 ? 'bg-danger' : 'bg-success';
                html += `<a href="#" class="list-group-item list-group-item-action resultado-insumo d-flex justify-content-between align-items-center" 
                            data-insumo-id="${insumo.id}">
                            <span>${insumo.nombre}</span>
                            <span class="badge ${badgeClass}">Stock: ${insumo.stock}</span>
                         </a>`;
            });
            
            $('#lista-resultados').html(html);
            $('#resultados-busqueda').show();
            
            $('.resultado-insumo').click(function(e) {
                e.preventDefault();
                const insumoId = $(this).data('insumo-id');
                const filaInsumo = $(`.fila-insumo[data-insumo-id="${insumoId}"]`);
                filaInsumo.addClass('table-info');
                $('html, body').animate({scrollTop: filaInsumo.offset().top - 150}, 500);
                setTimeout(() => filaInsumo.removeClass('table-info'), 4000);
                $('#resultados-busqueda').hide();
            });
        }

        // Eventos de búsqueda
        $('#buscador-insumos').on('input', function() {
            clearTimeout(timeoutBusqueda);
            const termino = $(this).val().trim();
            if (termino.length === 0) {
                $('#tabla-insumos .fila-insumo').show();
                $('#resultados-busqueda').hide();
                return;
            }
            timeoutBusqueda = setTimeout(() => {
                if (termino.length >= 2) buscarInsumosLocal(termino);
            }, 300);
        });

        $('#btn-buscar-insumos').click(() => {
            const termino = $('#buscador-insumos').val().trim();
            if (termino.length >= 2) buscarInsumosLocal(termino);
        });

        $('#btn-limpiar-busqueda').click(() => {
            $('#buscador-insumos').val('');
            $('#tabla-insumos .fila-insumo').show();
            $('#resultados-busqueda').hide();
        });

        $('#filtro-stock').change(function() {
            const filtro = $(this).val();
            $('.fila-insumo').each(function() {
                const stock = parseInt($(this).find('td:eq(2) .badge').text());
                const mostrar = !filtro || (filtro === 'bajo' && stock < 10) || (filtro === 'normal' && stock >= 10);
                $(this).toggle(mostrar);
            });
        });
    }

    // Validaciones
    function verificarDisponibilidad() {
        const fecha = $('#fecha').val(), hora = $('#hora_inicio').val(), 
              duracion = $('#duracion').val(), quirofano = $('#id_quirofano').val();
        
        if (fecha && hora && duracion && quirofano) {
            $.post('<?= base_url("turnos/verificar_disponibilidad") ?>', {
                fecha, hora_inicio: hora, duracion, id_quirofano: quirofano,
                turno_actual: '<?= $turno['id'] ?>',
                <?= csrf_token() ?>: '<?= csrf_