<?php

class Formulario7 extends Model
{

    private $pdo;

    public function __construct() {}

    // ===================================================================
    // FUNCIÓN MAESTRA DE GUARDADO (UPSERT) - CORREGIDA
    // ===================================================================
    public function guardarPaso7($id_proyecto, $datos)
    {
        $data = ['IdPropuesta' => $id_proyecto];
        $this->executeStoredProcedure('SetEstado', 'DELETE_Formulario7_PoblacionObjetivo', $data, 'Transaccion', true);
        $this->executeStoredProcedure('SetEstado', 'DELETE_Formulario7_Beneficiarios', $data, 'Transaccion', true);


        // --- 2. Insertar nueva Población Objetivo---

        $data_po = [
            'Formulario7' => [
                'IdPropuesta' => $id_proyecto,
                'Descripcion' =>  $datos['descripcion'],
                'ComentariosDescripcion' =>  $datos['comentarios_descripcion'],
                'NumeroTotalPoblacion' =>  $datos['numero_poblacion'],
                'ComentariosNumeroPoblacion' =>  $datos['comentarios_numero_poblacion'],
                'Caracteristicas' =>  $datos['caracteristicas'],
                'ComentariosCaracteristicas' =>  $datos['comentarios_caracteristicas'],
                'DetalleDirecto' =>  $datos['detalle_beneficiarios_directos'],
                'DetalleIndirecto' =>  $datos['detalle_beneficiarios_indirectos'],
                'UsuarioTrx' => $_SESSION['user']['username']
            ]
        ];
        $this->executeStoredProcedure('SetForms', 'UPSERT_FORM7_PO', $data_po, 'Transaccion', true);

        // --- 3. Insertar nuevos Beneficiarios ---
        // Directos
        if (!empty($datos['directo'])) {
            foreach ($datos['directo'] as $beneficiario) {
                if (!empty(trim($beneficiario['descripcion'])) || !empty($beneficiario['numero'])) {

                    $data_bd = [
                        'Formulario7' => [
                            'IdPropuesta' => $id_proyecto,
                            'Tipo' => 'DIRECTO',
                            'Grupo' => $beneficiario['grupo'],
                            'Descripcion' => $beneficiario['descripcion'],
                            'NumeroBeneficiarios' => $beneficiario['numero'] ?? 0,
                            'UsuarioTrx' => $_SESSION['user']['username']
                        ]
                    ];
                    $this->executeStoredProcedure('SetForms', 'UPSERT_FORM7_BNF', $data_bd, 'Transaccion', true);

                    // $stmtBeneficiario->execute([$id_proyecto, 'DIRECTO', $beneficiario['grupo'], $beneficiario['descripcion'], $beneficiario['numero'] ?? 0]);
                }
            }
        }
        // Indirectos
        if (!empty($datos['indirecto'])) {
            foreach ($datos['indirecto'] as $beneficiario) {
                if (!empty(trim($beneficiario['descripcion'])) || !empty($beneficiario['numero'])) {
                    $data_bd = [
                        'Formulario7' => [
                            'IdPropuesta' => $id_proyecto,
                            'Tipo' => 'INDIRECTO',
                            'Grupo' => $beneficiario['grupo'],
                            'Descripcion' => $beneficiario['descripcion'],
                            'NumeroBeneficiarios' => $beneficiario['numero'] ?? 0,
                            'UsuarioTrx' => $_SESSION['user']['username']
                        ]
                    ];
                    $this->executeStoredProcedure('SetForms', 'UPSERT_FORM7_BNF', $data_bd, 'Transaccion', true);
                }
            }
        }
    }

    // ===================================================================
    // FUNCIÓN PARA PREPARAR DATOS PARA LA VISTA - CORREGIDA
    // ===================================================================
    public function obtenerDatosCompletosPaso7($id_proyecto)
    {
        $datos_formateados = [];

        // Obtener datos de población objetivo
        $data = ['IdPropuesta' => $id_proyecto];
        $poblacion = $this->executeStoredProcedure('GetForms', 'GET_FORM7_POBLACION_OBJETIVO', $data, 'Transaccion', true);
        $datos_poblacion = $poblacion['resultado']['Table'][0] ?? [];

        if ($datos_poblacion) {
            $datos_formateados['descripcion'] = $datos_poblacion['Descripcion'];
            $datos_formateados['comentarios_descripcion'] = $datos_poblacion['ComentariosDescripcion'];
            $datos_formateados['numero_poblacion'] = $datos_poblacion['NumeroTotalPoblacion'];
            $datos_formateados['comentarios_numero_poblacion'] = $datos_poblacion['ComentariosNumeroPoblacion'];
            $datos_formateados['caracteristicas'] = $datos_poblacion['Caracteristicas'];
            $datos_formateados['comentarios_caracteristicas'] = $datos_poblacion['ComentariosCaracteristicas'];
            $datos_formateados['detalle_beneficiarios_directos'] = $datos_poblacion['DetalleDirecto'];
            $datos_formateados['detalle_beneficiarios_indirectos'] = $datos_poblacion['DetalleIndirecto'];
            // Se eliminaron las líneas que buscaban los comentarios de beneficiarios
        }

        // Obtener beneficiarios y agruparlos (sin cambios aquí)
        $beneficiarios = $this->executeStoredProcedure('GetForms', 'GET_FORM7_BENEFICIARIOS', $data, 'Transaccion', true);
        $beneficiarios = $beneficiarios['resultado']['Table'] ?? [];

        $directos_map = [];
        $indirectos_map = [];

        foreach ($beneficiarios as $ben) {
            if ($ben['Tipo'] === 'DIRECTO') {
                $directos_map[$ben['Grupo']] = ['descripcion' => $ben['Descripcion'], 'numero' => $ben['NumeroBeneficiarios']];
            } else if ($ben['Tipo'] === 'INDIRECTO') {
                $indirectos_map[$ben['Grupo']] = ['descripcion' => $ben['Descripcion'], 'numero' => $ben['NumeroBeneficiarios']];
            }
        }

        $datos_formateados['directos_map'] = $directos_map;
        $datos_formateados['indirectos_map'] = $indirectos_map;

        // var_dump($datos_formateados);
        // die();
        return $datos_formateados;
    }
}
