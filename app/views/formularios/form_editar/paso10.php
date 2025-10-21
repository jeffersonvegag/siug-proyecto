<?php
require_once ROOT_PATH . '/app/views/layout/header.php';

$pasoActual = 10;
// Usamos una variable para el ID para que los enlaces del stepper funcionen en modo edición
$id_for_stepper = $id_proyecto;
require_once ROOT_PATH . '/app/views/layout/stepper_static.php';



// --- Lógica de repoblación ---
// Cuando hay un error, $datos_guardados contiene los datos de $_POST
$comentarios_seguimiento = $datos_guardados['comentarios_seguimiento'] ?? '';
$comentarios_cronograma = $datos_guardados['comentarios_cronograma'] ?? '';
$comentarios_presupuesto = $datos_guardados['comentarios_presupuesto'] ?? '';
$meses_totales_proyecto = (int)($datos_guardados['duracion_proyecto_meses'] ?? 0);
?>

<div class="d-flex flex-column flex-column-fluid">
    <div class="app-content flex-column-fluid">
        <form method="post" action="?c=Formularios&m=actualizarPaso10&id=<?= htmlspecialchars($id_proyecto) ?>" class="form" id="formPaso10" novalidate>
            <div class="card card-custom gutter-b">
                <div class="card-header bg-dark py-6">
                    <div class="card-title">
                        <h3 class="card-label text-white fw-bolder fs-2">10. MATRIZ DE SEGUIMIENTO, CRONOGRAMA Y PRESUPUESTO</h3>
                    </div>
                </div>

                <div class="card-body">

                    <h2 class="fs-4 fw-bolder mb-5">5.1 Matriz de seguimiento y control</h2>
                    <?php if (!empty($errores['matriz_general'])) : ?><div class="alert alert-danger p-2"><?= htmlspecialchars($errores['matriz_general']) ?></div><?php endif; ?>
                    <div class="table-responsive">
                        <table class="table table-row-bordered gy-4 gs-4">
                            <thead>
                                <tr class="fw-bolder fs-6 text-gray-800">
                                    <th class="min-w-200px">Resultados esperados</th>
                                    <th class="min-w-100px">Cantidad</th>
                                    <th class="min-w-200px">Medio de verificación</th>
                                    <th class="min-w-150px">Fecha de resultados parcial</th>
                                    <th class="min-w-200px">Responsable del control</th>
                                </tr>
                            </thead>
                            <tbody class="fs-6">
                                <?php foreach ($resultadosEsperados as $index => $resultado) : ?>
                                    <?php
                                    // Lógica de repoblación para la matriz
                                    $cantidad = $datos_guardados['cantidad'][$index] ?? ($datos_guardados['matriz_seguimiento'][$index]['Cantidad'] ?? '');
                                    $medio_verificacion = $datos_guardados['medio_verificacion'][$index] ?? ($datos_guardados['matriz_seguimiento'][$index]['MedioVerificacion'] ?? '');
                                    $fecha_parcial = $datos_guardados['fecha_parcial'][$index] ?? ($datos_guardados['matriz_seguimiento'][$index]['FechaResultadoParcial'] ?? '');
                                    $responsable_control = $datos_guardados['responsable_control'][$index] ?? ($datos_guardados['matriz_seguimiento'][$index]['ResponsableControlVerificacion'] ?? '');
                                    ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <span class="fw-bolder text-gray-800 d-block me-2">R<?= ($index + 1) ?>.</span>
                                                <span class="text-gray-600"><?= htmlspecialchars($resultado['Resultado']) ?></span>
                                                <input type="hidden" name="resultado_esperado_texto[<?= $index ?>]" value="<?= htmlspecialchars($resultado['Resultado']) ?>">
                                            </div>
                                        </td>
                                        <td><input type="number" name="cantidad[<?= $index ?>]" value="<?= htmlspecialchars($cantidad) ?>" class="form-control form-control-solid input-cantidad <?= !empty($errores['cantidad'][$index]) ? 'is-invalid' : '' ?>" min="0" data-editable></td>
                                        <td><input type="text" name="medio_verificacion[<?= $index ?>]" value="<?= htmlspecialchars($medio_verificacion) ?>" class="form-control form-control-solid <?= !empty($errores['medio_verificacion'][$index]) ? 'is-invalid' : '' ?>" data-editable></td>
                                        <td><input type="date" name="fecha_parcial[<?= $index ?>]" value="<?= htmlspecialchars($fecha_parcial) ?>" class="form-control form-control-solid <?= !empty($errores['fecha_parcial'][$index]) ? 'is-invalid' : '' ?>" data-editable></td>
                                        <td>
                                            <select name="responsable_control[<?= $index ?>]" class="form-select form-select-solid <?= !empty($errores['responsable_control'][$index]) ? 'is-invalid' : '' ?>" data-editable>
                                                <option value="">Seleccione</option>
                                                <option value="director" <?= ($responsable_control == 'director') ? 'selected' : '' ?>>Director del proyecto</option>
                                                <option value="tutor" <?= ($responsable_control == 'tutor') ? 'selected' : '' ?>>Tutor</option>
                                            </select>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="form-group mb-8 mt-4">
                        <label class="form-label fw-bolder">Comentarios:</label>
                        <textarea name="comentarios_seguimiento" class="form-control form-control-solid" rows="2" placeholder=". . ." data-comentario><?= htmlspecialchars($comentarios_seguimiento) ?></textarea>
                    </div>

                    <h2 class="fs-4 fw-bolder mb-5 mt-10">6. CRONOGRAMA DE ACTIVIDADES</h2>
                    <?php if (!empty($errores['cronograma_general'])) : ?><div class="alert alert-danger p-2"><?= htmlspecialchars($errores['cronograma_general']) ?></div><?php endif; ?>
                    <div class="alert alert-info">
                        <strong>Duración Total del Proyecto:</strong> <?= $meses_totales_proyecto > 0 ? $meses_totales_proyecto : 'N/A' ?> Meses.
                    </div>

                    <div id="cronograma-container" class="mb-8">
                        <?php foreach ($objetivos_especificos as $objIndex => $objetivo) : ?>
                            <div class="card card-body bg-light-secondary p-6 mb-9" data-objetivo-index="<?= $objIndex ?>">
                                <h3 class="fs-5 fw-bolder text-primary mb-4">OE<?= $objIndex + 1 ?>: <?= htmlspecialchars($objetivo['texto']) ?></h3>

                                <div class="row g-3 mb-2 d-none d-md-flex">
                                    <div class="col-md-3"><label class="form-label fw-bolder">Actividad</label></div>
                                    <div class="col-md-2"><label class="form-label fw-bolder">Duración</label></div>
                                    <div class="col-md-4"><label class="form-label fw-bolder">Meses Asignados</label></div>
                                    <div class="col-md-2"><label class="form-label fw-bolder">Responsable</label></div>
                                    <div class="col-md-1"></div>
                                </div>
                                <div class="actividades-lista" data-objetivo-index="<?= $objIndex ?>">
                                    </div>
                                <button type="button" class="btn btn-sm btn-light-primary mt-3 align-self-start btn-agregar-actividad" data-editable><i class="bi bi-plus-lg fs-6"></i> Agregar Actividad</button>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <template id="template-actividad-fila">
                        <div class="row g-3 mb-4 align-items-start actividad-fila">
                            <div class="col-md-3">
                                <input type="text" class="form-control form-control-solid actividad-input" placeholder="Describa la actividad" data-editable>
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-md-2">
                                <select class="form-select form-select-solid duracion-select" data-editable>
                                    <option value="">Seleccione</option>
                                    <?php for ($m = 1; $m <= 6; $m++) : ?>
                                        <option value="<?= $m ?>"><?= $m ?> Mes(es)</option>
                                    <?php endfor; ?>
                                </select>
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-md-4">
                                <select class="form-select form-select-solid meses-multiselect" multiple="multiple" data-editable>
                                    <?php if ($meses_totales_proyecto > 0) : ?>
                                        <?php for ($i = 1; $i <= $meses_totales_proyecto; $i++) : ?>
                                            <option value="<?= $i ?>"><?= "Mes " . $i ?></option>
                                        <?php endfor; ?>
                                    <?php else : ?>
                                        <option value="" disabled>Primero defina la duración total en el Paso 3</option>
                                    <?php endif; ?>
                                </select>
                                <div class="d-block invalid-feedback"></div>
                            </div>
                            <div class="col-md-2">
                                <select class="form-select form-select-solid responsable-select" data-editable>
                                    <option value="">Seleccione</option>
                                    <option value="Director">Director</option>
                                    <option value="Tutor">Tutor</option>
                                    <option value="Estudiantes">Estudiantes</option>
                                    <option value="Todos">Todos</option>
                                </select>
                                <div class="invalid-feedback"></div>
                            </div>
                            <div class="col-md-1 text-end">
                                <button type="button" class="btn btn-icon btn-danger btn-sm btn-eliminar-actividad" title="Eliminar" data-editable><i class="bi bi-trash-fill fs-6"></i></button>
                            </div>
                        </div>
                    </template>

                    <div class="form-group mb-8">
                        <label class="form-label fw-bolder">Comentarios:</label>
                        <textarea name="comentarios_cronograma" class="form-control form-control-solid" rows="2" placeholder=". . ." data-comentario><?= htmlspecialchars($comentarios_cronograma) ?></textarea>
                    </div>

                    <h2 class="fs-4 fw-bolder mb-5 mt-10">7. PRESUPUESTO</h2>
                    <div class="table-responsive">
                        <table class="table table-row-bordered table-rounded gy-5 gs-5 w-100" id="tablaPresupuesto">
                            <thead>
                                <tr class="text-start text-gray-800 fw-bold fs-7 text-uppercase gs-0 bg-light">
                                    <th class="ps-4">Nro.</th>
                                    <th class="min-w-150px">Talento Humano UG</th>
                                    <th class="min-w-150px">Cantidad</th>
                                    <th class="min-w-200px">Especificaciones</th>
                                    <th class="min-w-100px">Horas</th>
                                    <th class="min-w-100px">Meses</th>
                                    <th class="min-w-120px">Valor por Hora</th>
                                    <th class="min-w-120px">Total</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-600 fw-semibold">
                                <?php
                                $num_filas_presupuesto = isset($datos_guardados['responsable_presupuesto']) ? count($datos_guardados['responsable_presupuesto']) : (isset($datos_guardados['presupuesto']) ? count($datos_guardados['presupuesto']) : 2);
                                for ($i = 0; $i < $num_filas_presupuesto; $i++) :
                                    $resp = $datos_guardados['responsable_presupuesto'][$i] ?? ($datos_guardados['presupuesto'][$i]['Responsable'] ?? '');
                                    $cant = $datos_guardados['cantidad_presupuesto'][$i] ?? ($datos_guardados['presupuesto'][$i]['Cantidad'] ?? '');
                                    $espe = $datos_guardados['especificaciones'][$i] ?? ($datos_guardados['presupuesto'][$i]['Especificaciones'] ?? '');
                                    $hora = $datos_guardados['horas'][$i] ?? ($datos_guardados['presupuesto'][$i]['Horas'] ?? '');
                                    $valo = $datos_guardados['valor_hora'][$i] ?? ($datos_guardados['presupuesto'][$i]['ValorHora'] ?? '');
                                ?>
                                    <tr>
                                        <td class="ps-4"><?= $i + 1 ?></td>
                                        <td>
                                            <select name="responsable_presupuesto[]" class="form-select form-select-solid" data-editable>
                                                <option value="">Seleccione</option>
                                                <option value="director" <?= ($resp == 'director') ? 'selected' : '' ?>>Director del Proyecto</option>
                                                <option value="tutor" <?= ($resp == 'tutor') ? 'selected' : '' ?>>Docente Tutor</option>
                                            </select>
                                        </td>
                                        <td><input type="text" inputmode="numeric" name="cantidad_presupuesto[]" class="form-control form-control-solid cantidad-presupuesto" value="<?= htmlspecialchars($cant) ?>" placeholder="Cantidad" data-editable></td>
                                        <td><input type="text" name="especificaciones[]" class="form-control form-control-solid" value="<?= htmlspecialchars($espe) ?>" placeholder="Detalle" data-editable></td>
                                        <td><input type="text" inputmode="numeric" name="horas[]" class="form-control form-control-solid horas" value="<?= htmlspecialchars($hora) ?>" placeholder="Horas" data-editable></td>
                                        <td><input type="text" inputmode="numeric" name="meses[]" class="form-control form-control-solid meses" value="<?= htmlspecialchars($meses_totales_proyecto) ?>" readonly></td>
                                        <td><input type="text" inputmode="numeric" name="valor_hora[]" class="form-control form-control-solid valor-hora" value="<?= htmlspecialchars($valo) ?>" placeholder="0.00" data-editable></td>
                                        <td><input type="text" class="form-control form-control-solid bg-light total-fila" readonly></td>
                                    </tr>
                                <?php endfor; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="7" class="text-end fw-bolder fs-5">Total Final:</td>
                                    <td><input type="text" id="totalFinal" class="form-control form-control-solid bg-light fw-bolder" readonly></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="form-group mb-8 mt-4">
                        <label class="form-label fw-bolder">Comentarios:</label>
                        <textarea name="comentarios_presupuesto" class="form-control form-control-solid" rows="2" placeholder=". . ." data-comentario><?= htmlspecialchars($comentarios_presupuesto) ?></textarea>
                    </div>
                </div>

                <div class="card-footer d-flex justify-content-end py-6 px-9">
                    <a href="?c=Formularios&m=editarPaso9&id=<?= htmlspecialchars($id_proyecto) ?>" class="btn btn-secondary me-2">Anterior</a>
                    <button type="submit" class="btn btn-primary" >Guardar y Siguiente</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
