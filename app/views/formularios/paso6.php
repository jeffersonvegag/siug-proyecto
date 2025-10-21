<?php require_once ROOT_PATH . '/app/views/layout/header.php'; 

$pasoActual = 6;
require_once ROOT_PATH . '/app/views/layout/stepper_static.php';

// Se unifican los datos, ya sea que vengan de la BD (con mayúsculas) o del POST (con minúsculas)
$justificacion = $datos_enviados['Justificacion'] ?? ($datos_enviados['justificacion'] ?? '');
$com_justificacion = $datos_enviados['ComentariosJustificacion'] ?? ($datos_enviados['comentarios_justificacion'] ?? '');
$linea_base = $datos_enviados['LineaBase'] ?? ($datos_enviados['linea_base'] ?? '');
$com_linea_base = $datos_enviados['ComentariosLineaBase'] ?? ($datos_enviados['comentarios_linea_base'] ?? '');
$fundamentacion = $datos_enviados['FundamentacionTeorica'] ?? ($datos_enviados['fundamentacion_teorica'] ?? '');
$com_fundamentacion = $datos_enviados['ComentariosFundamentacion'] ?? ($datos_enviados['comentarios_fundamentacion'] ?? '');
?>

<div class="d-flex flex-column flex-column-fluid">
    <div class="app-content flex-column-fluid">
        <div class="">
            <form method="post" action="?c=Formularios&m=paso6" class="form" id="formPaso6" novalidate>
                <div class="card card-custom gutter-b">
                    <div class="card-header bg-dark py-6">
                        <div class="card-title">
                            <h3 class="card-label text-white fw-bolder fs-2">6. DESCRIPCIÓN DETALLADA DEL PROYECTO</h3>
                        </div>
                    </div>

                    <div class="card-body">

                        <h2 class="fs-4 fw-bolder mb-5 ">4.1 Descripción del problema y línea base</h2>

                        <h4 class="fs-6 fw-bolder mt-4">4.1.1 Justificación</h4>
                        <div class="form-group mb-8">
                            <textarea name="justificacion" rows="10" class="form-control form-control-solid <?= !empty($errores['justificacion']) ? 'is-invalid' : '' ?>" placeholder="Ingrese la justificación aquí"><?= htmlspecialchars($justificacion) ?></textarea>
                            <?php if (!empty($errores['justificacion'])): ?>
                                <div class="invalid-feedback"><?= $errores['justificacion'] ?></div>
                            <?php endif; ?>
                        </div>


                        <h4 class="fs-6 fw-bolder mt-10">4.1.2 Línea Base</h4>
                        <div class="form-group mb-8">
                            <textarea name="linea_base" rows="10" class="form-control form-control-solid <?= !empty($errores['linea_base']) ? 'is-invalid' : '' ?>" placeholder="Ingrese la línea base aquí"><?= htmlspecialchars($linea_base) ?></textarea>
                            <?php if (!empty($errores['linea_base'])): ?>
                                <div class="invalid-feedback"><?= $errores['linea_base'] ?></div>
                            <?php endif; ?>
                        </div>


                        <h4 class="fs-6 fw-bolder mt-10">4.2 Fundamentación Teórica</h4>
                        <p class="text-gray-600 mb-8">Exponer los resultados de trabajos de investigación y/o docencia de la UG que van a ser transferidos a la comunidad a través del presente proyecto, acorde a lo solicitado en el Modelo de Evaluación CACES.</p>
                        <div class="form-group mb-8">
                            <textarea name="fundamentacion_teorica" rows="10" class="form-control form-control-solid <?= !empty($errores['fundamentacion_teorica']) ? 'is-invalid' : '' ?>" placeholder="Ingrese la fundamentacion teórica aquí"><?= htmlspecialchars($fundamentacion) ?></textarea>
                            <?php if (!empty($errores['fundamentacion_teorica'])): ?>
                                <div class="invalid-feedback"><?= $errores['fundamentacion_teorica'] ?></div>
                            <?php endif; ?>
                        </div>

                    </div>
                    <div class="card-footer d-flex justify-content-end py-6 px-9">
                        <a href="?c=Formularios&m=paso5" id="btnAnterior" class="btn btn-secondary me-2">Anterior</a>
                        <button type="submit" class="btn btn-primary">Siguiente</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once ROOT_PATH . '/app/views/layout/footer.php'; ?>