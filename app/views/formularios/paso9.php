<?php require_once ROOT_PATH . '/app/views/layout/header.php';

$pasoActual = 9;
require_once ROOT_PATH . '/app/views/layout/stepper_static.php';

// --- INICIO: Lógica robusta para repoblar todos los campos ---

$efectos_nuevas_investigaciones = $datos_enviados['efectos_nuevas_investigaciones'] ?? '';
$efectos_nuevas_metodologias = $datos_enviados['efectos_nuevas_metodologias'] ?? '';
$efectos_nuevos_trabajos_titulacion = $datos_enviados['efectos_nuevos_trabajos_titulacion'] ?? '';

$referencias_citadas = $datos_enviados['referencias_citadas'] ?? '';


$ponencias_nacionales = $datos_enviados['ponencias_nacionales'] ?? 0;
$ponencias_internacionales = $datos_enviados['ponencias_internacionales'] ?? 0;
$articulos_cientificos = $datos_enviados['articulos_cientificos'] ?? 0;
$libros_publicados = $datos_enviados['libros_publicados'] ?? 0;
$capitulos_libros = $datos_enviados['capitulos_libros'] ?? 0;
$revistas_divulgacion = $datos_enviados['revistas_divulgacion'] ?? 0;
$otras_publicaciones = $datos_enviados['otras_publicaciones'] ?? 0;
$talleres_capacitacion = $datos_enviados['talleres_capacitacion'] ?? 0;
$productos_tecnologicos = $datos_enviados['productos_tecnologicos'] ?? 0;
$productos_artisticos = $datos_enviados['productos_artisticos'] ?? 0;
$productos_culturales = $datos_enviados['productos_culturales'] ?? 0;
$productos_sociales = $datos_enviados['productos_sociales'] ?? 0;

$resultados_esperados = $datos_enviados['resultados_esperados'] ?? [];
// --- FIN: Lógica de repoblación ---
?>

