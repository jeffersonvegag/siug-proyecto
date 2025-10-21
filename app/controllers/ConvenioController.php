<?php
require_once ROOT_PATH . '/vendor/autoload.php';

use PhpOffice\PhpWord\TemplateProcessor;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Shared\Html;
use PhpOffice\PhpWord\SimpleType\Jc;


class ConvenioController extends Controller
{

    // ========= CONVENIO ESPECÍFICO =========

    // ---TP---

    // SI SE OCUPA  class ConvenioController sin extends
    // public function formularioConvenioEspecifico() {
    //     require_once __DIR__ . '/../models/PropuestaModel.php';
    //     $model = new PropuestaModel();
    //     $proyectos = $model->obtenerTodas(); // <-- asegúrate de que este método ya esté corregido para proyectos
    //
    //     // Si usas require para mostrar la vista:
    //     require ROOT_PATH . '/app/views/convenio/convenio_especifico.php';
    // }


    // En app/controllers/ConvenioController.php

// 1. SIMPLIFICA el método original para que solo cargue la lista
public function formularioConvenioEspecifico()
{
    require_once __DIR__ . '/../models/PropuestaModel.php';
    $model = new PropuestaModel();

    // Ahora solo obtenemos la lista simple. ¡Esto es súper rápido!
    $proyectos = $model->obtenerTodas();

    $this->view("convenio/convenio_especifico", [
        "proyectos" => $proyectos,
        // Ya no enviamos $proyectosCompletos, irá vacío
        "proyectosCompletos" => [] 
    ]);
}

// 2. CREA un nuevo método para responder a las peticiones AJAX
public function getProyectoParaConvenioAjax()
{
    // Establece la cabecera para devolver JSON
    header('Content-Type: application/json');

    $idProyecto = $_GET['id'] ?? null;
    if (!$idProyecto) {
        echo json_encode(['error' => 'No se proporcionó un ID de proyecto.']);
        return;
    }

    require_once __DIR__ . '/../models/PropuestaModel.php';
    $model = new PropuestaModel();

    // Obtenemos los datos completos solo para el ID solicitado
    $datosCompletos = $model->obtenerDatosCompletosPorId($idProyecto);

    if ($datosCompletos) {
        echo json_encode($datosCompletos);
    } else {
        echo json_encode(['error' => 'Proyecto no encontrado.']);
    }
    // Importante: detenemos la ejecución para no renderizar nada más
    exit;
}


    public function generarDesdeFormularioEspecifico()
    {
        require_once __DIR__ . '/../models/DocumentosGeneradosModel.php';
        require_once __DIR__ . '/../models/PropuestaModel.php'; // <-- Agrega esto
        $model = new DocumentosGeneradosModel();
        $modelProy = new PropuestaModel();
        $datos = $_POST;

        $config = include ROOT_PATH . '/config/app.php';
        $baseUrl = $config['base_url'];

        list($logoPath, $logoPublicPath) = $this->procesarLogo(
            $_FILES['LOGO_CONTRAPARTE'],
            ROOT_PATH . '/public/uploads_logo_contraparte/',
            $baseUrl . '/uploads_logo_contraparte/'
        );

        $enriquecidos = json_decode($datos['_enriquecidos'] ?? '[]', true);
        $datos_json = $datos;

        // --- AQUÍ: Cargar datos completos del proyecto seleccionado ---
        $idProyecto = !empty($_POST['id_proyecto']) ? $_POST['id_proyecto'] : null;
        if ($idProyecto) {
            $datosExtra = $modelProy->obtenerDatosCompletosPorId($idProyecto);
            if ($datosExtra) {
                // Sobrescribe los campos del formulario (hazlo solo para los que necesitas, aquí todos)
                foreach ($datosExtra as $k => $v) {
                    $datos[$k] = $v;
                }
            }
        }

        $datos = $this->limpiarCamposEnriquecidos($datos, $enriquecidos);

        $templatePath = ROOT_PATH . '/plantillas/plantilla_convenio_especifico.docx';
        if (!file_exists($templatePath)) {
            http_response_code(500);
            exit("Plantilla no encontrada en: $templatePath");
        }

        try {
            $tpl = new TemplateProcessor($templatePath);
            $this->reemplazarCamposDocx($tpl, $datos, $enriquecidos);

            if ($logoPath) {
                $tpl->setImageValue('LOGO_CONTRAPARTE', [
                    'path'   => $logoPath,
                    'width'  => 80,
                    'height' => 80,
                    'ratio'  => true,
                ]);
            } else {
                $tpl->setValue('LOGO_CONTRAPARTE', '');
            }

            $timestamp      = time();
            $idGrupo        = $this->generarUUID();
            $nombreDocx     = "convenio_especifico_{$timestamp}.docx";
            $documentosDir  = ROOT_PATH . '/writable/documentos_convenios_generados/';
            if (!is_dir($documentosDir)) mkdir($documentosDir, 0755, true);
            $rutaDocx       = $documentosDir . $nombreDocx;

            $tpl->saveAs($rutaDocx);

            $idDocGen = $model->guardarDocumentoGenerado($idGrupo, $nombreDocx, $rutaDocx, 'WORD');
            $rutaJson = $this->guardarFormularioComoJson($datos_json, $nombreDocx, $logoPublicPath);
            $model->guardarDatosConvenio($idDocGen, 'especifico', $rutaJson, $idProyecto);

            header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
            header("Content-Disposition: attachment; filename={$nombreDocx}");
            readfile($rutaDocx);
            exit;
        } catch (\Throwable $e) {
            http_response_code(500);
            exit("Error al generar documento: " . $e->getMessage());
        }
    }

