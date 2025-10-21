    </div> <!-- cierre del contenido principal -->
    <div id="loading-overlay" style="display: none;">
        <div class="loading-box">
            <div class="loading-spinner"></div>
            <p>Procesando, un momento por favor...</p>
        </div>
    </div>
    </div> <!-- cierre del wrapper sidebar + contenido -->
    <?php if (!empty($errores)): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: '¡Errores Encontrados!',
                    html: 'Por favor, revise los campos marcados en rojo.',
                    icon: 'error',
                    confirmButtonText: 'Entendido'
                });
            });
        </script>
    <?php endif; ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const loadingOverlay = document.getElementById('loading-overlay');

            // --------------- 1. FUNCIONES CENTRALES ---------------
            // Funciones básicas para mostrar y ocultar el overlay genérico.

            function showOverlay() {
                if (loadingOverlay) {
                    loadingOverlay.style.display = 'flex';
                }
            }

            function hideOverlay() {
                if (loadingOverlay) {
                    loadingOverlay.style.display = 'none';
                }
            }

            // --------------- 2. MANEJADORES DE EVENTOS POR CLASE ---------------
            // Estos son los listeners que buscan clases específicas en tu HTML.

            // Para formularios que SÍ deben mostrar el overlay genérico (porque recargan la página)
            document.querySelectorAll('form.show-overlay-on-submit').forEach(form => {
                form.addEventListener('submit', showOverlay);
            });

            // Para enlaces <a> o botones <button> que navegan a otra página y deben mostrar el overlay
            document.querySelectorAll('.show-overlay-on-click').forEach(element => {
                element.addEventListener('click', showOverlay);
            });

            // --------------- 3. MECANISMOS DE SEGURIDAD ---------------
            // Estos listeners mejoran la experiencia de usuario en casos especiales.

            // Para la flecha "atrás" del navegador, oculta el overlay si la página se carga desde caché.
            window.addEventListener('pageshow', function(event) {
                if (event.persisted) {
                    hideOverlay();
                }
            });

            // Permite al usuario cancelar el overlay presionando la tecla "Escape".
            window.addEventListener('keydown', function(event) {
                if (event.key === 'Escape') {
                    hideOverlay();
                }
            });

        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Seleccionamos todos los botones que tengan la clase 'btn-eliminar'
            const botonesEliminar = document.querySelectorAll('.btn-eliminar');

            botonesEliminar.forEach(function(boton) {
                boton.addEventListener('click', function() {
                    // Obtenemos el ID del proyecto desde el atributo data-id
                    const proyectoId = this.getAttribute('data-id');

                    Swal.fire({
                        title: '¿Estás seguro?',
                        text: "¡No podrás revertir esta acción!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Sí, ¡eliminar!',
                        cancelButtonText: 'Cancelar'
                    }).then((result) => {
                        // Si el usuario confirma la acción
                        if (result.isConfirmed) {
                            // Mostramos el overlay de carga
                            const loadingOverlay = document.getElementById('loading-overlay');
                            if (loadingOverlay) {
                                loadingOverlay.style.display = 'flex';
                            }

                            // Redirigimos a la URL de eliminación
                            window.location.href = `index.php?c=formularios&m=eliminar&id=${proyectoId}`;
                        }
                    });
                });
            });
        });
        const botonesPdf = document.querySelectorAll('.btn-descargar-pdf');

        botonesPdf.forEach(function(boton) {
            boton.addEventListener('click', function() {
                const controller = new AbortController();
                const signal = controller.signal;

                const proyectoId = this.getAttribute('data-id');
                const nombreProyecto = this.getAttribute('data-nombre');
                const nombreArchivo = `Proyecto-${nombreProyecto.replace(/ /g, '_')}.pdf`;

                // --- ALERTA CON ESTILOS MEJORADOS ---
                Swal.fire({
                    title: 'Generando PDF',
                    text: 'Por favor, espera un momento...',
                    icon: 'info',
                    allowOutsideClick: false,
                    showCancelButton: true,
                    confirmButtonText: 'Entendido',
                    cancelButtonText: 'Cancelar',
                    cancelButtonColor: '#d33',
                    customClass: {
                        actions: 'swal-actions-separated',
                    },

                    // =======================================================
                    // ▼▼▼ MODIFICACIÓN DENTRO DE didOpen ▼▼▼
                    didOpen: () => {
                        // Muestra el ícono de carga y oculta el botón de "Entendido".
                        Swal.showLoading(Swal.getConfirmButton());

                        // --- Lógica para reordenar el layout ---
                        const actions = Swal.getActions(); // El contenedor de los botones
                        const loader = Swal.getLoader(); // El spinner de carga
                        const cancelButton = Swal.getCancelButton();

                        if (actions && loader && cancelButton) {
                            // Aplicamos los estilos para el diseño vertical
                            actions.style.flexDirection = 'column';
                            actions.style.gap = '1rem';

                            // Movemos el spinner para que sea el primer elemento
                            actions.prepend(loader);
                        }

                        // --- Tu lógica para el botón de cancelar no cambia ---
                        if (cancelButton) {
                            cancelButton.addEventListener('click', () => {
                                controller.abort();
                            });
                        }
                    }
                    // ▲▲▲ FIN DE LA MODIFICACIÓN ▲▲▲
                    // =======================================================
                });

                // ... (El resto de tu código 'fetch' va aquí y no cambia) ...
                fetch(`index.php?c=Proyectos&m=generarPdf&id=${proyectoId}`, {
                        signal
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('La respuesta del servidor no fue exitosa.');
                        }
                        return response.blob();
                    })
                    .then(blob => {
                        const url = window.URL.createObjectURL(blob);
                        const a = document.createElement('a');
                        a.style.display = 'none';
                        a.href = url;
                        a.download = nombreArchivo;
                        document.body.appendChild(a);
                        a.click();
                        window.URL.revokeObjectURL(url);
                        document.body.removeChild(a);

                        Swal.fire({
                            title: '¡Descarga Completa!',
                            text: `El archivo "${nombreArchivo}" se ha descargado correctamente.`,
                            icon: 'success',
                            timer: 2500,
                            showConfirmButton: false
                        });
                    })
                    .catch(error => {
                        if (error.name === 'AbortError') {
                            Swal.fire('Cancelado', 'La descarga del PDF ha sido cancelada.', 'info');
                        } else {
                            console.error('Error al generar el PDF:', error);
                            Swal.fire('Error', 'No se pudo generar el PDF. Inténtalo de nuevo más tarde.', 'error');
                        }
                    });
            });

        });
    </script>

    </body>

    </html>