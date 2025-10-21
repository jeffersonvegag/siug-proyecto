<?php require_once ROOT_PATH . '/app/views/layout/header.php';

$pasoActual = 10;
require_once ROOT_PATH . '/app/views/layout/stepper_static.php';
$meses_totales_proyecto = (int)($datos_enviados['duracion_proyecto_meses'] ?? 0);

// --- Lógica de repoblación ---
$comentarios_seguimiento = $datos_enviados['comentarios_seguimiento'] ?? '';
$comentarios_cronograma = $datos_enviados['comentarios_cronograma'] ?? '';
$comentarios_presupuesto = $datos_enviados['comentarios_presupuesto'] ?? '';
?>

<div class="d-flex flex-column flex-column-fluid">
    <div class="app-content flex-column-fluid">
        <form method="post" action="?c=Formularios&m=paso10" class="form" id="formPaso10" novalidate>
            <div class="card card-custom gutter-b">
                <div class="card-header bg-dark py-6">
                    <div class="card-title">
                        <h3 class="card-label text-white fw-bolder fs-2">10. MATRIZ DE SEGUIMIENTO Y CRONOGRAMA</h3>
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
                                    $cantidad = $datos_enviados['cantidad'][$index] ?? ($datos_enviados['matriz_seguimiento'][$index]['Cantidad'] ?? '');
                                    $medio_verificacion = $datos_enviados['medio_verificacion'][$index] ?? ($datos_enviados['matriz_seguimiento'][$index]['MedioVerificacion'] ?? '');
                                    $fecha_parcial = $datos_enviados['fecha_parcial'][$index] ?? ($datos_enviados['matriz_seguimiento'][$index]['FechaResultadoParcial'] ?? '');
                                    $responsable_control = $datos_enviados['responsable_control'][$index] ?? ($datos_enviados['matriz_seguimiento'][$index]['ResponsableControlVerificacion'] ?? '');
                                    ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <span class="fw-bolder text-gray-800 d-block me-2">R<?= ($index + 1) ?>.</span>
                                                <span class="text-gray-600"><?= htmlspecialchars($resultado['Resultado']) ?></span>
                                                <input type="hidden" name="resultado_esperado_texto[<?= $index ?>]" value="<?= htmlspecialchars($resultado['Resultado']) ?>">
                                            </div>
                                        </td>
                                        <td><input type="number" name="cantidad[<?= $index ?>]" value="<?= htmlspecialchars($cantidad) ?>" class="form-control form-control-solid input-cantidad <?= !empty($errores['cantidad'][$index]) ? 'is-invalid' : '' ?>" min="0"></td>
                                        <td><input type="text" name="medio_verificacion[<?= $index ?>]" value="<?= htmlspecialchars($medio_verificacion) ?>" class="form-control form-control-solid <?= !empty($errores['medio_verificacion'][$index]) ? 'is-invalid' : '' ?>"></td>
                                        <td><input type="date" name="fecha_parcial[<?= $index ?>]" value="<?= htmlspecialchars($fecha_parcial) ?>" class="form-control form-control-solid <?= !empty($errores['fecha_parcial'][$index]) ? 'is-invalid' : '' ?>"></td>
                                        <td>
                                            <select name="responsable_control[<?= $index ?>]" class="form-select form-select-solid <?= !empty($errores['responsable_control'][$index]) ? 'is-invalid' : '' ?>">
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
                                <button type="button" class="btn btn-sm btn-light-primary mt-3 align-self-start btn-agregar-actividad"><i class="bi bi-plus-lg fs-6"></i> Agregar Actividad</button>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <template id="template-actividad-fila">
                        <div class="row g-3 mb-4 align-items-center actividad-fila">
                            <div class="col-md-3"><input type="text" class="form-control form-control-solid actividad-input" placeholder="Describa la actividad"></div>
                            <div class="col-md-2">
                                <select class="form-select form-select-solid duracion-select">
                                    <option value="">Seleccione</option>
                                    <?php for ($m = 1; $m <= 6; $m++) : ?>
                                        <option value="<?= $m ?>"><?= $m ?> Mes(es)</option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <select class="form-select form-select-solid meses-multiselect" multiple="multiple">
                                    <?php if ($meses_totales_proyecto > 0) : ?>
                                        <?php for ($i = 1; $i <= $meses_totales_proyecto; $i++) : ?>
                                            <option value="<?= $i ?>"><?= "Mes " . $i ?></option>
                                        <?php endfor; ?>
                                    <?php else : ?>
                                        <option value="" disabled>Primero defina la duración total en el Paso 3</option>
                                    <?php endif; ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select class="form-select form-select-solid responsable-select">
                                    <option value="">Seleccione</option>
                                    <option value="Director">Director</option>
                                    <option value="Tutor">Tutor</option>
                                    <option value="Estudiantes">Estudiantes</option>
                                    <option value="Todos">Todos</option>
                                </select>
                            </div>
                            <div class="col-md-1 text-end">
                                <button type="button" class="btn btn-icon btn-danger btn-sm btn-eliminar-actividad" title="Eliminar"><i class="bi bi-trash-fill fs-6"></i></button>
                            </div>
                        </div>
                    </template>


                    <h2 class="fs-4 fw-bolder mb-5 mt-10">7. PRESUPUESTO</h2>
                    <p class="text-gray-600 mb-5">
                        El presente proyecto no compromete la transferencia de fondos, por lo tanto, no compromete partidas presupuestarias.
                    </p>
                    <p class="text-gray-600 mb-8">
                        Se ha efectuado la estimación del tiempo invertido tanto del Director del Proyecto de Vinculación con la Sociedad, como de los Tutores de Prácticas de Servicio Comunitario, y se ha valorado a una tarifa de $10/hora para efectos de estimación de costos.
                    </p>
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
                                $meses_del_proyecto = $datos_enviados['duracion_proyecto_meses'] ?? '';
                                for ($i = 0; $i < 2; $i++) :
                                    $resp = $datos_enviados['responsable_presupuesto'][$i] ?? ($datos_enviados['presupuesto'][$i]['Responsable'] ?? '');
                                    $cant = $datos_enviados['cantidad_presupuesto'][$i] ?? ($datos_enviados['presupuesto'][$i]['Cantidad'] ?? '');
                                    $espe = $datos_enviados['especificaciones'][$i] ?? ($datos_enviados['presupuesto'][$i]['Especificaciones'] ?? '');
                                    $hora = $datos_enviados['horas'][$i] ?? ($datos_enviados['presupuesto'][$i]['Horas'] ?? '');
                                    $valo = $datos_enviados['valor_hora'][$i] ?? ($datos_enviados['presupuesto'][$i]['ValorHora'] ?? '');
                                ?>
                                    <tr>
                                        <td class="ps-4"><?= $i + 1 ?></td>
                                        <td>
                                            <select name="responsable_presupuesto[]" class="form-select form-select-solid">
                                                <option value="">Seleccione</option>
                                                <option value="director" <?= ($resp == 'director') ? 'selected' : '' ?>>Director del Proyecto</option>
                                                <option value="tutor" <?= ($resp == 'tutor') ? 'selected' : '' ?>>Docente Tutor</option>
                                            </select>
                                        </td>
                                        <td>
                                            <input type="text" inputmode="numeric" name="cantidad_presupuesto[]" class="form-control form-control-solid cantidad-presupuesto" value="<?= htmlspecialchars($cant) ?>" placeholder="Cantidad" maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                                        </td>
                                        <td><input type="text" name="especificaciones[]" class="form-control form-control-solid" value="<?= htmlspecialchars($espe) ?>" placeholder="Detalle la especificación"></td>
                                        <td><input type="text" inputmode="numeric" name="horas[]" class="form-control form-control-solid horas" value="<?= htmlspecialchars($hora) ?>" placeholder="Horas" maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '')"></td>
                                        <td>
                                            <input type="text" inputmode="numeric" name="meses[]" class="form-control form-control-solid meses" value="<?= htmlspecialchars($meses_del_proyecto) ?>" placeholder="Meses" readonly>
                                        </td>
                                        <td><input type="text" inputmode="numeric" name="valor_hora[]" class="form-control form-control-solid valor-hora" value="<?= htmlspecialchars($valo) ?>" placeholder="0.00" maxlength="10" oninput="this.value = this.value.replace(/[^0-9.]/g, '')"></td>
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

                    </div>
                <div class="card-footer d-flex justify-content-end py-6 px-9">
                    <a href="?c=Formularios&m=paso9" class="btn btn-secondary me-2">Anterior</a>
                    <button type="submit" class="btn btn-primary">Siguiente</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // --- INICIO LÓGICA CRONOGRAMA (CORREGIDA) ---
        function inicializarFila(fila, objIndex, rowIndex, datos = {}) {
            const actividadInput = fila.querySelector('.actividad-input');
            const duracionSelect = fila.querySelector('.duracion-select');
            const mesesMultiSelect = fila.querySelector('.meses-multiselect');
            const responsableSelect = fila.querySelector('.responsable-select');

            actividadInput.name = `actividad[${objIndex}][${rowIndex}]`;
            duracionSelect.name = `duracion[${objIndex}][${rowIndex}]`;
            mesesMultiSelect.name = `meses_asignados[${objIndex}][${rowIndex}][]`;
            responsableSelect.name = `responsable[${objIndex}][${rowIndex}]`;

            actividadInput.value = datos.Actividad || '';
            duracionSelect.value = datos.Duracion || '';
            responsableSelect.value = datos.Responsable || '';

            const mesesArray = (datos.MesesAsignados && typeof datos.MesesAsignados === 'string') 
                ? datos.MesesAsignados.split(',').filter(Boolean) 
                : (datos.meses_asignados || []);

            const limiteSeleccion = parseInt(duracionSelect.value) || 0;

            const select2Instance = $(mesesMultiSelect).select2({
                placeholder: "Escoja los meses",
                language: "es",
                allowClear: true,
                maximumSelectionLength: limiteSeleccion
            }).val(mesesArray).trigger('change');

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
        }

        $('#cronograma-container').on('click', '.btn-agregar-actividad', function() {
            const seccion = this.closest('[data-objetivo-index]');
            const objIndex = seccion.dataset.objetivoIndex;
            const listaActividades = seccion.querySelector('.actividades-lista');
            const rowIndex = listaActividades.children.length;
            const template = document.getElementById('template-actividad-fila');
            const nuevaFila = template.content.cloneNode(true).firstElementChild;

            listaActividades.appendChild(nuevaFila);
            inicializarFila(nuevaFila, objIndex, rowIndex);
        });

        $('#cronograma-container').on('click', '.btn-eliminar-actividad', function() {
            this.closest('.actividad-fila').remove();
        });

        <?php
        $cronograma_para_js = [];
        if (!empty($datos_enviados['actividad'])) {
            foreach ($datos_enviados['actividad'] as $objIndex => $actividades) {
                foreach ($actividades as $i => $texto) {
                    $cronograma_para_js[] = [
                        'ObjetivoRelacionado' => $objIndex + 1,
                        'Actividad' => $texto,
                        'Duracion' => $datos_enviados['duracion'][$objIndex][$i] ?? 0,
                        'MesesAsignados' => implode(',', $datos_enviados['meses_asignados'][$objIndex][$i] ?? []),
                        'Responsable' => $datos_enviados['responsable'][$objIndex][$i] ?? ''
                    ];
                }
            }
        } elseif (!empty($datos_enviados['cronograma'])) {
            $cronograma_para_js = $datos_enviados['cronograma'];
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
        // --- FIN LÓGICA CRONOGRAMA ---


        // --- INICIO LÓGICA PRESUPUESTO (REINTEGRADA) ---
        const tablaPresupuesto = document.getElementById('tablaPresupuesto');
        if (tablaPresupuesto) {
            const currencyFormatter = new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: 'USD',
                minimumFractionDigits: 2
            });

            const calcularTotalesPresupuesto = () => {
                let totalFinal = 0;
                const filas = tablaPresupuesto.querySelectorAll('tbody tr');
                
                filas.forEach(fila => {
                    const cantidad = parseFloat(fila.querySelector('.cantidad-presupuesto').value) || 0;
                    const horas = parseFloat(fila.querySelector('.horas').value) || 0;
                    const meses = parseFloat(fila.querySelector('.meses').value) || 0;
                    const valorHora = parseFloat(fila.querySelector('.valor-hora').value.replace(',', '.')) || 0;
                    const totalFilaInput = fila.querySelector('.total-fila');
                    
                    const totalFila = cantidad * horas * meses * 4 * valorHora;
                    
                    totalFilaInput.value = currencyFormatter.format(totalFila);
                    totalFinal += totalFila;
                });
                
                const totalFinalInput = document.getElementById('totalFinal');
                if (totalFinalInput) {
                    totalFinalInput.value = currencyFormatter.format(totalFinal);
                }
            };

            tablaPresupuesto.addEventListener('input', (e) => {
                if (e.target.matches('.cantidad-presupuesto, .horas, .meses, .valor-hora')) {
                    calcularTotalesPresupuesto();
                }
            });
            
            calcularTotalesPresupuesto();
        }
        // --- FIN LÓGICA PRESUPUESTO ---
    });
</script>

<?php require_once ROOT_PATH . '/app/views/layout/footer.php'; ?>