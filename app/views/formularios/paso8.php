<?php require_once ROOT_PATH . '/app/views/layout/header.php';

$pasoActual = 8;
require_once ROOT_PATH . '/app/views/layout/stepper_static.php';

// --- INICIO: Lógica robusta para repoblar todos los campos ---
$objetivo_general = $datos_enviados['objetivo_general'] ?? '';
$com_obj_general = $datos_enviados['comentarios_obj_general'] ?? '';
$metodologia = $datos_enviados['metodologia'] ?? '';
$com_metodologia = $datos_enviados['comentarios_metodologia'] ?? '';
$dialogo = $datos_enviados['dialogo'] ?? '';
$com_dialogo = $datos_enviados['comentarios_dialogo'] ?? '';
$interculturalidad = $datos_enviados['interculturalidad'] ?? '';
$com_interculturalidad = $datos_enviados['comentarios_interculturalidad'] ?? '';
$sostenibilidad = $datos_enviados['sostenibilidad_ambiental'] ?? '';
$com_sostenibilidad = $datos_enviados['comentarios_sostenibilidad'] ?? '';
$evaluacion = $datos_enviados['evaluacion_impacto'] ?? '';
$com_evaluacion = $datos_enviados['comentarios_evaluacion'] ?? '';
$linea_comparacion = $datos_enviados['linea_comparacion'] ?? '';
$actividades = $datos_enviados['actividades'] ?? '';
$com_actividades = $datos_enviados['comentarios_actividades'] ?? '';
$com_impactos = $datos_enviados['comentarios_impactos'] ?? '';

// Campos dinámicos (aseguramos que siempre sean un array con al menos un elemento vacío)
$objetivos_especificos = $datos_enviados['objetivos'] ?? [''];
$com_objetivos_especificos = $datos_enviados['comentarios_objetivos'] ?? [];

$tipos_impacto_map = ['ambiental' => 'Ambientales', 'social' => 'Sociales', 'economico' => 'Económicos', 'politico' => 'Políticos', 'cientifico' => 'Científicos'];
// --- FIN: Lógica de repoblación ---
?>

