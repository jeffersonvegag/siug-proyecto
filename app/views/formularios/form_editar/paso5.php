<?php
require_once ROOT_PATH . '/app/views/layout/header.php';

$pasoActual = 5;
require_once ROOT_PATH . '/app/views/layout/stepper_static.php';

?>

<div class="d-flex flex-column flex-column-fluid">
    <div class="app-content flex-column-fluid">
        <form method="POST" action="?c=Formularios&m=actualizarPaso5&id=<?= htmlspecialchars($idProyecto) ?>" class="form" id="formPaso5">
            <div class="card card-custom gutter-b">
                <div class="card-header bg-dark py-6 ">
                    <div class="card-title">
                        <h3 class="card-label text-white fw-bolder fs-2">5. EQUIPO DEL PROYECTO</h3>
                    </div>
                </div>

                <div class="card-body">
                    <h2 class="fs-4 fw-bolder mb-5 ">3.1 Grupo de Docentes</h2>
                    
                    <div id="seccion_director">
                        <h4 class="fs-6 fw-bolder mt-4">3.1.1 Director del proyecto:</h4>
                        <?php if ($autoFilled): ?>
                            <div class="row">
                                <div class="col-md-4 mb-8"><label class="form-label fw-bolder">Nombre del Director:</label><input type="text" name="nombre_director" class="form-control form-control-solid" value="<?= htmlspecialchars($datos_enviados['nombre_director'] ?? '') ?>" readonly></div>
                                <div class="col-md-4 mb-8"><label class="form-label fw-bolder">Cédula:</label><input type="text" name="cedula_director" class="form-control form-control-solid" value="<?= htmlspecialchars($datos_enviados['cedula_director'] ?? '') ?>" readonly></div>
                                <div class="col-md-4 mb-8">
                                    <label class="form-label fw-bolder">Acreditado:</label>
                                    <select name="acreditado_director" class="form-select form-select-solid" data-editable required>
                                        <option value="">SELECCIONE</option>
                                        <option value="si" <?= ($datos_enviados['acreditado_director'] ?? '') == 'si' ? 'selected' : '' ?>>Sí</option>
                                        <option value="no" <?= ($datos_enviados['acreditado_director'] ?? '') == 'no' ? 'selected' : '' ?>>No</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-8"><label class="form-label fw-bolder">Categoría:</label><input type="text" name="categoria_director" class="form-control form-control-solid" value="<?= htmlspecialchars($datos_enviados['categoria_director'] ?? '') ?>" data-editable required></div>
                                <div class="col-md-4 mb-8"><label class="form-label fw-bolder">Dedicación:</label><input type="text" name="dedicacion_director" class="form-control form-control-solid" value="<?= htmlspecialchars($datos_enviados['dedicacion_director'] ?? '') ?>" data-editable required></div>
                                <div class="col-md-4 mb-8"><label class="form-label fw-bolder">Correo:</label><input type="email" name="correo_director" class="form-control form-control-solid" value="<?= htmlspecialchars($datos_enviados['correo_director'] ?? '') ?>" readonly></div>
                            </div>
                             <div class="row">
                                <div class="col-md-4 mb-8"><label class="form-label fw-bolder">Teléfono:</label><input type="tel" name="telefono_director" class="form-control form-control-solid" value="<?= htmlspecialchars($datos_enviados['telefono_director'] ?? '') ?>" readonly></div>
                                <div class="col-md-4 mb-8"><label class="form-label fw-bolder">Facultad:</label><input type="text" class="form-control form-control-solid" value="<?= htmlspecialchars($datos_director_precargados['facultad_nombre'] ?? '') ?>" readonly></div>
                                <div class="col-md-4 mb-8"><label class="form-label fw-bolder">Carrera:</label><input type="text" class="form-control form-control-solid" value="<?= htmlspecialchars($datos_director_precargados['carrera_nombre'] ?? '') ?>" readonly></div>
                            </div>
                            <div class="form-group mb-8">
                                <label class="form-label fw-bolder">Comentarios:</label>
                                <textarea name="comentarios_director" class="form-control form-control-solid" rows="2" data-comentario><?= htmlspecialchars($datos_enviados['comentarios_director'] ?? '') ?></textarea>
                            </div>
                        <?php else: ?>
                            <div class="alert alert-info">No se encontró un director de proyecto. La información debe ser ingresada en la tabla de "Docentes Tutores".</div>
                        <?php endif; ?>
                    </div>
                    
                    <div id="seccion_tutores">
                        <h4 class="fs-5 fw-bolder text-gray-800 mb-5">3.1.2 Docentes Tutores de Servicio Comunitario:</h4>
                        <div class="table-responsive mb-1">
                            <table class="table table-row-bordered table-rounded gy-5 gs-5 w-100" id="tabla_docentes">
                                <thead>
                                    <tr class="text-start text-gray-800 fw-bold fs-7 text-uppercase gs-0 bg-gray-100">
                                        <th>Facultad</th><th>Carrera</th><th>Nombre</th><th>Cédula</th><th>Acreditado</th><th>Categoría</th><th>Dedicación</th><th>Correo</th><th>Teléfono</th><th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="cuerpoTablaTutores">
                                    <?php foreach ($datos_enviados['facultad_tutor'] ?? [] as $key => $facultadId): ?>
                                        <tr>
                                            <td>
                                                <select name="facultad_tutor[]" class="form-select form-select-solid select-facultad" data-editable required>
                                                    <option value="">Seleccione</option>
                                                    <?php foreach ($facultadData['dtResultado'] as $facultad): ?>
                                                        <option value="<?= htmlspecialchars($facultad['CodFacultad']) ?>" <?= ($facultadId == $facultad['CodFacultad']) ? 'selected' : '' ?>><?= htmlspecialchars($facultad['Facultad']) ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </td>
                                            <td><select name="carrera_tutor[]" class="form-select form-select-solid select-carrera" data-editable data-valor-guardado="<?= htmlspecialchars($datos_enviados['carrera_tutor'][$key] ?? '') ?>" required><option>...</option></select></td>
                                            <td><select name="docente_tutor[]" class="form-select form-select-solid select-docente" data-editable data-valor-guardado="<?= htmlspecialchars($datos_enviados['docente_tutor'][$key] ?? '') ?>" required><option>...</option></select></td>
                                            <td><input type="text" name="docente_cedula[]" class="form-control form-control-solid input-cedula" value="<?= htmlspecialchars($datos_enviados['docente_cedula'][$key] ?? '') ?>" readonly></td>
                                            <td>
                                                <select name="docente_acreditado[]" class="form-select form-select-solid" data-editable required>
                                                    <option value="">Seleccione</option>
                                                    <option value="si" <?= ($datos_enviados['docente_acreditado'][$key] ?? '') == 'si' ? 'selected' : '' ?>>Sí</option>
                                                    <option value="no" <?= ($datos_enviados['docente_acreditado'][$key] ?? '') == 'no' ? 'selected' : '' ?>>No</option>
                                                </select>
                                            </td>
                                            <td><input type="text" name="docente_categoria[]" class="form-control form-control-solid" value="<?= htmlspecialchars($datos_enviados['docente_categoria'][$key] ?? '') ?>" data-editable required></td>
                                            <td><input type="text" name="docente_dedicacion[]" class="form-control form-control-solid" value="<?= htmlspecialchars($datos_enviados['docente_dedicacion'][$key] ?? '') ?>" data-editable required></td>
                                            <td><input type="email" name="docente_correo[]" class="form-control form-control-solid" value="<?= htmlspecialchars($datos_enviados['docente_correo'][$key] ?? '') ?>" data-editable required></td>
                                            <td><input type="tel" name="docente_telefono[]" class="form-control form-control-solid" value="<?= htmlspecialchars($datos_enviados['docente_telefono'][$key] ?? '') ?>" data-editable required></td>
                                            <td><button type="button" class="btn btn-icon btn-danger btn-sm removeRowBtn" data-editable><i class="bi bi-trash fs-5"></i></button></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <button type="button" class="btn btn-primary" id="addTutorRowBtn" data-editable><i class="bi bi-plus-lg fs-4 me-2"></i>Agregar Tutor</button>
                        <div class="form-group mt-3">
                            <label class="form-label fw-bolder">Comentarios:</label>
                            <textarea name="comentarios_docentes" class="form-control form-control-solid" rows="2" data-comentario><?= htmlspecialchars($datos_enviados['comentarios_docentes'] ?? '') ?></textarea>
                        </div>
                    </div>

                     <div id="seccion_estudiantes">
                        <h4 class="fw-bolder mt-8">3.2 Grupo de Estudiantes</h4>
                        <h4 class="fw-bolder mt-4">3.2.1 Estudiantes que intervienen en el proyecto:</h4>
                         <div class="table-responsive mb-1">
                            <table class="table table-row-bordered table-rounded gy-5 gs-5 w-100" id="tabla_estudiantes">
                                <thead>
                                    <tr class="text-start text-gray-800 fw-bold fs-7 text-uppercase gs-0 bg-gray-100">
                                        <th>Facultad</th><th>Carrera</th><th>Número de estudiantes</th><th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="cuerpoTablaEstud">
                                    <?php foreach ($datos_enviados['facultad_estud'] ?? [] as $key => $facultadId): ?>
                                        <tr>
                                            <td>
                                                <select name="facultad_estud[]" class="form-select form-select-solid select-facultad" data-editable required>
                                                    <option value="">Seleccione</option>
                                                    <?php foreach ($facultadData['dtResultado'] as $facultad): ?>
                                                        <option value="<?= htmlspecialchars($facultad['CodFacultad']) ?>" <?= ($facultadId == $facultad['CodFacultad']) ? 'selected' : '' ?>><?= htmlspecialchars($facultad['Facultad']) ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </td>
                                            <td>
                                                <select name="carrera_estud[]" class="form-select form-select-solid select-carrera" data-editable data-valor-guardado="<?= htmlspecialchars($datos_enviados['carrera_estud'][$key] ?? '') ?>" required>
                                                    <option>...</option>
                                                </select>
                                            </td>
                                            <td><input type="number" name="estudiante_numero[]" class="form-control form-control-solid" value="<?= htmlspecialchars($datos_enviados['estudiante_numero'][$key] ?? '') ?>" data-editable required></td>
                                            <td><button type="button" class="btn btn-icon btn-danger btn-sm removeRowBtn" data-editable><i class="bi bi-trash fs-5"></i></button></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <button type="button" class="btn btn-primary mb-4" id="addEstudianteRowBtn" data-editable><i class="bi bi-plus-lg fs-4 me-2"></i>Agregar Estudiante</button>
                         <div class="form-group mt-3">
                            <label class="form-label fw-bolder">Comentarios:</label>
                            <textarea name="comentarios_estudiantes" class="form-control form-control-solid" rows="2" data-comentario><?= htmlspecialchars($datos_enviados['comentarios_estudiantes'] ?? '') ?></textarea>
                        </div>
                    </div>

                    <div id="seccion_programas">
                        <h4 class="fw-bolder mt-8">3.2.2 Estudiantes que intervienen en los programas de articulación:</h4>
                        <table class="table table-row-bordered table-rounded gy-5 gs-5 w-100" id="tabla_programas">
                            <thead>
                                <tr class="text-start text-gray-800 fw-bold fs-7 text-uppercase gs-0 bg-gray-100">
                                    <th class="w-500px">Programa de articulación</th><th class="w-500px">Número de estudiantes</th><th class="w-50px">Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="cuerpoTablaProgram">
                                <?php foreach ($datos_enviados['programa_articulacion_nombre'] ?? [] as $key => $nombrePrograma): ?>
                                    <tr>
                                        <td><input type="text" name="programa_articulacion_nombre[]" class="form-control form-control-solid" value="<?= htmlspecialchars($nombrePrograma) ?>" data-editable></td>
                                        <td><input type="number" name="programa_articulacion_numero[]" class="form-control form-control-solid input-programa-numero" value="<?= htmlspecialchars($datos_enviados['programa_articulacion_numero'][$key] ?? '') ?>" data-editable></td>
                                        <td><button type="button" class="btn btn-icon btn-danger btn-sm removeRowBtn" data-editable><i class="bi bi-trash fs-5"></i></button></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td class="fw-bolder text-end pe-3">Total de estudiantes:</td>
                                    <td><input type="number" id="total_estudiantes_programas" class="form-control form-control-solid fw-bolder" readonly></td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                        <button type="button" class="btn btn-primary mb-4" id="addProgramaRowBtn" data-editable><i class="bi bi-plus-lg fs-4 me-2"></i>Agregar Programa</button>
                        <div class="form-group mb-8">
                            <label class="form-label fw-bolder">Comentarios:</label>
                            <textarea name="comentarios_programas" class="form-control form-control-solid" rows="1" data-comentario><?= htmlspecialchars($datos_enviados['comentarios_programas'] ?? '') ?></textarea>
                        </div>
                    </div>

                    <div id="seccion_ciclos">
                        <h3 class="fs-4 fw-bolder mt-10">3.3.1 Estudiantes de Grado</h3>
                        <h4 class="fs-6 fw-bolder mb-5">3.3.1.1 Número de estudiantes por ciclo académico</h4>
                        <div id="tabla-ciclos-container">
                            <div class="row mb-3 fw-bolder text-gray-700">
                                <div class="col-md-3">Ciclo académico</div>
                                <div class="col-md-4">Número de estudiantes por ciclo académico</div>
                                <div class="col-md-4">Estudiantes con discapacidad</div>
                            </div>
                            <?php 
                            $ciclos_totales = $datos_enviados['estudiantes_ciclo_total'] ?? array_fill(0, 4, '');
                            $ciclos_discapacidad = $datos_enviados['estudiantes_ciclo_discapacidad'] ?? array_fill(0, 4, '');
                            for ($i = 0; $i < 4; $i++):
                                $ciclo_numero = $i + 1;
                                $total = $ciclos_totales[$i] ?? 0;
                                $discapacidad = $ciclos_discapacidad[$i] ?? 0;
                            ?>
                                <div class="row mb-3 align-items-center">
                                    <div class="col-md-3"><label class="form-label">Ciclo <?= $ciclo_numero ?></label></div>
                                    <div class="col-md-4"><input type="number" name="estudiantes_ciclo_total[]" class="form-control form-control-solid estudiantes-ciclo-total" min="0" value="<?= htmlspecialchars($total) ?>" data-editable></div>
                                    <div class="col-md-4"><input type="number" name="estudiantes_ciclo_discapacidad[]" class="form-control form-control-solid estudiantes-ciclo-discapacidad" min="0" value="<?= htmlspecialchars($discapacidad) ?>" data-editable></div>
                                </div>
                            <?php endfor; ?>
                            <div class="row mb-3 align-items-center border-top pt-3 mt-3">
                                <div class="col-md-3"><label class="form-label fw-bolder">Total</label></div>
                                <div class="col-md-4"><input type="number" id="total_estudiantes_ciclo" class="form-control form-control-solid fw-bolder" readonly></div>
                                <div class="col-md-4"><input type="number" id="total_estudiantes_discapacidad" class="form-control form-control-solid fw-bolder" readonly></div>
                            </div>
                        </div>
                    </div>
                    
                    <div id="seccion_acciones_inclusion">
                        <h5 class="fs-6 mt-10 fw-bolder">3.3.3 Describa brevemente las acciones...</h5>
                        <div class="form-group mb-8">
                            <textarea name="acciones_contribucion" rows="3" class="form-control form-control-solid" placeholder="Describa las acciones" data-editable required><?= htmlspecialchars($datos_enviados['acciones_contribucion'] ?? '') ?></textarea>
                        </div>
                        <div class="form-group mb-8">
                            <label class="form-label fw-bolder">Comentarios:</label>
                            <textarea name="comentarios_acciones_contribucion" class="form-control form-control-solid" rows="1" data-comentario><?= htmlspecialchars($datos_enviados['comentarios_acciones_contribucion'] ?? '') ?></textarea>
                        </div>
                    </div>
                </div>

                <div class="card-footer d-flex justify-content-end py-6 px-9">
                    <a href="?c=Formularios&m=editarPaso4&id=<?= $idProyecto ?>" id="btnAnterior" class="btn btn-secondary me-2">Anterior</a>
                    <button type="submit" class="btn btn-primary">Siguiente</button>
                </div>
            </div>
        </form>
    </div>
