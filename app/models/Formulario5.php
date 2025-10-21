<?php

class Formulario5 extends Model
{

    private $pdo;

    public function __construct() {}

    // ==================================================================
    // === INICIO DEL CÓDIGO AÑADIDO ====================================
    // ==================================================================

    /**
     * Función maestra que orquesta el guardado de TODOS los datos del Paso 5.
     * Llama a las funciones de actualización individuales.
     * @param int $id_proyecto El ID del proyecto.
     * @param array $datos Los datos completos del formulario (usualmente $_POST).
     */
    public function guardarPaso5($id_proyecto, $datos)
    {
        // 1. DIRECTOR DE PROYECTO
        $directores = [];
        // Solo procesamos al director si se envió su cédula (lo que indica que el formulario estaba activo para él)
        if (!empty($datos['cedula_director'])) {
            $directores[] = [
                'nombre' => $datos['nombre_director'] ?? '',
                'cedula' => $datos['cedula_director'] ?? '',
                'acreditado' => $datos['acreditado_director'] ?? '',
                'categoria' => $datos['categoria_director'] ?? '',
                'dedicacion' => $datos['dedicacion_director'] ?? '',
                'correo' => $datos['correo_director'] ?? '',
                'telefono' => $datos['telefono_director'] ?? '',
                'facultad' => $datos['facultad_director_paso5'] ?? '',
                'carrera' => $datos['carrera_director_paso5'] ?? '',
                'comentarios' => $datos['comentarios_director'] ?? '',
            ];
        }
        $this->actualizarDirectorProyecto($id_proyecto, $directores);

        // 2. DOCENTES TUTORES (LÓGICA CORREGIDA)
        $docentes = [];
        if (!empty($datos['docente_tutor'])) { // Se itera sobre el campo principal 'docente_tutor'
            foreach ($datos['docente_tutor'] as $key => $nombre) {
                // Solo se guarda la fila si el usuario seleccionó un nombre de docente
                if (!empty($nombre)) {
                    $docentes[] = [
                        'nombre' => $nombre,
                        'cedula' => $datos['docente_cedula'][$key] ?? '',
                        'facultad' => $datos['facultad_tutor'][$key] ?? '',
                        'carrera' => $datos['carrera_tutor'][$key] ?? '',
                        'acreditado' => $datos['docente_acreditado'][$key] ?? '',
                        'categoria' => $datos['docente_categoria'][$key] ?? '',
                        'dedicacion' => $datos['docente_dedicacion'][$key] ?? '',
                        'correo' => $datos['docente_correo'][$key] ?? '',
                        'telefono' => $datos['docente_telefono'][$key] ?? '',
                    ];
                }
            }
        }
        $this->actualizarDocentesTutores($id_proyecto, $docentes);
        $this->actualizarComentarioDocentes($id_proyecto, $datos['comentarios_docentes'] ?? '');

        // 3. ESTUDIANTES DEL PROYECTO (LÓGICA CORREGIDA)
        $estudiantes = [];
        if (!empty($datos['facultad_estud'])) { // Se itera sobre el campo principal 'facultad_estud'
            foreach ($datos['facultad_estud'] as $key => $facultad) {
                if (!empty($facultad)) {
                    $estudiantes[] = [
                        'facultad' => $facultad,
                        'carrera' => $datos['carrera_estud'][$key] ?? '',
                        'cantidad' => $datos['estudiante_numero'][$key] ?? 0
                    ];
                }
            }
        }
        $this->actualizarEstudiantesProyecto($id_proyecto, $estudiantes);
        $this->actualizarComentarioEstudiantes($id_proyecto, $datos['comentarios_estudiantes'] ?? '');

        // 4. ESTUDIANTES DE PROGRAMAS DE ARTICULACIÓN (LÓGICA CORREGIDA)
        $estudiantes_programas = [];
        if (!empty($datos['programa_articulacion_nombre'])) { // Se itera sobre el campo principal
            foreach ($datos['programa_articulacion_nombre'] as $key => $programa) {
                if (!empty(trim($programa))) {
                    $estudiantes_programas[] = [
                        'programa' => $programa,
                        'cantidad' => $datos['programa_articulacion_numero'][$key] ?? 0
                    ];
                }
            }
        }
        $this->actualizarEstudiantesProgramas($id_proyecto, $estudiantes_programas);
        $this->actualizarComentarioProgramas($id_proyecto, $datos['comentarios_programas'] ?? '');

        // 5. INCLUSIÓN DE ESTUDIANTES
        $inclusion = [
            'acciones' => $datos['acciones_contribucion'] ?? '',
            'comentarios' => $datos['comentarios_acciones_contribucion'] ?? ''
        ];
        $this->actualizarInclusionEstudiantes($id_proyecto, $inclusion);

        // 6. ESTUDIANTES POR CICLO
        $estudiantes_ciclos = [];
        if (!empty($datos['estudiantes_ciclo_total'])) {
            foreach ($datos['estudiantes_ciclo_total'] as $key => $total) {
                $estudiantes_ciclos[] = [
                    'ciclo' => $key + 1,
                    'total' => $total,
                    'discapacidad' => $datos['estudiantes_ciclo_discapacidad'][$key] ?? 0
                ];
            }
        }
        $this->actualizarEstudiantesCiclos($id_proyecto, $estudiantes_ciclos);
    }

