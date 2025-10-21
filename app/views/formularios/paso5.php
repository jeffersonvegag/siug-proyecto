<?php require_once ROOT_PATH . '/app/views/layout/header.php';

$pasoActual = 5;
require_once ROOT_PATH . '/app/views/layout/stepper_static.php';
?>

<div class="d-flex flex-column flex-column-fluid">
    <div class="app-content flex-column-fluid">
        <div>
            <form method="POST" action="?c=Formularios&m=paso5" class="form" id="formPaso5" novalidate>
                <div class="card card-custom gutter-b">
                    <div class="card-header bg-dark  py-6 ">
                        <div class="card-title">
                            <h3 class="card-label text-white fw-bolder fs-2">5. EQUIPO DEL PROYECTO</h3>
                        </div>
                    </div>

                    <div class="card-body">

                        <h2 class="fs-4 fw-bolder mb-5 ">3.1 Grupo de Docentes</h2>

                        <h4 class="fs-6 fw-bolder mt-4">3.1.1 Director del proyecto:</h4>
                        <?php
                        $autoFilled = !empty($datos_director_precargados);
                        ?>


                        <?php if ($autoFilled): ?>
                            <div class="row">
                                <div class="col-md-4 mb-8">
                                    <div class="form-group">
                                        <?php
                                        $fieldName = 'nombre_director';
                                        $valorPrevio = $datos_enviados[$fieldName] ?? ($datos_director_precargados['nombre'] ?? '');
                                        ?>
                                        <label for="nombre_director" class="form-label fw-bolder">Nombre del Director:</label>
                                        <input type="text" id="nombre_director_display" class="form-control form-control-solid" value="<?= htmlspecialchars($valorPrevio) ?>" disabled>
                                        <input type="hidden" name="nombre_director" value="<?= htmlspecialchars($valorPrevio) ?>">
                                        <?php if (!empty($errores[$fieldName])): ?><div class="text-danger small mt-1"><?= htmlspecialchars($errores[$fieldName]) ?></div><?php endif; ?>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-8">
                                    <div class="form-group">
                                        <?php
                                        $fieldName = 'cedula_director';
                                        $valorPrevio = $datos_enviados[$fieldName] ?? ($datos_director_precargados['cedula_director'] ?? '');
                                        ?>
                                        <label for="cedula_director" class="form-label fw-bolder">Cédula:</label>
                                        <input type="text" id="cedula_director_display" class="form-control form-control-solid" value="<?= htmlspecialchars($valorPrevio) ?>" disabled>
                                        <input type="hidden" name="cedula_director" value="<?= htmlspecialchars($valorPrevio) ?>">
                                        <?php if (!empty($errores[$fieldName])): ?><div class="text-danger small mt-1"><?= htmlspecialchars($errores[$fieldName]) ?></div><?php endif; ?>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-8">
                                    <div class="form-group">
                                        <?php
                                        $fieldName = 'acreditado_director';
                                        $valorPrevio = $datos_enviados[$fieldName] ?? '';
                                        ?>
                                        <label for="acreditado_director" class="form-label fw-bolder">Acreditado:</label>
                                        <select id="acreditado_director" name="acreditado_director" class="form-select form-select-solid <?= !empty($errores[$fieldName]) ? 'is-invalid' : '' ?>" required>
                                            <option value="">SELECCIONE</option>
                                            <option value="si" <?= ($valorPrevio == 'si') ? 'selected' : '' ?>>Sí</option>
                                            <option value="no" <?= ($valorPrevio == 'no') ? 'selected' : '' ?>>No</option>
                                        </select>
                                        <?php if (!empty($errores[$fieldName])): ?><div class="text-danger small mt-1"><?= htmlspecialchars($errores[$fieldName]) ?></div><?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 mb-8">
                                    <div class="form-group">
                                        <?php
                                        $fieldName = 'categoria_director';
                                        $valorPrevio = $datos_enviados[$fieldName] ?? '';
                                        ?>
                                        <label for="categoria_director" class="form-label fw-bolder">Categoría:</label>
                                        <input type="text" id="categoria_director" name="categoria_director" class="form-control form-control-solid <?= !empty($errores[$fieldName]) ? 'is-invalid' : '' ?>" value="<?= htmlspecialchars($valorPrevio) ?>" required>
                                        <?php if (!empty($errores[$fieldName])): ?><div class="text-danger small mt-1"><?= htmlspecialchars($errores[$fieldName]) ?></div><?php endif; ?>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-8">
                                    <div class="form-group">
                                        <?php
                                        $fieldName = 'dedicacion_director';
                                        $valorPrevio = $datos_enviados[$fieldName] ?? '';
                                        ?>
                                        <label for="dedicacion_director" class="form-label fw-bolder">Dedicación:</label>
                                        <input type="text" id="dedicacion_director" name="dedicacion_director" class="form-control form-control-solid <?= !empty($errores[$fieldName]) ? 'is-invalid' : '' ?>" value="<?= htmlspecialchars($valorPrevio) ?>" required>
                                        <?php if (!empty($errores[$fieldName])): ?><div class="text-danger small mt-1"><?= htmlspecialchars($errores[$fieldName]) ?></div><?php endif; ?>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-8">
                                    <div class="form-group">
                                        <?php
                                        $fieldName = 'correo_director';
                                        $valorPrevio = $datos_enviados[$fieldName] ?? ($datos_director_precargados['correo'] ?? '');
                                        ?>
                                        <label for="correo_director" class="form-label fw-bolder">Correo</label>
                                        <input type="email" id="correo_director_display" class="form-control form-control-solid" value="<?= htmlspecialchars($valorPrevio) ?>" disabled />
                                        <input type="hidden" name="correo_director" value="<?= htmlspecialchars($valorPrevio) ?>">
                                        <?php if (!empty($errores[$fieldName])): ?><div class="text-danger small mt-1"><?= htmlspecialchars($errores[$fieldName]) ?></div><?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 mb-8">
                                    <div class="form-group mb-8">
                                        <?php
                                        $fieldName = 'telefono_director';
                                        $valorPrevio = $datos_enviados[$fieldName] ?? ($datos_director_precargados['telefono'] ?? '');
                                        ?>
                                        <label for="telefono_director" class="form-label fw-bolder">Teléfono</label>
                                        <input type="tel" id="telefono_director_display" class="form-control form-control-solid" value="<?= htmlspecialchars($valorPrevio) ?>" disabled />
                                        <input type="hidden" name="telefono_director" value="<?= htmlspecialchars($valorPrevio) ?>">
                                        <?php if (!empty($errores[$fieldName])): ?><div class="text-danger small mt-1"><?= htmlspecialchars($errores[$fieldName]) ?></div><?php endif; ?>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-8">
                                    <div class="form-group">
                                        <label for="facultad_director_display" class="form-label fw-bolder">Facultad:</label>
                                        <input type="text" id="facultad_director_display" class="form-control form-control-solid" disabled value="<?= htmlspecialchars($datos_director_precargados['facultad_nombre'] ?? '') ?>">
                                        <input type="hidden" name="facultad_director_paso5" value="<?= htmlspecialchars($datos_director_precargados['facultad_id'] ?? '') ?>">
                                    </div>
                                </div>
                                <div class="col-md-4 mb-8">
                                    <div class="form-group">
                                        <label for="carrera_director_display" class="form-label fw-bolder">Carrera:</label>
                                        <input type="text" id="carrera_director_display" class="form-control form-control-solid" disabled value="<?= htmlspecialchars($datos_director_precargados['carrera_nombre'] ?? '') ?>">
                                        <input type="hidden" name="carrera_director_paso5" value="<?= htmlspecialchars($datos_director_precargados['carrera_id'] ?? '') ?>">
                                    </div>
                                </div>
                            </div>

                        <?php else: ?>
                            <div class="alert alert-info">
                                No se encontró un director de proyecto coincidente. Por favor, llene los datos manualmente en la tabla de Docentes Tutores.
                            </div>
                            
                        <?php endif; ?>

                        <h4 class="fs-5 fw-bolder text-gray-800 mb-5">3.1.2 Docentes Tutores de Servicio Comunitario:</h4>
                        <?php if (!empty($errores['tutores_general'])): ?><div class="alert alert-danger small p-2 mt-2"><?= htmlspecialchars($errores['tutores_general']) ?></div><?php endif; ?>
                        <div class="table-responsive mb-1">
                            <table class="table table-row-bordered table-rounded gy-5 gs-5 w-100" id="tabla_docentes">
                                <thead>
                                    <tr class="text-start text-gray-800 fw-bold fs-7 text-uppercase gs-0 bg-gray-100">
                                        <th class="w-150px">Facultad</th>
                                        <th class="w-150px">Carrera</th>
                                        <th class="w-150px">Nombre</th>
                                        <th class="w-150px">Cédula</th>
                                        <th class="w-150px">Acreditado</th>
                                        <th class="w-150px">Categoría</th>
                                        <th class="w-150px">Dedicación</th>
                                        <th class="w-150px">Correo</th>
                                        <th class="w-150px">Teléfono</th>
                                        <th class="w-50px text-start text-nowrap">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="cuerpoTablaTutores">
                                    <?php
                                    $tutoresGuardados = $datos_enviados['docente_cedula'] ?? [];
                                    if (!empty($tutoresGuardados)):
                                        foreach ($tutoresGuardados as $key => $cedula):
                                            $nombre = $datos_enviados['docente_tutor'][$key] ?? '';
                                            $facultadId = $datos_enviados['facultad_tutor'][$key] ?? '';
                                            $carreraId = $datos_enviados['carrera_tutor'][$key] ?? '';
                                            $acreditado = $datos_enviados['docente_acreditado'][$key] ?? '';
                                            $categoria = $datos_enviados['docente_categoria'][$key] ?? '';
                                            $dedicacion = $datos_enviados['docente_dedicacion'][$key] ?? '';
                                            $correo = $datos_enviados['docente_correo'][$key] ?? '';
                                            $telefono = $datos_enviados['docente_telefono'][$key] ?? '';
                                    ?>
                                            <tr>
                                                <td>
                                                    <select name="facultad_tutor[]" class="form-select form-select-solid fs-7 <?= !empty($errores['facultad_tutor'][$key]) ? 'is-invalid' : '' ?>" onchange="cargarCarrerasTutores(this.value, this.closest('tr'));" required>
                                                        <option value="">Seleccione</option>
                                                        <?php foreach ($facultadData['dtResultado'] as $facultad): ?>
                                                            <option value="<?= htmlspecialchars($facultad['CodFacultad']) ?>" <?= ($facultadId == $facultad['CodFacultad']) ? 'selected' : '' ?>><?= htmlspecialchars($facultad['Facultad']) ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                    <?php if (!empty($errores['facultad_tutor'][$key])): ?><div class="text-danger small mt-1"><?= htmlspecialchars($errores['facultad_tutor'][$key]) ?></div><?php endif; ?>
                                                </td>
                                                <td>
                                                    <select name="carrera_tutor[]" class="form-select form-select-solid fs-7 <?= !empty($errores['carrera_tutor'][$key]) ? 'is-invalid' : '' ?>" onchange="cargarDocentesTutores(this.value, this.closest('tr'));" data-selected-value="<?= htmlspecialchars($carreraId) ?>" required>
                                                        <option value="">Seleccione</option>
                                                        <?php
                                                        $carreras_posibles = $carreras_tutor_posibles[$key] ?? [];
                                                        foreach ($carreras_posibles as $carrera): ?>
                                                            <option value="<?= htmlspecialchars($carrera['CodCarrera']) ?>" <?= ($carreraId == $carrera['CodCarrera']) ? 'selected' : '' ?>><?= htmlspecialchars($carrera['Carrera']) ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                    <?php if (!empty($errores['carrera_tutor'][$key])): ?><div class="text-danger small mt-1"><?= htmlspecialchars($errores['carrera_tutor'][$key]) ?></div><?php endif; ?>
                                                </td>
                                                <td>
                                                    <select name="docente_tutor[]" class="form-select form-select-solid fs-7 <?= !empty($errores['docente_tutor'][$key]) ? 'is-invalid' : '' ?>" onchange="actualizarCedulaDocente(this);" data-selected-value="<?= htmlspecialchars($nombre) ?>" required>
                                                        <option value="">Seleccione</option>
                                                        <?php
                                                        $docentes_posibles = $docentes_tutor_posibles[$key] ?? [];
                                                        foreach ($docentes_posibles as $docente): ?>
                                                            <option value="<?= htmlspecialchars($docente['Nombres']) ?>" data-cedula="<?= htmlspecialchars($docente['CedulaDocente']) ?>" <?= ($nombre == $docente['Nombres']) ? 'selected' : '' ?>><?= htmlspecialchars($docente['Nombres']) ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                    <?php if (!empty($errores['docente_tutor'][$key])): ?><div class="text-danger small mt-1"><?= htmlspecialchars($errores['docente_tutor'][$key]) ?></div><?php endif; ?>
                                                </td>
                                                <td>
                                                    <input type="text" name="docente_cedula[]" class="form-control form-control-solid" value="<?= htmlspecialchars($cedula) ?>" readonly>
                                                    <?php if (!empty($errores['docente_cedula'][$key])): ?><div class="text-danger small mt-1"><?= htmlspecialchars($errores['docente_cedula'][$key]) ?></div><?php endif; ?>
                                                </td>
                                                <td>
                                                    <select name="docente_acreditado[]" class="form-select form-select-solid fs-7 <?= !empty($errores['docente_acreditado'][$key]) ? 'is-invalid' : '' ?>" required>
                                                        <option value="">SELECCIONE</option>
                                                        <option value="si" <?= ($acreditado == 'si') ? 'selected' : '' ?>>Sí</option>
                                                        <option value="no" <?= ($acreditado == 'no') ? 'selected' : '' ?>>No</option>
                                                    </select>
                                                    <?php if (!empty($errores['docente_acreditado'][$key])): ?><div class="text-danger small mt-1"><?= htmlspecialchars($errores['docente_acreditado'][$key]) ?></div><?php endif; ?>
                                                </td>
                                                <td>
                                                    <input type="text" name="docente_categoria[]" class="form-control form-control-solid <?= !empty($errores['docente_categoria'][$key]) ? 'is-invalid' : '' ?>" value="<?= htmlspecialchars($categoria) ?>" required>
                                                    <?php if (!empty($errores['docente_categoria'][$key])): ?><div class="text-danger small mt-1"><?= htmlspecialchars($errores['docente_categoria'][$key]) ?></div><?php endif; ?>
                                                </td>
                                                <td>
                                                    <input type="text" name="docente_dedicacion[]" class="form-control form-control-solid <?= !empty($errores['docente_dedicacion'][$key]) ? 'is-invalid' : '' ?>" value="<?= htmlspecialchars($dedicacion) ?>" required>
                                                    <?php if (!empty($errores['docente_dedicacion'][$key])): ?><div class="text-danger small mt-1"><?= htmlspecialchars($errores['docente_dedicacion'][$key]) ?></div><?php endif; ?>
                                                </td>
                                                <td>
                                                    <input type="email" name="docente_correo[]" class="form-control form-control-solid fs-7 <?= !empty($errores['docente_correo'][$key]) ? 'is-invalid' : '' ?>" value="<?= htmlspecialchars($correo) ?>" required />
                                                    <?php if (!empty($errores['docente_correo'][$key])): ?><div class="text-danger small mt-1"><?= htmlspecialchars($errores['docente_correo'][$key]) ?></div><?php endif; ?>
                                                </td>
                                                <td>
                                                    <input type="tel" name="docente_telefono[]" class="form-control form-control-solid fs-7 <?= !empty($errores['docente_telefono'][$key]) ? 'is-invalid' : '' ?>" value="<?= htmlspecialchars($telefono) ?>" required maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '')" />
                                                    <?php if (!empty($errores['docente_telefono'][$key])): ?><div class="text-danger small mt-1"><?= htmlspecialchars($errores['docente_telefono'][$key]) ?></div><?php endif; ?>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-icon btn-danger btn-sm removeRowBtn">
                                                        <i class="bi bi-trash fs-5"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                    <?php endforeach;
                                    endif; ?>
                                </tbody>
                            </table>
                        </div>

                        <button type="button" class="btn btn-primary" id="addTutorRowBtn">
                            <i class="bi bi-plus-lg fs-4 me-2"></i>Agregar
                        </button>

                        <template id="TutorRowTemplate">
                            <tr>
                                <td>
                                    <select name="facultad_tutor[]" class="form-select form-select-solid fs-7" onchange="cargarCarrerasTutores(this.value, this.closest('tr'))" required>
                                        <option value="">Seleccione</option>
                                        <?php if (isset($facultadData) && is_array($facultadData) && !empty($facultadData['dtResultado'])):
                                            foreach ($facultadData['dtResultado'] as $facultad): ?>
                                                <option value="<?= htmlspecialchars($facultad['CodFacultad']) ?>"><?= htmlspecialchars($facultad['Facultad']) ?></option>
                                        <?php endforeach;
                                        endif; ?>
                                    </select>
                                </td>
                                <td>
                                    <select name="carrera_tutor[]" class="form-select form-select-solid fs-7" onchange="cargarDocentesTutores(this.value, this.closest('tr'))" required>
                                        <option value="">Seleccione</option>
                                    </select>
                                </td>
                                <td>
                                    <select name="docente_tutor[]" class="form-select form-select-solid fs-7" onchange="actualizarCedulaDocente(this);" required>
                                        <option value="">Seleccione</option>
                                    </select>
                                </td>
                                <td><input type="text" name="docente_cedula[]" class="form-control form-control-solid" readonly></td>
                                <td>
                                    <select name="docente_acreditado[]" class="form-select form-select-solid fs-7" required>
                                        <option value="">SELECCIONE</option>
                                        <option value="si">Sí</option>
                                        <option value="no">No</option>
                                    </select>
                                </td>
                                <td><input type="text" name="docente_categoria[]" class="form-control form-control-solid" required></td>
                                <td><input type="text" name="docente_dedicacion[]" class="form-control form-control-solid" required></td>
                                <td>
                                    <input type="email" name="docente_correo[]" class="form-control form-control-solid fs-7" required />
                                </td>
                                <td>
                                    <input type="tel" name="docente_telefono[]" class="form-control form-control-solid fs-7" required maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '')" />
                                </td>
                                <td>
                                    <button type="button" class="btn btn-icon btn-danger btn-sm removeRowBtn">
                                        <i class="bi bi-trash fs-5"></i>
                                    </button>
                                </td>
                            </tr>
                        </template>


                        <h4 class="fw-bolder mt-8">3.2 Grupo de Estudiantes</h4>
                        <h4 class="fw-bolder mt-4">3.2.1 Estudiantes que intervienen en el proyecto de vinculación con la sociedad:</h4>
                        <?php if (!empty($errores['estudiantes_general'])): ?><div class="alert alert-danger small p-2 mt-2"><?= htmlspecialchars($errores['estudiantes_general']) ?></div><?php endif; ?>
                        <div class="table-responsive">
                            <table class="table table-row-bordered table-rounded gy-5 gs-5 w-100" id="tabla_estudiantes">
                                <thead>
                                    <tr class="text-start text-gray-800 fw-bold fs-7 text-uppercase gs-0 bg-gray-100">
                                        <th class="w-200px">Facultad</th>
                                        <th class="w-200px">Carrera</th>
                                        <th class="w-50px">Número de estudiantes</th>
                                        <th class="w-50px text-start text-nowrap">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="cuerpoTablaEstud">
                                    <?php
                                    $estudiantesGuardados = $datos_enviados['facultad_estud'] ?? [];
                                    if (!empty($estudiantesGuardados)):
                                        foreach ($estudiantesGuardados as $key => $facultadId):
                                            $carreraId = $datos_enviados['carrera_estud'][$key] ?? '';
                                            $cantidad = $datos_enviados['estudiante_numero'][$key] ?? '';
                                            $carreras_posibles = $carreras_estud_posibles[$key] ?? [];
                                    ?>
                                            <tr>
                                                <td>
                                                    <?php $fieldName = "facultad_estud[$key]"; ?>
                                                    <select name="facultad_estud[]" class="form-select form-select-solid fs-7 <?= !empty($errores['facultad_estud'][$key]) ? 'is-invalid' : '' ?>" onchange="cargarCarrerasEstud(this.value, this.closest('tr'))" required>
                                                        <option value="">Seleccione</option>
                                                        <?php if (isset($facultadData) && is_array($facultadData) && !empty($facultadData['dtResultado'])):
                                                            foreach ($facultadData['dtResultado'] as $facultad): ?>
                                                                <option value="<?= htmlspecialchars($facultad['CodFacultad']) ?>" <?= ($facultadId == $facultad['CodFacultad']) ? 'selected' : '' ?>><?= htmlspecialchars($facultad['Facultad']) ?></option>
                                                        <?php endforeach;
                                                        endif; ?>
                                                    </select>
                                                    <?php if (!empty($errores['facultad_estud'][$key])): ?><div class="text-danger small mt-1"><?= htmlspecialchars($errores['facultad_estud'][$key]) ?></div><?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php $fieldName = "carrera_estud[$key]"; ?>
                                                    <select name="carrera_estud[]" class="form-select form-select-solid fs-7 <?= !empty($errores['carrera_estud'][$key]) ? 'is-invalid' : '' ?>" data-selected-value="<?= htmlspecialchars($carreraId) ?>" required>
                                                        <option value="">Seleccione</option>
                                                        <?php foreach ($carreras_posibles as $carrera): ?>
                                                            <option value="<?= htmlspecialchars($carrera['CodCarrera']) ?>" <?= ($carreraId == $carrera['CodCarrera']) ? 'selected' : '' ?>><?= htmlspecialchars($carrera['Carrera']) ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                    <?php if (!empty($errores['carrera_estud'][$key])): ?><div class="text-danger small mt-1"><?= htmlspecialchars($errores['carrera_estud'][$key]) ?></div><?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php $fieldName = "estudiante_numero[$key]"; ?>
                                                    <input type="text" inputmode="numeric" name="estudiante_numero[]" class="form-control estudiante-numero-input <?= !empty($errores['estudiante_numero'][$key]) ? 'is-invalid' : '' ?>" placeholder="Número de estudiantes" maxlength="10" value="<?= htmlspecialchars($cantidad) ?>" oninput="this.value = this.value.replace(/[^0-9]/g, '')" required>
                                                    <?php if (!empty($errores['estudiante_numero'][$key])): ?><div class="text-danger small mt-1"><?= htmlspecialchars($errores['estudiante_numero'][$key]) ?></div><?php endif; ?>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-icon btn-danger btn-sm removeRowBtn">
                                                        <i class="bi bi-trash fs-5"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                    <?php endforeach;
                                    endif; ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="2" style="font-weight: bold;">Total de estudiantes</td>
                                        <td><input type="number" id="total_estudiantes" class="form-control" disabled></td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <button type="button" class="btn btn-primary mb-4" id="btn-agregar-estudiante">
                            <i class="bi bi-plus-lg fs-4 me-2"></i>Agregar
                        </button>
                        <template id="EstudRowTemplate">
                            <tr>
                                <td>
                                    <select name="facultad_estud[]" class="form-select form-select-solid fs-7" onchange="cargarCarrerasEstud(this.value, this.closest('tr'))" required>
                                        <option value="">Seleccione</option>
                                        <?php if (isset($facultadData) && is_array($facultadData) && !empty($facultadData['dtResultado'])):
                                            foreach ($facultadData['dtResultado'] as $facultad): ?>
                                                <option value="<?= htmlspecialchars($facultad['CodFacultad']) ?>"><?= htmlspecialchars($facultad['Facultad']) ?></option>
                                        <?php endforeach;
                                        endif; ?>
                                    </select>
                                </td>
                                <td>
                                    <select name="carrera_estud[]" class="form-select form-select-solid fs-7" required>
                                        <option value="">Seleccione</option>
                                    </select>
                                </td>
                                <td>
                                    <input type="text" inputmode="numeric" name="estudiante_numero[]" class="form-control estudiante-numero-input" placeholder="Número de estudiantes" maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '')" required>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-icon btn-danger btn-sm removeRowBtn">
                                        <i class="bi bi-trash fs-5"></i>
                                    </button>
                                </td>
                            </tr>
                        </template>


                        <h4 class="fw-bolder mt-8">3.2.2 Estudiantes que intervienen en los programas de articulación:</h4>
                        <?php if (!empty($errores['programas_general'])): ?><div class="alert alert-danger small p-2 mt-2"><?= htmlspecialchars($errores['programas_general']) ?></div><?php endif; ?>
                        <div class="table-responsive">
                            <table class="table table-row-bordered table-rounded gy-5 gs-5 w-100" id="tabla_programas">
                                <thead>
                                    <tr class="text-start text-gray-800 fw-bold fs-7 text-uppercase gs-0 bg-gray-100">
                                        <th class="w-500px">Programa de articulación</th>
                                        <th class="w-500px">Número de estudiantes</th>
                                        <th class="w-50px">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="cuerpoTablaProgram">
                                    <?php
                                    $programasGuardados = $datos_enviados['programa_articulacion_nombre'] ?? [];
                                    if (!empty($programasGuardados)):
                                        foreach ($programasGuardados as $key => $programa):
                                            $cantidad = $datos_enviados['programa_articulacion_numero'][$key] ?? '';
                                    ?>
                                            <tr>
                                                <td>
                                                    <input type="text" name="programa_articulacion_nombre[]" class="form-control <?= !empty($errores['programa_articulacion_nombre'][$key]) ? 'is-invalid' : '' ?>" value="<?= htmlspecialchars($programa) ?>" required>
                                                    <?php if (!empty($errores['programa_articulacion_nombre'][$key])): ?><div class="text-danger small mt-1"><?= htmlspecialchars($errores['programa_articulacion_nombre'][$key]) ?></div><?php endif; ?>
                                                </td>
                                                <td>
                                                    <input type="text" inputmode="numeric" name="programa_articulacion_numero[]" class="form-control programa-numero-input <?= !empty($errores['programa_articulacion_numero'][$key]) ? 'is-invalid' : '' ?>" placeholder="Número de estudiantes" maxlength="10" value="<?= htmlspecialchars($cantidad) ?>" oninput="this.value = this.value.replace(/[^0-9]/g, '')" required>
                                                    <?php if (!empty($errores['programa_articulacion_numero'][$key])): ?><div class="text-danger small mt-1"><?= htmlspecialchars($errores['programa_articulacion_numero'][$key]) ?></div><?php endif; ?>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-icon btn-danger btn-sm removeRowBtn">
                                                        <i class="bi bi-trash fs-5"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                    <?php endforeach;
                                    endif; ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td style="font-weight: bold;">Total de estudiantes que intervienen en los programas</td>
                                        <td><input type="number" id="total_estudiantes_programas" class="form-control" disabled></td>
                                        <td></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <button type="button" class="btn btn-primary mb-4" id="addProgramaEstud">
                            <i class="bi bi-plus-lg fs-4 me-2"></i>Agregar
                        </button>
                        <template id="ProgrRowTemplate">
                            <tr>
                                <td><input type="text" name="programa_articulacion_nombre[]" class="form-control" required></td>
                                <td>
                                    <input type="text" inputmode="numeric" name="programa_articulacion_numero[]" class="form-control programa-numero-input" placeholder="Número de estudiantes" maxlength="10" oninput="this.value = this.value.replace(/[^0-9]/g, '')" required>
                                </td>
                                <td>
                                    <button type="button" class="btn btn-icon btn-danger btn-sm removeRowBtn">
                                        <i class="bi bi-trash fs-5"></i>
                                    </button>
                                </td>
                            </tr>
                        </template>



                        <h3 class="fs-4 fw-bolder mt-10">3.3.1 Estudiantes de Grado</h3>
                        <h4 class="fs-6 fw-bolder mb-5">3.3.1.1 Número de estudiantes por ciclo académico</h4>
                        <div id="tabla-ciclos-container">
                            <div class="row mb-3 fw-bolder text-gray-700">
                                <div class="col-md-3">Ciclo académico</div>
                                <div class="col-md-4">Número de estudiantes por ciclo académico</div>
                                <div class="col-md-4">Estudiantes con discapacidad</div>
                            </div>
                            <?php for ($i = 0; $i < 4; $i++):
                                $ciclo_num = $i + 1;
                                $num_estudiantes = $datos_enviados['estudiantes_ciclo_total'][$i] ?? '';
                                $num_discapacidad = $datos_enviados['estudiantes_ciclo_discapacidad'][$i] ?? '';
                            ?>
                                <div class="row mb-3 align-items-center">
                                    <div class="col-md-3">
                                        <label class="form-label"><?= $ciclo_num ?></label>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" inputmode="numeric" name="estudiantes_ciclo_total[]" class="form-control form-control-solid estudiantes-ciclo-total" placeholder="Número de estudiantes" maxlength="10" value="<?= htmlspecialchars($num_estudiantes) ?>" oninput="this.value = this.value.replace(/[^0-9]/g, '')" required>
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" inputmode="numeric" name="estudiantes_ciclo_discapacidad[]" class="form-control form-control-solid estudiantes-ciclo-discapacidad" placeholder="Número de estudiantes" maxlength="10" value="<?= htmlspecialchars($num_discapacidad) ?>" oninput="this.value = this.value.replace(/[^0-9]/g, '')" required>
                                    </div>
                                </div>
                            <?php endfor; ?>
                            <div class="row mb-3 align-items-center border-top pt-3 mt-3">
                                <div class="col-md-3">
                                    <label class="form-label fw-bolder">Total</label>
                                </div>
                                <div class="col-md-4">
                                    <input type="number" id="total_estudiantes_ciclo" class="form-control form-control-solid fw-bolder" readonly>
                                </div>
                                <div class="col-md-4">
                                    <input type="number" id="total_estudiantes_discapacidad" class="form-control form-control-solid fw-bolder" readonly>
                                </div>
                            </div>
                        </div>

                        <h5 class="fs-6 mt-10 fw-bolder">3.3.3 Describa brevemente las acciones que contribuyen para que los estudiantes de la Universidad de Guayaquil (de grado o posgrado) que se encuentran en condición de vulnerabilidad o con discapacidad puedan realizar sus prácticas de servicio comunitario:</h5>
                        <div class="form-group mb-8">
                            <?php $fieldName = 'acciones_contribucion';
                            $valorPrevio = $datos_enviados[$fieldName] ?? ''; ?>
                            <textarea id="acciones_contribucion" name="acciones_contribucion" rows="1" style="resize: vertical; min-height: 80px;" class="form-control form-control-solid <?= !empty($errores[$fieldName]) ? 'is-invalid' : '' ?>" placeholder="Describa las acciones" required><?= htmlspecialchars($valorPrevio) ?></textarea>
                            <?php if (!empty($errores[$fieldName])): ?><div class="text-danger small mt-1"><?= htmlspecialchars($errores[$fieldName]) ?></div><?php endif; ?>
                        </div>



                    </div>
                </div>
                <div class="card-footer d-flex justify-content-end py-6 px-9">
                    <a href="?c=Formularios&m=paso4" id="btnAnterior" class="btn btn-secondary me-2">Anterior</a>
                    <button type="submit" class="btn btn-primary">Siguiente</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Variable para almacenar los datos de docentes de la API, para evitar múltiples llamadas
    let docentesDataCache = {};
    let carrerasDataCache = {};

    // --- Funciones de carga de datos para los selects de tutores ---
    async function cargarCarrerasTutores(facultadId, row) {
        const carreraSelect = row.querySelector('select[name="carrera_tutor[]"]');
        const docenteSelect = row.querySelector('select[name="docente_tutor[]"]');
        const cedulaInput = row.querySelector('input[name="docente_cedula[]"]');

        if (carreraSelect) carreraSelect.innerHTML = '<option value="">Cargando...</option>';
        if (docenteSelect) docenteSelect.innerHTML = '<option value="">Primero seleccione Carrera</option>';
        if (cedulaInput) cedulaInput.value = '';

        if (!facultadId) {
            if (carreraSelect) carreraSelect.innerHTML = '<option value="">Seleccione</option>';
            return;
        }

        try {
            const response = await fetch(`?c=Institucion&m=getCarreras&facultad=${facultadId}`);
            if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
            const data = await response.json();

            carreraSelect.innerHTML = '<option value="">Seleccione</option>';
            if (data.carreras) {
                data.carreras.forEach(carrera => {
                    const option = document.createElement('option');
                    option.value = carrera.CodCarrera;
                    option.textContent = carrera.Carrera;
                    carreraSelect.appendChild(option);
                });
            }
        } catch (error) {
            console.error('Error al cargar carreras:', error);
            if (carreraSelect) carreraSelect.innerHTML = '<option value="">Error al cargar</option>';
        }
    }

    async function cargarDocentesTutores(carreraId, row) {
        const facultadId = row.querySelector('select[name="facultad_tutor[]"]').value;
        const docenteSelect = row.querySelector('select[name="docente_tutor[]"]');
        const cedulaInput = row.querySelector('input[name="docente_cedula[]"]');

        docenteSelect.innerHTML = '<option value="">Cargando...</option>';
        cedulaInput.value = '';

        if (!carreraId || !facultadId) {
            docenteSelect.innerHTML = '<option value="">Seleccione</option>';
            return;
        }

        try {
            const response = await fetch(`?c=Institucion&m=getDocentes&facultad=${facultadId}&carrera=${carreraId}`);
            if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
            const data = await response.json();

            docenteSelect.innerHTML = '<option value="">Seleccione</option>';
            if (data.docentes) {
                data.docentes.forEach(docente => {
                    const option = document.createElement('option');
                    option.value = docente.Nombres;
                    option.textContent = docente.Nombres;
                    option.dataset.cedula = docente.CedulaDocente;
                    docenteSelect.appendChild(option);
                });
            }
        } catch (error) {
            console.error('Error al cargar docentes:', error);
            docenteSelect.innerHTML = '<option value="">Error al cargar</option>';
        }
    }

    function actualizarCedulaDocente(selectElement) {
        const selectedOption = selectElement.options[selectElement.selectedIndex];
        const row = selectElement.closest('tr');
        const cedulaInput = row.querySelector('input[name="docente_cedula[]"]');
        if (cedulaInput && selectedOption) {
            const cedula = selectedOption.dataset.cedula || '';
            cedulaInput.value = cedula.padStart(10, '0');
        } else if (cedulaInput) {
            cedulaInput.value = '';
        }
    }

    // --- Lógica para cargar carreras en la tabla de estudiantes ---
    async function cargarCarrerasEstud(facultadId, row) {
        const carreraSelect = row.querySelector('select[name="carrera_estud[]"]');

        if (carreraSelect) carreraSelect.innerHTML = '<option value="">Cargando...</option>';
        if (!facultadId) {
            if (carreraSelect) carreraSelect.innerHTML = '<option value="">Seleccione</option>';
            return;
        }

        try {
            const response = await fetch(`?c=Institucion&m=getCarreras&facultad=${facultadId}`);
            if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
            const data = await response.json();

            carreraSelect.innerHTML = '<option value="">Seleccione</option>';
            if (data.carreras) {
                data.carreras.forEach(carrera => {
                    const option = document.createElement('option');
                    option.value = carrera.CodCarrera;
                    option.textContent = carrera.Carrera;
                    carreraSelect.appendChild(option);
                });
            }
        } catch (error) {
            console.error('Error al cargar carreras:', error);
            if (carreraSelect) carreraSelect.innerHTML = '<option value="">Error al cargar</option>';
        }
    }

    // --- Lógica de cálculo de totales ---
    const actualizarTotalEstudiantes = () => {
        let total = 0;
        const inputs = document.querySelectorAll('#cuerpoTablaEstud .estudiante-numero-input');
        inputs.forEach(input => {
            total += parseInt(input.value) || 0;
        });
        document.getElementById('total_estudiantes').value = total;
    };

    const actualizarTotalProgramas = () => {
        let total = 0;
        const inputs = document.querySelectorAll('#cuerpoTablaProgram .programa-numero-input');
        inputs.forEach(input => {
            total += parseInt(input.value) || 0;
        });
        document.getElementById('total_estudiantes_programas').value = total;
    };

    const actualizarTotalCiclos = () => {
        let totalEstudiantes = 0;
        const inputsEstudiantes = document.querySelectorAll('#tabla-ciclos-container .estudiantes-ciclo-total');
        inputsEstudiantes.forEach(input => {
            totalEstudiantes += parseInt(input.value) || 0;
        });
        document.getElementById('total_estudiantes_ciclo').value = totalEstudiantes;

        let totalDiscapacidad = 0;
        const inputsDiscapacidad = document.querySelectorAll('#tabla-ciclos-container .estudiantes-ciclo-discapacidad');
        inputsDiscapacidad.forEach(input => {
            totalDiscapacidad += parseInt(input.value) || 0;
        });
        document.getElementById('total_estudiantes_discapacidad').value = totalDiscapacidad;
    };


    // --- Lógica de precarga y eventos al cargar la página ---
    document.addEventListener("DOMContentLoaded", function() {


        document.querySelectorAll('#cuerpoTablaTutores tr').forEach(async row => {
            const selectFacultad = row.querySelector('select[name="facultad_tutor[]"]');
            const selectCarrera = row.querySelector('select[name="carrera_tutor[]"]');
            const selectDocente = row.querySelector('select[name="docente_tutor[]"]');

            const facultadId = selectFacultad.value;
            const carreraId = selectCarrera.dataset.selectedValue;
            const docenteNombre = selectDocente.dataset.selectedValue;

            if (facultadId) {
                await cargarCarrerasTutores(facultadId, row);
                if (carreraId) {
                    selectCarrera.value = carreraId;
                    await cargarDocentesTutores(carreraId, row);
                    if (docenteNombre) {
                        selectDocente.value = docenteNombre;
                        actualizarCedulaDocente(selectDocente);
                    }
                }
            }
        });

        document.querySelectorAll('#cuerpoTablaEstud tr').forEach(async row => {
            const selectFacultad = row.querySelector('select[name="facultad_estud[]"]');
            const selectCarrera = row.querySelector('select[name="carrera_estud[]"]');
            const facultadId = selectFacultad.value;
            const carreraId = selectCarrera.dataset.selectedValue;

            if (facultadId) {
                await cargarCarrerasEstud(facultadId, row);
                if (carreraId) {
                    selectCarrera.value = carreraId;
                }
            }
        });

        // Llamada inicial para actualizar los totales al cargar la página
        actualizarTotalEstudiantes();
        actualizarTotalProgramas();
        actualizarTotalCiclos();

        // Event listener para la tabla de tutores (añadir filas)
        document.getElementById('addTutorRowBtn').addEventListener('click', function() {
            const template = document.getElementById('TutorRowTemplate').content.cloneNode(true);
            const newRow = template.querySelector('tr');
            document.getElementById('cuerpoTablaTutores').appendChild(newRow);
        });

        // Event listener para la tabla de estudiantes (añadir filas)
        document.getElementById('btn-agregar-estudiante').addEventListener('click', function() {
            const template = document.getElementById('EstudRowTemplate').content.cloneNode(true);
            const newRow = template.querySelector('tr');
            document.getElementById('cuerpoTablaEstud').appendChild(newRow);
            actualizarTotalEstudiantes();
        });

        // Event listener para la tabla de programas (añadir filas)
        document.getElementById('addProgramaEstud').addEventListener('click', function() {
            const template = document.getElementById('ProgrRowTemplate').content.cloneNode(true);
            const newRow = template.querySelector('tr');
            document.getElementById('cuerpoTablaProgram').appendChild(newRow);
            actualizarTotalProgramas();
        });

        // Event listeners para la remoción de filas y actualización de totales
        document.addEventListener("click", function(e) {
            if (e.target.closest(".removeRowBtn")) {
                e.target.closest("tr").remove();
                actualizarTotalEstudiantes();
                actualizarTotalProgramas();
                actualizarTotalCiclos();
            }
        });

        // Event listeners para la actualización de totales al cambiar los inputs
        document.getElementById('cuerpoTablaEstud').addEventListener('input', function(e) {
            if (e.target.classList.contains('estudiante-numero-input')) {
                actualizarTotalEstudiantes();
            }
        });

        document.getElementById('cuerpoTablaProgram').addEventListener('input', function(e) {
            if (e.target.classList.contains('programa-numero-input')) {
                actualizarTotalProgramas();
            }
        });
        
        document.getElementById('tabla-ciclos-container').addEventListener('input', function(e) {
            if (e.target.matches('.estudiantes-ciclo-total, .estudiantes-ciclo-discapacidad')) {
                actualizarTotalCiclos();
            }
        });
    });

    // Funciones para validaciones y campos dinámicos
    function allowOnlyPositiveNumbers(event) {
        const key = event.key;
        const input = event.target;
        if (input.value.length === 0 && key === '0') {
            event.preventDefault();
            return false;
        }
        if (key >= '0' && key <= '9' || ['Backspace', 'Delete', 'Tab', 'ArrowLeft', 'ArrowRight', 'Home', 'End'].includes(key)) {
            return true;
        }
        event.preventDefault();
        return false;
    }
</script>

<?php require_once ROOT_PATH . '/app/views/layout/footer.php'; ?>