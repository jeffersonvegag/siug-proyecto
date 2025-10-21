<?php

class Formulario3 extends Model
{

    private $pdo;

    public function __construct() {}

    // --- FUNCIONES DE OBTENCIÓN DE DATOS ---
    public function obtenerFormulario3PorProyecto($id_proyecto)
    {

        $data = ['IdPropuesta' => $id_proyecto];

        $datosGenerales = $this->executeStoredProcedure('GetForms', 'GET_FORM3_DATOS_GENERALES', $data, 'Transaccion', true);
       
        $perfilEgreso = $this->executeStoredProcedure('GetForms', 'GET_FORM3_PERFIL_EGRESO', $data, 'Transaccion', true);

        $coberturas = $this->executeStoredProcedure('GetForms', 'GET_FORM3_ID_COBERTURA', $data, 'Transaccion', true);
        return [
            'datos_generales' => $datosGenerales['resultado']['Table'],
            'perfil_egreso'   => $perfilEgreso['resultado']['Table'],
            'coberturas'      => $coberturas['resultado']['Table']
        ];
    }

    public function obtenerDatosCompletosPaso3($id_proyecto)
    {
        $datos_db = $this->obtenerFormulario3PorProyecto($id_proyecto);
        if (empty($datos_db['datos_generales'])) return [];

        $datos_generales = $datos_db['datos_generales'][0];
        $datos_formateados = [
            'comentarios_perfil_egreso' => $datos_generales['ComentariosPerfilEgreso'],
            'comentarios_cobertura'     => $datos_generales['ComentariosCobertura'],
            'contexto'                  => $datos_generales['IdContexto'],
            'comentarios_contexto'      => $datos_generales['ComentariosContexto'],
            'duracion'                  => $datos_generales['IdDuracion'],
            'comentarios_duracion'      => $datos_generales['ComentariosDuracion']
        ];

        if (!empty($datos_db['perfil_egreso'])) {
            foreach ($datos_db['perfil_egreso'] as $perfil) {
                $datos_formateados['facultad'][]      = $perfil['IdFacultad'];
                $datos_formateados['carrera'][]       = $perfil['IdCarrera'];
                $datos_formateados['programa'][]      = $perfil['Programa'];
                $datos_formateados['aporte_perfil'][] = $perfil['AportePerfil'];
            }
        }

        if (!empty($datos_db['coberturas'])) {
            foreach ($datos_db['coberturas'] as $cobertura) {
                $datos_formateados['cobertura'][]      = $cobertura['IdCobertura'];
            }
        }
        return $datos_formateados;
    }

