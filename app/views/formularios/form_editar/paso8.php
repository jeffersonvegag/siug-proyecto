<?php
require_once ROOT_PATH . '/app/views/layout/header.php';

$pasoActual = 8;
require_once ROOT_PATH . '/app/views/layout/stepper_static.php';

// --- LÓGICA DE PERMISOS ---
$idRol = isset($_SESSION['user']['perfilId']) ? (int)$_SESSION['user']['perfilId'] : null;
$permite_editar = ($idRol === 20255 || $idRol === 4);
$permite_comentar = ($idRol === 20255 || $idRol === 3);

// --- LÓGICA DE DATOS COMPLETA ---
$datos_a_usar = $datos_enviados ?? $datos_guardados;

$objetivo_general = $datos_a_usar['objetivo_general'] ?? '';
$com_obj_general = $datos_a_usar['comentarios_obj_general'] ?? '';
$objetivos_especificos = $datos_a_usar['objetivos'] ?? [''];
$metodologia = $datos_a_usar['metodologia'] ?? '';
$com_metodologia = $datos_a_usar['comentarios_metodologia'] ?? '';
$dialogo = $datos_a_usar['dialogo'] ?? '';
$com_dialogo = $datos_a_usar['comentarios_dialogo'] ?? '';
$interculturalidad = $datos_a_usar['interculturalidad'] ?? '';
$com_interculturalidad = $datos_a_usar['comentarios_interculturalidad'] ?? '';
$sostenibilidad = $datos_a_usar['sostenibilidad_ambiental'] ?? '';
$com_sostenibilidad = $datos_a_usar['comentarios_sostenibilidad'] ?? '';
$evaluacion = $datos_a_usar['evaluacion_impacto'] ?? '';
$com_evaluacion = $datos_a_usar['comentarios_evaluacion'] ?? '';
$linea_comparacion = $datos_a_usar['linea_comparacion'] ?? '';
$actividades = $datos_a_usar['actividades'] ?? '';
$com_actividades = $datos_a_usar['comentarios_actividades'] ?? '';
$com_impactos = $datos_a_usar['comentarios_impactos'] ?? '';
$tipos_impacto_map = ['ambiental' => 'Ambientales', 'social' => 'Sociales', 'economico' => 'Económicos', 'politico' => 'Políticos', 'cientifico' => 'Científicos'];
?>