    // app/controllers/ConvenioController.php

    public function obtenerDatosProyecto()
    {
        require_once __DIR__ . '/../models/PropuestaModel.php';
        $model = new PropuestaModel();

        $idProyecto = $_POST['id_proyecto'] ?? null;
        if (!$idProyecto) {
            echo json_encode(['error' => 'ID no enviado']);
            exit;
        }

        $datos = $model->obtenerDatosCompletosPorId($idProyecto);
        // Opcional: quitar ObjetivoEspecifico si no quieres llenarlo
        unset($datos['ObjetivoEspecifico']);
        echo json_encode($datos);
        exit;
    }


    // ========= CONVENIO MARCO =========
    public function formularioConvenioMarco()
    {
        $this->view("convenio/convenio_marco");
    }

    public function generarDesdeFormularioMarco()
    {
        require_once __DIR__ . '/../models/DocumentosGeneradosModel.php';
        $model = new DocumentosGeneradosModel();
        $datos = $_POST;

        // Cargar base_url desde config para rutas dinámicas
        $config = include ROOT_PATH . '/config/app.php';
        $baseUrl = $config['base_url']; // Ejemplo: '/convenio_UG/public'

        // --- PROCESA LOGO ---
        list($logoPath, $logoPublicPath) = $this->procesarLogo(
            $_FILES['LOGO_CONTRAPARTE'],
            // Ruta física en el servidor (carpeta /public/, NO confundir con el 'public' de la base_url del app.php)
            ROOT_PATH . '/public/uploads_logo_contraparte/',
            // Ruta pública dinámica basada en base_url (usada en el navegador)
            $baseUrl . '/uploads_logo_contraparte/'
        );

        // --- GUARDAR HTML ORIGINAL ANTES DE LIMPIAR ---
        $enriquecidos = json_decode($datos['_enriquecidos'] ?? '[]', true);
        $datos_json = $datos; // Guarda la versión original (HTML) para el JSON

        // --- LIMPIA ENRIQUECIDOS ---
        $datos = $this->limpiarCamposEnriquecidos($datos, $enriquecidos);

        // --- PLANTILLA DOCX ---
        $templatePath = ROOT_PATH . '/plantillas/plantilla_convenio_marco.docx';
        if (!file_exists($templatePath)) {
            http_response_code(500);
            exit("Plantilla no encontrada en: $templatePath");
        }

        try {
            $tpl = new TemplateProcessor($templatePath);

            // --- REEMPLAZA CAMPOS ---
            $this->reemplazarCamposDocx($tpl, $datos, $enriquecidos);

            // --- LOGO ---
            if ($logoPath) {
                $tpl->setImageValue('LOGO_CONTRAPARTE', [
                    'path'   => $logoPath,
                    'width'  => 80,
                    'height' => 80,
                    'ratio'  => true,
                ]);
            } else {
                $tpl->setValue('LOGO_CONTRAPARTE', '');
            }

            // --- GUARDAR Y ENVIAR DOCX ---
            $timestamp      = time();
            $idGrupo        = $this->generarUUID();
            $nombreDocx     = "convenio_marco_{$timestamp}.docx";
            $documentosDir  = ROOT_PATH . '/writable/documentos_convenios_generados/';
            if (!is_dir($documentosDir)) mkdir($documentosDir, 0755, true);
            $rutaDocx       = $documentosDir . $nombreDocx;
            $tpl->saveAs($rutaDocx);

            // === Guardar en la base de datos ===
            // 1. Guarda en la tabla documentos_generados el archivo DOCX generado (devuelve su id_doc_gen)
            $idDocGen = $model->guardarDocumentoGenerado($idGrupo, $nombreDocx, $rutaDocx, 'WORD');
            // 2. Guarda el archivo JSON con los datos originales del formulario (incluyendo logo y HTML enriquecido de Summernote)
            $rutaJson = $this->guardarFormularioComoJson($datos_json, $nombreDocx, $logoPublicPath);
            // 3. Registra en la tabla datos_convenios la relación entre el documento generado, el tipo de convenio y la ruta al JSON
            $model->guardarDatosConvenio($idDocGen, 'marco', $rutaJson);

            header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
            header("Content-Disposition: attachment; filename={$nombreDocx}");
            readfile($rutaDocx);
            exit;
        } catch (\Throwable $e) {
            http_response_code(500);
            exit("Error al generar documento: " . $e->getMessage());
        }
    }


