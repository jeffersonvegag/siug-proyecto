<?php include(__DIR__ . '/../layout/header.php'); ?>

<!-- INYECTA DATOS JSON AL FORMULARIO -->
<script>
  // Convierte datos PHP en objeto JS
  var datosFormulario = <?= json_encode($formulario ?? [], JSON_UNESCAPED_UNICODE) ?>;
</script>

<!-- COMPLETA CAMPOS BÁSICOS -->
<script>
  window.addEventListener('DOMContentLoaded', function() {
    // Recorre cada campo y lo aplica por id
    for (const [campo, valor] of Object.entries(datosFormulario)) {
      let input = document.getElementById(campo);
      if (input) input.value = valor;
    }
  });
</script>


<!-- COMPLETA CAMPOS DE EDICIÓN AVANZADA -->
<script>
  window.addEventListener('DOMContentLoaded', function() {
    // Campos básicos
    for (const [campo, valor] of Object.entries(datosFormulario)) {
      let input = document.getElementById(campo);
      if (input && (input.tagName === 'TEXTAREA' || input.tagName === 'INPUT')) {
        input.value = valor;
      }
    }

    // Campos enriquecidos (editores)
    if (typeof $ !== 'undefined' && $('.usar-summernote').length) {
      $('.usar-summernote').each(function() {
        var id = this.id;
        if (datosFormulario[id]) {
          $(this).summernote('code', datosFormulario[id]); // Asigna código HTML
        }
      });
    }
  });
</script>




