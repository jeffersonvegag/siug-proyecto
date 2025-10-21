<?php
require_once ROOT_PATH . '/app/views/layout/header.php';

$pasoActual = 9;
require_once ROOT_PATH . '/app/views/layout/stepper_static.php';

// --- CORRECCIÓN: Lógica de datos restaurada para manejar doble capitalización (de BD y de POST) ---
$datos_a_usar = $datos_enviados ?? $datos_guardados;

$comentarios_resultados = $datos_a_usar['comentarios_resultados'] ?? ($datos_a_usar['ComentariosResultados'] ?? '');
$comentarios_publicaciones = $datos_a_usar['comentarios_publicaciones'] ?? ($datos_a_usar['ComentariosPublicaciones'] ?? '');
$comentarios_otros_productos = $datos_a_usar['comentarios_otros_productos'] ?? ($datos_a_usar['ComentariosOtrosProductos'] ?? '');
$efectos_nuevas_investigaciones = $datos_a_usar['efectos_nuevas_investigaciones'] ?? ($datos_a_usar['EfectosNuevasInvestigaciones'] ?? '');
$efectos_nuevas_metodologias = $datos_a_usar['efectos_nuevas_metodologias'] ?? ($datos_a_usar['EfectosNuevasMetodologias'] ?? '');
$efectos_nuevos_trabajos_titulacion = $datos_a_usar['efectos_nuevos_trabajos_titulacion'] ?? ($datos_a_usar['EfectosNuevosTrabajosTitulacion'] ?? '');
$comentarios_impactos_ug = $datos_a_usar['comentarios_impactos_ug'] ?? ($datos_a_usar['ComentariosImpactosUg'] ?? '');
$referencias_citadas = $datos_a_usar['referencias_citadas'] ?? ($datos_a_usar['ReferenciasCitadas'] ?? '');
$comentarios_referencias = $datos_a_usar['comentarios_referencias'] ?? ($datos_a_usar['ComentariosReferencias'] ?? '');

$ponencias_nacionales = $datos_a_usar['ponencias_nacionales'] ?? ($datos_a_usar['PonenciasNacionales'] ?? 0);
$ponencias_internacionales = $datos_a_usar['ponencias_internacionales'] ?? ($datos_a_usar['PonenciasInternacionales'] ?? 0);
$articulos_cientificos = $datos_a_usar['articulos_cientificos'] ?? ($datos_a_usar['ArticulosCientificos'] ?? 0);
$libros_publicados = $datos_a_usar['libros_publicados'] ?? ($datos_a_usar['LibrosPublicados'] ?? 0);
$capitulos_libros = $datos_a_usar['capitulos_libros'] ?? ($datos_a_usar['CapitulosLibros'] ?? 0);
$revistas_divulgacion = $datos_a_usar['revistas_divulgacion'] ?? ($datos_a_usar['RevistasDivulgacion'] ?? 0);
$otras_publicaciones = $datos_a_usar['otras_publicaciones'] ?? ($datos_a_usar['OtrasPublicaciones'] ?? 0);
$talleres_capacitacion = $datos_a_usar['talleres_capacitacion'] ?? ($datos_a_usar['TalleresCapacitacion'] ?? 0);
$productos_tecnologicos = $datos_a_usar['productos_tecnologicos'] ?? ($datos_a_usar['ProductosTecnologicos'] ?? 0);
$productos_artisticos = $datos_a_usar['productos_artisticos'] ?? ($datos_a_usar['ProductosArtisticos'] ?? 0);
$productos_culturales = $datos_a_usar['productos_culturales'] ?? ($datos_a_usar['ProductosCulturales'] ?? 0);
$productos_sociales = $datos_a_usar['productos_sociales'] ?? ($datos_a_usar['ProductosSociales'] ?? 0);
?>

