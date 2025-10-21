<?php
require_once ROOT_PATH . '/app/views/layout/header.php';

// Marcar el paso actual en el stepper
$pasoActual = 1;
require_once ROOT_PATH . '/app/views/layout/stepper_static.php';
?>

<div class="d-flex flex-column flex-column-fluid">
    <div class="app-content flex-column-fluid">
        <div class="">
            <form method="POST" action="?c=Formularios&m=paso1" class="form" id="formPaso1" novalidate>
                <div class="card card-custom gutter-b">
                    <div class="card-header bg-dark  py-6">
                        <div class="card-title">
                            <h3 class="card-label text-white fw-bolder fs-2">1. Datos Generales del Proyecto</h3>
                        </div>
                    </div>
                    <div class="card-body">
                        
                        <div class="form-group mb-8">
                            <?php
                            $fieldName = 'Titulo';
                            $valorPrevio = $datos_enviados[$fieldName] ?? '';
                            ?>
                            <label for="Titulo" class="form-label fw-bolder">1.1. Título del proyecto:</label>
                            <input type="text" name="Titulo" id="Titulo"
                                class="form-control form-control-solid <?= !empty($errores[$fieldName]) ? 'is-invalid' : '' ?>"
                                placeholder="Ingrese el título del proyecto"
                                value="<?= htmlspecialchars($valorPrevio) ?>" required />
                            <?php if (!empty($errores[$fieldName])): ?>
                                <div class="text-danger small mt-1"><?= htmlspecialchars($errores[$fieldName]) ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="form-group mb-8">
                            <?php
                            $fieldName = 'eje_estrategico';
                            $valorPrevio = $datos_enviados[$fieldName] ?? '';
                            ?>
                            <label for="eje_estrategico" class="form-label fw-bolder">1.2. Eje estratégico de actuación:</label>
                            <select name="eje_estrategico" id="eje_estrategico" class="form-select form-select-solid <?= !empty($errores[$fieldName]) ? 'is-invalid' : '' ?>" required>
                                <option value="">Seleccione</option>
                                <?php foreach ($ejes_estrategicos as $eje): ?>
                                    <option value="<?= htmlspecialchars($eje['IdEje']) ?>" <?= ($valorPrevio == $eje['IdEje']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($eje['EjeEstrategico']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (!empty($errores[$fieldName])): ?>
                                <div class="text-danger small mt-1"><?= htmlspecialchars($errores[$fieldName]) ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="form-group mb-8">
                            <label class="form-label fw-bolder">1.3. Tipo de programa o proyecto con el que se articula:</label>
                            <?php if (!empty($errores['tipo_programa_general'])): ?>
                                <div class="alert alert-danger small p-2 mt-1"><?= htmlspecialchars($errores['tipo_programa_general']) ?></div>
                            <?php endif; ?>
                            <div>
                                <?php
                                foreach ($programas_articulacion as $programa):
                                    $section_id = 'section_programa_' . $programa['IdProgamaA'];
                                    $isChecked = isset($datos_enviados['tipo_programa']) && in_array($programa['IdProgamaA'], $datos_enviados['tipo_programa']);
                                ?>
                                    <div class="mb-6">
                                        <div class="form-check form-check-custom form-check-solid">
                                            <input class="form-check-input" type="checkbox" name="tipo_programa[]" value="<?= htmlspecialchars($programa['IdProgamaA']) ?>" id="programa_<?= $programa['IdProgamaA'] ?>" onclick="toggleFields(this, '<?= $section_id ?>')" <?= $isChecked ? 'checked' : '' ?> />
                                            <label class="form-check-label" for="programa_<?= $programa['IdProgamaA'] ?>">
                                                <?= htmlspecialchars($programa['Programa']) ?>
                                            </label>
                                        </div>
                                        <div id="<?= $section_id ?>" style="<?= $isChecked ? 'display:block;' : 'display:none;' ?>" class="mt-4 p-5 border rounded bg-light-secondary">

                                            <div class="form-group mb-5">
                                                <?php $fieldName = 'nombre_programa_' . $programa['IdProgamaA']; $valorPrevio = $datos_enviados[$fieldName] ?? ''; ?>
                                                <label class="form-label fw-bolder">Nombre del proyecto:</label>
                                                <input type="text" class="form-control form-control-solid <?= !empty($errores[$fieldName]) ? 'is-invalid' : '' ?>" name="<?= $fieldName ?>" value="<?= htmlspecialchars($valorPrevio) ?>" placeholder="Ingrese el nombre del proyecto" <?= $isChecked ? 'required' : '' ?> />
                                                <?php if (!empty($errores[$fieldName])): ?><div class="text-danger small mt-1"><?= htmlspecialchars($errores[$fieldName]) ?></div><?php endif; ?>
                                            </div>

                                            <div class="form-group mb-5">
                                                <?php $fieldName = 'autores_programa_' . $programa['IdProgamaA']; $valorPrevio = $datos_enviados[$fieldName] ?? ''; ?>
                                                <label class="form-label fw-bolder">Autores:</label>
                                                <input type="text" class="form-control form-control-solid <?= !empty($errores[$fieldName]) ? 'is-invalid' : '' ?>" name="<?= $fieldName ?>" value="<?= htmlspecialchars($valorPrevio) ?>" placeholder="Ingrese los autores" <?= $isChecked ? 'required' : '' ?> />
                                                <?php if (!empty($errores[$fieldName])): ?><div class="text-danger small mt-1"><?= htmlspecialchars($errores[$fieldName]) ?></div><?php endif; ?>
                                            </div>

                                            <div class="form-group mb-5">
                                                <?php $fieldName = 'anio_programa_' . $programa['IdProgamaA']; $valorPrevio = $datos_enviados[$fieldName] ?? ''; ?>
                                                <label class="form-label fw-bolder">Año:</label>
                                                <input type="number" class="form-control form-control-solid <?= !empty($errores[$fieldName]) ? 'is-invalid' : '' ?>" name="<?= $fieldName ?>" value="<?= htmlspecialchars($valorPrevio) ?>" placeholder="Ingrese el año" min="2015" max="2040" oninput="if (this.value.length > 4) this.value = this.value.slice(0, 4);" <?= $isChecked ? 'required' : '' ?> />
                                                <?php if (!empty($errores[$fieldName])): ?><div class="text-danger small mt-1"><?= htmlspecialchars($errores[$fieldName]) ?></div><?php endif; ?>
                                            </div>

                                            <div class="form-group mb-5">
                                                <?php $fieldName = 'enlace_programa_' . $programa['IdProgamaA']; $valorPrevio = $datos_enviados[$fieldName] ?? ''; ?>
                                                <label class="form-label fw-bolder">Enlace:</label>
                                                <input type="url" class="form-control form-control-solid <?= !empty($errores[$fieldName]) ? 'is-invalid' : '' ?>" name="<?= $fieldName ?>" value="<?= htmlspecialchars($valorPrevio) ?>" placeholder="Ingrese el enlace" />
                                                <?php if (!empty($errores[$fieldName])): ?><div class="text-danger small mt-1"><?= htmlspecialchars($errores[$fieldName]) ?></div><?php endif; ?>
                                            </div>

                                            <div class="form-group">
                                                <?php $fieldName = 'descripcion_programa_' . $programa['IdProgamaA']; $valorPrevio = $datos_enviados[$fieldName] ?? ''; ?>
                                                <label class="form-label fw-bolder">Resultados a transferir:</label>
                                                <textarea class="form-control form-control-solid <?= !empty($errores[$fieldName]) ? 'is-invalid' : '' ?>" rows="3" name="<?= $fieldName ?>" placeholder="Describa los resultados" <?= $isChecked ? 'required' : '' ?>><?= htmlspecialchars($valorPrevio) ?></textarea>
                                                <?php if (!empty($errores[$fieldName])): ?><div class="text-danger small mt-1"><?= htmlspecialchars($errores[$fieldName]) ?></div><?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <div class="form-group mb-8">
                            <?php $fieldName = 'area'; $valorPrevio = $datos_enviados[$fieldName] ?? ''; ?>
                            <label for="areaC" class="form-label fw-bolder">Área de conocimiento:</label>
                            <select id="areaC" name="area" class="form-select form-select-solid <?= !empty($errores[$fieldName]) ? 'is-invalid' : '' ?>" required>
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
                            <select id="subarea" name="subarea" class="form-select form-select-solid <?= !empty($errores[$fieldName]) ? 'is-invalid' : '' ?>" required>
                                <option value="">Seleccione</option>
                            </select>
                            <?php if (!empty($errores[$fieldName])): ?><div class="text-danger small mt-1"><?= htmlspecialchars($errores[$fieldName]) ?></div><?php endif; ?>
                        </div>
                        <div class="form-group mb-8">
                            <?php $fieldName = 'subarea_especifica'; ?>
                            <label for="subarea_especifica" class="form-label fw-bolder">Subárea específica:</label>
                            <select id="subarea_especifica" name="subarea_especifica" class="form-select form-select-solid <?= !empty($errores[$fieldName]) ? 'is-invalid' : '' ?>" required>
                                <option value="">Seleccione</option>
                            </select>
                            <?php if (!empty($errores[$fieldName])): ?><div class="text-danger small mt-1"><?= htmlspecialchars($errores[$fieldName]) ?></div><?php endif; ?>
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-end py-6 px-9">
                        <button type="submit" class="btn btn-primary">Siguiente</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    
    // --- 1. DEFINICIÓN DE ELEMENTOS Y DATOS ---
    const areaSelect = document.getElementById('areaC');
    const subareaSelect = document.getElementById('subarea');
    const subareaEspecificaSelect = document.getElementById('subarea_especifica');
    const checkboxes = document.querySelectorAll('input[name="tipo_programa[]"]');

    // Datos pasados desde PHP
    const subareasData = <?= $subareas_json ?? '{}' ?>;
    const especificasData = <?= $especificas_json ?? '{}' ?>;
    const valoresGuardados = {
        subarea: '<?= $datos_enviados['subarea'] ?? '' ?>',
        especifica: '<?= $datos_enviados['subarea_especifica'] ?? '' ?>'
    };

    // --- 2. FUNCIONES AUXILIARES (HELPERS) ---

    /**
     * REFACTORIZACIÓN: Función genérica para poblar un select.
     * Evita repetir el bucle para crear opciones.
     * @param {HTMLSelectElement} selectEl - El elemento select a poblar.
     * @param {Array} items - El array de datos (ej: [{id: 1, name: 'Ciencia'}])
     */
    function populateSelect(selectEl, items) {
        selectEl.innerHTML = '<option value="">Seleccione</option>'; // Limpia y añade la opción por defecto
        if (items && items.length > 0) {
            items.forEach(item => {
                // new Option() es una forma más limpia y estándar de crear opciones
                const option = new Option(item.name, item.id);
                selectEl.add(option);
            });
        }
    }

    /**
     * Gestiona la visibilidad de las secciones dinámicas.
     * Se ha eliminado la gestión del atributo 'required'.
     * @param {HTMLInputElement} checkbox - El checkbox que fue clickeado.
     */
    function toggleFields(checkbox) {
        const targetId = 'section_programa_' + checkbox.value;
        const section = document.getElementById(targetId);
        if (!section) return;

        section.style.display = checkbox.checked ? 'block' : 'none';

        // Si se desmarca, opcionalmente limpiar los valores y errores
        if (!checkbox.checked) {
            const inputs = section.querySelectorAll('input, textarea');
            inputs.forEach(input => {
                input.classList.remove('is-invalid');
                if (input.type !== 'checkbox' && input.type !== 'radio') {
                    input.value = '';
                }
            });
        }
    }


    // --- 3. LÓGICA DE EVENTOS ---

    // Asigna el evento 'click' a cada checkbox para llamar a la función toggleFields
    checkboxes.forEach(checkbox => {
        // La función original se llamaba desde el HTML con onclick, ahora se asigna aquí.
        checkbox.addEventListener('click', () => toggleFields(checkbox));
    });

    // Evento para el select de Áreas
    areaSelect.addEventListener('change', function() {
        const areaId = this.value;
        populateSelect(subareaSelect, subareasData[areaId] || []);
        populateSelect(subareaEspecificaSelect, []); // Limpia el último select para forzar nueva selección

        // MEJORA: Si hay un valor guardado para 'subarea', lo seleccionamos aquí directamente
        if (subareaSelect.value !== valoresGuardados.subarea && valoresGuardados.subarea) {
            subareaSelect.value = valoresGuardados.subarea;
            // Disparamos el evento 'change' en la subárea para cargar el siguiente nivel
            subareaSelect.dispatchEvent(new Event('change'));
        }
    });

    // Evento para el select de Subáreas
    subareaSelect.addEventListener('change', function() {
        const subareaId = this.value;
        populateSelect(subareaEspecificaSelect, especificasData[subareaId] || []);

        // MEJORA: Si hay un valor guardado para 'subarea_especifica', lo seleccionamos
        if (subareaEspecificaSelect.value !== valoresGuardados.especifica && valoresGuardados.especifica) {
            subareaEspecificaSelect.value = valoresGuardados.especifica;
        }
    });


    // --- 4. LÓGICA DE CARGA INICIAL (ROBUSTA Y SIN TIMEOUTS) ---
    
    /**
     * MEJORA: Esta función inicializa el estado del formulario de forma segura.
     * Se asegura de que los selects dependientes se carguen en cascada correctamente.
     */
    function initializeFormState() {
        // Si ya hay un área seleccionada (porque se recargó la página con datos),
        // disparamos su evento 'change' para iniciar la cascada.
        if (areaSelect.value) {
            areaSelect.dispatchEvent(new Event('change'));
        }
    }

    // Ejecutamos la función de inicialización cuando el DOM está listo.
    initializeFormState();

});
</script>

<?php require_once ROOT_PATH . '/app/views/layout/footer.php'; ?>