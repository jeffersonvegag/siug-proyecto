<?php

class Formulario10 extends Model
{
    private $pdo;

    public function __construct()
    {
        // $config = require ROOT_PATH . '/config/database.php';
        // try {
        //     $this->pdo = new PDO($config['dsn'], $config['usuario'], $config['clave']);
        //     $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // } catch (PDOException $e) {
        //     die("Error de conexión en Formulario10: " . $e->getMessage());
        // }
    }

    // ===================================================================
    // FUNCIÓN MAESTRA DE GUARDADO (UPSERT)
    // ===================================================================
    public function guardarPaso10($id_proyecto, $datos)
    {
        $data_delete = ['IdPropuesta' => $id_proyecto];
        // 1. Guardar Matriz de Seguimiento (si se enviaron datos)
        if (!empty($datos['cantidad'])) {
            // --- MODIFICADO: El DELETE ahora está DENTRO del IF ---
            $this->executeStoredProcedure('SetEstado', 'DELETE_Formulario10_MatrizSeguimiento', $data_delete, 'Transaccion', true);

            // $sql = "INSERT INTO proyecto.Formulario10_MatrizSeguimiento (IdProyecto, ResultadoEsperado, Cantidad, MedioVerificacion, FechaResultadoParcial, ResponsableControlVerificacion, ComentariosSeguimiento, ObjetivoRelacionado) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            // $stmt = $this->pdo->prepare($sql);
            $comentarios = $datos['comentarios_seguimiento'] ?? null;
            foreach ($datos['cantidad'] as $index => $cantidad) {
                $data_matriz = [
                    'Formulario10' => [
                        'IdPropuesta' => $id_proyecto,
                        'ResultadoEsperado' => $datos['resultado_esperado_texto'][$index],
                        'Cantidad' => $cantidad,
                        'MedioVerificacion' => $datos['medio_verificacion'][$index],
                        'FechaResultadoParcial' => $datos['fecha_parcial'][$index],
                        'ResponsableControlVerificacion' => $datos['responsable_control'][$index],
                        'ComentariosSeguimiento' => $comentarios,
                        'ObjetivoRelacionado' => $index + 1,
                        'UsuarioTrx' => $_SESSION['user']['username']
                    ]
                ];

                $this->executeStoredProcedure('SetForms', 'UPSERT_FORM10_MS', $data_matriz, 'Transaccion', true);

            }
        }

        // 2. Guardar Cronograma de Actividades (si se enviaron datos)
        if (!empty($datos['actividad'])) {
            // --- MODIFICADO: El DELETE ahora está DENTRO del IF ---
            $this->executeStoredProcedure('SetEstado', 'DELETE_Formulario10_Cronograma', $data_delete, 'Transaccion', true);

            // $sql = "INSERT INTO proyecto.Formulario10_Cronograma (IdProyecto, ObjetivoRelacionado, Actividad, Duracion, MesesAsignados, Responsable, ComentariosCronograma) VALUES (?, ?, ?, ?, ?, ?, ?)";
            // $stmt = $this->pdo->prepare($sql);
            $comentarios = $datos['comentarios_cronograma'] ?? null;

            foreach ($datos['actividad'] as $objIndex => $actividades) {
                foreach ($actividades as $i => $actividad) {
                    $duracion = (int) ($datos['duracion'][$objIndex][$i] ?? 0);
                    $meses_array = $datos['meses_asignados'][$objIndex][$i] ?? [];
                    $meses_string = implode(',', $meses_array);

                    $data_crono = [
                        'Formulario10' => [
                            'IdPropuesta' => $id_proyecto,
                            'ObjetivoRelacionado' => $objIndex + 1,
                            'Actividad' => $actividad,
                            'Duracion' => $duracion,
                            'MesesAsignados' => $meses_string,
                            'Responsable' => $datos['responsable'][$objIndex][$i] ?? null,
                            'ComentariosCronograma' => $comentarios,
                            'UsuarioTrx' => $_SESSION['user']['username']
                        ]
                    ];

                    $this->executeStoredProcedure('SetForms', 'UPSERT_FORM10_CNG', $data_crono, 'Transaccion', true);
                }
            }
        }

        // 3. Guardar Presupuesto (si se enviaron datos)
        if (!empty($datos['responsable_presupuesto'])) {
            // --- MODIFICADO: El DELETE ahora está DENTRO del IF ---
            $this->executeStoredProcedure('SetEstado', 'DELETE_Formulario10_Presupuesto', $data_delete, 'Transaccion', true);

            // $sql = "INSERT INTO proyecto.Formulario10_Presupuesto (IdProyecto, Responsable, Cantidad, Especificaciones, Horas, Meses, ValorHora, ComentariosPresupuesto) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            // $stmt = $this->pdo->prepare($sql);
            $comentarios = $datos['comentarios_presupuesto'] ?? null;

            foreach ($datos['responsable_presupuesto'] as $i => $responsable) {
                $cantidad = (float) ($datos['cantidad_presupuesto'][$i] ?? 0);
                $horas = (float) ($datos['horas'][$i] ?? 0);
                $meses = (float) ($datos['meses'][$i] ?? 0);
                $valor_hora = (float) str_replace(',', '.', $datos['valor_hora'][$i] ?? 0);

                $data_pres = [
                    'Formulario10' => [
                        'IdPropuesta' => $id_proyecto,
                        'Responsable' => $responsable,
                        'Cantidad' => $cantidad,
                        'Especificaciones' => $datos['especificaciones'][$i],
                        'Horas' => $horas,
                        'Meses' => $meses,
                        'ValorHora' => $valor_hora,
                        'ComentariosPresupuesto' => $comentarios,
                        'UsuarioTrx' => $_SESSION['user']['username']
                    ]
                ];
                $this->executeStoredProcedure('SetForms', 'UPSERT_FORM10_PSP', $data_pres, 'Transaccion', true);
            }
        }
    }

    public function obtenerDatosCompletosPaso10($id_proyecto)
    {
        $data = ['IdPropuesta' => $id_proyecto];
        $datos = [];

        $matriz = $this->executeStoredProcedure('GetForms', 'GET_FORM10_MATRIZ_SEGUIMIENTO', $data, 'Transaccion', true);
        $datos['matriz_seguimiento'] = $matriz['resultado']['Table'] ?? [];

        $cronograma = $this->executeStoredProcedure('GetForms', 'GET_FORM10_CRONOGRAMA', $data, 'Transaccion', true);
        $datos['cronograma'] = $cronograma['resultado']['Table'] ?? [];

        $presupuesto = $this->executeStoredProcedure('GetForms', 'GET_FORM10_PRESUPUESTO', $data, 'Transaccion', true);
        $datos['presupuesto'] = $presupuesto['resultado']['Table'] ?? [];

        $datos['comentarios_seguimiento'] = $datos['matriz_seguimiento'][0]['ComentariosSeguimiento'] ?? '';
        $datos['comentarios_cronograma'] = $datos['cronograma'][0]['ComentariosCronograma'] ?? '';
        $datos['comentarios_presupuesto'] = $datos['presupuesto'][0]['ComentariosPresupuesto'] ?? '';

        // var_dump($datos);
        // die();

        return $datos;
    }
}