<div class="d-flex flex-column flex-column-fluid">
    <div class="app-content flex-column-fluid">
        <form method="post" action="?c=Formularios&m=actualizarPaso9&id=<?= htmlspecialchars($idProyecto) ?>" class="form" id="formPaso9" novalidate>
            <div class="card card-custom gutter-b">
                <div class="card-header bg-dark py-6">
                    <div class="card-title"><h3 class="card-label text-white fw-bolder fs-2">9. RESULTADOS Y PRODUCTOS</h3></div>
                </div>

                <div class="card-body">
                    <h2 class="fs-4 fw-bolder mb-5">4.11 RESULTADOS Y PRODUCTOS ESPERADOS</h2>
                    
                    <div id="resultados-container">
                        <?php if (empty($objetivos_especificos)): ?>
                            <div class="alert alert-warning">No se encontraron objetivos específicos. Por favor, regrese al Paso 8.</div>
                        <?php else: ?>
                            <?php foreach ($objetivos_especificos as $objIndex => $objetivo): ?>
                                <div class="card card-body bg-light-secondary p-6 mb-9" data-objetivo-index="<?= $objIndex ?>">
                                    <div class="d-flex justify-content-between align-items-center mb-4">
                                        <h3 class="fs-5 fw-bolder text-primary m-0">OE<?= $objIndex + 1 ?>: <?= htmlspecialchars($objetivo['texto']) ?></h3>
                                        <button type="button" class="btn btn-primary btn-sm btn-agregar-indicador" data-editable><i class="bi bi-plus-lg"></i> Agregar Indicador</button>
                                    </div>
                                    <div class="indicadores-lista">
                                        <?php 
                                        $indicadores_a_mostrar = $datos_a_usar['indicador'][$objIndex] ?? ($datos_guardados['resultados_esperados'][$objIndex] ?? [['indicador' => '', 'resultado' => '', 'productos' => ['']]]);
                                        foreach($indicadores_a_mostrar as $indIndex => $data_indicador):
                                        ?>
                                        <div class="row gx-5 gy-3 border-bottom pb-5 mb-5 indicador-fila">
                                            <div class="col-md-3">
                                                <label class="form-label fw-bolder fs-7">Indicador</label>
                                                <select name="indicador[<?= $objIndex ?>][]" class="form-select form-select-solid" data-editable required>
                                                    <option value="">Seleccione...</option>
                                                    <?php foreach($lista_indicadores as $opt_indicador): ?>
                                                        <option value="<?= htmlspecialchars($opt_indicador) ?>" <?= (($data_indicador['indicador'] ?? '') == $opt_indicador) ? 'selected' : '' ?>><?= htmlspecialchars($opt_indicador) ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label fw-bolder fs-7">Resultado</label>
                                                <input type="text" name="resultado[<?= $objIndex ?>][]" value="<?= htmlspecialchars($data_indicador['resultado'] ?? '') ?>" class="form-control form-control-solid" placeholder="Describa el resultado" data-editable required>
                                            </div>
                                            <div class="col-md-5">
                                                <label class="form-label fw-bolder fs-7">Productos</label>
                                                <div class="productos-lista">
                                                    <?php foreach(($data_indicador['productos'] ?? ['']) as $producto_texto): ?>
                                                    <div class="input-group mb-3 producto-fila">
                                                        <input type="text" name="producto[<?= $objIndex ?>][<?= $indIndex ?>][]" value="<?= htmlspecialchars($producto_texto) ?>" class="form-control form-control-solid" placeholder="Describa el producto" data-editable required>
                                                        <button class="btn btn-outline-danger btn-sm btn-eliminar-producto" type="button" title="Eliminar producto" data-editable><i class="bi bi-trash"></i></button>
                                                    </div>
                                                    <?php endforeach; ?>
                                                </div>
                                                <button type="button" class="btn btn-sm btn-light-primary mt-1 btn-agregar-producto" data-editable><i class="bi bi-plus-lg"></i> Agregar Producto</button>
                                            </div>
                                            <div class="col-12 text-end">
                                                <button type="button" class="btn btn-sm btn-light-danger btn-eliminar-indicador" data-editable>Eliminar Fila de Indicador</button>
                                            </div>
                                        </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <div class="form-group mb-8">
                        <label class="form-label fw-bolder">Comentarios:</label>
                        <textarea name="comentarios_resultados" class="form-control form-control-solid" rows="2" placeholder=". . ." data-comentario><?= htmlspecialchars($comentarios_resultados) ?></textarea>
                    </div>

                    <h2 class="fs-4 fw-bolder mb-5 mt-10">4.12 Contribución a la divulgación y transferencia</h2>
                    <h4 class="fs-6 fw-bolder mb-5 mt-7">4.12.1 Ponencias / Publicaciones:</h4>
                    <div class="table-responsive mb-8">
                        <table class="table table-bordered table-striped">
                            <thead><tr><th class="text-center">Descripción</th><th class="text-center" style="width: 150px;">Total</th></tr></thead>
                            <tbody>
                                <tr><td>Número de ponencias nacionales indexadas con código ISBN y texto completo</td><td><input type="number" name="ponencias_nacionales" class="form-control" value="<?= htmlspecialchars($ponencias_nacionales) ?>" min="0" data-editable></td></tr>
                                <tr><td>Número de ponencias internacionales indexadas con código ISBN y texto completo</td><td><input type="number" name="ponencias_internacionales" class="form-control" value="<?= htmlspecialchars($ponencias_internacionales) ?>" min="0" data-editable></td></tr>
                                <tr><td>Número de artículos científicos publicados en revistas indexadas (Scopus, Scielo, Latindex, etc.)</td><td><input type="number" name="articulos_cientificos" class="form-control" value="<?= htmlspecialchars($articulos_cientificos) ?>" min="0" data-editable></td></tr>
                                <tr><td>Número de libros publicados con código ISBN</td><td><input type="number" name="libros_publicados" class="form-control" value="<?= htmlspecialchars($libros_publicados) ?>" min="0" data-editable></td></tr>
                                <tr><td>Número de capítulos de libros publicados con código ISBN</td><td><input type="number" name="capitulos_libros" class="form-control" value="<?= htmlspecialchars($capitulos_libros) ?>" min="0" data-editable></td></tr>
                                <tr><td>Número de publicaciones en revistas de divulgación científica o tecnológica</td><td><input type="number" name="revistas_divulgacion" class="form-control" value="<?= htmlspecialchars($revistas_divulgacion) ?>" min="0" data-editable></td></tr>
                                <tr><td>Número de publicaciones en otros medios (periódicos, revistas, blogs, etc.)</td><td><input type="number" name="otras_publicaciones" class="form-control" value="<?= htmlspecialchars($otras_publicaciones) ?>" min="0" data-editable></td></tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="form-group mb-8">
                        <label class="form-label fw-bolder">Comentarios:</label>
                        <textarea name="comentarios_publicaciones" class="form-control form-control-solid" rows="2" placeholder=". . ." data-comentario><?= htmlspecialchars($comentarios_publicaciones ?? '') ?></textarea>
                    </div>

                    <h4 class="fs-6 fw-bolder mb-5 mt-10">4.12.2 Otros productos de transferencia de conocimiento:</h4>
                    <div class="table-responsive mb-8">
                        <table class="table table-bordered table-striped">
                            <thead><tr><th class="text-center">Descripción</th><th class="text-center" style="width: 150px;">Total</th></tr></thead>
                            <tbody>
                                <tr><td>Número de talleres, seminarios, charlas, cursos de capacitación, etc.</td><td><input type="number" name="talleres_capacitacion" class="form-control" value="<?= htmlspecialchars($talleres_capacitacion) ?>" min="0" data-editable></td></tr>
                                <tr><td>Número de productos tecnológicos (software, prototipos, patentes, etc.)</td><td><input type="number" name="productos_tecnologicos" class="form-control" value="<?= htmlspecialchars($productos_tecnologicos) ?>" min="0" data-editable></td></tr>
                                <tr><td>Número de productos artísticos (obras de arte, exposiciones, etc.)</td><td><input type="number" name="productos_artisticos" class="form-control" value="<?= htmlspecialchars($productos_artisticos) ?>" min="0" data-editable></td></tr>
                                <tr><td>Número de productos culturales (eventos, festivales, etc.)</td><td><input type="number" name="productos_culturales" class="form-control" value="<?= htmlspecialchars($productos_culturales) ?>" min="0" data-editable></td></tr>
                                <tr><td>Número de productos sociales (modelos de intervención, metodologías, etc.)</td><td><input type="number" name="productos_sociales" class="form-control" value="<?= htmlspecialchars($productos_sociales) ?>" min="0" data-editable></td></tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="form-group mb-8">
                        <label class="form-label fw-bolder">Comentarios:</label>
                        <textarea name="comentarios_otros_productos" class="form-control form-control-solid" rows="2" placeholder=". . ." data-comentario><?= htmlspecialchars($comentarios_otros_productos ?? '') ?></textarea>
                    </div>

                    <h2 class="fs-4 fw-bolder mb-5 mt-10">4.13 Impactos del proyecto en la Universidad de Guayaquil</h2>
                    <div class="table-responsive mb-8">
                        <table class="table table-bordered table-striped">
                            <thead><tr><th>Efectos</th><th>Descripción</th></tr></thead>
                            <tbody>
                                <tr><td>Nuevas investigaciones</td><td><textarea name="efectos_nuevas_investigaciones" class="form-control" rows="2" data-editable><?= htmlspecialchars($efectos_nuevas_investigaciones) ?></textarea></td></tr>
                                <tr><td>Nuevas metodologías, procesos o técnicas aplicables</td><td><textarea name="efectos_nuevas_metodologias" class="form-control" rows="2" data-editable><?= htmlspecialchars($efectos_nuevas_metodologias) ?></textarea></td></tr>
                                <tr><td>Nuevos trabajos de titulación (grado y/o posgrado)</td><td><textarea name="efectos_nuevos_trabajos_titulacion" class="form-control" rows="2" data-editable><?= htmlspecialchars($efectos_nuevos_trabajos_titulacion) ?></textarea></td></tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="form-group mb-8">
                        <label class="form-label fw-bolder">Comentarios:</label>
                        <textarea name="comentarios_impactos_ug" class="form-control form-control-solid" rows="2" placeholder=". . ." data-comentario><?= htmlspecialchars($comentarios_impactos_ug) ?></textarea>
                    </div>

                    <h2 class="fs-4 fw-bolder mb-5 mt-10">4.14 Referencias citadas:</h2>
                    <div class="form-group mb-8">
                        <textarea name="referencias_citadas" class="form-control form-control-solid" rows="5" placeholder="Ingrese las referencias citadas aquí..." data-editable required><?= htmlspecialchars($referencias_citadas) ?></textarea>
                    </div>
                     <div class="form-group mb-8">
                        <label class="form-label fw-bolder">Comentarios:</label>
                        <textarea name="comentarios_referencias" class="form-control form-control-solid" rows="2" placeholder=". . ." data-comentario><?= htmlspecialchars($comentarios_referencias) ?></textarea>
                    </div>
                </div>

                <div class="card-footer d-flex justify-content-end py-6 px-9">
                    <a href="?c=Formularios&m=editarPaso8&id=<?= htmlspecialchars($idProyecto) ?>" id="btnAnterior" class="btn btn-secondary me-2">Anterior</a>
                    <button type="submit" class="btn btn-primary" >Guardar y Siguiente</button>
                </div>
            </div>
        </form>
    </div>
