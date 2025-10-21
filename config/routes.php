<?php
$controller = $_GET['c'] ?? 'home';
$method     = $_GET['m'] ?? 'index';

$controllerClass = ucfirst($controller) . 'Controller';
$controllerFile  = ROOT_PATH . "/app/controllers/{$controllerClass}.php";

if (file_exists($controllerFile)) {
    require_once $controllerFile;
    $obj = new $controllerClass();

    if (method_exists($obj, $method)) {
        $obj->$method();
    } else {
        echo "Método '$method' no encontrado.";
    }
} else {
    echo "Controlador '$controller' no encontrado.";
}
