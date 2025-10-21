<?php
require_once ROOT_PATH . '/app/views/layout/header.php';

$pasoActual = 11;
// Usamos el ID del proyecto para que los enlaces del stepper sigan funcionando
$id_for_stepper = $id_proyecto;
require_once ROOT_PATH . '/app/views/layout/stepper_static.php';



?>

<div class="d-flex flex-column flex-column-fluid">
    <div class="app-content flex-column-fluid">
        <div class="">
            <form method="post" action="?c=Formularios&m=actualizarPaso11&id=<?= htmlspecialchars($id_proyecto) ?>" class="form" id="formPaso11">
                <div class="card card-custom gutter-b">
                    <div class="card-header bg-dark py-6">
                        <div class="card-title">
                            <h3 class="card-label text-white fw-bolder fs-2">11. DECLARACIÓN FINAL</h3>
                        </div>
                    </div>

                    <div class="card-body">

                        <?php if (isset($_SESSION['mensaje_exito'])): ?>
                            <div class="alert alert-success" role="alert">
                                <?= $_SESSION['mensaje_exito']; ?>
                            </div>
                            <?php unset($_SESSION['mensaje_exito']); ?>
                        <?php endif; ?>

                        <div id="declaracion" class="declaracion mb-8">
                            <p class="text-gray-700 mb-5">
                                Los abajo firmantes declaramos bajo juramento que el proyecto descrito en este documento es de nuestra autoría, no causa perjuicio a las personas involucradas y/o comunidades; ambiente, e instituciones vinculadas, y no transgrede ninguna norma ética.
                            </p>
                            <p class="text-gray-700 mb-0">
                                Aceptamos también, que los descubrimientos e invenciones, las mejoras en los procedimientos, así como los trabajos y resultados que se logren alcanzar dentro del proyecto; así como lo correspondiente a la titularidad de los derechos de propiedad intelectual que pudieran llegar a derivarse de la ejecución del mismo, se regirán de conformidad a lo establecido en el Código Orgánico de la Economía Social de los Conocimientos, Creatividad e Innovación.
                            </p>
                        </div>

                        <div id="mirada-gestor" class="form-group mb-8">
                            <label for="mirada_gestor_facultad" class="form-label fw-bolder">Mirada del Gestor desde la Facultad:</label>
                            <textarea
                                id="mirada_gestor_facultad"
                                name="mirada_gestor_facultad"
                                rows="4"
                                style="resize: vertical; min-height: 120px;"
                                class="form-control form-control-solid"
                                placeholder="Solo el Gestor o Administrador puede editar este campo..."
                                data-comentario ><?= htmlspecialchars($declaracion['MiradaGestorFacultad'] ?? '') ?></textarea>
                        </div>
                    </div>

                    <div class="card-footer d-flex justify-content-end py-6 px-9">
                        <a href="?c=Formularios&m=editarPaso10&id=<?= htmlspecialchars($id_proyecto) ?>" class="btn btn-secondary me-2">Anterior</a>
                        <button type="submit" class="btn btn-primary" >Guardar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// =================================================================================
// SCRIPT DE PERMISOS UNIFICADO Y ROBUSTO
// =================================================================================

/**
 * Aplica restricciones de edición y comentarios a un contenedor específico del DOM.
 * @param {HTMLElement} scope El elemento contenedor sobre el cual aplicar los permisos.
 */
function aplicarPermisos(scope = document) {
    // --- LÓGICA PARA DESHABILITAR EDICIÓN ---
    <?php if (!$permite_editar): ?>
    scope.querySelectorAll('[data-editable]').forEach(function(el) {
        if (el.tagName === 'BUTTON') {
            el.disabled = true;
            el.style.pointerEvents = 'none';
            el.style.opacity = '0.5';
        } else if (el.tagName === 'SELECT') {
            if (el.multiple) {
                Array.from(el.selectedOptions).forEach(option => {
                    const hidden = document.createElement('input');
                    hidden.type = 'hidden';
                    hidden.name = el.name;
                    hidden.value = option.value;
                    el.parentNode.insertBefore(hidden, el);
                });
            } else {
                const hidden = document.createElement('input');
                hidden.type = 'hidden';
                hidden.name = el.name;
                hidden.value = el.value;
                el.parentNode.insertBefore(hidden, el);
            }
            el.disabled = true;
            el.style.backgroundColor = '#f5f6fa';
            if ($(el).data('select2')) {
               $(el).trigger('change.select2');
            }
        } else { // INPUT, TEXTAREA
            el.readOnly = true;
            el.style.backgroundColor = '#f5f6fa';
        }
    });
    <?php endif; ?>

    // --- LÓGICA PARA DESHABILITAR COMENTARIOS ---
    <?php if (!$permite_comentar): ?>
    scope.querySelectorAll('[data-comentario]').forEach(function(el) {
        if (el.tagName === 'BUTTON') {
             el.disabled = true;
             el.style.pointerEvents = 'none';
             el.style.opacity = '0.5';
        } else {
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
        }
    });
    <?php endif; ?>
}


document.addEventListener('DOMContentLoaded', function() {
    // Aplicar permisos a todo el formulario al cargar la página.
    aplicarPermisos(document.getElementById('formPaso11'));
});
</script>

<?php require_once ROOT_PATH . '/app/views/layout/footer.php'; ?>