<div class="d-flex flex-column flex-column-fluid">
    <div class="app-content flex-column-fluid">
        <form method="post" action="?c=Formularios&m=paso8" class="form" id="formPaso8" novalidate>
            <div class="card card-custom gutter-b">
                <div class="card-header bg-dark py-6">
                    <div class="card-title">
                        <h3 class="card-label text-white fw-bolder fs-2">8. OBJETIVOS Y METODOLOGÍA</h3>
                    </div>
                </div>

                <div class="card-body">
                    <h4 class="fs-6 fw-bolder mb-5">4.6 Objetivo General</h4>
                    <div class="form-group mb-8">
                        <textarea name="objetivo_general" class="form-control form-control-solid <?= !empty($errores['objetivo_general']) ? 'is-invalid' : '' ?>" rows="3" placeholder="Escribe el objetivo general..."><?= htmlspecialchars($objetivo_general) ?></textarea>
                        <?php if (!empty($errores['objetivo_general'])): ?><div class="invalid-feedback"><?= htmlspecialchars($errores['objetivo_general']) ?></div><?php endif; ?>
                    </div>


                    <div class="form-group mb-8 mt-10">
                        <label class="form-label fw-bolder">4.7 Objetivos Específicos</label>
                        <?php if (!empty($errores['objetivos_general'])): ?><div class="alert alert-danger p-2"><?= htmlspecialchars($errores['objetivos_general']) ?></div><?php endif; ?>
                        <div id="contenedor-objetivos">
                            <?php foreach ($objetivos_especificos as $index => $texto_objetivo): ?>
                            <div class="input-objetivo mb-4">
                                <label class="form-label fw-bolder">OE<?= $index + 1 ?></label>
                                <div class="d-flex align-items-center">
                                    <input type="text" name="objetivos[]" class="form-control form-control-solid flex-grow-1 me-3" value="<?= htmlspecialchars($texto_objetivo) ?>" placeholder="Ingrese el objetivo específico">
                                    <button type="button" class="btn btn-icon btn-danger btn-sm btn-eliminar-objetivo" title="Eliminar objetivo"><i class="bi bi-trash fs-5"></i></button>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <button id="btn-agregar-objetivo" type="button" class="btn btn-primary mt-3"><i class="bi bi-plus-lg fs-4 me-2"></i>Agregar Objetivo</button>
                    </div>

                    <h4 class="fs-6 fw-bolder mb-5 mt-10">4.8 Metodología</h4>
                    <div class="form-group mb-8">
                        <textarea name="metodologia" class="form-control form-control-solid <?= !empty($errores['metodologia']) ? 'is-invalid' : '' ?>" rows="3" placeholder="Describe la metodología..."><?= htmlspecialchars($metodologia) ?></textarea>
                        <?php if (!empty($errores['metodologia'])): ?><div class="invalid-feedback"><?= htmlspecialchars($errores['metodologia']) ?></div><?php endif; ?>
                    </div>


                    <h4 class="fs-6 fw-bolder mb-5 mt-10">4.8.1 Diálogo</h4>
                    <div class="form-group mb-8">
                        <textarea name="dialogo" class="form-control form-control-solid <?= !empty($errores['dialogo']) ? 'is-invalid' : '' ?>" rows="3" placeholder="Describe el aspecto de diálogo..."><?= htmlspecialchars($dialogo) ?></textarea>
                        <?php if (!empty($errores['dialogo'])): ?><div class="invalid-feedback"><?= htmlspecialchars($errores['dialogo']) ?></div><?php endif; ?>
                    </div>


                    <h4 class="fs-6 fw-bolder mb-5 mt-10">4.8.2 Interculturalidad</h4>
                    <div class="form-group mb-8">
                        <textarea name="interculturalidad" class="form-control form-control-solid <?= !empty($errores['interculturalidad']) ? 'is-invalid' : '' ?>" rows="3" placeholder="Describe cómo se abordará la interculturalidad..."><?= htmlspecialchars($interculturalidad) ?></textarea>
                        <?php if (!empty($errores['interculturalidad'])): ?><div class="invalid-feedback"><?= htmlspecialchars($errores['interculturalidad']) ?></div><?php endif; ?>
                    </div>

                    <h4 class="fs-6 fw-bolder mb-5 mt-10">4.8.3 Sostenibilidad ambiental</h4>
                    <div class="form-group mb-8">
                        <textarea name="sostenibilidad_ambiental" class="form-control form-control-solid <?= !empty($errores['sostenibilidad_ambiental']) ? 'is-invalid' : '' ?>" rows="3" placeholder="Explica cómo se garantizará la sostenibilidad ambiental..."><?= htmlspecialchars($sostenibilidad) ?></textarea>
                        <?php if (!empty($errores['sostenibilidad_ambiental'])): ?><div class="invalid-feedback"><?= htmlspecialchars($errores['sostenibilidad_ambiental']) ?></div><?php endif; ?>
                    </div>

                    <h4 class="fs-6 fw-bolder mb-5 mt-10">4.9 Metodología de evaluación de impacto</h4>
                    <div class="form-group mb-8">
                        <textarea name="evaluacion_impacto" class="form-control form-control-solid <?= !empty($errores['evaluacion_impacto']) ? 'is-invalid' : '' ?>" rows="3" placeholder="Describe la metodología para evaluar el impacto..."><?= htmlspecialchars($evaluacion) ?></textarea>
                        <?php if (!empty($errores['evaluacion_impacto'])): ?><div class="invalid-feedback"><?= htmlspecialchars($errores['evaluacion_impacto']) ?></div><?php endif; ?>
                    </div>


                    <h4 class="fs-6 fw-bolder mb-5 mt-10">4.9.1 Línea de Comparación</h4>
                    <div class="form-group mb-8">
                        <textarea name="linea_comparacion" class="form-control form-control-solid <?= !empty($errores['linea_comparacion']) ? 'is-invalid' : '' ?>" rows="3" placeholder="Define la línea de comparación para la evaluación..."><?= htmlspecialchars($linea_comparacion) ?></textarea>
                        <?php if (!empty($errores['linea_comparacion'])): ?><div class="invalid-feedback"><?= htmlspecialchars($errores['linea_comparacion']) ?></div><?php endif; ?>
                    </div>

                    <h4 class="fs-6 fw-bolder mb-5 mt-10">4.10 Actividades</h4>
                    <div class="form-group mb-8">
                        <textarea name="actividades" class="form-control form-control-solid <?= !empty($errores['actividades']) ? 'is-invalid' : '' ?>" rows="3" placeholder="Escribe las actividades del proyecto..."><?= htmlspecialchars($actividades) ?></textarea>
                        <?php if (!empty($errores['actividades'])): ?><div class="invalid-feedback"><?= htmlspecialchars($errores['actividades']) ?></div><?php endif; ?>
                    </div>

                    <h4 class="fs-6 fw-bolder mb-5 mt-10">4.10.1 Impactos Esperados</h4>
                    <?php if (!empty($errores['impactos_general'])): ?><div class="alert alert-danger p-2"><?= htmlspecialchars($errores['impactos_general']) ?></div><?php endif; ?>
                    <div class="table-responsive mb-8">
                        <table class="table table-bordered table-striped" id="tablaImpactos">
                            <thead>
                                <tr>
                                    <th>Tipo de Impacto</th>
                                    <th>Indicador(es)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($tipos_impacto_map as $clave => $valor): 
                                    $indicadores_a_mostrar = $datos_enviados["impacto_{$clave}_indicador"] ?? ($datos_enviados['impactos_agrupados'][$clave] ?? ['']);
                                ?>
                                <tr>
                                    <td><?= $valor ?></td>
                                    <td class="contenedor-indicadores" data-tipo-impacto="<?= $clave ?>">
                                        <?php foreach ($indicadores_a_mostrar as $texto_indicador): ?>
                                        <div class="indicador-item d-flex align-items-center mb-2">
                                            <textarea name="impacto_<?= $clave ?>_indicador[]" class="form-control form-control-solid flex-grow-1" rows="1" placeholder="Escribe el indicador..."><?= htmlspecialchars($texto_indicador) ?></textarea>
                                        </div>
                                        <?php endforeach; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                </div>
                
                <div class="card-footer d-flex justify-content-end py-6 px-9">
                    <a href="?c=Formularios&m=paso7" id="btnAnterior" class="btn btn-secondary me-2">Anterior</a>
                    <button type="submit" class="btn btn-primary">Siguiente</button>
                </div>
            </div>
        </form>
    </div>
