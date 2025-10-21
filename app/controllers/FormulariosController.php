<?php
require_once ROOT_PATH . '/app/models/InstitucionService.php';
require_once ROOT_PATH . '/app/models/Formulario1.php';
require_once ROOT_PATH . '/app/models/Formulario2.php';
require_once ROOT_PATH . '/app/models/Formulario3.php';
require_once ROOT_PATH . '/app/models/Formulario4.php';
require_once ROOT_PATH . '/app/models/Formulario5.php';
require_once ROOT_PATH . '/app/models/Formulario6.php';
require_once ROOT_PATH . '/app/models/Formulario7.php';
require_once ROOT_PATH . '/app/models/Formulario8.php';
require_once ROOT_PATH . '/app/models/Formulario9.php';
require_once ROOT_PATH . '/app/models/Formulario10.php';
require_once ROOT_PATH . '/app/models/Formulario11.php';

class FormulariosController
{
    // 1. Declara las propiedades privadas para guardar los objetos
    private $institucionService;
    private $validator;

    // 2. Este es el constructor. Se ejecuta una sola vez.
    public function __construct()
    {
        // Mueve los 'require_once' comunes aquí
        require_once ROOT_PATH . '/app/helpers/Validator.php';
        require_once ROOT_PATH . '/app/models/InstitucionService.php';

        // Crea los objetos UNA SOLA VEZ y guárdalos en las propiedades
        $this->institucionService = new InstitucionService();
        $this->validator = new Validator();
    }

    private function cargarDatosBaseFormulario()
    {
        $token = $this->institucionService->GetToken();
        return $this->institucionService->getCrearFormData();
    }

    /**
     * Valida una tabla dinámica asegurando que no esté vacía y que los campos
     * requeridos en cada fila estén llenos.
     *
     * @param array &$errores          El array de errores (se pasa por referencia).
     * @param array $datos             Los datos del formulario (ej. $_POST).
     * @param string $campoPrincipal   El campo que determina si hay filas (ej. 'facultad_tutor').
     * @param array $camposAValidar    Los campos a revisar en cada fila.
     * @param string $mensajeError     El mensaje de error si la tabla está vacía.
     */
    private function _validarTablaDinamica(array &$errores, array $datos, string $campoPrincipal, array $camposAValidar, string $mensajeError)
    {
        if (empty($datos[$campoPrincipal])) {
            // La tabla no tiene ni una fila.
            $errores[str_replace('_', '', $campoPrincipal) . '_general'] = $mensajeError;
        } else {
            // La tabla tiene filas, validamos cada una.
            foreach ($datos[$campoPrincipal] as $key => $value) {
                foreach ($camposAValidar as $campo) {
                    // Usamos trim() para campos de texto por si solo contienen espacios
                    if (empty(trim($datos[$campo][$key] ?? ''))) {
                        $errores[$campo][$key] = 'Campo requerido.';
                    }
                }
            }
        }
    }

    /**
     * Centraliza la definición de permisos basados en el rol del usuario.
     * @return array Un array con permisos booleanos (true/false).
     */
    private function _setupPermissions()
    {
        // Obtiene el rolId de la sesión, que debe coincidir con ID_PERFIL
        $rolId = $_SESSION['user']['perfilId'] ?? 0;

        // Definimos los permisos por defecto (sin acceso)
        $permisos = [
            'puede_crear' => false,
            'puede_cambiar_estado' => [],
            'permite_editar' => false,
            'permite_comentar' => false,
        ];

        // Asignamos los permisos correctos según la tabla de roles
        switch ($rolId) {
            case 20255: // SEGUIMIENTO_PROYECTO_ADMIN
                $permisos['puede_crear'] = true;
                $permisos['puede_cambiar_estado'] = ['BORRADOR', 'REVISADO', 'CORREGIDO', 'APROBADO', 'RECHAZADO'];
                $permisos['permite_editar'] = true;
                $permisos['permite_comentar'] = true;
                break;

            case 20253: // SEGUIMIENTO-PROYECTO-GESTOR
                $permisos['puede_crear'] = true;
                $permisos['puede_cambiar_estado'] = ['BORRADOR', 'REVISADO', 'CORREGIDO'];
                $permisos['permite_editar'] = false;
                $permisos['permite_comentar'] = true;
                break;

            case 20252: // SEGUIMIENTO-PROYECTO-DIRECTOR
                $permisos['puede_crear'] = false;
                $permisos['puede_cambiar_estado'] = ['APROBADO', 'RECHAZADO'];
                $permisos['permite_editar'] = false;
                $permisos['permite_comentar'] = false;
                break;

            case 20259: // SEGUIMIENTO_PROYECTO_DOCENTE (Equivalente a USUARIO NORMAL)
                $permisos['puede_crear'] = true;
                $permisos['puede_cambiar_estado'] = [];
                $permisos['permite_editar'] = true;
                $permisos['permite_comentar'] = false;
                break;

            // Puedes agregar más roles aquí si es necesario
            // case 20254: // SEGUIMIENTO-PROYECTO-COORDINADOR
            //     // Define aquí qué puede hacer el Coordinador
            //     break;
        }

        return $permisos;
    }

    // En: app/controllers/FormulariosController.php (añadir este nuevo método)

    public function verTrazabilidad()
    {
        // 1. Validar que el ID es un número entero válido.
        $idPropuesta = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        if (!$idPropuesta) {
            // Muestra un error claro si el ID no es válido o no se proporciona.
            echo "<p>Error: ID de propuesta no válido.</p>";
            return;
        }

        // 2. Cargar el modelo y obtener los datos.
        require_once __DIR__ . '/../models/TrazabilidadModel.php';
        $TrazabilidadModel = new TrazabilidadModel();
        $trazabilidad = $TrazabilidadModel->obtenerTrazabilidadPorPropuesta($idPropuesta);

        // 3. Cargar la vista del modal con los datos obtenidos.
        $this->view('formularios/modalPropuestas', [
            'trazabilidad' => $trazabilidad
        ]);
    }
    protected function view($vista, $data = [])
    {
        extract($data);
        include ROOT_PATH . "/app/views/{$vista}.php";
    }

    // VALIDACIONES DE FORMULARIOS

    /**
     * Función auxiliar para obtener los datos del Director del Proyecto del paso 4
     * si cumple la condición de ser el mismo que el decano de la facultad.
     * @param int $id_proyecto El ID del proyecto actual.
     * @return array|null Un array con los datos del director coincidente, o null si no se encuentra.
     */
    private function _obtenerDatosDirectorCoincidente($id_proyecto)
    {
        require_once ROOT_PATH . '/app/models/Formulario4.php';
        $modelo4 = new Formulario4();
        $username = $_SESSION['user']['username'];
        $token = $this->institucionService->GetToken();

        $datosPaso4 = $modelo4->obtenerDatosCompletosPaso4($id_proyecto);
        // var_dump($datosPaso4);
        // die();

        if (empty($datosPaso4['facultad_decano']) || empty($datosPaso4['facultad_director'])) {
            return null;
        }

        $facultad_decano_id = $datosPaso4['facultad_decano'];
        $carrera_decano_id = $datosPaso4['carrera_decano'];
        $directores_paso4 = $datosPaso4['facultad_director'];

        foreach ($directores_paso4 as $key => $facultad_director_id) {
            $carrera_director_id = $datosPaso4['carrera_director'][$key] ?? '';

            if ($facultad_decano_id == $facultad_director_id && $carrera_decano_id == $carrera_director_id) {
                $cedula_director = $datosPaso4['nombre_director'][$key] ?? '';


                $facultadesData = $this->institucionService->GetFacultad($username, $token);
                if (!empty($facultadesData['dtResultado'])) {
                    foreach ($facultadesData['dtResultado'] as $fac) {
                        if ($fac['CodFacultad'] == $facultad_director_id) {
                            $nombre_facultad = $fac['Facultad'];
                            break;
                        }
                    }
                }

                if ($facultad_director_id && $carrera_director_id) {

                    $carrerasData = $this->institucionService->GetCarrera($username, (string) $facultad_director_id, $token);

                    if (!empty($carrerasData['dtResultado'])) {
                        foreach ($carrerasData['dtResultado'] as $carrera) {
                            if ($carrera['CodCarrera'] == $carrera_director_id) {
                                $nombre_carrera = $carrera['Carrera'];
                                break;
                            }
                        }
                    }

                    $docentesData = $this->institucionService->GetDocentes($username, (string) $facultad_director_id, (string) $carrera_director_id, $token);
                    if (!empty($docentesData['dtResultado'])) {
                        foreach ($docentesData['dtResultado'] as $docente) {
                            if ($docente['CedulaDocente'] == $cedula_director) {
                                $nombre_director = $docente['Nombres'];
                                break;
                            }
                        }
                    }
                }

                return [
                    'cedula' => $cedula_director,
                    'nombre' => $nombre_director,
                    'correo' => $datosPaso4['director_correo'][$key] ?? '',
                    'telefono' => $datosPaso4['director_telefono'][$key] ?? '',
                    'facultad_id' => $facultad_director_id,
                    'carrera_id' => $carrera_director_id,
                    'facultad_nombre' => $nombre_facultad,
                    'carrera_nombre' => $nombre_carrera
                ];
            }
        }
        return null;
    }

    // En FormulariosController.php

    private function _validarPaso1(array $datos): array
    {
        $errores = [];
        $reglas = [
            'Titulo' => ['required' => true, 'message' => 'El título es obligatorio.'],
            'eje_estrategico' => ['required' => true, 'message' => 'El eje estratégico es obligatorio.'],
            'area' => ['required' => true, 'message' => 'El área es obligatoria.'],
            'subarea' => ['required' => true, 'message' => 'La subárea es obligatoria.'],
            'subarea_especifica' => ['required' => true, 'message' => 'La subárea específica es obligatoria.']
        ];

        if (!empty($datos['tipo_programa']) && is_array($datos['tipo_programa'])) {
            foreach ($datos['tipo_programa'] as $idProgamaA) {
                $reglas['nombre_programa_' . $idProgamaA] = ['required' => true];
                $reglas['autores_programa_' . $idProgamaA] = ['required' => true];
                $reglas['anio_programa_' . $idProgamaA] = [
                    'required' => true,
                    'range' => true,
                    'min' => 2010,
                    'max' => 2040,
                    'message' => 'El año debe ser válido (entre 2010-2040).'
                ];
                $reglas['enlace_programa_' . $idProgamaA] = ['url' => true];
                $reglas['descripcion_programa_' . $idProgamaA] = ['required' => true];
            }
        } else {
            $errores['tipo_programa_general'] = 'Debe seleccionar al menos un programa.';
        }

        // AHORA USAMOS EL VALIDADOR QUE YA EXISTE
        $validation_errors = $this->validator->validate($datos, $reglas);

        return array_merge($errores, $validation_errors);
    }

    private function validarPaso5($datos, $autoFilled)
    {
        $errores = [];
        $reglas = [];

        if ($autoFilled) {
            $reglas = [
                'acreditado_director' => ['required' => true, 'message' => 'El campo acreditado es obligatorio.'],
                'categoria_director' => ['required' => true, 'message' => 'La categoría es obligatoria.'],
                'dedicacion_director' => ['required' => true, 'message' => 'La dedicación es obligatoria.'],
            ];
        }
        $reglas['acciones_contribucion'] = ['required' => true, 'message' => 'Las acciones de contribución son obligatorias.'];
        $validation_errors = $this->validator->validate($datos, $reglas);
        $errores = array_merge($errores, $validation_errors);

        // 1. Docentes Tutores
        if (empty($datos['facultad_tutor'])) {
            $errores['tutores_general'] = 'Debe agregar al menos un Docente Tutor.';
        } else {
            foreach ($datos['facultad_tutor'] as $key => $value) {
                if (empty($datos['facultad_tutor'][$key] ?? null))
                    $errores['facultad_tutor'][$key] = 'Campo requerido.';
                if (empty($datos['carrera_tutor'][$key] ?? null))
                    $errores['carrera_tutor'][$key] = 'Campo requerido.';
                if (empty($datos['docente_tutor'][$key] ?? null))
                    $errores['docente_tutor'][$key] = 'Campo requerido.';
            }
        }

        // 2. Estudiantes del Proyecto
        if (empty($datos['facultad_estud'])) {
            $errores['estudiantes_general'] = 'Debe agregar al menos una fila de estudiantes.';
        } else {
            foreach ($datos['facultad_estud'] as $key => $value) {
                if (empty($datos['facultad_estud'][$key] ?? null))
                    $errores['facultad_estud'][$key] = 'Campo requerido.';
                if (empty($datos['carrera_estud'][$key] ?? null))
                    $errores['carrera_estud'][$key] = 'Campo requerido.';
                if (empty($datos['estudiante_numero'][$key] ?? null))
                    $errores['estudiante_numero'][$key] = 'Campo requerido.';
            }
        }

        // 3. Programas de Articulación
        if (empty($datos['programa_articulacion_nombre'])) {
            $errores['programas_general'] = 'Debe agregar al menos un programa de articulación.';
        } else {
            foreach ($datos['programa_articulacion_nombre'] as $key => $value) {
                if (empty(trim($datos['programa_articulacion_nombre'][$key] ?? '')))
                    $errores['programa_articulacion_nombre'][$key] = 'Campo requerido.';
                if (empty($datos['programa_articulacion_numero'][$key] ?? null))
                    $errores['programa_articulacion_numero'][$key] = 'Campo requerido.';
            }
        }

        return $errores;
    }