<div class="section-body">
  <div class="row">
    <div class="col-12">
      <div class="card">
        <h2 class="mb-4 card-header bg-dark py-6 card-label text-white fw-bolder fs-2">Formulario para editar el convenio adendum</h2>
        <div class="card-body">


          <form id="form-editar" method="POST" enctype="multipart/form-data" action="?c=DocumentosGenerados&m=actualizarAdendum&id=<?= urlencode($id_doc_gen) ?>">

            <br>
            <div class="row">

              <!-- LOGO uso solo para editar -->
              <div class="col-12 mb-4">
                <label>Logo de la contraparte:</label>
                <div class="row">
                  <!-- Selección de archivo + ayuda -->
                  <div class="col-md-6 mb-3">
                    <input type="file" name="LOGO_CONTRAPARTE"
                      accept=".jpg,.jpeg,.png,image/jpeg,image/png"
                      class="form-control"
                      onchange="validarImagen(this)">
                    <small id="logoHelp" class="form-text text-danger"></small>
                  </div>

                  <!-- Logo actual + opción de ocultar -->
                  <div class="col-md-3 mb-3 text-center">
                    <?php if (!empty($formulario['LOGO_CONTRAPARTE_RUTA'])): ?>
                      <div>
                        <small class="d-block text-muted">Logo Actual</small>
                        <img src="<?= htmlspecialchars($formulario['LOGO_CONTRAPARTE_RUTA']) ?>"
                          style="max-width:150px; max-height:90px;" class="mx-auto d-block mb-2">
                        <div class="form-check d-flex justify-content-center align-items-center mt-2">
                          <input type="checkbox" class="form-check-input" id="quitarLogo" name="quitar_logo" value="1">
                          <label class="form-check-label ms-2" for="quitarLogo">
                            No mostrar logo
                          </label>
                        </div>
                      </div>
                    <?php endif; ?>
                  </div>

                  <!-- Vista previa imagen nueva -->
                  <div class="col-md-3 mb-3 text-center" id="previewContainer" style="display:none;"> <!-- text-center agregado -->
                    <small class="d-block text-muted">Nuevo logo (vista previa)</small>
                    <img id="previewLogo" style="max-width:150px; max-height:90px;" alt="Vista previa logo" class="mx-auto d-block"> <!-- Centrado -->
                  </div>
                </div>
              </div>

              <br>

              <!-- Sección 1: Proponente -->

              <div class="col-12 mb-4">
                <h4>Datos</h4>
                <div class="row">
                  <!-- Nombre del Proyecto -->
                  <!-- <div class="col-md-6 mb-3">
          <label for="NOMBRE_PROYECTO">Nombre del proyecto</label>
          <input type="text" id="NOMBRE_PROYECTO" name="NOMBRE_PROYECTO" class="form-control" maxlength="200">
        </div> -->

                  <!-- Razón Social -->
                  <div class="col-md-6 mb-3">
                    <label for="RAZON_SOCIAL">Razón Social de la Contraparte</label>
                    <input type="text" id="RAZON_SOCIAL" name="RAZON_SOCIAL" class="form-control" maxlength="200">
                    <input type="hidden" id="RAZON_SOCIAL_MAYUS" name="RAZON_SOCIAL_MAYUS">
                    <small class="text-muted">Razón Social es el nombre legal y oficial de la empresa o entidad.</small>
                  </div>

                  <!-- Razón Social -->
                  <div class="col-md-6 mb-3">
                    <label for="TIPO_ACUERDO">Tipo de Acuerdo</label>
                    <input type="text" id="TIPO_ACUERDO" name="TIPO_ACUERDO" class="form-control" maxlength="200">
                    <small class="text-muted">Ejemplo: ASOCIACION.</small>
                  </div>

                </div>
              </div>
              <!-- /////////////////////////////////////////////////////////////////////////////////////// -->
              <!-- Vista previa: Título del convenio (Convenio Adendum) -->
              <div class="col-12 mb-3">
                <label class="form-label">
                  <strong>Convenio Adendum (Vista Previa):</strong>
                </label>
                <div
                  id="preview_convenio_adendum"
                  class="p-3 border rounded bg-light"
                  style="white-space: pre-wrap; font-size: 1.1rem;">
                </div>
              </div>


              <!-- Vista previa: Cláusula PRIMERA: Comparecientes (Adendum) -->
              <div class="col-12 mb-3">
                <label class="form-label">
                  <strong>Cláusula PRIMERA: Comparecientes (Vista Previa):</strong>
                </label>
                <div
                  id="preview_clausula_compar_adendum"
                  class="p-3 border rounded bg-light"
                  style="white-space: pre-wrap; font-size: 1.1rem;">
                </div>
              </div>

              <!-- /////////////////////////////////////////////////////////////////////////////////////// -->

              <!-- =====================================
         SECCIÓN: REPRESENTANTE DE LA CONTRAPARTE
         ===================================== -->

              <h4>PRIMERA: COMPARECIENTES</h4>

              <div class="col-12 mb-4">
                <h4 class="mb-1">
                  <small class="text-muted">Por Parte de la “Contraparte”:</small>
                </h4>
                <br>
                <div class="row">
                  <div class="col-md-3 mb-3">
                    <label for="GENERO_REPRESENTANTE">Apelativo (Contraparte)</label>
                    <select id="GENERO_REPRESENTANTE" name="GENERO_REPRESENTANTE" class="form-control">
                      <option value="">---Seleccione---</option>
                      <option value="el señor">El señor</option>
                      <option value="la señora">La señora</option>
                      <option value="la señorita">La señorita</option>
                    </select>
                  </div>
                  <div class="col-md-5 mb-3">
                    <label for="NOMBRE_REPRESENTANTE">Nombre y Apellidos del Representante Legal (Contraparte)</label>
                    <input type="text" id="NOMBRE_REPRESENTANTE" name="NOMBRE_REPRESENTANTE" class="form-control" maxlength="200">
                  </div>
                  <div class="col-md-4 mb-3">
                    <label for="CARGO_CONTRAPARTE">Cargo (Contraparte)</label>
                    <input type="text" id="CARGO_CONTRAPARTE" name="CARGO_CONTRAPARTE" class="form-control" maxlength="200">
                  </div>
                  <div class="col-md-6 mb-3">
                    <label for="TELEFONO_CONTRAPARTE">Teléfono (Contraparte)</label>
                    <input type="text" id="TELEFONO_CONTRAPARTE" name="TELEFONO_CONTRAPARTE" class="form-control" maxlength="200">
                  </div>
                  <div class="col-md-6 mb-3">
                    <label for="EMAIL_CONTRAPARTE">Email (Contraparte)</label>
                    <input type="text" id="EMAIL_CONTRAPARTE" name="EMAIL_CONTRAPARTE" class="form-control" maxlength="200">
                  </div>

                </div>
              </div>

              <div class="col-12 mb-4">
                <!-- <h4 class="mb-1"><small class="text-muted">"Contraparte"</small></h4> -->
                Para todos los efectos previstos en este convenio, las partes señalan las siguientes direcciones:
                <br><br>
                <div class="row">
                  <div class="col-md-6 mb-3">
                    <label for="DIRECCION_CONTRAPARTE">Dirección <small class="text-muted">(Ubicación del proyecto)</small></label>
                    <input type="text" id="DIRECCION_CONTRAPARTE" name="DIRECCION_CONTRAPARTE" class="form-control" maxlength="200">
                  </div>
                  <div class="col-md-6 mb-3">
                    <label for="CIUDAD_CONTRAPARTE">Ciudad <small class="text-muted">(Ubicación del proyecto)</small></label>
                    <input type="text" id="CIUDAD_CONTRAPARTE" name="CIUDAD_CONTRAPARTE" class="form-control" maxlength="200">
                  </div>
                </div>
              </div>



              <div class="col-12 mb-4">
                <h4 class="mb-1">
                  <small class="text-muted">Por Parte de la “UNIVERSIDAD DE GUAYAQUIL”:</small>
                </h4>
                <br>
                <div class="row">
                  <div class="col-md-6 mb-3">
                    <label for="NOMBRE_PROPONENTE">Nombre del Coordinador</label>
                    <input type="text" id="NOMBRE_PROPONENTE" name="NOMBRE_PROPONENTE" class="form-control" maxlength="200">
                  </div>
                  <div class="col-md-6 mb-3">
                    <label for="CARGO_PROPONENTE">Cargo del Coordinador</label>
                    <input type="text" id="CARGO_PROPONENTE" name="CARGO_PROPONENTE" class="form-control" maxlength="200">
                  </div>
                  <div class="col-md-6 mb-3">
                    <label for="TELEFONO_PROPONENTE">Teléfono del Coordinador</label>
                    <input type="text" id="TELEFONO_PROPONENTE" name="TELEFONO_PROPONENTE" class="form-control" maxlength="200">
                  </div>
                  <div class="col-md-6 mb-3">
                    <label for="EMAIL_PROPONENTE">Email del Coordinador</label>
                    <input type="text" id="EMAIL_PROPONENTE" name="EMAIL_PROPONENTE" class="form-control" maxlength="200">
                  </div>
                </div>
              </div>


              <!-- Sección 3: Datos de la Contraparte -->

              <div class="col-12 mb-4">
                <!-- <h4>SEGUNDA: ANTECEDENTES</<h4></h4>> -->
                <div class="row">
                  <div class="col-md-6 mb-3">
                    <label for="FACULTAD_UG">Facultad participante (UG)</label>
                    <input type="text" id="FACULTAD_UG" name="FACULTAD_UG" class="form-control" maxlength="200">
                  </div>
                </div>
              </div>



              <div class="col-12 mb-4">
                <!-- <h4>PRIMERA: COMPARECIENTES - RECTOR</h4> -->
                <h4 class="mb-1">
                  <small class="text-muted">RECTOR “UNIVERSIDAD DE GUAYAQUIL”:</small>
                </h4>
                <br>
                <div class="row">
                  <div class="col-md-3 mb-3">
                    <label for="GENERO_RECTOR">Apelativo</label>
                    <select id="GENERO_RECTOR" name="GENERO_RECTOR" class="form-control">
                      <option value="">---Seleccione---</option>
                      <option value="el señor" selected>El señor</option>
                      <option value="la señora">La señora</option>
                      <option value="la señorita">La señorita</option>
                    </select>
                  </div>

                  <div class="col-md-5 mb-3">
                    <label for="NOMBRE_RECTOR">Nombre y Apellidos</label>
                    <input type="text" id="NOMBRE_RECTOR" name="NOMBRE_RECTOR" class="form-control" value="Dr. Francisco Morán Peña, Ph.D." maxlength="200">
                    <small class="form-text text-muted">Incluir títulos académicos: Dr., M.Sc., Ph.D., etc..</small>
                  </div>

                  <div class="col-md-4 mb-3">
                    <label for="PALABRA_RECTOR">Cargo</label>
                    <select id="PALABRA_RECTOR" name="PALABRA_RECTOR" class="form-control">
                      <option value="">---Seleccione---</option>
                      <option value="Rector" selected>Rector</option>
                      <option value="Rectora">Rectora</option>
                    </select>
                  </div>

                </div>
              </div>



              <!-- Vista previa: Cláusula 2.g (Vista Previa) -->
              <div class="col-12 mb-3">
                <label class="form-label">
                  <strong>Cláusula 2.g (Vista Previa):</strong>
                </label>
                <div
                  id="preview_clausula_2_g"
                  class="p-3 border rounded bg-light"
                  style="white-space: pre-wrap; font-size: 1.1rem;">
                </div>
              </div>


              <div class="col-12 mb-4">
                <button type="button" class="btn btn-outline-primary mb-3" id="btnToggleClausula2g">
                  Editar la cláusula 2.g
                </button>

                <div class="row" id="camposClausula2g" style="display: none;">
                  <h4 class="mb-1">
                    <small class="text-muted">CLÁUSULA SEGUNDA: literal g)</small>
                  </h4>

                  <!-- Número de Resolución de Posesión -->
                  <div class="col-md-6 mb-3">
                    <label for="NUMERO_RESOLUCION_POSESION">Número de Resolución de Posesión</label>
                    <input type="text" id="NUMERO_RESOLUCION_POSESION" name="NUMERO_RESOLUCION_POSESION" class="form-control" value="R-CIFI-UG-SE23-084-23-03-2021" maxlength="200">
                  </div>

                  <!-- Periodo del Rector -->
                  <div class="col-md-6 mb-3">
                    <label for="PERIODO_RECTOR_CON_FORMATO">Periodo del Rector</label>
                    <input type="text" id="PERIODO_RECTOR_CON_FORMATO" name="PERIODO_RECTOR_CON_FORMATO" class="form-control" value="2021 - 2026" maxlength="200">
                    <small class="text-muted">Ejemplo: 2021 - 2026</small>
                  </div>

                  <!-- Fecha Resolución de Posesión (sin formato) -->
                  <div class="col-md-6 mb-3">
                    <label for="FECHA_RESOLUCION_POSESION_SIN_FORMATO">Fecha Resolución de Posesión</label>
                    <input type="date" id="FECHA_RESOLUCION_POSESION_SIN_FORMATO" name="FECHA_RESOLUCION_POSESION_SIN_FORMATO" class="form-control" value="2021-03-23">
                  </div>

                  <!-- Fecha Resolución de Posesión (con formato) -->
                  <div class="col-md-6 mb-3">
                    <label for="FECHA_RESOLUCION_POSESION_CON_FORMATO">Fecha Resolución de Posesión (formato)</label>
                    <input type="text" id="FECHA_RESOLUCION_POSESION_CON_FORMATO" name="FECHA_RESOLUCION_POSESION_CON_FORMATO" class="form-control" readonly>
                  </div>

                  <!-- Fecha de Posesión del Rector (sin formato) -->
                  <div class="col-md-6 mb-3">
                    <label for="FECHA_POSESION_RECTOR_SIN_FORMATO">Fecha de Posesión</label>
                    <input type="date" id="FECHA_POSESION_RECTOR_SIN_FORMATO" name="FECHA_POSESION_RECTOR_SIN_FORMATO" class="form-control" value="2021-03-24">
                  </div>

                  <!-- Fecha de Posesión del Rector (con formato) -->
                  <div class="col-md-6 mb-3">
                    <label for="FECHA_POSESION_RECTOR_CON_FORMATO">Fecha de Posesión (formato)</label>
                    <input type="text" id="FECHA_POSESION_RECTOR_CON_FORMATO" name="FECHA_POSESION_RECTOR_CON_FORMATO" class="form-control" readonly>
                  </div>

                </div>
              </div>


              <!-- Sección: Literal I - Segundo Antecedente -->
              <div class="col-12 mb-4">
                <h4>SEGUNDA: ANTECEDENTES</h4>
                <div class="row">

                  <label class="form-label"><strong>Literal I:</strong></label>

                  <div class="col-12 mb-4">
                    <label for="NUMERO_OFICIO">Numero de Oficio.</label>
                    <input type="text" id="NUMERO_OFICIO" name="NUMERO_OFICIO" class="form-control" maxlength="200">
                  </div>

                  <!-- fechas - Inicio-->
                  <div class="col-md-6 mb-3">
                    <label for="FECHA_OFICIO_1_SIN_FORMATO">Seleccione</label>
                    <input type="date" id="FECHA_OFICIO_1_SIN_FORMATO" name="FECHA_OFICIO_1_SIN_FORMATO" class="form-control">
                  </div>

                  <div class="col-md-6 mb-3">
                    <label for="FECHA_OFICIO_1_CON_FORMATO">Fecha de Oficio 1</label>
                    <input type="text" id="FECHA_OFICIO_1_CON_FORMATO" name="FECHA_OFICIO_1_CON_FORMATO" class="form-control" readonly>
                  </div>

                  <div class="col-md-6 mb-3">
                    <label for="FECHA_OFICIO_2_SIN_FORMATO">Seleccione</label>
                    <input type="date" id="FECHA_OFICIO_2_SIN_FORMATO" name="FECHA_OFICIO_2_SIN_FORMATO" class="form-control">
                  </div>

                  <div class="col-md-6 mb-3">
                    <label for="FECHA_OFICIO_2_CON_FORMATO">Fecha de Oficio 2</label>
                    <input type="text" id="FECHA_OFICIO_2_CON_FORMATO" name="FECHA_OFICIO_2_CON_FORMATO" class="form-control" readonly>
                  </div>
                  <!-- fechas - fin  -->


                  <!-- Vista previa: cláusula segunda literal l) -->
                  <div class="col-12 mb-3">
                    <label class="form-label">
                      <strong>Cláusula Segunda - Literal l) (Vista Previa):</strong>
                    </label>
                    <div
                      id="preview_clausula_2_l"
                      class="p-3 border rounded bg-light"
                      style="white-space: pre-wrap; font-size: 1.1rem;">
                    </div>
                  </div>


                  <!-- Textarea de contenido -->
                  <div class="col-12 mb-3">
                    <small class="text-muted">Ejemplo.</small>
                    <textarea id="TEXTO_SEGUNDO_ANTECEDENTE_LITERAL_I"
                      name="TEXTO_SEGUNDO_ANTECEDENTE_LITERAL_I"
                      rows="4"
                      class="form-control">Comunico que, mediante oficio oficial, se aprobó continuar con los trabajos conjuntos y se solicita realizar un adendum al convenio vigente.</textarea>
                    <!-- <small class="text-muted">Ejemplo.</small> -->
                    <small class="text-muted">Por favor, ingrese un resumen claro y conciso del acuerdo o antecedente relacionado.</small>

                  </div>


                  <!-- Vista previa: cláusula segunda literal m) -->
                  <div class="col-12 mb-3">
                    <label class="form-label">
                      <strong>Cláusula Segunda - Literal m) (Vista Previa):</strong>
                    </label>
                    <div
                      id="preview_clausula_2_m"
                      class="p-3 border rounded bg-light"
                      style="white-space: pre-wrap; font-size: 1.1rem;">
                    </div>
                  </div>

                  <!-- Textarea de contenido -->
                  <div class="col-12 mb-3">
                    <small class="text-muted">Ejemplo.</small>
                    <textarea id="TEXTO_SEGUNDO_ANTECEDENTE_LITERAL_M"
                      name="TEXTO_SEGUNDO_ANTECEDENTE_LITERAL_M"
                      rows="4"
                      class="form-control">La entidad responsable custodia un área específica y realiza actividades conjuntas con la universidad, siguiendo un plan o protocolo aprobado por la autoridad competente.</textarea>
                    <!-- <small class="text-muted">Ejemplo.</small> -->
                    <small class="text-muted">
                      Describa brevemente el nombre y la función o responsabilidad de la entidad involucrada, tal como su rol, actividades o acuerdos relevantes.
                    </small>

                  </div>

                  <!-- Literal N -->
                  <label class="form-label"><strong>Literal N:</strong></label>
                  <div class="col-12 mb-3">
                    <textarea id="TEXTO_SEGUNDO_ANTECEDENTE_LITERAL_N"
                      name="TEXTO_SEGUNDO_ANTECEDENTE_LITERAL_N"
                      rows="4"
                      class="form-control"></textarea>
                    <small class="text-muted d-block mt-1">
                      Por favor, ingrese antecedente/s que sustente la ejecución del proyecto.
                    </small>
                    <small class="text-muted d-block">
                      Puede incluir hechos, contextos o situaciones relevantes que impacten en el desarrollo del convenio.
                    </small>
                  </div>


                </div>
              </div>

              <!-- BLOQUES ENRIQUECIDOS -->
              <div class="col-12 mb-4">
                <h4>COMPROMISO DE LAS PARTES</h4>
                <div class="row">

                  <div class="col-md-12 mb-3">
                    <label for="BLOQUE_ENRIQUECIDO1"><strong>La “Universidad de Guayaquil” se compromete a:</strong></label>
                    <textarea id="BLOQUE_ENRIQUECIDO1" name="BLOQUE_ENRIQUECIDO1" class="form-control usar-summernote"></textarea>
                  </div>

                  <div class="col-md-12 mb-3">
                    <label for="BLOQUE_ENRIQUECIDO2"><strong>La “Contraparte" se compromete a:</strong></label>
                    <textarea id="BLOQUE_ENRIQUECIDO2" name="BLOQUE_ENRIQUECIDO2" class="form-control usar-summernote"></textarea>
                  </div>

                </div>
              </div>


              <!-- Sección 8: Proyecto y Representantes -->
              <div class="col-12 mb-4">
                <h4>ACEPTACIÓN</h4>
                <div class="row">

                  <div class="col-md-6 mb-3">
                    <label for="TIEMPO_VIGENCIA">Tiempo de Vigencia (en letras y números)</label>
                    <input type="text" id="TIEMPO_VIGENCIA" name="TIEMPO_VIGENCIA" class="form-control" maxlength="200">
                    <small class="text-muted">Ejemplo: CINCO (5) AÑOS</small>
                  </div>

                  <div class="col-md-6 mb-3">
                    <label for="NUMERO_EJEMPLARES">Numero de Ejemplares (en letras y números)</label>
                    <input type="text" id="NUMERO_EJEMPLARES" name="NUMERO_EJEMPLARES" class="form-control" maxlength="200">
                    <small class="text-muted">Ejemplo: cinco (5) ejemplares</small>
                  </div>
                </div>
              </div>



              <!-- Sección 8: Proyecto y Representantes -->
              <div class="col-12 mb-4">
                <h4>ALCANCE DEL CONVENIO - FECHAS</h4>
                <div class="row">

                  <div class="col-md-6 mb-3">
                    <label for="FECHA_ACEPTACION_SIN_FORMATO">Seleccione</label>
                    <input type="date" id="FECHA_ACEPTACION_SIN_FORMATO" name="FECHA_ACEPTACION_SIN_FORMATO" class="form-control">
                  </div>

                  <div class="col-md-6 mb-3">
                    <label for="FECHA_ACEPTACION_CON_FORMATO">Fecha de Aceptación del Convenio</label>
                    <input type="text" id="FECHA_ACEPTACION_CON_FORMATO" name="FECHA_ACEPTACION_CON_FORMATO" class="form-control" readonly>
                    <small class="text-muted">El presente convenio suscrito el:</small>
                  </div>

                  <div class="col-md-6 mb-3">
                    <label for="FECHA_VIGENCIA_SIN_FORMATO">Seleccione</label>
                    <input type="date" id="FECHA_VIGENCIA_SIN_FORMATO" name="FECHA_VIGENCIA_SIN_FORMATO" class="form-control">
                  </div>

                  <div class="col-md-6 mb-3">
                    <label for="FECHA_VIGENCIA_CON_FORMATO">Fecha de Vigencia del Convenio</label>
                    <input type="text" id="FECHA_VIGENCIA_CON_FORMATO" name="FECHA_VIGENCIA_CON_FORMATO" class="form-control" readonly>
                    <small class="text-muted">El presente convenio tendrá vigencia hasta: </small>
                  </div>

                </div>
              </div>


            </div> <!-- fin del row -->

            <br><br>
            <!-- div botones -->
            <div class="form-group d-flex justify-content-between">
              <!-- Botón: Salir -->
              <a href="?c=DocumentosGenerados&m=index"
   id="btn-salir" class="btn btn-danger mt-3"
   title="Salir al listado">
    Salir