// =================================================================================
// SCRIPT UNIFICADO Y ROBUSTO PARA EL FORMULARIO 10
// =================================================================================

/**
 * Aplica restricciones de edición y comentarios a un contenedor específico del DOM.
 * @param {HTMLElement} scope El elemento contenedor sobre el cual aplicar los permisos.
 */
function aplicarPermisos(scope = document) {
    // --- LÓGICA PARA DESHABILITAR EDICIÓN ---
    <?php if (!$permite_editar): ?>
    scope.querySelectorAll('[data-editable]').forEach(function(el) {
        if (el.tagName === 'BUTTON') {
            el.disabled = true;
            el.style.pointerEvents = 'none';
            el.style.opacity = '0.5';
        } else if (el.tagName === 'SELECT') {
            // Si el select es múltiple (como el de los meses)
            if (el.multiple) {
                // Iteramos sobre CADA opción seleccionada
                Array.from(el.selectedOptions).forEach(option => {
                    // Creamos un campo oculto para CADA valor, preservando el array
                    const hidden = document.createElement('input');
                    hidden.type = 'hidden';
                    hidden.name = el.name; // El nombre ya incluye '[]', ej: "meses_asignados[0][0][]"
                    hidden.value = option.value;
                    el.parentNode.insertBefore(hidden, el);
                });
            } else {
                // Si es un select simple, la lógica anterior es suficiente
                const hidden = document.createElement('input');
                hidden.type = 'hidden';
                hidden.name = el.name;
                hidden.value = el.value;
                el.parentNode.insertBefore(hidden, el);
            }

            // Deshabilitamos el control visual. Select2 lo mostrará como bloqueado pero con todas las opciones visibles.
            el.disabled = true;
            el.style.backgroundColor = '#f5f6fa';
            
            // Si es un select2, actualizamos su estado visual para que se muestre como deshabilitado.
            if ($(el).data('select2')) {
               $(el).trigger('change.select2');
            }
        } else { // INPUT, TEXTAREA
            el.readOnly = true;
            el.style.backgroundColor = '#f5f6fa';
        }
    });
    <?php endif; ?>

    // --- LÓGICA PARA DESHABILITAR COMENTARIOS ---
    <?php if (!$permite_comentar): ?>
    scope.querySelectorAll('[data-comentario]').forEach(function(el) {
        el.readOnly = true;
        el.style.backgroundColor = '#f5f6fa';
        // Agrega mensaje visual "Solo lectura" si no existe ya
        if (!el.nextElementSibling || !el.nextElementSibling.classList.contains('solo-lectura-msg')) {
            const span = document.createElement('span');
            span.textContent = ' Solo lectura';
            span.style.color = 'red';
            span.style.fontWeight = 'bold';
            span.className = 'solo-lectura-msg';
            el.parentNode.insertBefore(span, el.nextSibling);
        }
    });
    <?php endif; ?>
}


