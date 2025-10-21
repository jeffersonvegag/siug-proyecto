<?php
require_once ROOT_PATH . '/app/views/layout/header.php';

$pasoActual = 1;
require_once ROOT_PATH . '/app/views/layout/stepper_static.php';


?>

<div class="d-flex flex-column flex-column-fluid">
    <div class="app-content flex-column-fluid">
        <div class="">
            <form method="POST" action="?c=Formularios&m=actualizarPaso1&id=<?= htmlspecialchars($idProyecto) ?>" class="form" id="formPaso1" novalidate>
                <div class="card card-custom gutter-b">
                    <div class="card-header bg-dark py-6">
                        <div class="card-title">
                            <h3 class="card-label text-white fw-bolder fs-2">1. Datos Generales del Proyecto (Edición)</h3>
                        </div>
                    </div>
                    <div class="card-body">

                        <div class="form-group mb-8">
                            <?php $fieldName = 'Titulo';
                            $valorPrevio = $datos_enviados[$fieldName] ?? ''; ?>
                            <label for="Titulo" class="form-label fw-bolder">1.1. Título del proyecto:</label>
                            <input type="text" name="Titulo" id="Titulo" class="form-control form-control-solid <?= !empty($errores[$fieldName]) ? 'is-invalid' : '' ?>" placeholder="Ingrese el título del proyecto" value="<?= htmlspecialchars($valorPrevio) ?>" required data-editable/>
                            <?php if (!empty($errores[$fieldName])): ?><div class="text-danger small mt-1"><?= htmlspecialchars($errores[$fieldName]) ?></div><?php endif; ?>
                        </div>

                        <div class="form-group mb-8">
                            <?php $fieldName = 'eje_estrategico';
                            $valorPrevio = $datos_enviados[$fieldName] ?? ''; ?>
                            <label for="eje_estrategico" class="form-label fw-bolder">1.2. Eje estratégico de actuación:</label>
                            <select name="eje_estrategico" id="eje_estrategico" class="form-select form-select-solid <?= !empty($errores[$fieldName]) ? 'is-invalid' : '' ?>" required data-editable>
                                <option value="">Seleccione</option>
                                <?php foreach ($ejes_estrategicos as $eje): ?>
                                    <option value="<?= htmlspecialchars($eje['IdEje']) ?>" <?= ($valorPrevio == $eje['IdEje']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($eje['EjeEstrategico']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (!empty($errores[$fieldName])): ?><div class="text-danger small mt-1"><?= htmlspecialchars($errores[$fieldName]) ?></div><?php endif; ?>
                        </div>

                        <div class="form-group mb-8">
                            <label class="form-label fw-bolder">1.3. Tipo de programa o proyecto con el que se articula:</label>
                            <?php if (!empty($errores['tipo_programa_general'])): ?>
                                <div class="alert alert-danger small p-2 mt-2"><?= htmlspecialchars($errores['tipo_programa_general']) ?></div>
                            <?php endif; ?>
                            <div>
                                <?php
                                $programasSeleccionados = $datos_enviados['tipo_programa'] ?? [];
                                foreach ($programas_articulacion as $programa):
                                    $section_id = 'section_programa_' . $programa['IdProgamaA'];
                                    $isChecked = in_array($programa['IdProgamaA'], $programasSeleccionados);
                                ?>
                                    <div class="mb-6">
                                        <div class="form-check form-check-custom form-check-solid">
                                            <input class="form-check-input" type="checkbox" name="tipo_programa[]" value="<?= htmlspecialchars($programa['IdProgamaA']) ?>" id="programa_<?= $programa['IdProgamaA'] ?>" onclick="toggleFields(this, '<?= $section_id ?>')" <?= $isChecked ? 'checked' : '' ?> data-editable />
                                            <label class="form-check-label" for="programa_<?= $programa['IdProgamaA'] ?>"><?= htmlspecialchars($programa['Programa']) ?></label>
                                        </div>
                                        <div id="<?= $section_id ?>" style="<?= $isChecked ? 'display:block;' : 'display:none;' ?>" class="mt-4 p-5 border rounded bg-light-secondary">

                                            <div class="form-group mb-5">
                                                <?php $fieldName = 'nombre_programa_' . $programa['IdProgamaA'];
                                                $valorPrevio = $datos_enviados[$fieldName] ?? ''; ?>
                                                <label class="form-label fw-bolder">Nombre del proyecto:</label>
                                                <input type="text" class="form-control form-control-solid <?= !empty($errores[$fieldName]) ? 'is-invalid' : '' ?>" name="<?= $fieldName ?>" value="<?= htmlspecialchars($valorPrevio) ?>" <?= $isChecked ? 'required' : '' ?> data-editable />
                                                <?php if (!empty($errores[$fieldName])): ?><div class="text-danger small mt-1"><?= htmlspecialchars($errores[$fieldName]) ?></div><?php endif; ?>
                                            </div>

                                            <div class="form-group mb-5">
                                                <?php $fieldName = 'autores_programa_' . $programa['IdProgamaA'];
                                                $valorPrevio = $datos_enviados[$fieldName] ?? ''; ?>
                                                <label class="form-label fw-bolder">Autores:</label>
                                                <input type="text" class="form-control form-control-solid <?= !empty($errores[$fieldName]) ? 'is-invalid' : '' ?>" name="<?= $fieldName ?>" value="<?= htmlspecialchars($valorPrevio) ?>" <?= $isChecked ? 'required' : '' ?> data-editable />
                                                <?php if (!empty($errores[$fieldName])): ?><div class="text-danger small mt-1"><?= htmlspecialchars($errores[$fieldName]) ?></div><?php endif; ?>
                                            </div>

                                            <div class="form-group mb-5">
                                                <?php $fieldName = 'anio_programa_' . $programa['IdProgamaA'];
                                                $valorPrevio = $datos_enviados[$fieldName] ?? ''; ?>
                                                <label class="form-label fw-bolder">Año:</label>
                                                <input type="number" class="form-control form-control-solid <?= !empty($errores[$fieldName]) ? 'is-invalid' : '' ?>" name="<?= $fieldName ?>" value="<?= htmlspecialchars($valorPrevio) ?>" min="2015" max="2040" oninput="if (this.value.length > 4) this.value = this.value.slice(0, 4);" <?= $isChecked ? 'required' : '' ?> data-editable />
                                                <?php if (!empty($errores[$fieldName])): ?><div class="text-danger small mt-1"><?= htmlspecialchars($errores[$fieldName]) ?></div><?php endif; ?>
                                            </div>

                                            <div class="form-group mb-5">
                                                <?php $fieldName = 'enlace_programa_' . $programa['IdProgamaA'];
                                                $valorPrevio = $datos_enviados[$fieldName] ?? ''; ?>
                                                <label class="form-label fw-bolder">Enlace:</label>
                                                <input type="url" class="form-control form-control-solid <?= !empty($errores[$fieldName]) ? 'is-invalid' : '' ?>" name="<?= $fieldName ?>" value="<?= htmlspecialchars($valorPrevio) ?>" data-editable />
                                                <?php if (!empty($errores[$fieldName])): ?><div class="text-danger small mt-1"><?= htmlspecialchars($errores[$fieldName]) ?></div><?php endif; ?>
                                            </div>

                                            <div class="form-group">
                                                <?php $fieldName = 'descripcion_programa_' . $programa['IdProgamaA'];
                                                $valorPrevio = $datos_enviados[$fieldName] ?? ''; ?>
                                                <label class="form-label fw-bolder">Resultados a transferir:</label>
                                                <textarea class="form-control form-control-solid <?= !empty($errores[$fieldName]) ? 'is-invalid' : '' ?>" rows="3" name="<?= $fieldName ?>" <?= $isChecked ? 'required' : '' ?> data-editable><?= htmlspecialchars($valorPrevio) ?></textarea>
                                                <?php if (!empty($errores[$fieldName])): ?><div class="text-danger small mt-1"><?= htmlspecialchars($errores[$fieldName]) ?></div><?php endif; ?>
                                            </div>

                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <div class="form-group mb-8">
                            <?php $fieldName = 'area';
                            $valorPrevio = $datos_enviados[$fieldName] ?? ''; ?>
                            <label for="areaC" class="form-label fw-bolder">Área de conocimiento:</label>
                            <select id="areaC" name="area" class="form-select form-select-solid <?= !empty($errores[$fieldName]) ? 'is-invalid' : '' ?>" required data-editable>
                                <option value="">Seleccione</option>
                                <?php foreach ($areas_data as $area): ?>
                                    <option value="<?= htmlspecialchars($area['IdArea']) ?>" <?= ($valorPrevio == $area['IdArea']) ? 'selected' : '' ?>><?= htmlspecialchars($area['Area']) ?></option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (!empty($errores[$fieldName])): ?><div class="text-danger small mt-1"><?= htmlspecialchars($errores[$fieldName]) ?></div><?php endif; ?>
                        </div>
                        <div class="form-group mb-8">
                            <?php $fieldName = 'subarea'; ?>
                            <label for="subarea" class="form-label fw-bolder">Subárea del conocimiento:</label>
                            <select id="subarea" name="subarea" class="form-select form-select-solid <?= !empty($errores[$fieldName]) ? 'is-invalid' : '' ?>" required data-editable>
                                <option value="">Seleccione</option>
                            </select>
                            <?php if (!empty($errores[$fieldName])): ?><div class="text-danger small mt-1"><?= htmlspecialchars($errores[$fieldName]) ?></div><?php endif; ?>
                        </div>
                        <div class="form-group mb-8">
                            <?php $fieldName = 'subarea_especifica'; ?>
                            <label for="subarea_especifica" class="form-label fw-bolder">Subárea específica:</label>
                            <select id="subarea_especifica" name="subarea_especifica" class="form-select form-select-solid <?= !empty($errores[$fieldName]) ? 'is-invalid' : '' ?>" required data-editable>
                                <option value="">Seleccione</option>
                            </select>
                            <?php if (!empty($errores[$fieldName])): ?><div class="text-danger small mt-1"><?= htmlspecialchars($errores[$fieldName]) ?></div><?php endif; ?>
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-end py-6 px-9">
                        <button type="submit" class="btn btn-primary">Guardar y Siguiente</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Datos iniciales de PHP
        const subareas_data = <?= $subareas_json ?? '[]' ?>;
        const especificas_data = <?= $especificas_json ?? '[]' ?>;
        const valorArea = "<?= $datos_enviados['area'] ?? '' ?>";
        const valorSubarea = "<?= $datos_enviados['subarea'] ?? '' ?>";
        const valorEspecifica = "<?= $datos_enviados['subarea_especifica'] ?? '' ?>";

        // Elementos del DOM
        const areaSelect = document.getElementById('areaC');
        const subareaSelect = document.getElementById('subarea');
        const subareaEspecificaSelect = document.getElementById('subarea_especifica');

        if (!areaSelect || !subareaSelect || !subareaEspecificaSelect) return;

        // Función para mostrar/ocultar secciones de checkboxes
        // Se deja como estaba, ya que el bloqueo se hará con 'disabled'
        window.toggleFields = function(checkbox, targetId) {
            var section = document.getElementById(targetId);
            var inputs = $(section).find('input, textarea');
            if (checkbox.checked) {
                section.style.display = 'block';
                inputs.prop('required', true);
            } else {
                section.style.display = 'none';
                inputs.prop('required', false).removeClass('is-invalid');
            }
        }
        
        /**
         * Función mejorada que aplica permisos y distingue entre tipos de input.
         */
        function aplicarPermisos() {
            <?php 
                if (!$permite_editar): ?>
            document.querySelectorAll('[data-editable]').forEach(function(el) {

                // --- INICIO DE LA CORRECCIÓN PARA CHECKBOX ---
                if (el.type === 'checkbox') {
                    // Si el checkbox está marcado, creamos un campo oculto para enviar su valor.
                    if (el.checked) {
                        const hidden = document.createElement('input');
                        hidden.type = 'hidden';
                        hidden.name = el.name;
                        hidden.value = el.value;
                        el.parentNode.insertBefore(hidden, el);
                    }
                    // Deshabilitamos el checkbox para prevenir clics y el "efecto fantasma".
                    el.disabled = true;
                }
                // --- FIN DE LA CORRECCIÓN PARA CHECKBOX ---
                
                else if (el.tagName === 'SELECT') {
                    // La lógica para SELECT sigue siendo la misma
                    if (!el.parentNode.querySelector(`input[type="hidden"][name="${el.name}"]`)) {
                        const hidden = document.createElement('input');
                        hidden.type = 'hidden';
                        hidden.name = el.name;
                        hidden.value = el.value;
                        el.parentNode.insertBefore(hidden, el);
                    }
                    el.disabled = true;
                    el.style.backgroundColor = '#f5f6fa';
                }
                // Para otros inputs (text, url, number) y textareas, 'readonly' es suficiente.
                else if (el.tagName === 'INPUT' || el.tagName === 'TEXTAREA') {
                    el.readOnly = true;
                    el.style.backgroundColor = '#f5f6fa';
                }
            });
            <?php endif; ?>

            <?php if (!$permite_comentar): ?>
            document.querySelectorAll('[data-comentario]').forEach(function(el) {
                if (el.tagName === 'TEXTAREA' || el.tagName === 'INPUT') el.setAttribute('readonly', true);
                el.style.backgroundColor = '#f5f6fa';
                if (!el.nextElementSibling || !el.nextElementSibling.classList.contains('solo-lectura-msg')) {
                    var span = document.createElement('span');
                    span.textContent = ' Solo lectura';
                    span.style.color = 'red';
                    span.style.fontWeight = 'bold';
                    span.className = 'solo-lectura-msg';
                    el.parentNode.insertBefore(span, el.nextSibling);
                }
            });
            <?php endif; ?>
        }

        // Lógica de eventos para los selects (sin cambios)
        areaSelect.addEventListener('change', function() {
            const areaId = this.value;
            subareaSelect.innerHTML = '<option value="">Seleccione</option>';
            subareaEspecificaSelect.innerHTML = '<option value="">Seleccione</option>';
            if (subareas_data[areaId]) {
                subareas_data[areaId].forEach(function(subarea) {
                    const option = document.createElement('option');
                    option.value = subarea.id;
                    option.textContent = subarea.name;
                    subareaSelect.appendChild(option);
                });
            }
        });

        subareaSelect.addEventListener('change', function() {
            const subareaId = this.value;
            subareaEspecificaSelect.innerHTML = '<option value="">Seleccione</option>';
            if (especificas_data[subareaId]) {
                especificas_data[subareaId].forEach(function(especifica) {
                    const option = document.createElement('option');
                    option.value = especifica.id;
                    option.textContent = especifica.name;
                    subareaEspecificaSelect.appendChild(option);
                });
            }
        });

        // Lógica de recarga inicial (sin cambios)
        if (valorArea) {
            areaSelect.value = valorArea;
            areaSelect.dispatchEvent(new Event('change'));

            setTimeout(function() {
                if (valorSubarea) {
                    subareaSelect.value = valorSubarea;
                    subareaSelect.dispatchEvent(new Event('change'));
                }
                setTimeout(function() {
                    if (valorEspecifica) {
                        subareaEspecificaSelect.value = valorEspecifica;
                    }
                    // Llamada crítica: Aplicamos los permisos DESPUÉS de poblar los datos.
                    aplicarPermisos();
                }, 150);
            }, 150);
        } else {
            // Si no hay datos, aplicamos permisos directamente.
            aplicarPermisos();
        }
    });
</script>

<?php require_once ROOT_PATH . '/app/views/layout/footer.php'; ?>