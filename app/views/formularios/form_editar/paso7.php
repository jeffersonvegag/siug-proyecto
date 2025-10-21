<?php
require_once ROOT_PATH . '/app/views/layout/header.php';

$pasoActual = 7;
require_once ROOT_PATH . '/app/views/layout/stepper_static.php';

// --- LÓGICA DE DATOS MEJORADA ---
$datos_a_usar = $datos_enviados ?? $datos_guardados; // Prioriza POST sobre DB

$descripcion = $datos_a_usar['descripcion'] ?? '';
$com_descripcion = $datos_a_usar['comentarios_descripcion'] ?? '';
$num_poblacion = $datos_a_usar['numero_poblacion'] ?? '';
$com_num_poblacion = $datos_a_usar['comentarios_numero_poblacion'] ?? '';
$caracteristicas = $datos_a_usar['caracteristicas'] ?? '';
$det_ben_directos = $datos_a_usar['detalle_beneficiarios_directos'] ?? '';
$det_ben_indirectos = $datos_a_usar['detalle_beneficiarios_indirectos'] ?? '';
$com_caracteristicas = $datos_guardados['comentarios_caracteristicas'] ?? '';

// CORRECCIÓN: Se normalizan los datos de las tablas para manejar la doble estructura (de BD y de POST)
$tiposBeneficiarios = ['Comunidad en general', 'Grupos de interés', 'Pueblos y Nacionalidades', 'Grupos de Acción Afirmativa', 'Grupo de Atención Prioritaria'];
$directos_map = [];
$indirectos_map = [];

$raw_directos = $datos_a_usar['directo'] ?? ($datos_a_usar['directos_map'] ?? []);
$raw_indirectos = $datos_a_usar['indirecto'] ?? ($datos_a_usar['indirectos_map'] ?? []);

foreach ($tiposBeneficiarios as $index => $tipo) {
    // Normalizar beneficiarios directos
    $directos_map[$index]['descripcion'] = $raw_directos[$tipo]['descripcion'] ?? ($raw_directos[$index]['descripcion'] ?? '');
    $directos_map[$index]['numero'] = $raw_directos[$tipo]['numero'] ?? ($raw_directos[$index]['numero'] ?? '');
    
    // Normalizar beneficiarios indirectos
    $indirectos_map[$index]['descripcion'] = $raw_indirectos[$tipo]['descripcion'] ?? ($raw_indirectos[$index]['descripcion'] ?? '');
    $indirectos_map[$index]['numero'] = $raw_indirectos[$tipo]['numero'] ?? ($raw_indirectos[$index]['numero'] ?? '');
}

?>