</a>

              <button type="submit"
                id="btn-guardar-cambios" name="accion"
                value="actualizar_quedarse"
                class="btn btn-secondary mt-3"
                title="Guarda los cambios y permanece en esta página">
                Guardar Cambios
              </button>

              <?php if (!empty($documento['ruta_archivo_doc_gen']) && file_exists($documento['ruta_archivo_doc_gen'])): ?>
                <a href="<?= $baseUrl ?>/index.php?c=DocumentosGenerados&m=descargar&id=<?= urlencode($id_doc_gen) ?>"
                  id="btn-descargar" class="btn btn-success mt-3"
                  target="_blank"
                  title="Descargar el documento con los cambios del último guardado">
                  Descargar
                </a>
              <?php endif; ?>

            </div>

          </form>

        </div> <!-- .card-body -->
      </div> <!-- .card -->
    </div> <!-- .col-12 -->
  </div> <!-- .row -->
</div> <!-- .section-body -->


<!-- Gnerar doc - Evita enter -->
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    form.addEventListener('keydown', function(e) {
      // permite enter solo en textarea o elementos que tengan la clase .usar-summernote
      const esTextarea = e.target.tagName === 'TEXTAREA';
      const esSummernote = e.target.classList.contains('note-editable') || e.target.classList.contains('usar-summernote');
      if (e.key === 'Enter' && !esTextarea && !esSummernote) {
        e.preventDefault();
      }
    });
  });
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {

    // --- Lógica para el botón "Guardar Cambios" ---
    const btnGuardar = document.getElementById('btn-guardar-cambios');
    if (btnGuardar) {
        btnGuardar.addEventListener('click', function(event) {
            // 1. Prevenimos que el formulario se envíe automáticamente
            event.preventDefault();

            // 2. Mostramos la alerta de SweetAlert
            Swal.fire({
                title: '¿Guardar cambios?',
                text: "La página se recargará para guardar los cambios.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, continuar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                // 3. Si el usuario confirma, enviamos el formulario
                if (result.isConfirmed) {
                    // Buscamos el formulario al que pertenece el botón y lo enviamos
                    btnGuardar.closest('form').submit();
                }
            });
        });
    }


    // --- Lógica para el botón "Descargar" ---
    const btnDescargar = document.getElementById('btn-descargar');
    if (btnDescargar) {
        btnDescargar.addEventListener('click', function(event) {
            // 1. Prevenimos que el navegador siga el enlace
            event.preventDefault();

            // Guardamos la URL del enlace
            const url = this.href;

            // 2. Mostramos la alerta
            Swal.fire({
                title: '¿Desea continuar?',
                text: "Se descargará el documento con los cambios del último guardado.",
                icon: 'info',
                showCancelButton: true,
                confirmButtonColor: '#198754', // Color verde de success
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, descargar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                // 3. Si el usuario confirma, abrimos la URL de descarga
                if (result.isConfirmed) {
                    window.open(url, '_blank');
                }
            });
        });
    }
    // --- Lógica para el botón "Salir" ---
const btnSalir = document.getElementById('btn-salir');
if (btnSalir) {
    btnSalir.addEventListener('click', function(event) {
        // 1. Prevenimos la navegación
        event.preventDefault();
        
        // Guardamos la URL del enlace
        const url = this.href;

        // 2. Mostramos la alerta de SweetAlert
        Swal.fire({
            title: '¿Está seguro de que desea salir?',
            text: "Los cambios que no haya guardado se perderán.",
            icon: 'warning', // Un ícono de advertencia es apropiado aquí
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, salir',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            // 3. Si el usuario confirma, lo redirigimos
            if (result.isConfirmed) {
                window.location.href = url;
            }
        });
    });
}

});
</script>


