<?php

class ProyectosModel extends Model
{
    /**
     * @var PDO Conexión a la base de datos.
     */
    private $pdo;

    /**
     * @var InstitucionService Instancia del servicio de institución.
     */
    private $institucionService;

    /**
     * @var array Caché para los mapas de catálogos (datos de la API).
     */
    private static $cacheMapas = [];

    /**
     * Constructor de la clase.
     */
    public function __construct()
    {
        // $this->pdo = $this->conectar();
        if (!class_exists('InstitucionService')) {
            require_once ROOT_PATH . '/app/models/InstitucionService.php';
        }
        $this->institucionService = new InstitucionService();
    }


    /**
     * Obtiene TODOS los proyectos.
     * @return array
     */
    public function obtenerTodos()
    {
        $response = $this->executeStoredProcedure('GetForms', 'GET_ALL_PROYECTOS', [], 'Parametros', true);
        return $response['resultado']['Table'] ?? [];
    }

    /**
     * Carga los catálogos desde la API.
     * @return array
     */
    private function cargarMapasCatalogo()
    {
        if (!empty(self::$cacheMapas)) {
            return self::$cacheMapas;
        }

        $mapas = [];
        $listasCompletas = $this->institucionService->getCrearFormData();
        $tokenSistemaResponse = $this->institucionService->GetToken();

        $mapas['ejes_estrategicos'] = array_column($listasCompletas['ejes_estrategicos'] ?? [], 'EjeEstrategico', 'IdEje');
        $mapas['programas_articulacion'] = array_column($listasCompletas['programas_articulacion'] ?? [], 'Programa', 'IdProgamaA');
        $mapas['areas_data'] = array_column($listasCompletas['areas_data'] ?? [], 'Area', 'IdArea');
        $mapas['ods'] = array_column($listasCompletas['ods'] ?? [], 'ObjDesarrolloS', 'IdObjDS');
        $mapas['ejes_desarrollo'] = array_column($listasCompletas['ejes_desarrollo'] ?? [], 'Eje', 'IdEjes');
        $mapas['dominios'] = array_column($listasCompletas['dominios'] ?? [], 'Dominio', 'IdDominio');
        $mapas['cobertura'] = array_column($listasCompletas['cobertura'] ?? [], 'cobertura', 'IdCobertura');
        $mapas['contexto'] = array_column($listasCompletas['contexto'] ?? [], 'contexto', 'IdContexto');
        $mapas['duracion'] = array_column($listasCompletas['duracion'] ?? [], 'duracion', 'IdDuracion');

        $mapas['subareas'] = json_decode($listasCompletas['subareas_json'] ?? '{}', true);
        $mapas['especificas'] = json_decode($listasCompletas['especificas_json'] ?? '{}', true);
        $mapas['obj_nacionales'] = json_decode($listasCompletas['obj_nac_json'] ?? '{}', true);
        $mapas['lineas_investigacion'] = json_decode($listasCompletas['lineas_json'] ?? '{}', true);

        if ($tokenSistemaResponse) {
            $facultadesResponse = $this->institucionService->GetFacultad($_SESSION['user']['username'], $tokenSistemaResponse);
            $facultades = $facultadesResponse['dtResultado'] ?? [];
            $mapas['facultades'] = array_column($facultades, 'Facultad', 'CodFacultad');
            $mapas['carreras'] = [];
            $mapas['docentes'] = [];
            if (!empty($facultades)) {
                foreach ($facultades as $fac) {
                    $carrerasResponse = $this->institucionService->GetCarrera($_SESSION['user']['username'], $fac['CodFacultad'], $tokenSistemaResponse);
                    $carrerasFac = $carrerasResponse['dtResultado'] ?? [];
                    $mapas['carreras'] += array_column($carrerasFac, 'Carrera', 'CodCarrera');
                    if (!empty($carrerasFac)) {
                        foreach ($carrerasFac as $carr) {
                            $docentesResponse = $this->institucionService->GetDocentes($_SESSION['user']['username'], $fac['CodFacultad'], $carr['CodCarrera'], $tokenSistemaResponse);
                            $carrerasFacDocentes = $docentesResponse['dtResultado'] ?? [];
                            $mapas['docentes'] += array_column($carrerasFacDocentes, 'Nombres', 'CedulaDocente');
                        }
                    }
                }
            }
        }

        self::$cacheMapas = $mapas;
        return self::$cacheMapas;
    }

