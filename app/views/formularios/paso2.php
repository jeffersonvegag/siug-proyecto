<?php
require_once ROOT_PATH . '/app/views/layout/header.php';

$pasoActual = 2;
require_once ROOT_PATH . '/app/views/layout/stepper_static.php';
?>

<div class="d-flex flex-column flex-column-fluid">
    <div class="app-content flex-column-fluid">
        <div class="">
            <form method="POST" action="?c=Formularios&m=paso2" class="form" id="formPaso2" novalidate>
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
                            <select id="objetivo_sostenible" name="objetivo_sostenible" class="form-select form-select-solid <?= !empty($errores[$fieldName]) ? 'is-invalid' : '' ?>" required>
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
                            <?php $fieldName = 'eje'; $valorPrevio = $datos_enviados[$fieldName] ?? ''; ?>
                            <label class="form-label fw-bolder" for="eje">1.6 Eje estratégico del Plan</label>
                            <select id="eje" name="eje" class="form-select form-select-solid <?= !empty($errores[$fieldName]) ? 'is-invalid' : '' ?>" required>
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
                            <?php $fieldName = 'objetivo_nacional'; ?>
                            <label class="form-label fw-bolder" for="objetivo_nacional">1.7 Objetivo del Plan Nacional de Desarrollo</label>
                            <select id="objetivo_nacional" name="objetivo_nacional" class="form-select form-select-solid <?= !empty($errores[$fieldName]) ? 'is-invalid' : '' ?>" required>
                                <option value="">Seleccione</option>
                            </select>
                             <?php if (!empty($errores[$fieldName])): ?><div class="text-danger small mt-1"><?= htmlspecialchars($errores[$fieldName]) ?></div><?php endif; ?>
                        </div>

                        <div class="form-group mb-8">
                            <?php $fieldName = 'dominios'; $valorPrevio = $datos_enviados[$fieldName] ?? ''; ?>
                            <label class="form-label fw-bolder" for="dominios">1.8 Alineación del proyecto a los dominios científicos</label>
                            <select id="dominios" name="dominios" class="form-select form-select-solid <?= !empty($errores[$fieldName]) ? 'is-invalid' : '' ?>" required>
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
                            <?php $fieldName = 'lineas'; ?>
                            <label class="form-label fw-bolder" for="lineas">1.9 Líneas de investigación institucionales</label>
                            <select id="lineas" name="lineas" class="form-select form-select-solid <?= !empty($errores[$fieldName]) ? 'is-invalid' : '' ?>" required>
                                <option value="">Seleccione</option>
                            </select>
                            <?php if (!empty($errores[$fieldName])): ?><div class="text-danger small mt-1"><?= htmlspecialchars($errores[$fieldName]) ?></div><?php endif; ?>
                        </div>

      
                    </div>

                    <div class="card-footer d-flex justify-content-end py-6 px-9">
                        <a href="?c=Formularios&m=paso1" id="btnAnterior" class="btn btn-secondary me-2">Anterior</a>
                        <button type="submit" class="btn btn-primary">Siguiente</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Pasamos los datos de PHP a variables de JavaScript
    const obj_nac = <?= $obj_nac_json ?? '[]' ?>;
    const lineas_data = <?= $lineas_json ?? '[]' ?>;
    
    // Obtenemos los valores guardados para re-seleccionar en los selects dinámicos
    const valorGuardadoObjNac = "<?= $datos_enviados['objetivo_nacional'] ?? '' ?>";
    const valorGuardadoLinea = "<?= $datos_enviados['lineas'] ?? '' ?>";

    // Función unificada para poblar un select
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

    document.addEventListener('DOMContentLoaded', function() {
        const ejeSelect = document.getElementById('eje');
        const objSelect = document.getElementById('objetivo_nacional');
        const dominioSelect = document.getElementById('dominios');
        const lineaSelect = document.getElementById('lineas');

        // Lógica para Eje -> Objetivos Nacionales
        if (ejeSelect && objSelect) {
            function cargarObjetivos() {
                const ejeId = ejeSelect.value;
                const dataParaPoblar = obj_nac[ejeId] || [];
                popularSelect(objSelect, dataParaPoblar, valorGuardadoObjNac);
            }
            ejeSelect.addEventListener('change', cargarObjetivos);
            if (ejeSelect.value) { // Si ya hay un valor (desde PHP), carga las opciones
                cargarObjetivos();
            }
        }

        // Lógica para Dominio -> Líneas de Investigación
        if (dominioSelect && lineaSelect) {
            function cargarLineas() {
                const dominioId = dominioSelect.value;
                const dataParaPoblar = lineas_data[dominioId] || [];
                popularSelect(lineaSelect, dataParaPoblar, valorGuardadoLinea);
            }
            dominioSelect.addEventListener('change', cargarLineas);
            if (dominioSelect.value) { // Si ya hay un valor, carga las opciones
                cargarLineas();
            }
        }
    });
</script>

<?php require_once ROOT_PATH . '/app/views/layout/footer.php'; ?>