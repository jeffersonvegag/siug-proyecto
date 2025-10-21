<?php

class ConveniosSubidosController
{
    private $baseUrl;

    public function __construct()
    {
        $config = require ROOT_PATH . '/config/app.php';
        $this->baseUrl = $config['base_url'];
    }

    public function index()
    {
        require_once __DIR__ . '/../models/ConveniosSubidosModel.php';
        $model = new ConveniosSubidosModel();
        $archivos = $model->obtenerTodos();
        $this->view('convenios_subidos/index', [
            'archivos' => $archivos,
            'baseUrl' => $this->baseUrl
        ]);
    }

    public function subir()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once __DIR__ . '/../models/ConveniosSubidosModel.php';
            $model = new ConveniosSubidosModel();

            $descripcion = $_POST['descripcion'] ?? '';
            $archivo = $_FILES['archivo_convenio'] ?? null;

            if ($archivo && $archivo['error'] === UPLOAD_ERR_OK) {
                $extensionesPermitidas = ['pdf', 'doc', 'docx'];
                $original = basename($archivo['name']);
                $ext = strtolower(pathinfo($original, PATHINFO_EXTENSION));

                if (!in_array($ext, $extensionesPermitidas)) {
                    $error = "Solo se permiten archivos PDF o Word (.doc, .docx)";
                } elseif ($archivo['size'] > 5 * 1024 * 1024) {
                    $error = "El archivo no puede superar los 5 MB";
                } else {
                    $dir = ROOT_PATH . '/writable/convenios_subidos/';
                    if (!is_dir($dir)) mkdir($dir, 0755, true);

                    $nombreUnico = time() . '_' . uniqid() . '.' . $ext;
                    $ruta = $dir . $nombreUnico;
                    if (!move_uploaded_file($archivo['tmp_name'], $ruta)) {
                        $error = "No se pudo guardar el archivo en el servidor.";
                    } else {
                        $tipo = ($ext === 'pdf') ? 'PDF' : 'WORD';
                        $model->guardarArchivo($original, $nombreUnico, $tipo, $ruta, $descripcion);
                        header("Location: {$this->baseUrl}/index.php?c=ConveniosSubidos&m=index");
                        exit;
                    }
                }
            } else {
                $error = "Debe seleccionar un archivo para subir.";
            }