    // --- FUNCIÓN MAESTRA DE GUARDADO (UPSERT) ---
    public function guardarPaso3($id_proyecto, $data)
    {
        $datosExistentes = $this->obtenerFormulario3PorProyecto($id_proyecto);
        $datosExistentes = $datosExistentes;
        if ($datosExistentes['datos_generales']) {
            $data_DG = [
                'Formulario3' => [
                    'IdFormulario3Datos' => $datosExistentes['datos_generales'][0]['IdFormulario3Datos'],
                    'IdPropuesta' => $id_proyecto,
                    'ComentariosPerfilEgreso'  => $data['comentarios_perfil_egreso'] ?? null,
                    'ComentariosCobertura' => $data['comentarios_cobertura'] ?? null,
                    'IdContexto'=> $data['contexto'] ?? null,
                    'ComentariosContexto' => $data['comentarios_contexto'] ?? null,
                    'IdDuracion'=> $data['duracion'] ?? null,
                    'ComentariosDuracion' =>  $data['comentarios_duracion'] ?? null,
                    'UsuarioTrx' => $_SESSION['user']['username']
                ]
            ];
            
            $this->executeStoredProcedure('SetForms', 'UPSERT_FORM3', $data_DG, 'Transaccion', true);
            // $stmt = $this->pdo->prepare("UPDATE proyecto.Formulario3_DatosGenerales SET ComentariosPerfilEgreso = ?, ComentariosCobertura = ?, IdContexto = ?, ComentariosContexto = ?, IdDuracion = ?, ComentariosDuracion = ? WHERE IdProyecto = ?");
            // $stmt->execute(array_values($datosGenerales + [$id_proyecto]));
        } else {

                $data_DG = [
                'Formulario3' => [
                    'IdPropuesta' => $id_proyecto,
                    'ComentariosPerfilEgreso'  => $data['comentarios_perfil_egreso'] ?? null,
                    'ComentariosCobertura' => $data['comentarios_cobertura'] ?? null,
                    'IdContexto'=> $data['contexto'] ?? null,
                    'ComentariosContexto' => $data['comentarios_contexto'] ?? null,
                    'IdDuracion'=> $data['duracion'] ?? null,
                    'ComentariosDuracion' =>  $data['comentarios_duracion'] ?? null,
                    'UsuarioTrx' => $_SESSION['user']['username']
                ]
            ];
           $this->executeStoredProcedure('SetForms', 'UPSERT_FORM3', $data_DG, 'Transaccion', true); 
            // $stmt = $this->pdo->prepare("INSERT INTO proyecto.Formulario3_DatosGenerales (ComentariosPerfilEgreso, ComentariosCobertura, IdContexto, ComentariosContexto, IdDuracion, ComentariosDuracion, IdProyecto) VALUES (?, ?, ?, ?, ?, ?, ?)");
            // $stmt->execute(array_values($datosGenerales + [$id_proyecto]));
        }

        // 2. Guardar Perfil de Egreso (Borrar y Reinsertar)
        $proyecto_id=['IdPropuesta' => $id_proyecto];
        $this->executeStoredProcedure('SetEstado', 'DELETE_Formulario3_PerfilEgreso', $proyecto_id, 'Transaccion', true);
        if (!empty($data['facultad'])) {
            for ($i = 0; $i < count($data['facultad']); $i++) {
                $data_pe = [
                'Formulario3' => [
                    'IdPropuesta' => $id_proyecto,
                    'IdFacultad'  => $data['facultad'][$i] ?? null,
                    'IdCarrera' => $data['carrera'][$i] ?? null,
                    'Programa'=> $data['programa'][$i]?? null,
                    'AportePerfil' => $data['aporte_perfil'][$i] ?? null,
                    'UsuarioTrx' => $_SESSION['user']['username']
                ]];
                $this->executeStoredProcedure('SetForms', 'UPSERT_FORM3_PE', $data_pe, 'Transaccion', true); 
            //     $stmt = $this->pdo->prepare("INSERT INTO proyecto.Formulario3_PerfilEgreso (IdProyecto, IdFacultad, IdCarrera, Programa, AportePerfil) VALUES (?, ?, ?, ?, ?)");
            //     $stmt->execute([$id_proyecto, $data['facultad'][$i], $data['carrera'][$i], $data['programa'][$i], $data['aporte_perfil'][$i]]);
            }
            
        }


        // 3. Guardar Cobertura (Borrar y Reinsertar)
        $this->executeStoredProcedure('SetEstado', 'DELETE_Formulario3_Cobertura', $proyecto_id, 'Transaccion', true);
        if (!empty($data['cobertura'])) {         
            //$stmt = $this->pdo->prepare("INSERT INTO proyecto.Formulario3_Cobertura (IdProyecto, IdCobertura) VALUES (?, ?)");
            foreach ($data['cobertura'] as $idCobertura) {
                $data_c = [
                'Formulario3' => [
                    'IdPropuesta' => $id_proyecto,
                    'IdCobertura'  => $idCobertura ?? null,
                    'UsuarioTrx' => $_SESSION['user']['username']
                ]];
                
                $result = $this->executeStoredProcedure('SetForms', 'UPSERT_FORM3_COBERTURA', $data_c, 'Transaccion', true); 
                // $stmt->execute([$id_proyecto, $idCobertura]);
            }
        }
    }
}
