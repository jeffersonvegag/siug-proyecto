<?php require_once ROOT_PATH . '/config/constants.php'; ?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Sistema Integrado</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="<?php echo WEB_URL; ?>/assets/metronic/plugins/global/plugins.bundle.css">
    <link rel="stylesheet" href="<?php echo WEB_URL; ?>/assets/metronic/css/style.bundle.css">
    <link rel="stylesheet" href="<?php echo WEB_URL; ?>/assets/css/styles.css">

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <script src="<?php echo WEB_URL; ?>/assets/metronic/plugins/global/plugins.bundle.js"></script>
    <script src="<?php echo WEB_URL; ?>/assets/metronic/js/scripts.bundle.js"></script>

    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>
    <div class="navbar p-6 navbar-expand-lg navbar-light bg-white shadow-sm fixed-top">
        <div class="container-fluid">
            <button id="toggleSidebar" class="navbar-toggler me-3" data-kt-drawer-toggle="true">
                <i class="fas fa-bars fs-2"></i>
            </button>

            <div class="d-flex align-items-center">
                <span class="me-3 text-dark fs-2rounded-3">
                    Bienvenido(a),
                    <?php
                    // Imprime el nombre y el apellido si existen en la sesión
                    if (isset($_SESSION['user']['nombre']) && isset($_SESSION['user']['apellidos'])) {
                        echo htmlspecialchars($_SESSION['user']['nombre']) . ' ' . htmlspecialchars($_SESSION['user']['apellidos']);
                    }
                    ?>
                </span>
            </div>

            <a id="logoutBtn" class="btn btn-danger btn-sm ms-auto" href="?c=auth&m=logout">Cerrar Sesión</a>
        </div>
    </div>

    <div class="d-flex pt-20">

        <div id="kt_sidebar" class="aside position-fixed kt-drawer kt-drawer-start bg-dark text-white mt-15 mt-lg-0 pt-10 pt-lg-0"
            data-kt-drawer="true"
            data-kt-drawer-activate="{default: true, lg: false}"
            data-kt-drawer-overlay="true"
            data-kt-drawer-width="300px"
            data-kt-drawer-direction="start"
            data-kt-drawer-toggle="#toggleSidebar">

            <div class="aside-logo d-flex align-items-center justify-content-center border-bottom border-gray-800 pb-4 pt-4 mb-4">
                <a href="?c=home&m=index" class="text-white text-decoration-none">
                    <img src="<?php echo WEB_URL; ?>/assets/img/LogoMenu.png" alt="Logo" class="img-fluid mb-2">

                </a>
            </div>
            <div class="menu menu-column menu-title-gray-800 menu-state-title-primary menu-state-icon-primary menu-state-bullet-primary menu-arrow-gray-500" id="kt_aside_menu" data-kt-menu="true">
                <?php
                // Esta sección solo imprime el menú, no lo diseña.
                if (isset($_SESSION['menu'])) {
                    echo $_SESSION['menu'];
                }
                ?>
                <li class="nav-item mb-2">
                    <a class="nav-link text-white d-flex align-items-center" href="?c=Mantenimiento&m=index">
                        <i class="bi bi-gear-fill me-2"></i> Mantenimiento
                    </a>
                </li>
            </div>

            <!-- <ul class="nav flex-column">
                <?php
                // Obtenemos el ID del rol del usuario para usarlo en las condiciones
                //$rolUsuario = $_SESSION['user']['perfilId'] ?? 0;

                // Roles que pueden ver los menús de Convenios y Mantenimiento
                // (Añadimos el admin 20255 a tu nueva lista)
                //$rolesConvenios = [20260, 20261, 20262, 20259, 20255];

                // Roles que NO pueden ver el menú de Propuestas
                //$rolesSinPropuestas = [20260, 20261, 20262, 20259];
                ?>
                <li class="nav-item mb-2">
                    <a class="nav-link text-white d-flex align-items-center" href="?c=home&m=index">
                        <i class="bi bi-house me-2"></i> Inicio
                    </a>
                </li>

                <?php if (in_array($rolUsuario, $rolesConvenios)): ?>

                    <li class="nav-item mb-2">
                        <a class="nav-link text-white d-flex align-items-center" data-bs-toggle="collapse" href="#crearConvenios" role="button" aria-expanded="true" aria-controls="crearConvenios">
                            <i class="bi bi-pencil-square me-2"></i> Crear Convenios <i class="bi bi-chevron-up ms-auto"></i>
                        </a>
                        <div class="collapse show" id="crearConvenios">
                            <ul class="nav flex-column ms-3 mt-2">
                                <li><a class="nav-link text-white small" href="?c=convenio&m=formularioConvenioEspecifico">Convenio Específico</a></li>
                                <li><a class="nav-link text-white small" href="?c=convenio&m=formularioConvenioMarco">Convenio Marco</a></li>
                                <li><a class="nav-link text-white small" href="?c=convenio&m=formularioConvenioAdendum">Convenio Adendum</a></li>
                            </ul>
                        </div>
                    </li>

                    <li class="nav-item mb-2">
                        <a class="nav-link text-white d-flex align-items-center" data-bs-toggle="collapse" href="#gestionarConvenios" role="button" aria-expanded="true" aria-controls="gestionarConvenios">
                            <i class="bi bi-folder2-open me-2"></i> Convenios Guardados <i class="bi bi-chevron-up ms-auto"></i>
                        </a>
                        <div class="collapse show" id="gestionarConvenios">
                            <ul class="nav flex-column ms-3 mt-2">
                                <li><a class="nav-link text-white small" href="?c=DocumentosGenerados&m=index">Documentos Generados</a></li>
                                <li><a class="nav-link text-white small" href="?c=ConveniosSubidos&m=index">Convenios Subidos</a></li>
                            </ul>
                        </div>
                    </li>



                <?php endif; ?>
                <?php if (!in_array($rolUsuario, $rolesSinPropuestas)): ?>

                    <li class="nav-item mb-2">
                        <a class="nav-link text-white d-flex align-items-center" data-bs-toggle="collapse" href="#propuestasConvenio" role="button" aria-expanded="false" aria-controls="propuestasConvenio">
                            <i class="bi bi-file-earmark-text me-2"></i> Propuestas <i class="bi bi-chevron-down ms-auto"></i>
                        </a>
                        <div class="collapse show" id="propuestasConvenio">
                            <ul class="nav flex-column ms-3 mt-2">
                                <li><a class="nav-link text-white small" href="?c=Formularios&m=index">Gestionar Propuestas</a></li>
                            </ul>
                        </div>
                    </li>

                <?php endif; ?>
            </ul> -->
        </div>


        <script>
            /**
             * Valida todos los campos requeridos dentro de un formulario especificado,
             * excluyendo textareas.
             * Muestra un SweetAlert con estilo Metronic si algún campo requerido está vacío.
             *
             * @param {string} formId El ID del formulario a validar.
             */

            let phoneValidationTimeout;

            /**
             * Valida un formulario de forma global. Incluye lógica especial para 
             * formularios con campos dinámicos condicionales.
             * @param {string} formId El ID del formulario a validar.
             */


            function allowOnlyNumbers(event) {
                const key = event.key;
                // Permite la entrada de dígitos numéricos
                if (key >= '0' && key <= '9') {
                    return true;
                }
                // Permite teclas de control esenciales (borrar, tab, flechas, etc.)
                if (['Backspace', 'Delete', 'Tab', 'ArrowLeft', 'ArrowRight', 'Home', 'End'].includes(key)) {
                    return true;
                }
                // Bloquea todas las demás teclas previniendo la acción por defecto
                event.preventDefault();
                return false;
            }

            /**
             * Valida la longitud del teléfono en tiempo real mientras el usuario escribe.
             * Proporciona retroalimentación visual.
             * @param {HTMLElement} inputElement - El campo de input del teléfono.
             */
            function validatePhone(inputElement) {
                const value = inputElement.value.trim();

                // Si el campo tiene contenido pero no llega a 10 dígitos, muestra error.
                if (value.length > 0 && value.length < 10) {
                    inputElement.classList.add('is-invalid');
                } else {
                    // Si está vacío o tiene 10 dígitos, quita el error.
                    inputElement.classList.remove('is-invalid');
                }
            }

            function validateEmail(inputElement) {
                const value = inputElement.value.trim();
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

                // Si el campo tiene un valor pero no es un formato de correo válido
                if (value.length > 0 && !emailRegex.test(value)) {
                    // Añade la clase de Bootstrap para resaltar el error y mostrar el mensaje
                    inputElement.classList.add('is-invalid');
                } else {
                    // Si es válido o está vacío, quita la clase de error
                    inputElement.classList.remove('is-invalid');
                }
            }
        </script>

        <div class="w-100">
            <div id="propuestas-container" class="wrapper d-flex flex-column flex-grow-1 pt-5">
                <div class="container-fluid">