<?php

class Formulario8 extends Model
{
    private $pdo;

    public function __construct()
    {
        // $config = require ROOT_PATH . '/config/database.php';
        // try {
        //     $this->pdo = new PDO($config['dsn'], $config['usuario'], $config['clave']);
        //     $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // } catch (PDOException $e) {
        //     die("Error de conexión en Formulario8: " . $e->getMessage());
        // }
    }

    public function guardarPaso8($id_proyecto, $datos)
    {
        // ... (Este método ya es correcto, no necesita cambios)
        $data_delete = ['IdPropuesta' => $id_proyecto];
        $this->executeStoredProcedure('SetEstado', 'DELETE_Formulario8_ObjetivosMetodologia', $data_delete, 'Transaccion', true);
        $this->executeStoredProcedure('SetEstado', 'DELETE_Formulario8_ObjetivosEspecificos', $data_delete, 'Transaccion', true);
        $this->executeStoredProcedure('SetEstado', 'DELETE_Formulario8_Impactos', $data_delete, 'Transaccion', true);
        $this->executeStoredProcedure('SetEstado', 'DELETE_Formulario8_ComentarioImpactos', $data_delete, 'Transaccion', true);

        // 2. Guardar datos de la tabla principal
        $data_metodologia = [
            'Formulario8' => [
                'IdPropuesta' => $id_proyecto,
                'ObjetivoGeneral' =>  $datos['objetivo_general'] ?? null,
                'ComentariosObjGeneral' => $datos['comentarios_obj_general'] ?? null,
                'Metodologia' => $datos['metodologia'] ?? null,
                'ComentariosMetodologia' =>  $datos['comentarios_metodologia'] ?? null,
                'Dialogo' => $datos['dialogo'] ?? null,
                'ComentariosDialogo' =>  $datos['comentarios_dialogo'] ?? null,
                'Interculturalidad' =>  $datos['interculturalidad'] ?? null,
                'ComentariosInterculturalidad' =>  $datos['comentarios_interculturalidad'] ?? null,
                'SostenibilidadAmbiental' => $datos['sostenibilidad_ambiental'] ?? null,
                'ComentariosSostenibilidad' => $datos['comentarios_sostenibilidad'] ?? null,
                'EvaluacionImpacto' => $datos['evaluacion_impacto'] ?? null,
                'ComentariosEvaluacion' =>  $datos['comentarios_evaluacion'] ?? null,
                'LineaComparacion' =>  $datos['linea_comparacion'] ?? null,
                'Actividades' => $datos['actividades'] ?? null,
                'ComentariosActividades' =>  $datos['comentarios_actividades'] ?? null,
                'UsuarioTrx' => $_SESSION['user']['username']
            ]
        ];
        $this->executeStoredProcedure('SetForms', 'UPSERT_FORM8', $data_metodologia, 'Transaccion', true);

        // 3. Guardar Objetivos Específicos
        if (isset($datos['objetivos']) && is_array($datos['objetivos'])) {
            foreach ($datos['objetivos'] as $index => $objetivo) {
                $data_obj = [
                    'Formulario8' => [
                        'IdPropuesta' => $id_proyecto,
                        'ObjetivoEspecifico' => $objetivo,
                        'ComentariosObjetivoEspecifico' =>  $datos['comentarios_evaluacion'][$index] ?? null,
                        'UsuarioTrx' => $_SESSION['user']['username']
                    ]
                ];
                $resultad = $this->executeStoredProcedure('SetForms', 'UPSERT_FORM8_OE', $data_obj, 'Transaccion', true);
                                
            }
        }

        // 4. Guardar Impactos
        $tipo_map = [
            'ambiental' => 'Ambientales',
            'social'    => 'Sociales',
            'economico' => 'Económicos',
            'politico'  => 'Políticos',
            'cientifico' => 'Científicos'
        ];

        foreach ($tipo_map as $clave => $valor_db) {
            $nombre_input = "impacto_{$clave}_indicador";
            if (!empty($datos[$nombre_input]) && is_array($datos[$nombre_input])) {
                foreach ($datos[$nombre_input] as $indicador) {
                    if (!empty(trim($indicador)))

                        $data_impacto = [
                            'Formulario8' => [
                                'IdPropuesta' => $id_proyecto,
                                'TipoImpacto' => $valor_db,
                                'Indicador' => $indicador,
                                'UsuarioTrx' => $_SESSION['user']['username']
                            ]
                        ];
                    $this->executeStoredProcedure('SetForms', 'UPSERT_FORM8_I', $data_impacto, 'Transaccion', true);
                }
            }
        }

        // 5. Guardar Comentario de Impactos
        if (!empty(trim($datos['comentarios_impactos']))) {

            $data_comentario = [
                'Formulario8' => [
                    'IdPropuesta' => $id_proyecto,
                    'ComentariosImpactos' => $datos['comentarios_impactos'],
                    'UsuarioTrx' => $_SESSION['user']['username']
                ]
            ];
            $this->executeStoredProcedure('SetForms', 'UPSERT_FORM8_CI', $data_comentario, 'Transaccion', true);
            // $sqlComImpacto = "INSERT INTO proyecto.Formulario8_ComentarioImpactos (IdProyecto, ComentariosImpactos) VALUES (?, ?)";
            // $this->pdo->prepare($sqlComImpacto)->execute([$id_proyecto, $datos['comentarios_impactos']]);
        }
    }

