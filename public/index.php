<?php
// 1. INICIO DE SESIÓN Y CONFIGURACIÓN INICIAL
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

define('ROOT_PATH', dirname(__DIR__));
require_once ROOT_PATH . '/vendor/autoload.php';
require_once ROOT_PATH . '/config/constants.php'; // Cargar constantes aquí para usarlas en el redirect

// 2. BLOQUE DE SEGURIDAD (EL "GUARDIA")
// --------------------------------------------------

// Define las únicas rutas que se pueden visitar sin iniciar sesión.
$rutas_publicas = [
    'auth/index', // La página del formulario de login.
    'auth/login'  // La acción que procesa el envío del formulario.
];

// Obtiene la ruta que el usuario intenta visitar.
$controlador_actual = $_GET['c'] ?? 'auth'; // Si no hay controlador, por defecto es 'auth'.
$metodo_actual = $_GET['m'] ?? 'index';   // Si no hay método, por defecto es 'index'.
$ruta_actual = $controlador_actual . '/' . $metodo_actual;

// Comprueba si la ruta actual NO está en la lista de rutas públicas.
if (!in_array($ruta_actual, $rutas_publicas)) {
    
    // Si la ruta es protegida, comprueba si el usuario NO ha iniciado sesión.
    if (!isset($_SESSION['user'])) {
        
        // Si no ha iniciado sesión, lo redirige al login y detiene todo.
        header("Location: " . WEB_URL . "/?c=auth&m=index");
        exit();
    }
}
// --------------------------------------------------
// Si el código llega hasta aquí, significa que el usuario tiene permiso para continuar.


// 3. CARGA DEL NÚCLEO DE LA APLICACIÓN
require_once ROOT_PATH . '/core/Controller.php';
require_once ROOT_PATH . '/core/Model.php';
require_once ROOT_PATH . '/core/View.php';

// 4. CARGA DEL ENRUTADOR QUE EJECUTA EL CONTROLADOR
require_once ROOT_PATH . '/config/routes.php';