document.addEventListener('DOMContentLoaded', () => {
    const erroresCronograma = <?= json_encode($errores ?? []) ?>;

    function inicializarFila(fila, objIndex, rowIndex, datos = {}) {
        const actividadInput = fila.querySelector('.actividad-input');
        const duracionSelect = fila.querySelector('.duracion-select');
        const mesesMultiSelect = fila.querySelector('.meses-multiselect');
        const responsableSelect = fila.querySelector('.responsable-select');

        // Asignar nombres a los campos
        actividadInput.name = `actividad[${objIndex}][${rowIndex}]`;
        duracionSelect.name = `duracion[${objIndex}][${rowIndex}]`;
        mesesMultiSelect.name = `meses_asignados[${objIndex}][${rowIndex}][]`;
        responsableSelect.name = `responsable[${objIndex}][${rowIndex}]`;

        // Poblar con datos existentes
        actividadInput.value = datos.Actividad || '';
        duracionSelect.value = datos.Duracion || '';
        responsableSelect.value = datos.Responsable || '';

        const mesesArray = (datos.MesesAsignados && typeof datos.MesesAsignados === 'string') 
            ? datos.MesesAsignados.split(',').filter(Boolean) 
            : (datos.meses_asignados || []);
        
        const limiteSeleccion = parseInt(duracionSelect.value) || 0;

        // Inicializar Select2
        const select2Instance = $(mesesMultiSelect).select2({
            placeholder: "Escoja los meses",
            language: "es",
            allowClear: true,
            maximumSelectionLength: limiteSeleccion
        }).val(mesesArray).trigger('change');

        // Lógica para actualizar el límite del multiselect
        $(duracionSelect).on('change', function() {
            const nuevoLimite = parseInt(this.value) || 0;
            const valoresActuales = select2Instance.val() || [];
            const valoresFiltrados = valoresActuales.slice(0, nuevoLimite);
            select2Instance.select2('destroy');
            select2Instance.select2({
                placeholder: "Escoja los meses",
                maximumSelectionLength: nuevoLimite,
                language: "es",
                allowClear: true
            }).val(valoresFiltrados).trigger('change');
        });

        // Mostrar errores de validación si existen
        // (La lógica existente para errores es correcta)
    }

    // --- MANEJO DEL CRONOGRAMA ---
    $('#cronograma-container').on('click', '.btn-agregar-actividad', function() {
        const seccion = this.closest('[data-objetivo-index]');
        const objIndex = seccion.dataset.objetivoIndex;
        const listaActividades = seccion.querySelector('.actividades-lista');
        const rowIndex = listaActividades.children.length;
        const template = document.getElementById('template-actividad-fila');
        const nuevaFila = template.content.cloneNode(true).firstElementChild;
        listaActividades.appendChild(nuevaFila);
        
        inicializarFila(nuevaFila, objIndex, rowIndex);
        // ¡IMPORTANTE! Aplicar permisos a la nueva fila agregada
        aplicarPermisos(nuevaFila);
    });

    $('#cronograma-container').on('click', '.btn-eliminar-actividad', function() {
        this.closest('.actividad-fila').remove();
    });

    // --- POBLAR CRONOGRAMA CON DATOS INICIALES ---
    <?php
    $cronograma_para_js = [];
    if (!empty($datos_guardados['actividad'])) { 
        foreach ($datos_guardados['actividad'] as $objIndex => $actividades) {
            if (!is_array($actividades)) continue;
            foreach ($actividades as $i => $texto) {
                $cronograma_para_js[] = [
                    'ObjetivoRelacionado' => $objIndex + 1,
                    'Actividad' => $texto,
                    'Duracion' => $datos_guardados['duracion'][$objIndex][$i] ?? 0,
                    'MesesAsignados' => implode(',', $datos_guardados['meses_asignados'][$objIndex][$i] ?? []),
                    'Responsable' => $datos_guardados['responsable'][$objIndex][$i] ?? ''
                ];
            }
        }
    } elseif (!empty($datos_guardados['cronograma'])) {
        $cronograma_para_js = $datos_guardados['cronograma'];
    }
    ?>

    const cronogramaData = <?= json_encode($cronograma_para_js) ?>;
    const filasPorObjetivo = {};

    cronogramaData.forEach(actividad => {
        const objIndex = actividad.ObjetivoRelacionado - 1;
        const listaActividades = document.querySelector(`.actividades-lista[data-objetivo-index='${objIndex}']`);
        if (listaActividades) {
            if (!filasPorObjetivo[objIndex]) {
                filasPorObjetivo[objIndex] = 0;
            }
            const rowIndex = filasPorObjetivo[objIndex];
            const template = document.getElementById('template-actividad-fila');
            const nuevaFila = template.content.cloneNode(true).firstElementChild;
            listaActividades.appendChild(nuevaFila);
            inicializarFila(nuevaFila, objIndex, rowIndex, actividad);
            filasPorObjetivo[objIndex]++;
        }
    });

    // --- CÁLCULO DEL PRESUPUESTO ---
    const tablaPresupuesto = document.getElementById('tablaPresupuesto');
    if (tablaPresupuesto) {
        const currencyFormatter = new Intl.NumberFormat('en-US', { style: 'currency', currency: 'USD' });
        
        const calcularTotalesPresupuesto = () => {
            let totalFinal = 0;
            tablaPresupuesto.querySelectorAll('tbody tr').forEach(fila => {
                const cantidad = parseFloat(fila.querySelector('.cantidad-presupuesto').value) || 0;
                const horas = parseFloat(fila.querySelector('.horas').value) || 0;
                const meses = parseFloat(fila.querySelector('.meses').value) || 0;
                const valorHora = parseFloat(fila.querySelector('.valor-hora').value) || 0;
                const totalFilaInput = fila.querySelector('.total-fila');
                
                // Asumiendo que las horas son por semana, se multiplica por 4 para obtener horas/mes.
                const totalFila = cantidad * horas * meses * 4 * valorHora;
                
                totalFilaInput.value = currencyFormatter.format(totalFila);
                totalFinal += totalFila;
            });
            document.getElementById('totalFinal').value = currencyFormatter.format(totalFinal);
        };

        tablaPresupuesto.addEventListener('input', e => {
            if (e.target.matches('.cantidad-presupuesto, .horas, .valor-hora')) {
                calcularTotalesPresupuesto();
            }
        });
        
        // Cálculo inicial al cargar
        calcularTotalesPresupuesto();
    }
    
    // --- INICIALIZACIÓN FINAL ---
    // ¡IMPORTANTE! Aplicar permisos a todo el formulario al cargar la página.
    aplicarPermisos(document.getElementById('formPaso10'));
});
</script>

<?php require_once ROOT_PATH . '/app/views/layout/footer.php'; ?>