<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title><?= esc($titulo) ?> - Gestión Quirúrgica</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet" />
    <style>
        .app-container {
            min-height: 100vh;
            background-color: #f8f9fa;
        }
        .main-content {
            padding: 20px;
        }
        .welcome-section {
            margin-bottom: 20px;
        }
        .page-title {
            color: #2c3e50;
            font-weight: 600;
        }
        .data-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .data-card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px 20px;
        }
        .data-card-body {
            padding: 20px;
        }
        .btn-group .btn {
            margin: 0 2px;
        }
    </style>
</head>
<body>
    <div class="app-container">
        <?//= view('includes/sidebar') ?>

        <div class="main-content">
            <?//= view('includes/header') ?>

            <div class="main-wrapper">
                <div class="welcome-section">
                    <div class="d-flex justify-content-between align-items-center">
                        <h1 class="page-title">
                            <i class="bi bi-person-lines-fill me-2"></i><?= esc($titulo) ?>
                        </h1>
                        <a href="<?= site_url('pacientes/crear') ?>" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-1"></i> Nuevo Paciente
                        </a>
                    </div>
                </div>

                <?php if (session()->getFlashdata('mensaje')): ?>
                    <div class="alert alert-success alert-dismissible fade show">
                        <?= esc(session()->getFlashdata('mensaje')) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show">
                        <?= esc(session()->getFlashdata('error')) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <div class="data-card">
                    <div class="data-card-header">
                        <h5 class="mb-0">Listado de Pacientes</h5>
                    </div>
                    <div class="data-card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>DNI</th>
                                        <th>Teléfono</th>
                                        <th>Dirección</th>
                                        <th>Email</th>
                                        <th class="text-end">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($pacientes)): ?>
                                        <tr>
                                            <td colspan="6" class="text-center">No hay pacientes registrados</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($pacientes as $paciente): ?>
                                            <tr>
                                                <td><?= esc($paciente->nombre) ?></td>
                                                <td><?= esc($paciente->dni) ?></td>
                                                <td><?= esc($paciente->telefono) ?></td>
                                                <td><?= esc($paciente->direccion) ?></td>
                                                <td><?= esc($paciente->email) ?></td>
                                                <td class="text-end">
                                                    <div class="btn-group">
                                                        <a href="<?= site_url('pacientes/ver/'.$paciente->id) ?>" class="btn btn-sm btn-secondary" title="Ver">
                                                            <i class="bi bi-eye"></i>
                                                        </a>
                                                        <a href="<?= site_url('pacientes/editar/'.$paciente->id) ?>" class="btn btn-sm btn-primary" title="Editar">
                                                            <i class="bi bi-pencil"></i>
                                                        </a>
                                                        <button type="button"
                                                            class="btn btn-sm btn-danger"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#modalEliminarPaciente"
                                                            data-id="<?= esc($paciente->id) ?>"
                                                            data-nombre="<?= esc($paciente->nombre) ?>"
                                                            title="Eliminar">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
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

                <!-- Modal Eliminar Paciente -->
                <div class="modal fade" id="modalEliminarPaciente" tabindex="-1" aria-labelledby="modalEliminarPacienteLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="formEliminarPaciente" method="post" action="">
                <?= csrf_field() ?>
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="modalEliminarPacienteLabel">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>Confirmar Eliminación
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    <p>¿Está seguro que desea eliminar al paciente <strong><span id="nombrePacienteModal"></span></strong>?</p>
                    <p class="fw-bold text-danger">Esta acción no se puede deshacer.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash me-1"></i> Eliminar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const modalEliminar = document.getElementById('modalEliminarPaciente');
        const formEliminar = document.getElementById('formEliminarPaciente');

        if (modalEliminar) {
            modalEliminar.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;
                const id = button.getAttribute('data-id');
                const nombre = button.getAttribute('data-nombre');

                document.getElementById('nombrePacienteModal').textContent = nombre;
                formEliminar.action = '<?= site_url("pacientes/delete") ?>/' + id;
            });
        }

        // Auto-ocultar alertas después de 5 segundos
        const alertas = document.querySelectorAll('.alert');
        alertas.forEach(alerta => {
            setTimeout(() => {
                alerta.classList.remove('show');
            }, 5000);
        });
    });
</script>
</body>
</html>