    // ========= CONVENIO ADENDUM =========

    public function formularioConvenioAdendum()
    {
        $this->view("convenio/convenio_adendum");
    }

    public function generarDesdeFormularioAdendum()
    {
        require_once __DIR__ . '/../models/DocumentosGeneradosModel.php';
        $model = new DocumentosGeneradosModel();
        $datos = $_POST;

        // Cargar base_url desde config para rutas dinámicas
        $config = include ROOT_PATH . '/config/app.php';
        $baseUrl = $config['base_url']; // Ejemplo: '/convenio_UG/public'

        // --- PROCESA LOGO ---
        list($logoPath, $logoPublicPath) = $this->procesarLogo(
            $_FILES['LOGO_CONTRAPARTE'],
            // Ruta física en el servidor (carpeta /public/, NO confundir con el 'public' de la base_url del app.php)
            ROOT_PATH . '/public/uploads_logo_contraparte/',
            // Ruta pública dinámica basada en base_url (usada en el navegador)
            $baseUrl . '/uploads_logo_contraparte/'
        );

        // --- GUARDAR HTML ORIGINAL ANTES DE LIMPIAR ---
        $enriquecidos = json_decode($datos['_enriquecidos'] ?? '[]', true);
        $datos_json = $datos; // Guarda la versión original (HTML) para el JSON

        // --- LIMPIA ENRIQUECIDOS ---
        $datos = $this->limpiarCamposEnriquecidos($datos, $enriquecidos);

        // --- PLANTILLA DOCX ---
        $templatePath = ROOT_PATH . '/plantillas/plantilla_convenio_adendum.docx';
        if (!file_exists($templatePath)) {
            http_response_code(500);
            exit("Plantilla no encontrada en: $templatePath");
        }

        try {
            $tpl = new TemplateProcessor($templatePath);

            // --- REEMPLAZA CAMPOS ---
            $this->reemplazarCamposDocx($tpl, $datos, $enriquecidos);

            // --- LOGO ---
            if ($logoPath) {
                $tpl->setImageValue('LOGO_CONTRAPARTE', [
                    'path'   => $logoPath,
                    'width'  => 80,
                    'height' => 80,
                    'ratio'  => true,
                ]);
            } else {
                $tpl->setValue('LOGO_CONTRAPARTE', '');
            }

            // --- GUARDAR Y ENVIAR DOCX ---
            $timestamp      = time();
            $idGrupo        = $this->generarUUID();
            $nombreDocx     = "convenio_adendum_{$timestamp}.docx";
            $documentosDir  = ROOT_PATH . '/writable/documentos_convenios_generados/';
            if (!is_dir($documentosDir)) mkdir($documentosDir, 0755, true);
            $rutaDocx       = $documentosDir . $nombreDocx;
            $tpl->saveAs($rutaDocx);


            // === Guardar en la base de datos ===
            // 1. Guarda en la tabla documentos_generados el archivo DOCX generado (devuelve su id_doc_gen)
            $idDocGen = $model->guardarDocumentoGenerado($idGrupo, $nombreDocx, $rutaDocx, 'WORD');
            // 2. Guarda el archivo JSON con los datos originales del formulario (incluyendo logo y HTML enriquecido de Summernote)
            $rutaJson = $this->guardarFormularioComoJson($datos_json, $nombreDocx, $logoPublicPath);
            // 3. Registra en la tabla datos_convenios la relación entre el documento generado, el tipo de convenio y la ruta al JSON
            $model->guardarDatosConvenio($idDocGen, 'adendum', $rutaJson);


            header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
            header("Content-Disposition: attachment; filename={$nombreDocx}");
            readfile($rutaDocx);
            exit;
        } catch (\Throwable $e) {
            http_response_code(500);
            exit("Error al generar documento: " . $e->getMessage());
        }
    }


    // ========== FUNCIONES REUTILIZABLES (helpers internos) ==========

