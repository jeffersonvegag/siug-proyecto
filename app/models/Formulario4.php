<?php

class Formulario4 extends Model
{
    private $pdo;

    public function __construct() {}

    // ===================================================================
    // FUNCIÓN MAESTRA DE GUARDADO (UPSERT) PARA EL PASO 4 (ACTUALIZADA)
    // ===================================================================
    public function guardarPaso4($idProyecto, $data, $file_data = []) // Se añade $file_data
    {
        // --- 1. Unidad Académica (1 fila por proyecto) ---
        // $stmt_check_ua = $this->pdo->prepare("SELECT IdUnidadAcademica FROM proyecto.Formulario4_UnidadesAcademicas WHERE IdProyecto = ?");
        // $stmt_check_ua->execute([$idProyecto]);
        

        $data_ua_id = ['IdPropuesta' => $idProyecto];

        $existe_ua = $this->executeStoredProcedure('GetForms', 'GET_FORM4_ID_UNIDAD_ACADEMICA', $data_ua_id, 'Transaccion', true);
        $existe_ua = $existe_ua['resultado']['Table'];


        if ($existe_ua) {
            $data_ua = [
                'Formulario4' => [
                    'IdUnidadAcademica' => $existe_ua[0]['IdUnidadAcademica'],
                    'IdPropuesta' => $idProyecto,
                    'IdFacultad' => $data['facultad_decano'],
                    'IdCarrera' => $data['carrera_decano'],
                    'Decano' => $data['decano_decano'],
                    'Telefono' => $data['decano_telefono'],
                    'Correo' =>  $data['decano_correo'],
                    'UsuarioTrx' => $_SESSION['user']['username']

                ]
            ];

            $this->executeStoredProcedure('SetForms', 'UPSERT_FORM4_UA', $data_ua, 'Transaccion', true);

            // $stmt_ua = $this->pdo->prepare("UPDATE proyecto.Formulario4_UnidadesAcademicas SET IdFacultad = ?, IdCarrera = ?, Decano = ?, Correo = ?, Telefono = ? WHERE IdProyecto = ?");
            // $stmt_ua->execute([$data['facultad_decano'], $data['carrera_decano'], $data['decano_decano'], $data['decano_correo'], $data['decano_telefono'], $idProyecto]);
        } else {
            $data_ua = [
                'Formulario4' => [
                    'IdPropuesta' => $idProyecto,
                    'IdFacultad' => $data['facultad_decano'],
                    'IdCarrera' => $data['carrera_decano'],
                    'Decano' => $data['decano_decano'],
                    'Correo' =>  $data['decano_correo'],
                    'Telefono' => $data['decano_telefono'],
                    'UsuarioTrx' => $_SESSION['user']['username']
                    
                ]
            ];

            $this->executeStoredProcedure('SetForms', 'UPSERT_FORM4_UA', $data_ua, 'Transaccion', true);
        }

        // --- 2. Director del Proyecto (múltiples filas, se borran y reinsertan) ---
        $this->executeStoredProcedure('SetEstado', 'DELETE_Formulario4_DirectorProyecto', $data_ua_id, 'Transaccion', true);
        if (!empty($data['facultad_director'])) {
            for ($i = 0; $i < count($data['facultad_director']); $i++) {

                $data_dp = [
                    'Formulario4' => [
                        'IdPropuesta' => $idProyecto,
                        'FacultadDirector' => $data['facultad_director'][$i],
                        'CarreraDirector' => $data['carrera_director'][$i],
                        'NombreDirector' => $data['nombre_director'][$i],
                        'Correo' => $data['director_correo'][$i],
                        'Telefono' => $data['director_telefono'][$i],
                        'UsuarioTrx' => $_SESSION['user']['username']

                    ]
                ];

                $this->executeStoredProcedure('SetForms', 'UPSERT_FORM4_DP', $data_dp, 'Transaccion', true);

                // $stmt_dir = $this->pdo->prepare("INSERT INTO proyecto.Formulario4_DirectorProyecto (IdProyecto, FacultadDirector, CarreraDirector, NombreDirector, Correo, Telefono) VALUES (?, ?, ?, ?, ?, ?)");
                // $stmt_dir->execute([$idProyecto, $data['facultad_director'][$i], $data['carrera_director'][$i], $data['nombre_director'][$i], $data['director_correo'][$i], $data['director_telefono'][$i]]);
            }
        }

        // $stmt_check_ie = $this->pdo->prepare("SELECT IdInstitucionExterna, RutaImagen FROM proyecto.Formulario4_InstitucionExterna WHERE IdProyecto = ?");
        // $stmt_check_ie->execute([$idProyecto]);
        $institucion_existente = $this->executeStoredProcedure('GetForms', 'GET_FORM4_INSTITUCION_EXTERNA', $data_ua_id, 'Transaccion', true);
        $institucion_existente = $institucion_existente['resultado']['Table'];

        $ruta_imagen_a_guardar = $institucion_existente['RutaImagen'] ?? null;

        // Lógica para manejar la subida de la imagen SOLO si hay un nuevo archivo
        if (isset($file_data['logo_proyecto']) && $file_data['logo_proyecto']['error'] === UPLOAD_ERR_OK) {
            $directorio_subida = ROOT_PATH . '/public/uploads/';
            if (!is_dir($directorio_subida)) {
                mkdir($directorio_subida, 0777, true);
            }
            $nombre_archivo = uniqid() . '_' . basename($file_data['logo_proyecto']['name']);
            $ruta_completa = $directorio_subida . $nombre_archivo;

            if (move_uploaded_file($file_data['logo_proyecto']['tmp_name'], $ruta_completa)) {
                $ruta_imagen_a_guardar = '/public/uploads/' . $nombre_archivo;
            }
        }
        // --- FIN: LÓGICA DE IMAGEN ---


        // --- 3. Institución Externa (1 fila por proyecto) - Actualizada con RutaImagen ---
        if ($institucion_existente) {
            // $stmt_ie = $this->pdo->prepare(
            //     "UPDATE proyecto.Formulario4_InstitucionExterna 
            //      SET NombreInstitucion = ?, RepresentanteLegal = ?, Direccion = ?, Telefono = ?, Correo = ?, PaginaWeb = ?, RutaImagen = ? 
            //      WHERE IdProyecto = ?"
            // );
            // $stmt_ie->execute([$data['externa_nombre'], $data['externa_repres_nombre'], $data['externa_dir'], $data['externa_tel'], $data['externa_correo'], $data['externa_web'], $ruta_imagen_a_guardar, $idProyecto]);
            $data_IE = [
                'Formulario4' => [
                    'IdInstitucionExterna' =>  $institucion_existente['RutaImagen'],
                    'IdPropuesta' => $idProyecto,
                    'NombreInstitucion' => $data['externa_nombre'],
                    'RepresentanteLegal' => $data['externa_repres_nombre'],
                    'Direccion' => $data['externa_dir'],
                    'Telefono' => $data['externa_tel'],
                    'Correo' => $data['externa_correo'],
                    'PaginaWeb' => $data['externa_web'],
                    'RutaImagen' => $ruta_imagen_a_guardar,
                    'UsuarioTrx' => $_SESSION['user']['username']
                ]
            ];

            $this->executeStoredProcedure('SetForms', 'UPSERT_FORM4_IE', $data_IE, 'Transaccion', true);
        } else {
            // $stmt_ie = $this->pdo->prepare(
            //     "INSERT INTO proyecto.Formulario4_InstitucionExterna 
            //      (IdProyecto, NombreInstitucion, RepresentanteLegal, Direccion, Telefono, Correo, PaginaWeb, RutaImagen) 
            //      VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
            // );
            // $stmt_ie->execute([$idProyecto, $data['externa_nombre'], $data['externa_repres_nombre'], $data['externa_dir'], $data['externa_tel'], $data['externa_correo'], $data['externa_web'], $ruta_imagen_a_guardar]);

            $data_IE = [
                'Formulario4' => [
                    'IdPropuesta' => $idProyecto,
                    'NombreInstitucion' => $data['externa_nombre'],
                    'RepresentanteLegal' => $data['externa_repres_nombre'],
                    'Direccion' => $data['externa_dir'],
                    'Telefono' => $data['externa_tel'],
                    'Correo' => $data['externa_correo'],
                    'PaginaWeb' => $data['externa_web'],
                    'RutaImagen' => $ruta_imagen_a_guardar,
                    'UsuarioTrx' => $_SESSION['user']['username']
                ]
            ];

            $this->executeStoredProcedure('SetForms', 'UPSERT_FORM4_IE', $data_IE, 'Transaccion', true);
        }

        // --- 4. Unidades Cooperantes (múltiples filas, se borran y reinsertan) ---
        $this->executeStoredProcedure('SetEstado', 'DELETE_Formulario4_UnidadesCooperantes', $data_ua_id, 'Transaccion', true);

        if (!empty($data['facultad_coop'])) {
            for ($i = 0; $i < count($data['facultad_coop']); $i++) {
                // $stmt_coop = $this->pdo->prepare("INSERT INTO proyecto.Formulario4_UnidadesCooperantes (IdProyecto, FacultadCoop, CarreraCoop, DocenteCoop, Correo, Telefono) VALUES (?, ?, ?, ?, ?, ?)");
                // $stmt_coop->execute([$idProyecto, $data['facultad_coop'][$i], $data['carrera_coop'][$i],, $data['correo'][$i], $data['telefono'][$i]]);
                $data_FC = [
                    'Formulario4' => [
                        'IdPropuesta' => $idProyecto,
                        'FacultadCoop' =>  $data['facultad_coop'][$i],
                        'CarreraCoop' => $data['carrera_coop'][$i],
                        'DocenteCoop' =>  $data['docente_coop'][$i],
                        'Telefono' => $data['telefono'][$i],
                        'Correo' => $data['correo'][$i],
                        'UsuarioTrx' => $_SESSION['user']['username']
                    ]
                ];
                $this->executeStoredProcedure('SetForms', 'UPSERT_FORM4_UC', $data_FC, 'Transaccion', true);
            }
        }

        // --- 5. Aliado Estratégico (1 fila por proyecto) ---
        $stmt_check_aliado = $this->executeStoredProcedure('GetForms', 'GET_FORM4_ALIADO_ESTRATEGICO', $data_ua_id, 'Transaccion', true);
        $existe_aliado = $stmt_check_aliado['resultado']['Table'];

        if ($existe_aliado) {
            $data_ae = [
                'Formulario4' => [
                    'IdAliadoEstrategico' => $existe_aliado['IdAliadoEstrategico'],
                    'IdPropuesta' => $idProyecto,
                    'NombreInstitucion' => $data['aliado_nombre'],
                    'RepresentanteLegal' => $data['aliado_repres_nombre'],
                    'Direccion' => $data['aliado_direccion'],
                    'Telefono' => $data['aliado_tel'],
                    'Correo' => $data['aliado_correo'],
                    'PaginaWeb' => $data['aliado_web'],
                    'Contribucion' => $data['aliado_contribucion'],
                    'UsuarioTrx' => $_SESSION['user']['username']
                ]
            ];

            $this->executeStoredProcedure('SetForms', 'UPSERT_FORM4_AE', $data_ae, 'Transaccion', true);

            // $stmt_aliado = $this->pdo->prepare("UPDATE proyecto.Formulario4_AliadoEstrategico SET NombreInstitucion = ?, RepresentanteLegal = ?, Direccion = ?, Telefono = ?, Correo = ?, PaginaWeb = ?, Contribucion = ? WHERE IdProyecto = ?");
            // $stmt_aliado->execute([$data['aliado_nombre'], $data['aliado_repres_nombre'], $data['aliado_direccion'], $data['aliado_tel'], $data['aliado_correo'], $data['aliado_web'], $data['aliado_contribucion'], $idProyecto]);
        } else {
            $data_ae = [
                'Formulario4' => [
                    'IdPropuesta' => $idProyecto,
                    'NombreInstitucion' => $data['aliado_nombre'],
                    'RepresentanteLegal' => $data['aliado_repres_nombre'],
                    'Direccion' => $data['aliado_direccion'],
                    'Telefono' => $data['aliado_tel'],
                    'Correo' => $data['aliado_correo'],
                    'PaginaWeb' => $data['aliado_web'],
                    'Contribucion' => $data['aliado_contribucion'],
                    'UsuarioTrx' => $_SESSION['user']['username']
                ]
            ];

            $this->executeStoredProcedure('SetForms', 'UPSERT_FORM4_AE', $data_ae, 'Transaccion', true);

            // $stmt_aliado = $this->pdo->prepare("INSERT INTO proyecto.Formulario4_AliadoEstrategico (IdProyecto, NombreInstitucion, RepresentanteLegal, Direccion, Telefono, Correo, PaginaWeb, Contribucion) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            // $stmt_aliado->execute([$idProyecto, $data['aliado_nombre'], $data['aliado_repres_nombre'], $data['aliado_direccion'], $data['aliado_tel'], $data['aliado_correo'], $data['aliado_web'], $data['aliado_contribucion']]);
        }
    }

