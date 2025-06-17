<?= $this->extend('layouts/main') ?>

<?= $this->section('title') ?><?= $titulo ?> - Gestión Quirúrgica<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="app-container">
    <!-- Sidebar -->
    <?= view('includes/sidebar') ?>
    
    <div class="main-wrapper">
        <div class="welcome-section">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="page-title"><i class="bi bi-calendar-plus me-2"></i><?= $titulo ?></h1>
                <a href="<?= base_url('turnos/crear') ?>" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-1"></i> Nuevo Turno
                </a>
            </div>
        </div>

        <?php if(session()->getFlashdata('mensaje')): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?= session()->getFlashdata('mensaje') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <?php if(session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show">
            <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php endif; ?>

        <div class="data-card">
            <div class="data-card-header">
                <h5 class="mb-0">Listado de Turnos Quirúrgicos</h5>
            </div>
            <div class="data-card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Fecha</th>
                                <th>Hora</th>
                                <th>Paciente</th>
                                <th>Médico</th>
                                <th>Quirófano</th>
                                <th>Procedimiento</th>
                                <th>Estado</th>
                                <th class="text-end">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($turnos as $turno): ?>
                            <tr>
                                <td><?= date('d/m/Y', strtotime($turno['fecha'])) ?></td>
                                <td><?= date('H:i', strtotime($turno['hora_inicio'])) ?></td>
                                <td><?= esc($turno['nombre_paciente']) ?></td>
                                <td><?= esc($turno['nombre_medico']) ?></td>
                                <td><?= esc($turno['nombre_quirofano']) ?></td>
                                <td><?= esc($turno['procedimiento']) ?></td>
                                <td>
                                    <span class="badge 
                                        <?= $turno['estado'] == 'programado' ? 'bg-primary' : 
                                           ($turno['estado'] == 'en_proceso' ? 'bg-warning text-dark' : 
                                           ($turno['estado'] == 'completado' ? 'bg-success' : 'bg-secondary')) ?>">
                                        <?= ucfirst(str_replace('_', ' ', $turno['estado'])) ?>
                                    </span>
                                </td>
                                <td class="text-end">
                                    <div class="btn-group">
                                        <a href="<?= base_url('turnos/ver/'.$turno['id']) ?>" class="btn btn-sm btn-secondary">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <a href="<?= base_url('turnos/editar/'.$turno['id']) ?>" class="btn btn-sm btn-primary">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        <button type="button" 
                                           class="btn btn-sm btn-danger" 
                                           data-bs-toggle="modal"
                                           data-bs-target="#modalEliminarTurno"
                                           data-id="<?= $turno['id'] ?>"
                                           data-paciente="<?= esc($turno['nombre_paciente']) ?>"
                                           <?= in_array($turno['estado'], ['en_proceso', 'completado']) ? 'disabled' : '' ?>>
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Modal para eliminar turno -->
        <div class="modal fade" id="modalEliminarTurno" tabindex="-1" aria-labelledby="modalEliminarTurnoLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title" id="modalEliminarTurnoLabel">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>Confirmar Cancelación
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>¿Está seguro que desea cancelar el turno del paciente <strong><span id="nombrePacienteModal"></span></strong>?</p>
                        <p class="fw-bold text-danger">Esta acción cambiará el estado a "cancelado".</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-1"></i> Cancelar
                        </button>
                        <a href="#" id="btnConfirmarEliminar" class="btn btn-danger">
                            <i class="bi bi-trash me-1"></i> Confirmar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const modalEliminar = document.getElementById('modalEliminarTurno');
    
    if (modalEliminar) {
        modalEliminar.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const id = button.getAttribute('data-id');
            const paciente = button.getAttribute('data-paciente');
            
            document.getElementById('nombrePacienteModal').textContent = paciente;
            const btnEliminar = document.getElementById('btnConfirmarEliminar');
            btnEliminar.href = '<?= base_url("turnos/eliminar") ?>/' + id;
        });
    }
});
</script>
<?= $this->endSection() ?>

<?= $this->endSection() ?>

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
    .badge-categoria {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
    }
</style>