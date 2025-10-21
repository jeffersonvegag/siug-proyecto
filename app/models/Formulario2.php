<?php

class Formulario2 extends Model
{

    private $pdo;

    public function __construct()
    {
        // $config = require ROOT_PATH . '/config/database.php';
        // try {
        //     $this->pdo = new PDO($config['dsn'], $config['usuario'], $config['clave']);
        //     $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // } catch (PDOException $e) {
        //     die("Error de conexión en Formulario2: " . $e->getMessage());
        // }
    }

    public function insertarFormulario2($id_proyecto, $datos)
    {
        $data = [
            'Formulario2' => [
                'IdPropuesta' => $id_proyecto,
                'IdObjetivoOds' =>  $datos['objetivo_sostenible'] ?? null,
                'ComentariosOds' => $datos['comentarios_ods'] ?? null,
                'IdEjePlan' =>  $datos['eje'] ?? null,
                'ComentariosEje' =>  $datos['comentarios_eje'] ?? null,
                'IdObjetivoNacional' =>  $datos['objetivo_nacional'] ?? null,
                'ComentariosObjNacional' => $datos['comentarios_obj_nacional'] ?? null,
                'IdDominioCientifico' => $datos['dominios'] ?? null,
                'ComentariosDominios' =>  $datos['comentarios_dominios'] ?? null,
                'IdLineaInstitucional' =>$datos['lineas'] ?? null,
                'ComentariosLineas' => $datos['comentarios_lineas'] ?? null,
                'UsuarioTrx' => $_SESSION['user']['username']
            ]
        ];
        $this->executeStoredProcedure('SetForms', 'UPSERT_FORM2', $data, 'Transaccion', true);

        // $sql = "INSERT INTO proyecto.Formulario2_ObjetivosAlineacion
        //         (IdProyecto, IdObjetivoOds, ComentariosOds, IdEjePlan, ComentariosEje,
        //          IdObjetivoNacional, ComentariosObjNacional, IdDominioCientifico,
        //          ComentariosDominios, IdLineaInstitucional, ComentariosLineas)
        //         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        // $stmt = $this->pdo->prepare($sql);
        // $stmt->execute([
        //     $id_proyecto,
        //     $datos['objetivo_sostenible'] ?? null,
        //     $datos['comentarios_ods'] ?? null,
        //     $datos['eje'] ?? null,
        //     $datos['comentarios_eje'] ?? null,
        //     $datos['objetivo_nacional'] ?? null,
        //     $datos['comentarios_obj_nacional'] ?? null,
        //     $datos['dominios'] ?? null,
        //     $datos['comentarios_dominios'] ?? null,
        //     $datos['lineas'] ?? null,
        //     $datos['comentarios_lineas'] ?? null
        // ]);
    }

    public function obtenerFormulario2PorProyecto($id_proyecto)
    {
        $data = [  'IdPropuesta' => $id_proyecto  ];
        $result = $this->executeStoredProcedure('GetForms', 'GET_FORM2_OBJETIVOS_ALINEACION', $data, 'Transaccion', true);
        return $result['resultado']['Table'];

    }

    public function actualizarFormulario2($id_formulario2, $id_proyecto, $datos)
    {
        $data = [
            'Formulario2' => [
                'IdFormulario2' =>  $id_formulario2,
                'IdPropuesta' => $id_proyecto,
                'IdObjetivoOds' =>  $datos['objetivo_sostenible'] ?? null,
                'ComentariosOds' => $datos['comentarios_ods'] ?? null,
                'IdEjePlan' =>  $datos['eje'] ?? null,
                'ComentariosEje' =>  $datos['comentarios_eje'] ?? null,
                'IdObjetivoNacional' =>  $datos['objetivo_nacional'] ?? null,
                'ComentariosObjNacional' => $datos['comentarios_obj_nacional'] ?? null,
                'IdDominioCientifico' => $datos['dominios'] ?? null,
                'ComentariosDominios' =>  $datos['comentarios_dominios'] ?? null,
                'IdLineaInstitucional' =>$datos['lineas'] ?? null,
                'ComentariosLineas' => $datos['comentarios_lineas'] ?? null,
                'UsuarioTrx' => $_SESSION['user']['username']
            ]
        ];
        $this->executeStoredProcedure('SetForms', 'UPSERT_FORM2', $data, 'Transaccion', true);
    }

    // ===================================================================
    // NUEVAS FUNCIONES AÑADIDAS
    // ===================================================================

    /**
     * Guarda (inserta o actualiza) los datos del paso 2.
     */
    public function guardarPaso2($id_proyecto, $data)
    {
        $datosExistentes = $this->obtenerFormulario2PorProyecto($id_proyecto);

        if ($datosExistentes) {
            // Si ya existen datos, los actualiza
            $id_formulario2 = $datosExistentes[0]['IdFormulario2'];
            $this->actualizarFormulario2($id_formulario2, $id_proyecto, $data);
        } else {
            // Si no existen, los inserta
            $this->insertarFormulario2($id_proyecto, $data);
        }
    }

    /**
     * Recupera y formatea los datos del paso 2 para rellenar la vista.
     */
    public function obtenerDatosCompletosPaso2($id_proyecto)
    {
        $datos_db = $this->obtenerFormulario2PorProyecto($id_proyecto);

        if (!$datos_db) {
            return []; // Devuelve vacío si no hay nada guardado
        }else{
            $datos_db =$datos_db[0];
        }

        // Mapea los nombres de la BD a los nombres de los campos del formulario
        return [
            'objetivo_sostenible' => $datos_db['IdObjetivoOds'],
            'comentarios_ods' => $datos_db['ComentariosOds'],
            'eje' => $datos_db['IdEjePlan'],
            'comentarios_eje' => $datos_db['ComentariosEje'],
            'objetivo_nacional' => $datos_db['IdObjetivoNacional'],
            'comentarios_obj_nacional' => $datos_db['ComentariosObjNacional'],
            'dominios' => $datos_db['IdDominioCientifico'],
            'comentarios_dominios' => $datos_db['ComentariosDominios'],
            'lineas' => $datos_db['IdLineaInstitucional'],
            'comentarios_lineas' => $datos_db['ComentariosLineas']
        ];
    }
}//fin