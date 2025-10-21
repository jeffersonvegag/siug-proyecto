<?php
require_once ROOT_PATH . '/app/views/layout/header.php';

$pasoActual = 2; // Paso actual
require_once ROOT_PATH . '/app/views/layout/stepper_static.php';

?>

<div class="d-flex flex-column flex-column-fluid">
    <div class="app-content flex-column-fluid">
        <div class="">
            <form method="POST" action="?c=Formularios&m=actualizarPaso2&id=<?= htmlspecialchars($idProyecto) ?>" class="form" id="formPaso2" novalidate>
                <div class="card card-custom gutter-b">
                    <div class="card-header bg-dark  py-6">
                        <div class="card-title">
                            <h3 class="card-label text-white fw-bolder fs-2">2. Objetivos Generales del Proyecto</h3>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="form-group mb-8">
                            <?php $fieldName = 'objetivo_sostenible'; $valorPrevio = $datos_enviados[$fieldName] ?? ''; ?>
                            <label class="form-label fw-bolder" for="objetivo_sostenible">1.5 Objetivo de Desarrollo Sostenible</label>
                            <select id="objetivo_sostenible" name="objetivo_sostenible" class="form-select form-select-solid <?= !empty($errores[$fieldName]) ? 'is-invalid' : '' ?>" required data-editable>
                                <option value="">Seleccione</option>
                                <?php foreach ($ods as $obj): ?>
                                    <option value="<?= htmlspecialchars($obj['IdObjDS']) ?>" <?= ($valorPrevio == $obj['IdObjDS']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($obj['ObjDesarrolloS']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (!empty($errores[$fieldName])): ?><div class="text-danger small mt-1"><?= htmlspecialchars($errores[$fieldName]) ?></div><?php endif; ?>
                        </div>

                        <div class="form-group mb-8">
                            <?php $fieldName = 'comentarios_ods'; $valorPrevio = $datos_enviados[$fieldName] ?? ''; ?>
                            <label class="form-label fw-bolder" for="comentarios_ods">Comentarios:</label>
                            <textarea name="comentarios_ods" class="form-control form-control-solid" rows="2" placeholder="Ingrese comentarios aquí" data-comentario><?= htmlspecialchars($valorPrevio) ?></textarea>
                        </div>

                        <div class="form-group mb-8">
                            <?php $fieldName = 'eje'; $valorPrevio = $datos_enviados[$fieldName] ?? ''; ?>
                            <label class="form-label fw-bolder" for="eje">1.6 Eje estratégico del Plan</label>
                            <select id="eje" name="eje" class="form-select form-select-solid <?= !empty($errores[$fieldName]) ? 'is-invalid' : '' ?>" required data-editable>
                                <option value="">Seleccione</option>
                                <?php foreach ($ejes_desarrollo as $eje_dev): ?>
                                    <option value="<?= htmlspecialchars($eje_dev['IdEjes']) ?>" <?= ($valorPrevio == $eje_dev['IdEjes']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($eje_dev['Eje']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (!empty($errores[$fieldName])): ?><div class="text-danger small mt-1"><?= htmlspecialchars($errores[$fieldName]) ?></div><?php endif; ?>
                        </div>

                        <div class="form-group mb-8">
                             <?php $fieldName = 'comentarios_eje'; $valorPrevio = $datos_enviados[$fieldName] ?? ''; ?>
                            <label class="form-label fw-bolder" for="comentarios_eje">Comentarios:</label>
                            <textarea name="comentarios_eje" class="form-control form-control-solid" rows="2" placeholder="Ingrese comentarios aquí" data-comentario><?= htmlspecialchars($valorPrevio) ?></textarea>
                        </div>

                        <div class="form-group mb-8">
                            <?php $fieldName = 'objetivo_nacional'; ?>
                            <label class="form-label fw-bolder" for="objetivo_nacional">1.7 Objetivo del Plan Nacional de Desarrollo</label>
                            <select id="objetivo_nacional" name="objetivo_nacional" class="form-select form-select-solid <?= !empty($errores[$fieldName]) ? 'is-invalid' : '' ?>" required data-editable>
                                <option value="">Seleccione</option>
                            </select>
                             <?php if (!empty($errores[$fieldName])): ?><div class="text-danger small mt-1"><?= htmlspecialchars($errores[$fieldName]) ?></div><?php endif; ?>
                        </div>

                        <div class="form-group mb-8">
                            <?php $fieldName = 'comentarios_obj_nacional'; $valorPrevio = $datos_enviados[$fieldName] ?? ''; ?>
                            <label class="form-label fw-bolder" for="comentarios_obj_nacional">Comentarios:</label>
                            <textarea name="comentarios_obj_nacional" class="form-control form-control-solid" rows="2" placeholder="Ingrese comentarios aquí" data-comentario><?= htmlspecialchars($valorPrevio) ?></textarea>
                        </div>

                        <div class="form-group mb-8">
                            <?php $fieldName = 'dominios'; $valorPrevio = $datos_enviados[$fieldName] ?? ''; ?>
                            <label class="form-label fw-bolder" for="dominios">1.8 Alineación del proyecto a los dominios científicos</label>
                            <select id="dominios" name="dominios" class="form-select form-select-solid <?= !empty($errores[$fieldName]) ? 'is-invalid' : '' ?>" required data-editable>
                                <option value="">Seleccione</option>
                                <?php foreach ($dominios as $dominio): ?>
                                    <option value="<?= htmlspecialchars($dominio['IdDominio']) ?>" <?= ($valorPrevio == $dominio['IdDominio']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($dominio['Dominio']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (!empty($errores[$fieldName])): ?><div class="text-danger small mt-1"><?= htmlspecialchars($errores[$fieldName]) ?></div><?php endif; ?>
                        </div>

                        <div class="form-group mb-8">
                            <?php $fieldName = 'comentarios_dominios'; $valorPrevio = $datos_enviados[$fieldName] ?? ''; ?>
                            <label class="form-label fw-bolder" for="comentarios_dominios">Comentarios:</label>
                            <textarea name="comentarios_dominios" class="form-control form-control-solid" rows="2" placeholder="Ingrese comentarios aquí" data-comentario><?= htmlspecialchars($valorPrevio) ?></textarea>
                        </div>

                        <div class="form-group mb-8">
                            <?php $fieldName = 'lineas'; ?>
                            <label class="form-label fw-bolder" for="lineas">1.9 Líneas de investigación institucionales</label>
                            <select id="lineas" name="lineas" class="form-select form-select-solid <?= !empty($errores[$fieldName]) ? 'is-invalid' : '' ?>" required data-editable>
                                <option value="">Seleccione</option>
                            </select>
                             <?php if (!empty($errores[$fieldName])): ?><div class="text-danger small mt-1"><?= htmlspecialchars($errores[$fieldName]) ?></div><?php endif; ?>
                        </div>

                        <div class="form-group mb-8">
                            <?php $fieldName = 'comentarios_lineas'; $valorPrevio = $datos_enviados[$fieldName] ?? ''; ?>
                            <label class="form-label fw-bolder" for="comentarios_lineas">Comentarios:</label>
                            <textarea name="comentarios_lineas" class="form-control form-control-solid" rows="2" placeholder="Ingrese comentarios aquí" data-comentario><?= htmlspecialchars($valorPrevio) ?></textarea>
                        </div>
                    </div>

                    <div class="card-footer d-flex justify-content-end py-6 px-9">
                        <a href="?c=Formularios&m=editarPaso1&id=<?= htmlspecialchars($idProyecto) ?>" id="btnAnterior" class="btn btn-secondary me-2">Anterior</a>
                        <button type="submit" class="btn btn-primary">Siguiente</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Datos y valores de PHP
    const obj_nac_data = <?= $obj_nac_json ?? '[]' ?>;
    const lineas_data = <?= $lineas_json ?? '[]' ?>;
    const valorGuardadoObjNac = "<?= $datos_enviados['objetivo_nacional'] ?? '' ?>";
    const valorGuardadoLinea = "<?= $datos_enviados['lineas'] ?? '' ?>";

    // Elementos del DOM
    const ejeSelect = document.getElementById('eje');
    const objSelect = document.getElementById('objetivo_nacional');
    const dominioSelect = document.getElementById('dominios');
    const lineaSelect = document.getElementById('lineas');

    /**
     * Función centralizada para aplicar permisos. Deshabilita campos
     * según el rol del usuario y asegura el envío de datos con campos ocultos.
     */
    function aplicarPermisos() {
        <?php if (!$permite_editar): ?>
        document.querySelectorAll('[data-editable]').forEach(function(el) {
            if (el.tagName === 'INPUT' || el.tagName === 'TEXTAREA') {
                el.setAttribute('readonly', true);
                el.style.backgroundColor = '#f5f6fa';
            }
            if (el.tagName === 'SELECT') {
                if (!el.parentNode.querySelector(`input[type="hidden"][name="${el.name}"]`)) {
                    const hidden = document.createElement('input');
                    hidden.type = 'hidden';
                    hidden.name = el.name;
                    hidden.value = el.value; // El valor es correcto en este punto
                    el.parentNode.insertBefore(hidden, el);
                }
                el.setAttribute('disabled', true);
                el.style.backgroundColor = '#f5f6fa';
            }
            if (el.tagName === 'BUTTON') {
                el.setAttribute('disabled', true);
                el.style.pointerEvents = 'none';
                el.style.opacity = '0.5';
            }
        });
        <?php endif; ?>

        <?php if (!$permite_comentar): ?>
        document.querySelectorAll('[data-comentario]').forEach(function(el) {
            if (el.tagName === 'TEXTAREA' || el.tagName === 'INPUT') {
                el.setAttribute('readonly', true);
            }
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

    // Función para poblar un select (sin cambios)
    function popularSelect(selectElement, data, valorGuardado) {
        selectElement.innerHTML = '<option value="">Seleccione</option>';
        data.forEach(function(item) {
            const option = document.createElement('option');
            option.value = item.id;
            option.textContent = item.name;
            if (valorGuardado && valorGuardado == item.id) {
                option.selected = true;
            }
            selectElement.appendChild(option);
        });
    }

    // Lógica para cargar los selects dependientes (sin cambios)
    if (ejeSelect && objSelect) {
        function cargarObjetivos() {
            const ejeId = ejeSelect.value;
            const dataParaPoblar = obj_nac_data[ejeId] || [];
            popularSelect(objSelect, dataParaPoblar, valorGuardadoObjNac);
        }
        ejeSelect.addEventListener('change', cargarObjetivos);
        if (ejeSelect.value) {
            cargarObjetivos();
        }
    }

    if (dominioSelect && lineaSelect) {
        function cargarLineas() {
            const dominioId = dominioSelect.value;
            const dataParaPoblar = lineas_data[dominioId] || [];
            popularSelect(lineaSelect, dataParaPoblar, valorGuardadoLinea);
        }
        dominioSelect.addEventListener('change', cargarLineas);
        if (dominioSelect.value) {
            cargarLineas();
        }
    }

    // LLAMADA CRÍTICA: Aplicamos los permisos DESPUÉS de que la lógica
    // de carga inicial de los selects haya finalizado.
    aplicarPermisos();
});
</script>

<?php require_once ROOT_PATH . '/app/views/layout/footer.php'; ?>