<div class="d-flex flex-column flex-column-fluid">
    <div class="app-content flex-column-fluid">
        <form method="post" action="?c=Formularios&m=actualizarPaso8&id=<?= htmlspecialchars($idProyecto) ?>" class="form" id="formPaso8" novalidate>
            <div class="card card-custom gutter-b">
                <div class="card-header bg-dark py-6">
                    <div class="card-title">
                        <h3 class="card-label text-white fw-bolder fs-2">8. OBJETIVOS Y METODOLOGÍA</h3>
                    </div>
                </div>

                <div class="card-body">
                    <h4 class="fs-6 fw-bolder mb-5">4.6 Objetivo General</h4>
                    <div class="form-group mb-8">
                        <textarea name="objetivo_general" class="form-control form-control-solid" rows="3" placeholder="Escribe el objetivo general..." data-editable required><?= htmlspecialchars($objetivo_general) ?></textarea>
                    </div>
                    <div class="form-group mb-8">
                        <label class="form-label fw-bolder">Comentarios:</label>
                        <textarea name="comentarios_obj_general" class="form-control form-control-solid" rows="1" placeholder=". . ." data-comentario><?= htmlspecialchars($com_obj_general) ?></textarea>
                    </div>

                    <div class="form-group mb-8 mt-10">
                        <label class="form-label fw-bolder">4.7 Objetivos Específicos</label>
                        <div id="contenedor-objetivos">
                            <?php foreach ($objetivos_especificos as $index => $texto_objetivo): ?>
                            <div class="input-objetivo mb-4">
                                <label class="form-label fw-bolder">OE<?= $index + 1 ?></label>
                                <div class="d-flex align-items-center">
                                    <input type="text" name="objetivos[]" class="form-control form-control-solid flex-grow-1 me-3" value="<?= htmlspecialchars($texto_objetivo) ?>" placeholder="Ingrese el objetivo específico" data-editable required>
                                    <button type="button" class="btn btn-icon btn-danger btn-sm btn-eliminar-objetivo" title="Eliminar objetivo" data-editable><i class="bi bi-trash fs-5"></i></button>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <button id="btn-agregar-objetivo" type="button" class="btn btn-primary mt-3" data-editable><i class="bi bi-plus-lg fs-4 me-2"></i>Agregar Objetivo</button>
                    </div>

                    <h4 class="fs-6 fw-bolder mb-5 mt-10">4.8 Metodología</h4>
                    <div class="form-group mb-8">
                        <textarea name="metodologia" class="form-control form-control-solid" rows="3" placeholder="Describe la metodología..." data-editable required><?= htmlspecialchars($metodologia) ?></textarea>
                    </div>
                    <div class="form-group mb-8">
                        <label class="form-label fw-bolder">Comentarios:</label>
                        <textarea name="comentarios_metodologia" class="form-control form-control-solid" rows="1" placeholder=". . ." data-comentario><?= htmlspecialchars($com_metodologia) ?></textarea>
                    </div>

                    <h4 class="fs-6 fw-bolder mb-5 mt-10">4.8.1 Diálogo</h4>
                    <div class="form-group mb-8">
                        <textarea name="dialogo" class="form-control form-control-solid" rows="3" placeholder="Describe el aspecto de diálogo..." data-editable required><?= htmlspecialchars($dialogo) ?></textarea>
                    </div>
                    <div class="form-group mb-8">
                        <label class="form-label fw-bolder">Comentarios:</label>
                        <textarea name="comentarios_dialogo" class="form-control form-control-solid" rows="1" placeholder=". . ." data-comentario><?= htmlspecialchars($com_dialogo) ?></textarea>
                    </div>

                    <h4 class="fs-6 fw-bolder mb-5 mt-10">4.8.2 Interculturalidad</h4>
                    <div class="form-group mb-8">
                        <textarea name="interculturalidad" class="form-control form-control-solid" rows="3" placeholder="Describe cómo se abordará la interculturalidad..." data-editable required><?= htmlspecialchars($interculturalidad) ?></textarea>
                    </div>
                    <div class="form-group mb-8">
                        <label class="form-label fw-bolder">Comentarios:</label>
                        <textarea name="comentarios_interculturalidad" class="form-control form-control-solid" rows="1" placeholder=". . ." data-comentario><?= htmlspecialchars($com_interculturalidad) ?></textarea>
                    </div>

                    <h4 class="fs-6 fw-bolder mb-5 mt-10">4.8.3 Sostenibilidad ambiental</h4>
                    <div class="form-group mb-8">
                        <textarea name="sostenibilidad_ambiental" class="form-control form-control-solid" rows="3" placeholder="Explica cómo se garantizará la sostenibilidad ambiental..." data-editable required><?= htmlspecialchars($sostenibilidad) ?></textarea>
                    </div>
                    <div class="form-group mb-8">
                        <label class="form-label fw-bolder">Comentarios:</label>
                        <textarea name="comentarios_sostenibilidad" class="form-control form-control-solid" rows="1" placeholder=". . ." data-comentario><?= htmlspecialchars($com_sostenibilidad) ?></textarea>
                    </div>

                    <h4 class="fs-6 fw-bolder mb-5 mt-10">4.9 Metodología de evaluación de impacto</h4>
                    <div class="form-group mb-8">
                        <textarea name="evaluacion_impacto" class="form-control form-control-solid" rows="3" placeholder="Describe la metodología para evaluar el impacto..." data-editable required><?= htmlspecialchars($evaluacion) ?></textarea>
                    </div>
                    <div class="form-group mb-8">
                        <label class="form-label fw-bolder">Comentarios:</label>
                        <textarea name="comentarios_evaluacion" class="form-control form-control-solid" rows="1" placeholder=". . ." data-comentario><?= htmlspecialchars($com_evaluacion) ?></textarea>
                    </div>

                    <h4 class="fs-6 fw-bolder mb-5 mt-10">4.9.1 Línea de Comparación</h4>
                    <div class="form-group mb-8">
                        <textarea name="linea_comparacion" class="form-control form-control-solid" rows="3" placeholder="Define la línea de comparación para la evaluación..." data-editable required><?= htmlspecialchars($linea_comparacion) ?></textarea>
                    </div>

                    <h4 class="fs-6 fw-bolder mb-5 mt-10">4.10 Actividades</h4>
                    <div class="form-group mb-8">
                        <textarea name="actividades" class="form-control form-control-solid" rows="3" placeholder="Escribe las actividades del proyecto..." data-editable required><?= htmlspecialchars($actividades) ?></textarea>
                    </div>
                    <div class="form-group mb-8">
                        <label class="form-label fw-bolder">Comentarios:</label>
                        <textarea name="comentarios_actividades" class="form-control form-control-solid" rows="1" placeholder=". . ." data-comentario><?= htmlspecialchars($com_actividades) ?></textarea>
                    </div>
                    <h4 class="fs-6 fw-bolder mb-5 mt-10">4.10.1 Impactos Esperados</h4>
                    <div class="table-responsive mb-8">
                        <table class="table table-bordered table-striped" id="tablaImpactos">
                            <thead>
                                <tr><th>Tipo de Impacto</th><th>Indicador(es)</th></tr>
                            </thead>
                            <tbody>
                                <?php foreach ($tipos_impacto_map as $clave => $valor): 
                                    $indicadores = $datos_a_usar["impacto_{$clave}_indicador"] ?? ($datos_a_usar['impactos_agrupados'][$clave] ?? ['']);
                                ?>
                                <tr>
                                    <td><?= $valor ?></td>
                                    <td class="contenedor-indicadores" data-tipo-impacto="<?= $clave ?>">
                                        <?php foreach ($indicadores as $texto_indicador): ?>
                                        <div class="indicador-item d-flex align-items-center mb-2">
                                            <textarea name="impacto_<?= $clave ?>_indicador[]" class="form-control form-control-solid flex-grow-1" rows="1" placeholder="Escribe el indicador..." data-editable required><?= htmlspecialchars($texto_indicador) ?></textarea>
                                        </div>
                                        <?php endforeach; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="form-group mb-8">
                        <label class="form-label fw-bolder">Comentarios:</label>
                        <textarea name="comentarios_impactos" class="form-control form-control-solid" rows="2" placeholder=". . ." data-comentario><?= htmlspecialchars($com_impactos) ?></textarea>
                    </div>
                </div>
                
                <div class="card-footer d-flex justify-content-end py-6 px-9">
                    <a href="?c=Formularios&m=editarPaso7&id=<?= htmlspecialchars($idProyecto)  ?>" id="btnAnterior" class="btn btn-secondary me-2">Anterior</a>
                    <button type="submit" class="btn btn-primary">Guardar y Siguiente</button>
                </div>
            </div>
        </form>
    </div>
