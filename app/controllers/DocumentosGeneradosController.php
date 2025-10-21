<?php
require_once ROOT_PATH . '/vendor/autoload.php';

use PhpOffice\PhpWord\TemplateProcessor;

class DocumentosGeneradosController
{
    private $baseUrl;

    public function __construct()
    {
        $config = require ROOT_PATH . '/config/app.php';
        $this->baseUrl = $config['base_url'];
    }

    private function _setupPermissions()
    {
        $rolId = $_SESSION['user']['perfilId'] ?? 0;

        // =================================================================
        // LÍNEA DE DEPURACIÓN: Descomenta la siguiente línea temporalmente
        // para ver qué ID de perfil tiene tu sesión actual.
        // var_dump("El ID de Perfil de la sesión es: " . $rolId); die();
        // =================================================================

        $permisos = [
            'puede_ver_todo' => false,
            'puede_editar' => false,
            'puede_eliminar' => false,
            'puede_enviar' => false,
            'puede_aprobar' => false,
        ];

        switch ($rolId) {
            case 20260:
            case 20255: // SEGUIMIENTO_CONVENIO_ADMIN
                $permisos['puede_ver_todo'] = true;
                $permisos['puede_editar'] = true;
                $permisos['puede_eliminar'] = true;
                $permisos['puede_enviar'] = true;
                $permisos['puede_aprobar'] = true;
                $_SESSION['user']['rol'] = 'Administrador';
                break;

            case 20261: // SEGUIMIENTO_CONVENIO_GESTOR
            case 20262: // SEGUIMIENTO_CONVENIO_GESTOR (si aplica)
                $permisos['puede_ver_todo'] = true;
                $permisos['puede_aprobar'] = true;
                $_SESSION['user']['rol'] = 'Gestor';
                break;

            case 20259: // SEGUIMIENTO_PROYECTO_DOCENTE (Asumido como creador de convenios)
                $permisos['puede_editar'] = true;
                $permisos['puede_eliminar'] = true;
                $permisos['puede_enviar'] = true;
                $_SESSION['user']['rol'] = 'Director';
                break;
        }

        return $permisos;
    }

    // =======================
    // MÉTODOS PÚBLICOS
    // =======================

    // Muestra todos los documentos
    public function index()
    {
        require_once __DIR__ . '/../models/DocumentosGeneradosModel.php';
        $model = new DocumentosGeneradosModel();
        $documentos = $model->obtenerTodos();

        $permisos = $this->_setupPermissions();
        $this->view('documentos/index', [
            'documentos' => $documentos,
            'baseUrl'    => $this->baseUrl,
            'permisos'   => $permisos
        ]);
    }

     /**
     * Cambia el estado a APROBADO y GUARDA la trazabilidad.
     */
    /**
     * Cambia el estado a APROBADO y GUARDA la trazabilidad.
     * AÑADIDO: Bloque try-catch para capturar errores de la base de datos.
     */
    /**
     * Cambia el estado y guarda la trazabilidad, VERIFICANDO la respuesta del SP.
     */
    public function aprobar() {
        $permisos = $this->_setupPermissions();
        if (!$permisos['puede_aprobar']) {
            die("Acceso denegado.");
        }

        $idDocGen = $_GET['id'] ?? null;
        if ($idDocGen) {
            try {
                require_once __DIR__ . '/../models/DocumentosGeneradosModel.php';
                $model = new DocumentosGeneradosModel();
                
                // Paso 1: Ejecutar y CAPTURAR la respuesta del modelo
                $resultadoUpdate = $model->actualizarEstado($idDocGen, 'APROBADO');

                // Paso 2: VERIFICAR si el SP devolvió un error lógico
                if (isset($resultadoUpdate['resultado']['Table'][0]['CodErrorOut']) && $resultadoUpdate['resultado']['Table'][0]['CodErrorOut'] != 0) {
                    $errorMsg = $resultadoUpdate['resultado']['Table'][0]['SmsOut'] ?? 'Error desconocido desde la base de datos.';
                    die("No se pudo actualizar el estado. Mensaje del SP: " . $errorMsg);
                }

                // Paso 3: Si todo fue exitoso, registrar la trazabilidad
                require_once __DIR__ . '/../models/TrazabilidadModel.php';
                $trazabilidadModel = new TrazabilidadModel();
                $usuario = $_SESSION['user']['nombre'] . ' ' . htmlspecialchars($_SESSION['user']['apellidos'] ?? 'sistema');
                $rol = $_SESSION['user']['rol'] ?? 'Rol Desconocido';
                $trazabilidadModel->guardarAccion($idDocGen, $usuario, $rol, 'APROBADO' ,'Documento Aprobado');

            } catch (Exception $e) {
                die("ERROR FATAL AL PROCESAR LA SOLICITUD: " . $e->getMessage());
            }
        }
        header("Location: {$this->baseUrl}/index.php?c=DocumentosGenerados&m=index");
        exit;
    }