<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Summernote CSS -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-bs4.min.css" rel="stylesheet">

<!-- Summernote JS -->
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-bs4.min.js"></script>

<!-- Summernote lenguaje en español -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/lang/summernote-es-ES.min.js"></script>

<!-- Bootstrap (requerido por Summernote BS4) -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>


<!-- Cambiar estilo de listas numeradas a letras con paréntesis -->
<style>
  .note-editable ol {
    list-style: none;
    counter-reset: item;
    padding-left: 1.8em;
  }

  .note-editable ol>li {
    position: relative;
    margin-bottom: 4px;
  }

  .note-editable ol>li::before {
    position: absolute;
    left: -1.8em;
    counter-increment: item;
    content: counter(item, lower-alpha) ") ";
  }
</style>

<script>
  document.addEventListener("DOMContentLoaded", function() {
    $('.usar-summernote').summernote({
      height: 200,
      lang: 'es-ES',
      codeviewFilter: true,
      codeviewIframeFilter: true,
      toolbar: [
        ['para', ['ol']],
        ['view', ['fullscreen']]
      ],
      placeholder: 'Ingrese el contenido',
      shortcuts: false, // Desactivar atajos nativos de summernote
      callbacks: {
        onPaste: function(e) {
          const clipboardData = (e.originalEvent || e).clipboardData;
          if (clipboardData && clipboardData.items) {
            for (let i = 0; i < clipboardData.items.length; i++) {
              if (clipboardData.items[i].type.indexOf('image') !== -1) {
                e.preventDefault();
                alert('No se permite pegar imágenes.');
                return false;
              }
            }
          }

          e.preventDefault();
          let text = clipboardData.getData('text/plain') || '';
          document.execCommand('insertText', false, text);
        },
        onImageUpload: function() {
          alert('No se permite subir imágenes.');
          return false;
        },
        onDrop: function(e) {
          e.preventDefault();
          alert('No se permite arrastrar ni soltar imágenes.');
        },
        keydown: function(e) {
          const forbiddenShortcuts = [
            e.ctrlKey && e.key.toLowerCase() === 'k', // enlace
            e.ctrlKey && e.key.toLowerCase() === 'b', // negrita
            e.ctrlKey && e.key.toLowerCase() === 'i', // cursiva
            e.ctrlKey && e.key.toLowerCase() === 'u', // subrayado
            e.ctrlKey && e.shiftKey && e.key.toLowerCase() === 's' // tachado
          ];

          if (forbiddenShortcuts.some(Boolean)) {
            e.preventDefault();
            e.stopPropagation();
            // Bloqueo silencioso, sin alertas
          }
        }
      },
      keyMap: { // Anular atajos predeterminados para PC (opcional pero recomendado)
        pc: {
          'CTRL+B': null,
          'CTRL+I': null,
          'CTRL+U': null,
          'CTRL+K': null,
          'CTRL+SHIFT+S': null
        }
      }
    });

    document.querySelector('form').addEventListener('submit', function() {
      const enriquecidos = [];
      document.querySelectorAll('.usar-summernote').forEach(el => {
        enriquecidos.push(el.name);
      });
      const inputHidden = document.createElement('input');
      inputHidden.type = 'hidden';
      inputHidden.name = '_enriquecidos';
      inputHidden.value = JSON.stringify(enriquecidos);
      this.appendChild(inputHidden);
    });
  });
