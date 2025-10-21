<?php

class Model
{
    protected function conectar()
    {
        // $config = require ROOT_PATH . '/config/database.php';
        // try {
        //     if (empty($config['usuario']) && empty($config['clave'])) {
        //         $pdo = new PDO($config['dsn']);
        //     } else {
        //         $pdo = new PDO($config['dsn'], $config['usuario'], $config['clave']);
        //     }
        //     $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        //     return $pdo;
        // } catch (PDOException $e) {
        //     // Consider logging the error here.
        //     throw new Exception('Error de conexión a la base de datos: ' . $e->getMessage());
        // }
    }

    /**
     * Estandariza la llamada a procedimientos almacenados.
     *
     * @param string $spName El nombre completo del procedimiento almacenado (ej. 'CONVENIO.SetConveniosSubidos').
     * @param string $iTransaccion El valor para el parámetro @iTransaccion del SP.
     * @param array $xmlData Array asociativo con los datos para generar el XML.
     * @param string $xmlRootElement El nombre del elemento raíz del XML (ej. 'Parametros').
     * @param bool $fetchAll Si es true, retorna todos los resultados; si es false, retorna uno.
     * @return array|bool Retorna los resultados de la consulta o un indicador de éxito/fallo.
     * @throws Exception Si ocurre un error de PDO.
     */
    protected function executeStoredProcedure(
        string $spName,
        string $iTransaccion,
        array $xmlData,
        string $xmlRootElement,
        bool $fetchAll = false
    ) {
        require_once ROOT_PATH . '/core/XmlGenerator.php';
        require_once ROOT_PATH . '/app/models/InstitucionService.php';


        //$pdo = $this->conectar();
        $xml = XmlGenerator::generateXmlFromArray($xmlData, $xmlRootElement);

/* 
        $log = json_encode($xml);
        echo "<script>console.log('{$log}');</script>";
 */
        //var_dump(json_encode($xml));  // con esto hago las pruebas
        //die();

        $institutionService = new InstitucionService();
        $token = $institutionService->GetTokenDB();
        return $institutionService->GetData($spName, $xml, $iTransaccion, $token);
    }
}
