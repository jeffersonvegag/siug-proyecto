<?php

class PropuestaModel extends Model
{
    /**
     * Obtiene la lista simple de todos los proyectos para el menú desplegable.
     */
    public function obtenerTodas()
    {
        try {
            $spName = 'GetForms';
            $transaccion = 'GET_ALL_PROYECTOS';
            $response = $this->executeStoredProcedure($spName, $transaccion, [], 'Parametros', true);
            return $response['resultado']['Table'] ?? [];
        } catch (Exception $e) {
            throw new Exception("Error en PropuestaModel::obtenerTodas(): " . $e->getMessage());
        }
    }

    /**
     * Obtiene los datos completos y necesarios para el convenio de un ÚNICO proyecto.
     * VERSIÓN MEJORADA Y MÁS SEGURA.
     *
     * @param int $id El ID del proyecto (IdPropuesta).
     * @return array|null
     */
    public function obtenerDatosCompletosPorId($id)
    {
        try {
            $spName = 'GetForms';
            $params = ['IdPropuesta' => $id];
            
            // **MEJORA CLAVE**: Definimos la estructura completa que esperamos.
            // Esto garantiza que todos los campos siempre existan en la respuesta.
            $proyectoData = [
                'IdPropuesta' => $id,
                'NombreProyecto' => 'Sin Título',
                'NombreInstitucion' => '',
                'RepresentanteLegal' => '',
                'Direccion' => '',
                'TelefonoInstitucion' => '',
                'PaginaWeb' => '',
                'EmailInstitucion' => '',
                'Decano' => '',
                'EmailDecano' => '',
                'TelefonoDecano' => '',
                'ObjetivosEspecificosLista' => ''
            ];

            // 1. Obtener datos base del proyecto
            $baseResponse = $this->executeStoredProcedure($spName, 'GET_FORM1_PROYECTOS', $params, 'Parametros', false);
            if (!empty($baseResponse['resultado']['Table'][0])) {
                $baseData = $baseResponse['resultado']['Table'][0];
                $proyectoData['NombreProyecto'] = $baseData['Titulo'] ?? $proyectoData['NombreProyecto'];
                // Nos aseguramos de mantener el IdPropuesta original
                $proyectoData['IdPropuesta'] = $baseData['IdPropuesta'] ?? $id;
            }

            // 2. Obtener datos de la Institución Externa
            $institucionResponse = $this->executeStoredProcedure($spName, 'GET_FORM4_INSTITUCION_EXTERNA', $params, 'Parametros', false);
            if (!empty($institucionResponse['resultado']['Table'][0])) {
                $institucionData = $institucionResponse['resultado']['Table'][0];
                $proyectoData['NombreInstitucion'] = $institucionData['NombreInstitucion'] ?? '';
                $proyectoData['RepresentanteLegal'] = $institucionData['RepresentanteLegal'] ?? '';
                $proyectoData['Direccion'] = $institucionData['Direccion'] ?? '';
                $proyectoData['PaginaWeb'] = $institucionData['PaginaWeb'] ?? '';
                $proyectoData['TelefonoInstitucion'] = $institucionData['Telefono'] ?? '';
                $proyectoData['EmailInstitucion'] = $institucionData['Correo'] ?? '';
            }

            // 3. Obtener datos de las Unidades Académicas
            $unidadesResponse = $this->executeStoredProcedure($spName, 'GET_FORM4_UNIDADES_ACADEMICAS', $params, 'Parametros', false);
            if (!empty($unidadesResponse['resultado']['Table'][0])) {
                $unidadesData = $unidadesResponse['resultado']['Table'][0];
                $proyectoData['Decano'] = $unidadesData['Decano'] ?? '';
                $proyectoData['EmailDecano'] = $unidadesData['Correo'] ?? '';
                $proyectoData['TelefonoDecano'] = $unidadesData['Telefono'] ?? '';
            }

            // 4. Obtener Objetivos Específicos
            $objetivosResponse = $this->executeStoredProcedure($spName, 'GET_FORM8_OBJETIVOS_ESPECIFICOS_2', $params, 'Parametros', true);
            $objetivosLista = $objetivosResponse['resultado']['Table'] ?? [];
            
            if (!empty($objetivosLista)) {
                $items = '';
                foreach ($objetivosLista as $obj) {
                    if (!empty($obj['ObjetivoEspecifico'])) {
                        $items .= '<li>' . htmlspecialchars($obj['ObjetivoEspecifico']) . '</li>';
                    }
                }
                if ($items !== '') {
                    $proyectoData['ObjetivosEspecificosLista'] = '<ol>' . $items . '</ol>';
                }
            }

            return $proyectoData;

        } catch (Exception $e) {
            throw new Exception("Error en PropuestaModel::obtenerDatosCompletosPorId({$id}): " . $e->getMessage());
        }
    }
}