    // ===================================================================
    // FUNCIÓN PARA RECUPERAR Y FORMATEAR LOS DATOS PARA LA VISTA (ACTUALIZADA)
    // ===================================================================
    public function obtenerDatosCompletosPaso4($idProyecto)
    {
        $data_ua_id = ['IdPropuesta' => $idProyecto];
        $datos_formateados = [];
        // 1. Obtener Unidad Académica
        $stmt_ua = $this->executeStoredProcedure('GetForms', 'GET_FORM4_UNIDADES_ACADEMICAS', $data_ua_id, 'Transaccion', true);
        $stmt_ua = $stmt_ua['resultado']['Table'][0] ?? [];
        if ($ua = $stmt_ua) {
            $datos_formateados['facultad_decano'] = $ua['IdFacultad'];
            $datos_formateados['carrera_decano'] = $ua['IdCarrera'];
            $datos_formateados['decano_decano'] = $ua['Decano'];
            $datos_formateados['decano_correo'] = $ua['Correo'];
            $datos_formateados['decano_telefono'] = $ua['Telefono'];
        }

        // 2. Obtener Directores de Proyecto
        // $stmt_dir = $this->pdo->prepare("SELECT * FROM proyecto.Formulario4_DirectorProyecto WHERE IdProyecto = ?");
        // $stmt_dir->execute([$idProyecto]);
        $stmt_dir = $this->executeStoredProcedure('GetForms', 'GET_FORM4_DIRECTOR_PROYECTO', $data_ua_id, 'Transaccion', true);
        $stmt_dir = $stmt_dir['resultado']['Table'] ?? [];
        if ($directores = $stmt_dir) {
            foreach ($directores as $dir) {
                $datos_formateados['facultad_director'][] = $dir['FacultadDirector'];
                $datos_formateados['carrera_director'][] = $dir['CarreraDirector'];
                $datos_formateados['nombre_director'][] = $dir['NombreDirector'];
                $datos_formateados['director_correo'][] = $dir['Correo'];
                $datos_formateados['director_telefono'][] = $dir['Telefono'];
            }
        }

        // 3. Obtener Institución Externa (Actualizada para incluir RutaImagen)
        // $stmt_ie = $this->pdo->prepare("SELECT * FROM proyecto.Formulario4_InstitucionExterna WHERE IdProyecto = ?");
        // $stmt_ie->execute([$idProyecto]);
        $stmt_ie = $this->executeStoredProcedure('GetForms', 'GET_FORM4_INSTITUCION_EXTERNA', $data_ua_id, 'Transaccion', true);
        $stmt_ie = $stmt_ie['resultado']['Table'][0] ?? [];
        if ($ie = $stmt_ie) {
            $datos_formateados['externa_nombre'] = $ie['NombreInstitucion'];
            $datos_formateados['externa_repres_nombre'] = $ie['RepresentanteLegal'];
            $datos_formateados['externa_dir'] = $ie['Direccion'];
            $datos_formateados['externa_tel'] = $ie['Telefono'];
            $datos_formateados['externa_correo'] = $ie['Correo'];
            $datos_formateados['externa_web'] = $ie['PaginaWeb'];
            $datos_formateados['RutaImagen'] = $ie['RutaImagen']; // <-- CAMBIO AÑADIDO
        }

        // 4. Obtener Unidades Cooperantes
        // $stmt_coop = $this->pdo->prepare("SELECT * FROM proyecto.Formulario4_UnidadesCooperantes WHERE IdProyecto = ?");
        // $stmt_coop->execute([$idProyecto]);
        $stmt_coop = $this->executeStoredProcedure('GetForms', 'GET_FORM4_UNIDADES_COOPERANTES', $data_ua_id, 'Transaccion', true);
        $stmt_coop = $stmt_coop['resultado']['Table'] ?? [];
        if ($cooperantes = $stmt_coop) {
            foreach ($cooperantes as $coop) {
                $datos_formateados['facultad_coop'][] = $coop['FacultadCoop'];
                $datos_formateados['carrera_coop'][] = $coop['CarreraCoop'];
                $datos_formateados['docente_coop'][] = $coop['DocenteCoop'];
                $datos_formateados['correo'][] = $coop['Correo'];
                $datos_formateados['telefono'][] = $coop['Telefono'];
            }
        }

        // 5. Obtener Aliado Estratégico
        // $stmt_aliado = $this->pdo->prepare("SELECT * FROM proyecto.Formulario4_AliadoEstrategico WHERE IdProyecto = ?");
        // $stmt_aliado->execute([$idProyecto]);
        $stmt_aliado = $this->executeStoredProcedure('GetForms', 'GET_FORM4_ALIADO_ESTRATEGICO', $data_ua_id, 'Transaccion', true);
        $stmt_aliado = $stmt_aliado['resultado']['Table'][0] ?? [];
        if ($aliado = $stmt_aliado) {
            $datos_formateados['aliado_nombre'] = $aliado['NombreInstitucion'];
            $datos_formateados['aliado_repres_nombre'] = $aliado['RepresentanteLegal'];
            $datos_formateados['aliado_direccion'] = $aliado['Direccion'];
            $datos_formateados['aliado_tel'] = $aliado['Telefono'];
            $datos_formateados['aliado_correo'] = $aliado['Correo'];
            $datos_formateados['aliado_web'] = $aliado['PaginaWeb'];
            $datos_formateados['aliado_contribucion'] = $aliado['Contribucion'];
        }

        return $datos_formateados;
    }
} //fin de la clase