</script>



<script>
  /**
   * Validar logo al EDITAR
   * - Solo permite .jpg, .jpeg, .png
   * - Máximo 2MB
   * - Muestra vista previa nueva
   * - Oculta / muestra la etiqueta "Nuevo logo (vista previa)"
   */
  function validarImagen(input) {
    const file = input.files[0];
    const help = document.getElementById('logoHelp');
    const preview = document.getElementById('previewLogo');
    const previewContainer = document.getElementById('previewContainer');

    // Reiniciar mensajes
    help.textContent = '';
    previewContainer.style.display = 'none';
    preview.src = '';

    if (!file) return;

    const extensiones = ['jpg', 'jpeg', 'png'];
    const ext = file.name.split('.').pop().toLowerCase();

    if (!extensiones.includes(ext)) {
      help.textContent = 'Solo se permiten imágenes JPG o PNG.';
      input.value = '';
      return;
    }

    if (file.size > 2 * 1024 * 1024) {
      help.textContent = 'El archivo no puede superar los 2MB.';
      input.value = '';
      return;
    }

    // Mostrar vista previa y el título
    const reader = new FileReader();
    reader.onload = e => {
      preview.src = e.target.result;
      previewContainer.style.display = 'block';
    };
    reader.readAsDataURL(file);
  }