    private function precargarDatosPaso5($datosFormulario)
    {
        $username = $_SESSION['user']['username'];
        $token = $this->institucionService->GetToken();

        // Precargar para Docentes Tutores
        if (!empty($datosFormulario['facultad_tutor'])) {
            foreach ($datosFormulario['facultad_tutor'] as $key => $facultadId) {
                if ($facultadId) {
                    $carreras = $this->institucionService->GetCarrera($username, (string) $facultadId, $token);
                    $datosFormulario['carreras_tutor_posibles'][$key] = $carreras['dtResultado'] ?? [];
                    $carreraId = $datosFormulario['carrera_tutor'][$key] ?? null;

                    if ($carreraId) {
                        $docentes = $this->institucionService->GetDocentes($username, (string) $facultadId, (string) $carreraId, $token);
                        $datosFormulario['docentes_tutor_posibles'][$key] = $docentes['dtResultado'] ?? [];
                    }
                }
            }
        }

        // Precargar para Estudiantes del Proyecto
        if (!empty($datosFormulario['facultad_estud'])) {
            foreach ($datosFormulario['facultad_estud'] as $key => $facultadId) {
                if ($facultadId) {
                    // Usamos "$this->"
                    $carreras = $this->institucionService->GetCarrera($username, (string) $facultadId, $token);
                    $datosFormulario['carreras_estud_posibles'][$key] = $carreras['dtResultado'] ?? [];
                }
            }
        }

        return $datosFormulario;
    }

    // Validaciones FORMULARIO 6

    private function validarPaso6($datos)
    {
        $reglas = [
            'justificacion' => ['required' => true, 'message' => 'La justificación es obligatoria.'],
            'linea_base' => ['required' => true, 'message' => 'La línea base es obligatoria.'],
            'fundamentacion_teorica' => ['required' => true, 'message' => 'La fundamentación teórica es obligatoria.']
        ];

        return $this->validator->validate($datos, $reglas);
    }

    // Validaciones FORMULARIO 7

    private function validarPaso7($datos)
    {
        $errores = [];

        // Reglas para los campos estáticos (no cambian)
        $reglas = [
            'descripcion' => ['required' => true, 'message' => 'La descripción de la población es obligatoria.'],
            'numero_poblacion' => ['required' => true, 'numeric' => true, 'message' => 'El número total de población es obligatorio y debe ser numérico.'],
            'caracteristicas' => ['required' => true, 'message' => 'Las características de la población son obligatorias.'],
            'detalle_beneficiarios_indirectos' => ['required' => true, 'message' => 'Detalle los beneficiarios indirectos.'],
            'detalle_beneficiarios_directos' => ['required' => true, 'message' => 'Detalle los beneficiarios directos.']
        ];

        // 1. Comprobar si se ha llenado al menos una fila de beneficiarios directos
        $beneficiarios_directos_llenos = false;
        if (!empty($datos['directo'])) {
            foreach ($datos['directo'] as $ben) {
                // Se considera "lleno" si tiene descripción o número
                if (!empty(trim($ben['descripcion'])) || !empty($ben['numero'])) {
                    $beneficiarios_directos_llenos = true;
                    break;
                }
            }
        }

        // 2. Comprobar si se ha llenado al menos una fila de beneficiarios indirectos
        $beneficiarios_indirectos_llenos = false;
        if (!empty($datos['indirecto'])) {
            foreach ($datos['indirecto'] as $ben) {
                if (!empty(trim($ben['descripcion'])) || !empty($ben['numero'])) {
                    $beneficiarios_indirectos_llenos = true;
                    break;
                }
            }
        }

        // 3. Lanzar error si NO hay beneficiarios directos
        if (!$beneficiarios_directos_llenos) {
            $errores['beneficiarios_directos_general'] = 'Debe especificar al menos un beneficiario directo.';
        }

        // 4. Lanzar error si NO hay beneficiarios indirectos
        if (!$beneficiarios_indirectos_llenos) {
            $errores['beneficiarios_indirectos_general'] = 'Debe especificar al menos un beneficiario indirecto.';
        }

        // Se ejecutan las validaciones de las reglas estáticas
        $validation_errors = $this->validator->validate($datos, $reglas);

        // Se unen todos los errores
        return array_merge($errores, $validation_errors);
    }

    // Validaciones FORMULARIO 8

    private function validarPaso8($datos)
    {
        $errores = [];

        $reglas = [
            'objetivo_general' => ['required' => true, 'message' => 'El objetivo general es obligatorio.'],
            'metodologia' => ['required' => true, 'message' => 'La metodología es obligatoria.'],
            'dialogo' => ['required' => true, 'message' => 'La descripción del diálogo es obligatoria.'],
            'interculturalidad' => ['required' => true, 'message' => 'La descripción de interculturalidad es obligatoria.'],
            'sostenibilidad_ambiental' => ['required' => true, 'message' => 'La descripción de sostenibilidad ambiental es obligatoria.'],
            'evaluacion_impacto' => ['required' => true, 'message' => 'La metodología de evaluación de impacto es obligatoria.'],
            'linea_comparacion' => ['required' => true, 'message' => 'La línea de comparación es obligatoria.'],
            'actividades' => ['required' => true, 'message' => 'La descripción de actividades es obligatoria.']
        ];

        // Validar que al menos un objetivo específico no esté vacío
        if (empty($datos['objetivos']) || empty(trim(implode('', $datos['objetivos'])))) {
            $errores['objetivos_general'] = 'Debe agregar al menos un objetivo específico.';
        }

        // Validar que al menos un indicador no esté vacío
        $tipos_impacto = ['ambiental', 'social', 'economico', 'politico', 'cientifico'];
        $impactos_llenos = false;
        foreach ($tipos_impacto as $tipo) {
            $nombre_input = "impacto_{$tipo}_indicador";
            if (!empty($datos[$nombre_input]) && !empty(trim(implode('', $datos[$nombre_input])))) {
                $impactos_llenos = true;
                break;
            }
        }
        if (!$impactos_llenos) {
            $errores['impactos_general'] = 'Debe agregar al menos un indicador en cualquier tipo de impacto.';
        }

        // AQUÍ USAMOS EL VALIDADOR DEL CONSTRUCTOR
        $validation_errors = $this->validator->validate($datos, $reglas);
        return array_merge($errores, $validation_errors);
    }
    // Validaciones FORMULARIO 9

    private function validarPaso9($datos)
    {
        require_once ROOT_PATH . '/app/helpers/Validator.php';
        $validator = new Validator();
        $errores = [];

        // Validar campos numéricos
        $campos_numericos = [
            'ponencias_nacionales',
            'ponencias_internacionales',
            'articulos_cientificos',
            'libros_publicados',
            'capitulos_libros',
            'revistas_divulgacion',
            'otras_publicaciones',
            'talleres_capacitacion',
            'productos_tecnologicos',
            'productos_artisticos',
            'productos_culturales',
            'productos_sociales'
        ];
        foreach ($campos_numericos as $campo) {
            if (!empty($datos[$campo]) && !is_numeric($datos[$campo])) {
                $errores[$campo] = 'Este campo debe ser un número.';
            }
        }

        // Validar textareas requeridos
        if (empty(trim($datos['referencias_citadas']))) {
            $errores['referencias_citadas'] = 'Las referencias citadas son obligatorias.';
        }

        // Validar campos dinámicos
        if (empty($datos['indicador'])) {
            $errores['resultados_general'] = 'Debe agregar al menos un indicador para cada objetivo.';
        } else {
            foreach ($datos['indicador'] as $objIndex => $indicadores) {
                if (empty(array_filter($indicadores))) {
                    $errores['indicador_obj_' . $objIndex] = 'Debe seleccionar al menos un indicador para este objetivo.';
                }
            }
        }

        return $errores;
    }

    // Validaciones FORMULARIO 10

    private function validarPaso10($datos)
    {
        $errores = [];

        // Validar Matriz de Seguimiento
        if (empty($datos['cantidad'])) {
            $errores['matriz_general'] = "Debe completar la matriz de seguimiento.";
        } else {
            foreach ($datos['cantidad'] as $index => $val) {
                if (empty($val))
                    $errores['cantidad'][$index] = 'Requerido';
                if (empty($datos['medio_verificacion'][$index]))
                    $errores['medio_verificacion'][$index] = 'Requerido';
                if (empty($datos['fecha_parcial'][$index]))
                    $errores['fecha_parcial'][$index] = 'Requerido';
                if (empty($datos['responsable_control'][$index]))
                    $errores['responsable_control'][$index] = 'Requerido';
            }
        }

        // Validar Cronograma
        if (empty($datos['actividad'])) {
            $errores['cronograma_general'] = "Debe completar el cronograma de actividades.";
        } else {
            foreach ($datos['actividad'] as $objIndex => $actividades) {
                foreach ($actividades as $i => $act) {
                    if (empty($act))
                        $errores['actividad'][$objIndex][$i] = 'Requerido';
                    // Añadir más validaciones si es necesario...
                }
            }
        }

        return $errores;
    }

    // FIN VALIDACIONES DE FORMULARIOS

    public function index()
    {
        require_once ROOT_PATH . '/app/models/ProyectosModel.php';
        $proyectosModel = new ProyectosModel();
        $proyectos = $proyectosModel->obtenerTodos();


        $permisos = $this->_setupPermissions();

        // Pasamos los permisos a la vista
        extract($permisos);
        require_once ROOT_PATH . '/app/views/formularios/index.php';
    }

    // En: app/controllers/FormulariosController.php

    public function nuevaRespuesta()
    {
        // 1. Limpia cualquier ID de proyecto o formulario anterior de la sesión.
        unset($_SESSION['id_proyecto']);
        unset($_SESSION['id_formulario1']);

        // 2. Crea el nuevo proyecto vacío.
        $modelo = new Formulario1();
        $idPropuesta = $modelo->crearProyecto("Proyecto sin título");

        // 3. Guarda el NUEVO ID en la sesión.
        $_SESSION['id_proyecto'] = $idPropuesta;

        // =======================================================
        // INICIO: CORRECCIÓN EN EL REGISTRO DE TRAZABILIDAD
        // =======================================================
        require_once __DIR__ . '/../models/TrazabilidadModel.php';
        $trazabilidadModel = new TrazabilidadModel();
        $usuario = $_SESSION['user']['nombre'] . ' ' . ($_SESSION['user']['apellidos'] ?? '');
        $rol = $_SESSION['user']['rol'] ?? 'Rol Desconocido';

        // ASÍ ES LA FORMA CORRECTA DE LLAMAR A LA FUNCIÓN:
        // El primer parámetro (idDocGen) es null.
        // El último parámetro (idPropuesta) es el que tiene el valor.
        $trazabilidadModel->guardarAccion(
            null, // <--- Parámetro para idDocGen
            $usuario,
            $rol,
            'Propuesta Creada',
            'Se generó un nuevo registro de propuesta en estado borrador.',
            $idPropuesta // <--- Parámetro para idPropuesta
        );
        // =======================================================
        // FIN DEL BLOQUE
        // =======================================================

        // 4. Redirige al paso 1.
        header("Location: index.php?c=formularios&m=paso1");
        exit;
    }