<div class="d-flex flex-column flex-column-fluid">
    <div class="app-content flex-column-fluid">
        <form method="post" action="?c=Formularios&m=paso9" class="form" id="formPaso9" novalidate>
            <div class="card card-custom gutter-b">
                <div class="card-header bg-dark py-6">
                    <div class="card-title">
                        <h3 class="card-label text-white fw-bolder fs-2">9. RESULTADOS Y PRODUCTOS</h3>
                    </div>
                </div>

                <div class="card-body">
                    <h2 class="fs-4 fw-bolder mb-5">4.11 RESULTADOS Y PRODUCTOS ESPERADOS</h2>
                    <?php if (!empty($errores['resultados_general'])): ?><div class="alert alert-danger p-2"><?= htmlspecialchars($errores['resultados_general']) ?></div><?php endif; ?>

                    <div id="resultados-container">
                        <?php if (empty($objetivos_especificos)): ?>
                            <div class="alert alert-warning">No se encontraron objetivos específicos del paso anterior. Por favor, regrese al Paso 8 y agréguelos.</div>
                        <?php else: ?>
                            <?php foreach ($objetivos_especificos as $objIndex => $objetivo): ?>
                                <div class="card card-body bg-light-secondary p-6 mb-9" data-objetivo-index="<?= $objIndex ?>">
                                    <div class="d-flex justify-content-between align-items-center mb-4">
                                        <h3 class="fs-5 fw-bolder text-primary m-0">OE<?= $objIndex + 1 ?>: <?= htmlspecialchars($objetivo['texto']) ?></h3>
                                        <button type="button" class="btn btn-primary btn-sm btn-agregar-indicador"><i class="bi bi-plus-lg"></i> Agregar Indicador</button>
                                    </div>
                                    <div class="indicadores-lista">
                                        <?php
                                        // --- INICIO DE LA CORRECCIÓN ---
                                        // Esta lógica es más simple y segura
                                        $indicadores_a_mostrar = [];

                                        // CASO 1: Hay un error y estamos repoblando desde $_POST
                                        if (isset($datos_enviados['indicador'][$objIndex])) {
                                            foreach ($datos_enviados['indicador'][$objIndex] as $i => $val) {
                                                $indicadores_a_mostrar[$i]['indicador'] = $datos_enviados['indicador'][$objIndex][$i];
                                                $indicadores_a_mostrar[$i]['resultado'] = $datos_enviados['resultado'][$objIndex][$i];
                                                $indicadores_a_mostrar[$i]['productos'] = $datos_enviados['producto'][$objIndex][$i] ?? [''];
                                            }
                                        }
                                        // CASO 2: Es la carga inicial desde la Base de Datos
                                        else if (isset($resultados_esperados[$objIndex])) {
                                            $indicadores_a_mostrar = $resultados_esperados[$objIndex];
                                        }
                                        // CASO 3: Es la primera vez que se carga y no hay nada en la BD
                                        else {
                                            $indicadores_a_mostrar = [['indicador' => '', 'resultado' => '', 'productos' => ['']]];
                                        }
                                        // --- FIN DE LA CORRECCIÓN ---

                                        foreach ($indicadores_a_mostrar as $indIndex => $data_indicador):
                                            // El resto del código para mostrar la fila del indicador no cambia
                                        ?>
                                            <div class="row gx-5 gy-3 border-bottom pb-5 mb-5 indicador-fila">
                                                <div class="col-md-3">
                                                    <label class="form-label fw-bolder fs-7">Indicador</label>
                                                    <?php if (!empty($errores['indicador_obj_' . $objIndex])): ?><div class="text-danger small mb-1"><?= htmlspecialchars($errores['indicador_obj_' . $objIndex]) ?></div><?php endif; ?>
                                                    <select name="indicador[<?= $objIndex ?>][]" class="form-select form-select-solid">
                                                        <option value="">Seleccione...</option>
                                                        <?php foreach ($lista_indicadores as $opt_indicador): ?>
                                                            <option value="<?= htmlspecialchars($opt_indicador) ?>" <?= (isset($data_indicador['indicador']) && $opt_indicador == $data_indicador['indicador']) ? 'selected' : '' ?>><?= htmlspecialchars($opt_indicador) ?></option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label fw-bolder fs-7">Resultado</label>
                                                    <input type="text" name="resultado[<?= $objIndex ?>][]" value="<?= htmlspecialchars($data_indicador['resultado'] ?? '') ?>" class="form-control form-control-solid" placeholder="Describa el resultado">
                                                </div>
                                                <div class="col-md-5">
                                                    <label class="form-label fw-bolder fs-7">Productos</label>
                                                    <div class="productos-lista">
                                                        <?php
                                                        $productos_guardados = $data_indicador['productos'] ?? [''];
                                                        foreach ($productos_guardados as $producto_texto):
                                                        ?>
                                                            <div class="input-group mb-3 producto-fila">
                                                                <input type="text" name="producto[<?= $objIndex ?>][<?= $indIndex ?>][]" value="<?= htmlspecialchars($producto_texto) ?>" class="form-control form-control-solid" placeholder="Describa el producto">
                                                                <button class="btn btn-outline-danger btn-sm btn-eliminar-producto" type="button" title="Eliminar producto"><i class="bi bi-trash"></i></button>
                                                            </div>
                                                        <?php endforeach; ?>
                                                    </div>
                                                    <button type="button" class="btn btn-sm btn-light-primary mt-1 btn-agregar-producto"><i class="bi bi-plus-lg"></i> Agregar Producto</button>
                                                </div>
                                                <div class="col-12 text-end">
                                                    <button type="button" class="btn btn-sm btn-light-danger btn-eliminar-indicador">Eliminar Fila de Indicador</button>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>


                    <h2 class="fs-4 fw-bolder mb-5 mt-10">4.12 Contribución a la divulgación de resultados y transferencia de conocimiento</h2>