    /**
     * Obtiene todos los datos de un proyecto para el PDF.
     * @param int $id_proyecto El ID del proyecto.
     * @return object|false
     */
    public static function obtenerProyectoCompleto($id_proyecto)
    {
        $modelo = new self();
        $proyecto = new stdClass();

        try {
            $mapas = $modelo->cargarMapasCatalogo();
            $params = ['IdPropuesta' => $id_proyecto];

            $proyecto_base = $modelo->executeStoredProcedureAndFetch('GetForms', 'GET_ALL_PROYECTOS', $params);


            if (!$proyecto_base || !isset($proyecto_base->IdPropuesta)) {
                return false;
            }

            foreach ($proyecto_base as $key => $value) {
                $finalKey = $key === 'IdPropuesta';
                $proyecto->$finalKey = $value;
            }

            // OBTENER DATOS DE TODOS LOS FORMULARIOS
            $proyecto->datos_generales = $modelo->executeStoredProcedureAndFetch('GetForms', 'GET_FORM1_DATOS_GENERALES', $params);
            $proyecto->programas_articulados = $modelo->executeStoredProcedureAndFetchAll('GetForms', 'GET_FORM1_PROGRAMAS_ARTICULADOS', $params);
            $f2_data = $modelo->executeStoredProcedureAndFetch('GetForms', 'GET_FORM2_OBJETIVOS_ALINEACION', $params);
            $f3_gen = $modelo->executeStoredProcedureAndFetch('GetForms', 'GET_FORM3_DATOS_GENERALES', $params);
            $f3_cob_result = $modelo->executeStoredProcedureAndFetch('GetForms', 'GET_FORM3_ID_COBERTURA', $params);
            $f3_cob = new stdClass();
            $f3_cob->IdCobertura = $f3_cob_result->IdCobertura ?? null;
            $proyecto->perfil_egreso = $modelo->executeStoredProcedureAndFetchAll('GetForms', 'GET_FORM3_PERFIL_EGRESO', $params);
            $proyecto->unidades_academicas = $modelo->executeStoredProcedureAndFetchAll('GetForms', 'GET_FORM4_UNIDADES_ACADEMICAS', $params);
            $proyecto->institucion_externa = $modelo->executeStoredProcedureAndFetch('GetForms', 'GET_FORM4_INSTITUCION_EXTERNA', $params);
            $proyecto->unidades_cooperantes = $modelo->executeStoredProcedureAndFetchAll('GetForms', 'GET_FORM4_UNIDADES_COOPERANTES', $params);
            $proyecto->directorProyecto = $modelo->executeStoredProcedureAndFetchAll('GetForms', 'GET_FORM4_DIRECTOR_PROYECTO', $params);
            $proyecto->aliadoEstrategico = $modelo->executeStoredProcedureAndFetchAll('GetForms', 'GET_FORM4_ALIADO_ESTRATEGICO', $params);
            $proyecto->directores = $modelo->executeStoredProcedureAndFetchAll('GetForms', 'GET_FORM5_DIRECTOR_PROYECTO', $params);
            $proyecto->tutores = $modelo->executeStoredProcedureAndFetchAll('GetForms', 'GET_FORM5_DOCENTES_TUTORES', $params);
            $proyecto->estudiantes_carrera = $modelo->executeStoredProcedureAndFetchAll('GetForms', 'GET_FORM5_ESTUDIANTES_PROYECTO', $params);
            $proyecto->estudiantes_programas = $modelo->executeStoredProcedureAndFetchAll('GetForms', 'GET_FORM5_ESTUDIANTES_PROGRAMAS', $params);
            $proyecto->estudiantes_ciclo = $modelo->executeStoredProcedureAndFetchAll('GetForms', 'GET_FORM5_ESTUDIANTES_CICLOS', $params);
            $proyecto->acciones_contribucion = $modelo->executeStoredProcedureAndFetchAll('GetForms', 'GET_FORM5_INCLUSION_ESTUDIANTES', $params);
            $proyecto->detalle_proyecto = $modelo->executeStoredProcedureAndFetch('GetForms', 'GET_FORM6_DETALLE_PROYECTO', $params);

            // CORRECCIÓN DE ERROR TIPOGRÁFICO: 'POBLacion' a 'POBLACION'
            $proyecto->poblacion_objetivo = $modelo->executeStoredProcedureAndFetch('GetForms', 'GET_FORM7_POBLACION_OBJETIVO', $params);
            $todos_beneficiarios = $modelo->executeStoredProcedureAndFetchAll('GetForms', 'GET_FORM7_BENEFICIARIOS', $params);
            $proyecto->beneficiarios_directos = array_filter($todos_beneficiarios, fn($b) => isset($b->Tipo) && $b->Tipo === 'Directo');
            $proyecto->beneficiarios_indirectos = array_filter($todos_beneficiarios, fn($b) => isset($b->Tipo) && $b->Tipo === 'Indirecto');
            $proyecto->objetivos_metodologia = $modelo->executeStoredProcedureAndFetch('GetForms', 'GET_FORM8_OBJETIVOS_METODOLOGIA', $params);
            $proyecto->objetivos_especificos = $modelo->executeStoredProcedureAndFetchAll('GetForms', 'GET_FORM8_OBJETIVOS_ESPECIFICOS_2', $params);
            $proyecto->impactos = $modelo->executeStoredProcedureAndFetchAll('GetForms', 'GET_FORM8_IMPACTOS', $params);
            $proyecto->resultados_productos = $modelo->executeStoredProcedureAndFetch('GetForms', 'GET_FORM9_RESULTADOS_PRODUCTOS', $params);
            $proyecto->resultados_esperados = $modelo->executeStoredProcedureAndFetchAll('GetForms', 'GET_FORM9_RESULTADOS_ESPERADOS_2', $params);
            $proyecto->matriz_seguimiento = $modelo->executeStoredProcedureAndFetchAll('GetForms', 'GET_FORM10_MATRIZ_SEGUIMIENTO', $params);
            $proyecto->cronograma = $modelo->executeStoredProcedureAndFetchAll('GetForms', 'GET_FORM10_CRONOGRAMA', $params);
            $proyecto->presupuesto = $modelo->executeStoredProcedureAndFetchAll('GetForms', 'GET_FORM10_PRESUPUESTO', $params);
            $proyecto->declaracion = $modelo->executeStoredProcedureAndFetch('GetForms', 'GET_FORM11_DECLARACION_FINAL', $params);

            // *** BLOQUE DE MAPEO REINCORPORADO Y CORREGIDO ***
            // Asignar IDs a variables seguras para evitar errores
            $idEjeEstrategico = $proyecto->datos_generales->EjeEstrategico ?? null;
            $idAreaConocimiento = $proyecto->datos_generales->AreaConocimiento ?? null;
            $idSubareaConocimiento = $proyecto->datos_generales->SubareaConocimiento ?? null;
            $idSubareaEspecifica = $proyecto->datos_generales->SubareaEspecifica ?? null;
            $idObjetivoOds = $f2_data->IdObjetivoOds ?? null;
            $idEjePlan = $f2_data->IdEjePlan ?? null;
            $idObjNacional = $f2_data->IdObjetivoNacional ?? null;
            $idDominioCientifico = $f2_data->IdDominioCientifico ?? null;
            $idLineaInstitucional = $f2_data->IdLineaInstitucional ?? null;
            $idCobertura = $f3_cob->IdCobertura ?? null;
            $idContexto = $f3_gen->IdContexto ?? null;
            $idDuracion = $f3_gen->IdDuracion ?? null;

            // Mapeo seguro usando las variables
            $proyecto->datos_generales->EjeEstrategicoTexto = $mapas['ejes_estrategicos'][$idEjeEstrategico] ?? 'N/A';
            $proyecto->datos_generales->AreaConocimientoTexto = $mapas['areas_data'][$idAreaConocimiento] ?? 'N/A';
            $subareasDeArea = $mapas['subareas'][$idAreaConocimiento] ?? [];
            $proyecto->datos_generales->SubareaConocimientoTexto = array_reduce($subareasDeArea, fn($c, $i) => ($i['id'] ?? null) == $idSubareaConocimiento ? $i['name'] : $c, 'N/A');
            $especificasDeSubarea = $mapas['especificas'][$idSubareaConocimiento] ?? [];
            $proyecto->datos_generales->SubareaEspecificaTexto = array_reduce($especificasDeSubarea, fn($c, $i) => ($i['id'] ?? null) == $idSubareaEspecifica ? $i['name'] : $c, 'N/A');
            $proyecto->datos_generales->ObjetivoOdsTexto = $mapas['ods'][$idObjetivoOds] ?? 'N/A';
            $proyecto->datos_generales->EjePlanTexto = $mapas['ejes_desarrollo'][$idEjePlan] ?? 'N/A';
            $objetivosDeEje = $mapas['obj_nacionales'][$idEjePlan] ?? [];
            $proyecto->datos_generales->ObjetivoNacionalTexto = array_reduce($objetivosDeEje, fn($c, $i) => ($i['id'] ?? null) == $idObjNacional ? $i['name'] : $c, 'N/A');
            $proyecto->datos_generales->DominioCientificoTexto = $mapas['dominios'][$idDominioCientifico] ?? 'N/A';
            $lineasDeDominio = $mapas['lineas_investigacion'][$idDominioCientifico] ?? [];
            $proyecto->datos_generales->LineaInstitucionalTexto = array_reduce($lineasDeDominio, fn($c, $i) => ($i['id'] ?? null) == $idLineaInstitucional ? $i['name'] : $c, 'N/A');
            $proyecto->datos_generales->CoberturaTexto = $mapas['cobertura'][$idCobertura] ?? 'N/A';
            $proyecto->datos_generales->ContextoTexto = $mapas['contexto'][$idContexto] ?? 'N/A';
            $proyecto->datos_generales->DuracionTexto = $mapas['duracion'][$idDuracion] ?? 'N/A';

            foreach ($proyecto->programas_articulados as $prog) {
                $prog->ProgramaBaseTexto = $mapas['programas_articulacion'][$prog->IdProgramaBase] ?? 'N/A';
            }
            foreach ($proyecto->perfil_egreso as $p) {
                $p->FacultadTexto = $mapas['facultades'][$p->IdFacultad] ?? 'N/A';
                $p->CarreraTexto = $mapas['carreras'][$p->IdCarrera] ?? 'N/A';
            }
            foreach ($proyecto->unidades_academicas as $ua) {
                $ua->FacultadTexto = $mapas['facultades'][$ua->IdFacultad] ?? 'N/A';
                $ua->CarreraTexto = $mapas['carreras'][$ua->IdCarrera] ?? 'N/A';
            }
            foreach ($proyecto->directorProyecto as $dp) {
                $dp->FacultadTexto = $mapas['facultades'][$dp->FacultadDirector] ?? 'N/A';
                $dp->CarreraTexto = $mapas['carreras'][$dp->CarreraDirector] ?? 'N/A';
                $dp->NombreDirectorTexto = $mapas['docentes'][$dp->NombreDirector] ?? 'N/A';
            }
            foreach ($proyecto->unidades_cooperantes as $uc) {
                $uc->FacultadCoopTexto = $mapas['facultades'][$uc->FacultadCoop] ?? 'N/A';
                $uc->CarreraCoopTexto = $mapas['carreras'][$uc->CarreraCoop] ?? 'N/A';
                $uc->DocenteCoopTexto = $mapas['docentes'][$uc->DocenteCoop] ?? 'N/A';
            }
            foreach ($proyecto->directores as $d) {
                $d->NombreDirectorTexto = $mapas['docentes'][$d->CedulaDirector] ?? 'N/A';
                $d->FacultadTexto = $mapas['facultades'][$d->Facultad] ?? $d->Facultad ?? 'N/A';
                $d->CarreraTexto = $mapas['carreras'][$d->Carrera] ?? $d->Carrera ?? 'N/A';
            }
            foreach ($proyecto->tutores as $t) {
                $t->NombreTexto = $mapas['docentes'][$t->Cedula] ?? 'N/A';
                $t->FacultadTexto = $mapas['facultades'][$t->Facultad] ?? 'N/A';
                $t->CarreraTexto = $mapas['carreras'][$t->Carrera] ?? 'N/A';
            }
            foreach ($proyecto->estudiantes_carrera as $ec) {
                $ec->FacultadTexto = $mapas['facultades'][$ec->Facultad] ?? 'N/A';
                $ec->CarreraTexto = $mapas['carreras'][$ec->Carrera] ?? 'N/A';
            }
        } catch (Throwable $e) {
            error_log("ERROR FATAL en obtenerProyectoCompleto: " . $e->getMessage() . " en la línea " . $e->getLine() . " del archivo " . $e->getFile());
            $proyecto = $proyecto ?? new stdClass();
            $proyecto->datos_generales = $proyecto->datos_generales ?? new stdClass();
            $proyecto->datos_generales->Titulo = "Error al generar el proyecto: " . $e->getMessage();
            $proyecto->error = true;
            return $proyecto;
        }

        return $proyecto;
    }