    public function eliminar()
    {
        // 1. Validar que el ID es un número entero válido
        if (!isset($_GET['id']) || !filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
            $_SESSION['mensaje'] = ['tipo' => 'error', 'texto' => 'Error: ID de proyecto no válido.'];
            header('Location: index.php?c=Proyectos&m=index');
            exit();
        }
        $idProyecto = (int) $_GET['id'];

        // 2. Cargar tu modelo específico
        require_once ROOT_PATH . '/app/models/PropuestaModel.php';
        $propuestaModel = new PropuestaModel();

        // 3. Llamar al método robusto y dar feedback al usuario
        // if ($propuestaModel->eliminarPorId($idProyecto)) {
        //     $_SESSION['mensaje'] = ['tipo' => 'success', 'texto' => '¡El proyecto ha sido eliminado correctamente!'];
        // } else {
        //     $_SESSION['mensaje'] = ['tipo' => 'error', 'texto' => 'Error: No se pudo eliminar el proyecto. Es posible que ya haya sido eliminado o no exista.'];
        // }

        // 4. Redirigir de vuelta a la lista
        header('Location: index.php?c=Proyectos&m=index');
        exit();
    }

    // En tu controlador de Formularios

    public function paso1()
    {
        $modelo = new Formulario1();

        // --- Lógica para procesar el envío del formulario (POST) ---
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id_proyecto = $_SESSION['id_proyecto'] ?? null;
            if (!$id_proyecto) {
                header("Location: index.php?ruta=formularios");
                exit;
            }

            // Llama al método de validación centralizado
            $errores = $this->_validarPaso1($_POST);

            if (empty($errores)) {
                // SIN ERRORES: Guarda los datos y redirige al siguiente paso
                $id_formulario1 = $modelo->guardarPaso1($id_proyecto, $_POST);
                $_SESSION['id_formulario1'] = $id_formulario1;
                //var_dump($_SESSION['id_formulario1']);
                //die();

                header("Location: index.php?c=formularios&m=paso2");
                exit;
            } else {
                // CON ERRORES: Recarga la vista con los datos enviados y los errores
                $datos_guardados = $modelo->obtenerDatosCompletosPaso1($id_proyecto);
                $datos_a_mostrar = array_merge($datos_guardados, $_POST);

                $formData = $this->cargarDatosBaseFormulario();
                $formData['errores'] = $errores;
                $formData['datos_enviados'] = $datos_a_mostrar;
                extract($formData);
                require_once ROOT_PATH . '/app/views/formularios/paso1.php';
                return;
            }
        }

