<!-- Modal de Insumos Reutilizable -->
<div class="modal fade" id="modalInsumos" tabindex="-1" aria-labelledby="modalInsumosLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalInsumosLabel">
                    <i class='bx bx-plus-medical me-2'></i>
                    Seleccionar Insumos Quirúrgicos
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-8">
                        <div class="input-group">
                            <input type="text" class="form-control" id="buscador-insumos" placeholder="Buscar insumo...">
                            <button class="btn btn-primary" type="button" id="btn-buscar-insumos">
                                <i class='bx bx-search'></i> Buscar
                            </button>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <select class="form-select" id="filtro-categoria">
                            <option value="">Todas las categorías</option>
                            <option value="medicamento">Medicamentos</option>
                            <option value="material">Materiales</option>
                            <option value="instrumento">Instrumentos</option>
                        </select>
                    </div>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-hover table-insumos">
                        <thead>
                            <tr>
                                <th>Seleccionar</th>
                                <th>Insumo</th>
                                <th>Categoría</th>
                                <th>Stock</th>
                                <th>Cantidad</th>
                            </tr>
                        </thead>
                        <tbody id="lista-insumos">
                            <!-- Aquí se cargarán los insumos disponibles -->
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="modal-footer">
                <div id="contador-seleccionados" class="text-muted me-auto">
                    0 insumos seleccionados
                </div>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class='bx bx-x'></i> Cancelar
                </button>
                <button type="button" class="btn btn-primary" id="btn-confirmar-insumos">
                    <i class='bx bx-check'></i> Confirmar Selección
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Función para inicializar el modal de insumos
function inicializarModalInsumos() {
    // Insumos seleccionados
    let insumosSeleccionados = {};

    // Función para cargar insumos en el modal
    function cargarInsumos(termino = '', categoria = '') {
        $.get('<?= site_url('turnos/buscarInsumos') ?>', {termino: termino, categoria: categoria}, function(data) {
            const tbody = $('#lista-insumos');
            tbody.empty();
            
            if (data.length === 0) {
                tbody.append('<tr><td colspan="5" class="text-center py-4 text-muted">No se encontraron insumos</td></tr>');
                return;
            }
            
            data.forEach(insumo => {
                const fila = `
                    <tr>
                        <td>
                            <div class="form-check">
                                <input class="form-check-input check-insumo" type="checkbox" 
                                       data-id="${insumo.id_insumo}" 
                                       data-nombre="${insumo.nombre}" 
                                       data-stock="${insumo.cantidad}" 
                                       data-categoria="${insumo.tipo}">
                            </div>
                        </td>
                        <td>${insumo.nombre}</td>
                        <td>${insumo.tipo}</td>
                        <td><span class="badge ${insumo.cantidad < 10 ? 'bg-danger' : 'bg-success'}">${insumo.cantidad}</span></td>
                        <td>
                            <input type="number" class="form-control cantidad-insumo" 
                                   min="1" max="${insumo.cantidad}" 
                                   value="1" disabled>
                        </td>
                    </tr>
                `;
                tbody.append(fila);
            });
        }).fail(function() {
            console.error('Error al cargar insumos');
        });
    }

    // Evento para seleccionar/deseleccionar insumos
    $(document).on('change', '.check-insumo', function() {
        const id = $(this).data('id');
        const cantidadInput = $(this).closest('tr').find('.cantidad-insumo');
        
        if ($(this).is(':checked')) {
            cantidadInput.prop('disabled', false);
            insumosSeleccionados[id] = {
                id: id,
                nombre: $(this).data('nombre'),
                cantidad: cantidadInput.val(),
                stock: $(this).data('stock'),
                categoria: $(this).data('categoria')
            };
        } else {
            cantidadInput.prop('disabled', true);
            delete insumosSeleccionados[id];
        }
        
        $('#contador-seleccionados').text(`${Object.keys(insumosSeleccionados).length} insumos seleccionados`);
    });

    // Evento para cambiar cantidad de insumo
    $(document).on('change', '.cantidad-insumo', function() {
        const id = $(this).closest('tr').find('.check-insumo').data('id');
        if (insumosSeleccionados[id]) {
            insumosSeleccionados[id].cantidad = $(this).val();
        }
    });

    // Evento para buscar insumos
    $('#btn-buscar-insumos').click(function() {
        const termino = $('#buscador-insumos').val();
        const categoria = $('#filtro-categoria').val();
        cargarInsumos(termino, categoria);
    });

    // Evento al presionar Enter en el buscador
    $('#buscador-insumos').keypress(function(e) {
        if (e.which === 13) {
            $('#btn-buscar-insumos').click();
        }
    });

    // Evento para filtrar por categoría
    $('#filtro-categoria').change(function() {
        $('#btn-buscar-insumos').click();
    });

    // Evento para mostrar el modal de insumos
    $('#modalInsumos').on('show.bs.modal', function() {
        cargarInsumos();
    });

    return {
        getInsumosSeleccionados: function() {
            return insumosSeleccionados;
        },
        setInsumosSeleccionados: function(insumos) {
            insumosSeleccionados = insumos;
        }
    };
}

// Inicializar el modal cuando el DOM esté listo
$(document).ready(function() {
    const modalInsumos = inicializarModalInsumos();

    // Evento para confirmar selección de insumos
    $('#btn-confirmar-insumos').click(function() {
        const insumos = modalInsumos.getInsumosSeleccionados();
        actualizarTablaInsumosSeleccionados(insumos);
        $('#modalInsumos').modal('hide');
    });

    // Función para actualizar la tabla de insumos seleccionados
    function actualizarTablaInsumosSeleccionados(insumos) {
        const tbody = $('#tabla-insumos-seleccionados');
        tbody.empty();
        
        if (Object.keys(insumos).length === 0) {
            tbody.append('<tr id="sin-insumos"><td colspan="4" class="text-center text-muted">No hay insumos seleccionados</td></tr>');
            return;
        }
        
        $('#sin-insumos').remove();
        
        Object.values(insumos).forEach(insumo => {
            const fila = `
                <tr data-id="${insumo.id}">
                    <td>${insumo.nombre}</td>
                    <td>${insumo.cantidad}</td>
                    <td><span class="badge ${insumo.stock < 10 ? 'bg-danger' : 'bg-success'}">${insumo.stock}</span></td>
                    <td>
                        <button class="btn btn-sm btn-danger btn-eliminar-insumo" data-id="${insumo.id}">
                            <i class='bx bx-trash'></i>
                        </button>
                        <input type="hidden" name="insumos[${insumo.id}]" value="${insumo.cantidad}">
                    </td>
                </tr>
            `;
            tbody.append(fila);
        });
    }

    // Evento para eliminar insumo de la tabla
    $(document).on('click', '.btn-eliminar-insumo', function() {
        const id = $(this).data('id');
        const insumos = modalInsumos.getInsumosSeleccionados();
        delete insumos[id];
        modalInsumos.setInsumosSeleccionados(insumos);
        actualizarTablaInsumosSeleccionados(insumos);
    });
});
</script>