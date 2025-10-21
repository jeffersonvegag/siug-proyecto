<?php require_once ROOT_PATH . '/app/views/layout/header.php'; 

$pasoActual = 11; // Define the current step
require_once ROOT_PATH . '/app/views/layout/stepper_static.php';
?> 

<div class="d-flex flex-column flex-column-fluid">
    <div class="app-content flex-column-fluid">
        <div class="">
            <form method="post" action="?c=Formularios&m=paso11" class="form">
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

                        <div id="mirada-gestor" style="display: none;" class="form-group mb-8"> 
                            <label for="mirada_gestor_facultad" class="form-label">Mirada del Gestor desde la Facultad:</label>
                            <textarea id="mirada_gestor_facultad" name="mirada_gestor_facultad" rows="1" style="resize: vertical; min-height: 120px;" class="form-control form-control-solid"></textarea> 
                        </div>
                    </div>

                    <div class="card-footer d-flex justify-content-end py-6 px-9">
                        <button type="button" onclick="location.href='?c=Formularios&m=paso10'" class="btn btn-secondary me-2">Anterior</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once ROOT_PATH . '/app/views/layout/footer.php'; ?>
