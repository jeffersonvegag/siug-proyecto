<?php

// No es necesario incluir Model.php si tu proyecto ya lo carga automáticamente.
// Si no, asegúrate de que esté incluido:
// require_once ROOT_PATH . '/core/Model.php';

class MantenimientoModel extends Model
{
    /**
     * Guarda el contenido HTML del documento llamando al servicio intermediario.
     *
     * @param string $htmlContent El contenido HTML a guardar.
     * @return mixed El resultado devuelto por el servicio InstitucionService.
     * @throws Exception Si ocurre un error durante la ejecución.
     */
    public function guardarContenido($htmlContent)
    {
        // 1. Nombre del Stored Procedure que quieres ejecutar.
        //    Asegúrate de que este sea el nombre correcto.
        $spName = 'sp_guardar_documento';

        // 2. Código de transacción.
        //    Este valor depende de cómo esté programado tu SP.
        //    'INS' (de Insertar) es una suposición común. ¡Debes verificar si es el correcto!
        $iTransaccion = 'INS';

        // 3. Datos que se convertirán en XML.
        //    La clave del array ('ContenidoHTML') será la etiqueta XML dentro del raíz.
        //    Ej: <Parametros><ContenidoHTML>...tu html...</ContenidoHTML></Parametros>
        $xmlData = [
            'ContenidoHTML' => $htmlContent
        ];

        // 4. Nombre del elemento raíz para el XML.
        $xmlRootElement = 'Parametros';

        // 5. Llamamos a la función que SÍ funciona en tu proyecto.
        //    Esta función está en tu 'core/Model.php' y se encarga de todo.
        //    El 'false' al final indica que no esperamos recibir una lista de resultados,
        //    sino una respuesta de éxito/fallo.
        return $this->executeStoredProcedure(
            $spName,
            $iTransaccion,
            $xmlData,
            $xmlRootElement,
            false // fetchAll = false
        );
    }
}