<?php

require_once ROOT_PATH . '/app/models/PropuestaModel.php';
require_once ROOT_PATH . '/app/models/InstitucionService.php';

class PropuestasController
{
    private $model;
    private $baseUrl;

    public function __construct()
    {
        $this->baseUrl = rtrim((require ROOT_PATH . '/config/app.php')['base_url'], '/');
        require_once ROOT_PATH . '/app/models/PropuestaModel.php';
        $this->model = new PropuestaModel();
    }


    // Muestra todas las propuestas
    public function index()
    {
        require_once __DIR__ . '/../models/PropuestaModel.php';
        $model = new PropuestaModel();
       // $propuestas = $model->obtenerListadoCompleto();

        // Carga de base_url para enlaces
        $config = require ROOT_PATH . '/config/app.php';
        $baseUrl = $config['base_url'];

        include ROOT_PATH . '/app/views/propuesta_convenio/index.php';
    }

    // Mostrar formulario
    public function crear()
    {
        $baseUrl = $this->baseUrl;
        $institucionService = new InstitucionService();
        $token = $institucionService->GetToken();
        $formData = $institucionService->getCrearFormData();
        $facultadData = $institucionService->GetFacultad($_SESSION['user']['username'], $token);
        extract($formData);
        extract($facultadData);
        require ROOT_PATH . '/app/views/propuesta_convenio/crear.php';
    }

    // Guardar propuesta con solo Titulo
    public function guardar()
    {
        $titulo = $_POST['Titulo'] ?? null;
    // $this->model->insertar($titulo);

        // Redirigir al index
        header("Location: " . $this->baseUrl . "/index.php?c=Propuestas&m=index");
        exit;
    }

    // Muestra el formulario con datos para editar
    public function editar()
    {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            exit("ID no proporcionado.");
        }

      //  $propuesta = $this->model->obtenerPorId($id);
        require ROOT_PATH . '/app/views/propuesta_convenio/editar.php';
    }

    // Actualiza una propuesta existente
    public function actualizar()
    {
        $id     = $_POST['id'] ?? null;
        $titulo = $_POST['titulo'] ?? null;

        if (!$id || !$titulo) {
            exit("Datos incompletos.");
        }

       // $this->model->actualizar($id, $titulo);
        header("Location: index.php?c=Propuestas&m=index");
        exit;
    }

    
}