<div class="d-flex flex-column flex-column-fluid">
    <div class="app-content flex-column-fluid">
        <div class="">
            <form method="post" action="?c=Formularios&m=actualizarPaso7&id=<?= htmlspecialchars($idProyecto) ?>" class="form" id="formPaso7" novalidate>
                <div class="card card-custom gutter-b">
                    <div class="card-header bg-dark py-6">
                        <div class="card-title">
                            <h3 class="card-label text-white fw-bolder fs-2">7. DESCRIPCIÓN DETALLADA DEL PROYECTO</h3>
                        </div>
                    </div>

                    <div class="card-body">
                        <h4 class="fs-6 fw-bolder mb-5 ">4.3. Descripción de la Población Objetivo</h4>
                        <div class="form-group mb-8">
                            <label class="form-label fw-bolder">Descripción:</label>
                            <textarea name="descripcion" class="form-control form-control-solid" placeholder="Escribe aquí..." rows="3" data-editable required><?= htmlspecialchars($descripcion) ?></textarea>
                        </div>
                        <div class="form-group mb-8">
                            <label class="form-label fw-bolder">Comentarios:</label>
                            <textarea name="comentarios_descripcion" class="form-control form-control-solid" rows="1" placeholder=". . ." data-comentario><?= htmlspecialchars($com_descripcion) ?></textarea>
                        </div>
                        
                        <div class="form-group mb-8">
                            <label class="form-label fw-bolder">4.3.1. Número Total de la Población Objetivo:</label>
                            <input type="number" name="numero_poblacion" class="form-control form-control-solid" value="<?= htmlspecialchars($num_poblacion) ?>" placeholder="Ej: 150" data-editable required>
                        </div>
                        <div class="form-group mb-8">
                            <label class="form-label fw-bolder">Comentarios:</label>
                            <textarea name="comentarios_numero_poblacion" class="form-control form-control-solid" rows="1" placeholder=". . ." data-comentario><?= htmlspecialchars($com_num_poblacion) ?></textarea>
                        </div>
                        
                        <div class="form-group mb-8">
                            <label class="form-label fw-bolder">4.3.2. Características de la Población Objetivo:</label>
                            <textarea name="caracteristicas" class="form-control form-control-solid" placeholder="Escribe aquí..." rows="3" data-editable required><?= htmlspecialchars($caracteristicas) ?></textarea>
                        </div>
                        <div class="form-group mb-8">
                            <label class="form-label fw-bolder">Comentarios:</label>
                            <textarea name="comentarios_caracteristicas" class="form-control form-control-solid" rows="1" placeholder=". . ." data-comentario><?= htmlspecialchars($com_caracteristicas) ?></textarea>
                        </div>

                        <h4 class="fs-6 fw-bolder mt-10">4.4. Beneficiarios Directos:</h4>
                        <div class="form-group mb-8">
                            <textarea name="detalle_beneficiarios_directos" class="form-control form-control-solid" rows="2" placeholder=". . ." data-editable required><?= htmlspecialchars($det_ben_directos) ?></textarea>
                        </div>
                        <div class="table-responsive mb-8">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Tipo de Beneficiario</th><th>Descripción</th><th>Número de Beneficiarios</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($tiposBeneficiarios as $index => $tipo): ?>
                                    <tr>
                                        <td><?= $tipo ?></td>
                                        <td>
                                            <textarea name="directo[<?= $index ?>][descripcion]" class="form-control form-control-sm" placeholder="Descripción..." data-editable><?= htmlspecialchars($directos_map[$index]['descripcion']) ?></textarea>
                                            <input type="hidden" name="directo[<?= $index ?>][grupo]" value="<?= $tipo ?>">
                                        </td>
                                        <td>
                                            <input type="number" name="directo[<?= $index ?>][numero]" class="form-control form-control-sm input-beneficiario-directo" value="<?= htmlspecialchars($directos_map[$index]['numero']) ?>" placeholder="N°" data-editable>
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
                        
                        <h4 class="fs-6 fw-bolder mb-5 mt-10">4.5. Beneficiarios Indirectos:</h4>
                        <div class="form-group mb-8">
                            <textarea name="detalle_beneficiarios_indirectos" class="form-control form-control-solid" rows="2" placeholder=". . ." data-editable required><?= htmlspecialchars($det_ben_indirectos) ?></textarea>
                        </div>
                        <div class="table-responsive mb-8">
                             <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Tipo de Beneficiario</th><th>Descripción</th><th>Número de Beneficiarios</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($tiposBeneficiarios as $index => $tipo): ?>
                                    <tr>
                                        <td><?= $tipo ?></td>
                                        <td>
                                            <textarea name="indirecto[<?= $index ?>][descripcion]" class="form-control form-control-sm" placeholder="Descripción..." data-editable><?= htmlspecialchars($indirectos_map[$index]['descripcion']) ?></textarea>
                                            <input type="hidden" name="indirecto[<?= $index ?>][grupo]" value="<?= $tipo ?>">
                                        </td>
                                        <td>
                                            <input type="number" name="indirecto[<?= $index ?>][numero]" class="form-control form-control-sm input-beneficiario-indirecto" value="<?= htmlspecialchars($indirectos_map[$index]['numero']) ?>" placeholder="N°" data-editable>
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
                        <a href="?c=Formularios&m=editarPaso6&id=<?= htmlspecialchars($idProyecto) ?>" id="btnAnterior" class="btn btn-secondary me-2">Anterior</a>
                        <button type="submit" class="btn btn-primary" >Guardar y Siguiente</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// =================================================================================
// SCRIPT UNIFICADO Y ROBUSTO PARA EL FORMULARIO 7
// =================================================================================

/**
 * Aplica permisos a un contenedor específico (scope).
 */
function aplicarPermisos(scope = document) {
    <?php if (!$permite_editar): ?>
    scope.querySelectorAll('[data-editable]').forEach(function(el) {
        if (el.tagName === 'BUTTON') {
            el.disabled = true;
            el.style.pointerEvents = 'none';
            el.style.opacity = '0.5';
        } else { // Aplica a INPUT y TEXTAREA
            el.readOnly = true;
            el.style.backgroundColor = '#f5f6fa';
        }
    });
    <?php endif; ?>

    <?php if (!$permite_comentar): ?>
    scope.querySelectorAll('[data-comentario]').forEach(function(el) {
        el.readOnly = true;
        el.style.backgroundColor = '#f5f6fa';
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
 * Lógica para calcular y actualizar los totales de beneficiarios.
 */
function inicializarCalculoTotales() {
    const form = document.getElementById('formPaso7');

    function calcularTotal(selectorInput, idTotal) {
        let total = 0;
        form.querySelectorAll(selectorInput).forEach(input => {
            total += parseInt(input.value, 10) || 0;
        });
        const totalField = document.getElementById(idTotal);
        if (totalField) {
            totalField.value = total;
        }
    }

    // Calcular totales al cargar la página
    calcularTotal(".input-beneficiario-directo", "totalBeneficiariosDirectos");
    calcularTotal(".input-beneficiario-indirecto", "totalBeneficiariosIndirectos");

    // Recalcular cuando se modifica un campo
    form.addEventListener("input", (e) => {
        if (e.target.matches(".input-beneficiario-directo")) {
            calcularTotal(".input-beneficiario-directo", "totalBeneficiariosDirectos");
        }
        if (e.target.matches(".input-beneficiario-indirecto")) {
            calcularTotal(".input-beneficiario-indirecto", "totalBeneficiariosIndirectos");
        }
    });
}

/**
 * Listener principal que se ejecuta cuando el DOM está listo.
 */
document.addEventListener('DOMContentLoaded', () => {
    // 1. Inicializa la lógica de cálculo de totales
    inicializarCalculoTotales();

    // 2. Aplica los permisos a todo el formulario
    aplicarPermisos(document.getElementById('formPaso7'));
});
</script>

<?php require_once ROOT_PATH . '/app/views/layout/footer.php'; ?>