    /**
     * Sube y valida el logo.
     * @param array $fileData  $_FILES['LOGO_CONTRAPARTE']
     * @param string $uploadDir Ruta física para guardar
     * @param string $publicDir Prefijo público para JSON/formulario
     * @return array [ruta_fisica, ruta_publica]
     */
    private function procesarLogo($fileData, $uploadDir, $publicDir)
    {
        $logoPath = null;
        $logoPublicPath = '';
        if (!empty($fileData['tmp_name'])) {
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

            $maxSize = 2 * 1024 * 1024; // 2 MB
            if ($fileData['size'] > $maxSize) {
                http_response_code(400);
                exit("El archivo supera el tamaño máximo permitido (2MB).");
            }
            $originalName = basename($fileData['name']);
            $fileName = preg_replace('/[^a-zA-Z0-9_\.-]/', '_', $originalName);
            $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            if (!in_array($ext, ['jpg', 'jpeg', 'png'])) {
                http_response_code(400);
                exit("Formato no permitido: $ext");
            }
            $fileName = time() . '_' . $fileName;
            $destino = $uploadDir . $fileName;

            if (!is_uploaded_file($fileData['tmp_name'])) {
                http_response_code(400);
                exit("Error en la carga del archivo.");
            }
            if (!move_uploaded_file($fileData['tmp_name'], $destino)) {
                http_response_code(500);
                exit("No se pudo mover el archivo subido.");
            }
            $logoPath = $destino;
            $logoPublicPath = $publicDir . $fileName;
        }
        return [$logoPath, $logoPublicPath];
    }

    /**
     * Limpia campos enriquecidos (ej. Summernote/HTML) y los deja listos para DOCX.
     */
    private function limpiarCamposEnriquecidos($datos, $enriquecidos)
    {
        foreach ($enriquecidos as $campo) {
            if (isset($datos[$campo])) {
                $text = $datos[$campo];

                // Reemplazar saltos de línea y párrafos HTML
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
                            if ($temp !== '') {
                                $output .= $temp . "\n\n";
                            }
                        }
                    }
                    $text = trim($output);
                } else {
                    $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                    $text = strip_tags($text);
                    $text = trim($text);
                }
                $datos[$campo] = $text;
            } else {
                $datos[$campo] = '';
            }
        }
        return $datos;
    }

    // CÓDIGO REAL
    // Reemplaza todos los campos normales y enriquecidos en el template DOCX.
    // Si un campo no tiene valor, los campos ${CAMPO} en el DOCX se visualizarán como un espacio en blanco.
    // private function reemplazarCamposDocx($tpl, $datos, $enriquecidos)
    // {
    //     // Campos enriquecidos ya vienen limpios
    //     foreach ($enriquecidos as $campo) {
    //         $tpl->setValue($campo, $datos[$campo] ?? '');
    //     }
    //     // Resto de campos simples
    //     foreach ($datos as $key => $val) {
    //         if ($key !== '_enriquecidos' && !in_array($key, $enriquecidos)) {
    //             $tpl->setValue($key, htmlspecialchars($val, ENT_QUOTES | ENT_XML1, 'UTF-8'));
    //         }
    //     }
    // }

    // FUNCIÓN PARA PRUEBAS:
    // Reemplaza todos los campos normales y enriquecidos en el template DOCX.
    // Si un campo no tiene valor, no se reemplaza (se deja ${CAMPO} visible en el DOCX).
    private function reemplazarCamposDocx($tpl, $datos, $enriquecidos)
    {
        // Campos enriquecidos ya vienen limpios
        foreach ($enriquecidos as $campo) {
            if (isset($datos[$campo]) && trim($datos[$campo]) !== '') {
                $tpl->setValue($campo, $datos[$campo]);
            }
            // Si está vacío, no se reemplaza, se deja el marcador
        }

        // Resto de campos simples
        foreach ($datos as $key => $val) {
            if ($key !== '_enriquecidos' && !in_array($key, $enriquecidos)) {
                if (trim($val) !== '') {
                    $tpl->setValue($key, htmlspecialchars($val, ENT_QUOTES | ENT_XML1, 'UTF-8'));
                }
                // Si está vacío, no se reemplaza, se deja el marcador
            }
        }
    }


    /**
     * Guarda el formulario como JSON (siempre usando la ruta pública del logo).
     */
    private function guardarFormularioComoJson($datos, $nombreBase, $logoPublicPath = null)
    {
        unset($datos['_enriquecidos'], $datos['formato']);
        if ($logoPublicPath !== null && $logoPublicPath !== '') {
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
     * Genera un UUID v4.
     */
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
}//FIN
