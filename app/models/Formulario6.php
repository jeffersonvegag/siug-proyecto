<?php

class Formulario6 extends Model
{

    private $pdo;

    public function __construct()
    {    }

    /**
     * FUNCIÓN CORREGIDA Y UNIFICADA (UPSERT)
     * Guarda o actualiza los datos del paso 6 para un proyecto.
     * @param int $id_proyecto El ID del proyecto.
     * @param array $datos Los datos del formulario (de $_POST).
     */
    public function guardarPaso6($id_proyecto, $datos)
    {
        // Primero, verificamos si ya existe un registro para este proyecto
        $existente = $this->obtenerPaso6PorProyecto($id_proyecto);

        if ($existente) {
            // Si existe, preparamos una sentencia UPDATE
            $existente = $existente[0];
            $data = [
                'Formulario6' => [
                    'IdDetalleProyecto'=>$existente['IdDetalleProyecto'],
                    'IdPropuesta' => $id_proyecto,
                    'Justificacion' =>  $datos['justificacion'] ?? null,
                    'ComentariosJustificacion' => $datos['comentarios_justificacion'] ?? null,
                    'LineaBase' =>  $datos['linea_base'] ?? null,
                    'ComentariosLineaBase' =>   $datos['comentarios_linea_base'] ?? null,
                    'FundamentacionTeorica' =>  $datos['fundamentacion_teorica'] ?? null,
                    'ComentariosFundamentacion' =>   $datos['comentarios_fundamentacion'] ?? null,
                    'UsuarioTrx' => $_SESSION['user']['username']
                ]
            ];
            $this->executeStoredProcedure('SetForms', 'UPSERT_FORM6', $data, 'Transaccion', true);

        } else {
            // Si no existe, preparamos una sentencia INSERT
             $data = [
                'Formulario6' => [
                    'IdPropuesta' => $id_proyecto,
                    'Justificacion' =>  $datos['justificacion'] ?? null,
                    'ComentariosJustificacion' => $datos['comentarios_justificacion'] ?? null,
                    'LineaBase' =>  $datos['linea_base'] ?? null,
                    'ComentariosLineaBase' =>   $datos['comentarios_linea_base'] ?? null,
                    'FundamentacionTeorica' =>  $datos['fundamentacion_teorica'] ?? null,
                    'ComentariosFundamentacion' =>   $datos['comentarios_fundamentacion'] ?? null,
                    'UsuarioTrx' => $_SESSION['user']['username']
                ]
            ];
            $this->executeStoredProcedure('SetForms', 'UPSERT_FORM6', $data, 'Transaccion', true);
        }
    }

    /**
     * Obtiene los datos del paso 6 para un proyecto específico.
     * @param int $id_proyecto El ID del proyecto.
     * @return array|false Un array asociativo con los datos o false si no se encuentra.
     */
    public function obtenerPaso6PorProyecto($id_proyecto)
    {
         $data = ['IdPropuesta' => $id_proyecto];
        $datosGenerales = $this->executeStoredProcedure('GetForms', 'GET_FORM6_DETALLE_PROYECTO', $data, 'Transaccion', true);
        return $datosGenerales['resultado']['Table'];
    }


    // ACTUALIZAR: borra el existente y crea nuevo (como en otros formularios)
    public function actualizarPaso6($id_proyecto, $datos)
    {
        $data = ['IdPropuesta' => $id_proyecto];
        $this->executeStoredProcedure('SetEstado', 'DELETE_Formulario6_DetalleProyecto', $data, 'Transaccion', true);
        $this->guardarPaso6($id_proyecto, $datos);
    }
}//fin