    // ==================================================================
    // === FIN DEL CÓDIGO AÑADIDO =======================================
    // ==================================================================


    public function guardarDirectorProyecto($datos, $id_proyecto)
    {
        $data = [
            'Formulario5' => [
                'IdPropuesta' => $id_proyecto,
                'NombreDirector' => $datos['nombre'],
                'CedulaDirector' => $datos['cedula'],
                'Acreditado' => $datos['acreditado'],
                'Categoria' => $datos['categoria'],
                'Dedicacion' => $datos['dedicacion'],
                'Correo' => $datos['correo'],
                'Telefono' => $datos['telefono'],
                'Facultad' => $datos['facultad'],
                'Carrera' => $datos['carrera'],
                'Comentarios' => $datos['comentarios'],
                'UsuarioTrx' => $_SESSION['user']['username']
            ]
        ];
        $this->executeStoredProcedure('SetForms', 'UPSERT_FORM5', $data, 'Transaccion', true);
    }

    public function guardarDocenteTutor($datos, $id_proyecto)
    {
        $data = [
            'Formulario5' => [
                'IdPropuesta' => $id_proyecto,
                'Facultad' =>  $datos['facultad'],
                'Carrera' => $datos['carrera'],
                'Nombre' => $datos['nombre'],
                'Cedula' => $datos['cedula'],
                'Acreditado' => $datos['acreditado'],
                'Categoria' => $datos['categoria'],
                'Dedicacion' =>  $datos['dedicacion'],
                'Correo' => $datos['correo'],
                'Telefono' => $datos['telefono'],
                'UsuarioTrx' => $_SESSION['user']['username']
            ]
        ];
        $this->executeStoredProcedure('SetForms', 'UPSERT_FORM5_TUTOR', $data, 'Transaccion', true);

        //         var_dump($this->executeStoredProcedure('SetForms', 'UPSERT_FORM5_TUTOR', $data, 'Transaccion', true));
        // die();
    }

    public function guardarComentarioDocentes($comentario, $id_proyecto)
    {
        $data = [
            'Formulario5' => [
                'IdPropuesta' => $id_proyecto,
                'ComentariosDocentes' => $comentario,
                'UsuarioTrx' => $_SESSION['user']['username']
            ]
        ];
        $this->executeStoredProcedure('SetForms', 'UPSERT_FORM5_CD', $data, 'Transaccion', true);
    }

    public function guardarEstudiantesProyecto($datos, $id_proyecto)
    {
        $data = [
            'Formulario5' => [
                'IdPropuesta' => $id_proyecto,
                'Facultad' =>  $datos['facultad'],
                'Carrera' => $datos['carrera'],
                'NumeroEstudiantes' =>  $datos['cantidad'],
                'UsuarioTrx' => $_SESSION['user']['username']
            ]
        ];
        $this->executeStoredProcedure('SetForms', 'UPSERT_FORM5_ESTUD_PROY', $data, 'Transaccion', true);
    }

    public function guardarComentarioEstudiantes($comentario, $id_proyecto)
    {
        $data = [
            'Formulario5' => [
                'IdPropuesta' => $id_proyecto,
                'ComentariosEstudiantes' => $comentario,
                'UsuarioTrx' => $_SESSION['user']['username']
            ]
        ];
        $this->executeStoredProcedure('SetForms', 'UPSERT_FORM5_CE', $data, 'Transaccion', true);
    }

    public function guardarEstudiantesPrograma($datos, $id_proyecto)
    {
        $data = [
            'Formulario5' => [
                'IdPropuesta' => $id_proyecto,
                'ProgramaArticulacion' =>  $datos['programa'],
                'NumeroEstudiantesPrograma' =>  $datos['cantidad'],
                'UsuarioTrx' => $_SESSION['user']['username']
            ]
        ];
        $this->executeStoredProcedure('SetForms', 'UPSERT_FORM5_ESTUD_PROG', $data, 'Transaccion', true);
    }

