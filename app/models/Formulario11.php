<?php

class Formulario11 extends Model{
    private $pdo;

    public function __construct() {
        // $config = require ROOT_PATH . '/config/database.php';
        // try {
        //     $this->pdo = new PDO($config['dsn'], $config['usuario'], $config['clave']);
        //     $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // } catch (PDOException $e) {
        //     die("Error de conexión en Formulario11: " . $e->getMessage());
        // }
    }

    public function guardarDeclaracion($id_proyecto, $mirada_gestor) {
         $data = [
            'Formulario11' => [
                'IdPropuesta' => $id_proyecto,
                'AceptaDeclaracion' => 1,
                'MiradaGestorFacultad' => $mirada_gestor,
                'UsuarioTrx' => $_SESSION['user']['username']
            ]
        ];
        $this->executeStoredProcedure('SetForms', 'UPSERT_FORM11', $data, 'Transaccion', true);
    }

    // OBTENER la declaración de un proyecto (devuelve array asociativo o false si no existe)
    public function obtenerDeclaracion($id_proyecto) {
        $data = ['IdPropuesta' => $id_proyecto];
        $declaracion = $this->executeStoredProcedure('GetForms', 'GET_FORM11_DECLARACION_FINAL', $data, 'Transaccion', true);
        
        return $declaracion['resultado']['Table'][0] ?? false;
    }

    // ACTUALIZAR la declaración (siempre AceptaDeclaracion = 1)
    public function actualizarDeclaracion($id_proyecto, $mirada_gestor) {
       
        $declaracion = $this->obtenerDeclaracion($id_proyecto);
        $data = [
            'Formulario11' => [
                'IdDeclaracion'=> $declaracion['resultado']['Table'][0]['IdDeclaracion'],
                'IdPropuesta' => $id_proyecto,
                'AceptaDeclaracion' => 1,
                'MiradaGestorFacultad' => $mirada_gestor,
                'UsuarioTrx' => $_SESSION['user']['username']
            ]
        ];
        $this->executeStoredProcedure('SetForms', 'UPSERT_FORM11', $data, 'Transaccion', true);
    }



}//fin