    public function obtenerDatosCompletosPaso8($id_proyecto)
    {

        $data = ['IdPropuesta' => $id_proyecto];
        $datos_formateados = [];

        // Obtener datos de la tabla principal
        $metodologia = $this->executeStoredProcedure('GetForms', 'GET_FORM8_OBJETIVOS_METODOLOGIA', $data, 'Transaccion', true);
        $metodologia = $metodologia['resultado']['Table'] ?? [];
        if (!empty($metodologia)) {
            $principales =  $metodologia[0];
            $datos_formateados['objetivo_general'] = $principales['ObjetivoGeneral'];
            $datos_formateados['comentarios_obj_general'] = $principales['ComentariosObjGeneral'];
            $datos_formateados['metodologia'] = $principales['Metodologia'];
            $datos_formateados['comentarios_metodologia'] = $principales['ComentariosMetodologia'];
            $datos_formateados['dialogo'] = $principales['Dialogo'];
            $datos_formateados['comentarios_dialogo'] = $principales['ComentariosDialogo'];
            $datos_formateados['interculturalidad'] = $principales['Interculturalidad'];
            $datos_formateados['comentarios_interculturalidad'] = $principales['ComentariosInterculturalidad'];
            $datos_formateados['sostenibilidad_ambiental'] = $principales['SostenibilidadAmbiental'];
            $datos_formateados['comentarios_sostenibilidad'] = $principales['ComentariosSostenibilidad'];
            $datos_formateados['evaluacion_impacto'] = $principales['EvaluacionImpacto'];
            $datos_formateados['comentarios_evaluacion'] = $principales['ComentariosEvaluacion'];
            $datos_formateados['linea_comparacion'] = $principales['LineaComparacion'];
            $datos_formateados['actividades'] = $principales['Actividades'];
            $datos_formateados['comentarios_actividades'] = $principales['ComentariosActividades'];
        }

        // Objetivos específicos (sin cambios)

        $objetivos = $this->executeStoredProcedure('GetForms', 'GET_FORM8_OBJETIVOS_ESPECIFICOS_2', $data, 'Transaccion', true);
        $objetivos_db = $objetivos['resultado']['Table'] ?? [];
        // var_dump($objetivos_db);
        // die();
        foreach ($objetivos_db as $obj) {
            $datos_formateados['objetivos'][] = $obj['ObjetivoEspecifico'];
            $datos_formateados['comentarios_objetivos'][] = $obj['ComentariosObjetivoEspecifico'];
        }

        // --- INICIO DE LA CORRECCIÓN ---
        // Impactos (con lógica de mapeo inverso precisa)
        $impactos = $this->executeStoredProcedure('GetForms', 'GET_FORM8_IMPACTOS', $data, 'Transaccion', true);
        $impactos_db = $impactos['resultado']['Table'] ?? [];
        $impactos_agrupados = [];

        $reverse_map = [
            'Ambientales' => 'ambiental',
            'Sociales'    => 'social',
            'Económicos'  => 'economico',
            'Políticos'   => 'politico',
            'Científicos' => 'cientifico'
        ];

        foreach ($impactos_db as $imp) {
            $clave_formulario = $reverse_map[$imp['TipoImpacto']] ?? null;
            if ($clave_formulario) {
                $impactos_agrupados[$clave_formulario][] = $imp['Indicador'];
            }
        }
        $datos_formateados['impactos_agrupados'] = $impactos_agrupados;
        // --- FIN DE LA CORRECCIÓN ---

        // Comentario de impactos (sin cambios)
        $comentario = $this->executeStoredProcedure('GetForms', 'GET_FORM8_COMENTARIO_IMPACTOS', $data, 'Transaccion', true);
        $comentario = $comentario['resultado']['Table'] ?? [];
        $datos_formateados['comentarios_impactos'] = $comentario[0]['ComentariosImpactos'] ?? '';


        return $datos_formateados;
    }
    /**
     * Obtiene los objetivos específicos de un proyecto.
     * @param int $idProyecto El ID del proyecto.
     * @return array Un array con los objetivos.
     */
    public function obtenerObjetivosEspecificos($idProyecto)
    {
        $data = ['IdPropuesta' => $idProyecto];
        $objetivos = $this->executeStoredProcedure('GetForms', 'GET_FORM8_OBJETIVOS_ESPECIFICOS_1', $data, 'Transaccion', true);
        $objetivos = $objetivos['resultado']['Table'] ?? [];
        return $objetivos;
    }

    /**
     * Obtiene todos los indicadores de impacto de un proyecto.
     * @param int $idProyecto El ID del proyecto.
     * @return array Un array con los impactos.
     */
    public function obtenerImpactos($idProyecto)
    {
        $data = ['IdPropuesta' => $idProyecto];
        $impactos = $this->executeStoredProcedure('GetForms', 'GET_FORM8_IMPACTOS', $data, 'Transaccion', true);
        $impactos_db = $impactos['resultado']['Table'] ?? [];
        return $impactos_db;
    }
}