<h4 class="fs-6 fw-bolder mb-5 mt-7">4.12.1 Ponencias / Publicaciones:</h4>
<div class="table-responsive mb-8">
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th class="text-center">Descripción</th>
                <th class="text-center" style="width: 150px;">Total</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Número de ponencias nacionales indexadas con código ISBN y texto completo</td>
                <td>
                    <input type="number" name="ponencias_nacionales" class="form-control" value="<?= ($ponencias_nacionales != 0) ? htmlspecialchars($ponencias_nacionales) : '' ?>" min="0" placeholder="0">
                </td>
            </tr>
            <tr>
                <td>Número de ponencias internacionales indexadas con código ISBN y texto completo</td>
                <td>
                    <input type="number" name="ponencias_internacionales" class="form-control" value="<?= ($ponencias_internacionales != 0) ? htmlspecialchars($ponencias_internacionales) : '' ?>" min="0" placeholder="0">
                </td>
            </tr>
            <tr>
                <td>Número de artículos científicos publicados en revistas indexadas (Scopus, Scielo, Latindex, etc.)</td>
                <td>
                    <input type="number" name="articulos_cientificos" class="form-control" value="<?= ($articulos_cientificos != 0) ? htmlspecialchars($articulos_cientificos) : '' ?>" min="0" placeholder="0">
                </td>
            </tr>
            <tr>
                <td>Número de libros publicados con código ISBN</td>
                <td>
                    <input type="number" name="libros_publicados" class="form-control" value="<?= ($libros_publicados != 0) ? htmlspecialchars($libros_publicados) : '' ?>" min="0" placeholder="0">
                </td>
            </tr>
            <tr>
                <td>Número de capítulos de libros publicados con código ISBN</td>
                <td>
                    <input type="number" name="capitulos_libros" class="form-control" value="<?= ($capitulos_libros != 0) ? htmlspecialchars($capitulos_libros) : '' ?>" min="0" placeholder="0">
                </td>
            </tr>
            <tr>
                <td>Número de publicaciones en revistas de divulgación científica o tecnológica</td>
                <td>
                    <input type="number" name="revistas_divulgacion" class="form-control" value="<?= ($revistas_divulgacion != 0) ? htmlspecialchars($revistas_divulgacion) : '' ?>" min="0" placeholder="0">
                </td>
            </tr>
            <tr>
                <td>Número de publicaciones en otros medios (periódicos, revistas, blogs, etc.)</td>
                <td>
                    <input type="number" name="otras_publicaciones" class="form-control" value="<?= ($otras_publicaciones != 0) ? htmlspecialchars($otras_publicaciones) : '' ?>" min="0" placeholder="0">
                </td>
            </tr>
        </tbody>
    </table>
</div>


<h4 class="fs-6 fw-bolder mb-5 mt-10">4.12.2 Otros productos de transferencia de conocimiento:</h4>
<div class="table-responsive mb-8">
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th class="text-center">Descripción</th>
                <th class="text-center" style="width: 150px;">Total</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Número de talleres, seminarios, charlas, cursos de capacitación, etc.</td>
                <td>
                    <input type="number" name="talleres_capacitacion" class="form-control" value="<?= ($talleres_capacitacion != 0) ? htmlspecialchars($talleres_capacitacion) : '' ?>" min="0" placeholder="0">
                </td>
            </tr>
            <tr>
                <td>Número de productos tecnológicos (software, prototipos, patentes, etc.)</td>
                <td>
                    <input type="number" name="productos_tecnologicos" class="form-control" value="<?= ($productos_tecnologicos != 0) ? htmlspecialchars($productos_tecnologicos) : '' ?>" min="0" placeholder="0">
                </td>
            </tr>
            <tr>
                <td>Número de productos artísticos (obras de arte, exposiciones, etc.)</td>
                <td>
                    <input type="number" name="productos_artisticos" class="form-control" value="<?= ($productos_artisticos != 0) ? htmlspecialchars($productos_artisticos) : '' ?>" min="0" placeholder="0">
                </td>
            </tr>
            <tr>
                <td>Número de productos culturales (eventos, festivales, etc.)</td>
                <td>
                    <input type="number" name="productos_culturales" class="form-control" value="<?= ($productos_culturales != 0) ? htmlspecialchars($productos_culturales) : '' ?>" min="0" placeholder="0">
                </td>
            </tr>
            <tr>
                <td>Número de productos sociales (modelos de intervención, metodologías, etc.)</td>
                <td>
                    <input type="number" name="productos_sociales" class="form-control" value="<?= ($productos_sociales != 0) ? htmlspecialchars($productos_sociales) : '' ?>" min="0" placeholder="0">
                </td>
            </tr>
        </tbody>
    </table>
