<!-- 
<pre style="background:#eee; color:#444; padding:1em; font-size:12px;">
<?= htmlspecialchars(print_r($proyectosCompletos, true)) ?>
</pre> -->
<script>
  // El array PROYECTOS ya no es necesario aquí
  // const PROYECTOS = ...

  document.addEventListener('DOMContentLoaded', function() {
    const select = document.getElementById('SELECT_NOMBRE_PROYECTO');
    
    select.addEventListener('change', function() {
      const texto = select.options[select.selectedIndex].text;
      const dataId = select.selectedOptions[0].getAttribute('data-id');

      // Limpia todos los campos primero
      limpiarCampos();

      // Llenar campos ocultos básicos
      document.getElementById('id_proyecto').value = dataId || '';
      document.getElementById('NOMBRE_PROYECTO').value = texto;
      document.getElementById('NOMBRE_PROYECTO_MAYUS').value = texto.toUpperCase();
      
      // Si no se seleccionó un proyecto válido, no hacemos nada más
      if (!dataId) {
        actualizarVistasPrevias();
        return;
      }
      
      // Muestra un indicador de carga (opcional, pero mejora la experiencia)
      document.getElementById('RAZON_SOCIAL').placeholder = 'Cargando datos...';

      // Petición AJAX para obtener los detalles del proyecto
      fetch(`?c=convenio&m=getProyectoParaConvenioAjax&id=${dataId}`)
        .then(response => response.json())
        .then(datos => {
          document.getElementById('RAZON_SOCIAL').placeholder = ''; // Limpia el indicador
          
          if (datos && !datos.error) {
            // Llenamos el formulario con los datos recibidos
            document.getElementById('RAZON_SOCIAL').value = datos.NombreInstitucion || '';
            document.getElementById('RAZON_SOCIAL_MAYUS').value = (datos.NombreInstitucion || '').toUpperCase();
            document.getElementById('NOMBRE_REPRESENTANTE').value = datos.RepresentanteLegal || '';
            document.getElementById('DIRECCION_CONTRAPARTE').value = datos.Direccion || '';
            document.getElementById('TELEFONO_CONTRAPARTE').value = datos.TelefonoInstitucion || '';
            document.getElementById('WEB_CONTRAPARTE').value = datos.PaginaWeb || '';
            document.getElementById('EMAIL_CONTRAPARTE').value = datos.EmailInstitucion || '';
            document.getElementById('NOMBRE_PROPONENTE').value = datos.Decano || '';
            document.getElementById('EMAIL_PROPONENTE').value = datos.EmailDecano || '';
            document.getElementById('TELEFONO_PROPONENTE').value = datos.TelefonoDecano || '';
            document.getElementById('CARGO_PROPONENTE').value = 'Decano';

            if (typeof $('#BLOQUE_ENRIQUECIDO1').summernote === 'function') {
              $('#BLOQUE_ENRIQUECIDO1').summernote('code', datos.ObjetivosEspecificosLista || '');
            }
          } else {
            // Si hay un error, lo mostramos en la consola
            console.error('Error al cargar datos del proyecto:', datos.error);
          }
          
          // Actualizamos las vistas previas después de llenar los datos
          actualizarVistasPrevias();
        })
        .catch(error => {
          console.error('Fetch error:', error);
          document.getElementById('RAZON_SOCIAL').placeholder = '';
        });
    });
    
    // Función para limpiar todos los campos
    function limpiarCampos() {
        const ids = ['RAZON_SOCIAL', 'RAZON_SOCIAL_MAYUS', 'NOMBRE_REPRESENTANTE', 'DIRECCION_CONTRAPARTE', 'TELEFONO_CONTRAPARTE', 'WEB_CONTRAPARTE', 'EMAIL_CONTRAPARTE', 'NOMBRE_PROPONENTE', 'EMAIL_PROPONENTE', 'TELEFONO_PROPONENTE', 'CARGO_PROPONENTE'];
        ids.forEach(id => {
            const el = document.getElementById(id);
            if (el) el.value = '';
        });
        if (typeof $('#BLOQUE_ENRIQUECIDO1').summernote === 'function') {
            $('#BLOQUE_ENRIQUECIDO1').summernote('code', '');
        }
    }
    
    // Función para actualizar todas las vistas previas a la vez
    function actualizarVistasPrevias() {
        if (typeof actualizarParrafoInicialConvenio === 'function') {
          actualizarParrafoInicialConvenio();
        }
        // Puedes añadir aquí llamadas a otras funciones de actualización de vistas previas
    }
  });
</script>







