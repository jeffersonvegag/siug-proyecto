<?php

// CAMBIO: Ya no se necesita el GeneradorModel, lo puedes eliminar o comentar.
// require_once ROOT_PATH . '/app/models/GeneradorModel.php';
require_once ROOT_PATH . '/app/models/DocumentosGeneradosModel.php'; // Este se mantiene para el historial

class GeneradorController extends Controller
{
    // CAMBIO: Eliminamos la propiedad del modelo del generador.
    // private $generadorModel;
    private $documentosGeneradosModel;

    public function __construct()
    {
        // CAMBIO: Ya no se instancia GeneradorModel.
        // $this->generadorModel = new GeneradorModel();
        $this->documentosGeneradosModel = new DocumentosGeneradosModel();
    }

    /**
     * Muestra una página para elegir qué tipo de convenio generar.
     */
    public function index()
    {
        $this->view('generador/index');
    }

    /**
     * Muestra el formulario específico para el Convenio Marco.
     */
    public function formularioMarco()
    {
        $this->view('generador/formulario_marco');
    }

    /**
     * Proceso principal: Recibe los datos del formulario, los combina con la plantilla
     * de la base de datos y genera el documento .docx para descargar.
     */
    public function generar()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            die('Acceso no permitido.');
        }

        // 1. Obtenemos el nombre de la plantilla base desde el formulario
        $nombrePlantillaBase = $_POST['template_name'] ?? 'plantilla_desconocida.docx';

        // --- INICIO DE LA LÓGICA MODIFICADA ---
        $plantillaHTML = null;
        $nombrePlantillaHtml = str_replace('.docx', '.html', basename($nombrePlantillaBase));
        $rutaEditadaHtml = ROOT_PATH . '/writable/plantillas_html/' . $nombrePlantillaHtml;
        
        // 2. Buscamos la plantilla HTML editada.
        if (file_exists($rutaEditadaHtml)) {
            $plantillaHTML = file_get_contents($rutaEditadaHtml);
        }
        
        // 3. Si no la encuentra, mostramos un error.
        if ($plantillaHTML === null) {
            die("Error: No se encontró una plantilla editada para '{$nombrePlantillaBase}'. Por favor, guarde una versión en el módulo de Mantenimiento primero.");
        }
        // --- FIN DE LA LÓGICA MODIFICADA ---

        $camposHtml = [];
        if (isset($_POST['_enriquecidos'])) {
            $camposHtml = json_decode($_POST['_enriquecidos'], true);
        }

        $datosFormulario = $_POST;
        $htmlFinal = $this->reemplazarMarcadores($plantillaHTML, $datosFormulario, $camposHtml);

        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        
        // ✅ LÍNEA CORREGIDA ✅
        $section = $phpWord->addSection(); 
        
        \PhpOffice\PhpWord\Shared\Html::addHtml($section, $htmlFinal, false, false);

        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        header('Content-Disposition: attachment;filename="' . $nombrePlantillaBase . '"');
        header('Cache-Control: max-age=0');

        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save('php://output');
        exit;
    }

    /**
     * Función auxiliar para reemplazar marcadores.
     * (Este método no necesita cambios)
     */
    private function reemplazarMarcadores($html, $datos, $camposHtml = [])
    {
        foreach ($datos as $clave => $valor) {
            $marcador = '{{' . $clave . '}}';

            if (in_array($clave, $camposHtml)) {
                $valorProcesado = $valor;
            } else {
                $valorProcesado = is_array($valor) ? implode(', ', $valor) : nl2br(htmlspecialchars($valor));
            }
            
            $html = str_replace($marcador, $valorProcesado, $html);
        }
        return $html;
    }
}