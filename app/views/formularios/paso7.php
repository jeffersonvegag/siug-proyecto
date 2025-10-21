<?php require_once ROOT_PATH . '/app/views/layout/header.php';

$pasoActual = 7;
require_once ROOT_PATH . '/app/views/layout/stepper_static.php';

// Lógica de repoblación
$descripcion = $datos_enviados['descripcion'] ?? '';
$com_descripcion = $datos_enviados['comentarios_descripcion'] ?? '';
$num_poblacion = $datos_enviados['numero_poblacion'] ?? '';
$com_num_poblacion = $datos_enviados['comentarios_numero_poblacion'] ?? '';
$caracteristicas = $datos_enviados['caracteristicas'] ?? '';
$com_caracteristicas = $datos_enviados['comentarios_caracteristicas'] ?? '';
$com_ben_directos = $datos_enviados['comentarios_beneficiarios_directos'] ?? '';
$det_ben_indirectos = $datos_enviados['detalle_beneficiarios_indirectos'] ?? '';
$det_ben_directos = $datos_enviados['detalle_beneficiarios_directos'] ?? '';
$com_ben_indirectos = $datos_enviados['comentarios_beneficiarios_indirectos'] ?? '';


// Para tablas dinámicas
$directos_map = $datos_enviados['directos_map'] ?? ($datos_enviados['directo'] ?? []);
$indirectos_map = $datos_enviados['indirectos_map'] ?? ($datos_enviados['indirecto'] ?? []);
?>