<div class="section-body">
  <div class="row">
    <div class="col-12">
      <div class="card">
        <h2 class="mb-4 card-header bg-dark py-6 card-label text-white fw-bolder fs-2">Formulario para completar el convenio específico</h2>
        <div class="card-body">


          <form method="POST" id="formEspecifico" enctype="multipart/form-data" action="?c=convenio&m=generarDesdeFormularioEspecifico">

            <br>
            <div class="row">

              <!-- LOGO -->
              <div class="col-12 mb-4">
                <div class="row">
                  <div class="col-md-6 mb-3">
                    <label>Logo de la contraparte:</label>
                    <input type="file" name="LOGO_CONTRAPARTE"
                      accept=".jpg,.jpeg,.png,image/jpeg,image/png"
                      class="form-control"
                      onchange="validarImagen(this)">
                    <small id="logoHelp" class="form-text text-danger"></small>
                  </div>

                  <div class="col-md-6 mb-3 text-center">
                    <small class="d-block text-muted mb-1">Vista previa del logo</small>
                    <div class="d-flex justify-content-center align-items-center" style="min-height:100px;">
                      <img id="previewLogo" style="display:none; max-width:80px; margin-top:1px;" alt="Vista previa del logo">
                    </div>
                  </div>
                </div>
              </div>

              <br>

              <!-- TP -->
              <!-- CREAR -->
              <!-- Sección 1: Proponente -->
              <div class="col-12 mb-4">
                <h4>Datos</h4>
                <div class="row">
                  <div class="col-md-6 mb-3">
                    <label for="SELECT_NOMBRE_PROYECTO">Nombre del proyecto</label>
                    <select id="SELECT_NOMBRE_PROYECTO" class="form-control">
                      <option value="">Seleccione un proyecto...</option>
                      <?php foreach ($proyectos as $proyecto): ?>
                        <option
                          value="<?= htmlspecialchars($proyecto['NombreProyecto']) ?>"
                          data-id="<?= $proyecto['IdPropuesta'] ?>"
