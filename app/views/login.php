<?php require_once "../config/constants.php"; ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Sistema de Vinculación</title>
    <link rel="stylesheet" href="<?php echo WEB_URL ?>/assets/metronic/plugins/global/plugins.bundle.css">
    <link rel="stylesheet" href="<?php echo WEB_URL ?>/assets/metronic/css/style.bundle.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <style>
        /* Fondo degradado sutil para un look más profesional */
        body {
            background-color: #f0f2f5;
            background-image: linear-gradient(to top, #eef1f5 0%, #ffffff 100%);
        }
        /* Corrección para que el modal de SweetAlert2 no mueva la página */
        body.swal2-shown {
            height: 100vh !important;
            overflow-y: hidden !important;
            padding-right: 0px !important;
        }
        /* Ajuste para el ícono del ojo dentro del campo flotante */
        .form-floating .password-toggle {
            position: absolute;
            top: 50%;
            right: 1rem;
            transform: translateY(-50%);
            cursor: pointer;
            color: #a1a5b7;
        }
    </style>
</head>

<body class="d-flex justify-content-center align-items-center min-vh-100">
    
    <div class="card shadow-lg p-5 p-lg-10 rounded-3 w-100" style="max-width: 450px;">
        <div class="text-center mb-8">
            <img src="assets/img/LogoUGcolor.png" alt="Logo Universidad de Guayaquil" class="img-fluid mb-4" style="max-height: 130px;">
            <h1 class="text-dark fw-light text-uppercase mb-3 fs-2">Sistema Integrado de propuestas y convenios de Vinculación con la Sociedad</h1>
            <p class="text-muted fw-semibold fs-6">Inicia sesión con tus credenciales institucionales</p>
        </div>

        <form method="POST" action="?c=auth&m=login" id="login-form">
            <div class="form-floating mb-4">
                <input type="email" id="correo" name="correo" placeholder="" required class="form-control form-control-solid">
                <label for="correo">Correo institucional</label>
            </div>

            <div class="form-floating mb-4 position-relative">
                <input type="password" id="password" name="password" placeholder="" required class="form-control form-control-solid">
                <label for="password">Contraseña</label>
                <span id="togglePassword" class="password-toggle">
                    <i class="fa fa-eye-slash"></i>
                </span>
            </div>
            
            <div class="d-grid">
                <button type="submit" id="submit-button" class="btn btn-primary btn-lg">
                    <span class="indicator-label">Iniciar Sesión</span>
                    <span class="indicator-progress">
                        Por favor espere...
                        <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                    </span>
                </button>
            </div>
        </form>
    </div>

    <script src="<?php echo WEB_URL ?>/assets/metronic/plugins/global/plugins.bundle.js"></script>
    <script src="<?php echo WEB_URL ?>/assets/metronic/js/scripts.bundle.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            // --- Lógica para mostrar/ocultar contraseña ---
            const togglePassword = document.getElementById('togglePassword');
            const passwordInput = document.getElementById('password');

            togglePassword.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                
                // Cambiar el ícono
                this.querySelector('i').classList.toggle('fa-eye');
                this.querySelector('i').classList.toggle('fa-eye-slash');
            });


            // --- Lógica de login con AJAX y SweetAlert (simplificada) ---
            const loginForm = document.getElementById('login-form');
            const submitButton = document.getElementById('submit-button');

            loginForm.addEventListener('submit', function(event) {
                event.preventDefault(); // Prevenimos la recarga de la página

                // Activar el indicador de carga en el botón
                submitButton.setAttribute('data-kt-indicator', 'on');
                submitButton.disabled = true;

                fetch(this.action, {
                    method: 'POST',
                    body: new FormData(this)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Alerta de éxito
                        Swal.fire({
                            text: "¡Inicio de sesión exitoso!",
                            icon: "success",
                            timer: 1500,
                            showConfirmButton: false
                        });
                        // Redirigir después de que la alerta se cierre
                        setTimeout(() => {
                            if (data.redirectUrl) {
                                window.location.href = data.redirectUrl;
                            }
                        }, 1500);
                    } else {
                        // Alerta de error
                        Swal.fire({
                            text: data.message || "Las credenciales son incorrectas. Por favor, inténtelo de nuevo.",
                            icon: "error",
                            confirmButtonText: 'Entendido'
                        });
                    }
                })
                .catch(error => {
                    // Alerta para errores de conexión o del servidor
                    Swal.fire({
                        text: "Ocurrió un error de conexión. Por favor, verifique su red.",
                        icon: "error",
                        confirmButtonText: 'Entendido'
                    });
                    console.error('Error en el fetch:', error);
                })
                .finally(() => {
                    // Quitar el indicador de carga y reactivar el botón
                    submitButton.removeAttribute('data-kt-indicator');
                    submitButton.disabled = false;
                });
            });
        });
    </script>

</body>
</html>