<div class="d-flex flex-column flex-column-fluid">
    <div class="app-content flex-column-fluid">
        <div class="">
            <form method="post" action="?c=Formularios&m=paso7" class="form" id="formPaso7" novalidate>
                <div class="card card-custom gutter-b">
                    <div class="card-header bg-dark py-6">
                        <div class="card-title">
                            <h3 class="card-label text-white fw-bolder fs-2">7. DESCRIPCIÓN DETALLADA DEL PROYECTO</h3>
                        </div>
                    </div>

                    <div class="card-body">
                        <h4 class="fs-6 fw-bolder mb-5 ">4.3. Descripción de la Población Objetivo</h4>
                        <div class="form-group mb-8">
                            <label for="descripcion" class="form-label fw-bolder">Descripción:</label>
                            <textarea name="descripcion" class="form-control form-control-solid <?= !empty($errores['descripcion']) ? 'is-invalid' : '' ?>" placeholder="Escribe aquí..." rows="3"><?= htmlspecialchars($descripcion) ?></textarea>
                            <?php if (!empty($errores['descripcion'])): ?><div class="invalid-feedback"><?= $errores['descripcion'] ?></div><?php endif; ?>
                        </div>


                        <div class="form-group mb-8">
                            <label class="form-label fw-bolder">4.3.1. Número Total de la Población Objetivo:</label>
                            <input type="number" name="numero_poblacion" class="form-control form-control-solid <?= !empty($errores['numero_poblacion']) ? 'is-invalid' : '' ?>" value="<?= htmlspecialchars($num_poblacion) ?>" placeholder="Ej: 150">
                            <?php if (!empty($errores['numero_poblacion'])): ?><div class="invalid-feedback"><?= $errores['numero_poblacion'] ?></div><?php endif; ?>
                        </div>


                        <div class="form-group mb-8">
                            <label class="form-label fw-bolder">4.3.2. Características de la Población Objetivo:</label>
                            <textarea name="caracteristicas" class="form-control form-control-solid <?= !empty($errores['caracteristicas']) ? 'is-invalid' : '' ?>" placeholder="Escribe aquí..." rows="3"><?= htmlspecialchars($caracteristicas) ?></textarea>
                            <?php if (!empty($errores['caracteristicas'])): ?><div class="invalid-feedback"><?= $errores['caracteristicas'] ?></div><?php endif; ?>
                        </div>


                        <h4 class="fs-6 fw-bolder mt-10">4.4. Beneficiarios Directos:</h4>
                        <div class="form-group mb-8">
                            <textarea name="detalle_beneficiarios_directos" class="form-control form-control-solid <?= !empty($errores['detalle_beneficiarios_directos']) ? 'is-invalid' : '' ?>" rows="2" style="resize: vertical;" placeholder=". . ."><?= htmlspecialchars($det_ben_directos) ?></textarea>
                            <?php if (!empty($errores['detalle_beneficiarios_directos'])): ?><div class="invalid-feedback"><?= $errores['detalle_beneficiarios_directos'] ?></div><?php endif; ?>
                        </div>
                        <?php if (!empty($errores['beneficiarios_indirectos_general'])): ?>
                            <div class="alert alert-danger p-2"><?= $errores['beneficiarios_indirectos_general'] ?></div>
                        <?php endif; ?>
                        <div class="table-responsive mb-8">
                            <table class="table table-bordered table-striped">
                                <tbody>
                                    <?php
                                    $tiposBeneficiarios = ['Comunidad en general', 'Grupos de interés', 'Pueblos y Nacionalidades', 'Grupos de Acción Afirmativa', 'Grupo de Atención Prioritaria'];
                                    foreach ($tiposBeneficiarios as $index => $tipo):
                                        $valorDesc = $directos_map[$tipo]['descripcion'] ?? ($directos_map[$index]['descripcion'] ?? '');
                                        $valorNum = $directos_map[$tipo]['numero'] ?? ($directos_map[$index]['numero'] ?? '');
                                    ?>
                                        <tr>
                                            <td><?= $tipo ?></td>
                                            <td>
                                                <textarea name="directo[<?= $index ?>][descripcion]" class="form-control form-control-sm" placeholder="Descripción..."><?= htmlspecialchars($valorDesc) ?></textarea>
                                                <input type="hidden" name="directo[<?= $index ?>][grupo]" value="<?= $tipo ?>">
                                            </td>
                                            <td>
                                                <input type="number" name="directo[<?= $index ?>][numero]" class="form-control form-control-sm input-beneficiario-directo" value="<?= htmlspecialchars($valorNum) ?>" placeholder="N°">
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="2" class="fw-bolder text-end">Total de Beneficiarios Directos</td>
                                        <td><input type="number" class="form-control" id="totalBeneficiariosDirectos" readonly></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>


                        <h4 class="fs-6 fw-bolder mt-10">4.5. Beneficiarios Indirectos:</h4>
                        <div class="form-group mb-8">
                            <textarea name="detalle_beneficiarios_indirectos" class="form-control form-control-solid <?= !empty($errores['detalle_beneficiarios_indirectos']) ? 'is-invalid' : '' ?>" rows="2" style="resize: vertical;" placeholder=". . ."><?= htmlspecialchars($det_ben_indirectos) ?></textarea>
                            <?php if (!empty($errores['detalle_beneficiarios_indirectos'])): ?><div class="invalid-feedback"><?= $errores['detalle_beneficiarios_indirectos'] ?></div><?php endif; ?>
                        </div>
                        <?php if (!empty($errores['beneficiarios_indirectos_general'])): ?>
                            <div class="alert alert-danger p-2"><?= $errores['beneficiarios_indirectos_general'] ?></div>
                        <?php endif; ?>
                        <div class="table-responsive mb-8">
                            <table class="table table-bordered table-striped">
                                <tbody>
                                    <?php
                                    foreach ($tiposBeneficiarios as $index => $tipo):
                                        $valorDesc = $indirectos_map[$tipo]['descripcion'] ?? ($indirectos_map[$index]['descripcion'] ?? '');
                                        $valorNum = $indirectos_map[$tipo]['numero'] ?? ($indirectos_map[$index]['numero'] ?? '');
                                    ?>
                                        <tr>
                                            <td><?= $tipo ?></td>
                                            <td>
                                                <textarea name="indirecto[<?= $index ?>][descripcion]" class="form-control form-control-sm" placeholder="Descripción..."><?= htmlspecialchars($valorDesc) ?></textarea>
                                                <input type="hidden" name="indirecto[<?= $index ?>][grupo]" value="<?= $tipo ?>">
                                            </td>
                                            <td>
                                                <input type="number" name="indirecto[<?= $index ?>][numero]" class="form-control form-control-sm input-beneficiario-indirecto" value="<?= htmlspecialchars($valorNum) ?>" placeholder="N°">
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="2" class="fw-bolder text-end">Total de Beneficiarios Indirectos</td>
                                        <td><input type="number" class="form-control" id="totalBeneficiariosIndirectos" readonly></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                    </div>
                    <div class="card-footer d-flex justify-content-end py-6 px-9">
                        <a href="?c=Formularios&m=paso6" class="btn btn-secondary me-2">Anterior</a>
                        <button type="submit" class="btn btn-primary">Siguiente</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        function calcularTotal(selectorInput, idTotal) {
            const inputs = document.querySelectorAll(selectorInput);
            let total = 0;
            inputs.forEach(input => {
                total += parseInt(input.value) || 0;
            });
            document.getElementById(idTotal).value = total;
        }

        // Calcular totales al cargar la página
        calcularTotal(".input-beneficiario-directo", "totalBeneficiariosDirectos");
        calcularTotal(".input-beneficiario-indirecto", "totalBeneficiariosIndirectos");

        // Recalcular al cambiar valores
        document.querySelectorAll(".input-beneficiario-directo").forEach(input => {
            input.addEventListener("input", () => calcularTotal(".input-beneficiario-directo", "totalBeneficiariosDirectos"));
        });
        document.querySelectorAll(".input-beneficiario-indirecto").forEach(input => {
            input.addEventListener("input", () => calcularTotal(".input-beneficiario-indirecto", "totalBeneficiariosIndirectos"));
        });
    });
</script>

<?php require_once ROOT_PATH . '/app/views/layout/footer.php'; ?>