<?= ($proyecto['IdPropuesta'] == ($datos['IdPropuesta'] ?? null)) ? 'selected' : '' ?>>
                          <?= htmlspecialchars($proyecto['NombreProyecto']) ?>
                        </option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                </div>
              </div>

              <!-- Campos ocultos -->
              <input type="hidden" id="NOMBRE_PROYECTO" name="NOMBRE_PROYECTO" value="<?= htmlspecialchars($datos['NOMBRE_PROYECTO'] ?? '') ?>">
              <input type="hidden" id="NOMBRE_PROYECTO_MAYUS" name="NOMBRE_PROYECTO_MAYUS" value="<?= htmlspecialchars($datos['NOMBRE_PROYECTO_MAYUS'] ?? '') ?>">
              <input type="hidden" name="id_proyecto" id="id_proyecto" value="<?= htmlspecialchars($datos['IdProyecto'] ?? '') ?>">

              <!-- <script>
    document.getElementById('SELECT_NOMBRE_PROYECTO').addEventListener('change', function () {
      const select = this;
      const texto = select.options[select.selectedIndex].text;
      const dataId = select.selectedOptions[0].getAttribute('data-id');

      document.getElementById('id_proyecto').value = dataId ? dataId : '';
      document.getElementById('NOMBRE_PROYECTO').value = texto;
      document.getElementById('NOMBRE_PROYECTO_MAYUS').value = texto.toUpperCase();

      // Si existe esta función, llama (solo en editar)
      if (typeof actualizarParrafoInicialConvenio === 'function') {
        actualizarParrafoInicialConvenio();
      }
    });
    </script> -->


              <!-- Sección 1: Proponente -->
              <div class="col-12 mb-4">
                <!-- <h4>Datos</h4> -->
                <div class="row">
                  <!-- Razón Social -->
                  <div class="col-md-6 mb-3">
                    <label for="RAZON_SOCIAL">Razón Social de la Contraparte</label>
                    <input type="text" id="RAZON_SOCIAL" name="RAZON_SOCIAL" class="form-control" maxlength="200">
                    <input type="hidden" id="RAZON_SOCIAL_MAYUS" name="RAZON_SOCIAL_MAYUS">
                    <small class="text-muted">Razón Social es el nombre legal y oficial de la empresa o entidad.</small>
                  </div>

                  <div class="col-md-6 mb-3">
                    <label for="APELATIVO_CONTRAPARTE">Apelativo o nombre abreviado de la contraparte</label>
                    <input type="text" id="APELATIVO_CONTRAPARTE" name="APELATIVO_CONTRAPARTE" class="form-control" maxlength="200">
                    <small class="form-text text-muted">Nombre a utilizar para referirse a la contraparte en el convenio.</small>
                  </div>
                </div>
              </div>

              <!-- Vista previa: Título del convenio (CONVENIO ESPECÍFICO) -->
              <div class="col-12 mb-3">
                <label class="form-label">
                  <strong>Convenio Específico (Vista Previa):</strong>
                </label>
                <div
                  id="preview_convenio_especifico"
                  class="p-3 border rounded bg-light"
                  style="white-space: pre-wrap; font-size: 1.1rem;">
                </div>
              </div>

              <!-- Cláusula Primera: Comparecientes (Vista Previa) -->
              <div class="col-12 mb-3">
                <label class="form-label"> <strong>Cláusula: Comparecientes (Vista Previa):</strong></label>
                <div id="preview_clausula_compar" class="p-3 border rounded bg-light" style="white-space: pre-wrap; font-size: 1.1rem;"> </div>
              </div>

              <!-- Sección 2: Proyecto y Representantes -->
              <div class="col-12 mb-4">
                <h4>CLÁUSULA: COMPARECIENTES</h4>
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

                  <div class="col-md-6 mb-3">
                    <label for="TELEFONO_CONTRAPARTE">Teléfono (Contraparte)</label>
                    <input type="text" id="TELEFONO_CONTRAPARTE" name="TELEFONO_CONTRAPARTE" class="form-control" maxlength="200">
                  </div>

                  <div class="col-md-6 mb-3">
                    <label for="EMAIL_CONTRAPARTE">Email/s (Contraparte)</label>
                    <input type="text" id="EMAIL_CONTRAPARTE" name="EMAIL_CONTRAPARTE" class="form-control" maxlength="200">
                  </div>

                  <div class="col-md-6 mb-3">
                    <label for="WEB_CONTRAPARTE">Sitio web (Contraparte)</label>
                    <input type="text" id="WEB_CONTRAPARTE" name="WEB_CONTRAPARTE" class="form-control" maxlength="200">
                  </div>

                </div>
              </div>

              <h4>CLÁUSULA: COORDINACIÓN</h4>
              <!-- Sección 6.1: Proponente -->
              <div class="col-12 mb-4">
                <h4>Delegado por la “Universidad”</h4>
                <div class="row">
                  <div class="col-md-6 mb-3">
                    <label for="NOMBRE_PROPONENTE">Nombre del delegado</label>
                    <input type="text" id="NOMBRE_PROPONENTE" name="NOMBRE_PROPONENTE" class="form-control" maxlength="200">
                  </div>
                  <div class="col-md-6 mb-3">
                    <label for="CARGO_PROPONENTE">Cargo del delegado</label>
                    <input type="text" id="CARGO_PROPONENTE" name="CARGO_PROPONENTE" class="form-control" maxlength="200">
                  </div>
                  <div class="col-md-6 mb-3">
                    <label for="FACULTAD_PROPONENTE">Facultad</label>
                    <input type="text" id="FACULTAD_PROPONENTE" name="FACULTAD_PROPONENTE" class="form-control" maxlength="200">
                  </div>
                  <div class="col-md-6 mb-3">
                    <label for="DIRECCION_PROPONENTE">Dirección</label>
                    <input type="text" id="DIRECCION_PROPONENTE" name="DIRECCION_PROPONENTE" class="form-control" maxlength="200">
                  </div>
                  <div class="col-md-6 mb-3">
                    <label for="TELEFONO_PROPONENTE">Teléfono</label>
                    <input type="text" id="TELEFONO_PROPONENTE" name="TELEFONO_PROPONENTE" class="form-control" maxlength="200">
                  </div>
                  <div class="col-md-6 mb-3">
                    <label for="EMAIL_PROPONENTE">Email</label>
                    <input type="text" id="EMAIL_PROPONENTE" name="EMAIL_PROPONENTE" class="form-control" maxlength="200">
                  </div>
                </div>
              </div>

              <!-- Sección 6.2: Datos de la Contraparte -->
              <div class="col-12 mb-4">
                <!-- <h4>Delegado por la contraparte</h4> -->
                <div class="row">
                  <div class="col-md-6 mb-3">
                    <label for="UNIDAD_RESPONSABLE_UG">
                      Unidad responsable en la Universidad de Guayaquil
                    </label>
                    <select id="UNIDAD_RESPONSABLE_UG" name="UNIDAD_RESPONSABLE_UG" class="form-control">
                      <option value="">---Seleccione---</option>
                      <option value="Vicerrectorado Académico">Vicerrectorado Académico</option>
                      <option value="Gerencia Administrativa">Gerencia Administrativa</option>
                    </select>
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
                      <option value="el" selected>El</option>
                      <option value="la">La</option>
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

                  <div class="col-md-3 mb-3 d-none">
                    <label for="GENERO_RECTOR_2">Apelativo</label>
                    <select id="GENERO_RECTOR_2" name="GENERO_RECTOR_2" class="form-control">
                      <option value="">---Seleccione---</option>
                      <option value="al">al</option>
                      <option value="a la">a la</option>
                    </select>
                  </div>

                  <div class="col-md-3 mb-3 d-none">
                    <label for="GENERO_RECTOR_3">Apelativo Artículo</label>
                    <select id="GENERO_RECTOR_3" name="GENERO_RECTOR_3" class="form-control">
                      <option value="">---Seleccione---</option>
                      <option value="del">del</option>
                      <option value="de">de</option>
                    </select>
                  </div>

                  <!-- Cláusula 2.8 (Vista Previa): -->
                  <div class="col-12 mb-3">
                    <label class="form-label"><strong>Cláusula 2.8 (Vista Previa):</strong></label>
                    <div id="preview_clausula_28" class="p-3 border rounded bg-light" style="white-space: pre-wrap; font-size: 1.1rem;"></div>
                  </div>

                </div>
              </div>

              <div class="col-12 mb-4">
                <button type="button" class="btn btn-outline-primary mb-3" id="btnToggleClausula28">
                  Editar la cláusula 2.8
                </button>

                <div class="row" id="camposClausula28" style="display: none;">
                  <h4 class="mb-1">
                    <small class="text-muted">CLÁUSULA SEGUNDA: 2.8</small>
                  </h4>
                  <div class="col-md-6 mb-3">
                    <label for="FECHA_ELECCION_RECTOR_SIN_FORMATO">Fecha de Elección</label>
                    <input type="date" id="FECHA_ELECCION_RECTOR_SIN_FORMATO" name="FECHA_ELECCION_RECTOR_SIN_FORMATO" class="form-control" value="2021-03-12">
                  </div>
                  <div class="col-md-6 mb-3">
                    <label for="FECHA_ELECCION_RECTOR_CON_FORMATO">Fecha de Elección (formato)</label>
                    <input type="text" id="FECHA_ELECCION_RECTOR_CON_FORMATO" name="FECHA_ELECCION_RECTOR_CON_FORMATO" class="form-control" readonly>
                  </div>
                  <div class="col-md-6 mb-3">
                    <label for="FECHA_POSESION_RECTOR_SIN_FORMATO">Fecha de Posesión</label>
                    <input type="date" id="FECHA_POSESION_RECTOR_SIN_FORMATO" name="FECHA_POSESION_RECTOR_SIN_FORMATO" class="form-control" value="2021-03-24">
                  </div>
                  <div class="col-md-6 mb-3">
                    <label for="FECHA_POSESION_RECTOR_CON_FORMATO">Fecha de Posesión (formato)</label>
                    <input type="text" id="FECHA_POSESION_RECTOR_CON_FORMATO" name="FECHA_POSESION_RECTOR_CON_FORMATO" class="form-control" readonly>
                  </div>
                  <div class="col-md-6 mb-3">
                    <label for="FECHA_ACCION_PERSONAL_RECTOR_SIN_FORMATO">Fecha de Acción de Personal</label>
                    <input type="date" id="FECHA_ACCION_PERSONAL_RECTOR_SIN_FORMATO" name="FECHA_ACCION_PERSONAL_RECTOR_SIN_FORMATO" class="form-control" value="2021-03-24">
                  </div>
                  <div class="col-md-6 mb-3">
                    <label for="FECHA_ACCION_PERSONAL_RECTOR_CON_FORMATO">Fecha de Acción de Personal (formato)</label>
                    <input type="text" id="FECHA_ACCION_PERSONAL_RECTOR_CON_FORMATO" name="FECHA_ACCION_PERSONAL_RECTOR_CON_FORMATO" class="form-control" readonly>
                  </div>
                  <div class="col-md-6 mb-3">
                    <label for="NUMERO_ACCION_PERSONAL_RECTOR">Número de Acción de Personal: Nro.</label>
                    <input type="text" id="NUMERO_ACCION_PERSONAL_RECTOR" name="NUMERO_ACCION_PERSONAL_RECTOR" class="form-control" value="434-DOC-21" maxlength="200">
                    <small class="form-text text-muted">Si aplica, modifique el número según el nuevo nombramiento.</small>
                  </div>
                </div>
              </div>

              <!-- Sección 3: Datos de la Contraparte -->
              <div class="col-12 mb-4">
                <h4>CLÁUSULA SEGUNDA: ANTECEDENTES</h4>
                <div class="row">

                  <!-- Vista previa -->
                  <div class="col-12 mb-3">
                    <label class="form-label"><strong>Ítem 2.9:</strong></label>
                    <div id="preview_item_2_9" class="p-3 border rounded bg-light" style="white-space: pre-wrap;"></div>
                  </div>

                  <div class="col-12 mb-3">
                    <label for="DESCRIPCION_CONTRAPARTE">Descripción</label>
                    <textarea id="DESCRIPCION_CONTRAPARTE" name="DESCRIPCION_CONTRAPARTE" class="form-control" rows="4"></textarea>
                  </div>

                </div>
              </div>

              <!-- Sección 4: Fases de ejecución -->
              <div class="col-12 mb-4">
                <h4>CLÁUSULA CUARTA: EJECUCIÓN</h4>
                <div class="row">
                  <div class="col-md-4 mb-3">
                    <label for="FASE_INICIAL">Fase inicial</label>
                    <textarea id="FASE_INICIAL" name="FASE_INICIAL" class="form-control" rows="3"></textarea>
                  </div>
                  <div class="col-md-4 mb-3">
                    <label for="FASE_DESARROLLO">Fase de desarrollo</label>
                    <textarea id="FASE_DESARROLLO" name="FASE_DESARROLLO" class="form-control" rows="3"></textarea>
                  </div>
                  <div class="col-md-4 mb-3">
                    <label for="FASE_FINAL">Fase final</label>
                    <textarea id="FASE_FINAL" name="FASE_FINAL" class="form-control" rows="3"></textarea>
                  </div>
                </div>
              </div>

              <!-- BLOQUES ENRIQUECIDOS -->
              <div class="col-12 mb-4">
                <h4>CLÁUSULA QUINTA: OBLIGACIONES DE LAS PARTES</h4>
                <div class="row">
                  <div class="col-md-12 mb-3">
                    <label for="BLOQUE_ENRIQUECIDO1"><strong>La “Universidad de Guayaquil” se compromete a:</strong></label>
                    <textarea id="BLOQUE_ENRIQUECIDO1" name="BLOQUE_ENRIQUECIDO1" class="form-control usar-summernote"></textarea>
                  </div>
                  <div class="col-md-12 mb-3">
                    <label for="BLOQUE_ENRIQUECIDO2"><strong>El “Apelativo / Contraparte” se compromete a:</strong></label>
                    <textarea id="BLOQUE_ENRIQUECIDO2" name="BLOQUE_ENRIQUECIDO2" class="form-control usar-summernote"></textarea>
                  </div>
                </div>
              </div>

              <!-- Sección 7: Financiamiento -->
              <div class="col-12 mb-4">
                <h4>CLÁUSULA SÉPTIMA: FINANCIAMIENTO (si aplica)</h4>
                <div class="mb-3">
                  <label for="BLOQUE_ENRIQUECIDO3">Cláusula de financiamiento</label>
                  <textarea id="BLOQUE_ENRIQUECIDO3" name="BLOQUE_ENRIQUECIDO3" class="form-control usar-summernote" rows="4">
  Este convenio no obligará a ninguna de las partes a la transferencia de recursos económicos entre ellos, por lo tanto, no comprometen partidas presupuestarias.
        </textarea>
                </div>
              </div>

              <!-- Sección 8: Proyecto y Representantes -->
              <div class="col-12 mb-4">
                <h4>CLÁUSULA NOVENA: DE LA VIGENCIA</h4>
                <div class="row">

                  <div class="col-md-6 mb-3">
                    <label for="TIEMPO_VIGENCIA">Tiempo de vigencia (en letras y números)</label>
                    <input type="text" id="TIEMPO_VIGENCIA" name="TIEMPO_VIGENCIA" class="form-control" maxlength="200">
                    <small class="text-muted">Ejemplo: CINCO (5) AÑOS</small>
                  </div>
                </div>
              </div>

            </div> <!-- fin del row -->

            <div class="form-group">
              <label for="formato">Formato de salida:</label>
              <select name="formato" id="formato" class="form-control">
                <option value="word">Word (.docx)</option>
              </select>
            </div>

            <!-- <button type="submit" class="btn btn-primary mt-3">Generar documento</button> -->
            <!-- <button type="submit" name="generar" value="1" class="btn btn-primary mt-3" > Generar documento</button> -->
            <button type="submit" name="generar" value="1" class="btn btn-primary mt-3">
        <i class="fa fa-file-signature me-2"></i>Generar documento
    </button>

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
    // Seleccionamos el formulario por su nuevo ID 'formEspecifico'
    const form = document.getElementById('formEspecifico');

    form.addEventListener('submit', function(e) {
        // 1. Prevenimos el envío automático
        e.preventDefault();

        // 2. Mostramos la alerta de SweetAlert2
        Swal.fire({
            title: '¿Estás seguro?',
            text: "Se generará un nuevo documento y se guardará en la sección 'Documentos Registrados'.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, ¡generar!',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            // 3. Si se confirma, enviamos el formulario
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
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
   * Validar logo al CREAR
   * - Solo permite .jpg, .jpeg, .png
   * - Máximo 2MB
   * - Muestra vista previa
   */
  function validarImagen(input) {
    const file = input.files[0];
    const help = document.getElementById('logoHelp');
    const preview = document.getElementById('previewLogo');

    // Reiniciar mensajes
    help.textContent = '';
    preview.style.display = 'none';
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

    // Mostrar vista previa
    const reader = new FileReader();
    reader.onload = e => {
      preview.src = e.target.result;
      preview.style.display = 'block';
    };
    reader.readAsDataURL(file);
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



<!-- Boton: Editar la cláusula 2.8 -->
<script>
  document.getElementById('btnToggleClausula28').addEventListener('click', function() {
    const campos = document.getElementById('camposClausula28');
    if (campos.style.display === 'none') {
      campos.style.display = '';
      this.textContent = 'Ocultar edición de la cláusula 2.8';
    } else {
      campos.style.display = 'none';
      this.textContent = 'Editar la cláusula 2.8';
    }
  });
</script>



<!-- Sincronización automática de apelativos según género seleccionado -->
<script>
  // Función para sincronizar apelativos según género de rector
  function actualizarApelativosRector() {
    const generoSelect = document.getElementById('GENERO_RECTOR');
    const genero2Select = document.getElementById('GENERO_RECTOR_2');
    const genero3Select = document.getElementById('GENERO_RECTOR_3');

    if (!generoSelect || !genero2Select || !genero3Select) return;

    function sincronizar() {
      const valor = generoSelect.value;
      if (valor === 'el') {
        genero2Select.value = 'al';
        genero3Select.value = 'del';
      } else if (valor === 'la') {
        genero2Select.value = 'a la';
        genero3Select.value = 'de';
      } else {
        genero2Select.value = '';
        genero3Select.value = '';
      }
      if (typeof actualizarClausula2_8 === 'function') actualizarClausula2_8();
    }

    // Inicializa y asocia eventos
    sincronizar();
    generoSelect.addEventListener('change', sincronizar);
  }
</script>


<!--  // Función global para escapar cadenas y prevenir XSS -->
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
<!-- Encabezado inicial del convenio específico -->
<script>
  function actualizarParrafoInicialConvenio() {
    const RAZON_SOCIAL_MAYUS = escapeHtml(document.getElementById('RAZON_SOCIAL_MAYUS').value || '______');
    const NOMBRE_PROYECTO_MAYUS = escapeHtml(document.getElementById('NOMBRE_PROYECTO_MAYUS').value || '______');

    const textoConvenio = `CONVENIO ESPECÍFICO DE COOPERACIÓN INTERINSTITUCIONAL ENTRE LA UNIVERSIDAD DE GUAYAQUIL Y <strong>${RAZON_SOCIAL_MAYUS}</strong> PARA LA EJECUCIÓN DEL PROYECTO <strong>${NOMBRE_PROYECTO_MAYUS}</strong>.`;

    document.getElementById('preview_convenio_especifico').innerHTML = textoConvenio;
  }
</script>

<!-- Inicialización de campos, autollenado y asociación de eventos al cargar la página -->
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Sincronización de mayúsculas, si tienes el script reutilizable
    sincronizarMayus('RAZON_SOCIAL', 'RAZON_SOCIAL_MAYUS', actualizarParrafoInicialConvenio);
    sincronizarMayus('NOMBRE_PROYECTO', 'NOMBRE_PROYECTO_MAYUS', actualizarParrafoInicialConvenio);

    // Si usas autollenado:
    if (typeof datosFormulario !== "undefined" && datosFormulario && Object.keys(datosFormulario).length > 0) {
      for (const [campo, valor] of Object.entries(datosFormulario)) {
        let input = document.getElementById(campo);
        if (input) input.value = valor;
      }
      // Vuelve a sincronizar por si acaso
      sincronizarMayus('RAZON_SOCIAL', 'RAZON_SOCIAL_MAYUS', actualizarParrafoInicialConvenio);
      sincronizarMayus('NOMBRE_PROYECTO', 'NOMBRE_PROYECTO_MAYUS', actualizarParrafoInicialConvenio);
    }

    // Eventos por si el usuario cambia directamente los campos mayúscula
    ['RAZON_SOCIAL_MAYUS', 'NOMBRE_PROYECTO_MAYUS'].forEach(id => {
      const el = document.getElementById(id);
      if (el) el.addEventListener('input', actualizarParrafoInicialConvenio);
      if (el) el.addEventListener('change', actualizarParrafoInicialConvenio);
    });

    // Vista previa inicial
    actualizarParrafoInicialConvenio();
  });
</script>


<!-- 2 -->
<!-- Generación dinámica de la vista previa de la cláusula primera -->
<script>
  function actualizarClausula1() {
    const GENERO_RECTOR = escapeHtml(document.getElementById('GENERO_RECTOR').value || '______');
    const NOMBRE_RECTOR = escapeHtml(document.getElementById('NOMBRE_RECTOR').value || '______');
    const PALABRA_RECTOR = escapeHtml(document.getElementById('PALABRA_RECTOR').value || '______');
    const RAZON_SOCIAL_MAYUS = escapeHtml(document.getElementById('RAZON_SOCIAL_MAYUS').value || '______');
    const GENERO_REPRESENTANTE = escapeHtml(document.getElementById('GENERO_REPRESENTANTE').value || '______');
    const NOMBRE_REPRESENTANTE = escapeHtml(document.getElementById('NOMBRE_REPRESENTANTE').value || '______');
    const CARGO_CONTRAPARTE = escapeHtml(document.getElementById('CARGO_CONTRAPARTE').value || '______');
    const APELATIVO_CONTRAPARTE = escapeHtml(document.getElementById('APELATIVO_CONTRAPARTE').value || '______');

    const textoClausula = `Comparecen a la celebración del presente Convenio Específico, por una parte, la UNIVERSIDAD DE GUAYAQUIL, representada legalmente por <strong>${GENERO_RECTOR} ${NOMBRE_RECTOR}</strong>, en su calidad de <strong>${PALABRA_RECTOR}</strong>, a quien en adelante y para efectos de este instrumento se denominará como “Universidad de Guayaquil”, y, por otra parte, <strong>${RAZON_SOCIAL_MAYUS}</strong> representada legalmente por <strong>${GENERO_REPRESENTANTE} ${NOMBRE_REPRESENTANTE}</strong>, en su calidad de <strong>${CARGO_CONTRAPARTE}</strong>, a quien en adelante y para efectos de este instrumento se denominará como “<strong>${APELATIVO_CONTRAPARTE}</strong>”.`;

    document.getElementById('preview_clausula_compar').innerHTML = textoClausula;
  }
</script>

<!-- Inicialización de campos, autollenado y asociación de eventos al cargar la página -->
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Sincroniza campos que requieren mayúsculas
    sincronizarMayus('RAZON_SOCIAL', 'RAZON_SOCIAL_MAYUS', actualizarClausula1);
    sincronizarMayus('NOMBRE_PROYECTO', 'NOMBRE_PROYECTO_MAYUS'); // Ejemplo sin callback

    // Autollenado si aplica
    if (typeof datosFormulario !== "undefined" && datosFormulario && Object.keys(datosFormulario).length > 0) {
      for (const [campo, valor] of Object.entries(datosFormulario)) {
        let input = document.getElementById(campo);
        if (input) input.value = valor;
      }
      // Re-sincroniza por si los autollenados no disparan el evento input
      sincronizarMayus('RAZON_SOCIAL', 'RAZON_SOCIAL_MAYUS', actualizarClausula1);
      sincronizarMayus('NOMBRE_PROYECTO', 'NOMBRE_PROYECTO_MAYUS');
    }

    // Eventos para la vista previa de la cláusula comparecientes
    const idsCompar = [
      'GENERO_RECTOR',
      'NOMBRE_RECTOR',
      'PALABRA_RECTOR',
      'RAZON_SOCIAL_MAYUS',
      'GENERO_REPRESENTANTE',
      'NOMBRE_REPRESENTANTE',
      'CARGO_CONTRAPARTE',
      'APELATIVO_CONTRAPARTE'
    ];
    idsCompar.forEach(id => {
      const el = document.getElementById(id);
      if (el) el.addEventListener('input', actualizarClausula1);
      if (el) el.addEventListener('change', actualizarClausula1);
    });

    // Vista previa inicial
    actualizarClausula1();
  });
</script>

<!-- 3 -->
<script>
  function actualizarClausula2_8() {
    const FECHA_ELECCION_RECTOR_CON_FORMATO = escapeHtml(document.getElementById('FECHA_ELECCION_RECTOR_CON_FORMATO').value || '______');
    const GENERO_RECTOR_2 = escapeHtml(document.getElementById('GENERO_RECTOR_2').value || '______');
    const PALABRA_RECTOR = escapeHtml(document.getElementById('PALABRA_RECTOR').value || '______');
    const NOMBRE_RECTOR = escapeHtml(document.getElementById('NOMBRE_RECTOR').value || '______');
    const FECHA_POSESION_RECTOR_CON_FORMATO = escapeHtml(document.getElementById('FECHA_POSESION_RECTOR_CON_FORMATO').value || '______');
    const NUMERO_ACCION_PERSONAL_RECTOR = escapeHtml(document.getElementById('NUMERO_ACCION_PERSONAL_RECTOR').value || '______');
    const FECHA_ACCION_PERSONAL_RECTOR_CON_FORMATO = escapeHtml(document.getElementById('FECHA_ACCION_PERSONAL_RECTOR_CON_FORMATO').value || '______');
    const GENERO_RECTOR_3 = escapeHtml(document.getElementById('GENERO_RECTOR_3').value || '______');

    const textoClausula = `Que, el día <strong>${FECHA_ELECCION_RECTOR_CON_FORMATO}</strong>, se llevaron a cabo las elecciones convocadas para elegir <strong>${GENERO_RECTOR_2}</strong> <strong>${PALABRA_RECTOR}</strong> y Vicerrector Académico y a los Miembros Consejo Superior Universitario de la Universidad de Guayaquil, cuyos resultados le confirieron la calidad de <strong>${PALABRA_RECTOR}</strong> de esta institución de educación superior <strong>${GENERO_RECTOR_2}</strong> <strong>${NOMBRE_RECTOR}</strong>; por lo que, el <strong>${FECHA_POSESION_RECTOR_CON_FORMATO}</strong>, se realizó la posesión de las autoridades electas y mediante Acción de Personal Nro. <strong>${NUMERO_ACCION_PERSONAL_RECTOR}</strong> de fecha <strong>${FECHA_ACCION_PERSONAL_RECTOR_CON_FORMATO}</strong>, se expidió el nombramiento <strong>${GENERO_RECTOR_3}</strong> <strong>${PALABRA_RECTOR}</strong> de la Universidad de Guayaquil.`;

    document.getElementById('preview_clausula_28').innerHTML = textoClausula;
  }
</script>

<!-- Inicialización de campos, autollenado y asociación de eventos al cargar la página -->
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Si hay autollenado (datosFormulario), primero pon los valores:
    if (typeof datosFormulario !== "undefined" && datosFormulario && Object.keys(datosFormulario).length > 0) {
      for (const [campo, valor] of Object.entries(datosFormulario)) {
        let input = document.getElementById(campo);
        if (input) input.value = valor;
      }
    }

    // 1. Inicializa apelativos (esto también sincroniza la vista previa)
    actualizarApelativosRector();

    // 2. Inicializa fechas con callback (esto también sincroniza la vista previa)
    formatearFecha('FECHA_ELECCION_RECTOR_SIN_FORMATO', 'FECHA_ELECCION_RECTOR_CON_FORMATO', actualizarClausula2_8);
    formatearFecha('FECHA_POSESION_RECTOR_SIN_FORMATO', 'FECHA_POSESION_RECTOR_CON_FORMATO', actualizarClausula2_8);
    formatearFecha('FECHA_ACCION_PERSONAL_RECTOR_SIN_FORMATO', 'FECHA_ACCION_PERSONAL_RECTOR_CON_FORMATO', actualizarClausula2_8);

    // 3. Asociar eventos input/change a los campos relevantes
    const ids_28 = [
      'GENERO_RECTOR_2',
      'PALABRA_RECTOR',
      'NOMBRE_RECTOR',
      'NUMERO_ACCION_PERSONAL_RECTOR',
      'GENERO_RECTOR_3'
    ];
    ids_28.forEach(id => {
      const el = document.getElementById(id);
      if (el) el.addEventListener('input', actualizarClausula2_8);
      if (el) el.addEventListener('change', actualizarClausula2_8);
    });

    // 4. Actualiza vista previa inicial (por si acaso)
    actualizarClausula2_8();
  });
</script>


<!-- 4 -->
<!-- Script para actualizar el texto dinámicamente del ítem 2.9 - PART 1-->
<script>
  function actualizarClausula2_9() {
    const apelativo = escapeHtml(document.getElementById('APELATIVO_CONTRAPARTE').value.trim() || '______');
    const descripcion = escapeHtml(document.getElementById('DESCRIPCION_CONTRAPARTE').value.trim() || '______');

    const texto = `2.9 “${apelativo}”, ${descripcion}.`;

    document.getElementById('preview_item_2_9').textContent = texto;
  }

  document.addEventListener('DOMContentLoaded', function() {
    ['APELATIVO_CONTRAPARTE', 'DESCRIPCION_CONTRAPARTE'].forEach(id => {
      const el = document.getElementById(id);
      if (el) el.addEventListener('input', actualizarClausula2_9);
      if (el) el.addEventListener('change', actualizarClausula2_9);
    });
    actualizarClausula2_9();
  });
</script>