</div>

<template id="TutorRowTemplate">
    <tr>
        <td><select name="facultad_tutor[]" class="form-select form-select-solid select-facultad" data-editable required><option value="">Seleccione</option><?php foreach ($facultadData['dtResultado'] as $facultad): ?><option value="<?= htmlspecialchars($facultad['CodFacultad']) ?>"><?= htmlspecialchars($facultad['Facultad']) ?></option><?php endforeach; ?></select></td>
        <td><select name="carrera_tutor[]" class="form-select form-select-solid select-carrera" data-editable required><option>...</option></select></td>
        <td><select name="docente_tutor[]" class="form-select form-select-solid select-docente" data-editable required><option>...</option></select></td>
        <td><input type="text" name="docente_cedula[]" class="form-control form-control-solid input-cedula" readonly></td>
        <td><select name="docente_acreditado[]" class="form-select form-select-solid" data-editable required><option value="">Seleccione</option><option value="si">Sí</option><option value="no">No</option></select></td>
        <td><input type="text" name="docente_categoria[]" class="form-control form-control-solid" data-editable required></td>
        <td><input type="text" name="docente_dedicacion[]" class="form-control form-control-solid" data-editable required></td>
        <td><input type="email" name="docente_correo[]" class="form-control form-control-solid" data-editable required></td>
        <td><input type="tel" name="docente_telefono[]" class="form-control form-control-solid" data-editable required></td>
        <td><button type="button" class="btn btn-icon btn-danger btn-sm removeRowBtn" data-editable><i class="bi bi-trash fs-5"></i></button></td>
    </tr>