</script>


<script>
  /**
   * Sincroniza el valor de un campo origen a mayúsculas en el campo destino.
   * Ejecuta también una función callback opcional cada vez que cambia el campo origen.
   */
  function sincronizarMayus(origenId, destinoId, callback) {
    const input = document.getElementById(origenId);
    const output = document.getElementById(destinoId);

    if (input && output) {
      // Al cargar
      output.value = input.value.toLocaleUpperCase('es-EC');
      // Al editar
      input.addEventListener('input', () => {
        output.value = input.value.toLocaleUpperCase('es-EC');
        if (typeof callback === 'function') callback();
      });
    }
  }
</script>


<!-- Función reutilizable para formatear fechas -->
<script>
  // Función reutilizable para formatear fechas con callback opcional
  function formatearFecha(idSinFormato, idConFormato, callback) {
    const fechaInput = document.getElementById(idSinFormato);
    const fechaFormateadaInput = document.getElementById(idConFormato);

    if (!fechaInput || !fechaFormateadaInput) return;

    const meses = [
      'enero', 'febrero', 'marzo', 'abril', 'mayo', 'junio',
      'julio', 'agosto', 'septiembre', 'octubre', 'noviembre', 'diciembre'
    ];

    function actualizar() {
      const valor = fechaInput.value;
      if (valor) {
        const [anio, mes, dia] = valor.split('-');
        const texto = `${parseInt(dia, 10)} de ${meses[parseInt(mes, 10) - 1]} del ${anio}`;
        fechaFormateadaInput.value = texto;
      } else {
        fechaFormateadaInput.value = '';
      }
      if (typeof callback === 'function') callback();
    }

    // Ejecuta al cargar y al cambiar
    actualizar();
    fechaInput.addEventListener('change', actualizar);
  }
</script>


<!--Boton clausula 2 G -->
<script>
  document.getElementById('btnToggleClausula2g').addEventListener('click', function() {
    const campos = document.getElementById('camposClausula2g');
    if (campos.style.display === 'none') {
      campos.style.display = '';
      this.textContent = 'Ocultar edición de la cláusula 2.g';
    } else {
      campos.style.display = 'none';
      this.textContent = 'Editar la cláusula 2.g';
    }
  });
</script>


<!-- Función global para escapar cadenas y prevenir XSS -->
<script>
  function escapeHtml(str) {
    if (!str) return '';
    return String(str)
      .replace(/&/g, "&amp;")
      .replace(/</g, "&lt;")
      .replace(/>/g, "&gt;")
      .replace(/"/g, "&quot;")
      .replace(/'/g, "&#039;");
  }
</script>

<!-- 1 -->
<!-- Encabezado inicial del adendum modificatorio -->
<script>
  function actualizarParrafoInicialConvenio() {
    const RAZON_SOCIAL_MAYUS = escapeHtml(document.getElementById('RAZON_SOCIAL_MAYUS').value || '______');

    const textoConvenio = `ADENDUM MODIFICATORIO AL CONVENIO ESPECÍFICO DE COOPERACIÓN INTERINSTITUCIONAL ENTRE LA UNIVERSIDAD DE GUAYAQUIL Y <strong>${RAZON_SOCIAL_MAYUS}</strong>.`;

    document.getElementById('preview_convenio_adendum').innerHTML = textoConvenio;
  }
</script>

<!-- Inicialización de campos, autollenado y asociación de eventos al cargar la página -->
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // sincronizar mayúsculas
    sincronizarMayus('RAZON_SOCIAL', 'RAZON_SOCIAL_MAYUS', actualizarParrafoInicialConvenio);

    // Si hay autollenado
    if (typeof datosFormulario !== "undefined" && datosFormulario && Object.keys(datosFormulario).length > 0) {
      for (const [campo, valor] of Object.entries(datosFormulario)) {
        let input = document.getElementById(campo);
        if (input) input.value = valor;
      }
      sincronizarMayus('RAZON_SOCIAL', 'RAZON_SOCIAL_MAYUS', actualizarParrafoInicialConvenio);
    }

    // eventos para cambios manuales
    ['RAZON_SOCIAL_MAYUS'].forEach(id => {
      const el = document.getElementById(id);
      if (el) {
        el.addEventListener('input', actualizarParrafoInicialConvenio);
        el.addEventListener('change', actualizarParrafoInicialConvenio);
      }
    });

    // Vista previa inicial
    actualizarParrafoInicialConvenio();
  });
