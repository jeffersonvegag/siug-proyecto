<?php

class ConveniosSubidosModel extends Model
{
    public function obtenerTodos()
    {
        $data = $this->executeStoredProcedure('GetConveniosSubidos', 'CONSULTA_TODOS', [], 'Parametros', true);
        return $data['resultado']['Table'];
    }

    public function guardarArchivo($nombreOriginal, $nombreGuardado, $tipo, $ruta, $descripcion)
    {
        $data = [
            'ConvenioSubido' => [
                'NombreOriginalConSub' => $nombreOriginal,
                'NombreGuardadoConSub' => $nombreGuardado,
                'TipoConSub' => $tipo,
                'RutaArchivoConSub' => $ruta,
                'DescripcionConSub' => $descripcion
            ],
            'UsuarioTrx' => $_SESSION['user']['username'],
        ];
        $this->executeStoredProcedure('SetConveniosSubidos', 'TRANSACCION_CONVENIO_SUBIDO', $data, 'Parametros', true);
    }

    public function obtenerPorId($id)
    {
        $data = [
            'ConvenioSubido' => [
                'IdConSub' => $id
            ],
            'UsuarioTrx' => $_SESSION['user']['username'],
        ];

        $result = $this->executeStoredProcedure('GetConveniosSubidos', 'CONSULTA_POR_ID', $data, 'Parametros', true);
       // var_dump($result['resultado']['Table']);
       // die();
        return $result['resultado']['Table'][0];
    }

    public function eliminarPorId($id)
    {
        $data = [
            'ConvenioSubido' => [
                'IdConSub' => $id
            ],
            'UsuarioTrx' => $_SESSION['user']['username'],
        ];
        $this->executeStoredProcedure('SetConveniosSubidos', 'ELIMINA_CONVENIO_SUBIDO', $data, 'Parametros', true);
    }

    // editar app\views\convenios_subidos\editar.php

    public function actualizarArchivo($id, $nombreOriginal, $nombreGuardado, $tipo, $ruta, $descripcion)
    {
        $data = [
            'ConvenioSubido' => [
                'IdConSub' => $id,
                'NombreOriginalConSub' => $nombreOriginal,
                'NombreGuardadoConSub' => $nombreGuardado,
                'TipoConSub' => $tipo,
                'RutaArchivoConSub' => $ruta,
                'DescripcionConSub' => $descripcion
            ],
            'UsuarioTrx' => $_SESSION['user']['username'],
        ];
        $this->executeStoredProcedure('SetConveniosSubidos', 'TRANSACCION_CONVENIO_SUBIDO', $data, 'Parametros', true);
    }
}
