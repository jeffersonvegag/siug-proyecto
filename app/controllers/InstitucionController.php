<?php
require_once ROOT_PATH . '/app/models/InstitucionService.php';

class InstitucionController
{
    private $model;
    private $baseUrl;

    public function __construct()
    {
        $this->baseUrl = rtrim((require ROOT_PATH . '/config/app.php')['base_url'], '/');
        require_once ROOT_PATH . '/app/models/InstitucionService.php';
        $this->model = new InstitucionService();
    }

    public function getCarreras()
    {
        header('Content-Type: application/json'); // Indicar que la respuesta es JSON
        $idFacultad = $_GET['facultad'] ?? null; // O $_POST si usas POST

        if (!$idFacultad) {
            echo json_encode(["success" => false, "message" => "ID de Facultad no proporcionado."]);
            return;
        }

        $institucionService = new InstitucionService();
        $token = $institucionService->GetToken(); // Obtén tu token
        $username = $_SESSION['user']['username']; // O el usuario real que uses

        // Llama a tu InstitucionService para obtener las carreras
        $carrerasData = $institucionService->GetCarrera($username, $idFacultad, $token);

        // Asegúrate de que $carrerasData tenga el formato esperado (ej. un array de objetos/arrays de carreras)
        // Puedes necesitar ajustar esto según cómo GetCarrera devuelva los datos
        if (isset($carrerasData['dtResultado']) && is_array($carrerasData['dtResultado'])) {
            echo json_encode(["success" => true, "carreras" => $carrerasData['dtResultado']]);
        } else {
            echo json_encode(["success" => false, "message" => "No se encontraron carreras o error en la API.", "detail" => $carrerasData]);
        }
        exit; // Terminar la ejecución para evitar renderizar la vista completa
    }

    public function getDocentes()
    {
        header('Content-Type: application/json'); // Indicar que la respuesta es JSON
        $idFacultad = $_GET['facultad'] ?? null; // O $_POST si usas POST
        $idCarrera = $_GET['carrera'] ?? null; // O $_POST si usas POST

        if (!$idFacultad) {
            echo json_encode(["success" => false, "message" => "ID de Facultad no proporcionado."]);
            return;
        }

        $institucionService = new InstitucionService();
        $token = $institucionService->GetToken(); // Obtén tu token
        $username = $_SESSION['user']['username']; 

        // Llama a tu InstitucionService para obtener las carreras
        $docentesData = $institucionService->GetDocentes($username,$idFacultad, $idCarrera, $token);

        // Asegúrate de que $carrerasData tenga el formato esperado (ej. un array de objetos/arrays de carreras)
        // Puedes necesitar ajustar esto según cómo GetCarrera devuelva los datos
        if (isset($docentesData['dtResultado']) && is_array($docentesData['dtResultado'])) {
            echo json_encode(["success" => true, "docentes" => $docentesData['dtResultado']]);
        } else {
            echo json_encode(["success" => false, "message" => "No se encontraron carreras o error en la API.", "detail" => $docentesData]);
        }
        exit; // Terminar la ejecución para evitar renderizar la vista completa
    }
}
