<?php
// Vista principal de turnos quirúrgicos
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Turnos Quirúrgicos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="<?= base_url('assets/css/turnos.css') ?>" rel="stylesheet">
</head>
<body>
    <!-- Incluir sidebar del sistema -->
    <?= $this->include('includes/sidebar') ?>

    <!-- Contenido principal -->
    <div class="main-content">
        <!-- Header -->
        <div class="dashboard-header animate-fade-in">
            <h1>
                <i class='bx bx-calendar'></i> Gestion de Turnos Quirúrgicos
            </h1>
            <a href="<?= base_url('turnos/crear') ?>" class="btn btn-primary">
                <i class='bx bx-plus'></i> Nuevo Turno
            </a>
        </div>

        <!-- Mensajes flash -->
        <?php if (session('message')): ?>
            <div class="alert alert-success alert-dismissible fade show glass-card">
                <div class="d-flex align-items-center">
                    <i class='bx bx-check-circle me-2'></i>
                    <?= session('message') ?>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (session('error')): ?>
            <div class="alert alert-danger alert-dismissible fade show glass-card">
                <div class="d-flex align-items-center">
                    <i class='bx bx-error-circle me-2'></i>
                    <?= session('error') ?>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Tabla de turnos -->
        <div class="glass-card animate-fade-in" style="animation-delay: 0.1s">
            <div class="card-header">
                <h5><i class='bx bx-table me-2'></i>Listado de Turnos</h5>
                <span class="badge bg-primary"><?= count($turnos) ?> registros</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Hora Inicio</th>
                                <th>Paciente</th>
                                <th>Cirujano</th>
                                <th>Quirófano</th>
                                <th>Procedimiento</th>
                                <th>Estado</th>
                                <th class="text-end">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($turnos)): ?>
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <i class='bx bx-calendar-x' style="font-size: 2rem; color: var(--gray-400);"></i>
                                        <p class="mt-2">No hay turnos programados</p>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($turnos as $turno): ?>
                                    <tr>
                                        <td><?= date('d/m/Y', strtotime($turno['fecha'])) ?></td>
                                        <td><?= date('H:i', strtotime($turno['hora_inicio'])) ?></td>
                                        <td><?= $turno['nombre_paciente'] ?></td>
                                        <td><?= $turno['nombre_cirujano'] ?></td>
                                        <td><?= $turno['nombre_quirofano'] ?></td>
                                        <td><?= $turno['procedimiento'] ?></td>
                                        <td>
                                            <span class="badge-estado bg-<?= $turno['estado'] ?>">
                                                <?= ucfirst(str_replace('_', ' ', $turno['estado'])) ?>
                                            </span>
                                        </td>
                                        <td class="text-end">
                                            <div class="d-flex justify-content-end">
                                                <a href="<?= base_url('turnos/ver/' . $turno['id']) ?>" class="btn-action btn-view" title="Ver detalles">
                                                    <i class='bx bx-show'></i>
                                                </a>
                                                <a href="<?= base_url('turnos/editar/' . $turno['id']) ?>" class="btn-action btn-edit" title="Editar">
                                                    <i class='bx bx-edit'></i>
                                                </a>
                                                <?php if ($turno['estado'] == 'programado' || $turno['estado'] == 'agendada' || $turno['estado'] == 'urgencia'): ?>
                                                    <button type="button"
                                                        class="btn-action btn-cancel"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#modalCancelar"
                                                        onclick="configurarModalCancelar({
                                                            idElemento: '<?= $turno['id'] ?>',
                                                            nombreElemento: '<?= esc($turno['nombre_paciente']) ?>',
                                                            actionUrl: '<?= base_url('turnos/cancelar/' . $turno['id']) ?>',
                                                            titulo: 'Cancelar Turno',
                                                            mensajeAdicional: 'El turno será marcado como cancelado.',
                                                            icono: 'bx-calendar-x'
                                                        })"
                                                        title="Cancelar">
                                                        <i class='bx bx-x'></i>
                                                    </button>
                                                <?php endif; ?>
                                                <?php if ($turno['estado'] == 'cancelado'): ?>
                                                    <button type="button"
                                                        class="btn-action btn-delete"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#modalEliminar"
                                                        onclick="configurarModalEliminar({
                                                            idElemento: '<?= $turno['id'] ?>',
                                                            nombreElemento: '<?= esc($turno['nombre_paciente']) ?>',
                                                            actionUrl: '<?= base_url('turnos/eliminar-definitivo/' . $turno['id']) ?>',
                                                            titulo: 'Eliminar Turno',
                                                            mensajeAdicional: 'Se eliminará permanentemente este registro.',
                                                            icono: 'bx-trash'
                                                        })"
                                                        title="Eliminar">
                                                        <i class='bx bx-trash'></i>
                                                    </button>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Incluir modales -->
    <?= $this->include('includes/modal_eliminar') ?>
    <?= $this->include('includes/modal_cancelar') ?>

    <!-- Incluir footer del sistema -->
    <?= $this->include('includes/footer') ?>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="<?= base_url('js/turnos.js') ?>"></script>
    
    <script>
    // Función para configurar el modal de cancelación
    function configurarModalCancelar(options) {
        const {
            idElemento,
            nombreElemento,
            actionUrl,
            titulo = 'Confirmar Cancelación',
            mensajeAdicional = 'El turno será marcado como cancelado.',
            icono = 'bx-calendar-x'
        } = options;

        document.getElementById('modalCancelarTitulo').textContent = titulo;
        document.getElementById('idElementoCancelar').value = idElemento;
        document.getElementById('nombreElementoCancelar').textContent = nombreElemento;
        document.getElementById('mensajeAdicionalCancelar').textContent = mensajeAdicional;
        document.getElementById('formCancelar').action = actionUrl;
        document.querySelector('.cancel-icon-main i').className = `bx ${icono}`;
    }

    // Función para configurar el modal de eliminación
    function configurarModalEliminar(options) {
        const {
            idElemento,
            nombreElemento,
            actionUrl,
            titulo = 'Confirmar Eliminación',
            mensajeAdicional = 'Esta acción no se puede deshacer y se perderán todos los datos asociados.',
            icono = 'bx-trash'
        } = options;

        document.getElementById('modalEliminarTitulo').textContent = titulo;
        document.getElementById('idElemento').value = idElemento;
        document.getElementById('nombreElemento').textContent = nombreElemento;
        document.getElementById('mensajeAdicional').textContent = mensajeAdicional;
        document.getElementById('formEliminar').action = actionUrl;
        document.querySelector('.delete-icon-main i').className = `bx ${icono}`;
    }
    </script>
</body>
</html>