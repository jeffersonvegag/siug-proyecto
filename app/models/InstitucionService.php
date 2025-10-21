<?php
require_once "../app/models/ApiClient.php";

class InstitucionService
{

    private $ApiClient;
    private $ADMIN_USER = "admin";
    private $ADMIN_PASS = "C0n3x10n@3xt3rnaUG*";
    private $API_APP = "2";
    private $API_USER = "userJWTApiGenericoExterno";
    private $API_PASS = "3G5CWaKXeW";


    public function __construct()
    {
        $this->ApiClient = new ApiClient();
    }

    /**
     * Obtiene el token de autenticación del sistema.
     */
    public function GetToken()
    {
        $tokenData = [
            "usuario" => $this->ADMIN_USER,
            "clave"   => $this->ADMIN_PASS
        ];

        $tokenResponse = $this->ApiClient->LlamadaApi(
            $this->ApiClient->apiObtenerUsuarioToken,
            $tokenData
        );

        // Validar si la llamada fue exitosa y si se recibió el token
        if (isset($tokenResponse['result'])) {
            return $tokenResponse['result'];
        } else {
            return [
                "success" => false,
                "mensaje" => "No se pudo generar el token de acceso",
                "detalle" => $tokenResponse
            ];
        }
    }

    public function GetTokenDB()
    {

        $tokenData = [
            "aplicacion" => $this->API_APP,
            "usuario"    => $this->API_USER,
            "clave"      => $this->API_PASS
        ];


        $tokenResponse = $this->ApiClient->LlamadaApi(
            $this->ApiClient->apiGetDbToken,
            $tokenData
        );

        if (!isset($tokenResponse['token'])) {
            return [
                "success" => false,
                "mensaje" => "La API externa no devolvió el campo 'token' esperado.",
                "detalle" => $tokenResponse
            ];
        }
        return $tokenResponse['token'];
    }

    /**
     * Obtiene la facultad del usuario autenticado.
     * @param string $username Nombre de usuario.
     * @param string $token Token de autorización.
     * @return object|array Resultado de la API o detalles de error.
     */
    public function GetFacultad($username, $token)
    {
        $bodyData = [
            "usuario" => $username,
            "token"   => $token
        ];

        return $this->ApiClient->LlamadaApi(
            $this->ApiClient->apiGetFacultad,
            $bodyData,
            $token
        );
    }

    /**
     * Obtiene las carreras para un usuario y facultad.
     * @param string $username Nombre de usuario.
     * @param int $idFacultad ID de la facultad.
     * @param string $token Token de autorización.
     */
    public function GetCarrera($username, $idFacultad, $token)
    {

        $bodyData = [
            "codFacultad" => $idFacultad,
            "usuario"     => $username,
            "token"       => $token
        ];

        return $this->ApiClient->LlamadaApi(
            $this->ApiClient->apiGetCarrera,
            $bodyData,
            $token
        );
    }

    /**
     * Obtiene los docentes para una facultad y carrera.
     * @param string $username Nombre de usuario.
     * @param int $idFacultad ID de la facultad.
     * @param int $idCarrera ID de la carrera.
     * @param string $token Token de autorización.
     */
    public function GetDocentes($username, $idFacultad, $idCarrera, $token)
    {
        $bodyData = [
            "codFacultad" => $idFacultad,
            "codCarrera"  => $idCarrera,
            "usuario"     => $username,
            "token"       => $token
        ];


        $response = $this->ApiClient->LlamadaApi(
            $this->ApiClient->apiGetDocente,
            $bodyData,
            $token
        );

        return $response;
    }

    public function GetData($sentencia, $xml, $transaccion, $token)
    {

        $bodyData = array(
            'conexion' => "SER_ACA_DESA_108", //ESTANDARDIZAR CONEXION
            'sentencia' => $sentencia, //MANDAR COMO PARA PARAMETRO
            'parametros' => [
                '@Transaccion' => $transaccion,
                '@Xml' => $xml

            ]
        );

        // var_dump($this->ApiClient->apiDbMetodo);
        // die();

        $response = $this->ApiClient->LlamadaApi(
            $this->ApiClient->apiDbMetodo,
            $bodyData,
            $token
        );

        return $response;
    }

    public function GetFormulario($xml, $transaccion, $token)
    {

        $bodyData = array(
            'conexion' => "SER_ACA_DESA_108", //ESTANDARDIZAR CONEXION
            'sentencia' => "OBTENER_FORMULARIOS", //MANDAR COMO PARA PARAMETRO
            'parametros' => [
                '@Transaccion' => $transaccion,
                '@Xml' => $xml

            ]
        );

        // var_dump($this->ApiClient->apiDbMetodo);
        // die();

        $response = $this->ApiClient->LlamadaApi(
            $this->ApiClient->apiDbMetodo,
            $bodyData,
            $token
        );

        return $response;
    }