</div>



<h2 class="fs-4 fw-bolder mb-5 mt-10">4.13 Impactos del proyecto en la Universidad</h2>
<div class="table-responsive mb-8">
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Efectos</th>
                <th>Descripción</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Nuevas investigaciones</td>
                <td><textarea name="efectos_nuevas_investigaciones" class="form-control" rows="2"><?= htmlspecialchars($efectos_nuevas_investigaciones) ?></textarea></td>
            </tr>
            <tr>
                <td>Nuevas metodologías...</td>
                <td><textarea name="efectos_nuevas_metodologias" class="form-control" rows="2"><?= htmlspecialchars($efectos_nuevas_metodologias) ?></textarea></td>
            </tr>
            <tr>
                <td>Nuevos trabajos de titulación...</td>
                <td><textarea name="efectos_nuevos_trabajos_titulacion" class="form-control" rows="2"><?= htmlspecialchars($efectos_nuevos_trabajos_titulacion) ?></textarea></td>
            </tr>
        </tbody>
    </table>
</div>

                    <h2 class="fs-4 fw-bolder mb-5 mt-10">4.14 Referencias citadas:</h2>
                    <div class="form-group mb-8">
                        <textarea name="referencias_citadas" class="form-control form-control-solid <?= !empty($errores['referencias_citadas']) ? 'is-invalid' : '' ?>" rows="5" placeholder="Ingrese las referencias citadas aquí..."><?= htmlspecialchars($referencias_citadas) ?></textarea>
                        <?php if (!empty($errores['referencias_citadas'])): ?><div class="invalid-feedback"><?= htmlspecialchars($errores['referencias_citadas']) ?></div><?php endif; ?>
                    </div>

                </div>

                <div class="card-footer d-flex justify-content-end py-6 px-9">
                    <a href="?c=Formularios&m=paso8" class="btn btn-secondary me-2">Anterior</a>
                    <button type="submit" class="btn btn-primary">Siguiente</button>
                </div>
            </div>
        </form>
    </div>
</div>

<template id="template-indicador-fila">
    <div class="row gx-5 gy-3 border-bottom pb-5 mb-5 indicador-fila">
        <div class="col-md-3">
            <label class="form-label fw-bolder text-gray-700 fs-7">Indicador</label>
            <select class="form-select form-select-solid">
                <option value="">Seleccione un indicador</option>
                <?php foreach ($lista_indicadores as $opt_indicador): ?>
                    <option value="<?= htmlspecialchars($opt_indicador) ?>"><?= htmlspecialchars($opt_indicador) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-4">
            <label class="form-label fw-bolder text-gray-700 fs-7">Resultado</label>
            <input type="text" class="form-control form-control-solid" placeholder="Describa el resultado">
        </div>
        <div class="col-md-5">
            <label class="form-label fw-bolder text-gray-700 fs-7">Productos</label>
            <div class="productos-lista"></div>
            <button type="button" class="btn btn-sm btn-light-primary mt-1 btn-agregar-producto"><i class="bi bi-plus-lg"></i> Agregar Producto</button>
        </div>
        <div class="col-12 text-end">
            <button type="button" class="btn btn-sm btn-light-danger btn-eliminar-indicador">Eliminar Fila de Indicador</button>
        </div>
    </div>
