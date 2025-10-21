<?php

class DocumentosGeneradosModel extends Model
{
    // V2 con la datos_convenios
    public function obtenerTodos()
    {
        $data = $this->executeStoredProcedure('GetDocumentosGenerados', 'CONSULTA_TODOS', [], 'Parametros', true);
        return $data['resultado']['Table'];
    }

    public function obtenerPorId($id)
    {
        $data = [
            'DocumentosGenerados' => [
                'IdDocGen' => $id
            ],
            'UsuarioTrx' => $_SESSION['user']['username'],
        ];
        $result = $this->executeStoredProcedure('GetDocumentosGenerados', 'CONSULTA_POR_ID', $data, 'Parametros', true);
        //var_dump($id['resultado']['Table'][0]);
        //die();
        return $result['resultado']['Table'][0];
    }

    public function eliminarPorId($id)
    {
        $data = [
            'DocumentoGenerado' => [
                'IdDocGen' => $id
            ],
            'UsuarioTrx' => $_SESSION['user']['username'],
        ];
        $this->executeStoredProcedure('SetDocumentosGenerados', 'ELIMINA_DOCUMENTO_GENERADO', $data, 'Parametros', true);

        //$pdo = $this->conectar();
        //$stmt = $pdo->prepare("DELETE FROM documentos_generados WHERE id_doc_gen = :id");
        //return $stmt->execute([':id' => $id]);
    }

    public function guardarDocumentoGenerado($uuid, $nombre, $ruta, $tipo)
    {
        // $pdo = $this->conectar();

        // $stmt = $pdo->prepare("
        //     INSERT INTO documentos_generados
        //         (codigo_convenio_dog_gen, nombre_archivo_doc_gen, ruta_archivo_doc_gen, tipo_documento_doc_gen)
        //     OUTPUT INSERTED.id_doc_gen
        //     VALUES
        //         (:uuid, :nombre, :ruta, :tipo)
        // ");

        // $stmt->execute([
        //     ':uuid' => $uuid,
        //     ':nombre' => $nombre,
        //     ':ruta' => $ruta,
        //     ':tipo' => strtoupper($tipo)
        // ]);

        $data = [
            'DocumentoGenerado' => [
                'CodigoConvenioDogGen' => $uuid,
                'NombreArchivoDocGen' => $nombre,
                'TipoDocumentoDocGen' => $tipo,
                'RutaArchivoDocGen' => $ruta,
            ],
            'UsuarioTrx' => $_SESSION['user']['username'],
        ];
        $id = $this->executeStoredProcedure('SetDocumentosGenerados', 'TRANSACCION_DOCUMENTO_GENERADO', $data, 'Parametros', true);
        return $id['resultado']['Table'][0]['IdDocumentoGeneradoOut']; // Devuelve el id_doc_gen insertado
    }


    // -----------------------------------------------------

    //---TP---
    // Guarda los datos del formulario generado para un convenio
    public function guardarDatosConvenio($idDocGen, $tipoConvenio, $rutaFormulario, $idProyecto = null)
    {
        // $pdo = $this->conectar();
        // $stmt = $pdo->prepare("
        //     INSERT INTO datos_convenios
        //         (id_doc_gen, tipo_convenio_dat_con, ruta_archivo_formulario_dat_con, IdProyecto)
        //     VALUES
        //         (:id_doc_gen, :tipo_convenio, :ruta_formulario, :id_proyecto)
        // ");
        // $stmt->execute([
        //     ':id_doc_gen' => $idDocGen,
        //     ':tipo_convenio' => $tipoConvenio,
        //     ':ruta_formulario' => $rutaFormulario,
        //     ':id_proyecto' => $idProyecto
        // ]);

        if (isset($idProyecto)) {
            $data = [
                'DatosConvenio' => [
                    'IdDocGen' => $idDocGen,
                    'TipoConvenioDatCon' => $tipoConvenio,
                    'RutaArchivoFormularioDatCon' => $rutaFormulario,
                    'IdPropuesta' => $idProyecto,
                ],
                'UsuarioTrx' => $_SESSION['user']['username'],
            ];
        } else {
            $data = [
                'DatosConvenio' => [
                    'IdDocGen' => $idDocGen,
                    'TipoConvenioDatCon' => $tipoConvenio,
                    'RutaArchivoFormularioDatCon' => $rutaFormulario
                ],
                'UsuarioTrx' => $_SESSION['user']['username'],
            ];
        }
        $this->executeStoredProcedure('SetDatosConvenios', 'TRANSACCION_DATOS_CONVENIO', $data, 'Parametros', true);
    }



    // Obtiene los datos de un convenio a partir del id_doc_gen
    public function obtenerDatosConvenioPorIdDocGen($idDocGen)
    {
        // $pdo = $this->conectar();
        // $stmt = $pdo->prepare("
        //     SELECT *
        //     FROM datos_convenios
        //     WHERE id_doc_gen = :id_doc_gen
        // ");
        // $stmt->execute([':id_doc_gen' => $idDocGen]);
        $data = [
            'DatosConvenio' => [
                'IdDocGen' => $idDocGen
            ],
            'UsuarioTrx' => $_SESSION['user']['username'],
        ];
        $result = $this->executeStoredProcedure('GetDatosConvenios', 'CONSULTA_POR_DOC_GEN_ID', $data, 'Parametros', true);
        return $result['resultado']['Table'][0];
    }

    //---TP---
    public function actualizarDatosConvenio($idDocGen, $tipoConvenio, $rutaArchivo, $idProyecto = null)
    {
        $data = [
            'DatosConvenio' => [
                'IdDocGen' => $idDocGen,
                'TipoConvenioDatCon' => $tipoConvenio,
                'RutaArchivoFormularioDatCon' => $rutaArchivo,
                'IdPropuesta' => $idProyecto,
            ],
            'UsuarioTrx' => $_SESSION['user']['username'],
        ];
        $this->executeStoredProcedure('SetDatosConvenios', 'TRANSACCION_DATOS_CONVENIO', $data, 'Parametros', true);
    }

    // (Opcional) Eliminar los datos del convenio (si alguna vez lo necesitas)
    public function eliminarDatosConvenioPorIdDocGen($idDocGen)
    {
        $data = [
            'DatosConvenio' => [
                'IdDatCon' => $idDocGen
            ],
            'UsuarioTrx' => $_SESSION['user']['username'],
        ];
        $this->executeStoredProcedure('SetDatosConvenios', 'ELIMINA_DATOS_CONVENIO', $data, 'Parametros', true);
    }

    public function actualizarEstado($idDocGen, $nuevoEstado)
    {
        $data = [

            'IdDocGen' => $idDocGen,
            'Estado' => $nuevoEstado

        ];

        // Ejecutamos el SP y capturamos la respuesta cruda
        $respuestaCruda = $this->executeStoredProcedure('SetEstado', 'UPDATE_ESTADO_CONVENIO', $data, 'Parametros', true);

        return $respuestaCruda;
    }

    // --------------------

}//fin