            // Si hubo error, mostramos el formulario otra vez
            $this->view('convenios_subidos/subir', [
                'error' => $error ?? null,
                'baseUrl' => $this->baseUrl
            ]);
        } else {
            // GET: mostrar formulario
            $this->view('convenios_subidos/subir', [
                'baseUrl' => $this->baseUrl
            ]);
        }
    }

    public function descargar()
    {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header("Location: {$this->baseUrl}/index.php?c=ConveniosSubidos&m=index");
            exit;
        }

        require_once __DIR__ . '/../models/ConveniosSubidosModel.php';
        $model = new ConveniosSubidosModel();
        $doc = $model->obtenerPorId($id);

        if (!$doc || !file_exists($doc['RutaArchivoConSub'])) {
            header("Location: {$this->baseUrl}/index.php?c=ConveniosSubidos&m=index");
            exit;
        }

        $mime = 'application/octet-stream';
        $ext = strtolower(pathinfo($doc['NombreGuardadoConSub'], PATHINFO_EXTENSION));
        if ($ext === 'pdf') $mime = 'application/pdf';
        if ($ext === 'doc' || $ext === 'docx') $mime = 'application/vnd.openxmlformats-officedocument.wordprocessingml.document';

        header("Content-Type: {$mime}");
        header("Content-Disposition: attachment; filename=\"{$doc['NombreOriginalConSub']}\"");
        readfile($doc['RutaArchivoConSub']);
        exit;
    }

    public function eliminar()
    {
        $id = $_GET['id'] ?? null;
        if ($id) {
            require_once __DIR__ . '/../models/ConveniosSubidosModel.php';
            $model = new ConveniosSubidosModel();
            $doc = $model->obtenerPorId($id);

            if ($doc && file_exists($doc['RutaArchivoConSub'])) {
                unlink($doc['RutaArchivoConSub']);
            }
            $model->eliminarPorId($id);
        }
        header("Location: {$this->baseUrl}/index.php?c=ConveniosSubidos&m=index");
        exit;
    }

    public function preview()
    {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header("Location: {$this->baseUrl}/index.php?c=ConveniosSubidos&m=index");
            exit;
        }
        require_once __DIR__ . '/../models/ConveniosSubidosModel.php';
        $model = new ConveniosSubidosModel();
        $doc = $model->obtenerPorId($id);
        if (!$doc || !file_exists($doc['RutaArchivoConSub'])) {
            header("Location: {$this->baseUrl}/index.php?c=ConveniosSubidos&m=index");
            exit;
        }

        $ext = strtolower(pathinfo($doc['NombreGuardadoConSub'], PATHINFO_EXTENSION));
        if ($ext === 'pdf') {
            // PDF: muestra inline en navegador
            header('Content-Type: application/pdf');
            header('Content-Disposition: inline; filename="' . $doc['NombreOriginalConSub'] . '"');
            readfile($doc['RutaArchivoConSub']);
            exit;
        } elseif ($ext === 'doc' || $ext === 'docx') {
            // Word: convierte al vuelo a PDF y muestra inline
            try {
                require_once ROOT_PATH . '/vendor/autoload.php';
                $phpWord = \PhpOffice\PhpWord\IOFactory::load($doc['RutaArchivoConSub']);
                $xmlWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'HTML');
                ob_start();
                $xmlWriter->save('php://output');
                $html = ob_get_clean();

                // Si tu HTML sale vacío, puedes agregar un mensaje alternativo:
                if (empty(trim($html))) {
                    throw new Exception("No se pudo generar vista previa para este Word.");
                }

                $dompdf = new \Dompdf\Dompdf(['isHtml5ParserEnabled' => true]);
                $dompdf->loadHtml($html);
                $dompdf->setPaper('A4', 'portrait');
                $dompdf->render();

                header('Content-Type: application/pdf');
                header('Content-Disposition: inline; filename="preview.pdf"');
                echo $dompdf->output();
                exit;
            } catch (\Throwable $e) {
                echo "<h2>No se pudo mostrar la vista previa de este archivo Word.</h2>";
                echo "<small>" . htmlspecialchars($e->getMessage()) . "</small>";
                exit;
            }
        } else {
            echo "<h2>Vista previa no disponible para este tipo de archivo.</h2>";
            exit;
        }
    }

    protected function view($vista, $data = [])
    {
        extract($data);
        include ROOT_PATH . "/app/views/{$vista}.php";
    }


    // app\views\convenios_subidos\editar.php
    public function editar()
    {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header("Location: {$this->baseUrl}/index.php?c=ConveniosSubidos&m=index");
            exit;
        }

        require_once __DIR__ . '/../models/ConveniosSubidosModel.php';
        $model = new ConveniosSubidosModel();
        $archivo = $model->obtenerPorId($id);

        if (!$archivo) {
            header("Location: {$this->baseUrl}/index.php?c=ConveniosSubidos&m=index");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $descripcion = $_POST['descripcion'] ?? '';
            $archivoNuevo = $_FILES['archivo_convenio'] ?? null;
            $nombreOriginal = $archivo['NombreOriginalConSub'];
            $nombreGuardado = $archivo['NombreGuardadoConSub'];
            $rutaArchivo = $archivo['RutaArchivoConSub'];
            $tipo = $archivo['TipoConSub'];

            if ($archivoNuevo && $archivoNuevo['error'] === UPLOAD_ERR_OK) {
                $extensionesPermitidas = ['pdf', 'doc', 'docx'];
                $ext = strtolower(pathinfo($archivoNuevo['name'], PATHINFO_EXTENSION));
                if (!in_array($ext, $extensionesPermitidas)) {
                    $error = "Solo se permiten archivos PDF o Word (.doc, .docx)";
                } elseif ($archivoNuevo['size'] > 5 * 1024 * 1024) {
                    $error = "El archivo no puede superar los 5 MB";
                } else {
                    $dir = ROOT_PATH . '/writable/convenios_subidos/';
                    if (!is_dir($dir)) mkdir($dir, 0755, true);

                    $nuevoNombre = time() . '_' . uniqid() . '.' . $ext;
                    $nuevaRuta = $dir . $nuevoNombre;

                    if (!move_uploaded_file($archivoNuevo['tmp_name'], $nuevaRuta)) {
                        $error = "No se pudo guardar el archivo nuevo en el servidor.";
                    } else {
                        // eliminar el archivo anterior
                        if (file_exists($rutaArchivo)) {
                            unlink($rutaArchivo);
                        }
                        $nombreOriginal = basename($archivoNuevo['name']);
                        $nombreGuardado = $nuevoNombre;
                        $rutaArchivo = $nuevaRuta;
                        $tipo = ($ext === 'pdf') ? 'PDF' : 'WORD';
                    }
                }
            }

            if (empty($error)) {
                $model->actualizarArchivo(
                    $id,
                    $nombreOriginal,
                    $nombreGuardado,
                    $tipo,
                    $rutaArchivo,
                    $descripcion
                );
                header("Location: {$this->baseUrl}/index.php?c=ConveniosSubidos&m=index");
                exit;
            }

            // si hubo error, vuelve a cargar la vista
            $this->view('convenios_subidos/editar', [
                'archivo' => $archivo,
                'error'   => $error ?? null,
                'baseUrl' => $this->baseUrl
            ]);
        } else {
            // GET: mostrar formulario
            $this->view('convenios_subidos/editar', [
                'archivo' => $archivo,
                'baseUrl' => $this->baseUrl
            ]);
        }
    }
}//FIN