    public function guardarComentarioProgramas($comentario, $id_proyecto)
    {
        $data = [
            'Formulario5' => [
                'IdPropuesta' => $id_proyecto,
                'ComentariosProgramas' => $comentario,
                'UsuarioTrx' => $_SESSION['user']['username']
            ]
        ];
        $this->executeStoredProcedure('SetForms', 'UPSERT_FORM5_CP', $data, 'Transaccion', true);
    }

    public function guardarInclusionEstudiantes($datos, $id_proyecto)
    {
        $data = [
            'Formulario5' => [
                'IdPropuesta' => $id_proyecto,
                'AccionesContribucion' =>  $datos['acciones'],
                'ComentariosAccionContrib' =>  $datos['comentarios'],
                'UsuarioTrx' => $_SESSION['user']['username']
            ]
        ];
        $this->executeStoredProcedure('SetForms', 'UPSERT_FORM5_ESTUD_INC', $data, 'Transaccion', true);
    }

    // NUEVAS FUNCIONES PARA ESTUDIANTES POR CICLO
    public function guardarEstudiantesCiclos($datos, $id_proyecto)
    {
        $data = [
            'Formulario5' => [
                'IdPropuesta' => $id_proyecto,
                'Ciclo' =>  $datos['ciclo'],
                'TotalEstudiantes' =>  $datos['total'],
                'EstudiantesDiscapacidad' =>  $datos['discapacidad'],
                'UsuarioTrx' => $_SESSION['user']['username']
            ]
        ];
        $this->executeStoredProcedure('SetForms', 'UPSERT_FORM5_ESTUD_CICLO', $data, 'Transaccion', true);
    }

    // OBTENER ESTUDIANTES POR CICLO
    public function obtenerEstudiantesCiclosPorProyecto($idProyecto)
    {
        $data = ['IdPropuesta' => $idProyecto];
        $datosGenerales = $this->executeStoredProcedure('GetForms', 'GET_FORM5_ESTUDIANTES_CICLOS', $data, 'Transaccion', true);

        return $datosGenerales['resultado']['Table'];
    }

    /**
     * Obtiene todos los datos del Formulario 5 para un proyecto en un formato unificado.
     * @param int $idProyecto El ID del proyecto.
     * @return array Un array asociativo con todos los datos.
     */
    public function obtenerDatosCompletosPaso5($idProyecto)
    {
        $data = [];

        $director = $this->obtenerDirectorProyectoPorProyecto($idProyecto);
        if ($director) {
                    $director = $director[0];
            $data['nombre_director'] = $director['NombreDirector'];
            $data['cedula_director'] = $director['CedulaDirector'];
            $data['acreditado_director'] = $director['Acreditado'];
            $data['categoria_director'] = $director['Categoria'];
            $data['dedicacion_director'] = $director['Dedicacion'];
            $data['correo_director'] = $director['Correo'];
            $data['telefono_director'] = $director['Telefono'];
            $data['facultad_director_paso5'] = $director['Facultad'];
            $data['carrera_director_paso5'] = $director['Carrera'];
            $data['comentarios_director'] = $director['Comentarios'];
        }

        $docentes = $this->obtenerDocentesTutoresPorProyecto($idProyecto);
        if ($docentes) {
            foreach ($docentes as $docente) {
                $data['docente_tutor'][] = $docente['Nombre'];
                $data['docente_cedula'][] = $docente['Cedula'];
                $data['facultad_tutor'][] = $docente['Facultad'];
                $data['carrera_tutor'][] = $docente['Carrera'];
                $data['docente_acreditado'][] = $docente['Acreditado'];
                $data['docente_categoria'][] = $docente['Categoria'];
                $data['docente_dedicacion'][] = $docente['Dedicacion'];
                $data['docente_correo'][] = $docente['Correo'];
                $data['docente_telefono'][] = $docente['Telefono'];
            }
        }

        $comentarioDocentes = $this->obtenerComentarioDocentesPorProyecto($idProyecto);

        $data['comentarios_docentes'] = $comentarioDocentes['ComentariosDocentes'] ?? '';

        $estudiantes = $this->obtenerEstudiantesProyectoPorProyecto($idProyecto);
        if ($estudiantes) {
            foreach ($estudiantes as $estudiante) {
                $data['facultad_estud'][] = $estudiante['Facultad'];
                $data['carrera_estud'][] = $estudiante['Carrera'];
                $data['estudiante_numero'][] = $estudiante['NumeroEstudiantes'];
            }
        }
        $comentarioEstudiantes = $this->obtenerComentarioEstudiantesPorProyecto($idProyecto);
        $data['comentarios_estudiantes'] = $comentarioEstudiantes['ComentariosEstudiantes'] ?? '';

        $estudiantesProgramas = $this->obtenerEstudiantesProgramasPorProyecto($idProyecto);
        if ($estudiantesProgramas) {
            foreach ($estudiantesProgramas as $programa) {
                $data['programa_articulacion_nombre'][] = $programa['ProgramaArticulacion'];
                $data['programa_articulacion_numero'][] = $programa['NumeroEstudiantesPrograma'];
            }
        }

        $comentarioProgramas = $this->obtenerComentarioProgramasPorProyecto($idProyecto);
        $data['comentarios_programas'] = $comentarioProgramas['ComentariosProgramas'] ?? '';

        $inclusionEstudiantes = $this->obtenerInclusionEstudiantesPorProyecto($idProyecto);
        $data['acciones_contribucion'] = $inclusionEstudiantes['AccionesContribucion'] ?? '';
        $data['comentarios_acciones_contribucion'] = $inclusionEstudiantes['ComentariosAccionContrib'] ?? '';

        // NUEVA LÓGICA PARA RECUPERAR ESTUDIANTES POR CICLO
        $estudiantesCiclos = $this->obtenerEstudiantesCiclosPorProyecto($idProyecto);
        if ($estudiantesCiclos) {
            foreach ($estudiantesCiclos as $ciclo) {
                // Almacenar los valores por índice, ya que se renderizan en un bucle for en la vista
                $data['estudiantes_ciclo_total'][$ciclo['Ciclo'] - 1] = $ciclo['TotalEstudiantes'];
                $data['estudiantes_ciclo_discapacidad'][$ciclo['Ciclo'] - 1] = $ciclo['EstudiantesDiscapacidad'];
            }
        } else {
            // Inicializar con valores vacíos si no hay datos guardados para evitar errores en la vista.
            $data['estudiantes_ciclo_total'] = array_fill(0, 4, '');
            $data['estudiantes_ciclo_discapacidad'] = array_fill(0, 4, '');
        }
        return $data;
    }


