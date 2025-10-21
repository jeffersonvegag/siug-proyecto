<?php
require_once ROOT_PATH . '/app/views/layout/header.php';

$pasoActual = 4;
require_once ROOT_PATH . '/app/views/layout/stepper_static.php';

?>

<div class="d-flex flex-column flex-column-fluid">
    <div class="app-content flex-column-fluid">
        <div class="">
            <form method="POST" action="?c=Formularios&m=actualizarPaso4&id=<?= htmlspecialchars($idProyecto) ?>" class="form" id="formPaso4" enctype="multipart/form-data">
                <div class="card card-custom gutter-b">
                    <div class="card-header bg-dark py-6">
                        <div class="card-title">
                            <h3 class="card-label text-white fw-bolder fs-2">4. DATOS DE LAS UNIDADES ACADÉMICAS E INSTITUCIONALES</h3>
                        </div>
                    </div>

                    <div class="p-6">
                        <div id="seccion_decano">
                            <h2 class="fs-5 fw-bold text-gray-800 mb-5 mt-5 fw-bolder">2.1 Unidad(es) académica(s) y decano de facultad</h2>
                            <div class="row">
                                <div class="col-md-6 mb-8">
                                    <label class="form-label fw-bolder">Facultad</label>
                                    <select name="facultad_decano" class="form-select form-select-solid fs-7 select-facultad" data-editable required>
                                        <option value="">Seleccione</option>
                                        <?php foreach ($facultadData['dtResultado'] as $facultad):
                                            $selected = (($datos_enviados['facultad_decano'] ?? '') == $facultad['CodFacultad']) ? 'selected' : ''; ?>
                                            <option value="<?= htmlspecialchars($facultad['CodFacultad']) ?>" <?= $selected ?>><?= htmlspecialchars($facultad['Facultad']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-8">
                                    <label class="form-label fw-bolder">Carrera</label>
                                    <select name="carrera_decano" class="form-select form-select-solid fs-7 select-carrera" data-editable data-valor-guardado="<?= htmlspecialchars($datos_enviados['carrera_decano'] ?? '') ?>" required>
                                        <option value="">Seleccione Facultad primero</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-8">
                                    <label class="form-label fw-bolder">Decano</label>
                                    <input type="text" name="decano_decano" class="form-control form-control-solid" value="<?= htmlspecialchars($datos_enviados['decano_decano'] ?? '') ?>" data-editable required />
                                </div>
                                <div class="col-md-4 mb-8">
                                    <label class="form-label fw-bolder">Correo</label>
                                    <input type="email" name="decano_correo" class="form-control form-control-solid" value="<?= htmlspecialchars($datos_enviados['decano_correo'] ?? '') ?>" data-editable required />
                                </div>
                                <div class="col-md-4 mb-8">
                                    <label class="form-label fw-bolder">Teléfono</label>
                                    <input type="text" name="decano_telefono" class="form-control form-control-solid" value="<?= htmlspecialchars($datos_enviados['decano_telefono'] ?? '') ?>" data-editable required />
                                </div>
                            </div>
                        </div>

                        <div id="seccion_director_proyecto">
                            <h2 class="fs-5 fw-bolder text-gray-800 mb-5 mt-10">Director de Proyecto</h2>
                            <div class="table-responsive mb-1">
                                <table class="table table-row-bordered table-rounded gy-5 gs-5 w-100" id="tablaDirectoresProyecto">
                                    <thead>
                                        <tr class="text-start text-gray-800 fw-bold fs-7 text-uppercase gs-0 bg-gray-100">
                                            <th>Facultad</th><th>Carrera</th><th>Director</th><th>Correo</th><th>Teléfono</th><th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody id="cuerpoTablaDirectoresProyecto">
                                        <?php foreach ($datos_enviados['facultad_director'] ?? [] as $k => $facultadId): ?>
                                            <tr>
                                                <td>
                                                    <select name="facultad_director[]" class="form-select form-select-solid fs-7 select-facultad" data-editable required>
                                                        <option value="">Seleccione</option>
                                                        <?php foreach ($facultadData['dtResultado'] as $facultad):
                                                            $selected = ($facultadId == $facultad['CodFacultad']) ? 'selected' : ''; ?>
                                                            <option value="<?= htmlspecialchars($facultad['CodFacultad']) ?>" <?= $selected ?>><?= htmlspecialchars($facultad['Facultad']) ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </td>
                                                <td><select name="carrera_director[]" class="form-select form-select-solid fs-7 select-carrera" data-editable data-valor-guardado="<?= htmlspecialchars($datos_enviados['carrera_director'][$k] ?? '') ?>" required><option value="">...</option></select></td>
                                                <td><select name="nombre_director[]" class="form-select form-select-solid fs-7 select-docente" data-editable data-valor-guardado="<?= htmlspecialchars($datos_enviados['nombre_director'][$k] ?? '') ?>" required><option value="">...</option></select></td>
                                                <td><input type="email" name="director_correo[]" class="form-control form-control-solid fs-7" value="<?= htmlspecialchars($datos_enviados['director_correo'][$k] ?? '') ?>" data-editable required></td>
                                                <td><input type="text" name="director_telefono[]" class="form-control form-control-solid fs-7" value="<?= htmlspecialchars($datos_enviados['director_telefono'][$k] ?? '') ?>" data-editable required></td>
                                                <td><button type="button" class="btn btn-icon btn-danger btn-sm removeRowBtn" data-editable><i class="bi bi-trash fs-5"></i></button></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            <button type="button" class="btn btn-primary" id="addDirectorProyectoRowBtn" data-editable><i class="bi bi-plus-lg fs-4 me-2"></i>Agregar Director</button>
                        </div>

                        <div id="seccion_institucion_externa">
                            <h2 class="fs-5 fw-bolder text-gray-800 mb-5 mt-10">2.2 Institución externa</h2>
                            
                            <div class="form-group mb-8">
                                <label for="logo_proyecto" class="form-label fw-bolder">Logo del proyecto (opcional):</label>
                                <input type="file" name="logo_proyecto" id="logo_proyecto" class="form-control form-control-solid" accept="image/*" data-editable/>
                                <?php if (!empty($errores['logo_proyecto'])): ?>
                                    <div class="text-danger small mt-1"><?= htmlspecialchars($errores['logo_proyecto']) ?></div>
                                <?php endif; ?>
                                <?php 
                                    $rutaImagen = $datos_enviados['RutaImagen'] ?? null;
                                    if ($rutaImagen): 
                                        $nombreArchivo = basename($rutaImagen);
                                ?>
                                    <div class="mt-2" id="nombre-archivo-actual">
                                        <span class="text-muted fw-bolder">Archivo actual:</span> <?= htmlspecialchars($nombreArchivo) ?>
                                    </div>
                                <?php else: ?>
                                    <div class="mt-2" id="nombre-archivo-actual"></div>
                                <?php endif; ?>
                            </div>

                            <div class="mb-8" id="contenedor-imagen-actual" style="<?= !$rutaImagen ? 'display:none;' : '' ?>">
                                <label class="form-label fw-bolder">Imagen Actual:</label>
                                <div>
                                    <img id="imagen-previsualizacion" src="<?= $rutaImagen ? '/SISPROCON_UG' . htmlspecialchars($rutaImagen) : '#' ?>" alt="Logo del Proyecto" style="max-width: 200px; height: auto; border: 1px solid #ccc; padding: 5px; border-radius: 5px;">
                                </div>
                            </div>
                            
                            <div class="form-group mb-8">
                                <label class="form-label fw-bolder ">Nombre de la institución:</label>
                                <input type="text" name="externa_nombre" class="form-control form-control-solid" value="<?= htmlspecialchars($datos_enviados['externa_nombre'] ?? '') ?>" data-editable required>
                            </div>
                            <div class="form-group mb-8">
                                <label class="form-label fw-bolder ">Nombre del representante legal:</label>
                                <input type="text" name="externa_repres_nombre" class="form-control form-control-solid" value="<?= htmlspecialchars($datos_enviados['externa_repres_nombre'] ?? '') ?>" data-editable required>
                            </div>
                            <div class="form-group mb-8">
                                <label class="form-label fw-bolder ">Dirección:</label>
                                <input type="text" name="externa_dir" class="form-control form-control-solid" value="<?= htmlspecialchars($datos_enviados['externa_dir'] ?? '') ?>" data-editable required>
                            </div>
                            <div class="form-group mb-8">
                                <label class="form-label fw-bolder ">Teléfonos:</label>
                                <input type="text" name="externa_tel" class="form-control form-control-solid" value="<?= htmlspecialchars($datos_enviados['externa_tel'] ?? '') ?>" data-editable required>
                            </div>
                            <div class="form-group mb-8">
                                <label class="form-label fw-bolder ">Correo electrónico:</label>
                                <input type="email" name="externa_correo" class="form-control form-control-solid" value="<?= htmlspecialchars($datos_enviados['externa_correo'] ?? '') ?>" data-editable required>
                            </div>
                            <div class="form-group mb-8">
                                <label class="form-label fw-bolder ">Página Web:</label>
                                <input type="text" name="externa_web" class="form-control form-control-solid" value="<?= htmlspecialchars($datos_enviados['externa_web'] ?? '') ?>" data-editable>
                            </div>
                        </div>

                         <div id="seccion_cooperantes">
                             <h2 class="fs-5 fw-bolder text-gray-800 mb-5 mt-10">2.3 Otras Unidades Académicas Cooperantes</h2>
                             <div class="table-responsive mb-1">
                                 <table class="table table-row-bordered table-rounded gy-5 gs-5 w-100" id="tablaUnidadesCooperantes">
                                     <thead>
                                         <tr class="text-start text-gray-800 fw-bold fs-7 text-uppercase gs-0 bg-gray-100">
                                            <th>Facultad</th><th>Carrera</th><th>Docente responsable</th><th>Correo</th><th>Teléfono</th><th>Acciones</th>
                                         </tr>
                                     </thead>
                                     <tbody id="cuerpoTablaUnidadesCooperantes">
                                        <?php foreach ($datos_enviados['facultad_coop'] ?? [] as $k => $facultadId): ?>
                                            <tr>
                                                <td>
                                                    <select name="facultad_coop[]" class="form-select form-select-solid fs-7 select-facultad" data-editable required>
                                                        <option value="">Seleccione</option>
                                                        <?php foreach ($facultadData['dtResultado'] as $facultad):
                                                            $selected = ($facultadId == $facultad['CodFacultad']) ? 'selected' : ''; ?>
                                                            <option value="<?= htmlspecialchars($facultad['CodFacultad']) ?>" <?= $selected ?>><?= htmlspecialchars($facultad['Facultad']) ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </td>
                                                <td><select name="carrera_coop[]" class="form-select form-select-solid fs-7 select-carrera" data-editable data-valor-guardado="<?= htmlspecialchars($datos_enviados['carrera_coop'][$k] ?? '') ?>" required><option value="">...</option></select></td>
                                                <td><select name="docente_coop[]" class="form-select form-select-solid fs-7 select-docente" data-editable data-valor-guardado="<?= htmlspecialchars($datos_enviados['docente_coop'][$k] ?? '') ?>" required><option value="">...</option></select></td>
                                                
                                                <td><input type="email" name="correo[]" class="form-control form-control-solid fs-7" value="<?= htmlspecialchars($datos_enviados['correo'][$k] ?? '') ?>" data-editable required></td>
                                                <td><input type="text" name="telefono[]" class="form-control form-control-solid fs-7" value="<?= htmlspecialchars($datos_enviados['telefono'][$k] ?? '') ?>" data-editable required></td>
                                                
                                                <td><button type="button" class="btn btn-icon btn-danger btn-sm removeRowBtn" data-editable><i class="bi bi-trash fs-5"></i></button></td>
                                            </tr>
                                        <?php endforeach; ?>
                                     </tbody>
                                 </table>
                             </div>
                             <button type="button" class="btn btn-primary" id="addUnidadCooperanteRowBtn" data-editable><i class="bi bi-plus-lg fs-4 me-2"></i>Agregar Unidad</button>
                         </div>
                         
                         <div id="seccion_aliado_estrategico">
                            <h2 class="fs-5 fw-bolder text-gray-800 mb-5 mt-10">2.4 Aliado Estratégico</h2>
                            <div class="form-group mb-8">
                                <label class="form-label fw-bolder ">Nombre de la institución:</label>
                                <input type="text" name="aliado_nombre" class="form-control form-control-solid" value="<?= htmlspecialchars($datos_enviados['aliado_nombre'] ?? '') ?>" data-editable required>
                            </div>
                            <div class="form-group mb-8">
                                <label class="form-label fw-bolder ">Nombre del representante legal:</label>
                                <input type="text" name="aliado_repres_nombre" class="form-control form-control-solid" value="<?= htmlspecialchars($datos_enviados['aliado_repres_nombre'] ?? '') ?>" data-editable required>
                            </div>
                            <div class="form-group mb-8">
                                <label class="form-label fw-bolder ">Dirección:</label>
                                <input type="text" name="aliado_direccion" class="form-control form-control-solid" value="<?= htmlspecialchars($datos_enviados['aliado_direccion'] ?? '') ?>" data-editable required>
                            </div>
                            <div class="form-group mb-8">
                                <label class="form-label fw-bolder ">Teléfonos:</label>
                                <input type="text" name="aliado_tel" class="form-control form-control-solid" value="<?= htmlspecialchars($datos_enviados['aliado_tel'] ?? '') ?>" data-editable required>
                            </div>
                            <div class="form-group mb-8">
                                <label class="form-label fw-bolder ">Correo electrónico:</label>
                                <input type="email" name="aliado_correo" class="form-control form-control-solid" value="<?= htmlspecialchars($datos_enviados['aliado_correo'] ?? '') ?>" data-editable required>
                            </div>
                            <div class="form-group mb-8">
                                <label class="form-label fw-bolder ">Página Web:</label>
                                <input type="text" name="aliado_web" class="form-control form-control-solid" value="<?= htmlspecialchars($datos_enviados['aliado_web'] ?? '') ?>" data-editable>
                            </div>
                            <div class="form-group mb-8">
                                <label class="form-label fw-bolder ">Describa la contribución por parte del aliado estratégico:</label>
                                <textarea name="aliado_contribucion" class="form-control form-control-solid" rows="2" data-editable><?= htmlspecialchars($datos_enviados['aliado_contribucion'] ?? '') ?></textarea>
                            </div>
                         </div>
                    </div>

                    <div class="card-footer d-flex justify-content-end py-6 px-9">
                        <a href="?c=Formularios&m=editarPaso3&id=<?= htmlspecialchars($idProyecto) ?>" class="btn btn-secondary me-2">Anterior</a>
                        <button type="submit" class="btn btn-primary">Guardar y Siguiente</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<template id="directorProyectoRowTemplate">
    <tr>
        <td>
            <select name="facultad_director[]" class="form-select form-select-solid fs-7 select-facultad" data-editable required>
                <option value="">Seleccione</option>
                <?php foreach ($facultadData['dtResultado'] as $facultad): ?>
                    <option value="<?= htmlspecialchars($facultad['CodFacultad']) ?>"><?= htmlspecialchars($facultad['Facultad']) ?></option>
                <?php endforeach; ?>
            </select>
        </td>
        <td><select name="carrera_director[]" class="form-select form-select-solid fs-7 select-carrera" data-editable required><option value="">Seleccione Facultad</option></select></td>
        <td><select name="nombre_director[]" class="form-select form-select-solid fs-7 select-docente" data-editable required><option value="">Seleccione Carrera</option></select></td>
        <td><input type="email" name="director_correo[]" class="form-control form-control-solid fs-7" data-editable required></td>
        <td><input type="text" name="director_telefono[]" class="form-control form-control-solid fs-7" data-editable required></td>
        <td><button type="button" class="btn btn-icon btn-danger btn-sm removeRowBtn" data-editable><i class="bi bi-trash fs-5"></i></button></td>
    </tr>
</template>
<template id="unidadCooperanteRowTemplate">
    <tr>
        <td>
             <select name="facultad_coop[]" class="form-select form-select-solid fs-7 select-facultad" data-editable required>
                <option value="">Seleccione</option>
                <?php foreach ($facultadData['dtResultado'] as $facultad): ?>
                    <option value="<?= htmlspecialchars($facultad['CodFacultad']) ?>"><?= htmlspecialchars($facultad['Facultad']) ?></option>
                <?php endforeach; ?>
            </select>
        </td>
        <td><select name="carrera_coop[]" class="form-select form-select-solid fs-7 select-carrera" data-editable required><option value="">Seleccione Facultad</option></select></td>
        <td><select name="docente_coop[]" class="form-select form-select-solid fs-7 select-docente" data-editable required><option value="">Seleccione Carrera</option></select></td>
        <td><input type="email" name="correo[]" class="form-control form-control-solid fs-7" data-editable required></td>
        <td><input type="text" name="telefono[]" class="form-control form-control-solid fs-7" data-editable required></td>
        <td><button type="button" class="btn btn-icon btn-danger btn-sm removeRowBtn" data-editable><i class="bi bi-trash fs-5"></i></button></td>
    </tr>
</template>


<script>
// =================================================================================
// SCRIPT UNIFICADO Y ROBUSTO PARA EL FORMULARIO 4
// =================================================================================

/**
 * Aplica permisos a un contenedor específico (scope).
 */
function aplicarPermisos(scope = document) {
    <?php if (!$permite_editar): ?>
    scope.querySelectorAll('[data-editable]').forEach(function(el) {
        if (el.type === 'hidden') return;

        if (el.tagName === 'BUTTON' || el.type === 'file') {
            el.disabled = true;
            if (el.tagName === 'BUTTON') {
                el.style.pointerEvents = 'none';
                el.style.opacity = '0.5';
            }
        } 
        else if (el.tagName === 'SELECT') {
            if (!el.parentNode.querySelector(`input[type="hidden"][name="${el.name}"]`)) {
                const hidden = document.createElement('input');
                hidden.type = 'hidden';
                hidden.name = el.name;
                hidden.value = el.value;
                el.parentNode.insertBefore(hidden, el);
            }
            el.disabled = true;
            el.style.backgroundColor = '#f5f6fa';
        } 
        else {
            el.readOnly = true;
            el.style.backgroundColor = '#f5f6fa';
        }
    });
    <?php endif; ?>
}

function cargarCarreras(selectFacultad, selectCarrera) {
    const valorFacultad = selectFacultad.value;
    const valorGuardado = selectCarrera.dataset.valorGuardado || '';
    selectCarrera.innerHTML = '<option value="">Cargando...</option>';
    if (!valorFacultad) {
        selectCarrera.innerHTML = '<option value="">Seleccione Facultad</option>';
        return Promise.resolve();
    }
    return fetch(`?c=Institucion&m=getCarreras&facultad=${valorFacultad}`)
        .then(response => response.json())
        .then(data => {
            selectCarrera.innerHTML = '<option value="">Seleccione Carrera</option>';
            if (Array.isArray(data.carreras)) {
                data.carreras.forEach(carrera => {
                    const option = document.createElement('option');
                    option.value = carrera.CodCarrera;
                    option.textContent = carrera.Carrera;
                    if (valorGuardado == carrera.CodCarrera) option.selected = true;
                    selectCarrera.appendChild(option);
                });
            }
        });
}

function cargarDocentes(selectFacultad, selectCarrera, selectDocente) {
    const valorFacultad = selectFacultad.value;
    const valorCarrera = selectCarrera.value;
    const valorGuardado = selectDocente.dataset.valorGuardado || '';
    selectDocente.innerHTML = '<option value="">Cargando...</option>';
    if (!valorFacultad || !valorCarrera) {
        selectDocente.innerHTML = '<option value="">Seleccione Carrera</option>';
        return Promise.resolve();
    }
    return fetch(`?c=Institucion&m=getDocentes&facultad=${valorFacultad}&carrera=${valorCarrera}`)
        .then(response => response.json())
        .then(data => {
            selectDocente.innerHTML = '<option value="">Seleccione Docente</option>';
            if (Array.isArray(data.docentes)) {
                data.docentes.forEach(docente => {
                    const option = document.createElement('option');
                    option.value = docente.CedulaDocente;
                    option.textContent = docente.Nombres;
                    if (valorGuardado == docente.CedulaDocente) option.selected = true;
                    selectDocente.appendChild(option);
                });
            }
        });
}


document.addEventListener('DOMContentLoaded', async () => {

    // 1. CARGA INICIAL DE DATOS CON AWAIT
    const seccionDecano = document.getElementById('seccion_decano');
    if (seccionDecano) {
        const selectFacultad = seccionDecano.querySelector('.select-facultad');
        const selectCarrera = seccionDecano.querySelector('.select-carrera');
        if (selectFacultad?.value) await cargarCarreras(selectFacultad, selectCarrera);
    }

    const tablaDirectores = document.getElementById('cuerpoTablaDirectoresProyecto');
    if (tablaDirectores) {
        for (const row of tablaDirectores.rows) {
            const selectFacultad = row.querySelector('.select-facultad');
            const selectCarrera = row.querySelector('.select-carrera');
            const selectDocente = row.querySelector('.select-docente');
            if (selectFacultad?.value) {
                await cargarCarreras(selectFacultad, selectCarrera);
                if (selectCarrera.value) await cargarDocentes(selectFacultad, selectCarrera, selectDocente);
            }
        }
    }
    
    const tablaCooperantes = document.getElementById('cuerpoTablaUnidadesCooperantes');
    if (tablaCooperantes) {
        for (const row of tablaCooperantes.rows) {
            const selectFacultad = row.querySelector('.select-facultad');
            const selectCarrera = row.querySelector('.select-carrera');
            const selectDocente = row.querySelector('.select-docente');
            if (selectFacultad?.value) {
                await cargarCarreras(selectFacultad, selectCarrera);
                if (selectCarrera.value) await cargarDocentes(selectFacultad, selectCarrera, selectDocente);
            }
        }
    }

    // 2. CONFIGURACIÓN DE EVENTOS DINÁMICOS
    document.addEventListener('change', function(e) {
        const target = e.target;
        if (target.matches('.select-facultad')) {
            const container = target.closest('.row, tr');
            const selectCarrera = container.querySelector('.select-carrera');
            const selectDocente = container.querySelector('.select-docente');
            if (selectCarrera) {
                cargarCarreras(target, selectCarrera).then(() => {
                    if (selectDocente) selectDocente.innerHTML = '<option value="">Seleccione Carrera</option>';
                });
            }
        }
        if (target.matches('.select-carrera')) {
             const container = target.closest('.row, tr');
             const selectFacultad = container.querySelector('.select-facultad');
             const selectDocente = container.querySelector('.select-docente');
             if (selectDocente && selectFacultad) cargarDocentes(selectFacultad, target, selectDocente);
        }
    });

    document.getElementById('addDirectorProyectoRowBtn')?.addEventListener('click', function() {
        const template = document.getElementById('directorProyectoRowTemplate').content.cloneNode(true);
        const newRow = template.querySelector('tr');
        tablaDirectores.appendChild(template);
        aplicarPermisos(newRow);
    });

    document.getElementById('addUnidadCooperanteRowBtn')?.addEventListener('click', function() {
        const template = document.getElementById('unidadCooperanteRowTemplate').content.cloneNode(true);
        const newRow = template.querySelector('tr');
        tablaCooperantes.appendChild(template);
        aplicarPermisos(newRow);
    });

    document.addEventListener("click", function(e) {
        if (e.target.closest(".removeRowBtn")) e.target.closest("tr").remove();
    });

    // CORRECCIÓN 1: Se añadió la lógica para la previsualización de la imagen.
    const fileInput = document.getElementById('logo_proyecto');
    if (fileInput) {
        fileInput.addEventListener('change', function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const previewContainer = document.getElementById('contenedor-imagen-actual');
                    const previewImage = document.getElementById('imagen-previsualizacion');
                    const fileNameDisplay = document.getElementById('nombre-archivo-actual');
                    
                    previewImage.src = e.target.result;
                    previewContainer.style.display = 'block';
                    if(fileNameDisplay) fileNameDisplay.innerHTML = `<span class="text-muted fw-bolder">Archivo nuevo:</span> ${file.name}`;
                };
                reader.readAsDataURL(file);
            }
        });
    }

    // 3. APLICACIÓN FINAL DE PERMISOS
    aplicarPermisos(document.getElementById('formPaso4'));
});
</script>

<?php require_once ROOT_PATH . '/app/views/layout/footer.php'; ?>