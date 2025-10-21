<?php
require_once ROOT_PATH . '/app/views/layout/header.php';

$pasoActual = 4;
require_once ROOT_PATH . '/app/views/layout/stepper_static.php';
?>

<div class="d-flex flex-column flex-column-fluid">
    <div class="app-content flex-column-fluid">
        <div class="">
            <form method="POST" action="?c=Formularios&m=paso4" class="form" id="formPaso4" novalidate  enctype="multipart/form-data">
                <div class="card card-custom gutter-b">
                    <div class="card-header bg-dark y py-6">
                        <div class="card-title">
                            <h3 class="card-label text-white fw-bolder fs-2">4. DATOS DE LAS UNIDADES ACADÉMICAS E INSTITUCIONALES</h3>
                        </div>
                    </div>

                    <div class="card-body p-6">
                        <h2 class="fs-5 fw-bold text-gray-800 mb-5 mt-5 fw-bolder">2.1 Unidad(es) académica(s) y decano de facultad</h2>
                        <div class="row">
                            <div class="col-md-6 mb-8">
                                <div class="form-group">
                                    <?php $fieldName = 'facultad_decano';
                                    $valorPrevio = $datos_enviados[$fieldName] ?? ''; ?>
                                    <label for="facultad_decano" class="form-label fw-bolder">Facultad</label>
                                    <select name="facultad_decano" id="facultad_decano" class="form-select form-select-solid fs-7 <?= !empty($errores[$fieldName]) ? 'is-invalid' : '' ?>" required>
                                        <option value="">Seleccione una Facultad</option>
                                        <?php if (isset($facultadData) && !empty($facultadData['dtResultado'])): foreach ($facultadData['dtResultado'] as $facultad): ?>
                                                <option value="<?= htmlspecialchars($facultad['CodFacultad']) ?>" <?= ($valorPrevio == $facultad['CodFacultad']) ? 'selected' : '' ?>><?= htmlspecialchars($facultad['Facultad']) ?></option>
                                        <?php endforeach;
                                        endif; ?>
                                    </select>
                                    <?php if (!empty($errores[$fieldName])): ?><div class="text-danger small mt-1"><?= htmlspecialchars($errores[$fieldName]) ?></div><?php endif; ?>
                                </div>
                            </div>
                            <div class="col-md-6 mb-8">
                                <div class="form-group">
                                    <?php $fieldName = 'carrera_decano';
                                    $valorPrevio = $datos_enviados[$fieldName] ?? ''; ?>
                                    <label for="carrera_decano" class="form-label fw-bolder">Carrera</label>
                                    <select name="carrera_decano" id="carrera_decano" class="form-select form-select-solid fs-7 <?= !empty($errores[$fieldName]) ? 'is-invalid' : '' ?>" data-selected-value="<?= htmlspecialchars($valorPrevio) ?>" required>
                                        <option value="">
                                            <?php
                                            // Cambia el texto del placeholder dependiendo de si hay una facultad seleccionada
                                            if (!empty($datos_enviados['facultad_decano'])) {
                                                echo 'Seleccione una Carrera';
                                            } else {
                                                echo 'Primero seleccione una Facultad';
                                            }
                                            ?>
                                        </option>
                                        <?php
                                        // Si el controlador nos pasó la lista de carreras, la usamos para rellenar las opciones
                                        if (isset($carreras_decano_precargadas) && !empty($carreras_decano_precargadas)):
                                            foreach ($carreras_decano_precargadas as $carrera): ?>
                                                <option value="<?= htmlspecialchars($carrera['CodCarrera']) ?>" <?= ($valorPrevio == $carrera['CodCarrera']) ? 'selected' : '' ?>>
                                                    <?= htmlspecialchars($carrera['Carrera']) ?>
                                                </option>
                                        <?php
                                            endforeach;
                                        endif;
                                        ?>
                                    </select>
                                    <?php if (!empty($errores[$fieldName])): ?><div class="text-danger small mt-1"><?= htmlspecialchars($errores[$fieldName]) ?></div><?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 mb-8">
                                <div class="form-group">
                                    <?php $fieldName = 'decano_decano';
                                    $valorPrevio = $datos_enviados[$fieldName] ?? ''; ?>
                                    <label class="form-label fw-bolder">Decano</label>
                                    <input type="text" name="decano_decano" class="form-control form-control-solid <?= !empty($errores[$fieldName]) ? 'is-invalid' : '' ?>" value="<?= htmlspecialchars($valorPrevio) ?>" placeholder="Nombre del decano" required />
                                    <?php if (!empty($errores[$fieldName])): ?><div class="text-danger small mt-1"><?= htmlspecialchars($errores[$fieldName]) ?></div><?php endif; ?>
                                </div>
                            </div>
                            <div class="col-md-4 mb-8">
                                <div class="form-group">
                                    <?php $fieldName = 'decano_correo';
                                    $valorPrevio = $datos_enviados[$fieldName] ?? ''; ?>
                                    <label for="decano_correo" class="form-label fw-bolder">Correo</label>
                                    <input type="email" id="decano_correo" name="decano_correo" class="form-control form-control-solid <?= !empty($errores[$fieldName]) ? 'is-invalid' : '' ?>" value="<?= htmlspecialchars($valorPrevio) ?>" placeholder="Correo del decano" required />
                                    <?php if (!empty($errores[$fieldName])): ?><div class="text-danger small mt-1"><?= htmlspecialchars($errores[$fieldName]) ?></div><?php endif; ?>
                                </div>
                            </div>
                            <div class="col-md-4 mb-8">
                                <div class="form-group">
                                    <?php $fieldName = 'decano_telefono';
                                    $valorPrevio = $datos_enviados[$fieldName] ?? ''; ?>
                                    <label for="decano_telefono" class="form-label fw-bolder">Teléfono</label>
                                    <input type="tel" id="decano_telefono" name="decano_telefono" class="form-control form-control-solid <?= !empty($errores[$fieldName]) ? 'is-invalid' : '' ?>" value="<?= htmlspecialchars($valorPrevio) ?>" placeholder="Teléfono del decano" required maxlength="10" pattern="\d{10}" oninput="this.value = this.value.replace(/[^0-9]/g, '')" />
                                    <?php if (!empty($errores[$fieldName])): ?><div class="text-danger small mt-1"><?= htmlspecialchars($errores[$fieldName]) ?></div><?php endif; ?>
                                </div>
                            </div>
                        </div>


                        <h2 class="fs-5 fw-bolder text-gray-800 mb-5 mt-10">Director de Proyecto</h2>
                        <?php if (!empty($errores['director_general'])): ?><div class="alert alert-danger small p-2"><?= htmlspecialchars($errores['director_general']) ?></div><?php endif; ?>
                        <div class="table-responsive mb-1">
                            <table class="table table-row-bordered table-rounded gy-5 gs-5 w-100" id="tablaDirectoresProyecto">
                                <thead>
                                    <tr class="text-start text-gray-800 fw-bold fs-7 text-uppercase gs-0 bg-gray-100">
                                        <th class="w-150px">Facultad</th>
                                        <th class="w-150px">Carrera</th>
                                        <th class="w-150px">Director</th>
                                        <th class="w-200px">Correo</th>
                                        <th class="w-150px">Teléfono</th>
                                        <th class="w-50px text-start">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="cuerpoTablaDirectoresProyecto">
                                    <?php
                                    $directoresGuardados = $datos_enviados['facultad_director'] ?? [];
                                    if (!empty($directoresGuardados)):
                                        foreach ($directoresGuardados as $key => $facultadId):
                                            $carreraId = $datos_enviados['carrera_director'][$key] ?? '';
                                            $directorId = $datos_enviados['nombre_director'][$key] ?? '';
                                            $correo = $datos_enviados['director_correo'][$key] ?? '';
                                            $telefono = $datos_enviados['director_telefono'][$key] ?? '';

                                            // Obtener las listas precargadas por el controlador
                                            $carreras_posibles = $datos_enviados['carreras_director_posibles'][$key] ?? [];
                                            $docentes_posibles = $datos_enviados['docentes_director_posibles'][$key] ?? [];
                                    ?>
                                            <tr>
                                                <td>
                                                    <select name="facultad_director[]" class="form-select form-select-solid fs-7 <?= !empty($errores['facultad_director'][$key]) ? 'is-invalid' : '' ?>" required>
                                                        <option value="">Seleccione</option>
                                                        <?php foreach ($facultadData['dtResultado'] as $facultad): ?>
                                                            <option value="<?= htmlspecialchars($facultad['CodFacultad']) ?>" <?= ($facultadId == $facultad['CodFacultad']) ? 'selected' : '' ?>><?= htmlspecialchars($facultad['Facultad']) ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </td>
                                                <td>
                                                    <select name="carrera_director[]" class="form-select form-select-solid fs-7 <?= !empty($errores['carrera_director'][$key]) ? 'is-invalid' : '' ?>" data-selected-value="<?= htmlspecialchars($carreraId) ?>" required>
                                                        <option value="">Seleccione</option>
                                                        <?php foreach ($carreras_posibles as $carrera): ?>
                                                            <option value="<?= htmlspecialchars($carrera['CodCarrera']) ?>" <?= ($carreraId == $carrera['CodCarrera']) ? 'selected' : '' ?>><?= htmlspecialchars($carrera['Carrera']) ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </td>
                                                <td>
                                                    <select name="nombre_director[]" class="form-select form-select-solid fs-7 <?= !empty($errores['nombre_director'][$key]) ? 'is-invalid' : '' ?>" data-selected-value="<?= htmlspecialchars($directorId) ?>" required>
                                                        <option value="">Seleccione</option>
                                                        <?php foreach ($docentes_posibles as $docente): ?>
                                                            <option value="<?= htmlspecialchars($docente['CedulaDocente']) ?>" <?= ($directorId == $docente['CedulaDocente']) ? 'selected' : '' ?>><?= htmlspecialchars($docente['Nombres']) ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </td>
                                                <td><input type="email" name="director_correo[]" class="form-control form-control-solid fs-7 <?= !empty($errores['director_correo'][$key]) ? 'is-invalid' : '' ?>" value="<?= htmlspecialchars($correo) ?>" placeholder="Correo" required /></td>
                                                <td><input type="tel" name="director_telefono[]" class="form-control form-control-solid fs-7 <?= !empty($errores['director_telefono'][$key]) ? 'is-invalid' : '' ?>" value="<?= htmlspecialchars($telefono) ?>" placeholder="Teléfono" required maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '')" /></td>
                                                <td><button type="button" class="btn btn-icon btn-danger btn-sm removeRowBtn"><i class="bi bi-trash fs-5"></i></button></td>
                                            </tr>
                                    <?php endforeach;
                                    endif; ?>
                                </tbody>
                            </table>
                        </div>
                        <button type="button" class="btn btn-primary" id="addDirectorProyectoRowBtn"><i class="bi bi-plus-lg fs-4 me-2"></i>Agregar Director</button>

                        <template id="directorProyectoRowTemplate">
                            <tr>
                                <td>
                                    <select name="facultad_director[]" class="form-select form-select-solid fs-7" required>
                                        <option value="">Seleccione una Facultad</option>
                                        <?php if (isset($facultadData) && !empty($facultadData['dtResultado'])): foreach ($facultadData['dtResultado'] as $facultad): ?>
                                                <option value="<?= htmlspecialchars($facultad['CodFacultad']) ?>"><?= htmlspecialchars($facultad['Facultad']) ?></option>
                                        <?php endforeach;
                                        endif; ?>
                                    </select>
                                </td>
                                <td><select name="carrera_director[]" class="form-select form-select-solid fs-7" required>
                                        <option value="">Seleccione Carrera</option>
                                    </select></td>
                                <td><select name="nombre_director[]" class="form-select form-select-solid fs-7" required>
                                        <option value="">Seleccione Director</option>
                                    </select></td>
                                <td><input type="email" name="director_correo[]" class="form-control form-control-solid fs-7" placeholder="Correo institucional" required /></td>
                                <td><input type="tel" name="director_telefono[]" class="form-control form-control-solid fs-7" placeholder="Telefono" required maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '')" /></td>
                                <td><button type="button" class="btn btn-icon btn-danger btn-sm removeRowBtn"><i class="bi bi-trash fs-5"></i></button></td>
                            </tr>
                        </template>


                        <h2 class="fs-5 fw-bolder text-gray-800 mb-5 mt-10">2.2 Institución externa</h2>
                        <div class="form-group mb-8">
                            <?php $fieldName = 'externa_nombre';
                            $valorPrevio = $datos_enviados[$fieldName] ?? ''; ?>
                            <label class="form-label fw-bolder">Nombre de la institución:</label>
                            <input type="text" name="externa_nombre" class="form-control form-control-solid <?= !empty($errores[$fieldName]) ? 'is-invalid' : '' ?>" value="<?= htmlspecialchars($valorPrevio) ?>" placeholder="Ingrese el nombre" required>
                            <?php if (!empty($errores[$fieldName])): ?><div class="text-danger small mt-1"><?= htmlspecialchars($errores[$fieldName]) ?></div><?php endif; ?>
                        </div>
                        <div class="form-group mb-8">
                            <label for="logo_proyecto" class="form-label fw-bolder">Logo del proyecto (opcional):</label>
                            <input type="file" name="logo_proyecto" id="logo_proyecto" class="form-control form-control-solid" accept="image/*" />
                            <?php if (!empty($errores['logo_proyecto'])): ?>
                                <div class="text-danger small mt-1"><?= htmlspecialchars($errores['logo_proyecto']) ?></div>
                            <?php endif; ?>
                            <div class="mt-2" id="nombre-archivo-actual">
                                <?php
                                $rutaImagen = $datos_enviados['RutaImagen'] ?? null;
                                if ($rutaImagen) {
                                    $nombreArchivo = basename($rutaImagen);
                                    echo '<span class="text-muted fw-bolder">Archivo actual:</span> ' . htmlspecialchars($nombreArchivo);
                                }
                                ?>
                            </div>
                        </div>

                        <div class="mb-8" id="contenedor-imagen-actual" style="<?= ($rutaImagen) ? 'display:block;' : 'display:none;' ?>">
                            <label class="form-label fw-bolder">Imagen Actual:</label>
                            <div>
                                <img id="imagen-previsualizacion" src="<?= ($rutaImagen) ? '/SISPROCON_UG' . htmlspecialchars($rutaImagen) : '#' ?>" alt="Logo del Proyecto" style="max-width: 200px; height: auto; border: 1px solid #ccc; padding: 5px; border-radius: 5px;">
                            </div>
                        </div>
                        <div class="form-group mb-8">
                            <?php $fieldName = 'externa_repres_nombre';
                            $valorPrevio = $datos_enviados[$fieldName] ?? ''; ?>
                            <label class="form-label fw-bolder">Nombre del representante legal:</label>
                            <input type="text" name="externa_repres_nombre" class="form-control form-control-solid <?= !empty($errores[$fieldName]) ? 'is-invalid' : '' ?>" value="<?= htmlspecialchars($valorPrevio) ?>" placeholder="Ingrese el representante" required>
                            <?php if (!empty($errores[$fieldName])): ?><div class="text-danger small mt-1"><?= htmlspecialchars($errores[$fieldName]) ?></div><?php endif; ?>
                        </div>
                        <div class="form-group mb-8">
                            <?php $fieldName = 'externa_dir';
                            $valorPrevio = $datos_enviados[$fieldName] ?? ''; ?>
                            <label class="form-label fw-bolder">Dirección:</label>
                            <input type="text" name="externa_dir" class="form-control form-control-solid <?= !empty($errores[$fieldName]) ? 'is-invalid' : '' ?>" value="<?= htmlspecialchars($valorPrevio) ?>" placeholder="Ingrese la dirección" required>
                            <?php if (!empty($errores[$fieldName])): ?><div class="text-danger small mt-1"><?= htmlspecialchars($errores[$fieldName]) ?></div><?php endif; ?>
                        </div>
                        <div class="form-group mb-8">
                            <?php $fieldName = 'externa_tel';
                            $valorPrevio = $datos_enviados[$fieldName] ?? ''; ?>
                            <label class="form-label fw-bolder">Teléfono:</label>
                            <input type="tel" name="externa_tel" class="form-control form-control-solid <?= !empty($errores[$fieldName]) ? 'is-invalid' : '' ?>" value="<?= htmlspecialchars($valorPrevio) ?>" placeholder="Ingrese el teléfono" required maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                            <?php if (!empty($errores[$fieldName])): ?><div class="text-danger small mt-1"><?= htmlspecialchars($errores[$fieldName]) ?></div><?php endif; ?>
                        </div>
                        <div class="form-group mb-8">
                            <?php $fieldName = 'externa_correo';
                            $valorPrevio = $datos_enviados[$fieldName] ?? ''; ?>
                            <label class="form-label fw-bolder">Correo</label>
                            <input type="email" name="externa_correo" class="form-control form-control-solid <?= !empty($errores[$fieldName]) ? 'is-invalid' : '' ?>" value="<?= htmlspecialchars($valorPrevio) ?>" placeholder="Ingrese el correo" required>
                            <?php if (!empty($errores[$fieldName])): ?><div class="text-danger small mt-1"><?= htmlspecialchars($errores[$fieldName]) ?></div><?php endif; ?>
                        </div>
                        <div class="form-group mb-8">
                            <?php $fieldName = 'externa_web';
                            $valorPrevio = $datos_enviados[$fieldName] ?? ''; ?>
                            <label class="form-label fw-bolder">Página Web:</label>
                            <input type="text" name="externa_web" class="form-control form-control-solid" value="<?= htmlspecialchars($valorPrevio) ?>" placeholder="Ingrese la página web">
                        </div>

                        <h2 class="fs-5 fw-bolder text-gray-800 mb-5 mt-10">2.3 Otras Unidades Académicas Cooperantes</h2>
                        <div class="table-responsive mb-1">
                            <table class="table table-row-bordered table-rounded gy-5 gs-5 w-100" id="tablaUnidadesCooperantes">
                                <thead>
                                    <tr class="text-start text-gray-800 fw-bold fs-7 text-uppercase gs-0 bg-gray-100">
                                        <th class="w-150px">Facultad</th>
                                        <th class="w-150px">Carrera</th>
                                        <th class="w-150px">Docente responsable</th>
                                        <th class="w-200px">Correo</th>
                                        <th class="w-150px">Teléfono</th>
                                        <th class="w-50px text-start">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="cuerpoTablaUnidadesCooperantes">
                                    <?php
                                    $cooperantesGuardados = $datos_enviados['facultad_coop'] ?? [];
                                    if (!empty($cooperantesGuardados)):
                                        foreach ($cooperantesGuardados as $key => $facultadId):
                                            $carreraId = $datos_enviados['carrera_coop'][$key] ?? '';
                                            $docenteId = $datos_enviados['docente_coop'][$key] ?? '';
                                            $correo = $datos_enviados['correo'][$key] ?? '';
                                            $telefono = $datos_enviados['telefono'][$key] ?? '';

                                            // Obtener las listas precargadas por el controlador
                                            $carreras_posibles = $datos_enviados['carreras_coop_posibles'][$key] ?? [];
                                            $docentes_posibles = $datos_enviados['docentes_coop_posibles'][$key] ?? [];
                                    ?>
                                            <tr>
                                                <td>
                                                    <select name="facultad_coop[]" class="form-select form-select-solid fs-7">
                                                        <option value="">Seleccione</option>
                                                        <?php foreach ($facultadData['dtResultado'] as $facultad): ?>
                                                            <option value="<?= htmlspecialchars($facultad['CodFacultad']) ?>" <?= ($facultadId == $facultad['CodFacultad']) ? 'selected' : '' ?>><?= htmlspecialchars($facultad['Facultad']) ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </td>
                                                <td>
                                                    <select name="carrera_coop[]" class="form-select form-select-solid fs-7" data-selected-value="<?= htmlspecialchars($carreraId) ?>">
                                                        <option value="">Seleccione</option>
                                                        <?php foreach ($carreras_posibles as $carrera): ?>
                                                            <option value="<?= htmlspecialchars($carrera['CodCarrera']) ?>" <?= ($carreraId == $carrera['CodCarrera']) ? 'selected' : '' ?>><?= htmlspecialchars($carrera['Carrera']) ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </td>
                                                <td>
                                                    <select name="docente_coop[]" class="form-select form-select-solid fs-7" data-selected-value="<?= htmlspecialchars($docenteId) ?>">
                                                        <option value="">Seleccione</option>
                                                        <?php foreach ($docentes_posibles as $docente): ?>
                                                            <option value="<?= htmlspecialchars($docente['CedulaDocente']) ?>" <?= ($docenteId == $docente['CedulaDocente']) ? 'selected' : '' ?>><?= htmlspecialchars($docente['Nombres']) ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </td>
                                                <td><input type="email" name="correo[]" class="form-control form-control-solid fs-7" value="<?= htmlspecialchars($correo) ?>" placeholder="Correo" /></td>
                                                <td><input type="tel" name="telefono[]" class="form-control form-control-solid fs-7" value="<?= htmlspecialchars($telefono) ?>" placeholder="Teléfono" maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '')" /></td>
                                                <td><button type="button" class="btn btn-icon btn-danger btn-sm removeRowBtn"><i class="bi bi-trash fs-5"></i></button></td>
                                            </tr>
                                    <?php endforeach;
                                    endif; ?>
                                </tbody>
                            </table>
                        </div>
                        <button type="button" class="btn btn-primary" id="addUnidadEjecutoraRowBtn"><i class="bi bi-plus-lg fs-4 me-2"></i>Agregar Cooperante</button>

                        <template id="unidadcooperanteRowTemplate">
                            <tr>
                                <td>
                                    <select name="facultad_coop[]" class="form-select form-select-solid fs-7">
                                        <option value="">Seleccione una Facultad</option>
                                        <?php if (isset($facultadData) && !empty($facultadData['dtResultado'])): foreach ($facultadData['dtResultado'] as $facultad): ?>
                                                <option value="<?= htmlspecialchars($facultad['CodFacultad']) ?>"><?= htmlspecialchars($facultad['Facultad']) ?></option>
                                        <?php endforeach;
                                        endif; ?>
                                    </select>
                                </td>
                                <td><select name="carrera_coop[]" class="form-select form-select-solid fs-7">
                                        <option value="">Seleccione Carrera</option>
                                    </select></td>
                                <td><select name="docente_coop[]" class="form-select form-select-solid fs-7">
                                        <option value="">Seleccione Docente</option>
                                    </select></td>
                                <td><input type="email" name="correo[]" class="form-control form-control-solid fs-7" placeholder="Correo institucional" /></td>
                                <td><input type="tel" name="telefono[]" class="form-control form-control-solid fs-7" placeholder="Telefono" maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '')" /></td>
                                <td><button type="button" class="btn btn-icon btn-danger btn-sm removeRowBtn"><i class="bi bi-trash fs-5"></i></button></td>
                            </tr>
                        </template>

                        <h2 class="fs-5 fw-bolder text-gray-800 mb-5 mt-10">2.4 Aliado Estratégico</h2>
                        <div class="form-group mb-8">
                            <?php $fieldName = 'aliado_nombre';
                            $valorPrevio = $datos_enviados[$fieldName] ?? ''; ?>
                            <label class="form-label fw-bolder">Nombre de la institución:</label>
                            <input type="text" name="aliado_nombre" class="form-control form-control-solid <?= !empty($errores[$fieldName]) ? 'is-invalid' : '' ?>" value="<?= htmlspecialchars($valorPrevio) ?>" placeholder="Ingrese el nombre" required>
                            <?php if (!empty($errores[$fieldName])): ?><div class="text-danger small mt-1"><?= htmlspecialchars($errores[$fieldName]) ?></div><?php endif; ?>
                        </div>
                        <div class="form-group mb-8">
                            <?php $fieldName = 'aliado_repres_nombre';
                            $valorPrevio = $datos_enviados[$fieldName] ?? ''; ?>
                            <label class="form-label fw-bolder">Nombre del representante legal:</label>
                            <input type="text" name="aliado_repres_nombre" class="form-control form-control-solid <?= !empty($errores[$fieldName]) ? 'is-invalid' : '' ?>" value="<?= htmlspecialchars($valorPrevio) ?>" placeholder="Ingrese el representante" required>
                            <?php if (!empty($errores[$fieldName])): ?><div class="text-danger small mt-1"><?= htmlspecialchars($errores[$fieldName]) ?></div><?php endif; ?>
                        </div>
                        <div class="form-group mb-8">
                            <?php $fieldName = 'aliado_direccion';
                            $valorPrevio = $datos_enviados[$fieldName] ?? ''; ?>
                            <label class="form-label fw-bolder">Dirección:</label>
                            <input type="text" name="aliado_direccion" class="form-control form-control-solid <?= !empty($errores[$fieldName]) ? 'is-invalid' : '' ?>" value="<?= htmlspecialchars($valorPrevio) ?>" placeholder="Ingrese la dirección" required>
                            <?php if (!empty($errores[$fieldName])): ?><div class="text-danger small mt-1"><?= htmlspecialchars($errores[$fieldName]) ?></div><?php endif; ?>
                        </div>
                        <div class="form-group mb-8">
                            <?php $fieldName = 'aliado_tel';
                            $valorPrevio = $datos_enviados[$fieldName] ?? ''; ?>
                            <label class="form-label fw-bolder">Teléfono:</label>
                            <input type="tel" name="aliado_tel" class="form-control form-control-solid <?= !empty($errores[$fieldName]) ? 'is-invalid' : '' ?>" value="<?= htmlspecialchars($valorPrevio) ?>" placeholder="Teléfono" required maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                            <?php if (!empty($errores[$fieldName])): ?><div class="text-danger small mt-1"><?= htmlspecialchars($errores[$fieldName]) ?></div><?php endif; ?>
                        </div>
                        <div class="form-group mb-8">
                            <?php $fieldName = 'aliado_correo';
                            $valorPrevio = $datos_enviados[$fieldName] ?? ''; ?>
                            <label class="form-label fw-bolder">Correo electrónico:</label>
                            <input type="email" name="aliado_correo" class="form-control form-control-solid <?= !empty($errores[$fieldName]) ? 'is-invalid' : '' ?>" value="<?= htmlspecialchars($valorPrevio) ?>" placeholder="Correo electrónico" required>
                            <?php if (!empty($errores[$fieldName])): ?><div class="text-danger small mt-1"><?= htmlspecialchars($errores[$fieldName]) ?></div><?php endif; ?>
                        </div>
                        <div class="form-group mb-8">
                            <?php $fieldName = 'aliado_web';
                            $valorPrevio = $datos_enviados[$fieldName] ?? ''; ?>
                            <label class="form-label fw-bolder">Página Web:</label>
                            <input type="text" name="aliado_web" class="form-control form-control-solid" value="<?= htmlspecialchars($valorPrevio) ?>" placeholder="Ingrese la página web">
                        </div>
                        <div class="form-group mb-8">
                            <?php $fieldName = 'aliado_contribucion';
                            $valorPrevio = $datos_enviados[$fieldName] ?? ''; ?>
                            <label class="form-label fw-bolder">Describa la contribución por parte del aliado estratégico:</label>
                            <textarea name="aliado_contribucion" class="form-control form-control-solid" rows="1"><?= htmlspecialchars($valorPrevio) ?></textarea>
                        </div>
                    </div>

                    <div class="card-footer d-flex justify-content-end py-6 px-9">
                        <a href="?c=Formularios&m=paso3" id="btnAnterior" class="btn btn-secondary me-2">Anterior</a>
                        <button type="submit" class="btn btn-primary">Siguiente</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    /**
     * Función universal para poblar un select de forma asíncrona.
     */
    async function poblarSelect(selectElement, url, placeholder, valorGuardado = null) {
        if (!selectElement) return; // Guarda de seguridad para evitar errores
        selectElement.innerHTML = `<option value="">Cargando...</option>`;

        // Si no hay una URL válida (ej. no se seleccionó la facultad), limpiar y poner el placeholder.
        if (!url || url.endsWith('=')) {
            selectElement.innerHTML = `<option value="">${placeholder}</option>`;
            return;
        }

        try {
            const response = await fetch(url);
            if (!response.ok) throw new Error(`Error HTTP: ${response.status}`);
            const data = await response.json();

            selectElement.innerHTML = `<option value="">${placeholder}</option>`;

            const results = data.carreras || data.docentes || [];
            results.forEach(item => {
                const option = document.createElement('option');
                option.value = item.CodCarrera || item.CedulaDocente;
                option.textContent = item.Carrera || item.Nombres;
                selectElement.appendChild(option);
            });

            // Si se pasó un valor para pre-seleccionar, lo usamos.
            if (valorGuardado && selectElement.querySelector(`option[value="${valorGuardado}"]`)) {
                selectElement.value = valorGuardado;
            }
        } catch (error) {
            console.error('Error al poblar el select:', error);
            selectElement.innerHTML = `<option value="">Error al cargar</option>`;
        }
    }

    // --- LÓGICA PRINCIPAL ---
    document.addEventListener("DOMContentLoaded", function() {

        // --- LÓGICA DE AÑADIR/ELIMINAR FILAS (Sin cambios) ---
        document.getElementById('addDirectorProyectoRowBtn').addEventListener('click', function() {
            const tbody = document.getElementById('cuerpoTablaDirectoresProyecto');
            const template = document.getElementById('directorProyectoRowTemplate').content.cloneNode(true);
            tbody.appendChild(template);
        });
        // Asumiendo que tienes un botón para la otra tabla. Si no, puedes quitar esto.
        const addCooperanteBtn = document.getElementById('addUnidadEjecutoraRowBtn');
        if (addCooperanteBtn) {
            addCooperanteBtn.addEventListener('click', function() {
                const tbody = document.getElementById('cuerpoTablaUnidadesCooperantes');
                const template = document.getElementById('unidadcooperanteRowTemplate').content.cloneNode(true);
                tbody.appendChild(template);
            });
        }
        document.querySelector('.form').addEventListener("click", function(e) {
            if (e.target.closest(".removeRowBtn")) {
                e.target.closest("tr").remove();
            }
        });

        // --- PREVISUALIZACIÓN DE IMAGEN ---
        const fileInput = document.getElementById('logo_proyecto');
        const previewImage = document.getElementById('imagen-previsualizacion');
        const previewContainer = document.getElementById('contenedor-imagen-actual');
        const fileNameDisplay = document.getElementById('nombre-archivo-actual');

        if (fileInput) {
            fileInput.addEventListener('change', function(event) {
                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewImage.src = e.target.result;
                        previewContainer.style.display = 'block';
                        fileNameDisplay.innerHTML = `<span class="text-muted fw-bolder">Archivo nuevo:</span> ${file.name}`;
                    };
                    reader.readAsDataURL(file);
                }
            });
        }

        // --- DELEGACIÓN DE EVENTOS PARA CAMBIOS DEL USUARIO ---
        document.querySelector('.form').addEventListener('change', async function(e) {
            const select = e.target;

            // Lógica para la sección 2.1 (Decano) - Se mantiene igual
            if (select.id === 'facultad_decano') {
                const carreraSelect = document.getElementById('carrera_decano');
                await poblarSelect(carreraSelect, `?c=Institucion&m=getCarreras&facultad=${select.value}`, 'Seleccione una Carrera');
                return;
            }

            // Lógica para las tablas dinámicas
            const parentRow = select.closest('tr');
            if (!parentRow) return;

            // Si cambia una FACULTAD en las tablas...
            if (select.matches('select[name="facultad_director[]"], select[name="facultad_coop[]"]')) {
                const carreraSelect = parentRow.querySelector('select[name^="carrera_"]');
                const docenteSelect = parentRow.querySelector('select[name^="nombre_director[]"], select[name^="docente_coop[]"]');

                if (docenteSelect) docenteSelect.innerHTML = '<option value="">Primero seleccione Carrera</option>';

                await poblarSelect(carreraSelect, `?c=Institucion&m=getCarreras&facultad=${select.value}`, 'Seleccione Carrera');
            }

            // Si cambia una CARRERA en las tablas...
            if (select.matches('select[name="carrera_director[]"], select[name="carrera_coop[]"]')) {
                const facultadSelect = parentRow.querySelector('select[name^="facultad_"]');
                const docenteSelect = parentRow.querySelector('select[name^="nombre_director[]"], select[name^="docente_coop[]"]');
                let placeholder = 'Seleccione Docente';
                if (docenteSelect && docenteSelect.name.includes('nombre_director')) {
                    placeholder = 'Seleccione Director';
                }
                await poblarSelect(docenteSelect, `?c=Institucion&m=getDocentes&facultad=${facultadSelect.value}&carrera=${select.value}`, placeholder);
            }
        });

        // --- LÓGICA DE RECARGA SINCRONIZADA AL CARGAR LA PÁGINA ---
        // ¡Esta sección se deja vacía intencionadamente!
        // El servidor (PHP) ahora se encarga de renderizar el estado inicial de todos los selects,
        // por lo que JavaScript ya no necesita hacerlo al cargar la página. Esto evita conflictos.
    });
</script>

<?php require_once ROOT_PATH . '/app/views/layout/footer.php'; ?>