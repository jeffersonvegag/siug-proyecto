<?php

class TrazabilidadModel extends Model
{
    /**
     * Guarda un nuevo registro de acción llamando al Stored Procedure [GENERAL].[SetTrazabilidad].
     *
     * @param int $idDocGen El ID del documento generado.
     * @param string $usuario El nombre de usuario que realiza la acción.
     * @param string $rol El rol del usuario.
     * @param string $accion Una descripción de la acción realizada.
     * @param string|null $comentario Un comentario opcional.
     * @param int|null $idPropuesta El ID de la propuesta (opcional).
     */
    public function guardarAccion($idDocGen, $usuario, $rol, $accion, $comentario = '', $idPropuesta = null)
    {
        // Construimos el XML que espera el Stored Procedure

        if ($idPropuesta) {
            $xmlData = [

                'IdPropuesta' => $idPropuesta,
                'Usuario' => $usuario,
                'RolUsuario' => $rol,
                'Accion' => $accion,
                'Comentario' => $comentario,
                'UsuarioTrx' => $_SESSION['user']['username'] ?? 'sistema' // Es bueno registrar quién hace el cambio

            ];
            // Asignamos 0 si no se proporciona un ID de propuesta
        } else {
            $xmlData = [

                'IdDocGen' => $idDocGen,
                'Usuario' => $usuario,
                'RolUsuario' => $rol,
                'Accion' => $accion,
                'Comentario' => $comentario,
                'UsuarioTrx' => $_SESSION['user']['username'] ?? 'sistema' // Es bueno registrar quién hace el cambio

            ];
        }

        // Llamamos al Stored Procedure
        $result = $this->executeStoredProcedure(
            'SetTrazabilidad',      // Nombre del SP
            'INSERT_TRAZABILIDAD',          // Parámetro @iTransaccion
            $xmlData,                             // Datos (no se usa en tu implementación base)
            'Transaccion',                   // Nombre del parámetro XML (ajusta si es diferente)
            true                           // Indica que se espera un resultado

        );
        



    }


    /**
     * Obtiene el historial de un documento llamando al Stored Procedure [GENERAL].[GetTrazabilidad].
     *
     * @param int $idDocGen El ID del documento a consultar.
     * @return array El historial de trazabilidad.
     */
    public function obtenerTrazabilidadPorDocumento($idDocGen)
    {

        $xmlData = [
            'Trazabilidad' => [
                'IdDocGen' => $idDocGen
            ]
        ];

        // Llamamos al Stored Procedure de consulta
        $result = $this->executeStoredProcedure(
            'GetTrazabilidad',
            '', // O un nombre de transacción adecuado
            $xmlData,
            'Transaccion',
            true
        );


        $registrosSinFiltrar = $result['resultado']['Table'] ?? [];
        $registrosFiltrados = [];

        // 2. Filtramos los resultados aquí mismo, en PHP.
        // Recorremos cada registro que nos devolvió la base de datos.
        foreach ($registrosSinFiltrar as $registro) {
            // Solo nos quedamos con los registros cuyo 'IdDocGen' coincide exactamente con el que buscamos.
            if (isset($registro['IdDocGen']) && $registro['IdDocGen'] == $idDocGen) {
                $registrosFiltrados[] = $registro;
            }
        }

        // 3. Devolvemos únicamente el array que contiene los resultados correctos.
        return $registrosFiltrados;
    }

    /**
     * Obtiene el historial completo de una propuesta específica.
     *
     * @param int $idPropuesta El ID de la propuesta a consultar.
     * @return array El historial de trazabilidad.
     */
    // En: app/models/TrazabilidadModel.php

    public function obtenerTrazabilidadPorPropuesta($idPropuesta)
    {

        $xmlData = [
            'Trazabilidad' => [
                'IdPropuesta' => $idPropuesta
            ]
        ];

        $result = $this->executeStoredProcedure(
            'GetTrazabilidad',
            '',
            $xmlData,
            'Transaccion',
            true
        );



        $todosLosRegistros = $result['resultado']['Table'] ?? [];
        $registrosFiltrados = [];

        foreach ($todosLosRegistros as $registro) {
            if (isset($registro['IdPropuesta']) && $registro['IdPropuesta'] == $idPropuesta) {
                $registrosFiltrados[] = $registro;
            }
        }

        return $registrosFiltrados;
    }
}
