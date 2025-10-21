<?php
require_once ROOT_PATH . '/app/views/layout/header.php';

$pasoActual = 3;
require_once ROOT_PATH . '/app/views/layout/stepper_static.php';

?>

<div class="d-flex flex-column flex-column-fluid">
    <div class="app-content flex-column-fluid">
        <div class="">
            <form method="POST" action="?c=Formularios&m=actualizarPaso3&id=<?= htmlspecialchars($idProyecto) ?>" class="form" id="formPaso3" novalidate>
                <div class="card card-custom gutter-b">
                    <div class="card-header bg-dark py-6">
                        <div class="card-title">
                            <h3 class="card-label text-white fw-bolder fs-2">3. Contexto General del Proyecto (Edición)</h3>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="seccion-perfil-egreso">
                            <h2 class="form-label fw-bolder">1.10 Perfil de egreso</h2>
                            <h4 class="fs-6 text-muted mb-8 fw-bolder">Identifique todas las facultades, carreras y programas que intervienen</h4>
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
                                    <tbody id="cuerpoTablaFacultades">
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
                                                <select name="facultad[]" class="form-select form-select-solid fs-7" onchange="cargarCarreras(this)" required data-editable>
                                                    <option value="">Seleccione</option>
                                                    <?php if (isset($facultadData['dtResultado'])): foreach ($facultadData['dtResultado'] as $facultad): ?>
                                                        <option value="<?= htmlspecialchars($facultad['CodFacultad']) ?>" <?= ($facultad['CodFacultad'] == $facultadId) ? 'selected' : '' ?>><?= htmlspecialchars($facultad['Facultad']) ?></option>
                                                    <?php endforeach; endif; ?>
                                                </select>
                                            </td>
                                            <td><select name="carrera[]" class="form-select form-select-solid fs-7" required data-editable data-selected-carrera="<?= htmlspecialchars($carreraId) ?>"><option value="">Cargando carreras...</option></select></td>
                                            <td><input type="text" name="programa[]" class="form-control form-control-solid fs-7" value="<?= htmlspecialchars($programa) ?>" required data-editable></td>
                                            <td><textarea name="aporte_perfil[]" class="form-control form-control-solid fs-7" rows="1" required data-editable><?= htmlspecialchars($aporte) ?></textarea></td>
                                            <td class="text-start"><button type="button" class="btn btn-icon btn-danger btn-sm removeRowBtn" data-editable><i class="bi bi-trash fs-5"></i></button></td>
                                        </tr>
                                        <?php endforeach; endif; ?>
                                    </tbody>
                                </table>
                            </div>
                            <button type="button" class="btn btn-primary" id="addRowBtn" data-editable><i class="bi bi-plus-lg fs-4 me-2"></i>Agregar Fila</button>
                            <div class="form-group mt-10">
                                <label class="form-label fw-bolder">Comentarios:</label>
                                <textarea name="comentarios_perfil_egreso" class="form-control form-control-solid" rows="2" data-comentario><?= htmlspecialchars($datos_enviados['comentarios_perfil_egreso'] ?? '') ?></textarea>
                            </div>
                        </div>
                        <hr class="my-10">

                        <div id="seccion-cobertura">
                             <?php $errorCobertura = !empty($errores['cobertura_general']); ?>
                            <h2 class="form-label fw-bolder <?= $errorCobertura ? 'text-danger' : '' ?>">1.11 Cobertura de ejecución del proyecto</h2>
                            <?php if ($errorCobertura): ?>
                                <div class="alert alert-danger small p-2 mt-2"><?= htmlspecialchars($errores['cobertura_general']) ?></div>
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
                                            <input class="form-check-input" type="checkbox" name="cobertura[]" value="<?= htmlspecialchars($cob['IdCobertura']) ?>" id="cobertura_<?= htmlspecialchars($cob['IdCobertura']) ?>" <?= $isChecked ? 'checked' : '' ?> data-editable>
                                            <label class="form-check-label" for="cobertura_<?= htmlspecialchars($cob['IdCobertura']) ?>"><?= htmlspecialchars($cob['cobertura']) ?></label>
                                        </div>
                                        <textarea name="comentario_cobertura[<?= htmlspecialchars($cob['IdCobertura']) ?>]" class="form-control form-control-solid mt-2 <?= $isChecked ? '' : 'd-none' ?>" rows="2" data-editable><?= htmlspecialchars($comentarioCobertura) ?></textarea>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <div class="form-group mb-8">
                                <label class="form-label fw-bolder">Comentarios:</label>
                                <textarea name="comentarios_cobertura" class="form-control form-control-solid" rows="2" data-comentario><?= htmlspecialchars($datos_enviados['comentarios_cobertura'] ?? '') ?></textarea>
                            </div>
                        </div>
                        <hr class="my-10">
                        
                        <div id="seccion-contexto-duracion">
                            <h2 class="form-label fw-bolder">1.12 Contexto de ejecución del proyecto</h2>
                            <div class="form-group mb-8">
                                <label class="form-label fw-bolder">Contexto</label>
                                <select id="contexto" name="contexto" class="form-select form-select-solid <?= !empty($errores['contexto']) ? 'is-invalid' : '' ?>" required data-editable>
                                    <option value="">Seleccione</option>
                                    <?php foreach ($contexto as $cont): ?>
                                        <option value="<?= htmlspecialchars($cont['IdContexto']) ?>" <?= (($datos_enviados['contexto'] ?? '') == $cont['IdContexto']) ? 'selected' : '' ?>><?= htmlspecialchars($cont['contexto']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group mb-8">
                                <label class="form-label fw-bolder">Comentarios:</label>
                                <textarea name="comentarios_contexto" class="form-control form-control-solid" rows="2" data-comentario><?= htmlspecialchars($datos_enviados['comentarios_contexto'] ?? '') ?></textarea>
                            </div>

                            <h2 class="form-label fw-bolder">1.13 Duración del proyecto en meses</h2>
                            <div class="form-group mb-8">
                                <label class="form-label fw-bolder">Duración del Proyecto</label>
                                <select id="duracion" name="duracion" class="form-select form-select-solid <?= !empty($errores['duracion']) ? 'is-invalid' : '' ?>" required data-editable>
                                    <option value="">Seleccione</option>
                                    <?php foreach ($duracion as $dur): ?>
                                        <option value="<?= htmlspecialchars($dur['IdDuracion']) ?>" <?= (($datos_enviados['duracion'] ?? '') == $dur['IdDuracion']) ? 'selected' : '' ?>><?= htmlspecialchars($dur['duracion']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group mb-8">
                                <label class="form-label fw-bolder">Comentarios:</label>
                                <textarea name="comentarios_duracion" class="form-control form-control-solid" rows="2" data-comentario><?= htmlspecialchars($datos_enviados['comentarios_duracion'] ?? '') ?></textarea>
                            </div>
                        </div>
                        </div>

                    <div class="card-footer d-flex justify-content-end py-6 px-9">
                        <a href="?c=Formularios&m=editarPaso2&id=<?= htmlspecialchars($idProyecto) ?>" id="btnAnterior" class="btn btn-secondary me-2">Anterior</a>
                        <button type="submit" class="btn btn-primary">Guardar y Siguiente</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<template id="facultyRowTemplate">
    <tr>
        <td>
            <select name="facultad[]" class="form-select form-select-solid fs-7" onchange="cargarCarreras(this)" required data-editable>
                <option value="">Seleccione una Facultad</option>
                <?php if (isset($facultadData) && !empty($facultadData['dtResultado'])): ?>
                    <?php foreach ($facultadData['dtResultado'] as $facultad): ?>
                        <option value="<?= htmlspecialchars($facultad['CodFacultad']) ?>"><?= htmlspecialchars($facultad['Facultad']) ?></option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </td>
        <td><select name="carrera[]" class="form-select form-select-solid fs-7" required data-editable><option value="">Seleccione Facultad</option></select></td>
        <td><input type="text" name="programa[]" class="form-control form-control-solid fs-7" placeholder="Ingrese el programa" required data-editable></td>
        <td><textarea name="aporte_perfil[]" class="form-control form-control-solid fs-7" rows="1" placeholder="Describa el aporte" required data-editable></textarea></td>
        <td class="text-start"><button type="button" class="btn btn-icon btn-danger btn-sm removeRowBtn" data-editable><i class="bi bi-trash fs-5"></i></button></td>
    </tr>
</template>

<script>
    /**
     * Función robusta que aplica permisos a un `scope` (contenedor) específico.
     */
    function aplicarPermisos(scope = document) {
        <?php if (!$permite_editar): ?>
        scope.querySelectorAll('[data-editable]').forEach(function(el) {
            if (el.tagName === 'BUTTON') {
                el.disabled = true; el.style.pointerEvents = 'none'; el.style.opacity = '0.5';
            } else if (el.type === 'checkbox' || el.type === 'radio') {
                 if (el.checked && !el.parentNode.querySelector(`input[type="hidden"][name="${el.name}"][value="${el.value}"]`)) {
                    const hidden = document.createElement('input'); hidden.type = 'hidden'; hidden.name = el.name; hidden.value = el.value; el.parentNode.insertBefore(hidden, el);
                }
                el.disabled = true;
            } else if (el.tagName === 'SELECT') {
                if (!el.parentNode.querySelector(`input[type="hidden"][name="${el.name}"]`)) {
                    const hidden = document.createElement('input'); hidden.type = 'hidden'; hidden.name = el.name; hidden.value = el.value; el.parentNode.insertBefore(hidden, el);
                }
                el.disabled = true; el.style.backgroundColor = '#f5f6fa';
            } else { // INPUT y TEXTAREA
                el.readOnly = true; el.style.backgroundColor = '#f5f6fa';
            }
        });
        <?php endif; ?>

        <?php if (!$permite_comentar): ?>
        scope.querySelectorAll('[data-comentario]').forEach(function(el) {
            el.readOnly = true; el.style.backgroundColor = '#f5f6fa';
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

    /**
     * ==================================================================
     * CORRECCIÓN 2.1: La función ahora retorna la promesa del fetch
     * ==================================================================
     */
    window.cargarCarreras = function(selectFacultad) {
        const facultadId = selectFacultad.value;
        const row = selectFacultad.closest('tr');
        const carreraSelect = row.querySelector('select[name="carrera[]"]');
        if (!facultadId) {
            carreraSelect.innerHTML = '<option value="">Seleccione Facultad</option>';
            aplicarPermisos(row);
            return Promise.resolve(); // Retorna una promesa resuelta
        }
        // Retornar la cadena de promesas para poder usar 'await'
        return fetch('?c=Institucion&m=getCarreras&facultad=' + facultadId)
            .then(response => response.json())
            .then(data => {
                carreraSelect.innerHTML = '<option value="">Seleccione Carrera</option>';
                if (data.carreras) {
                    data.carreras.forEach(carrera => {
                        const option = document.createElement('option');
                        option.value = carrera.CodCarrera;
                        option.textContent = carrera.Carrera;
                        carreraSelect.appendChild(option);
                    });
                }
                const carreraGuardada = carreraSelect.getAttribute('data-selected-carrera');
                if (carreraGuardada) {
                    carreraSelect.value = carreraGuardada;
                }
                // Aplicar permisos a la fila DESPUÉS de poblarla y seleccionarla
                aplicarPermisos(row);
            });
    }

    /**
     * ==================================================================
     * CORRECCIÓN 2.2: El listener ahora es 'async' para usar 'await'
     * ==================================================================
     */
    document.addEventListener('DOMContentLoaded', async () => {
        // --- 1. LÓGICA DE LA TABLA DINÁMICA ---
        document.getElementById("addRowBtn").addEventListener("click", function () {
            const tbody = document.getElementById("cuerpoTablaFacultades");
            const template = document.getElementById("facultyRowTemplate").content.cloneNode(true);
            tbody.appendChild(template);
            const newRow = tbody.lastElementChild;
            aplicarPermisos(newRow); // Aplica permisos a la nueva fila
        });

        document.getElementById("tablaFacultades").addEventListener("click", function (e) {
            if (e.target.closest(".removeRowBtn")) {
                e.target.closest("tr").remove();
            }
        });
        
        // Carga inicial de datos de la tabla (con 'await' para evitar race condition)
        const rows = document.querySelectorAll('#cuerpoTablaFacultades tr');
        for (const row of rows) {
            const facultadSelect = row.querySelector('select[name="facultad[]"]');
            if (facultadSelect && facultadSelect.value) {
                await cargarCarreras(facultadSelect); // Espera a que las carreras carguen
            } else {
                aplicarPermisos(row);
            }
        }

        // --- 2. LÓGICA DE COBERTURA ---
        const contenedorCobertura = document.getElementById('contenedor-cobertura');
        if (contenedorCobertura) {
            contenedorCobertura.addEventListener('change', (event) => {
                if (event.target.matches('input[type="checkbox"]')) {
                    const textarea = event.target.closest('.cobertura-item').querySelector('textarea');
                    if (textarea) {
                        textarea.classList.toggle('d-none', !event.target.checked);
                    }
                }
            });
        }
        
        // --- 3. APLICAR PERMISOS A TODAS LAS SECCIONES ---
        // Se ejecuta DESPUÉS de que la tabla se haya cargado completamente gracias a 'await'
        aplicarPermisos(document.getElementById('seccion-perfil-egreso'));
        aplicarPermisos(document.getElementById('seccion-cobertura'));
        aplicarPermisos(document.getElementById('seccion-contexto-duracion'));
    });
</script>

<?php require_once ROOT_PATH . '/app/views/layout/footer.php'; ?>