</template>
<template id="EstudRowTemplate">
    <tr>
        <td><select name="facultad_estud[]" class="form-select form-select-solid select-facultad" data-editable required><option value="">Seleccione</option><?php foreach ($facultadData['dtResultado'] as $facultad): ?><option value="<?= htmlspecialchars($facultad['CodFacultad']) ?>"><?= htmlspecialchars($facultad['Facultad']) ?></option><?php endforeach; ?></select></td>
        <td><select name="carrera_estud[]" class="form-select form-select-solid select-carrera" data-editable required><option>...</option></select></td>
        <td><input type="number" name="estudiante_numero[]" class="form-control form-control-solid" data-editable required></td>
        <td><button type="button" class="btn btn-icon btn-danger btn-sm removeRowBtn" data-editable><i class="bi bi-trash fs-5"></i></button></td>
    </tr>
</template>
<template id="ProgrRowTemplate">
    <tr>
        <td><input type="text" name="programa_articulacion_nombre[]" class="form-control form-control-solid" data-editable></td>
        <td><input type="number" name="programa_articulacion_numero[]" class="form-control form-control-solid input-programa-numero" data-editable></td>
        <td><button type="button" class="btn btn-icon btn-danger btn-sm removeRowBtn" data-editable><i class="bi bi-trash fs-5"></i></button></td>
    </tr>