    public function obtenerDirectorProyectoPorProyecto($idProyecto)
    {
        $data = ['IdPropuesta' => $idProyecto];
        $datosGenerales = $this->executeStoredProcedure('GetForms', 'GET_FORM5_DIRECTOR_PROYECTO', $data, 'Transaccion', true);
        return $datosGenerales['resultado']['Table'];
    }

    public function obtenerDocentesTutoresPorProyecto($idProyecto)
    {
        $data = ['IdPropuesta' => $idProyecto];
        $datosGenerales = $this->executeStoredProcedure('GetForms', 'GET_FORM5_DOCENTES_TUTORES', $data, 'Transaccion', true);

        return $datosGenerales['resultado']['Table'];
    }

    public function obtenerComentarioDocentesPorProyecto($idProyecto)
    {
        $data = ['IdPropuesta' => $idProyecto];
        $datosGenerales = $this->executeStoredProcedure('GetForms', 'GET_FORM5_COMENTARIOS_DOCENTES', $data, 'Transaccion', true);
        return $datosGenerales['resultado']['Table'];
    }

    public function obtenerEstudiantesProyectoPorProyecto($idProyecto)
    {
        $data = ['IdPropuesta' => $idProyecto];
        $datosGenerales = $this->executeStoredProcedure('GetForms', 'GET_FORM5_ESTUDIANTES_PROYECTO', $data, 'Transaccion', true);

        return $datosGenerales['resultado']['Table'];
    }

    public function obtenerComentarioEstudiantesPorProyecto($idProyecto)
    {
        $data = ['IdPropuesta' => $idProyecto];
        $datosGenerales = $this->executeStoredProcedure('GetForms', 'GET_FORM5_COMENTARIOS_ESTUDIANTES', $data, 'Transaccion', true);
        return $datosGenerales['resultado']['Table'];
    }

    public function obtenerEstudiantesProgramasPorProyecto($idProyecto)
    {
        $data = ['IdPropuesta' => $idProyecto];
        $datosGenerales = $this->executeStoredProcedure('GetForms', 'GET_FORM5_ESTUDIANTES_PROGRAMAS', $data, 'Transaccion', true);
        return $datosGenerales['resultado']['Table'];
    }

    public function obtenerComentarioProgramasPorProyecto($idProyecto)
    {
        $data = ['IdPropuesta' => $idProyecto];
        $datosGenerales = $this->executeStoredProcedure('GetForms', 'GET_FORM5_COMENTARIOS_PROGRAMAS', $data, 'Transaccion', true);
        return $datosGenerales['resultado']['Table'];
    }