</div>

<template id="template-indicador-fila">
    <div class="row gx-5 gy-3 border-bottom pb-5 mb-5 indicador-fila">
        <div class="col-md-3"><label class="form-label fw-bolder fs-7">Indicador</label><select class="form-select form-select-solid" data-editable required><option value="">Seleccione...</option><?php foreach($lista_indicadores as $opt): ?><option value="<?= htmlspecialchars($opt) ?>"><?= htmlspecialchars($opt) ?></option><?php endforeach; ?></select></div>
        <div class="col-md-4"><label class="form-label fw-bolder fs-7">Resultado</label><input type="text" class="form-control form-control-solid" placeholder="Describa el resultado" data-editable required></div>
        <div class="col-md-5"><label class="form-label fw-bolder fs-7">Productos</label><div class="productos-lista"></div><button type="button" class="btn btn-sm btn-light-primary mt-1 btn-agregar-producto" data-editable><i class="bi bi-plus-lg"></i> Agregar Producto</button></div>
        <div class="col-12 text-end"><button type="button" class="btn btn-sm btn-light-danger btn-eliminar-indicador" data-editable>Eliminar Fila de Indicador</button></div>
    </div>
</template>
<template id="template-producto-fila">
    <div class="input-group mb-3 producto-fila">
        <input type="text" class="form-control form-control-solid" placeholder="Describa el producto" data-editable required>
        <button class="btn btn-outline-danger btn-sm btn-eliminar-producto" type="button" title="Eliminar este producto" data-editable><i class="bi bi-trash"></i></button>
    </div>