</template>


<script>
// =================================================================================
// SCRIPT UNIFICADO Y ROBUSTO PARA EL FORMULARIO 5
// =================================================================================
function aplicarPermisos(scope = document) {
    <?php if (!$permite_editar): ?>
    scope.querySelectorAll('[data-editable]').forEach(function(el) {
        if (el.type === 'hidden') return;
        if (el.tagName === 'BUTTON' || el.type === 'file') {
            el.disabled = true;
            if (el.tagName === 'BUTTON') { el.style.pointerEvents = 'none'; el.style.opacity = '0.5'; }
        } else if (el.tagName === 'SELECT') {
            if (!el.parentNode.querySelector(`input[type="hidden"][name="${el.name}"]`)) {
                const hidden = document.createElement('input');
                hidden.type = 'hidden'; hidden.name = el.name; hidden.value = el.value;
                el.parentNode.insertBefore(hidden, el);
            }
            el.disabled = true; el.style.backgroundColor = '#f5f6fa';
        } else {
            el.readOnly = true; el.style.backgroundColor = '#f5f6fa';
        }
    });
    <?php endif; ?>
    <?php if (!$permite_comentar): ?>
    scope.querySelectorAll('[data-comentario]').forEach(function(el) {
        el.readOnly = true; el.style.backgroundColor = '#f5f6fa';
        if (!el.nextElementSibling || !el.nextElementSibling.classList.contains('solo-lectura-msg')) {
            const span = document.createElement('span');
            span.textContent = ' Solo lectura'; span.style.color = 'red'; span.style.fontWeight = 'bold'; span.className = 'solo-lectura-msg';
            el.parentNode.insertBefore(span, el.nextSibling);
        }
    });
    <?php endif; ?>
}

