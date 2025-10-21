<?php
require_once ROOT_PATH . '/app/views/layout/header.php';

$pasoActual = 3;
require_once ROOT_PATH . '/app/views/layout/stepper_static.php';
?>

<div class="d-flex flex-column flex-column-fluid">
    <div class="app-content flex-column-fluid">
        <div class="">
            <form method="POST" action="?c=Formularios&m=paso3" class="form" id="formPaso3" novalidate>
                <div class="card card-custom gutter-b">
                    <div class="card-header bg-dark  py-6">
                        <div class="card-title">
                            <h3 class="card-label text-white fw-bolder fs-2">3. Contexto General del Proyecto</h3>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- 1.10 Perfil de egreso -->
                        <h2 class="form-label fw-bolder">1.10 Perfil de egreso</h2>
                        <h4 class="fs-6 text-muted mb-8 fw-bolder">Identifique todas las facultades, carreras y programas que intervienen en el proyecto</h4>

                        <?php if (!empty($errores['perfil_egreso_general'])): ?>
                            <div class="alert alert-danger small p-2 mt-2"><?= htmlspecialchars($errores['perfil_egreso_general']) ?></div>
                        <?php endif; ?>

                        <div class="table-responsive mb-1">
                            <table class="table table-row-bordered table-rounded gy-5 gs-5 w-100" id="tablaFacultades">
                                <thead>
                                    <tr class="text-start text-gray-800 fw-bold fs-7 text-uppercase gs-0 bg-gray-100">
                                        <th class="w-25">Facultad</th>
                                        <th class="w-25">Carrera</th>
                                        <th class="w-15">Programa</th>
                                        <th class="w-35">Aporte al perfil de egreso</th>
                                        <th class="w-50px text-start">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="text-gray-600 fw-semibold" id="cuerpoTablaFacultades">
                                    <?php
                                    $filasGuardadas = $datos_enviados['facultad'] ?? [];
                                    if (!empty($filasGuardadas)):
                                        foreach ($filasGuardadas as $key => $facultadId):
                                            $carreraId = $datos_enviados['carrera'][$key] ?? '';
                                            $programa = $datos_enviados['programa'][$key] ?? '';
                                            $aporte = $datos_enviados['aporte_perfil'][$key] ?? '';
                                    ?>
                                            <tr>
                                                <td>
                                                    <select name="facultad[]" class="form-select form-select-solid fs-7 <?= !empty($errores['facultad'][$key]) ? 'is-invalid' : '' ?>" onchange="cargarCarreras(this)" required>
                                                        <option value="">Seleccione</option>
                                                        <?php if (isset($facultadData['dtResultado'])): foreach ($facultadData['dtResultado'] as $facultad): ?>
                                                                <option value="<?= htmlspecialchars($facultad['CodFacultad']) ?>" <?= ($facultad['CodFacultad'] == $facultadId) ? 'selected' : '' ?>><?= htmlspecialchars($facultad['Facultad']) ?></option>
                                                        <?php endforeach;
                                                        endif; ?>
                                                    </select>
                                                </td>
                                                <td>
                                                    <select name="carrera[]" class="form-select form-select-solid fs-7 <?= !empty($errores['carrera'][$key]) ? 'is-invalid' : '' ?>" required data-selected-carrera="<?= htmlspecialchars($carreraId) ?>">
                                                        <option value="">Seleccione Facultad</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="text" name="programa[]" class="form-control form-control-solid fs-7 <?= !empty($errores['programa'][$key]) ? 'is-invalid' : '' ?>" value="<?= htmlspecialchars($programa) ?>" required>
                                                </td>
                                                <td>
                                                    <textarea name="aporte_perfil[]" class="form-control form-control-solid fs-7 <?= !empty($errores['aporte_perfil'][$key]) ? 'is-invalid' : '' ?>" rows="1" required><?= htmlspecialchars($aporte) ?></textarea>
                                                </td>
                                                <td class="text-start">
                                                    <button type="button" class="btn btn-icon btn-danger btn-sm removeRowBtn"><i class="bi bi-trash fs-5"></i></button>
                                                </td>
                                            </tr>
                                    <?php
                                        endforeach;
                                    endif;
                                    ?>
                                </tbody>
                            </table>
                        </div>
                        <button type="button" class="btn btn-primary" id="addRowBtn"><i class="bi bi-plus-lg fs-4 me-2"></i>Agregar Fila</button>

                        <template id="facultyRowTemplate">
                            <tr>
                                <td>
                                    <select name="facultad[]" class="form-select form-select-solid fs-7" onchange="cargarCarreras(this)" required>
                                        <option value="">Seleccione una Facultad</option>
                                        <?php if (isset($facultadData) && !empty($facultadData['dtResultado'])): ?>
                                            <?php foreach ($facultadData['dtResultado'] as $facultad): ?>
                                                <option value="<?= htmlspecialchars($facultad['CodFacultad']) ?>"><?= htmlspecialchars($facultad['Facultad']) ?></option>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <option value="">No hay facultades</option>
                                        <?php endif; ?>
                                    </select>
                                </td>
                                <td><select name="carrera[]" class="form-select form-select-solid fs-7" required>
                                        <option value="">Seleccione una Carrera</option>
                                    </select></td>
                                <td><input type="text" name="programa[]" class="form-control form-control-solid fs-7" placeholder="Ingrese el programa" required></td>
                                <td><textarea name="aporte_perfil[]" class="form-control form-control-solid fs-7" rows="1" placeholder="Describa el aporte" required></textarea></td>
                                <td class="text-start"><button type="button" class="btn btn-icon btn-danger btn-sm removeRowBtn"><i class="bi bi-trash fs-5"></i></button></td>
                            </tr>
                        </template>

                        <?php $errorCobertura = !empty($errores['cobertura_general']); ?>
                        <h2 class="form-label fw-bolder mt-10 <?= $errorCobertura ? 'text-danger' : '' ?>">1.11 Cobertura de ejecución del proyecto</h2>

                        <?php if ($errorCobertura): ?>
                            <div class="alert alert-danger small p-2 mt-2 mb-4">
                                <?= htmlspecialchars($errores['cobertura_general']) ?>
                            </div>
                        <?php endif; ?>

                        <div class="mb-8" id="contenedor-cobertura">
                            <?php
                            $coberturasSeleccionadas = $datos_enviados['cobertura'] ?? [];
                            foreach ($cobertura as $cob):
                                $isChecked = in_array($cob['IdCobertura'], $coberturasSeleccionadas);
                                $comentarioCobertura = $datos_enviados['comentario_cobertura'][$cob['IdCobertura']] ?? '';
                            ?>
                                <div class="cobertura-item mb-3">
                                    <div class="form-check form-check-custom form-check-solid">
                                        <input class="form-check-input" type="checkbox" name="cobertura[]" value="<?= htmlspecialchars($cob['IdCobertura']) ?>" id="cobertura_<?= htmlspecialchars($cob['IdCobertura']) ?>" <?= $isChecked ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="cobertura_<?= htmlspecialchars($cob['IdCobertura']) ?>"><?= htmlspecialchars($cob['cobertura']) ?></label>
                                    </div>
                                    <textarea name="comentario_cobertura[<?= htmlspecialchars($cob['IdCobertura']) ?>]" class="form-control form-control-solid mt-2 <?= $isChecked ? '' : 'd-none' ?>" rows="2" placeholder="Ingrese comentario..."><?= htmlspecialchars($comentarioCobertura) ?></textarea>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- 1.12 Contexto -->
                        <h2 class="form-label fw-bolder">1.12 Contexto de ejecución del proyecto</h2>
                        <div class="form-group mb-8">
                            <?php $fieldName = 'contexto';
                            $valorPrevio = $datos_enviados[$fieldName] ?? ''; ?>
                            <label for="contexto" class="form-label fw-bolder">Contexto</label>
                            <select id="contexto" name="contexto" class="form-select form-select-solid <?= !empty($errores[$fieldName]) ? 'is-invalid' : '' ?>" required>
                                <option value="">Seleccione</option>
                                <?php foreach ($contexto as $cont): ?>
                                    <option value="<?= htmlspecialchars($cont['IdContexto']) ?>" <?= ($valorPrevio == $cont['IdContexto']) ? 'selected' : '' ?>><?= htmlspecialchars($cont['contexto']) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (!empty($errores[$fieldName])): ?><div class="text-danger small mt-1"><?= htmlspecialchars($errores[$fieldName]) ?></div><?php endif; ?>
                        </div>
                        <!-- 1.13 Duración -->
                        <h2 class="form-label fw-bolder">1.13 Duración del proyecto en meses</h2>
                        <div class="form-group mb-8">
                            <?php $fieldName = 'duracion';
                            $valorPrevio = $datos_enviados[$fieldName] ?? ''; ?>
                            <label for="duracion" class="form-label fw-bolder">Duración del Proyecto</label>
                            <select id="duracion" name="duracion" class="form-select form-select-solid <?= !empty($errores[$fieldName]) ? 'is-invalid' : '' ?>" required>
                                <option value="">Seleccione</option>
                                <?php foreach ($duracion as $dur): ?>
                                    <option value="<?= htmlspecialchars($dur['IdDuracion']) ?>" <?= ($valorPrevio == $dur['IdDuracion']) ? 'selected' : '' ?>><?= htmlspecialchars($dur['duracion']) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (!empty($errores[$fieldName])): ?><div class="text-danger small mt-1"><?= htmlspecialchars($errores[$fieldName]) ?></div><?php endif; ?>
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-end py-6 px-9">
                        <a href="?c=Formularios&m=paso2" id="btnAnterior" class="btn btn-secondary me-2">Anterior</a>
                        <button type="submit" class="btn btn-primary">Siguiente</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function cargarCarreras(selectFacultad) {
        const facultadId = selectFacultad.value;
        const carreraSelect = selectFacultad.closest('tr').querySelector('select[name="carrera[]"]');
        if (!facultadId) {
            carreraSelect.innerHTML = '<option value="">Seleccione una Carrera</option>';
            return;
        }
        fetch('?c=Institucion&m=getCarreras&facultad=' + facultadId)
            .then(response => response.json())
            .then(data => {
                carreraSelect.innerHTML = '<option value="">Seleccione una Carrera</option>';
                if (data.carreras) {
                    data.carreras.forEach(carrera => {
                        const option = document.createElement('option');
                        option.value = carrera.CodCarrera;
                        option.textContent = carrera.Carrera;
                        carreraSelect.appendChild(option);
                    });
                }
                const carreraGuardada = carreraSelect.getAttribute('data-selected-carrera');
                if (carreraGuardada && carreraSelect.querySelector('option[value="' + carreraGuardada + '"]')) {
                    carreraSelect.value = carreraGuardada;
                }
            });
    }

    document.addEventListener('DOMContentLoaded', () => {
        // Lógica para añadir filas
        document.getElementById("addRowBtn").addEventListener("click", function() {
            const tbody = document.getElementById("cuerpoTablaFacultades");
            const template = document.getElementById("facultyRowTemplate").content.cloneNode(true);
            tbody.appendChild(template);
        });

        // Lógica para eliminar filas
        document.getElementById("tablaFacultades").addEventListener("click", function(e) {
            if (e.target.closest(".removeRowBtn")) {
                e.target.closest("tr").remove();
            }
        });

        // Al cargar la página, recorre las filas ya existentes y carga sus carreras
        document.querySelectorAll('#cuerpoTablaFacultades tr').forEach(row => {
            const facultadSelect = row.querySelector('select[name="facultad[]"]');
            if (facultadSelect && facultadSelect.value) {
                cargarCarreras(facultadSelect);
            }
        });

        // Lógica para mostrar/ocultar comentarios por cobertura
        const contenedorCobertura = document.getElementById('contenedor-cobertura');
        if (contenedorCobertura) {
            contenedorCobertura.addEventListener('change', (event) => {
                if (event.target.matches('input[type="checkbox"][name="cobertura[]"]')) {
                    const checkbox = event.target;
                    const textarea = checkbox.closest('.cobertura-item').querySelector('textarea');
                    if (textarea) {
                        textarea.classList.toggle('d-none', !checkbox.checked);
                        if (!checkbox.checked) {
                            textarea.value = '';
                        }
                    }
                }
            });
        }
    });
</script>

<?php require_once ROOT_PATH . '/app/views/layout/footer.php'; ?>