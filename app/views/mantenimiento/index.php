<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mantenimiento de Plantillas</title>


</head>

<body>

    <div class="bg-dark py-5 mb-5">
        <div class="container">
            <h2 class="text-white text-center m-0">Mantenimiento de Plantillas de Convenios</h2>
        </div>
    </div>

    <div class="container pb-5">
        
        <?php
        // INICIO BLOQUE PHP: Lógica de retroalimentación (feedback) al usuario.
        // Se verifica si existe el parámetro 'status' en la URL (ej: index.php?c=mantenimiento&m=index&status=success).
        // La función isset() comprueba si una variable está definida y no es NULL.
        if (isset($_GET['status'])):
        ?>
            <div class="alert alert-<?php echo $_GET['status'] === 'success' ? 'success' : 'danger'; ?> alert-dismissible fade show" role="alert">
                
                <?php
                // Se comprueba de nuevo el valor de 'status' para mostrar el mensaje correspondiente.
                if ($_GET['status'] === 'success'):
                ?>
                    <strong>¡Éxito!</strong> La plantilla "<strong><?php echo htmlspecialchars($_GET['plantilla']); ?></strong>" ha sido actualizada correctamente.
                
                <?php
                // Si 'status' no es 'success', se asume que es un error.
                else:
                ?>
                    <strong>Error:</strong> Hubo un problema al actualizar la plantilla. <?php echo htmlspecialchars($_GET['error'] ?? 'Por favor, intente de nuevo.'); ?>
                <?php
                // Fin de la estructura condicional if/else.
                endif;
                ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php
        // Fin del bloque PHP que maneja las alertas.
        endif;
        ?>


        <div class="row g-5">

            <?php
            // INICIO BLOQUE PHP: Configuración de las plantillas.
            // Se define un array asociativo llamado $plantillas. Este array actúa como una fuente de datos centralizada.
            // Usar un array para la configuración hace que el código sea más limpio y fácil de mantener (principio DRY - Don't Repeat Yourself).
            // Para agregar una nueva plantilla, solo se necesita añadir un nuevo elemento a este array.
            $plantillas = [
                // Cada elemento tiene una clave (ej: 'marco') y un valor que es otro array con los detalles de la plantilla.
                'marco' => ['titulo' => 'Convenio Marco', 'archivo' => 'plantilla_convenio_marco.docx'],
                'especifico' => ['titulo' => 'Convenio Específico', 'archivo' => 'plantilla_convenio_especifico.docx'],
                'adendum' => ['titulo' => 'Adendum', 'archivo' => 'plantilla_convenio_adendum.docx']
            ];
            ?>

            <?php
            // INICIO BLOQUE PHP: Bucle para generar el contenido dinámicamente.
            // Se utiliza un bucle 'foreach' para recorrer cada elemento del array $plantillas.
            // En cada iteración:
            // - La clave (ej: 'marco') se asigna a la variable $tipo.
            // - El valor (el array con 'titulo' y 'archivo') se asigna a la variable $data.
            foreach ($plantillas as $tipo => $data):
            ?>
                <div class="col-12">
                    <div class="card card-plantilla p-3">
                        <div class="card-body">
                            <h5 class="card-title mb-3"><?php echo $data['titulo']; ?></h5>
                            <p class="card-text text-muted mb-4">Archivo base: <code><?php echo $data['archivo']; ?></code></p>
                            
                            <div class="d-flex flex-wrap justify-content-between align-items-center">
                                
                                <a href="?c=mantenimiento&m=descargar&plantilla=<?php echo $data['archivo']; ?>" class="btn btn-secondary mb-2">
                                    1. Descargar para Editar
                                </a>

                                <form action="?c=mantenimiento&m=subir" method="post" enctype="multipart/form-data" class="d-flex align-items-center mb-2">
                                    
                                    <input type="hidden" name="plantilla_nombre" value="<?php echo $data['archivo']; ?>">
                                    
                                    <input type="file" name="plantilla_archivo" class="form-control me-2" required accept=".docx,application/vnd.openxmlformats-officedocument.wordprocessingml.document">
                                    <button type="submit" class="btn btn-primary">2. Subir y Reemplazar</button>
                                </form>
                            </div>
                            <div class="form-text">
                                Descargue el archivo, edítelo en Word y súbalo para actualizar la plantilla base.
                            </div>
                        </div>
                    </div>
                </div>
            <?php
            // Fin del bucle foreach. El código HTML entre el inicio del bucle y este 'endforeach' se repetirá para cada plantilla.
            endforeach;
            ?>
        </div>
    </div>


</body>

</html>