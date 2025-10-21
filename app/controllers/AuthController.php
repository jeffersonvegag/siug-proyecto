<?php

require_once "../app/models/InicioSesionModel.php";
require_once "../app/controllers/MenuController.php";
require_once "../config/constants.php";

class AuthController extends Controller
{
    public function index()
    {
        // Esto no cambia, sigue mostrando la vista de login.
        require_once ROOT_PATH . '/app/views/login.php';
    }

    public function login()
    {
        // 1. Siempre establece la cabecera para responder con JSON.
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $correo = $_POST['correo'];
            $password = $_POST['password'];

            $login = new InicioSesionModel();
            $usuario = $login->loginConToken($correo, $password);

            if (isset($usuario['respuesta']) && $usuario['respuesta'] == "OK") {
                // --- La lógica de sesión se mantiene igual ---
                $_SESSION['user'] = $usuario;
                $_SESSION['user']['roles'] = [$usuario['perfil']];
                
                $menu = new MenuController();
                $_SESSION['menu'] = $menu->ObtenerMenuHtml();

                // 2. En lugar de redirigir, enviamos una respuesta JSON de éxito.
                echo json_encode([
                    'success' => true,
                    'redirectUrl' => WEB_URL . "/?c=home&m=index"
                ]);
                exit;

            } else {
                // 3. En lugar de redirigir con error, enviamos un JSON de error.
                http_response_code(401); // Código de "No Autorizado"
                echo json_encode([
                    'success' => false,
                    'message' => 'Credenciales incorrectas. Por favor, intente de nuevo.'
                ]);
                exit;
            }
        } else {
            // Si no es POST, se devuelve un error.
            http_response_code(405); // Método no permitido
            echo json_encode([
                'success' => false,
                'message' => 'Método no permitido.'
            ]);
            exit;
        }
    }

    public function logout()
    {
        session_destroy();
        header("Location: " . WEB_URL . "/?c=auth&m=index");
        exit;
    }
}