</template>

<script>
function aplicarPermisos(scope = document) {
    <?php if (!$permite_editar): ?>
    scope.querySelectorAll('[data-editable]').forEach(function(el) {
        if(el.tagName === 'BUTTON') { el.disabled = true; el.style.pointerEvents = 'none'; el.style.opacity = '0.5'; }
        else if (el.tagName === 'SELECT') {
            if (!el.parentNode.querySelector(`input[type="hidden"][name="${el.name}"]`)) {
                const hidden = document.createElement('input');
                hidden.type = 'hidden'; hidden.name = el.name; hidden.value = el.value;
                el.parentNode.insertBefore(hidden, el);
            }
            el.disabled = true; el.style.backgroundColor = '#f5f6fa';
        } else { el.readOnly = true; el.style.backgroundColor = '#f5f6fa'; }
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

document.addEventListener('DOMContentLoaded', () => {
    const resultadosContainer = document.getElementById('resultados-container');
    const indicadorTemplate = document.getElementById('template-indicador-fila');
    const productoTemplate = document.getElementById('template-producto-fila');

    const crearFilaProducto = (listaProductos, objIndex, indIndex) => {
        const nuevaFila = productoTemplate.content.cloneNode(true);
        const input = nuevaFila.querySelector('input');
        input.name = `producto[${objIndex}][${indIndex}][]`;
        listaProductos.appendChild(nuevaFila);
        aplicarPermisos(listaProductos.lastElementChild);
    };

    const crearFilaIndicador = (listaIndicadores, objIndex) => {
        const nuevaFila = indicadorTemplate.content.cloneNode(true);
        const indIndex = listaIndicadores.children.length;
        nuevaFila.querySelector('select').name = `indicador[${objIndex}][]`;
        nuevaFila.querySelector('input').name = `resultado[${objIndex}][]`;
        const listaProductos = nuevaFila.querySelector('.productos-lista');
        crearFilaProducto(listaProductos, objIndex, indIndex);
        listaIndicadores.appendChild(nuevaFila);
        aplicarPermisos(listaIndicadores.lastElementChild);
    };

    resultadosContainer?.addEventListener('click', (e) => {
        const target = e.target.closest('button');
        if (!target) return;
        if (target.matches('.btn-agregar-indicador')) {
            const seccion = target.closest('[data-objetivo-index]');
            crearFilaIndicador(seccion.querySelector('.indicadores-lista'), seccion.dataset.objetivoIndex);
        }
        if (target.matches('.btn-eliminar-indicador')) {
            const fila = target.closest('.indicador-fila');
            if (fila.parentElement.children.length > 1) fila.remove();
            else Swal.fire('Acción no permitida', 'Debe existir al menos un indicador por objetivo.', 'warning');
        }
        if (target.matches('.btn-agregar-producto')) {
            const filaIndicador = target.closest('.indicador-fila');
            const seccion = filaIndicador.closest('[data-objetivo-index]');
            const objIndex = seccion.dataset.objetivoIndex;
            const indIndex = Array.from(filaIndicador.parentElement.children).indexOf(filaIndicador);
            crearFilaProducto(filaIndicador.querySelector('.productos-lista'), objIndex, indIndex);
        }
        if (target.matches('.btn-eliminar-producto')) {
            const fila = target.closest('.producto-fila');
            if (fila.parentElement.children.length > 1) fila.remove();
            else Swal.fire('Acción no permitida', 'Debe existir al menos un producto por resultado.', 'warning');
        }
    });
    
    aplicarPermisos(document.getElementById('formPaso9'));
});
</script>

<?php require_once ROOT_PATH . '/app/views/layout/footer.php'; ?>