</template>

<template id="template-producto-fila">
    <div class="input-group mb-3 producto-fila">
        <input type="text" class="form-control form-control-solid" placeholder="Describa el producto">
        <button class="btn btn-outline-danger btn-sm btn-eliminar-producto" type="button" title="Eliminar este producto"><i class="bi bi-trash"></i></button>
    </div>
</template>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const resultadosContainer = document.getElementById('resultados-container');
        const indicadorTemplate = document.getElementById('template-indicador-fila');
        const productoTemplate = document.getElementById('template-producto-fila');

        const crearFilaProducto = (listaProductos, objIndex, indIndex) => {
            const nuevaFila = productoTemplate.content.cloneNode(true);
            const input = nuevaFila.querySelector('input');
            input.name = `producto[${objIndex}][${indIndex}][]`;
            listaProductos.appendChild(nuevaFila);
        };

        const crearFilaIndicador = (listaIndicadores, objIndex) => {
            const nuevaFila = indicadorTemplate.content.cloneNode(true);
            const indIndex = listaIndicadores.children.length;

            const selectIndicador = nuevaFila.querySelector('select');
            const inputResultado = nuevaFila.querySelector('input');
            const listaProductos = nuevaFila.querySelector('.productos-lista');

            selectIndicador.name = `indicador[${objIndex}][]`;
            inputResultado.name = `resultado[${objIndex}][]`;

            crearFilaProducto(listaProductos, objIndex, indIndex);
            listaIndicadores.appendChild(nuevaFila);
        };

        resultadosContainer.addEventListener('click', (e) => {
            const btnAgregarIndicador = e.target.closest('.btn-agregar-indicador');
            if (btnAgregarIndicador) {
                const seccion = btnAgregarIndicador.closest('[data-objetivo-index]');
                const objIndex = seccion.dataset.objetivoIndex;
                const listaIndicadores = seccion.querySelector('.indicadores-lista');
                crearFilaIndicador(listaIndicadores, objIndex);
            }

            const btnEliminarIndicador = e.target.closest('.btn-eliminar-indicador');
            if (btnEliminarIndicador) {
                const filaIndicador = btnEliminarIndicador.closest('.indicador-fila');
                const lista = filaIndicador.parentElement;
                if (lista.children.length > 1) {
                    filaIndicador.remove();
                } else {
                    Swal.fire('Acción no permitida', 'Debe existir al menos un indicador por objetivo.', 'warning');
                }
            }

            const btnAgregarProducto = e.target.closest('.btn-agregar-producto');
            if (btnAgregarProducto) {
                const filaIndicador = btnAgregarProducto.closest('.indicador-fila');
                const seccion = filaIndicador.closest('[data-objetivo-index]');
                const objIndex = seccion.dataset.objetivoIndex;
                const indIndex = Array.from(filaIndicador.parentElement.children).indexOf(filaIndicador);
                const listaProductos = filaIndicador.querySelector('.productos-lista');
                crearFilaProducto(listaProductos, objIndex, indIndex);
            }

            const btnEliminarProducto = e.target.closest('.btn-eliminar-producto');
            if (btnEliminarProducto) {
                const filaProducto = btnEliminarProducto.closest('.producto-fila');
                const lista = filaProducto.parentElement;
                if (lista.children.length > 1) {
                    filaProducto.remove();
                } else {
                    Swal.fire('Acción no permitida', 'Debe existir al menos un producto por resultado.', 'warning');
                }
            }
        });
    });
</script>

<?php require_once ROOT_PATH . '/app/views/layout/footer.php'; ?>