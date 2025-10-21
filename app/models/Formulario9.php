<?php

class Formulario9 extends Model
{
    private $pdo;

    public function __construct()
    {
        // $config = require ROOT_PATH . '/config/database.php';
        // try {
        //     $this->pdo = new PDO($config['dsn'], $config['usuario'], $config['clave']);
        //     $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // } catch (PDOException $e) {
        //     die("Error de conexión en Formulario9: " . $e->getMessage());
        // }
    }

    // ===================================================================
    // FUNCIÓN MAESTRA DE GUARDADO (UPSERT) - SIN CAMBIOS
    // ===================================================================
    public function guardarPaso9($id_proyecto, $datos)
    {
        // 1. Limpiar datos anteriores
        $data_delete = ['IdPropuesta' => $id_proyecto];
        $this->executeStoredProcedure('SetEstado', 'DELETE_Formulario9_ResultadosYProductos', $data_delete, 'Transaccion', true);
        $this->executeStoredProcedure('SetEstado', 'DELETE_Formulario9_ResultadosEsperados', $data_delete, 'Transaccion', true);

        // 2. Guardar la sección de "Resultados Esperados" (la tabla dinámica)
        if (!empty($datos['indicador']) && is_array($datos['indicador'])) {
            foreach ($datos['indicador'] as $objIndex => $indicadores) {
                foreach ($indicadores as $indIndex => $indicador) {
                    $resultado = $datos['resultado'][$objIndex][$indIndex] ?? '';
                    $productos = $datos['producto'][$objIndex][$indIndex] ?? [];
                    if (!empty($indicador) || !empty($resultado) || !empty(array_filter($productos))) {
                        $data = [
                            'Formulario9' => [
                                'IdPropuesta' => $id_proyecto,
                                'Indicador' => $indicador,
                                'Resultado' => $resultado,
                                'ProductosEsperados' => $productos,
                                'ObjetivoIndex' => $objIndex,
                                'UsuarioTrx' => $_SESSION['user']['username']
                            ]
                        ];
                        $this->executeStoredProcedure('SetForms', 'UPSERT_FORM9_RE', $data, 'Transaccion', true);
                        // $stmt->execute([$id_proyecto, $objIndex, $indicador, $resultado, json_encode($productos)]);
                    }
                }
            }
        }

        // 3. Guardar los campos estáticos (las tablas de conteos, textareas, etc.)
        // $sqlStatic = "INSERT INTO proyecto.Formulario9_ResultadosYProductos (IdProyecto, ComentariosResultados, PonenciasNacionales, PonenciasInternacionales, ArticulosCientificos, LibrosPublicados, CapitulosLibros, RevistasDivulgacion, OtrasPublicaciones, ComentariosPublicaciones, TalleresCapacitacion, ProductosTecnologicos, ProductosArtisticos, ProductosCulturales, ProductosSociales, ComentariosOtrosProductos, EfectosNuevasInvestigaciones, EfectosNuevasMetodologias, EfectosNuevosTrabajosTitulacion, ComentariosImpactosUg, ReferenciasCitadas, ComentariosReferencias) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        // $stmtStatic = $this->pdo->prepare($sqlStatic);
        $dataRP = [
            'Formulario9' => [
                'IdPropuesta' => $id_proyecto,
                'ComentariosResultados' => $datos['comentarios_resultados'] ?? null,
                'PonenciasNacionales' => $datos['ponencias_nacionales'] ?? 0,
                'PonenciasInternacionales' => $datos['ponencias_internacionales'] ?? 0,
                'ArticulosCientificos' => $datos['articulos_cientificos'] ?? 0,
                'LibrosPublicados' => $datos['libros_publicados'] ?? 0,
                'CapitulosLibros' => $datos['capitulos_libros'] ?? 0,
                'RevistasDivulgacion' => $datos['revistas_divulgacion'] ?? 0,
                'OtrasPublicaciones' => $datos['otras_publicaciones'] ?? 0,
                'ComentariosPublicaciones' => $datos['comentarios_publicaciones'] ?? null,
                'TalleresCapacitacion' => $datos['talleres_capacitacion'] ?? 0,
                'ProductosTecnologicos' => $datos['productos_tecnologicos'] ?? 0,
                'ProductosArtisticos' => $datos['productos_artisticos'] ?? 0,
                'ProductosCulturales' => $datos['productos_culturales'] ?? 0,
                'ProductosSociales' => $datos['productos_sociales'] ?? 0,
                'ComentariosOtrosProductos' => $datos['comentarios_otros_productos'] ?? null,
                'EfectosNuevasInvestigaciones' => $datos['efectos_nuevas_investigaciones'] ?? null,
                'EfectosNuevasMetodologias' => $datos['efectos_nuevas_metodologias'] ?? null,
                'EfectosNuevosTrabajosTitulacion' => $datos['efectos_nuevos_trabajos_titulacion'] ?? null,
                'ComentariosImpactosUg' => $datos['comentarios_impactos_ug'] ?? null,
                'ReferenciasCitadas' => $datos['referencias_citadas'] ?? null,
                'ComentariosReferencias' => $datos['comentarios_referencias'] ?? null,
                'UsuarioTrx' => $_SESSION['user']['username']
            ]
        ];

         $this->executeStoredProcedure('SetForms', 'UPSERT_FORM9', $dataRP, 'Transaccion', true);
    }