    /**
     * Cambia el estado y guarda la trazabilidad, VERIFICANDO la respuesta del SP.
     */
    public function enviar() {
        $permisos = $this->_setupPermissions();
        if (!$permisos['puede_enviar']) {
            die("Acceso denegado.");
        }

        $idDocGen = $_GET['id'] ?? null;
        if ($idDocGen) {
            try {
                require_once __DIR__ . '/../models/DocumentosGeneradosModel.php';
                $model = new DocumentosGeneradosModel();
                
                // Paso 1: Ejecutar y CAPTURAR la respuesta del modelo
                $resultadoUpdate = $model->actualizarEstado($idDocGen, 'PENDIENTE');
                
                // Paso 2: VERIFICAR si el SP devolvió un error lógico
                if (isset($resultadoUpdate['resultado']['Table'][0]['CodErrorOut']) && $resultadoUpdate['resultado']['Table'][0]['CodErrorOut'] != 0) {
                    $errorMsg = $resultadoUpdate['resultado']['Table'][0]['SmsOut'] ?? 'Error desconocido desde la base de datos.';
                    die("No se pudo actualizar el estado. Mensaje del SP: " . $errorMsg);
                }

                // Paso 3: Si todo fue exitoso, registrar la trazabilidad
                require_once __DIR__ . '/../models/TrazabilidadModel.php';
                $trazabilidadModel = new TrazabilidadModel();
                $usuario = $_SESSION['user']['nombre'] . ' ' . htmlspecialchars($_SESSION['user']['apellidos'] ?? 'sistema');
                $rol = $_SESSION['user']['rol'] ?? 'Rol Desconocido';
                $trazabilidadModel->guardarAccion($idDocGen, $usuario, $rol, 'PENDIENTE', 'Enviado para revisión');

            } catch (Exception $e) {
                die("ERROR FATAL AL PROCESAR LA SOLICITUD: " . $e->getMessage());
            }
        }
        header("Location: {$this->baseUrl}/index.php?c=DocumentosGenerados&m=index");
        exit;
    }
    // Descarga un documento (Word o PDF)
    public function descargar()
    {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header("Location: {$this->baseUrl}/index.php?c=DocumentosGenerados&m=index");
            exit;
        }
        require_once __DIR__ . '/../models/DocumentosGeneradosModel.php';
        $model = new DocumentosGeneradosModel();
        $doc = $model->obtenerPorId($id);
        if (!$doc || !file_exists($doc['RutaArchivoDocGen'])) {
            header("Location: {$this->baseUrl}/index.php?c=DocumentosGenerados&m=index");
            exit;
        }
        $ext = strtolower(pathinfo($doc['RutaArchivoDocGen'], PATHINFO_EXTENSION));
        $mime = $ext === 'pdf'
            ? 'application/pdf'
            : 'application/vnd.openxmlformats-officedocument.wordprocessingml.document';
        header("Content-Type: {$mime}");
        header("Content-Disposition: attachment; filename=\"{$doc['NombreArchivoDocGen']}\"");
        readfile($doc['RutaArchivoDocGen']);
        exit;
    }

    // Elimina el documento generado + JSON del documento (DEL SISTEMA)
    public function eliminar()
    {

        $permisos = $this->_setupPermissions();
        if (!$permisos['puede_eliminar']) {
            die("Acceso denegado."); // Protección a nivel de controlador
        }
        $id = $_GET['id'] ?? null;

        if ($id) {
            require_once __DIR__ . '/../models/DocumentosGeneradosModel.php';
            $model = new DocumentosGeneradosModel();

            // Buscar el documento
            $doc = $model->obtenerPorId($id);

            // =======================================================
            // INICIO: AGREGAR ESTE BLOQUE PARA LA TRAZABILIDAD
            // =======================================================
            if ($doc) {
                require_once __DIR__ . '/../models/TrazabilidadModel.php';
                $trazabilidadModel = new TrazabilidadModel();
                $usuario = $_SESSION['user']['nombre'] . ' ' . htmlspecialchars($_SESSION['user']['apellidos'] ?? 'sistema');
                $rol = $_SESSION['user']['rol'] ?? 'Rol Desconocido';
                $accion = "Documento '" . $doc['NombreArchivoDocGen'] . "' fue eliminado.";
                $trazabilidadModel->guardarAccion($id, $usuario, $rol, $accion);
            }
            // =======================================================
            // FIN DEL BLOQUE
            // =======================================================


            // Buscar el JSON asociado
            $infoConvenio = $model->obtenerDatosConvenioPorIdDocGen($id);
            $rutaJson = $infoConvenio['RutaArchivoFormularioDatCon'] ?? null;

            // Eliminar el archivo Word/PDF físico
            if ($doc && file_exists($doc['RutaArchivoDocGen'])) {
                unlink($doc['RutaArchivoDocGen']);
            }

            // Eliminar el archivo JSON
            if ($rutaJson && file_exists($rutaJson)) {
                unlink($rutaJson);
            }

            // Borrar registros en base de datos
            $model->eliminarPorId($id);
            $idDatCon = $infoConvenio['IdDatCon'];
            $model->eliminarDatosConvenioPorIdDocGen($idDatCon);
        }

        header("Location: {$this->baseUrl}/index.php?c=DocumentosGenerados&m=index");
        exit;
    }

    public function verTrazabilidad()
    {
        $idDocGen = $_GET['id'] ?? null;
        if (!$idDocGen) {
            echo "<p>ID de documento no proporcionado.</p>";
            return;
        }

        require_once __DIR__ . '/../models/TrazabilidadModel.php';
        $trazabilidadModel = new TrazabilidadModel();
        $trazabilidad = $trazabilidadModel->obtenerTrazabilidadPorDocumento($idDocGen);

        $this->view('documentos/modal', [
            'trazabilidad' => $trazabilidad
        ]);
    }


    // ----- ADENDUM -----
    public function editarAdendum()
    {
        $this->cargarVistaEdicionConvenio('adendum');
    }
    public function actualizarAdendum()
    {
        $this->actualizarConvenio('adendum');
    }

    // ----- MARCO -----
    public function editarMarco()
    {
        $this->cargarVistaEdicionConvenio('marco');
    }
    public function actualizarMarco()
    {
        $this->actualizarConvenio('marco');
    }

    // ----- ESPECÍFICO -----
    public function editarEspecifico()
    {
        $this->cargarVistaEdicionConvenio('especifico');
    }
    public function actualizarEspecifico()
    {
        $this->actualizarConvenio('especifico');
    }

    // =======================
    // FUNCIONES REUTILIZABLES
    // =======================

    // ---TP---
    private function cargarVistaEdicionConvenio($tipo)
    {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            http_response_code(400);
            exit("ID de documento no proporcionado.");
        }

        require_once __DIR__ . '/../models/DocumentosGeneradosModel.php';
        $model = new DocumentosGeneradosModel();

        $doc = $model->obtenerPorId($id);
        if (!$doc) {
            http_response_code(404);
            exit("Documento no encontrado.");
        }

        $infoConvenio = $model->obtenerDatosConvenioPorIdDocGen($id);

        if (!$infoConvenio) {
            http_response_code(404);
            exit("No existe información asociada en datos_convenios.");
        }

        $rutaJson = $infoConvenio['RutaArchivoFormularioDatCon'] ?? null;

        $formulario = [];
        if ($rutaJson && file_exists($rutaJson)) {
            $jsonData = file_get_contents($rutaJson);
            $formulario = json_decode($jsonData, true);
            if (!is_array($formulario)) $formulario = [];
        }

        // Cargar las propuestas
        require_once __DIR__ . '/../models/PropuestaModel.php';
        $modeloPropuestas = new PropuestaModel();
        $proyectos = $modeloPropuestas->obtenerTodas(); // <-- ya está bien

        $this->view('documentos/editar_convenio_' . $tipo, [
            'documento'  => $doc,
            'formulario' => $formulario,
            'id_doc_gen' => $id,
            'baseUrl'    => $this->baseUrl,
            'proyectos'  => $proyectos, // <-- así llega a la vista
            'datos'      => $infoConvenio,
        ]);
    }

    /**
     * Procesa la actualización para cualquier tipo de convenio.
     */
    private function actualizarConvenio($tipo)
    {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            http_response_code(400);
            exit("ID de documento no proporcionado.");
        }

        require_once __DIR__ . '/../models/DocumentosGeneradosModel.php';
        $model = new DocumentosGeneradosModel();

        $infoConvenio = $model->obtenerDatosConvenioPorIdDocGen($id);
        if (!$infoConvenio || empty($infoConvenio['RutaArchivoFormularioDatCon'])) {
            http_response_code(404);
            exit("No se encontró el convenio asociado.");
        }
        $rutaJson = $infoConvenio['RutaArchivoFormularioDatCon'];

        // Cargar logo anterior si existe
        $logoAnterior = '';
        if (file_exists($rutaJson)) {
            $jsonViejo = json_decode(file_get_contents($rutaJson), true);
            if (is_array($jsonViejo) && !empty($jsonViejo['LOGO_CONTRAPARTE_RUTA'])) {
                $logoAnterior = $jsonViejo['LOGO_CONTRAPARTE_RUTA'];
            }
        }

        // Cargar base_url desde config para usar rutas dinámicas
        $config = include ROOT_PATH . '/config/app.php';
        $baseUrl = $config['base_url']; // Ejemplo: '/convenio_UG/public'

        // 1. Limpiar y preparar datos
        $datos = $_POST;
        unset($datos['_enriquecidos'], $datos['formato']); // limpiar campos técnicos

        // 2. Procesar logo (subida nueva, mantener anterior, o quitar)
        $quitarLogo = !empty($_POST['quitar_logo']);
        if ($quitarLogo) {
            $logoPath = '';
            $logoPublicPath = '';
        } else {
            list($logoPath, $logoPublicPath) = $this->procesarLogo(
                $_FILES,
                // Ruta física en el servidor (carpeta /public/, NO confundir con el 'public' de la base_url en app.php)
                ROOT_PATH . '/public/uploads_logo_contraparte/',
                // Ruta pública dinámica basada en base_url (usada en el navegador)
                $baseUrl . '/uploads_logo_contraparte/',
                $logoAnterior
            );
        }
        $datos['LOGO_CONTRAPARTE_RUTA'] = $logoPublicPath;

        // 3. ---TP---V2 Guardar JSON actualizado
        $rutaJson = $this->guardarFormularioComoJson($datos, basename($rutaJson, '.json'), $logoPublicPath);

        // 3.5. ---TP---V2 Validar propuesta (convertir cadena vacía en NULL si no se selecciona nada)
        $idProyecto = !empty($_POST['id_proyecto']) ? $_POST['id_proyecto'] : null;

        // 4. ---TP---V2 Actualizar datos_convenios
        $model->actualizarDatosConvenio(
            $id,
            $tipo,
            $rutaJson,
            $idProyecto // <-- aquí va el id del proyecto seleccionado
        );

        // 5. Regenerar DOCX
        $infoDoc = $model->obtenerPorId($id);
        $rutaDocx = $infoDoc['RutaArchivoDocGen'];
        $plantilla = ROOT_PATH . '/plantillas/plantilla_convenio_' . $tipo . '.docx';
        if (!file_exists($plantilla)) {
            http_response_code(500);
            exit("Plantilla no encontrada: $plantilla");
        }

        try {
            $tpl = new TemplateProcessor($plantilla);
            $enriquecidos = json_decode($_POST['_enriquecidos'] ?? '[]', true);
            $this->reemplazarCamposDocx($tpl, $datos, $enriquecidos);

            // Logo (solo si hay ruta física válida)
            if ($logoPath && file_exists($logoPath)) {
                $tpl->setImageValue('LOGO_CONTRAPARTE', [
                    'path'   => $logoPath,
                    'width'  => 80,
                    'height' => 80,
                    'ratio'  => true,
                ]);
            } else {
                $tpl->setValue('LOGO_CONTRAPARTE', '');
            }
            $tpl->saveAs($rutaDocx);
            // =======================================================
            // INICIO: AGREGAR ESTE BLOQUE PARA LA TRAZABILIDAD
            // =======================================================
            require_once __DIR__ . '/../models/TrazabilidadModel.php';
            $trazabilidadModel = new TrazabilidadModel();
            $usuario = $_SESSION['user']['nombre'] . ' ' . htmlspecialchars($_SESSION['user']['apellidos'] ?? 'Sistema');
            // IMPORTANTE: Asegúrate de tener el rol del usuario en la sesión.
            // Si no lo tienes, deberás obtenerlo de alguna manera.
            $rol = $_SESSION['user']['rol'] ?? 'Rol Desconocido';
            $accion = "Convenio de tipo '" . ucfirst($tipo) . "' fue actualizado.";

            // Llama al nuevo método del modelo
            $trazabilidadModel->guardarAccion($id, $usuario, $rol, $accion);
            // =======================================================
            // FIN DEL BLOQUE
            // =======================================================
            $accion = $_POST['accion'] ?? 'actualizar_regresar';
            if ($accion === 'actualizar_quedarse') {
                // Redirige de nuevo a la misma página de edición
                header("Location: {$this->baseUrl}/index.php?c=DocumentosGenerados&m=editar" . ucfirst($tipo) . "&id=" . urlencode($id));
                exit;
            } else {
                // Redirige al índice principal
                header("Location: {$this->baseUrl}/index.php?c=DocumentosGenerados&m=index");
                exit;
            }
        } catch (\Throwable $e) {
            http_response_code(500);
            exit("Error al actualizar documento: " . $e->getMessage());
        }
    }

    /**
     * Sube el logo, valida y devuelve rutas (física y pública).
     * Si no hay logo nuevo, retorna la ruta anterior.
     */
    private function procesarLogo($files, $uploadDir, $publicDir, $logoAnterior = '')
    {
        $logoPath = '';
        $logoPublicPath = '';
        if (!empty($files['LOGO_CONTRAPARTE']['tmp_name'])) {
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

            $maxSize = 2 * 1024 * 1024; // 2 MB
            if ($files['LOGO_CONTRAPARTE']['size'] > $maxSize) {
                http_response_code(400);
                exit("El archivo supera el tamaño máximo permitido (2MB).");
            }
            $originalName = basename($files['LOGO_CONTRAPARTE']['name']);
            $fileName = time() . '_' . $originalName;
            $destino = $uploadDir . $fileName;

            if (!move_uploaded_file($files['LOGO_CONTRAPARTE']['tmp_name'], $destino)) {
                http_response_code(500);
                exit("No se pudo mover el archivo subido.");
            }
            $logoPath = $destino;
            $logoPublicPath = $publicDir . $fileName;
        } elseif ($logoAnterior) {
            // Mantener el logo anterior
            $logoPublicPath = $logoAnterior;

            // Reconstruir la ruta física del logo anterior dinámicamente
            $logoRel = ltrim($logoAnterior, '/');

            $baseUrlTrimmed = ltrim($publicDir, '/'); // Quita la barra al inicio
            if (strpos($logoRel, $baseUrlTrimmed) === 0) {
                $logoRel = substr($logoRel, strlen($baseUrlTrimmed));
            }
            // Ruta física en el servidor (carpeta /public/, NO confundir con el 'public' de la base_url en app.php)
            $logoPath = ROOT_PATH . '/public/uploads_logo_contraparte/' . $logoRel;
        }
        return [$logoPath, $logoPublicPath];
    }


    /**
     * Limpia los campos enriquecidos (HTML -> texto plano).
     */
    private function limpiarCamposEnriquecidos($datos, $enriquecidos)
    {
        $resultado = $datos;
        foreach ($enriquecidos as $campo) {
            if (isset($datos[$campo])) {
                $text = $datos[$campo];
                $text = preg_replace('#<br\s*/?>#i', "\n\n", $text);
                $text = preg_replace('#</p>#i', "\n\n", $text);
                $text = preg_replace('#</div>#i', "\n\n", $text);
                if (preg_match('#<ol[^>]*>#i', $text)) {
                    $parts = preg_split('#(<ol[^>]*>.*?</ol>)#is', $text, -1, PREG_SPLIT_DELIM_CAPTURE);
                    $output = '';
                    foreach ($parts as $part) {
                        if (preg_match('#^<ol[^>]*>#i', $part)) {
                            preg_match_all('#<li[^>]*>(.*?)</li>#is', $part, $matches);
                            $items = $matches[1] ?? [];
                            $letras = range('a', 'z');
                            foreach ($items as $i => $item) {
                                $contenido = strip_tags($item);
                                $contenido = html_entity_decode($contenido, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                                $output .= ($letras[$i] ?? '*') . ") " . trim($contenido) . "\n\n";
                            }
                        } else {
                            $temp = html_entity_decode($part, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                            $temp = strip_tags($temp);
                            $temp = trim($temp);
                            if ($temp !== '') $output .= $temp . "\n\n";
                        }
                    }
                    $text = trim($output);
                } else {
                    $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                    $text = strip_tags($text);
                    $text = trim($text);
                }
                $resultado[$campo] = $text;
            }
        }
        return $resultado;
    }

    /**
     * Reemplaza los campos (simples y enriquecidos) en la plantilla DOCX.
     */
    private function reemplazarCamposDocx($tpl, $datos, $enriquecidos)
    {
        // Enriquecidos
        foreach ($enriquecidos as $campo) {
            if (isset($datos[$campo])) {
                $text = $datos[$campo];
                $text = preg_replace('#<br\s*/?>#i', "\n\n", $text);
                $text = preg_replace('#</p>#i', "\n\n", $text);
                $text = preg_replace('#</div>#i', "\n\n", $text);
                if (preg_match('#<ol[^>]*>#i', $text)) {
                    $parts = preg_split('#(<ol[^>]*>.*?</ol>)#is', $text, -1, PREG_SPLIT_DELIM_CAPTURE);
                    $output = '';
                    foreach ($parts as $part) {
                        if (preg_match('#^<ol[^>]*>#i', $part)) {
                            preg_match_all('#<li[^>]*>(.*?)</li>#is', $part, $matches);
                            $items = $matches[1] ?? [];
                            $letras = range('a', 'z');
                            foreach ($items as $i => $item) {
                                $contenido = strip_tags($item);
                                $contenido = html_entity_decode($contenido, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                                $output .= ($letras[$i] ?? '*') . ") " . trim($contenido) . "\n\n";
                            }
                        } else {
                            $temp = html_entity_decode($part, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                            $temp = strip_tags($temp);
                            $temp = trim($temp);
                            if ($temp !== '') $output .= $temp . "\n\n";
                        }
                    }
                    $text = trim($output);
                } else {
                    $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                    $text = strip_tags($text);
                    $text = trim($text);
                }
                $tpl->setValue($campo, $text);
            } else {
                $tpl->setValue($campo, '');
            }
        }
        // Simples
        foreach ($datos as $key => $val) {
            if ($key !== '_enriquecidos' && !in_array($key, $enriquecidos)) {
                $tpl->setValue($key, htmlspecialchars($val, ENT_QUOTES | ENT_XML1, 'UTF-8'));
            }
        }
    }

    /**
     * Guarda el formulario como JSON.
     */
    private function guardarFormularioComoJson($datos, $nombreBase, $logoPublicPath = null)
    {
        unset($datos['_enriquecidos'], $datos['formato']);
        if ($logoPublicPath !== null) {
            $datos['LOGO_CONTRAPARTE_RUTA'] = $logoPublicPath;
        }
        $jsonDir = ROOT_PATH . '/writable/datos_convenio_formulario/';
        if (!is_dir($jsonDir)) mkdir($jsonDir, 0755, true);
        $nombreBase = preg_replace('/\.docx$/', '', $nombreBase);
        $nombreArchivo = $nombreBase . '.json';
        $rutaArchivo = $jsonDir . $nombreArchivo;
        file_put_contents($rutaArchivo, json_encode($datos, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
        return $rutaArchivo;
    }

    /**
     * Carga la vista.
     */
    protected function view($vista, $data = [])
    {
        extract($data);
        include ROOT_PATH . "/app/views/{$vista}.php";
    }

    // FUNICON SIN USO - Si llegas a necesitar UUID
    private function generarUUID(): string
    {
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff)
        );
    }
}
