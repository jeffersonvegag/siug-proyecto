<?php

require_once __DIR__ . "/../models/ApiClient.php";

class MenuController
{
    private $apiClient;
    private $instServ;

    public function __construct()
    {
        $this->apiClient = new ApiClient();
    }

    /**
     * Obtiene el menú completo en formato HTML con clases de Metronic,
     * mostrando solo los módulos a los que el usuario tiene acceso.
     * @return string El HTML del menú.
     */
    public function ObtenerMenuHtml()
    {
        // Iniciamos con el link estático de "Dashboard" con estilo Metronic.
        $menuHtml = '
        <div class="menu-item">
            <a class="menu-link" href="?c=home&m=index">
                <span class="menu-icon">
                    <i class="ki-duotone ki-element-11 fs-2">
                        <span class="path1"></span>
                        <span class="path2"></span>
                        <span class="path3"></span>
                        <span class="path4"></span>
                    </i>
                </span>
                <span class="menu-title text-white">Dashboard</span>
            </a>
        </div>';

        // Obtener el ID del usuario y el ID del sistema desde la sesión.
        // Asegúrate de que estas variables de sesión existan cuando el usuario inicie sesión.
        $usuarioId = $_SESSION["user"]["username"] ?? ''; // Usar un valor por defecto si no existe
        $token = $_SESSION["user"]["token"] ?? '';

        // Arrays para almacenar los datos de los módulos obtenidos
        $modulosDisponibles = [];

        // --- Lógica para obtener módulos del Sistema 35 (Sistema General) ---
        $datosSistemaGeneral = [
            'usuarioId' => $usuarioId,
            'sistemaId' => "35", // ID del sistema general
            'moduloId'  => ""
        ];
        $modulosSistemaGeneral = $this->apiClient->LlamadaApi($this->apiClient->apiGetMenu, $datosSistemaGeneral, $token);
        if (isset($modulosSistemaGeneral['resultado']) && is_array($modulosSistemaGeneral['resultado'])) {
            $modulosDisponibles = array_merge($modulosDisponibles, $modulosSistemaGeneral['resultado']);
        }

        // --- Lógica para obtener módulos del Sistema 36 (Sistema de Convenios) ---
        // Se llama a este API si el usuario tiene acceso a este sistema,
        // o si queremos comprobar ambos si aplica.
        // Aquí puedes agregar una condición si el usuario solo debería ver un sistema u otro,
        // o si siempre se consultan ambos si tiene permisos.
        // Para este ejemplo, lo consultamos independientemente del sistemaIdActual,
        // asumiendo que el usuario puede tener permisos en ambos y queremos mostrarlos.
        $datosSistemaConvenio = [
            'usuarioId' => $usuarioId,
            'sistemaId' => "36", // ID del sistema de convenios
            'moduloId'  => ""
        ];
        $modulosSistemaConvenio = $this->apiClient->LlamadaApi($this->apiClient->apiGetMenu, $datosSistemaConvenio, $token);
        if (isset($modulosSistemaConvenio['resultado']) && is_array($modulosSistemaConvenio['resultado'])) {
            $modulosDisponibles = array_merge($modulosDisponibles, $modulosSistemaConvenio['resultado']);
        }

        // Procesar todos los módulos disponibles (de ambos sistemas si se recuperaron)
        if (!empty($modulosDisponibles)) {
            foreach ($modulosDisponibles as $valor) {
                $nombreModulo = htmlspecialchars($valor["nombre"]);
                $icono = htmlspecialchars($valor["icono"]); // Asumimos que el icono de la API es una clase de FontAwesome o similar
                $moduloId = (string) $valor["moduloId"]; // Asegurarse de que sea string para la API
                $sistemaModulo = (string) $valor["sistemaId"]; // Obtener el sistemaId del módulo actual

                // Construye el elemento principal del acordeón de Metronic
                $menuHtml .= '
                <div class="menu-item menu-accordion" data-kt-menu-trigger="click">
                    <span class="menu-link">
                        <span class="menu-icon">
                            <i class="bi bi-house me-2"></i> </span>
                        <span class="menu-title text-white">' . $nombreModulo . '</span>
                        <span class="menu-arrow "></span>
                        
                    </span>
                    <div class="menu-sub menu-sub-accordion ">';

                // Petición para obtener los sub-items del módulo actual
                // Es importante pasar el sistemaId correcto para cada módulo individual
                $datosDetalle = [
                    'usuarioId' => $usuarioId,
                    'sistemaId' => $sistemaModulo, // Usar el sistemaId del módulo actual
                    'moduloId'  => $moduloId,
                ];
                $menuAPI = $this->apiClient->LlamadaApi($this->apiClient->apiGetDetalleMenu, $datosDetalle, $token);
                var_dump($menuAPI);
                
                if (isset($menuAPI['resultado']) && is_array($menuAPI['resultado'])) {
                    foreach ($menuAPI['resultado'] as $valorMenu) {
                        $nombreSubMenu = htmlspecialchars($valorMenu["nombre"]);
                        $ruta = htmlspecialchars($valorMenu["rutaForma"]);
                        $href = $ruta;

                        // Construye cada sub-item del menú
                        $menuHtml .= '
                        <div class="menu-item">
                            <a class="menu-link" href="' . $href . '">
                                <span class="menu-bullet ">
                                    <span class="bullet bullet-dot "></span>
                                </span>
                                <span class="menu-title text-white ">' . $nombreSubMenu . '</span>
                            </a>
                        </div>';
                    }
                }

                $menuHtml .= '
                    </div>
                </div>';
            }
        } else {
            // Mensaje si no se encuentran módulos para el usuario
            $menuHtml .= '
            <div class="menu-item">
                <span class="menu-link">
                    <span class="menu-title text-white">No tienes módulos asignados.</span>
                </span>
            </div>';
        }

        return $menuHtml;
    }
}