</div>

<template id="template-objetivo">
    <div class="input-objetivo mb-4">
        <label class="form-label fw-bolder"></label>
        <div class="d-flex align-items-center">
            <input type="text" name="objetivos[]" class="form-control form-control-solid flex-grow-1 me-3" placeholder="Ingrese el objetivo específico">
            <button type="button" class="btn btn-icon btn-danger btn-sm btn-eliminar-objetivo" title="Eliminar objetivo"><i class="bi bi-trash fs-5"></i></button>
        </div>
    </div>
</template>

<template id="template-indicador">
    <div class="indicador-item d-flex align-items-center mb-2">
        <textarea class="form-control form-control-solid flex-grow-1" rows="1" placeholder="Escribe el indicador..."></textarea>
    </div>
</template>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // --- SCRIPT PARA OBJETIVOS ESPECÍFICOS ---
    const btnAgregar = document.getElementById('btn-agregar-objetivo');
    const contenedor = document.getElementById('contenedor-objetivos');
    const template = document.getElementById('template-objetivo');

    const actualizarObjetivos = () => {
        const objetivosActuales = contenedor.querySelectorAll('.input-objetivo');
        objetivosActuales.forEach((objetivo, index) => {
            const numero = index + 1;
            const label = objetivo.querySelector('label');
            const input = objetivo.querySelector('input');
            const btnEliminar = objetivo.querySelector('.btn-eliminar-objetivo');
            label.textContent = `OE${numero}`;
            
            if (objetivosActuales.length <= 1) {
                btnEliminar.classList.add('d-none');
            } else {
                btnEliminar.classList.remove('d-none');
            }
        });
        if (objetivosActuales.length >= 5) {
            btnAgregar.disabled = true;
            btnAgregar.innerHTML = '<i class="bi bi-exclamation-circle-fill fs-4 me-2"></i>Máximo 5 objetivos';
        } else {
            btnAgregar.disabled = false;
            btnAgregar.innerHTML = '<i class="bi bi-plus-lg fs-4 me-2"></i>Agregar Objetivo';
        }
    };
    btnAgregar.addEventListener('click', () => {
        const numObjetivosActual = contenedor.querySelectorAll('.input-objetivo').length;
        if (numObjetivosActual >= 5) return;
        const nuevoObjetivo = template.content.cloneNode(true);
        contenedor.appendChild(nuevoObjetivo);
        actualizarObjetivos();
    });
    contenedor.addEventListener('click', (e) => {
        if (e.target.closest('.btn-eliminar-objetivo')) {
            e.target.closest('.input-objetivo').remove();
            actualizarObjetivos();
        }
    });
    actualizarObjetivos();

    // --- SCRIPT PARA IMPACTOS ESPERADOS ---
    const tablaImpactosBody = document.querySelector("#tablaImpactos tbody");
    const templateIndicador = document.getElementById('template-indicador');

    const actualizarBotonesImpacto = (contenedorIndicador) => {
        const items = contenedorIndicador.querySelectorAll('.indicador-item');
        const totalItems = items.length;
        items.forEach((item, index) => {
            item.querySelector('.btn-agregar-indicador')?.remove();
            item.querySelector('.btn-eliminar-indicador')?.remove();
            
            if (totalItems > 1) {
                const btnEliminar = document.createElement('button');
                btnEliminar.type = 'button';
                btnEliminar.className = 'btn btn-icon btn-danger btn-sm ms-2 btn-eliminar-indicador';
                btnEliminar.title = 'Eliminar indicador';
                btnEliminar.innerHTML = '<i class="bi bi-trash-fill fs-6"></i>';
                item.appendChild(btnEliminar);
            }
            if (index === totalItems - 1 && totalItems < 10) { // Límite de 10 indicadores por tipo
                const btnAgregar = document.createElement('button');
                btnAgregar.type = 'button';
                btnAgregar.className = 'btn btn-icon btn-success btn-sm ms-2 btn-agregar-indicador';
                btnAgregar.title = 'Agregar nuevo indicador';
                btnAgregar.innerHTML = '<i class="bi bi-plus-lg fs-6"></i>';
                item.appendChild(btnAgregar);
            }
        });
    };
    tablaImpactosBody.addEventListener('click', (e) => {
        const btnAgregar = e.target.closest('.btn-agregar-indicador');
        const btnEliminar = e.target.closest('.btn-eliminar-indicador');
        if (btnAgregar) {
            const contenedorIndicador = btnAgregar.closest('.contenedor-indicadores');
            const tipoImpacto = contenedorIndicador.dataset.tipoImpacto;
            const nuevoItem = templateIndicador.content.cloneNode(true);
            const textarea = nuevoItem.querySelector('textarea');
            textarea.name = `impacto_${tipoImpacto}_indicador[]`;
            contenedorIndicador.appendChild(nuevoItem);
            actualizarBotonesImpacto(contenedorIndicador);
        }
        if (btnEliminar) {
            const contenedorIndicador = btnEliminar.closest('.contenedor-indicadores');
            btnEliminar.closest('.indicador-item').remove();
            actualizarBotonesImpacto(contenedorIndicador);
        }
    });
    document.querySelectorAll('.contenedor-indicadores').forEach(contenedor => {
        actualizarBotonesImpacto(contenedor);
    });
});
</script>

<?php require_once ROOT_PATH . '/app/views/layout/footer.php'; ?>