</script>


<!-- 2 -->
<!-- Generación dinámica de la cláusula PRIMERA: COMPARECIENTES -->
<script>
  function actualizarClausula1() {
    const GENERO_RECTOR = escapeHtml(document.getElementById('GENERO_RECTOR').value || '______');
    const NOMBRE_RECTOR = escapeHtml(document.getElementById('NOMBRE_RECTOR').value || '______');
    const PALABRA_RECTOR = escapeHtml(document.getElementById('PALABRA_RECTOR').value || '______');
    const RAZON_SOCIAL = escapeHtml(document.getElementById('RAZON_SOCIAL').value || '______');
    const GENERO_REPRESENTANTE = escapeHtml(document.getElementById('GENERO_REPRESENTANTE').value || '______');
    const NOMBRE_REPRESENTANTE = escapeHtml(document.getElementById('NOMBRE_REPRESENTANTE').value || '______');
    const TIPO_ACUERDO = escapeHtml(document.getElementById('TIPO_ACUERDO').value || '______');

    const textoClausula = `Comparecen a la celebración del presente Convenio Específico de Cooperación Interinstitucional, por una parte, la Universidad de Guayaquil representada legalmente por <strong>${GENERO_RECTOR} ${NOMBRE_RECTOR}</strong>, en calidad de <strong>${PALABRA_RECTOR}</strong> de la Universidad de Guayaquil, quien en adelante se llamará “UNIVERSIDAD DE GUAYAQUIL”, y, por otra parte, <strong>${RAZON_SOCIAL}</strong>, representada legalmente por <strong>${GENERO_REPRESENTANTE} ${NOMBRE_REPRESENTANTE}</strong>, en calidad de Representante Legal, a quien en adelante se le llamará “<strong>${TIPO_ACUERDO}</strong>”, las partes libre y voluntariamente acuerdan suscribir el presente instrumento al tenor de las siguientes cláusulas.`;

    document.getElementById('preview_clausula_compar_adendum').innerHTML = textoClausula;
  }
</script>

<!-- Inicialización de campos, autollenado y asociación de eventos al cargar la página -->
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // sincronizar mayúsculas donde aplique
    sincronizarMayus('RAZON_SOCIAL', 'RAZON_SOCIAL_MAYUS', actualizarClausula1);

    // Si hay autollenado
    if (typeof datosFormulario !== "undefined" && datosFormulario && Object.keys(datosFormulario).length > 0) {
      for (const [campo, valor] of Object.entries(datosFormulario)) {
        let input = document.getElementById(campo);
        if (input) input.value = valor;
      }
      sincronizarMayus('RAZON_SOCIAL', 'RAZON_SOCIAL_MAYUS', actualizarClausula1);
    }

    // eventos para cambios manuales
    const idsCompar = [
      'GENERO_RECTOR',
      'NOMBRE_RECTOR',
      'PALABRA_RECTOR',
      'RAZON_SOCIAL',
      'GENERO_REPRESENTANTE',
      'NOMBRE_REPRESENTANTE',
      'TIPO_ACUERDO'
    ];
    idsCompar.forEach(id => {
      const el = document.getElementById(id);
      if (el) {
        el.addEventListener('input', actualizarClausula1);
        el.addEventListener('change', actualizarClausula1);
      }
    });

    // Vista previa inicial
    actualizarClausula1();
  });
</script>


<!-- G -->
<script>
  function actualizarClausula2_g() {
    const NUMERO_RESOLUCION_POSESION = escapeHtml(document.getElementById('NUMERO_RESOLUCION_POSESION').value || '______');
    const FECHA_RESOLUCION_POSESION_CON_FORMATO = escapeHtml(document.getElementById('FECHA_RESOLUCION_POSESION_CON_FORMATO').value || '______');
    const FECHA_POSESION_RECTOR_CON_FORMATO = escapeHtml(document.getElementById('FECHA_POSESION_RECTOR_CON_FORMATO').value || '______');
    const GENERO_RECTOR = escapeHtml(document.getElementById('GENERO_RECTOR').value || '______');
    const NOMBRE_RECTOR = escapeHtml(document.getElementById('NOMBRE_RECTOR').value || '______');
    const PALABRA_RECTOR = escapeHtml(document.getElementById('PALABRA_RECTOR').value || '______');
    const PERIODO_RECTOR_CON_FORMATO = escapeHtml(document.getElementById('PERIODO_RECTOR_CON_FORMATO').value || '______');

    const textoClausula = `g) Mediante Resolución No. <strong>${NUMERO_RESOLUCION_POSESION}</strong> de fecha <strong>${FECHA_RESOLUCION_POSESION_CON_FORMATO}</strong>, la Comisión Interventora de Fortalecimiento Institucional para la Universidad de Guayaquil resuelve en su artículo 2 la posesión de las autoridades y representantes electos. El <strong>${FECHA_POSESION_RECTOR_CON_FORMATO}</strong> se posesiona <strong>${GENERO_RECTOR} ${NOMBRE_RECTOR}</strong> en el cargo de <strong>${PALABRA_RECTOR}</strong> de la Universidad para el periodo <strong>${PERIODO_RECTOR_CON_FORMATO}</strong>.`;

    document.getElementById('preview_clausula_2_g').innerHTML = textoClausula;
  }