async function poblarSelect(select, url, placeholder, valorGuardado, tipo) {
    select.innerHTML = '<option value="">Cargando...</option>';
    if (!url || url.endsWith('=')) {
        select.innerHTML = `<option value="">${placeholder}</option>`; return;
    }
    try {
        const response = await fetch(url);
        const data = await response.json();
        select.innerHTML = `<option value="">${placeholder}</option>`;
        const results = (tipo === 'carrera') ? data.carreras : data.docentes;
        if (Array.isArray(results)) {
            results.forEach(item => {
                const option = document.createElement('option');
                if (tipo === 'carrera') {
                    option.value = item.CodCarrera; option.textContent = item.Carrera;
                } else {
                    option.value = item.Nombres; option.textContent = item.Nombres; option.dataset.cedula = item.CedulaDocente;
                }
                if (valorGuardado && valorGuardado == option.value) option.selected = true;
                select.appendChild(option);
            });
        }
    } catch (error) { console.error("Error poblando select:", error); select.innerHTML = `<option value="">Error al cargar</option>`; }
}

document.addEventListener('DOMContentLoaded', async () => {
    // 1. CARGA INICIAL DE DATOS
    const tablaTutores = document.getElementById('cuerpoTablaTutores');
    if (tablaTutores) {
        for (const row of tablaTutores.rows) {
            const selFac = row.querySelector('.select-facultad'), selCar = row.querySelector('.select-carrera'), selDoc = row.querySelector('.select-docente');
            if (selFac?.value) {
                await poblarSelect(selCar, `?c=Institucion&m=getCarreras&facultad=${selFac.value}`, 'Seleccione Carrera', selCar.dataset.valorGuardado, 'carrera');
                if (selCar.value) await poblarSelect(selDoc, `?c=Institucion&m=getDocentes&facultad=${selFac.value}&carrera=${selCar.value}`, 'Seleccione Docente', selDoc.dataset.valorGuardado, 'docente');
            }
        }
    }
    const tablaEstudiantes = document.getElementById('cuerpoTablaEstud');
    if (tablaEstudiantes) {
        for (const row of tablaEstudiantes.rows) {
            const selFac = row.querySelector('.select-facultad'), selCar = row.querySelector('.select-carrera');
            if (selFac?.value) await poblarSelect(selCar, `?c=Institucion&m=getCarreras&facultad=${selFac.value}`, 'Seleccione Carrera', selCar.dataset.valorGuardado, 'carrera');
        }
    }

    // 2. EVENTOS DINÁMICOS
    document.getElementById('formPaso5').addEventListener('change', async (e) => {
        const target = e.target, row = target.closest('tr');
        if (!row) return;
        if (target.matches('select[name="facultad_tutor[]"]')) {
            const selCar = row.querySelector('select[name="carrera_tutor[]"]'), selDoc = row.querySelector('select[name="docente_tutor[]"]');
            await poblarSelect(selCar, `?c=Institucion&m=getCarreras&facultad=${target.value}`, 'Seleccione Carrera', null, 'carrera');
            selDoc.innerHTML = '<option value="">Seleccione Carrera</option>'; row.querySelector('.input-cedula').value = '';
        } else if (target.matches('select[name="carrera_tutor[]"]')) {
            const selFac = row.querySelector('select[name="facultad_tutor[]"]'), selDoc = row.querySelector('select[name="docente_tutor[]"]');
            await poblarSelect(selDoc, `?c=Institucion&m=getDocentes&facultad=${selFac.value}&carrera=${target.value}`, 'Seleccione Docente', null, 'docente');
            row.querySelector('.input-cedula').value = '';
        } else if (target.matches('select[name="docente_tutor[]"]')) {
            row.querySelector('.input-cedula').value = target.options[target.selectedIndex].dataset.cedula || '';
        } else if (target.matches('select[name="facultad_estud[]"]')) {
            await poblarSelect(row.querySelector('select[name="carrera_estud[]"]'), `?c=Institucion&m=getCarreras&facultad=${target.value}`, 'Seleccione Carrera', null, 'carrera');
        }
    });

    ['addTutorRowBtn', 'addEstudianteRowBtn', 'addProgramaRowBtn'].forEach(id => {
        document.getElementById(id)?.addEventListener('click', function() {
            const tableId = (id === 'addTutorRowBtn') ? 'cuerpoTablaTutores' : (id === 'addEstudianteRowBtn' ? 'cuerpoTablaEstud' : 'cuerpoTablaProgram');
            const templateId = (id === 'addTutorRowBtn') ? 'TutorRowTemplate' : (id === 'addEstudianteRowBtn' ? 'EstudRowTemplate' : 'ProgrRowTemplate');
            const template = document.getElementById(templateId).content.cloneNode(true);
            const newRow = template.querySelector('tr');
            document.getElementById(tableId).appendChild(template);
            aplicarPermisos(newRow);
        });
    });

    document.addEventListener("click", (e) => { if (e.target.closest(".removeRowBtn")) e.target.closest("tr").remove(); });

    // 3. LÓGICA DE CÁLCULOS Y PREVISUALIZACIÓN DE IMAGEN
    const updateTotal = (selector, totalField) => {
        let total = 0;
        document.querySelectorAll(selector).forEach(input => total += Number(input.value) || 0);
        document.getElementById(totalField).value = total;
    };
    const form = document.getElementById('formPaso5');
    form.addEventListener('input', (e) => {
        if (e.target.matches('.input-programa-numero')) updateTotal('.input-programa-numero', 'total_estudiantes_programas');
        if (e.target.matches('.estudiantes-ciclo-total')) updateTotal('.estudiantes-ciclo-total', 'total_estudiantes_ciclo');
        if (e.target.matches('.estudiantes-ciclo-discapacidad')) updateTotal('.estudiantes-ciclo-discapacidad', 'total_estudiantes_discapacidad');
    });
    updateTotal('.input-programa-numero', 'total_estudiantes_programas');
    updateTotal('.estudiantes-ciclo-total', 'total_estudiantes_ciclo');
    updateTotal('.estudiantes-ciclo-discapacidad', 'total_estudiantes_discapacidad');

    const fileInput = document.getElementById('logo_proyecto');
    if (fileInput) {
        fileInput.addEventListener('change', (event) => {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    document.getElementById('imagen-previsualizacion').src = e.target.result;
                    document.getElementById('contenedor-imagen-actual').style.display = 'block';
                    const fileNameDisplay = document.getElementById('nombre-archivo-actual');
                    if(fileNameDisplay) fileNameDisplay.innerHTML = `<span class="text-muted fw-bolder">Archivo nuevo:</span> ${file.name}`;
                };
                reader.readAsDataURL(file);
            }
        });
    }

    // 4. APLICACIÓN FINAL DE PERMISOS
    aplicarPermisos(document.getElementById('formPaso5'));
});
</script>

<?php require_once ROOT_PATH . '/app/views/layout/footer.php'; ?>