    /**
     * Obtiene y procesa todos los datos necesarios para el formulario de creación.
     * @return array Datos estructurados del formulario.
     */
    public function getCrearFormData()
    {
        $token = $this->GetTokenDB();
        $formData = [
            'ejes_estrategicos' => [],
            'programas_articulacion' => [],
            'areas_data' => [],
            'subareas_json' => '{}',
            'ods' => [],
            'ejes_desarrollo' => [],
            'objetivos_pnd' => [],
            'objetivos_pedi' => [],
            'dominios' => [],
            'lineas_investigacion' => [],
            'cobertura' => [],
            'contexto' => [],
            'duracion' => []
        ];

        // --- Asumimos que aquí se hace la llamada para obtener el primer set de datos (Áreas, Subáreas, Ejes Estratégicos, Programas de Articulación) ---
        // (Utilizamos los datos y la lógica del ejemplo anterior)
        $response1 = $this->GetFormulario('<Transaccion><Propuesta><IdPropuesta>P001</IdPropuesta></Propuesta></Transaccion>', 'OBTENER_FORMULARIO_UNO', $token);

        if (isset($response1['estado']) && $response1['estado'] === 'OK' && isset($response1['resultado'])) {
            $resultado1 = $response1['resultado'];

            $formData['programas_articulacion'] = $resultado1['Table'] ?? [];
            $formData['ejes_estrategicos'] = $resultado1['Table1'] ?? [];
            $formData['areas_data'] = $resultado1['Table2'] ?? [];

            $subareas_data = $resultado1['Table3'] ?? [];
            $subareas_map = [];
            foreach ($subareas_data as $subarea) {
                $areaId = $subarea['IdArea'];
                if (!isset($subareas_map[$areaId])) {
                    $subareas_map[$areaId] = [];
                }
                $subareas_map[$areaId][] = ['id' => $subarea['IdSubarea'], 'name' => $subarea['Subarea']];
            }
            $formData['subareas_json'] = json_encode($subareas_map);

            $subareas_especificas_data = $resultado1['Table4'] ?? []; // Los datos de Table4 de la primera respuesta
            $subareas_especificas_map = [];
            foreach ($subareas_especificas_data as $especifica) {
                $subareaId = $especifica['IdSubarea']; // Mapear por IdSubarea
                if (!isset($subareas_especificas_map[$subareaId])) {
                    $subareas_especificas_map[$subareaId] = [];
                }
                $subareas_especificas_map[$subareaId][] = ['id' => $especifica['IdSubareaEspecifica'], 'name' => $especifica['SubareaEspecifica']];
            }
            $formData['especificas_json'] = json_encode($subareas_especificas_map); // Nueva variable JSON para la vista
        }

        // --- Carga de datos del Formulario 2 ---
        $response2 = $this->GetFormulario('...', 'OBTENER_FORMULARIO_DOS', $token);
        if (isset($response2['estado']) && $response2['estado'] === 'OK' && isset($response2['resultado'])) {
            $resultado2 = $response2['resultado'];

            $formData['ods'] = $resultado2['Table'] ?? [];
            $formData['ejes_desarrollo'] = $resultado2['Table1'] ?? [];

            // Agrupación de Objetivos Nacionales
            $obj_nac_data = $resultado2['Table2'] ?? [];
            $objetivo_map = [];
            foreach ($obj_nac_data as $obj) {
                $ejeId = $obj['IdEjes'];
                if (!isset($objetivo_map[$ejeId])) {
                    $objetivo_map[$ejeId] = [];
                }
                $objetivo_map[$ejeId][] = ['id' => $obj['IdObjP'], 'name' => $obj['ObjetivoP']];
            }
            // ▼▼▼ CORRECCIÓN 1: Cambiar el nombre de la variable ▼▼▼
            $formData['obj_nac_json'] = json_encode($objetivo_map);

            $formData['objetivos_pedi'] = $resultado2['Table3'] ?? [];
            $formData['dominios'] = $resultado2['Table4'] ?? [];

            // Agrupación de Líneas de Investigación
            $lineas_data = $resultado2['Table5'] ?? [];
            $lineas_map = [];
            foreach ($lineas_data as $linea) {
                $dominioId = $linea['IdDominio'];
                if (!isset($lineas_map[$dominioId])) {
                    $lineas_map[$dominioId] = [];
                }
                $lineas_map[$dominioId][] = ['id' => $linea['IdLineaInvest'], 'name' => $linea['LineaInvest']];
            }
            // ▼▼▼ CORRECCIÓN 2: Cambiar el nombre de la variable ▼▼▼
            $formData['lineas_json'] = json_encode($lineas_map);
        }

        $response3 = $this->GetFormulario('<Transaccion><Propuesta><IdPropuesta>P001</IdPropuesta></Propuesta></Transaccion>', 'OBTENER_FORMULARIO_TRES', $token);

        if (isset($response3['estado']) && $response3['estado'] === 'OK' && isset($response3['resultado'])) {
            $resultado3 = $response3['resultado'];

            $formData['cobertura'] = $resultado3['Table'] ?? [];
            $formData['contexto'] = $resultado3['Table1'] ?? [];
            $formData['duracion'] = $resultado3['Table2'] ?? [];
        }

        return $formData;
    }
}