</script>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const idsClausula2g = [
      'NUMERO_RESOLUCION_POSESION',
      'FECHA_RESOLUCION_POSESION_CON_FORMATO',
      'FECHA_POSESION_RECTOR_CON_FORMATO',
      'GENERO_RECTOR',
      'NOMBRE_RECTOR',
      'PALABRA_RECTOR',
      'PERIODO_RECTOR_CON_FORMATO'
    ];

    idsClausula2g.forEach(id => {
      const el = document.getElementById(id);
      if (el) {
        el.addEventListener('input', actualizarClausula2_g);
        el.addEventListener('change', actualizarClausula2_g);
      }
    });

    // sincronización de fechas con la función global
    formatearFecha('FECHA_RESOLUCION_POSESION_SIN_FORMATO', 'FECHA_RESOLUCION_POSESION_CON_FORMATO', actualizarClausula2_g);
    formatearFecha('FECHA_POSESION_RECTOR_SIN_FORMATO', 'FECHA_POSESION_RECTOR_CON_FORMATO', actualizarClausula2_g);

    actualizarClausula2_g(); // vista previa inicial
  });
</script>

<!-- L -->
<script>
  function actualizarClausula2_l() {
    const NUMERO_OFICIO = escapeHtml(document.getElementById('NUMERO_OFICIO').value || '______');
    const FECHA_OFICIO_1_CON_FORMATO = escapeHtml(document.getElementById('FECHA_OFICIO_1_CON_FORMATO').value || '______');
    const FECHA_OFICIO_2_CON_FORMATO = escapeHtml(document.getElementById('FECHA_OFICIO_2_CON_FORMATO').value || '______');
    const GENERO_REPRESENTANTE = escapeHtml(document.getElementById('GENERO_REPRESENTANTE').value || '______');
    const NOMBRE_REPRESENTANTE = escapeHtml(document.getElementById('NOMBRE_REPRESENTANTE').value || '______');
    const TIPO_ACUERDO = escapeHtml(document.getElementById('TIPO_ACUERDO').value || '______');
    const GENERO_RECTOR = escapeHtml(document.getElementById('GENERO_RECTOR').value || '______');
    const NOMBRE_RECTOR = escapeHtml(document.getElementById('NOMBRE_RECTOR').value || '______');
    const PALABRA_RECTOR = escapeHtml(document.getElementById('PALABRA_RECTOR').value || '______');
    const TEXTO_SEGUNDO_ANTECEDENTE_LITERAL_I = escapeHtml(document.getElementById('TEXTO_SEGUNDO_ANTECEDENTE_LITERAL_I').value || '______');

    const textoClausula = `l) Con Oficio Nro. <strong>${NUMERO_OFICIO}</strong> con fecha <strong>${FECHA_OFICIO_1_CON_FORMATO}</strong> y <strong>${FECHA_OFICIO_2_CON_FORMATO}</strong>, suscrito por <strong>${GENERO_REPRESENTANTE} ${NOMBRE_REPRESENTANTE}</strong>, Representante Legal de la “<strong>${TIPO_ACUERDO}</strong>”, dirigido al <strong>${GENERO_RECTOR} ${NOMBRE_RECTOR}</strong>, <strong>${PALABRA_RECTOR}</strong> de la “UNIVERSIDAD DE GUAYAQUIL”, expresa “[…] <strong>${TEXTO_SEGUNDO_ANTECEDENTE_LITERAL_I}</strong> […]”.`;

    document.getElementById('preview_clausula_2_l').innerHTML = textoClausula;
  }
</script>


<script>
  document.addEventListener('DOMContentLoaded', function() {
    const idsClausula2l = [
      'NUMERO_OFICIO',
      'FECHA_OFICIO_1_CON_FORMATO',
      'FECHA_OFICIO_2_CON_FORMATO',
      'GENERO_REPRESENTANTE',
      'NOMBRE_REPRESENTANTE',
      'TIPO_ACUERDO',
      'GENERO_RECTOR',
      'NOMBRE_RECTOR',
      'PALABRA_RECTOR',
      'TEXTO_SEGUNDO_ANTECEDENTE_LITERAL_I'
    ];

    idsClausula2l.forEach(id => {
      const el = document.getElementById(id);
      if (el) {
        el.addEventListener('input', actualizarClausula2_l);
        el.addEventListener('change', actualizarClausula2_l);
      }
    });

    // sincronización de fechas con la función global
    formatearFecha('FECHA_OFICIO_1_SIN_FORMATO', 'FECHA_OFICIO_1_CON_FORMATO', actualizarClausula2_l);
    formatearFecha('FECHA_OFICIO_2_SIN_FORMATO', 'FECHA_OFICIO_2_CON_FORMATO', actualizarClausula2_l);

    actualizarClausula2_l(); // vista previa inicial
  });
</script>


<!-- M -->
<script>
  function actualizarClausula2_m() {
    const TIPO_ACUERDO = escapeHtml(document.getElementById('TIPO_ACUERDO').value || '______');
    const TEXTO_SEGUNDO_ANTECEDENTE_LITERAL_M = escapeHtml(document.getElementById('TEXTO_SEGUNDO_ANTECEDENTE_LITERAL_M').value || '______');
    const FACULTAD_UG = escapeHtml(document.getElementById('FACULTAD_UG').value || '______');

    const textoClausula = `m) La “<strong>${TIPO_ACUERDO}</strong>” <strong>${TEXTO_SEGUNDO_ANTECEDENTE_LITERAL_M}</strong> con la “UNIVERSIDAD DE GUAYAQUIL” a través de la Facultad de <strong>${FACULTAD_UG}</strong>.`;

    document.getElementById('preview_clausula_2_m').innerHTML = textoClausula;
  }
</script>


<script>
  document.addEventListener('DOMContentLoaded', function() {
    const idsClausula2m = [
      'TIPO_ACUERDO',
      'TEXTO_SEGUNDO_ANTECEDENTE_LITERAL_M',
      'FACULTAD_UG'
    ];

    idsClausula2m.forEach(id => {
      const el = document.getElementById(id);
      if (el) {
        el.addEventListener('input', actualizarClausula2_m);
        el.addEventListener('change', actualizarClausula2_m);
      }
    });

    actualizarClausula2_m(); // vista previa inicial
  });
</script>


<!-- ALCANCE DEL CONVENIO - FECHAS -->
<script>
  document.addEventListener("DOMContentLoaded", function() {
    formatearFecha('FECHA_ACEPTACION_SIN_FORMATO', 'FECHA_ACEPTACION_CON_FORMATO');
    formatearFecha('FECHA_VIGENCIA_SIN_FORMATO', 'FECHA_VIGENCIA_CON_FORMATO');
  });
</script>