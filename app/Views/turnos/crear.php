<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Turno Quirúrgico - Gestión Quirúrgica</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- Boxicons -->
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Base URL para JavaScript -->
    <meta name="base-url" content="<?= base_url() ?>">
    <!-- CSS Personalizado -->
    <link href="<?= base_url('assets/css/turnos.css') ?>" rel="stylesheet">
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <?= $this->include('includes/sidebar') ?>
        
        <!-- Main Content -->
        <div class="main-content">
            <!-- Page Header -->
            <div class="dashboard-header animate-fade-in">
                <h1>
                    <i class='bx bx-calendar-plus'></i>Crear Turno Quirúrgico
                </h1>
                <a href="<?= base_url('turnos') ?>" class="btn btn-secondary">
                    <i class='bx bx-arrow-back'></i> Volver a Turnos
                </a>
            </div>

            <!-- Flash Messages -->
            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger alert-dismissible fade show glass-card">
                    <div class="d-flex align-items-center">
                        <i class='bx bx-error-circle me-2'></i>
                        <strong>Por favor, corrija los siguientes errores:</strong>
                    </div>
                    <ul class="mb-0">
                        <?php foreach ($errors as $error): ?>
                            <li><?= esc($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show glass-card">
                    <div class="d-flex align-items-center">
                        <i class='bx bx-error-circle me-2'></i>
                        <?= session()->getFlashdata('error') ?>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- Form Card -->
            <div class="glass-card animate-fade-in" style="animation-delay: 0.1s">
                <div class="card-header">
                    <h5><i class='bx bx-calendar-plus me-2'></i>Formulario de Turno Quirúrgico</h5>
                </div>
                <div class="card-body">
                    <form action="<?= base_url('turnos/guardar') ?>" method="post">
                        <?= csrf_field() ?>
                        
                        <!-- Sección de Información Básica -->
                        <div class="form-section">
                            <h5 class="form-section-title"><i class='bx bx-info-circle'></i>Información Básica</h5>
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="fecha" class="form-label required-field"><i class='bx bx-calendar'></i>Fecha</label>
                                    <input type="date" class="form-control <?= isset($errors['fecha']) ? 'is-invalid' : '' ?>" 
                                           id="fecha" name="fecha" value="<?= old('fecha') ?>" 
                                           min="<?= date('Y-m-d') ?>" required>
                                    <?php if (isset($errors['fecha'])): ?>
                                        <div class="invalid-feedback"><?= esc($errors['fecha']) ?></div>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-4">
                                    <label for="hora_inicio" class="form-label required-field"><i class='bx bx-time'></i>Hora de inicio</label>
                                    <input type="time" class="form-control <?= isset($errors['hora_inicio']) ? 'is-invalid' : '' ?>" 
                                           id="hora_inicio" name="hora_inicio" value="<?= old('hora_inicio', '08:00') ?>" 
                                           pattern="[0-9]{2}:[0-9]{2}" required>
                                    <small id="hora_fin_calculada" class="d-block mt-1 text-muted"></small>
                                    <?php if (isset($errors['hora_inicio'])): ?>
                                        <div class="invalid-feedback"><?= esc($errors['hora_inicio']) ?></div>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-4">
                                    <label for="duracion" class="form-label required-field"><i class='bx bx-stopwatch'></i>Duración (minutos)</label>
                                    <input type="number" class="form-control <?= isset($errors['duracion']) ? 'is-invalid' : '' ?>" 
                                           id="duracion" name="duracion" value="<?= old('duracion', '60') ?>" 
                                           min="15" max="240" step="15" required>
                                    <input type="hidden" id="hora_finalizacion" name="hora_finalizacion" value="<?= old('hora_finalizacion') ?>">
                                    <?php if (isset($errors['duracion'])): ?>
                                        <div class="invalid-feedback"><?= esc($errors['duracion']) ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Sección de Personal Médico -->
                        <div class="form-section">
                            <h5 class="form-section-title"><i class='bx bx-user-plus'></i>Personal Médico</h5>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="id_paciente" class="form-label required-field"><i class='bx bx-user'></i>Paciente</label>
                                    <select class="form-select <?= isset($errors['id_paciente']) ? 'is-invalid' : '' ?>" 
                                            id="id_paciente" name="id_paciente" required>
                                        <option value="">Seleccionar paciente</option>
                                        <?php foreach ($pacientes as $paciente): ?>
                                            <option value="<?= $paciente->id ?>" <?= old('id_paciente') == $paciente->id ? 'selected' : '' ?>>
                                                <?= esc($paciente->nombre) ?> (<?= esc($paciente->dni) ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <?php if (isset($errors['id_paciente'])): ?>
                                        <div class="invalid-feedback"><?= esc($errors['id_paciente']) ?></div>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-6">
                                    <label for="id_quirofano" class="form-label required-field"><i class='bx bx-building'></i>Quirófano</label>
                                    <select class="form-select <?= isset($errors['id_quirofano']) ? 'is-invalid' : '' ?>" 
                                            id="id_quirofano" name="id_quirofano" required>
                                        <option value="">Seleccionar quirófano</option>
                                        <?php foreach ($quirofanos as $quirofano): ?>
                                            <option value="<?= $quirofano->id ?>" <?= old('id_quirofano') == $quirofano->id ? 'selected' : '' ?>>
                                                <?= esc($quirofano->nombre) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <?php if (isset($errors['id_quirofano'])): ?>
                                        <div class="invalid-feedback"><?= esc($errors['id_quirofano']) ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="id_cirujano" class="form-label required-field"><i class='bx bx-user-plus'></i>Cirujano Principal</label>
                                    <select class="form-select <?= isset($errors['id_cirujano']) ? 'is-invalid' : '' ?>" 
                                            id="id_cirujano" name="id_cirujano" required
                                            data-old-value="<?= old('id_cirujano') ?>">
                                        <option value="">Seleccionar cirujano</option>
                                        <?php foreach ($cirujanos as $cirujano): ?>
                                            <option value="<?= $cirujano->id ?>" 
                                                    data-especialidad="<?= $cirujano->id_especialidad ?>"
                                                    <?= old('id_cirujano') == $cirujano->id ? 'selected' : '' ?>>
                                                <?= esc($cirujano->nombre) ?> (<?= esc($cirujano->especialidad) ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <?php if (isset($errors['id_cirujano'])): ?>
                                        <div class="invalid-feedback"><?= esc($errors['id_cirujano']) ?></div>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-6">
                                    <label for="id_cirujano_ayudante" class="form-label"><i class='bx bx-user-plus'></i>Cirujano Ayudante</label>
                                    <select class="form-select <?= isset($errors['id_cirujano_ayudante']) ? 'is-invalid' : '' ?>" 
                                            id="id_cirujano_ayudante" name="id_cirujano_ayudante">
                                        <option value="">Seleccionar ayudante</option>
                                        <?php foreach ($cirujanos as $cirujano): ?>
                                            <option value="<?= $cirujano->id ?>" <?= old('id_cirujano_ayudante') == $cirujano->id ? 'selected' : '' ?>>
                                                <?= esc($cirujano->nombre) ?> (<?= esc($cirujano->especialidad) ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <?php if (isset($errors['id_cirujano_ayudante'])): ?>
                                        <div class="invalid-feedback"><?= esc($errors['id_cirujano_ayudante']) ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Sección de Personal de Apoyo -->
                        <div class="form-section">
                            <h5 class="form-section-title"><i class='bx bx-group'></i>Personal de Apoyo</h5>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="id_anestesista" class="form-label required-field"><i class='bx bx-plus-medical'></i>Anestesista</label>
                                    <select class="form-select <?= isset($errors['id_anestesista']) ? 'is-invalid' : '' ?>" 
                                            id="id_anestesista" name="id_anestesista" required>
                                        <option value="">Seleccionar anestesista</option>
                                        <?php foreach ($anestesistas as $anestesista): ?>
                                            <option value="<?= $anestesista->id ?>" <?= old('id_anestesista') == $anestesista->id ? 'selected' : '' ?>>
                                                <?= esc($anestesista->nombre) ?> (<?= esc($anestesista->especialidad) ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <?php if (isset($errors['id_anestesista'])): ?>
                                        <div class="invalid-feedback"><?= esc($errors['id_anestesista']) ?></div>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-6">
                                    <label for="tipo_anestesia" class="form-label required-field"><i class='bx bx-syringe'></i>Tipo de Anestesia</label>
                                    <select class="form-select <?= isset($errors['tipo_anestesia']) ? 'is-invalid' : '' ?>" 
                                            id="tipo_anestesia" name="tipo_anestesia" required>
                                        <option value="">Seleccionar tipo</option>
                                        <option value="General" <?= old('tipo_anestesia') == 'General' ? 'selected' : '' ?>>General</option>
                                        <option value="Regional" <?= old('tipo_anestesia') == 'Regional' ? 'selected' : '' ?>>Regional</option>
                                        <option value="Local" <?= old('tipo_anestesia') == 'Local' ? 'selected' : '' ?>>Local</option>
                                        <option value="Sedación" <?= old('tipo_anestesia') == 'Sedación' ? 'selected' : '' ?>>Sedación</option>
                                    </select>
                                    <?php if (isset($errors['tipo_anestesia'])): ?>
                                        <div class="invalid-feedback"><?= esc($errors['tipo_anestesia']) ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="id_tecnico_anestesista" class="form-label required-field"><i class='bx bx-user'></i>Técnico Anestesista</label>
                                    <select class="form-select <?= isset($errors['id_tecnico_anestesista']) ? 'is-invalid' : '' ?>" 
                                            id="id_tecnico_anestesista" name="id_tecnico_anestesista" required>
                                        <option value="">Seleccionar técnico anestesista</option>
                                        <?php foreach ($tecnicos_anestesistas as $tecnico): ?>
                                            <option value="<?= $tecnico->id ?>" <?= old('id_tecnico_anestesista') == $tecnico->id ? 'selected' : '' ?>>
                                                <?= esc($tecnico->nombre) ?> (<?= esc($tecnico->especialidad) ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <?php if (isset($errors['id_tecnico_anestesista'])): ?>
                                        <div class="invalid-feedback"><?= esc($errors['id_tecnico_anestesista']) ?></div>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-4">
                                    <label for="id_instrumentador_principal" class="form-label required-field"><i class='bx bx-user'></i>Instrumentador Principal</label>
                                    <select class="form-select <?= isset($errors['id_instrumentador_principal']) ? 'is-invalid' : '' ?>" 
                                            id="id_instrumentador_principal" name="id_instrumentador_principal" required>
                                        <option value="">Seleccionar instrumentador</option>
                                        <?php foreach ($instrumentistas as $instrumentista): ?>
                                            <option value="<?= $instrumentista->id ?>" <?= old('id_instrumentador_principal') == $instrumentista->id ? 'selected' : '' ?>>
                                                <?= esc($instrumentista->nombre) ?> (<?= esc($instrumentista->especialidad) ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <?php if (isset($errors['id_instrumentador_principal'])): ?>
                                        <div class="invalid-feedback"><?= esc($errors['id_instrumentador_principal']) ?></div>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-4">
                                    <label for="id_instrumentador_circulante" class="form-label required-field"><i class='bx bx-user'></i>Instrumentador Circulante</label>
                                    <select class="form-select <?= isset($errors['id_instrumentador_circulante']) ? 'is-invalid' : '' ?>" 
                                            id="id_instrumentador_circulante" name="id_instrumentador_circulante" required>
                                        <option value="">Seleccionar instrumentador</option>
                                        <?php foreach ($instrumentistas as $instrumentista): ?>
                                            <option value="<?= $instrumentista->id ?>" <?= old('id_instrumentador_circulante') == $instrumentista->id ? 'selected' : '' ?>>
                                                <?= esc($instrumentista->nombre) ?> (<?= esc($instrumentista->especialidad) ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                    <?php if (isset($errors['id_instrumentador_circulante'])): ?>
                                        <div class="invalid-feedback"><?= esc($errors['id_instrumentador_circulante']) ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Sección de Procedimiento -->
                        <div class="form-section">
                            <h5 class="form-section-title"><i class='bx bx-plus-medical'></i>Procedimiento</h5>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="procedimiento" class="form-label required-field"><i class='bx bx-list-check'></i>Procedimiento</label>
                                    <select class="form-select <?= isset($errors['procedimiento']) ? 'is-invalid' : '' ?>" 
                                            id="procedimiento" name="procedimiento" required
                                            data-old-value="<?= old('procedimiento') ?>">
                                        <option value="">Seleccionar procedimiento</option>
                                        <option value="otro" <?= old('procedimiento') == 'otro' ? 'selected' : '' ?>>Otro (especificar)</option>
                                    </select>
                                    <input type="text" class="form-control mt-2 <?= (old('procedimiento') == 'otro') ? '' : 'd-none' ?> <?= isset($errors['otro_procedimiento']) ? 'is-invalid' : '' ?>" 
                                           id="otro_procedimiento" name="otro_procedimiento" 
                                           placeholder="Especificar otro procedimiento" value="<?= old('otro_procedimiento') ?>"
                                           data-old-value="<?= old('otro_procedimiento') ?>">
                                    <?php if (isset($errors['procedimiento'])): ?>
                                        <div class="invalid-feedback"><?= esc($errors['procedimiento']) ?></div>
                                    <?php endif; ?>
                                    <?php if (isset($errors['otro_procedimiento'])): ?>
                                        <div class="invalid-feedback"><?= esc($errors['otro_procedimiento']) ?></div>
                                    <?php endif; ?>
                                </div>
                                <div class="col-md-6">
                                    <label for="estado" class="form-label required-field"><i class='bx bx-check-circle'></i>Estado</label>
                                    <select class="form-select <?= isset($errors['estado']) ? 'is-invalid' : '' ?>" 
                                            id="estado" name="estado" required>
                                        <option value="programado" <?= old('estado', 'programado') == 'programado' ? 'selected' : '' ?>>Programado</option>
                                        <option value="agendada" <?= old('estado') == 'agendada' ? 'selected' : '' ?>>Agendada</option>
                                        <option value="urgencia" <?= old('estado') == 'urgencia' ? 'selected' : '' ?>>Urgencia</option>
                                    </select>
                                    <?php if (isset($errors['estado'])): ?>
                                        <div class="invalid-feedback"><?= esc($errors['estado']) ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Sección de Observaciones y Complicaciones -->
                        <div class="form-section">
                            <h5 class="form-section-title"><i class='bx bx-note'></i>Observaciones y Complicaciones</h5>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="observaciones" class="form-label"><i class='bx bx-edit'></i>Observaciones</label>
                                    <textarea class="form-control" id="observaciones" name="observaciones" rows="3"><?= old('observaciones') ?></textarea>
                                </div>
                                <div class="col-md-6">
                                    <label for="complicaciones" class="form-label"><i class='bx bx-error'></i>Complicaciones</label>
                                    <textarea class="form-control" id="complicaciones" name="complicaciones" rows="3"><?= old('complicaciones') ?></textarea>
                                </div>
                            </div>
                        </div>

                        <!-- Sección de Insumos -->
                        <div class="form-section">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="glass-card">
                                        <div class="card-header d-flex justify-content-between align-items-center">
                                            <h5 class="mb-0"><i class='bx bx-package'></i>Insumos Requeridos</h5>
                                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#insumosModal">
                                                <i class="bx bx-plus"></i> Seleccionar Insumos
                                            </button>
                                        </div>
                                        <div class="card-body">
                                            <div class="table-responsive">
                                                <table class="table table-bordered" id="tabla-insumos-seleccionados">
                                                    <thead>
                                                        <tr>
                                                            <th>Insumo</th>
                                                            <th>Stock</th>
                                                            <th>Cantidad</th>
                                                            <th>Acciones</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="insumos-seleccionados">
                                                        <!-- Contenido dinámico de insumos -->
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-4">
                            <a href="<?= base_url('turnos') ?>" class="btn btn-secondary">
                                <i class='bx bx-x me-1'></i>Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class='bx bx-save me-1'></i>Crear Turno
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Selección de Insumos -->
    <div class="modal fade" id="insumosModal" tabindex="-1" aria-labelledby="insumosModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content insumos-modal">
                <div class="modal-header insumos-modal-header">
                    <h5 class="modal-title insumos-modal-title" id="insumosModalLabel">
                        <i class='bx bx-package'></i>Seleccionar Insumos
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body insumos-modal-body">
                    <!-- Buscador de insumos -->
                    <div class="row mb-3">
                        <div class="col-md-8">
                            <div class="input-group">
                                <input type="text" class="form-control" id="buscador-insumos" placeholder="Buscar por nombre o tipo de insumo...">
                                <button class="btn btn-primary" type="button" id="btn-buscar-insumos">
                                    <i class="bx bx-search"></i> Buscar
                                </button>
                                <button class="btn btn-outline-light" type="button" id="btn-limpiar-busqueda" title="Limpiar búsqueda">
                                    <i class="bx bx-x"></i>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div id="filtros-avanzados">
                                <label class="form-label">Filtrar por stock:</label>
                                <select class="form-select" id="filtro-stock">
                                    <option value="">Todos</option>
                                    <option value="bajo">Stock bajo (< 10)</option>
                                    <option value="normal">Stock normal (≥ 10)</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div id="contador-resultados" class="text-muted small mb-2">Mostrando <?= count($insumos) ?> insumos</div>
                    
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover table-insumos">
                            <thead class="table-light">
                                <tr>
                                    <th width="50px">Seleccionar</th>
                                    <th>Insumo</th>
                                    <th>Tipo</th>
                                    <th width="100px">Stock</th>
                                    <th width="120px">Cantidad</th>
                                </tr>
                            </thead>
                            <tbody id="lista-insumos">
                                <?php foreach ($insumos as $insumo): ?>
                                    <tr data-insumo-id="<?= $insumo['id_insumo'] ?>" 
                                        data-nombre="<?= esc($insumo['nombre']) ?>"
                                        data-tipo="<?= esc($insumo['tipo']) ?>"
                                        data-stock="<?= $insumo['cantidad'] ?>"
                                        class="insumo-row">
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input insumo-check" type="checkbox" 
                                                       id="insumo-modal-<?= $insumo['id_insumo'] ?>"
                                                       data-nombre="<?= esc($insumo['nombre']) ?>"
                                                       data-tipo="<?= esc($insumo['tipo']) ?>"
                                                       data-stock="<?= $insumo['cantidad'] ?>"
                                                       <?= (isset(old('insumos')[$insumo['id_insumo']])) ? 'checked' : '' ?>>
                                            </div>
                                        </td>
                                        <td><?= esc($insumo['nombre']) ?></td>
                                        <td><?= esc($insumo['tipo']) ?></td>
                                        <td><span class="badge <?= ($insumo['cantidad'] < 10) ? 'bg-danger' : 'bg-success' ?>"><?= $insumo['cantidad'] ?></span></td>
                                        <td>
                                            <input type="number" class="form-control cantidad-insumo-modal" 
                                                   min="1" max="<?= $insumo['cantidad'] ?>" 
                                                   value="<?= isset(old('insumos')[$insumo['id_insumo']]) ? old('insumos')[$insumo['id_insumo']] : '1' ?>"
                                                   <?= (isset(old('insumos')[$insumo['id_insumo']])) ? '' : 'disabled' ?>>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer insumos-modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class='bx bx-x me-1'></i>Cancelar
                    </button>
                    <button type="button" class="btn btn-success insumos-btn-confirm" id="btn-guardar-insumos">
                        <i class='bx bx-save me-1'></i>Guardar Selección
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
$(document).ready(function() {
    // Inicializar el modal de Bootstrap
    var insumosModal = new bootstrap.Modal(document.getElementById('insumosModal'));
    
    // Manejar la selección de insumos en el modal
    $(document).on('change', '.insumo-check', function() {
        const row = $(this).closest('tr');
        const cantidadInput = row.find('.cantidad-insumo-modal');
        
        if ($(this).is(':checked')) {
            cantidadInput.prop('disabled', false);
        } else {
            cantidadInput.prop('disabled', true).val('1');
        }
    });

    // Función para actualizar el contador de resultados
    function actualizarContador() {
        const total = $('.insumo-row:visible').length;
        $('#contador-resultados').text(`Mostrando ${total} insumos`);
    }

    // Función para filtrar insumos
    function filtrarInsumos() {
        const terminoBusqueda = $('#buscador-insumos').val().toLowerCase();
        const filtroStock = $('#filtro-stock').val();
        
        $('.insumo-row').each(function() {
            const nombre = $(this).data('nombre').toLowerCase();
            const tipo = $(this).data('tipo').toLowerCase();
            const stock = parseInt($(this).data('stock'));
            let mostrar = true;
            
            // Aplicar filtro de búsqueda
            if (terminoBusqueda && !nombre.includes(terminoBusqueda) && !tipo.includes(terminoBusqueda)) {
                mostrar = false;
            }
            
            // Aplicar filtro de stock
            if (filtroStock === 'bajo' && stock >= 10) {
                mostrar = false;
            } else if (filtroStock === 'normal' && stock < 10) {
                mostrar = false;
            }
            
            if (mostrar) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
        
        actualizarContador();
    }

    // Eventos para los filtros
    $('#buscador-insumos, #filtro-stock').on('input change', filtrarInsumos);
    
    // Limpiar búsqueda
    $('#btn-limpiar-busqueda').click(function() {
        $('#buscador-insumos').val('');
        $('#filtro-stock').val('');
        filtrarInsumos();
    });

    // Guardar selección de insumos
    $('#btn-guardar-insumos').click(function() {
        const tbody = $('#insumos-seleccionados');
        tbody.empty();

        $('#lista-insumos tr').each(function() {
            const checkbox = $(this).find('.insumo-check');
            if (checkbox.is(':checked')) {
                const id = $(this).data('insumo-id');
                const nombre = checkbox.data('nombre');
                const tipo = checkbox.data('tipo');
                const stock = checkbox.data('stock');
                const cantidad = $(this).find('.cantidad-insumo-modal').val();

                tbody.append(`
                    <tr data-insumo-id="${id}">
                        <td>${nombre} - ${tipo}</td>
                        <td><span class="badge ${stock < 10 ? 'bg-danger' : 'bg-success'}">${stock}</span></td>
                        <td>
                            <input type="hidden" name="insumos[${id}]" value="${cantidad}">
                            ${cantidad}
                        </td>
                        <td>
                            <button type="button" class="btn btn-sm btn-danger quitar-insumo">
                                <i class="bx bx-trash"></i>
                            </button>
                        </td>
                    </tr>
                `);
            }
        });

        // Cerrar el modal correctamente
        insumosModal.hide();
        
        // Limpiar manualmente cualquier efecto residual del modal
        $('body').removeClass('modal-open');
        $('.modal-backdrop').remove();
    });

    // Quitar insumo de la lista
    $(document).on('click', '.quitar-insumo', function() {
        $(this).closest('tr').remove();
    });

    // Calcular hora de finalización
    function calcularHoraFin() {
        const horaInicio = $('#hora_inicio').val();
        const duracion = parseInt($('#duracion').val()) || 0;
        
        if (horaInicio && duracion > 0) {
            const [horas, minutos] = horaInicio.split(':').map(Number);
            const fechaInicio = new Date();
            fechaInicio.setHours(horas, minutos, 0, 0);
            
            const fechaFin = new Date(fechaInicio.getTime() + duracion * 60000);
            const horaFin = fechaFin.getHours().toString().padStart(2, '0') + ':' + 
                            fechaFin.getMinutes().toString().padStart(2, '0');
            
            $('#hora_fin_calculada').text(`Hora estimada de finalización: ${horaFin}`);
            $('#hora_finalizacion').val(horaFin);
        } else {
            $('#hora_fin_calculada').text('');
            $('#hora_finalizacion').val('');
        }
    }

    $('#hora_inicio, #duracion').on('change input', calcularHoraFin);

    // Mostrar/ocultar campo de otro procedimiento
    $('#procedimiento').on('change', function() {
        if ($(this).val() === 'otro') {
            $('#otro_procedimiento').removeClass('d-none');
        } else {
            $('#otro_procedimiento').addClass('d-none');
        }
    });

    // Aplicar animaciones escalonadas
    const elementosAnimados = document.querySelectorAll('.animate-fade-in');
    elementosAnimados.forEach((elemento, index) => {
        elemento.style.animationDelay = `${index * 0.1}s`;
    });

    // Inicializar filtros al abrir el modal
    $('#insumosModal').on('shown.bs.modal', function () {
        filtrarInsumos();
    });

    // Manejar el evento de cierre del modal para limpieza adicional
    $('#insumosModal').on('hidden.bs.modal', function () {
        $('body').removeClass('modal-open');
        $('body').css({
            'overflow': 'auto',
            'padding-right': ''
        });
        $('.modal-backdrop').remove();
    });

    // Función para actualizar opciones disponibles por categoría
    function actualizarOpciones() {
        // Definir grupos de selectores que comparten profesionales
        const grupos = {
            cirujanos: ['#id_cirujano', '#id_cirujano_ayudante'],
            instrumentistas: ['#id_instrumentador_principal', '#id_instrumentador_circulante'],
            anestesistas: ['#id_anestesista'],
            tecnicos: ['#id_tecnico_anestesista']
        };

        // Habilitar todas las opciones primero
        $('select option').prop('disabled', false);

        // Para cada grupo, deshabilitar las opciones seleccionadas en otros selects del mismo grupo
        Object.values(grupos).forEach(grupo => {
            // Obtener todos los IDs seleccionados en este grupo
            const seleccionados = grupo
                .map(selector => $(selector).val())
                .filter(id => id !== '' && id !== null);

            // Para cada select en el grupo
            grupo.forEach(selector => {
                const currentSelect = $(selector);
                const currentValue = currentSelect.val();
                
                // Habilitar todas las opciones en este select primero
                currentSelect.find('option').prop('disabled', false);
                
                // Para cada ID seleccionado en el grupo (excepto el actual)
                seleccionados.forEach(id => {
                    if (id && id !== currentValue) {
                        // Deshabilitar esta opción en el select actual
                        currentSelect.find(`option[value="${id}"]`).prop('disabled', true);
                    }
                });
            });
        });
    }

    // Llamar a actualizarOpciones cuando cambie cualquier select
    $('select').on('change', function() {
        actualizarOpciones();
    });

    // Inicializar al cargar la página
    actualizarOpciones();
});
</script>

    <!-- Incluir el footer desde la carpeta includes -->
    <?= view('includes/footer') ?>
</body>
</html>