    public function obtenerInclusionEstudiantesPorProyecto($idProyecto)
    {
        $data = ['IdPropuesta' => $idProyecto];
        $datosGenerales = $this->executeStoredProcedure('GetForms', 'GET_FORM5_INCLUSION_ESTUDIANTES', $data, 'Transaccion', true);
        return $datosGenerales['resultado']['Table'];
    }

    // Funciones para actualizar (eliminar e insertar)

    // DIRECTOR DE PROYECTO
    public function actualizarDirectorProyecto($idProyecto, $directores)
    {
        $data = ['IdPropuesta' => $idProyecto];
        $this->executeStoredProcedure('SetEstado', 'DELETE_Formulario5_DirectorProyecto', $data, 'Transaccion', true);
        foreach ($directores as $datos) {
            $this->guardarDirectorProyecto($datos, $idProyecto);
        }
    }

    // DOCENTES TUTORES
    public function actualizarDocentesTutores($idProyecto, $docentes)
    {
        $data = ['IdPropuesta' => $idProyecto];
        $this->executeStoredProcedure('SetEstado', 'DELETE_Formulario5_DocentesTutores', $data, 'Transaccion', true);
        foreach ($docentes as $datos) {
            $this->guardarDocenteTutor($datos, $idProyecto);
        }
    }

    // COMENTARIO DOCENTES
    public function actualizarComentarioDocentes($idProyecto, $comentario)
    {
        $data = ['IdPropuesta' => $idProyecto];
        $this->executeStoredProcedure('SetEstado', 'DELETE_Formulario5_ComentariosDocentes', $data, 'Transaccion', true);
        $this->guardarComentarioDocentes($comentario, $idProyecto);
    }

    // ESTUDIANTES PROYECTO
    public function actualizarEstudiantesProyecto($idProyecto, $estudiantes)
    {
        $data = ['IdPropuesta' => $idProyecto];
        $this->executeStoredProcedure('SetEstado', 'DELETE_Formulario5_EstudiantesProyecto', $data, 'Transaccion', true);
        foreach ($estudiantes as $datos) {
            $this->guardarEstudiantesProyecto($datos, $idProyecto);
        }
    }

    // COMENTARIO ESTUDIANTES
    public function actualizarComentarioEstudiantes($idProyecto, $comentario)
    {
        $data = ['IdPropuesta' => $idProyecto];
        $this->executeStoredProcedure('SetEstado', 'DELETE_Formulario5_ComentariosEstudiantes', $data, 'Transaccion', true);
        $this->guardarComentarioEstudiantes($comentario, $idProyecto);
    }

    // ESTUDIANTES PROGRAMAS
    public function actualizarEstudiantesProgramas($idProyecto, $estudiantesProgramas)
    {
        $data = ['IdPropuesta' => $idProyecto];
        $this->executeStoredProcedure('SetEstado', 'DELETE_Formulario5_EstudiantesProgramas', $data, 'Transaccion', true);
        foreach ($estudiantesProgramas as $datos) {
            $this->guardarEstudiantesPrograma($datos, $idProyecto);
        }
    }

    // COMENTARIO PROGRAMAS
    public function actualizarComentarioProgramas($idProyecto, $comentario)
    {
        $data = ['IdPropuesta' => $idProyecto];
        $this->executeStoredProcedure('SetEstado', 'DELETE_Formulario5_ComentariosProgramas', $data, 'Transaccion', true);
        $this->guardarComentarioProgramas($comentario, $idProyecto);
    }

    // INCLUSIÓN ESTUDIANTES
    public function actualizarInclusionEstudiantes($idProyecto, $datos)
    {
        $data = ['IdPropuesta' => $idProyecto];
        $this->executeStoredProcedure('SetEstado', 'DELETE_Formulario5_InclusionEstudiantes', $data, 'Transaccion', true);
        $this->guardarInclusionEstudiantes($datos, $idProyecto);
    }

    // ACTUALIZAR ESTUDIANTES POR CICLO
    public function actualizarEstudiantesCiclos($idProyecto, $estudiantesCiclos)
    {
        $data = ['IdPropuesta' => $idProyecto];
        $result = $this->executeStoredProcedure('GetForms', 'GET_FORM5_ESTUDIANTES_CICLOS', $data, 'Transaccion', true);
        foreach ($estudiantesCiclos as $datos) {
            $this->guardarEstudiantesCiclos($datos, $idProyecto);
        }
    }
}//fin