        // --- Lógica para la carga inicial de la página (GET) ---
        $id_proyecto = $_SESSION['id_proyecto'] ?? null;
        $datos_guardados = [];
        if ($id_proyecto) {
            $datos_guardados = $modelo->obtenerDatosCompletosPaso1($id_proyecto);
        }
        $formData = $this->cargarDatosBaseFormulario();
        $formData['datos_enviados'] = $datos_guardados;
        extract($formData);
        require_once ROOT_PATH . '/app/views/formularios/paso1.php';
    }

    //paso 2//

    // En tu controlador FormulariosController.php

    public function paso2()
    {
        // Carga los helpers y modelos necesarios
        require_once ROOT_PATH . '/app/helpers/Validator.php';
        require_once ROOT_PATH . '/app/models/Formulario2.php';
        $validator = new Validator();
        $modelo = new Formulario2();

        $id_proyecto = $_SESSION['id_proyecto'] ?? null;
        if (!$id_proyecto) {
            header("Location: index.php?c=formularios&m=index");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Define las reglas de validación para los campos del paso 2
            $reglas = [
                'objetivo_sostenible' => ['required' => true],
                'eje' => ['required' => true],
                'objetivo_nacional' => ['required' => true],
                'dominios' => ['required' => true],
                'lineas' => ['required' => true]
            ];

            $errores = $validator->validate($_POST, $reglas);

            if (empty($errores)) {
                // SIN ERRORES: Guarda los datos
                $modelo->guardarPaso2($id_proyecto, $_POST);

                // Redirige al siguiente paso
                header("Location: index.php?c=formularios&m=paso3");
                exit;
            } else {
                // CON ERRORES: Recarga la vista con los errores
                $formData = $this->cargarDatosBaseFormulario(); // <-- USA LA FUNCIÓN EXISTENTE
                $formData['errores'] = $errores;
                $formData['datos_enviados'] = $_POST;
                $formData['idProyecto'] = $id_proyecto;
                extract($formData);
                require_once ROOT_PATH . '/app/views/formularios/paso2.php';
                return;
            }
        }

        // --- LÓGICA PARA CARGA INICIAL (MÉTODO GET) ---
        $datosGuardados = $modelo->obtenerDatosCompletosPaso2($id_proyecto);

        $formData = $this->cargarDatosBaseFormulario(); // <-- USA LA FUNCIÓN EXISTENTE

        $formData['datos_enviados'] = $datosGuardados;
        $formData['idProyecto'] = $id_proyecto;

        extract($formData);
        require_once ROOT_PATH . '/app/views/formularios/paso2.php';
    }
    // Paso3

    public function paso3()
    {
        require_once ROOT_PATH . '/app/helpers/Validator.php';
        require_once ROOT_PATH . '/app/models/Formulario3.php';
        $validator = new Validator();
        $modelo = new Formulario3();
        $institucionService = new InstitucionService(); // Necesario para las facultades

        $id_proyecto = $_SESSION['id_proyecto'] ?? null;
        if (!$id_proyecto) {
            header("Location: index.php?c=formularios&m=index");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $reglas = [
                'contexto' => ['required' => true],
                'duracion' => ['required' => true]
            ];

            $errores = [];

            // Validación para la tabla dinámica de Perfil de Egreso
            if (empty($_POST['facultad'])) {
                $errores['perfil_egreso_general'] = 'Debe agregar al menos una fila de facultad/carrera.';
            } else {
                foreach ($_POST['facultad'] as $key => $value) {
                    if (empty($_POST['facultad'][$key]))
                        $errores['facultad'][$key] = true;
                    if (empty($_POST['carrera'][$key]))
                        $errores['carrera'][$key] = true;
                    if (empty(trim($_POST['programa'][$key])))
                        $errores['programa'][$key] = true;
                    if (empty(trim($_POST['aporte_perfil'][$key])))
                        $errores['aporte_perfil'][$key] = true;
                }
            }

            // --- VALIDACIÓN AÑADIDA PARA COBERTURA ---
            if (empty($_POST['cobertura'])) {
                $errores['cobertura_general'] = 'Debe seleccionar al menos una opción de cobertura.';
            }
            // --- FIN DE LA VALIDACIÓN AÑADIDA ---

            $validation_errors = $validator->validate($_POST, $reglas);
            $errores = array_merge($errores, $validation_errors);

            if (empty($errores)) {
                $modelo->guardarPaso3($id_proyecto, $_POST);
                header("Location: index.php?c=formularios&m=paso4");
                exit;
            } else {
                // CON ERRORES: Volvemos a cargar todo lo necesario para la vista
                $formData = $this->cargarDatosBaseFormulario();
                $token = $institucionService->GetToken();
                $formData['facultadData'] = $institucionService->GetFacultad($_SESSION['user']['username'], $token); // <-- RECUERDA CAMBIAR ESTO
                $formData['errores'] = $errores;
                $formData['datos_enviados'] = $_POST;
                $formData['idProyecto'] = $id_proyecto;
                extract($formData);
                require_once ROOT_PATH . '/app/views/formularios/paso3.php';
                return;
            }
        }

        // --- Carga de datos para el método GET ---
        $datosGuardados = $modelo->obtenerDatosCompletosPaso3($id_proyecto);
        $formData = $this->cargarDatosBaseFormulario();
        $token = $institucionService->GetToken();
        $formData['facultadData'] = $institucionService->GetFacultad($_SESSION['user']['username'], $token); // <-- RECUERDA CAMBIAR ESTO
        $formData['datos_enviados'] = $datosGuardados;
        $formData['idProyecto'] = $id_proyecto;
        extract($formData);
        require_once ROOT_PATH . '/app/views/formularios/paso3.php';
    }



    // Archivo: app/controllers/FormulariosController.php

    public function paso4()
    {
        require_once ROOT_PATH . '/app/helpers/Validator.php';
        require_once ROOT_PATH . '/app/models/Formulario4.php';
        $validator = new Validator();
        $modelo = new Formulario4();
        $institucionService = new InstitucionService();

        $id_proyecto = $_SESSION['id_proyecto'] ?? null;
        if (!$id_proyecto) {
            header("Location: index.php?c=formularios&m=index");
            exit;
        }

        $username = $_SESSION['user']['username'];
        $token = $institucionService->GetToken();
        $formData = $this->cargarDatosBaseFormulario();
        $formData['facultadData'] = $institucionService->GetFacultad($username, $token);
        $datos_enviados = [];
        $errores = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Reglas de validación para campos estáticos
            $reglas = [
                'facultad_decano' => ['required' => true, 'message' => 'La facultad del decano es obligatoria.'],
                'carrera_decano' => ['required' => true, 'message' => 'La carrera del decano es obligatoria.'],
                'decano_decano' => ['required' => true, 'message' => 'El nombre del decano es obligatorio.'],
                'decano_correo' => ['required' => true, 'email' => true, 'message' => 'El correo del decano es obligatorio y debe ser válido.'],
                'decano_telefono' => ['required' => true, 'message' => 'El teléfono del decano es obligatorio.'],
                'externa_nombre' => ['required' => true, 'message' => 'El nombre de la institución es obligatorio.'],
                'externa_repres_nombre' => ['required' => true, 'message' => 'El representante legal es obligatorio.'],
                'externa_dir' => ['required' => true, 'message' => 'La dirección es obligatoria.'],
                'externa_tel' => ['required' => true, 'message' => 'El teléfono es obligatorio.'],
                'externa_correo' => ['required' => true, 'email' => true, 'message' => 'El correo es obligatorio y debe ser válido.'],
                'aliado_nombre' => ['required' => true, 'message' => 'El nombre del aliado es obligatorio.'],
                'aliado_repres_nombre' => ['required' => true, 'message' => 'El representante del aliado es obligatorio.'],
                'aliado_direccion' => ['required' => true, 'message' => 'La dirección del aliado es obligatoria.'],
                'aliado_tel' => ['required' => true, 'message' => 'El teléfono del aliado es obligatorio.'],
                'aliado_correo' => ['required' => true, 'email' => true, 'message' => 'El correo del aliado es obligatorio y debe ser válido.'],
            ];

            // Se ejecuta la validación de los campos estáticos
            $errores = $validator->validate($_POST, $reglas);

            // +++ INICIO: VALIDACIÓN DEL LOGO AÑADIDA +++
            // Se valida el archivo del logo. Es opcional, pero si se sube, no debe tener errores.
            if (isset($_FILES['logo_proyecto']) && $_FILES['logo_proyecto']['error'] !== UPLOAD_ERR_OK) {
                // UPLOAD_ERR_NO_FILE significa que el campo se dejó vacío, lo cual está permitido.
                if ($_FILES['logo_proyecto']['error'] !== UPLOAD_ERR_NO_FILE) {
                    $errores['logo_proyecto'] = 'Error al subir el archivo. Verifique el tamaño o el formato.';
                }
            }
            // +++ FIN: VALIDACIÓN DEL LOGO AÑADIDA +++

            // Validación para la tabla dinámica de Directores de Proyecto
            if (empty($_POST['facultad_director'])) {
                $errores['director_general'] = 'Debe agregar al menos un Director de Proyecto.';
            } else {
                foreach ($_POST['facultad_director'] as $key => $facultad) {
                    if (empty($facultad))
                        $errores['facultad_director'][$key] = 'La facultad es obligatoria.';
                    if (empty($_POST['carrera_director'][$key]))
                        $errores['carrera_director'][$key] = 'La carrera es obligatoria.';
                    if (empty($_POST['nombre_director'][$key]))
                        $errores['nombre_director'][$key] = 'El director es obligatorio.';
                    if (empty($_POST['director_correo'][$key])) {
                        $errores['director_correo'][$key] = 'El correo es obligatorio.';
                    } else if (!filter_var($_POST['director_correo'][$key], FILTER_VALIDATE_EMAIL)) {
                        $errores['director_correo'][$key] = 'Formato de correo no válido.';
                    }
                    if (empty($_POST['director_telefono'][$key]))
                        $errores['director_telefono'][$key] = 'El teléfono es obligatorio.';
                }
            }

            if (!empty($_POST['facultad_coop'])) {
                // Como el usuario agregó filas, ahora sí validamos que cada una esté completa.
                foreach ($_POST['facultad_coop'] as $key => $facultad) {
                    // Esta validación interna es importante para la calidad de los datos.
                    if (empty($facultad))
                        $errores['facultad_coop'][$key] = 'La facultad es obligatoria.';
                    if (empty($_POST['carrera_coop'][$key]))
                        $errores['carrera_coop'][$key] = 'La carrera es obligatoria.';
                    if (empty($_POST['docente_coop'][$key]))
                        $errores['docente_coop'][$key] = 'El docente es obligatorio.';
                    if (empty($_POST['correo'][$key])) {
                        $errores['correo'][$key] = 'El correo es obligatorio.';
                    } else if (!filter_var($_POST['correo'][$key], FILTER_VALIDATE_EMAIL)) {
                        $errores['correo'][$key] = 'Formato de correo no válido.';
                    }
                    if (empty($_POST['telefono'][$key]))
                        $errores['telefono'][$key] = 'El teléfono es obligatorio.';
                }
            }

            if (empty($errores)) {
                // --- CAMBIO IMPORTANTE: Se pasa $_FILES al modelo ---
                $modelo->guardarPaso4($id_proyecto, $_POST, $_FILES);
                header("Location: index.php?c=formularios&m=paso5");
                exit;
            } else {
                // Si hay errores, se repopula el formulario con los datos enviados
                $datos_enviados = $_POST;
            }
        } else {
            // Carga inicial (GET)
            $datosGuardados = $modelo->obtenerDatosCompletosPaso4($id_proyecto);
            $datos_enviados = $datosGuardados;
        }

        // === LÓGICA DE PRECARGA DE DATOS DINÁMICOS (sin cambios) ===
        $carreras_decano_precargadas = [];
        if (!empty($datos_enviados['facultad_decano'])) {
            $carreras = $institucionService->GetCarrera($username, (string) $datos_enviados['facultad_decano'], $token);
            $carreras_decano_precargadas = $carreras['dtResultado'] ?? [];
        }

        if (!empty($datos_enviados['facultad_director'])) {
            $datos_enviados['carreras_director_posibles'] = [];
            $datos_enviados['docentes_director_posibles'] = [];
            foreach ($datos_enviados['facultad_director'] as $key => $facultadId) {
                if ($facultadId) {
                    $carreras = $institucionService->GetCarrera($username, (string) $facultadId, $token);
                    $datos_enviados['carreras_director_posibles'][$key] = $carreras['dtResultado'] ?? [];
                    $carreraId = $datos_enviados['carrera_director'][$key] ?? null;
                    if ($carreraId) {
                        $docentes = $institucionService->GetDocentes($username, (string) $facultadId, (string) $carreraId, $token);
                        $datos_enviados['docentes_director_posibles'][$key] = $docentes['dtResultado'] ?? [];
                    }
                }
            }
        }

        if (!empty($datos_enviados['facultad_coop'])) {
            $datos_enviados['carreras_coop_posibles'] = [];
            $datos_enviados['docentes_coop_posibles'] = [];
            foreach ($datos_enviados['facultad_coop'] as $key => $facultadId) {
                if ($facultadId) {
                    $carreras = $institucionService->GetCarrera($username, (string) $facultadId, $token);
                    $datos_enviados['carreras_coop_posibles'][$key] = $carreras['dtResultado'] ?? [];
                    $carreraId = $datos_enviados['carrera_coop'][$key] ?? null;
                    if ($carreraId) {
                        $docentes = $institucionService->GetDocentes($username, (string) $facultadId, (string) $carreraId, $token);
                        $datos_enviados['docentes_coop_posibles'][$key] = $docentes['dtResultado'] ?? [];
                    }
                }
            }
        }

        $formData['datos_enviados'] = $datos_enviados;
        $formData['carreras_decano_precargadas'] = $carreras_decano_precargadas;
        $formData['errores'] = $errores;
        $formData['idProyecto'] = $id_proyecto;

        extract($formData);
        require_once ROOT_PATH . '/app/views/formularios/paso4.php';
    }

    // Paso 5   

    public function paso5()
    {
        require_once ROOT_PATH . '/app/helpers/Validator.php';
        require_once ROOT_PATH . '/app/models/Formulario5.php';
        $modelo = new Formulario5();
        $institucionService = new InstitucionService();

        $id_proyecto = $_SESSION['id_proyecto'] ?? null;
        if (!$id_proyecto) {
            header("Location: index.php?c=formularios&m=index");
            exit;
        }

        // Obtener datos del director y determinar si el formulario se autocompleta
        $datos_director_precargados = $this->_obtenerDatosDirectorCoincidente($id_proyecto);
        $autoFilled = !empty($datos_director_precargados);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // 1. Validar los datos enviados
            $errores = $this->validarPaso5($_POST, $autoFilled);

            if (empty($errores)) {
                // 2. SIN ERRORES: Guardar y redirigir
                $modelo->guardarPaso5($id_proyecto, $_POST);

                header("Location: ?c=Formularios&m=paso6");
                exit();
            } else {
                // 3. CON ERRORES: Preparar datos para recargar la vista
                $datos_enviados = $_POST;

                // Re-inyectar datos del director si fue autocompletado
                if ($autoFilled) {
                    $datos_enviados['nombre_director'] = $datos_director_precargados['nombre'] ?? '';
                    $datos_enviados['cedula_director'] = str_pad($datos_director_precargados['cedula'] ?? '', 10, '0', STR_PAD_LEFT);
                    $datos_enviados['correo_director'] = $datos_director_precargados['correo'] ?? '';
                    $datos_enviados['telefono_director'] = $datos_director_precargados['telefono'] ?? '';
                    $datos_enviados['facultad_director_paso5'] = $datos_director_precargados['facultad_id'] ?? '';
                    $datos_enviados['carrera_director_paso5'] = $datos_director_precargados['carrera_id'] ?? '';
                }

                // Precargar selects dependientes
                $datos_enviados = $this->precargarDatosPaso5($datos_enviados);
            }
        } else { // Método GET (Carga inicial de la página)
            $errores = [];
            $datos_enviados = $modelo->obtenerDatosCompletosPaso5($id_proyecto);

            if ($autoFilled) {
                // Sobreescribir con los datos frescos del director del paso 4
                $datos_enviados['nombre_director'] = $datos_director_precargados['nombre'] ?? '';
                $datos_enviados['cedula_director'] = str_pad($datos_director_precargados['cedula'] ?? '', 10, '0', STR_PAD_LEFT);
                $datos_enviados['correo_director'] = $datos_director_precargados['correo'] ?? '';
                $datos_enviados['telefono_director'] = $datos_director_precargados['telefono'] ?? '';
                $datos_enviados['facultad_director_paso5'] = $datos_director_precargados['facultad_id'] ?? '';
                $datos_enviados['carrera_director_paso5'] = $datos_director_precargados['carrera_id'] ?? '';
            }
            // Precargar selects dependientes
            $datos_enviados = $this->precargarDatosPaso5($datos_enviados);
        }

        // Preparar todas las variables para la vista
        $facultadData = $institucionService->GetFacultad($_SESSION['user']['username'], $institucionService->GetToken());
        $mostrar_modal_sin_director = !$autoFilled && $_SERVER['REQUEST_METHOD'] === 'GET';

        // Extraer variables para que estén disponibles en la vista
        extract(compact('datos_enviados', 'errores', 'id_proyecto', 'datos_director_precargados', 'facultadData', 'autoFilled', 'mostrar_modal_sin_director'));

        require_once ROOT_PATH . '/app/views/formularios/paso5.php';
    }
    // paso 6 /////
    public function paso6()
    {
        require_once ROOT_PATH . '/app/models/Formulario6.php';
        $modelo = new Formulario6();
        $id_proyecto = $_SESSION['id_proyecto'] ?? null;

        if (!$id_proyecto) {
            header("Location: index.php?c=formularios&m=index");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errores = $this->validarPaso6($_POST);

            if (empty($errores)) {
                // Con el modelo corregido, esta función ahora hace un "upsert" (inserta o actualiza)
                $modelo->guardarPaso6($id_proyecto, $_POST);

                // Opcional: Guardar un mensaje de éxito para mostrar en la alerta del footer
                $_SESSION['mensaje_exito'] = "¡Paso 6 guardado exitosamente!";

                header("Location: index.php?c=Formularios&m=paso7");
                exit;
            } else {
                // CON ERRORES: Prepara los datos para recargar la vista.
                // El usuario no pierde lo que escribió.
                $datos_enviados = $_POST;
                extract(compact('datos_enviados', 'errores', 'id_proyecto'));
                require_once ROOT_PATH . '/app/views/formularios/paso6.php';
            }
        } else { // Petición GET
            // Cargar datos existentes si el usuario vuelve a esta página o navega entre pasos.
            $datos_enviados = $modelo->obtenerPaso6PorProyecto($id_proyecto);
            $errores = [];
            extract(compact('datos_enviados', 'errores', 'id_proyecto'));
            require_once ROOT_PATH . '/app/views/formularios/paso6.php';
        }
    }
    // ==================================================================
    // === FIN DEL BLOQUE PARA EL PASO 6 ================================
    // ==================================================================

    //paso 7///

    public function paso7()
    {
        require_once ROOT_PATH . '/app/models/Formulario7.php';
        $modelo = new Formulario7();
        $id_proyecto = $_SESSION['id_proyecto'] ?? null;

        if (!$id_proyecto) {
            header("Location: index.php?c=formularios&m=index");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errores = $this->validarPaso7($_POST);

            if (empty($errores)) {
                $modelo->guardarPaso7($id_proyecto, $_POST);
                $_SESSION['mensaje_exito'] = "¡Paso 7 guardado exitosamente!";
                header("Location: ?c=Formularios&m=paso8");
                exit;
            } else {
                // Con errores, recargamos la vista con los datos y errores
                $datos_enviados = $_POST;
                extract(compact('datos_enviados', 'errores', 'id_proyecto'));
                require_once ROOT_PATH . '/app/views/formularios/paso7.php';
            }
        } else {
            // Carga inicial (GET)
            $datos_enviados = $modelo->obtenerDatosCompletosPaso7($id_proyecto);
            $errores = [];
            extract(compact('datos_enviados', 'errores', 'id_proyecto'));
            require_once ROOT_PATH . '/app/views/formularios/paso7.php';
        }
    }



    public function paso8()
    {
        require_once ROOT_PATH . '/app/models/Formulario8.php';
        $modelo = new Formulario8();
        $id_proyecto = $_SESSION['id_proyecto'] ?? null;

        if (!$id_proyecto) {
            header("Location: index.php?c=formularios&m=index");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errores = $this->validarPaso8($_POST);

            if (empty($errores)) {
                $modelo->guardarPaso8($id_proyecto, $_POST);

                // --- INICIO: Lógica para pasar datos al paso 9 ---
                $tipos_impacto = ['ambiental', 'social', 'economico', 'politico', 'cientifico'];
                $lista_maestra_indicadores = [];
                foreach ($tipos_impacto as $clave) {
                    $nombre_input = "impacto_{$clave}_indicador";
                    if (!empty($_POST[$nombre_input]) && is_array($_POST[$nombre_input])) {
                        $lista_maestra_indicadores = array_merge($lista_maestra_indicadores, $_POST[$nombre_input]);
                    }
                }

                $lista_maestra_indicadores = array_map('trim', $lista_maestra_indicadores);
                $lista_maestra_indicadores = array_filter($lista_maestra_indicadores);
                $lista_maestra_indicadores = array_unique($lista_maestra_indicadores);

                $_SESSION['datos_para_paso9'] = [
                    'objetivos_especificos' => $_POST['objetivos'] ?? [],
                    'lista_indicadores' => array_values($lista_maestra_indicadores)
                ];
                // --- FIN: Lógica para pasar datos al paso 9 ---

                $_SESSION['mensaje_exito'] = "¡Paso 8 guardado exitosamente!";
                header("Location: ?c=Formularios&m=paso9");
                exit;
            } else {
                $datos_enviados = $_POST;
                extract(compact('datos_enviados', 'errores', 'id_proyecto'));
                require_once ROOT_PATH . '/app/views/formularios/paso8.php';
            }
        } else {
            $datos_enviados = $modelo->obtenerDatosCompletosPaso8($id_proyecto);
            $errores = [];
            extract(compact('datos_enviados', 'errores', 'id_proyecto'));
            require_once ROOT_PATH . '/app/views/formularios/paso8.php';
        }
    }




    // PASO 9 //
    public function paso9()
    {
        require_once ROOT_PATH . '/app/models/Formulario9.php';
        $modelo = new Formulario9();
        $id_proyecto = $_SESSION['id_proyecto'] ?? null;

        if (!$id_proyecto) {
            header("Location: index.php?c=formularios&m=index");
            exit;
        }

        // Datos que vienen del Paso 8 (para construir la estructura)
        $datos_paso8 = $_SESSION['datos_para_paso9'] ?? [];
        $objetivos_especificos = [];
        foreach (($datos_paso8['objetivos_especificos'] ?? []) as $texto) {
            $objetivos_especificos[] = ['texto' => $texto];
        }
        $lista_indicadores = $datos_paso8['lista_indicadores'] ?? [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errores = $this->validarPaso9($_POST);

            if (empty($errores)) {
                $modelo->guardarPaso9($id_proyecto, $_POST);
                $_SESSION['mensaje_exito'] = '¡Paso 9 guardado correctamente!';
                header("Location: ?c=Formularios&m=paso10");
                exit();
            } else {
                // Con error, repoblar con datos de $_POST para que no se pierdan
                $datos_enviados = $_POST;
                extract(compact('datos_enviados', 'errores', 'id_proyecto', 'objetivos_especificos', 'lista_indicadores'));
                require ROOT_PATH . '/app/views/formularios/paso9.php';
            }
        } else {
            // Carga inicial (GET), cargar datos desde la BD
            $datos_enviados = $modelo->obtenerDatosCompletosPaso9($id_proyecto);
            $errores = [];
            extract(compact('datos_enviados', 'errores', 'id_proyecto', 'objetivos_especificos', 'lista_indicadores'));
            require ROOT_PATH . '/app/views/formularios/paso9.php';
        }
    }



    // paso 10////

    public function paso10()
    {
        $id_proyecto = $_SESSION['id_proyecto'] ?? null;
        if (!$id_proyecto) {
            header("Location: index.php");
            exit;
        }

        // Cargar modelos de prerrequisitos
        require_once ROOT_PATH . '/app/models/Formulario8.php';
        require_once ROOT_PATH . '/app/models/Formulario9.php';
        // --- INICIO DE LA MODIFICACIÓN ---
        // 1. Añadir el modelo del Formulario3 para obtener la duración
        require_once ROOT_PATH . '/app/models/Formulario3.php';
        // --- FIN DE LA MODIFICACIÓN ---

        $modelo8 = new Formulario8();
        $modelo9 = new Formulario9();
        // --- INICIO DE LA MODIFICACIÓN ---
        $modelo3 = new Formulario3();
        // --- FIN DE LA MODIFICACIÓN ---


        // Obtener datos de pasos anteriores para construir la vista
        $objetivos_especificos = $modelo8->obtenerObjetivosEspecificos($id_proyecto);
        $resultadosEsperados = $modelo9->obtenerResultadosEsperados($id_proyecto);

        // --- INICIO DE LA MODIFICACIÓN ---
        // 2. Obtener la duración del proyecto desde el paso 3
        $mesesDelProyecto = '';
        $datosPaso3 = $modelo3->obtenerDatosCompletosPaso3($id_proyecto);
        $idDuracionSeleccionada = $datosPaso3['duracion'] ?? null;

        if ($idDuracionSeleccionada) {
            // Cargar los catálogos para encontrar el texto de la duración (ej: "12 Meses")
            $catalogos = $this->cargarDatosBaseFormulario();
            $duracionCatalogo = $catalogos['duracion'] ?? [];

            foreach ($duracionCatalogo as $dur) {
                if ($dur['IdDuracion'] == $idDuracionSeleccionada) {
                    // Extraer solo el número de la cadena de texto
                    preg_match('/^\d+/', $dur['duracion'], $matches);
                    $mesesDelProyecto = $matches[0] ?? '';
                    break;
                }
            }
        }
        // --- FIN DE LA MODIFICACIÓN ---


        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once ROOT_PATH . '/app/models/Formulario10.php';
            $modelo10 = new Formulario10();

            $errores = $this->validarPaso10($_POST);

            if (empty($errores)) {
                $modelo10->guardarPaso10($id_proyecto, $_POST);
                $_SESSION['mensaje_exito'] = "¡Paso 10 guardado exitosamente!";
                header('Location: ?c=Formularios&m=paso11');
                exit;
            } else {
                // Con error, recargar con datos del POST
                $datos_enviados = $_POST;
                // 3. Pasar la duración a la vista incluso si hay un error
                $datos_enviados['duracion_proyecto_meses'] = $mesesDelProyecto;
                extract(compact('datos_enviados', 'errores', 'id_proyecto', 'objetivos_especificos', 'resultadosEsperados'));
                require ROOT_PATH . '/app/views/formularios/paso10.php';
            }
        } else {
            // Carga inicial (GET)
            require_once ROOT_PATH . '/app/models/Formulario10.php';
            $modelo10 = new Formulario10();
            $datos_enviados = $modelo10->obtenerDatosCompletosPaso10($id_proyecto);
            $errores = [];

            // 4. Pasar la duración a la vista en la carga inicial
            $datos_enviados['duracion_proyecto_meses'] = $mesesDelProyecto;

            extract(compact('datos_enviados', 'errores', 'id_proyecto', 'objetivos_especificos', 'resultadosEsperados'));
            require ROOT_PATH . '/app/views/formularios/paso10.php';
        }
    }

    // paso 11 ///
    public function paso11()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once ROOT_PATH . '/app/models/Formulario11.php';
            $modelo = new Formulario11();

            // Asumo que el ID del proyecto ya está guardado en la sesión desde pasos anteriores.
            $id_proyecto = $_SESSION['id_proyecto'];
            $mirada = $_POST['mirada_gestor_facultad'] ?? '';

            // Guardas la última parte del formulario.
            $modelo->guardarDeclaracion($id_proyecto, $mirada);

            header('Location: ?c=Formularios&m=index&id=' . $id_proyecto);
            exit; // Muy importante usar exit() después de una redirección.
        }

        // Mostrar la vista del paso 11 si no es POST
        require_once ROOT_PATH . '/app/views/formularios/paso11.php';
    }


    /////////////////FUNCIONES PARA EDITAR///////////////////////////////////////////////

    // =========== EDITAR PASO 1 ===========

    public function editarPaso1()
    {
        // 1. Obtiene el ID del proyecto
        $id_proyecto = $_GET['id'] ?? null;
        if (!$id_proyecto) {
            exit('ID de proyecto no proporcionado.');
        }
        $_SESSION['id_proyecto'] = $id_proyecto;

        // 2. Prepara el array que contendrá TODOS los datos para la vista
        $formData = [];

        // 3. Carga los catálogos base (ejes, áreas, y LOS JSON)
        //    Esta función DEBE devolver 'subareas_json' y 'especificas_json'
        $formData = $this->cargarDatosBaseFormulario();

        // 4. Carga los datos ya guardados del proyecto
        $modelo = new Formulario1();
        $formData['datos_enviados'] = $modelo->obtenerDatosCompletosPaso1($id_proyecto);

        // 5. Carga los permisos desde tu lógica centralizada
        $permisos = $this->_setupPermissions(); // Llama a tu función de permisos
        $formData['permite_editar'] = $permisos['permite_editar'];
        $formData['permite_comentar'] = $permisos['permite_comentar'];

        // 6. Añade cualquier otro dato necesario
        $formData['idProyecto'] = $id_proyecto;
        $formData['errores'] = []; // Inicializa los errores como un array vacío

        // 7. Usa extract() para pasar todas las variables a la vista
        extract($formData);

        // 8. Carga la vista
        require_once ROOT_PATH . '/app/views/formularios/form_editar/paso1.php';
    }

    public function actualizarPaso1()
    {
        $modelo = new Formulario1();

        $id_proyecto = $_GET['id'] ?? null;
        if (!$id_proyecto) {
            // Es buena práctica manejar el caso donde el ID no viene en la URL
            // podrías redirigir a una página de error o al listado de proyectos.
            exit('Error: ID de proyecto no proporcionado para la actualización.');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Llama al MISMO método de validación centralizado
            $errores = $this->_validarPaso1($_POST);

            if (empty($errores)) {
                // SIN ERRORES: Llama a la función de guardado (que debe manejar la actualización)
                $modelo->guardarPaso1($id_proyecto, $_POST);

                // Redirige al siguiente paso de EDICIÓN
                header("Location: index.php?c=formularios&m=editarPaso2&id=" . $id_proyecto);
                exit;
            } else {
                // CON ERRORES: Recarga la vista de EDICIÓN con los errores
                $formData = $this->cargarDatosBaseFormulario();
                $formData['errores'] = $errores;
                $formData['datos_enviados'] = $_POST; // Muestra los datos que el usuario intentó guardar
                $formData['idProyecto'] = $id_proyecto; // El idProyecto es necesario para la vista de edición
                $permisos = $this->_setupPermissions();
                // var_dump($permisos);
                // die();
                $formData['permite_editar'] = $permisos['permite_editar'];
                $formData['permite_comentar'] = $permisos['permite_comentar'];

                extract($formData);
                require_once ROOT_PATH . '/app/views/formularios/form_editar/paso1.php';
                return;
            }
        }
    }
    // =========== EDITAR PASO 2 ===========

    public function editarPaso2()
    {
        $idProyecto = $_GET['id'] ?? null;
        if (!$idProyecto) {
            exit('ID de proyecto no proporcionado');
        }

        // 1. Carga los modelos necesarios
        require_once ROOT_PATH . '/app/models/Formulario2.php';
        $modelo = new Formulario2();

        // 2. Carga los datos que ya están guardados en la BD para este formulario
        // La función obtenerDatosCompletosPaso2 formatea los datos para la vista.
        $datosGuardados = $modelo->obtenerDatosCompletosPaso2($idProyecto);

        // 3. Carga TODOS los catálogos (ODS, Ejes, y los JSON para los selects dinámicos)
        $formData = $this->cargarDatosBaseFormulario();

        // 4. Combina los datos de los catálogos con los datos guardados
        // La vista usará 'datos_enviados' para rellenar los campos, igual que en el modo de creación.
        $formData['datos_enviados'] = $datosGuardados;
        $formData['idProyecto'] = $idProyecto;

        $permisos = $this->_setupPermissions(); // Llama a tu función de permisos
        $formData['permite_editar'] = $permisos['permite_editar'];
        $formData['permite_comentar'] = $permisos['permite_comentar'];

        // 5. Extrae las variables y carga la vista de edición
        extract($formData);
        require ROOT_PATH . '/app/views/formularios/form_editar/paso2.php';
    }

    public function actualizarPaso2()
    {
        $idProyecto = $_GET['id'] ?? null;
        if (!$idProyecto) {
            exit('ID de proyecto no proporcionado');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once ROOT_PATH . '/app/models/Formulario2.php';
            $modelo = new Formulario2();

            // Recoge todos los datos enviados del formulario
            $datos = $_POST;

            // Verifica si ya existe una fila para ese proyecto
            $datosExistentes = $modelo->obtenerFormulario2PorProyecto($idProyecto);
            $id_formulario2 = $datosExistentes[0]['IdFormulario2'];

            if ($datosExistentes) {
                // Actualiza si ya existe
                $modelo->actualizarFormulario2($id_formulario2, $idProyecto, $datos);
            } else {
                // Inserta si no existe
                $modelo->insertarFormulario2($idProyecto, $datos);
            }

            // Redirecciona al siguiente paso
            header("Location: index.php?c=formularios&m=editarPaso3&id=" . $idProyecto);
            exit;
        }

        // Si entra por GET, redirecciona a editar
        header("Location: index.php?c=formularios&m=editarPaso2&id=" . $idProyecto);
        exit;
    }

    // =========== EDITAR PASO 3 ===========

    public function editarPaso3()
    {
        // Esta función se encarga de MOSTRAR el formulario de edición con TODOS los datos.
        $id_proyecto = $_GET['id'] ?? $_SESSION['id_proyecto'] ?? null;
        if (!$id_proyecto) {
            exit('ID de proyecto no proporcionado.');
        }
        $_SESSION['id_proyecto'] = $id_proyecto;

        // 1. Carga los modelos necesarios
        $modelo = new Formulario3();
        $institucionService = new InstitucionService();

        // 2. Carga los datos que ya están guardados en la BD para este formulario
        $datosGuardados = $modelo->obtenerDatosCompletosPaso3($id_proyecto);

        // 3. Carga los catálogos generales (contexto, duración, etc.)
        $formData = $this->cargarDatosBaseFormulario();

        // --- AÑADIDO CRÍTICO: Carga los datos de las facultades ---
        $token = $institucionService->GetToken();
        $formData['facultadData'] = $institucionService->GetFacultad($_SESSION['user']['username'], $token); // ¡RECUERDA CAMBIAR EL USUARIO!

        // 4. Pasa todo a la vista con nombres consistentes
        $formData['datos_enviados'] = $datosGuardados;
        $formData['idProyecto'] = $id_proyecto;

        $permisos = $this->_setupPermissions(); // Llama a tu función de permisos
        $formData['permite_editar'] = $permisos['permite_editar'];
        $formData['permite_comentar'] = $permisos['permite_comentar'];

        // 5. Carga la vista
        extract($formData);
        require_once ROOT_PATH . '/app/views/formularios/form_editar/paso3.php';
    }

   public function actualizarPaso3()
    {
        // Esta función VALIDA y GUARDA los datos del POST.
        require_once ROOT_PATH . '/app/helpers/Validator.php';
        require_once ROOT_PATH . '/app/models/Formulario3.php';
        $validator = new Validator();
        $modelo = new Formulario3();

        $id_proyecto = $_GET['id'] ?? null;
        if (!$id_proyecto) {
            exit('ID de proyecto no proporcionado.');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Define las reglas de validación
            $reglas = [
                'contexto' => ['required' => true],
                'duracion' => ['required' => true]
            ];
            $errores = [];

            if (empty($_POST['facultad'])) {
                $errores['perfil_egreso_general'] = 'Debe agregar al menos una fila de facultad/carrera.';
            }
            if (empty($_POST['cobertura'])) {
                $errores['cobertura_general'] = 'Debe seleccionar al menos una opción de cobertura.';
            }

            $validation_errors = $validator->validate($_POST, $reglas);
            $errores = array_merge($errores, $validation_errors);

            if (empty($errores)) {
                // SIN ERRORES: Llama a la función de guardado (que actualiza)
                $modelo->guardarPaso3($id_proyecto, $_POST);

                header("Location: index.php?c=formularios&m=editarPaso4&id=" . $id_proyecto);
                exit;
            } else {
                // CON ERRORES: Recarga la vista de EDICIÓN con los errores
                $formData = $this->cargarDatosBaseFormulario();
                $formData['errores'] = $errores;
                $formData['datos_enviados'] = $_POST;
                $formData['idProyecto'] = $id_proyecto;
                $permisos = $this->_setupPermissions(); // Llama a tu función de permisos
                $formData['permite_editar'] = $permisos['permite_editar'];
                $formData['permite_comentar'] = $permisos['permite_comentar'];
                extract($formData);
                require_once ROOT_PATH . '/app/views/formularios/form_editar/paso3.php';
                return;
            }
        }
    }
    // =========== EDITAR PASO 4 ===========

    public function editarPaso4()
    {
        $idProyecto = $_GET['id'] ?? null;
        if (!$idProyecto) {
            exit('ID de proyecto no proporcionado');
        }

        require_once ROOT_PATH . '/app/models/Formulario4.php';
        $modelo = new Formulario4();
        $institucionService = new InstitucionService();

        // Obtener datos guardados de la base de datos
        $datosGuardados = [];
        $datosGuardados = $modelo->obtenerDatosCompletosPaso4($idProyecto);

        // Cargar datos de catálogos
        $username = $_SESSION['user']['username'];
        $token = $institucionService->GetToken();
        $facultadData = $institucionService->GetFacultad($username, $token) ?? [];

        // Lógica para precargar combos dependientes al cargar la página
        if (!empty($datosGuardados['facultad_decano'])) {
            $carreras = $institucionService->GetCarrera($username, (string) $datosGuardados['facultad_decano'], $token);
            $datosGuardados['carreras_decano_precargadas'] = $carreras['dtResultado'] ?? [];

        }

        // Para la tabla de directores
        if (!empty($datosGuardados['facultad_director'])) {
            foreach ($datosGuardados['facultad_director'] as $key => $facultadId) {
                if ($facultadId) {
                    $carreras = $institucionService->GetCarrera($username, (string) $facultadId, $token);
                    $datosGuardados['carreras_director_posibles'][$key] = $carreras['dtResultado'] ?? [];
                    if (!empty($datosGuardados['carrera_director'][$key])) {
                        $docentes = $institucionService->GetDocentes($username, (string) $facultadId, (string) $datosGuardados['carrera_director'][$key], $token);
                        $datosGuardados['docentes_director_posibles'][$key] = $docentes['dtResultado'] ?? [];
                    }
                }
            }
        }

        // Para la tabla de cooperantes
        if (!empty($datosGuardados['facultad_coop'])) {
            foreach ($datosGuardados['facultad_coop'] as $key => $facultadId) {
                if ($facultadId) {
                    $carreras = $institucionService->GetCarrera($username, (string) $facultadId, $token);
                    $datosGuardados['carreras_coop_posibles'][$key] = $carreras['dtResultado'] ?? [];
                    if (!empty($datosGuardados['carrera_coop'][$key])) {
                        $docentes = $institucionService->GetDocentes($username, (string) $facultadId, (string) $datosGuardados['carrera_coop'][$key], $token);
                        $datosGuardados['docentes_coop_posibles'][$key] = $docentes['dtResultado'] ?? [];
                    }
                }
            }
        }

        // Preparamos los datos para la vista
        $errores = []; // Se inicializa vacío para la carga inicial
        $datos_enviados = $datosGuardados;

        $permisos = $this->_setupPermissions(); // Llama a tu función de permisos
        $formData['permite_editar'] = $permisos['permite_editar'];
        $formData['permite_comentar'] = $permisos['permite_comentar'];

        extract($formData);

        require ROOT_PATH . '/app/views/formularios/form_editar/paso4.php';
    }

    public function actualizarPaso4()
    {
        // Esta función VALIDA y GUARDA los datos del formulario de edición.
        $idProyecto = $_GET['id'] ?? null;
        if (!$idProyecto) {
            exit('ID de proyecto no proporcionado');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once ROOT_PATH . '/app/helpers/Validator.php';
            require_once ROOT_PATH . '/app/models/Formulario4.php';
            $validator = new Validator();
            $modelo = new Formulario4();
            $institucionService = new InstitucionService();

            $username = $_SESSION['user']['username'];
            $token = $institucionService->GetToken();

            // Definición de reglas de validación (sin cambios)
            $reglas = [
                'facultad_decano' => ['required' => true, 'message' => 'La facultad del decano es obligatoria.'],
                'carrera_decano' => ['required' => true, 'message' => 'La carrera del decano es obligatoria.'],
                'decano_decano' => ['required' => true, 'message' => 'El nombre del decano es obligatorio.'],
                'decano_correo' => ['required' => true, 'email' => true, 'message' => 'El correo del decano es obligatorio y debe ser válido.'],
                'decano_telefono' => ['required' => true, 'message' => 'El teléfono del decano es obligatorio.'],
                'externa_nombre' => ['required' => true, 'message' => 'El nombre de la institución es obligatorio.'],
                'externa_repres_nombre' => ['required' => true, 'message' => 'El representante legal es obligatorio.'],
                'externa_dir' => ['required' => true, 'message' => 'La dirección es obligatoria.'],
                'externa_tel' => ['required' => true, 'message' => 'El teléfono es obligatorio.'],
                'externa_correo' => ['required' => true, 'email' => true, 'message' => 'El correo es obligatorio y debe ser válido.'],
                'aliado_nombre' => ['required' => true, 'message' => 'El nombre del aliado es obligatorio.'],
                'aliado_repres_nombre' => ['required' => true, 'message' => 'El representante del aliado es obligatorio.'],
                'aliado_direccion' => ['required' => true, 'message' => 'La dirección del aliado es obligatoria.'],
                'aliado_tel' => ['required' => true, 'message' => 'El teléfono del aliado es obligatorio.'],
                'aliado_correo' => ['required' => true, 'email' => true, 'message' => 'El correo del aliado es obligatorio y debe ser válido.'],
            ];

            $errores = $validator->validate($_POST, $reglas);

            // --- INICIO: VALIDACIÓN DEL LOGO AÑADIDA ---
            if (isset($_FILES['logo_proyecto']) && $_FILES['logo_proyecto']['error'] !== UPLOAD_ERR_OK) {
                if ($_FILES['logo_proyecto']['error'] !== UPLOAD_ERR_NO_FILE) {
                    $errores['logo_proyecto'] = 'Error al subir el archivo.';
                }
            }
            // --- FIN: VALIDACIÓN DEL LOGO AÑADIDA ---

            // Validación de tablas dinámicas (sin cambios)
            if (empty($_POST['facultad_director'])) {
                $errores['director_general'] = 'Debe agregar al menos un Director de Proyecto.';
            } else {
                foreach ($_POST['facultad_director'] as $key => $facultad) {
                    if (empty($facultad))
                        $errores['facultad_director'][$key] = 'La facultad es obligatoria.';
                    if (empty($_POST['carrera_director'][$key]))
                        $errores['carrera_director'][$key] = 'La carrera es obligatoria.';
                    if (empty($_POST['nombre_director'][$key]))
                        $errores['nombre_director'][$key] = 'El director es obligatorio.';
                    if (empty($_POST['director_correo'][$key])) {
                        $errores['director_correo'][$key] = 'El correo es obligatorio.';
                    } else if (!filter_var($_POST['director_correo'][$key], FILTER_VALIDATE_EMAIL)) {
                        $errores['director_correo'][$key] = 'Formato de correo no válido.';
                    }
                    if (empty($_POST['director_telefono'][$key]))
                        $errores['director_telefono'][$key] = 'El teléfono es obligatorio.';
                }
            }
            if (empty($_POST['facultad_coop'])) {
                $errores['cooperantes_general'] = 'Debe agregar al menos una Unidad Cooperante.';
            } else {
                foreach ($_POST['facultad_coop'] as $key => $facultad) {
                    if (empty($facultad))
                        $errores['facultad_coop'][$key] = 'La facultad es obligatoria.';
                    if (empty($_POST['carrera_coop'][$key]))
                        $errores['carrera_coop'][$key] = 'La carrera es obligatoria.';
                    if (empty($_POST['docente_coop'][$key]))
                        $errores['docente_coop'][$key] = 'El docente es obligatorio.';
                    if (empty($_POST['correo'][$key])) {
                        $errores['correo'][$key] = 'El correo es obligatorio.';
                    } else if (!filter_var($_POST['correo'][$key], FILTER_VALIDATE_EMAIL)) {
                        $errores['correo'][$key] = 'Formato de correo no válido.';
                    }
                    if (empty($_POST['telefono'][$key]))
                        $errores['telefono'][$key] = 'El teléfono es obligatorio.';
                }
            }

            if (empty($errores)) {
                // SIN ERRORES: Llama a la función de guardado, pasando $_FILES
                // --- CAMBIO IMPORTANTE: Se pasa $_FILES al modelo ---
                $modelo->guardarPaso4($idProyecto, $_POST, $_FILES);
                header("Location: ?c=Formularios&m=editarPaso5&id=" . $idProyecto);
                exit;
            } else {
                // CON ERRORES: Recarga la vista de EDICIÓN con los datos y errores
                $formData = [];
                $formData['facultadData'] = $institucionService->GetFacultad($username, $token);
                $formData['errores'] = $errores;
                $formData['datos_enviados'] = $_POST;
                $formData['idProyecto'] = $idProyecto;

                // --- INICIO: LÓGICA PARA NO PERDER LA IMAGEN EXISTENTE ---
                // Si la validación falla, los datos de $_POST no incluyen la ruta de la imagen
                // que ya estaba guardada. La recuperamos para mostrarla de nuevo.
                $datosGuardados = $modelo->obtenerDatosCompletosPaso4($idProyecto);
                $formData['datos_enviados']['RutaImagen'] = $datosGuardados['RutaImagen'] ?? null;
                // --- FIN: LÓGICA PARA NO PERDER LA IMAGEN EXISTENTE ---

                // Lógica para precargar combos dependientes (sin cambios)
                if (!empty($_POST['facultad_decano'])) {
                    $carreras = $institucionService->GetCarrera($username, $_POST['facultad_decano'], $token);
                    $formData['carreras_decano_precargadas'] = $carreras['dtResultado'] ?? [];
                }
                if (!empty($_POST['facultad_director'])) {
                    foreach ($_POST['facultad_director'] as $key => $facultadId) {
                        if ($facultadId) {
                            $carreras = $institucionService->GetCarrera($username, (string) $facultadId, $token);
                            $formData['datos_enviados']['carreras_director_posibles'][$key] = $carreras['dtResultado'] ?? [];
                            if (!empty($_POST['carrera_director'][$key])) {
                                $docentes = $institucionService->GetDocentes($username, (string) $facultadId, (string) $_POST['carrera_director'][$key], $token);
                                $formData['datos_enviados']['docentes_director_posibles'][$key] = $docentes['dtResultado'] ?? [];
                            }
                        }
                    }
                }
                if (!empty($_POST['facultad_coop'])) {
                    foreach ($_POST['facultad_coop'] as $key => $facultadId) {
                        if ($facultadId) {
                            $carreras = $institucionService->GetCarrera($username, (string) $facultadId, $token);
                            $formData['datos_enviados']['carreras_coop_posibles'][$key] = $carreras['dtResultado'] ?? [];
                            if (!empty($_POST['carrera_coop'][$key])) {
                                $docentes = $institucionService->GetDocentes($username, (string) $facultadId, (string) $_POST['carrera_coop'][$key], $token);
                                $formData['datos_enviados']['docentes_coop_posibles'][$key] = $docentes['dtResultado'] ?? [];
                            }
                        }
                    }
                }

                extract($formData);
                require_once ROOT_PATH . '/app/views/formularios/form_editar/paso4.php';
                return;
            }
        }
    }
    // =========== EDITAR PASO 5 ===========

    public function editarPaso5()
    {
        $idProyecto = $_GET['id'] ?? null;
        if (!$idProyecto) {
            exit('ID de proyecto no proporcionado');
        }

        $modelo = new Formulario5();
        $institucionService = new InstitucionService();

        // 1. Obtener datos guardados de la base de datos
        $datos_enviados = $modelo->obtenerDatosCompletosPaso5($idProyecto);


        // *** INICIO DE LA CORRECCIÓN ***
        // Asegurarse de que los arrays para las tablas dinámicas siempre existan.
        // Esto previene errores en la vista si no hay datos guardados para una tabla.
        $datos_enviados['facultad_tutor'] = $datos_enviados['facultad_tutor'] ?? [];
        $datos_enviados['facultad_estud'] = $datos_enviados['facultad_estud'] ?? [];
        $datos_enviados['programa_articulacion_nombre'] = $datos_enviados['programa_articulacion_nombre'] ?? [];
        // *** FIN DE LA CORRECCIÓN ***

        // 2. Verificar si el director coincide y enriquecer los datos
        $datos_director_precargados = $this->_obtenerDatosDirectorCoincidente($idProyecto);
        $autoFilled = !empty($datos_director_precargados);

        if ($autoFilled) {
            $datos_enviados['nombre_director'] = $datos_director_precargados['nombre'] ?? '';
            $datos_enviados['cedula_director'] = str_pad($datos_director_precargados['cedula'] ?? '', 10, '0', STR_PAD_LEFT);
            $datos_enviados['correo_director'] = $datos_director_precargados['correo'] ?? '';
            $datos_enviados['telefono_director'] = $datos_director_precargados['telefono'] ?? '';
            $datos_enviados['facultad_director_paso5'] = $datos_director_precargados['facultad_id'] ?? '';
            $datos_enviados['carrera_director_paso5'] = $datos_director_precargados['carrera_id'] ?? '';
        }

        // 3. Precargar los selects dependientes
        $datos_enviados = $this->precargarDatosPaso5($datos_enviados);

        $permisos = $this->_setupPermissions(); // Llama a tu función de permisos
        $formData['permite_editar'] = $permisos['permite_editar'];
        $formData['permite_comentar'] = $permisos['permite_comentar'];



        // 4. Preparar el resto de las variables para la vista
        $facultadData = $institucionService->GetFacultad($_SESSION['user']['username'], $institucionService->GetToken());
        $errores = [];

        // 5. Cargar la vista, pasando todas las variables de forma consistente
        extract(compact('datos_enviados', 'errores', 'idProyecto', 'datos_director_precargados', 'facultadData', 'autoFilled'));
        extract($formData);
        require_once ROOT_PATH . '/app/views/formularios/form_editar/paso5.php';
    }

    public function actualizarPaso5()
    {
        $idProyecto = $_GET['id'] ?? null;
        if (!$idProyecto) {
            exit('ID de proyecto no proporcionado');
        }

        // Cargar el validador ANTES de usarlo
        require_once ROOT_PATH . '/app/helpers/Validator.php';

        $modelo = new Formulario5();
        $institucionService = new InstitucionService();

        $datos_director_precargados = $this->_obtenerDatosDirectorCoincidente($idProyecto);
        $autoFilled = !empty($datos_director_precargados);

        // 1. Validar los datos
        $errores = $this->validarPaso5($_POST, $autoFilled);

        if (empty($errores)) {
            // 2. SIN ERRORES: Guardar y redirigir
            $modelo->guardarPaso5($idProyecto, $_POST);
            header("Location: ?c=Formularios&m=editarPaso6&id=" . $idProyecto);
            exit();
        } else {
            // 3. CON ERRORES: Preparar los datos para recargar la vista
            $datos_enviados = $_POST;

            // Re-inyectar datos del director si fue autocompletado
            if ($autoFilled) {
                $datos_enviados['nombre_director'] = $datos_director_precargados['nombre'] ?? '';
                $datos_enviados['cedula_director'] = str_pad($datos_director_precargados['cedula'] ?? '', 10, '0', STR_PAD_LEFT);
                $datos_enviados['correo_director'] = $datos_director_precargados['correo'] ?? '';
                $datos_enviados['telefono_director'] = $datos_director_precargados['telefono'] ?? '';
                $datos_enviados['facultad_director_paso5'] = $datos_director_precargados['facultad_id'] ?? '';
                $datos_enviados['carrera_director_paso5'] = $datos_director_precargados['carrera_id'] ?? '';
            }

            // Precargar los selects dependientes con los datos que el usuario envió
            $datos_enviados = $this->precargarDatosPaso5($datos_enviados);

            // Preparar las variables para la vista de forma explícita para evitar errores
            $facultadData = $institucionService->GetFacultad($_SESSION['user']['username'], $institucionService->GetToken());

            // Extraer todas las variables necesarias para que la vista las reciba
            extract(compact('datos_enviados', 'errores', 'idProyecto', 'datos_director_precargados', 'facultadData', 'autoFilled'));

            require_once ROOT_PATH . '/app/views/formularios/form_editar/paso5.php';
        }
    }

    // --- EDITAR FORMULARIO 6 ---

    public function editarPaso6()
    {
        // Cambiamos el nombre a $idProyecto para consistencia
        $idProyecto = $_GET['id'] ?? null;
        if (!$idProyecto) {
            exit('Error: No se proporcionó un ID de proyecto.');
        }

        require_once ROOT_PATH . '/app/models/Formulario6.php';
        $modelo = new Formulario6();

        $detalle = $modelo->obtenerPaso6PorProyecto($idProyecto);
        if (!$detalle) {
            $detalle = []; // Inicializa vacío si no hay datos previos
        } else {
            $detalle = $detalle[0];
        }

        $permisos = $this->_setupPermissions(); // Llama a tu función de permisos
        $formData['permite_editar'] = $permisos['permite_editar'];
        $formData['permite_comentar'] = $permisos['permite_comentar'];


        // CORRECCIÓN: Pasamos tanto 'detalle' como 'idProyecto' a la vista
        extract(compact('detalle', 'idProyecto'));
        require_once ROOT_PATH . '/app/views/formularios/form_editar/paso6.php';
    }

    public function actualizarPaso6()
    {
        // Cambiamos el nombre a $idProyecto para consistencia
        $idProyecto = $_GET['id'] ?? null;
        if (!$idProyecto) {
            exit('Error: No se proporcionó un ID de proyecto.');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errores = $this->validarPaso6($_POST);

            if (empty($errores)) {
                require_once ROOT_PATH . '/app/models/Formulario6.php';
                $modelo = new Formulario6();
                $modelo->guardarPaso6($idProyecto, $_POST);

                $_SESSION['mensaje_exito'] = "¡Paso 6 actualizado exitosamente!";
                header("Location: ?c=Formularios&m=editarPaso7&id=" . $idProyecto);
                exit();
            } else {
                $datos_enviados = $_POST;

                // CORRECCIÓN: Cuando hay errores, también debemos pasar 'idProyecto'
                // y los datos guardados de 'detalle' para repoblar correctamente.
                require_once ROOT_PATH . '/app/models/Formulario6.php';
                $modelo = new Formulario6();
                $detalle = $modelo->obtenerPaso6PorProyecto($idProyecto) ?? [];

                extract(compact('datos_enviados', 'errores', 'detalle', 'idProyecto'));
                require_once ROOT_PATH . '/app/views/formularios/form_editar/paso6.php';
            }
        } else {
            header("Location: ?c=Formularios&m=editarPaso6&id=" . $idProyecto);
            exit();
        }
    }

    // --- EDITAR FORMULARIO 7 ---

    public function editarPaso7()
    {
        require_once ROOT_PATH . '/app/models/Formulario7.php';
        $modelo = new Formulario7();

        // CORRECCIÓN: Usar idProyecto para consistencia
        $idProyecto = $_GET['id'] ?? null;

        if (!$idProyecto) {
            exit('ID de proyecto no proporcionado');
        }

        $datos_guardados = $modelo->obtenerDatosCompletosPaso7($idProyecto);
        $errores = [];

        $permisos = $this->_setupPermissions(); // Llama a tu función de permisos
        $formData['permite_editar'] = $permisos['permite_editar'];
        $formData['permite_comentar'] = $permisos['permite_comentar'];


        // Se pasa 'idProyecto' a la vista
        extract(compact('datos_guardados', 'errores', 'idProyecto'));
        extract($formData);
        require ROOT_PATH . '/app/views/formularios/form_editar/paso7.php';
    }

    public function actualizarPaso7()
    {
        require_once ROOT_PATH . '/app/models/Formulario7.php';
        $modelo = new Formulario7();

        // CORRECCIÓN: Usar idProyecto para consistencia
        $idProyecto = $_GET['id'] ?? null;

        if (!$idProyecto) {
            exit('ID de proyecto no proporcionado');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errores = $this->validarPaso7($_POST);

            if (empty($errores)) {
                $modelo->guardarPaso7($idProyecto, $_POST);
                $_SESSION['mensaje_exito'] = "¡Paso 7 actualizado exitosamente!";
                header('Location: ?c=Formularios&m=editarPaso8&id=' . $idProyecto);
                exit();
            } else {
                $datos_guardados = $_POST;

                // Se pasa 'idProyecto' a la vista también en caso de error
                extract(compact('datos_guardados', 'errores', 'idProyecto'));
                require ROOT_PATH . '/app/views/formularios/form_editar/paso7.php';
            }
        } else {
            header('Location: ?c=Formularios&m=editarPaso7&id=' . $idProyecto);
            exit();
        }
    }

    // --- EDITAR FORMULARIO 8 ---

    // --- EDITAR FORMULARIO 8 ---

    public function editarPaso8()
    {
        require_once ROOT_PATH . '/app/models/Formulario8.php';
        $modelo = new Formulario8();

        // CORRECCIÓN: Se cambió el nombre de la variable a $idProyecto para consistencia
        $idProyecto = $_GET['id'] ?? null;

        if (!$idProyecto)
            exit('ID de proyecto no proporcionado');

        $datos_guardados = $modelo->obtenerDatosCompletosPaso8($idProyecto);
        $errores = [];

        $permisos = $this->_setupPermissions(); // Llama a tu función de permisos
        $formData['permite_editar'] = $permisos['permite_editar'];
        $formData['permite_comentar'] = $permisos['permite_comentar'];


        // Ahora pasamos la variable correcta '$idProyecto' a la vista
        extract(compact('datos_guardados', 'errores', 'idProyecto'));
        extract($formData);
        require ROOT_PATH . '/app/views/formularios/form_editar/paso8.php';
    }

    public function actualizarPaso8()
    {
        require_once ROOT_PATH . '/app/models/Formulario8.php';
        $modelo = new Formulario8();

        // CORRECCIÓN: Se cambió el nombre de la variable a $idProyecto para consistencia
        $idProyecto = $_GET['id'] ?? null;

        if (!$idProyecto)
            exit('ID de proyecto no proporcionado');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $errores = $this->validarPaso8($_POST);

            if (empty($errores)) {
                $modelo->guardarPaso8($idProyecto, $_POST);

                $_SESSION['mensaje_exito'] = "¡Paso 8 actualizado exitosamente!";
                header('Location: ?c=Formularios&m=editarPaso9&id=' . $idProyecto);
                exit();
            } else {
                // Cuando hay errores, también pasamos la variable correcta '$idProyecto'
                $datos_guardados = $_POST;
                extract(compact('datos_guardados', 'errores', 'idProyecto'));
                require ROOT_PATH . '/app/views/formularios/form_editar/paso8.php';
            }
        } else {
            header('Location: ?c=Formularios&m=editarPaso8&id=' . $idProyecto);
            exit();
        }
    }

    // --- EDITAR FORMULARIO 9 ---

    // --- EDITAR FORMULARIO 9 ---

    public function editarPaso9()
    {
        // Cargar modelos
        require_once ROOT_PATH . '/app/models/Formulario8.php';
        require_once ROOT_PATH . '/app/models/Formulario9.php';
        $modelo8 = new Formulario8();
        $modelo9 = new Formulario9();

        // CORRECCIÓN: Usar idProyecto para consistencia con la vista
        $idProyecto = $_GET['id'] ?? null;
        if (!$idProyecto)
            exit('ID de proyecto no proporcionado');

        // Obtener datos
        $datos_guardados = $modelo9->obtenerDatosCompletosPaso9($idProyecto);
        $objetivos_db = $modelo8->obtenerObjetivosEspecificos($idProyecto);
        $objetivos_especificos = [];
        foreach ($objetivos_db as $obj) {
            $objetivos_especificos[] = ['texto' => $obj['texto']];
        }
        $impactos_db = $modelo8->obtenerImpactos($idProyecto);
        $lista_indicadores = array_values(array_unique(array_column($impactos_db, 'Indicador')));
        $errores = [];

        $permisos = $this->_setupPermissions(); // Llama a tu función de permisos
        $formData['permite_editar'] = $permisos['permite_editar'];
        $formData['permite_comentar'] = $permisos['permite_comentar'];


        // Pasar todas las variables necesarias a la vista
        extract(compact('datos_guardados', 'errores', 'idProyecto', 'objetivos_especificos', 'lista_indicadores'));
        extract($formData);
        require ROOT_PATH . '/app/views/formularios/form_editar/paso9.php';
    }

    public function actualizarPaso9()
    {
        // CORRECCIÓN: Usar idProyecto para consistencia con la vista
        $idProyecto = $_GET['id'] ?? null;
        if (!$idProyecto)
            exit('ID de proyecto no proporcionado');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            require_once ROOT_PATH . '/app/models/Formulario8.php';
            require_once ROOT_PATH . '/app/models/Formulario9.php';
            $modelo8 = new Formulario8();
            $modelo9 = new Formulario9();

            $errores = $this->validarPaso9($_POST);

            if (empty($errores)) {
                $modelo9->guardarPaso9($idProyecto, $_POST);
                $_SESSION['mensaje_exito'] = "¡Paso 9 actualizado exitosamente!";
                header('Location: ?c=Formularios&m=editarPaso10&id=' . $idProyecto);
                exit();
            } else {
                // Con errores, recargar la vista con todos los datos necesarios
                $objetivos_db = $modelo8->obtenerObjetivosEspecificos($idProyecto);
                $objetivos_especificos = [];
                foreach ($objetivos_db as $obj) {
                    $objetivos_especificos[] = ['texto' => $obj['texto']];
                }
                $impactos_db = $modelo8->obtenerImpactos($idProyecto);
                $lista_indicadores = array_values(array_unique(array_column($impactos_db, 'Indicador')));

                $datos_enviados = $_POST; // Renombrar para mayor claridad en la vista
                extract(compact('datos_enviados', 'errores', 'idProyecto', 'objetivos_especificos', 'lista_indicadores'));
                extract($formData);
                require ROOT_PATH . '/app/views/formularios/form_editar/paso9.php';
            }
        }
    }

    // --- EDITAR FORMULARIO 10 ---

    public function editarPaso10()
    {
        $id_proyecto = $_GET['id'] ?? null;
        if (!$id_proyecto) {
            exit('ID de proyecto no proporcionado');
        }

        // Cargar todos los modelos necesarios
        require_once ROOT_PATH . '/app/models/Formulario3.php'; // <--- AÑADIDO
        require_once ROOT_PATH . '/app/models/Formulario8.php';
        require_once ROOT_PATH . '/app/models/Formulario9.php';
        require_once ROOT_PATH . '/app/models/Formulario10.php';

        $modelo3 = new Formulario3(); // <--- AÑADIDO
        $modelo8 = new Formulario8();
        $modelo9 = new Formulario9();
        $modelo10 = new Formulario10();

        // 1. Obtener datos de prerrequisitos (Pasos 8 y 9)
        $objetivos_especificos = $modelo8->obtenerObjetivosEspecificos($id_proyecto);
        $resultadosEsperados = $modelo9->obtenerResultadosEsperados($id_proyecto);

        // 2. Obtener la duración del proyecto desde el paso 3
        $mesesDelProyecto = '';
        $datosPaso3 = $modelo3->obtenerDatosCompletosPaso3($id_proyecto);
        $idDuracionSeleccionada = $datosPaso3['duracion'] ?? null;

        if ($idDuracionSeleccionada) {
            // Cargar los catálogos para encontrar el texto de la duración (ej: "12 Meses")
            $catalogos = $this->cargarDatosBaseFormulario();
            $duracionCatalogo = $catalogos['duracion'] ?? [];

            foreach ($duracionCatalogo as $dur) {
                if ($dur['IdDuracion'] == $idDuracionSeleccionada) {
                    // Extraer solo el número de la cadena de texto
                    preg_match('/^\d+/', $dur['duracion'], $matches);
                    $mesesDelProyecto = $matches[0] ?? '';
                    break;
                }
            }
        }

        $permisos = $this->_setupPermissions(); // Llama a tu función de permisos
        $formData['permite_editar'] = $permisos['permite_editar'];
        $formData['permite_comentar'] = $permisos['permite_comentar'];


        // 3. Obtener los datos ya guardados del Paso 10
        $datos_guardados = $modelo10->obtenerDatosCompletosPaso10($id_proyecto);

        // 4. Añadir la duración a los datos que se enviarán a la vista
        $datos_guardados['duracion_proyecto_meses'] = $mesesDelProyecto;

        $errores = [];

        // 5. Cargar la vista con todos los datos
        extract(compact('datos_guardados', 'errores', 'id_proyecto', 'objetivos_especificos', 'resultadosEsperados'));
        extract($formData);
        require ROOT_PATH . '/app/views/formularios/form_editar/paso10.php';
    }

    public function actualizarPaso10()
    {
        $id_proyecto = $_GET['id'] ?? null;
        if (!$id_proyecto) {
            exit('ID de proyecto no proporcionado');
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Cargar modelos
            require_once ROOT_PATH . '/app/models/Formulario3.php'; // <--- AÑADIDO
            require_once ROOT_PATH . '/app/models/Formulario8.php';
            require_once ROOT_PATH . '/app/models/Formulario9.php';
            require_once ROOT_PATH . '/app/models/Formulario10.php';

            $modelo3 = new Formulario3(); // <--- AÑADIDO
            $modelo8 = new Formulario8();
            $modelo9 = new Formulario9();
            $modelo10 = new Formulario10();

            // 1. Validar los datos del formulario
            $errores = $this->validarPaso10($_POST);

            if (empty($errores)) {
                // SIN ERRORES: Guardar y redirigir
                $modelo10->guardarPaso10($id_proyecto, $_POST);
                $_SESSION['mensaje_exito'] = "¡Paso 10 actualizado exitosamente!";
                header('Location: ?c=Formularios&m=editarPaso11&id=' . $id_proyecto);
                exit;
            } else {
                // CON ERRORES: Recargar la vista sin perder datos

                // 1. Recargar los prerrequisitos para reconstruir la vista
                $objetivos_especificos = $modelo8->obtenerObjetivosEspecificos($id_proyecto);
                $resultadosEsperados = $modelo9->obtenerResultadosEsperados($id_proyecto);

                // 2. Obtener la duración del proyecto para mantenerla en el campo readonly
                $mesesDelProyecto = '';
                $datosPaso3 = $modelo3->obtenerDatosCompletosPaso3($id_proyecto);
                $idDuracionSeleccionada = $datosPaso3['duracion'] ?? null;

                if ($idDuracionSeleccionada) {
                    $catalogos = $this->cargarDatosBaseFormulario();
                    $duracionCatalogo = $catalogos['duracion'] ?? [];
                    foreach ($duracionCatalogo as $dur) {
                        if ($dur['IdDuracion'] == $idDuracionSeleccionada) {
                            preg_match('/^\d+/', $dur['duracion'], $matches);
                            $mesesDelProyecto = $matches[0] ?? '';
                            break;
                        }
                    }
                }

                // 3. Los datos a mostrar son los que el usuario envió ($_POST)
                $datos_guardados = $_POST;
                $datos_guardados['duracion_proyecto_meses'] = $mesesDelProyecto;

                // 4. Cargar la vista con los datos del POST y los errores
                extract(compact('datos_guardados', 'errores', 'id_proyecto', 'objetivos_especificos', 'resultadosEsperados'));
                require ROOT_PATH . '/app/views/formularios/form_editar/paso10.php';
            }
        } else {
            header('Location: ?c=Formularios&m=editarPaso10&id=' . $id_proyecto);
            exit();
        }
    }
    // =========== EDITAR PASO 11 ===========

    public function editarPaso11()
    {
        $id_proyecto = $_GET['id'] ?? null;
        if (!$id_proyecto) {
            exit('ID de proyecto no proporcionado');
        }

        require_once ROOT_PATH . '/app/models/Formulario11.php';
        $modelo = new Formulario11();
        $declaracion = $modelo->obtenerDeclaracion($id_proyecto); // array asociativo o false

        $permisos = $this->_setupPermissions(); // Llama a tu función de permisos
        $formData['permite_editar'] = $permisos['permite_editar'];
        $formData['permite_comentar'] = $permisos['permite_comentar'];
        extract($formData);


        // Renderiza la vista de edición (form_editar/paso11.php)
        require_once ROOT_PATH . '/app/views/formularios/form_editar/paso11.php';
    }

    // ========== ACTUALIZAR PASO 11 ==========
    public function actualizarPaso11()
    {
        $id_proyecto = $_GET['id'] ?? null;
        if (!$id_proyecto) {
            exit('ID de proyecto no proporcionado');
        }

        require_once ROOT_PATH . '/app/models/Formulario11.php';
        $modelo = new Formulario11();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $mirada = $_POST['mirada_gestor_facultad'] ?? '';

            // PATRÓN SEGURO: Si existe, actualiza; si no existe, inserta
            $declaracion = $modelo->obtenerDeclaracion($id_proyecto);

            if ($declaracion) {
                // Existe: ACTUALIZA
                $modelo->actualizarDeclaracion($id_proyecto, $mirada);
            } else {
                // No existe: CREA/INSERTA
                $modelo->guardarDeclaracion($id_proyecto, $mirada);
            }

            // Mensaje de éxito
            $_SESSION['mensaje_exito'] = "¡Declaración final actualizada exitosamente!";

            // Redireccionar a index o donde corresponda
            header('Location: ?c=Formularios&m=index');
            exit;
        }

        // Si no es POST, redirige a la edición
        header('Location: ?c=Formularios&m=editarPaso11&id=' . $id_proyecto);
        exit;
    }

    // En FormulariosController.php

    // En: app/controllers/FormulariosController.php

    public function cambiarEstado()
    {
        $idProyecto = $_GET['id'] ?? 0;
        $nuevoEstado = $_GET['estado'] ?? '';

        if ($idProyecto > 0 && !empty($nuevoEstado)) {
            require_once ROOT_PATH . '/app/models/ProyectosModel.php';
            $modelo = new ProyectosModel();

            // Se actualiza el estado. El modelo se encargará de la trazabilidad.
            $modelo->actualizarEstado((int) $idProyecto, $nuevoEstado);

            // --- HEMOS ELIMINADO EL BLOQUE "if ($exito)" QUE VOLVÍA A GUARDAR LA TRAZABILIDAD ---
        }

        // Redirigimos de vuelta a la lista
        header("Location: index.php?c=formularios&m=index");
        exit;
    }
}
