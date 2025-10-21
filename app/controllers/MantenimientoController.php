<?php

class MantenimientoController extends Controller
{
    private $rutaDePlantillas;

    public function __construct()
    {
        // Define la ubicación de tus plantillas base
        $this->rutaDePlantillas = ROOT_PATH . '/plantillas/';
    }

    public function index()
    {
        $this->view("mantenimiento/index");
    }

    /**
     * Permite al usuario descargar la plantilla actual.
     */
    public function descargar()
    {
        if (!isset($_GET['plantilla'])) {
            die("Nombre de plantilla no especificado.");
        }

        $nombreArchivo = basename($_GET['plantilla']);
        $rutaCompleta = $this->rutaDePlantillas . $nombreArchivo;

        if (file_exists($rutaCompleta)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
            header('Content-Disposition: attachment; filename="' . $nombreArchivo . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($rutaCompleta));
            readfile($rutaCompleta);
            exit;
        } else {
            die("Error: La plantilla no existe en el servidor.");
        }
    }

    /**
     * Recibe un archivo .docx subido y reemplaza la plantilla existente.
     */
    public function subir()
    {
        // 1. Validaciones de seguridad básicas
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_FILES['plantilla_archivo']) || !isset($_POST['plantilla_nombre'])) {
            $this->redirigirConError("Solicitud no válida.");
        }

        $nombreArchivo = basename($_POST['plantilla_nombre']);
        $archivoSubido = $_FILES['plantilla_archivo'];

        // 2. Validar errores de subida
        if ($archivoSubido['error'] !== UPLOAD_ERR_OK) {
            $this->redirigirConError("Error durante la subida del archivo.", $nombreArchivo);
        }

        // 3. Validar tipo de archivo (extensión)
        $extension = strtolower(pathinfo($archivoSubido['name'], PATHINFO_EXTENSION));
        if ($extension !== 'docx') {
            $this->redirigirConError("Formato de archivo no válido. Solo se permiten .docx.", $nombreArchivo);
        }

        // 4. Mover el archivo subido para reemplazar el antiguo
        $rutaDestino = $this->rutaDePlantillas . $nombreArchivo;
        if (move_uploaded_file($archivoSubido['tmp_name'], $rutaDestino)) {
            // Éxito: Redirigir a la página de mantenimiento con mensaje de éxito
            header('Location: ?c=mantenimiento&m=index&status=success&plantilla=' . urlencode($nombreArchivo));
            exit;
        } else {
            // Falla
            $this->redirigirConError("No se pudo guardar la nueva plantilla en el servidor.", $nombreArchivo);
        }
    }

    /**
     * Función auxiliar para redirigir con un mensaje de error.
     */
    private function redirigirConError($mensaje, $plantilla = 'desconocida')
    {
        header('Location: ?c=mantenimiento&m=index&status=error&plantilla=' . urlencode($plantilla) . '&error=' . urlencode($mensaje));
        exit;
    }
}