    // ===================================================================
    // FUNCIÓN PARA PREPARAR DATOS PARA LA VISTA (CORREGIDA)
    // ===================================================================
    public function obtenerDatosCompletosPaso9($id_proyecto)
    {
        // 1. Obtener los datos estáticos, usando alias para unificar a snake_case

        $data = ['IdPropuesta' => $id_proyecto];
        $datos_formateados = [];

        // Obtener datos de la tabla principal
        $metodologia = $this->executeStoredProcedure('GetForms', 'GET_FORM9_RESULTADOS_PRODUCTOS', $data, 'Transaccion', true);
        $metodologia = $metodologia['resultado']['Table'] ?? [];
        if (!empty($metodologia)) {
            $principales =  $metodologia[0];
            $datos_formateados['comentarios_resultados'] = $principales['ComentariosResultados'];
            $datos_formateados['ponencias_nacionales'] = $principales['PonenciasNacionales'];
            $datos_formateados['ponencias_internacionales'] = $principales['PonenciasInternacionales'];
            $datos_formateados['articulos_cientificos'] = $principales['ArticulosCientificos'];
            $datos_formateados['libros_publicados'] = $principales['LibrosPublicados'];
            $datos_formateados['capitulos_libros'] = $principales['CapitulosLibros'];
            $datos_formateados['revistas_divulgacion'] = $principales['RevistasDivulgacion'];
            $datos_formateados['otras_publicaciones'] = $principales['OtrasPublicaciones'];
            $datos_formateados['comentarios_publicaciones'] = $principales['ComentariosPublicaciones'];
            $datos_formateados['talleres_capacitacion'] = $principales['TalleresCapacitacion'];
            $datos_formateados['productos_tecnologicos'] = $principales['ProductosTecnologicos'];
            $datos_formateados['productos_artisticos'] = $principales['ProductosArtisticos'];
            $datos_formateados['productos_culturales'] = $principales['ProductosCulturales'];
            $datos_formateados['productos_sociales'] = $principales['ProductosSociales'];
            $datos_formateados['comentarios_otros_productos'] = $principales['ComentariosOtrosProductos'];
            $datos_formateados['efectos_nuevas_investigaciones'] = $principales['EfectosNuevasInvestigaciones'];
            $datos_formateados['efectos_nuevas_metodologias'] = $principales['EfectosNuevasMetodologias'];
            $datos_formateados['efectos_nuevos_trabajos_titulacion'] = $principales['EfectosNuevosTrabajosTitulacion'];
            $datos_formateados['comentarios_impactos_ug'] = $principales['ComentariosImpactosUg'];
            $datos_formateados['referencias_citadas'] = $principales['ReferenciasCitadas'];
            $datos_formateados['comentarios_referencias'] = $principales['ComentariosReferencias'];
        }



        // 2. Obtener los datos dinámicos (resultados esperados)
        $resultados_esperados = $this->executeStoredProcedure('GetForms', 'GET_FORM9_RESULTADOS_ESPERADOS_2', $data, 'Transaccion', true);
        $resultados_db = $resultados_esperados['resultado']['Table'] ?? [];

        // 3. Agrupar los resultados por el índice del objetivo
        $resultados_agrupados = [];
        foreach ($resultados_db as $row) {
            $objIndex = $row['ObjetivoIndex'];
            $productos = json_decode($row['ProductosEsperados'], true);
            $resultados_agrupados[$objIndex][] = [
                'indicador' => $row['Indicador'],
                'resultado' => $row['Resultado'],
                'productos' => is_array($productos) ? $productos : ['']
            ];
        }
        $datos_formateados['resultados_esperados'] = $resultados_agrupados;

        return $datos_formateados;
    }

    /**
     * Obtiene los resultados esperados de la tabla del paso 9.
     * @param int $idProyecto El ID del proyecto.
     * @return array Un array con los resultados.
     */
    public function obtenerResultadosEsperados($idProyecto)
    {
        $data = ['IdPropuesta' => $idProyecto];
        $resultados_esperados = $this->executeStoredProcedure('GetForms', 'GET_FORM9_RESULTADOS_ESPERADOS_1', $data, 'Transaccion', true);
        $resultados_db = $resultados_esperados['resultado']['Table'] ?? [];
        return  $resultados_db;
    }
}
