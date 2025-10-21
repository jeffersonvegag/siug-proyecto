<?php
require_once ROOT_PATH . '/app/views/layout/header.php';

$pasoActual = 6;
require_once ROOT_PATH . '/app/views/layout/stepper_static.php';

// --- LÓGICA DE PERMISOS ---
$idRol = isset($_SESSION['user']['perfilId']) ? (int)$_SESSION['user']['perfilId'] : null;
$permite_editar = ($idRol === 20255 || $idRol === 4);
$permite_comentar = ($idRol === 20255 || $idRol === 3);

// Lógica para repoblar el formulario
$justificacion = $datos_enviados['justificacion'] ?? ($detalle['Justificacion'] ?? '');
$com_justificacion = $datos_enviados['comentarios_justificacion'] ?? ($detalle['ComentariosJustificacion'] ?? '');
$linea_base = $datos_enviados['linea_base'] ?? ($detalle['LineaBase'] ?? '');
$com_linea_base = $datos_enviados['comentarios_linea_base'] ?? ($detalle['ComentariosLineaBase'] ?? '');
$fundamentacion = $datos_enviados['fundamentacion_teorica'] ?? ($detalle['FundamentacionTeorica'] ?? '');
$com_fundamentacion = $datos_enviados['comentarios_fundamentacion'] ?? ($detalle['ComentariosFundamentacion'] ?? '');
?>

<div class="d-flex flex-column flex-column-fluid">
    <div class="app-content flex-column-fluid">
        <div class="">
            <form method="post" action="?c=Formularios&m=actualizarPaso6&id=<?= htmlspecialchars($idProyecto) ?>" class="form" id="formPaso6" novalidate>
                <div class="card card-custom gutter-b">
                    <div class="card-header bg-dark py-6">
                        <div class="card-title">
                            <h3 class="card-label text-white fw-bolder fs-2">6. DESCRIPCIÓN DETALLADA DEL PROYECTO</h3>
                        </div>
                    </div>

                    <div class="card-body">
                        <h2 class="fs-4 fw-bolder mb-5">4.1 Descripción del problema y línea base</h2>

                        <h4 class="fs-6 fw-bolder mt-4">4.1.1 Justificación</h4>
                        <div class="form-group mb-8">
                            <textarea name="justificacion" rows="10" class="form-control form-control-solid" placeholder="Ingrese la justificación aquí" data-editable required><?= htmlspecialchars($justificacion) ?></textarea>
                        </div>

                        <div class="form-group mb-8">
                            <label class="form-label fw-bolder">Comentarios:</label>
                            <textarea name="comentarios_justificacion" class="form-control form-control-solid" rows="2" placeholder=". . ." data-comentario><?= htmlspecialchars($com_justificacion) ?></textarea>
                        </div>

                        <h4 class="fs-6 fw-bolder mt-10">4.1.2 Línea Base</h4>
                        <div class="form-group mb-8">
                            <textarea name="linea_base" rows="10" class="form-control form-control-solid" placeholder="Ingrese la línea base aquí" data-editable required><?= htmlspecialchars($linea_base) ?></textarea>
                        </div>

                        <div class="form-group mb-8">
                            <label class="form-label fw-bolder">Comentarios:</label>
                            <textarea name="comentarios_linea_base" class="form-control form-control-solid" rows="2" placeholder=". . ." data-comentario><?= htmlspecialchars($com_linea_base) ?></textarea>
                        </div>

                        <h4 class="fs-6 fw-bolder mt-10">4.2 Fundamentación Teórica</h4>
                        <p class="text-gray-600 mb-8">Exponer los resultados de trabajos de investigación y/o docencia de la UG que van a ser transferidos a la comunidad a través del presente proyecto, acorde a lo solicitado en el Modelo de Evaluación CACES.</p>
                        <div class="form-group mb-8">
                            <textarea name="fundamentacion_teorica" rows="10" class="form-control form-control-solid" placeholder="Ingrese la fundamentación teórica aquí" data-editable required><?= htmlspecialchars($fundamentacion) ?></textarea>
                        </div>

                        <div class="form-group mb-8">
                            <label class="form-label fw-bolder">Comentarios:</label>
                            <textarea name="comentarios_fundamentacion" class="form-control form-control-solid" rows="2" placeholder=". . ." data-comentario><?= htmlspecialchars($com_fundamentacion) ?></textarea>
                        </div>
                    </div>

                    <div class="card-footer d-flex justify-content-end py-6 px-9">
    <a href="?c=Formularios&m=editarPaso5&id=<?= htmlspecialchars($idProyecto) ?>" id="btnAnterior" class="btn btn-secondary me-2">Anterior</a>
    
    <button type="submit" class="btn btn-primary" >Guardar y Siguiente</button>
</div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// =================================================================================
// SCRIPT UNIFICADO Y ROBUSTO PARA EL FORMULARIO 6
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
        } else {
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
 * Listener principal que se ejecuta cuando el DOM está listo.
 */
document.addEventListener('DOMContentLoaded', () => {
    // Como este formulario es estático, solo necesitamos llamar a la función una vez.
    aplicarPermisos(document.getElementById('formPaso6'));
});
</script>

<?php require_once ROOT_PATH . '/app/views/layout/footer.php'; ?>