</div>

<template id="template-objetivo">
    <div class="input-objetivo mb-4">
        <label class="form-label fw-bolder"></label>
        <div class="d-flex align-items-center">
            <input type="text" name="objetivos[]" class="form-control form-control-solid flex-grow-1 me-3" placeholder="Ingrese el objetivo específico" data-editable required>
            <button type="button" class="btn btn-icon btn-danger btn-sm btn-eliminar-objetivo" title="Eliminar objetivo" data-editable><i class="bi bi-trash fs-5"></i></button>
        </div>
    </div>
</template>

<template id="template-indicador">
    <div class="indicador-item d-flex align-items-center mb-2">
        <textarea class="form-control form-control-solid flex-grow-1" rows="1" placeholder="Escribe el indicador..." data-editable required></textarea>
    </div>
</template>

<script>
// =================================================================================
// SCRIPT UNIFICADO Y ROBUSTO PARA EL FORMULARIO 8
// =================================================================================
function aplicarPermisos(scope = document) {
    <?php if (!$permite_editar): ?>
    scope.querySelectorAll('[data-editable]').forEach(function(el) {
        if (el.tagName === 'BUTTON') {
            el.disabled = true; el.style.pointerEvents = 'none'; el.style.opacity = '0.5';
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

document.addEventListener('DOMContentLoaded', function() {
    
    // --- LÓGICA PARA OBJETIVOS ESPECÍFICOS ---
    const btnAgregarObjetivo = document.getElementById('btn-agregar-objetivo');
    const contenedorObjetivos = document.getElementById('contenedor-objetivos');
    const templateObjetivo = document.getElementById('template-objetivo');

    const actualizarObjetivos = () => {
        const objetivos = contenedorObjetivos.querySelectorAll('.input-objetivo');
        objetivos.forEach((objetivo, index) => {
            objetivo.querySelector('label').textContent = `OE${index + 1}`;
            const btnEliminar = objetivo.querySelector('.btn-eliminar-objetivo');
            btnEliminar.style.display = (objetivos.length > 1) ? '' : 'none';
        });

        if (objetivos.length >= 5) {
            btnAgregarObjetivo.disabled = true;
            btnAgregarObjetivo.innerHTML = '<i class="bi bi-exclamation-circle-fill fs-4 me-2"></i>Máximo 5 objetivos';
        } else {
            btnAgregarObjetivo.disabled = false;
            btnAgregarObjetivo.innerHTML = '<i class="bi bi-plus-lg fs-4 me-2"></i>Agregar Objetivo';
        }
        aplicarPermisos(btnAgregarObjetivo.parentElement);
    };

    btnAgregarObjetivo?.addEventListener('click', () => {
        if (contenedorObjetivos.querySelectorAll('.input-objetivo').length >= 5) return;
        const nuevoObjetivo = templateObjetivo.content.cloneNode(true);
        contenedorObjetivos.appendChild(nuevoObjetivo);
        aplicarPermisos(contenedorObjetivos.lastElementChild);
        actualizarObjetivos();
    });

    contenedorObjetivos?.addEventListener('click', (e) => {
        if (e.target.closest('.btn-eliminar-objetivo')) {
            e.target.closest('.input-objetivo').remove();
            actualizarObjetivos();
        }
    });
    
    // --- LÓGICA PARA IMPACTOS ESPERADOS ---
    const tablaImpactosBody = document.querySelector("#tablaImpactos tbody");
    const templateIndicador = document.getElementById('template-indicador');

    const actualizarBotonesImpacto = (contenedorIndicador) => {
        const items = contenedorIndicador.querySelectorAll('.indicador-item');
        items.forEach((item, index) => {
            item.querySelector('.btn-agregar-indicador')?.remove();
            item.querySelector('.btn-eliminar-indicador')?.remove();
            
            if (items.length > 1) {
                const btnEliminar = document.createElement('button');
                btnEliminar.type = 'button';
                btnEliminar.className = 'btn btn-icon btn-danger btn-sm ms-2 btn-eliminar-indicador';
                btnEliminar.title = 'Eliminar indicador';
                btnEliminar.innerHTML = '<i class="bi bi-trash-fill fs-6"></i>';
                btnEliminar.setAttribute('data-editable', '');
                item.appendChild(btnEliminar);
            }
            if (index === items.length - 1 && items.length < 10) {
                const btnAgregar = document.createElement('button');
                btnAgregar.type = 'button';
                btnAgregar.className = 'btn btn-icon btn-success btn-sm ms-2 btn-agregar-indicador';
                btnAgregar.title = 'Agregar nuevo indicador';
                btnAgregar.innerHTML = '<i class="bi bi-plus-lg fs-6"></i>';
                btnAgregar.setAttribute('data-editable', '');
                item.appendChild(btnAgregar);
            }
        });
        aplicarPermisos(contenedorIndicador);
    };

    tablaImpactosBody?.addEventListener('click', (e) => {
        const btnAgregar = e.target.closest('.btn-agregar-indicador');
        const btnEliminar = e.target.closest('.btn-eliminar-indicador');
        const contenedorIndicador = e.target.closest('.contenedor-indicadores');
        
        if (btnAgregar) {
            const tipoImpacto = contenedorIndicador.dataset.tipoImpacto;
            const nuevoItem = templateIndicador.content.cloneNode(true);
            const textarea = nuevoItem.querySelector('textarea');
            textarea.name = `impacto_${tipoImpacto}_indicador[]`;
            contenedorIndicador.appendChild(nuevoItem);
            aplicarPermisos(contenedorIndicador.lastElementChild);
            actualizarBotonesImpacto(contenedorIndicador);
        }
        
        if (btnEliminar) {
            btnEliminar.closest('.indicador-item').remove();
            actualizarBotonesImpacto(contenedorIndicador);
        }
    });

    // --- INICIALIZACIÓN AL CARGAR LA PÁGINA ---
    actualizarObjetivos();
    document.querySelectorAll('.contenedor-indicadores').forEach(actualizarBotonesImpacto);
    aplicarPermisos(document.getElementById('formPaso8'));
});
</script>

<?php require_once ROOT_PATH . '/app/views/layout/footer.php'; ?>