    /**
     * Funciones auxiliares CORREGIDAS para devolver siempre objetos.
     */
    private function executeStoredProcedureAndFetch($spName, $transaction, $params)
    {
        $response = $this->executeStoredProcedure($spName, $transaction, $params, 'Parametros', true);
        $resultArray = $response['resultado']['Table'][0] ?? new stdClass();
        return (object) $resultArray; // Devuelve un objeto stdClass
    }

    private function executeStoredProcedureAndFetchAll($spName, $transaction, $params)
    {
        $response = $this->executeStoredProcedure($spName, $transaction, $params, 'Parametros', true);
        $results = $response['resultado']['Table'] ?? [];
        if (empty($results)) {
            return [];
        }
        // Convierte cada array asociativo en un objeto stdClass
        return array_map(function ($row) {
            return (object) $row;
        }, $results);
    }

    public function actualizarEstado(int $idProyecto, string $estado): bool
    {
        // 1. Validación (se mantiene, es una excelente práctica de seguridad)
        $estadosValidos = ['BORRADOR', 'REVISADO', 'CORREGIDO', 'APROBADO', 'RECHAZADO'];
        $estadoNormalizado = strtoupper(trim($estado));

        if (!in_array($estadoNormalizado, $estadosValidos, true)) {
            return false; // El estado no es válido, no se hace nada.
        }

        // 2. Preparar los datos para el Stored Procedure
        // La estructura debe coincidir con los parámetros que espera el SP.
        // Asumimos que espera 'IdPropuesta' y 'Estado'.
        $data = [
            'IdPropuesta' => $idProyecto,
            'Estado' => $estadoNormalizado,
            'UsuarioTrx' => $_SESSION['user']['username'] ?? 'sistema' // Es bueno registrar quién hace el cambio
        ];

        // 3. Ejecutar el SP a través del método heredado de la clase Model
        // Reemplaza 'SetPropuesta' y 'UPDATE_ESTADO' si tu SP o transacción se llaman diferente.
        $response = $this->executeStoredProcedure('SetEstado', 'UPDATE_ESTADO_PROPUESTA', $data, 'Transaccion', true);

        if ($response && isset($response['estado']) && $response['estado'] === 'OK') {
            // --- INICIO: REGISTRO DE TRAZABILIDAD ---
            require_once ROOT_PATH . '/app/models/TrazabilidadModel.php';
            $trazabilidadModel = new TrazabilidadModel();

            $usuario = $_SESSION['user']['nombre'] . ' ' . ($_SESSION['user']['apellidos'] ?? '');
            $rol = $_SESSION['user']['rol'] ?? 'Rol Desconocido';
            $accion = $estadoNormalizado;
            $comentario = "El estado de la propuesta fue modificado.";


            // Llamada correcta: null para idDocGen, y el ID para idPropuesta
            $trazabilidadModel->guardarAccion(null, $usuario, $rol, $accion, $comentario, $idProyecto);
            // --- FIN: REGISTRO DE TRAZABILIDAD ---


            return true;
        }

        // Si la respuesta de la API no es 'OK' o falla, devuelve 'false'.
        return false;
    }
}
