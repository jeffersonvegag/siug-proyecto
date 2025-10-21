<?php

// Asegúrate de que el autoload de Composer se cargue
require_once ROOT_PATH . '/vendor/autoload.php';

// Incluimos el archivo del modelo para que la clase ProyectosModel esté disponible.
require_once ROOT_PATH . '/app/models/ProyectosModel.php';

use Dompdf\Dompdf;
use Dompdf\Options;

class ProyectosController
{

    private $proyectosModel;

    public function __construct()
    {
        // Asegúrate de que tu modelo se esté instanciando
        $this->proyectosModel = new ProyectosModel();
    }

    public function index()
    {
        // 1. Obtener el término de búsqueda de la URL de forma segura.
        $busqueda = isset($_GET['busqueda']) ? trim($_GET['busqueda']) : '';
        $proyectos = []; // Inicializamos la variable para evitar errores.

        // 2. Si hay un término de búsqueda, usamos la función obtenerTodos
        // para buscar proyectos por su nombre o título.
        if (!empty($busqueda)) {
            $proyectos = $this->proyectosModel->obtenerTodos($busqueda);
        } else {
            // Si no hay búsqueda, mostramos todos los proyectos.
            $proyectos = $this->proyectosModel->obtenerTodos();
        }

        // 3. Ahora, en lugar de usar obtenerProyectoCompleto directamente,
        // puedes mostrar la lista de proyectos encontrados.
        // Si la intención es mostrar el detalle de un solo proyecto,
        // debes tomar el ID del primer resultado y usarlo.
        // Por ejemplo:

        // 4. Cargar la vista y pasarle los datos.
        // Puedes pasar tanto la lista de proyectos como el proyecto completo si lo obtuviste.
        require_once ROOT_PATH . '/app/views/formularios/index.php';
    }

    /**
     * Genera y descarga el PDF completo del proyecto.
     */
    public function generarPdf()
    {
        // Validamos el ID del proyecto.
        $idProyecto = $_GET['id'] ?? null;
        if (!$idProyecto || !filter_var($idProyecto, FILTER_VALIDATE_INT)) {
            header("HTTP/1.1 400 Bad Request");
            die("Error: ID de proyecto no válido o no proporcionado.");
        }

        // Liberar el bloqueo de sesión para permitir la navegación.
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_write_close();
        }

        // Configurar el manejo de cancelación por parte del usuario.
        ignore_user_abort(false);

        // Verificación #1: Antes de consumir recursos.
        if (connection_aborted()) {
            exit();
        }

        // Cargar el modelo del proyecto.
        require_once ROOT_PATH . '/app/models/ProyectosModel.php';
        $proyectoCompleto = $this->proyectosModel->obtenerProyectoCompleto($idProyecto);

        if (!$proyectoCompleto) {
            header("HTTP/1.1 404 Not Found");
            die("Error: No se encontró el proyecto con el ID proporcionado.");
        }

        // Verificación #2: Después de la consulta a la BD.
        if (connection_aborted()) {
            exit();
        }

        try {
            // Preparar DomPDF.
            $options = new Options();
            $options->set('isRemoteEnabled', TRUE);

            // --- LÍNEA CLAVE AÑADIDA ---
            // Se establece el directorio raíz del proyecto como un entorno seguro
            // para que DomPDF pueda acceder a archivos locales como tu logo.
            $options->set('chroot', ROOT_PATH);

            $dompdf = new Dompdf($options);

            // Cargar la plantilla HTML en una variable.
            ob_start();
            require_once ROOT_PATH . '/app/views/pdf/plantilla_proyecto.php';
            $html = ob_get_clean();

            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');

            // Verificación #3: Justo antes del renderizado.
            if (connection_aborted()) {
                exit();
            }

            // Renderizar el HTML a PDF.
            $dompdf->render();

            // Preparar el nombre del archivo para la descarga.
            $titulo = $proyectoCompleto->datos_generales->Titulo ?? 'Proyecto_Sin_Titulo';
            $nombreArchivoSeguro = preg_replace('/[^A-Za-z0-9_.-]/', '_', $titulo);
            $nombreArchivo = "Proyecto-" . $nombreArchivoSeguro . ".pdf";

            // Enviar el PDF al navegador.
            $dompdf->stream($nombreArchivo, ["Attachment" => true]);
        } catch (Exception $e) {
            error_log('Error al generar PDF: ' . $e->getMessage());
            header("HTTP/1.1 500 Internal Server Error");
            die("Ocurrió un error al generar el PDF.");
        }

        exit();
    }
}
