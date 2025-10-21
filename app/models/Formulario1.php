<?php

class Formulario1 extends Model
{

    private $pdo;

    public function __construct()
    {
        $config = require ROOT_PATH . '/config/database.php';
        // try {
        //     $this->pdo = new PDO($config['dsn'], $config['usuario'], $config['clave']);
        //     $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        // } catch (PDOException $e) {
        //     die("Error de conexión en Formulario1: " . $e->getMessage());
        // }
    }

    // Paso 1: Crear proyecto base con estado válido
    public function crearProyecto($nombre_proyecto)
    {
        $data = [
            'Titulo' => $nombre_proyecto,
            'UsuarioTrx' => $_SESSION['user']['username'],
        ];
        $id = $this->executeStoredProcedure('SetPropuesta', 'INSERT_PROPUESTA', $data, 'Transaccion', true);
        return $id['resultado']['Table'][0]['IdPropuesta']; // Devuelve el id_doc_gen insertado

        // $sql = "INSERT INTO proyecto.Proyectos (FechaCreacion, NombreProyecto, Estado)
        //         VALUES (GETDATE(), ?, 'BORRADOR')";
        // $stmt = $this->pdo->prepare($sql);
        // $stmt->execute([$nombre_proyecto]);
        // return $this->pdo->lastInsertId();
    }

    // Paso 2: Insertar en Formulario1 con el ID generado (sin RutaImagen)
    public function insertarFormulario1($id_proyecto, $titulo, $eje, $area, $subarea, $especifica)
    {
        $data = [
            'Formulario1' => [
                'IdPropuesta' => $id_proyecto,
                'Titulo' => $titulo,
                'EjeEstrategico' => $eje,
                'AreaConocimiento' => $area,
                'SubareaConocimiento' => $subarea,
                'SubareaEspecifica' => $especifica,
                'UsuarioTrx' => $_SESSION['user']['username']
            ]
        ];
        $id = $this->executeStoredProcedure('SetForms', 'UPSERT_FORM1', $data, 'Transaccion', true);
        //var_dump($id);
        //die();
        return $id['resultado']['Table'][0]['IdForms']; // Devuelve el id_doc_gen insertado


    }

    public function insertarProgramaArticulado($id_formulario1, $id_base, $nombre, $autores, $anio, $enlace, $descripcion)
    {

        $data = [
            'Formulario1' => [
                'IdFormulario1' => $id_formulario1,
                'IdProgramaBase' => $id_base,
                'NombreProyecto' => $nombre,
                'Autores' => $autores,
                'Anio' => $anio,
                'Enlace' => $enlace,
                'ResultadosTransferencia' => $descripcion,
                'UsuarioTrx' => $_SESSION['user']['username']
            ]
        ];
        $this->executeStoredProcedure('SetForms', 'UPSERT_FORM1_PA', $data, 'Transaccion', true);

        // $sql = "INSERT INTO proyecto.Formulario1_ProgramasArticulados
        //         (IdFormulario1, IdProgramaBase, NombreProyecto, Autores, Anio, Enlace, ResultadosTransferencia)
        //         VALUES (?, ?, ?, ?, ?, ?, ?)";
        // $stmt = $this->pdo->prepare($sql);
        // $stmt->execute([$id_formulario1, $id_base, $nombre, $autores, $anio, $enlace, $descripcion]);
    }

    // ===================================================================
    // FUNCIÓN AÑADIDA PARA EVITAR DUPLICADOS
    // ===================================================================
    public function obtenerProyectoPorId($id_proyecto)
    {
        $data = ['IdPropuesta' => $id_proyecto];
        $result = $this->executeStoredProcedure('GetForms', 'GET_FORM1_PROYECTOS', $data, 'Transaccion', true);
        return  $result['resultado']['Table'][0];
    }
    // ===================================================================

    // Obtener datos generales (única fila por proyecto)
    public function obtenerFormulario1PorProyecto($id_proyecto)
    {
        $data = ['IdPropuesta' => $id_proyecto];
        $id = $this->executeStoredProcedure('GetForms', 'GET_FORM1_DATOS_GENERALES', $data, 'Transaccion', true);

        return  $id['resultado']['Table'];
    }

    // Actualizar datos generales (solo si existe, sin RutaImagen)
    public function actualizarFormulario1($id_formulario1, $id_proyecto, $titulo, $eje, $area, $subarea, $especifica)
    {
        $data = [
            'Formulario1' => [
                'IdFormulario1'=> $id_formulario1,
                'IdPropuesta' => $id_proyecto,
                'Titulo' => $titulo,
                'EjeEstrategico' => $eje,
                'AreaConocimiento' => $area,
                'SubareaConocimiento' => $subarea,
                'SubareaEspecifica' => $especifica,
                'UsuarioTrx' => $_SESSION['user']['username']
            ]
        ];
        $this->executeStoredProcedure('SetForms', 'UPSERT_FORM1', $data, 'Transaccion', true);
    }

    // -- Para programas articulados --
    public function obtenerIdFormulario1PorProyecto($id_proyecto)
    {
        $data = ['IdPropuesta' => $id_proyecto];
        $id = $this->executeStoredProcedure('GetForms', 'GET_FORM1_ID_DATOS_GENERALES', $data, 'Transaccion', true);
        //var_dump($id[]);
        //die();
        return  $id['result']['Table'][0]['IdFormulario1'];

        // $sql = "SELECT IdFormulario1 FROM proyecto.Formulario1_DatosGenerales WHERE IdProyecto = ?";
        // $stmt = $this->pdo->prepare($sql);
        // $stmt->execute([$id_proyecto]);
        // return $stmt->fetchColumn();
    }

    public function obtenerProgramasArticuladosPorProyecto($id_proyecto)
    {
        $data = ['IdPropuesta' => $id_proyecto];
        $result = $this->executeStoredProcedure('GetForms', 'GET_FORM1_PROGRAMAS_ARTICULADOS', $data, 'Transaccion', true);
        //var_dump($result);
        //die();
        return  $result['resultado']['Table'];
        // $id_formulario1 = $this->obtenerIdFormulario1PorProyecto($id_proyecto);
        // if (!$id_formulario1)
        //     return [];

        // $sql = "SELECT * FROM proyecto.Formulario1_ProgramasArticulados WHERE IdFormulario1 = ?";
        // $stmt = $this->pdo->prepare($sql);
        // $stmt->execute([$id_formulario1]);
        // return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function actualizarProgramaArticuladoPorProyecto($id_formulario1, $id_base, $nombre, $autores, $anio, $enlace, $descripcion)
    {
        $data = [
            'Formulario1' => [
                'IdFormulario1' => $id_formulario1,
                'IdProgramaBase' => $id_base,
                'NombreProyecto' => $nombre,
                'Autores' => $autores,
                'Anio' => $anio,
                'Enlace' => $enlace,
                'ResultadosTransferencia' => $descripcion,
                'UsuarioTrx' => $_SESSION['user']['username']
            ]
        ];
        $this->executeStoredProcedure('SetForms', 'UPSERT_FORM1_PA', $data, 'Transaccion', true);
    }

    // Eliminar todos los programas antes de insertar nuevos
    public function eliminarProgramasArticuladosPorProyecto($id_proyecto)
    {
        $id_formulario1 = $this->obtenerIdFormulario1PorProyecto($id_proyecto);
        if (!$id_formulario1)
            return;

        $sql = "DELETE FROM proyecto.Formulario1_ProgramasArticulados WHERE IdFormulario1 = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id_formulario1]);
    }

    public function obtenerDatosCompletosPaso1($id_proyecto)
    {
        // 1. Obtener los datos del formulario principal
        $datos_formulario = $this->obtenerFormulario1PorProyecto($id_proyecto);


        if (!$datos_formulario) {
            return []; // No hay datos guardados, devuelve un array vacío
        } else {
            $datos_formulario = $datos_formulario[0];
        }

        // 2. Formatear los datos para que coincidan con los nombres de los campos del formulario (sin RutaImagen)
        $datos_combinados = [
            'Titulo' => $datos_formulario['Titulo'],
            'eje_estrategico' => $datos_formulario['EjeEstrategico'],
            'area' => $datos_formulario['AreaConocimiento'],
            'subarea' => $datos_formulario['SubareaConocimiento'],
            'subarea_especifica' => $datos_formulario['SubareaEspecifica']
        ];

        // 3. Obtener los programas articulados asociados
        $programas = $this->obtenerProgramasArticuladosPorProyecto($id_proyecto);

        if ($programas) {
            $datos_combinados['tipo_programa'] = []; // Array para guardar los IDs de los checkboxes marcados
            foreach ($programas as $prog) {
                $id = $prog['IdProgramaBase']; // Asumiendo que esta es la columna correcta
                $datos_combinados['tipo_programa'][] = $id;

                // Añadir los datos de los campos dinámicos
                $datos_combinados['nombre_programa_' . $id] = $prog['NombreProyecto'];
                $datos_combinados['autores_programa_' . $id] = $prog['Autores'];
                $datos_combinados['anio_programa_' . $id] = $prog['Anio'];
                $datos_combinados['enlace_programa_' . $id] = $prog['Enlace'];
                $datos_combinados['descripcion_programa_' . $id] = $prog['ResultadosTransferencia'];
            }
        }

        return $datos_combinados;
    }


    public function guardarPaso1($id_proyecto, $post_data) // Se elimina $file_data
    {
        // Descomponemos los datos necesarios
        $titulo = $post_data['Titulo'];
        $eje = $post_data['eje_estrategico'];
        $area = $post_data['area'];
        $subarea = $post_data['subarea'];
        $especifica = $post_data['subarea_especifica'];

        // Verificamos si ya existen datos para este formulario
        $datosExistentes = $this->obtenerFormulario1PorProyecto($id_proyecto);
        //var_dump($datosExistentes);
        //die();

        // --- SE ELIMINA TODA LA LÓGICA DE MANEJO DE IMAGEN ---

        if ($datosExistentes) {
            // --- SI YA EXISTEN, ACTUALIZAMOS ---
            $id_formulario1 = $datosExistentes[0]['IdFormulario1'];
            // Se llama a la función sin el parámetro de la imagen
            $this->actualizarFormulario1( $id_formulario1, $id_proyecto, $titulo, $eje, $area, $subarea, $especifica);

            // Borramos los programas antiguos para evitar duplicados
            $this->eliminarProgramasArticuladosPorProyecto($id_proyecto);
        } else {
            // --- SI NO EXISTEN, INSERTAMOS ---
            // Se llama a la función sin el parámetro de la imagen
            $id_formulario1 = $this->insertarFormulario1($id_proyecto, $titulo, $eje, $area, $subarea, $especifica);
            //var_dump($id_formulario1);
            //die();
        }

        // En ambos casos, reinsertamos los programas seleccionados
        if (!empty($post_data['tipo_programa'])) {
            foreach ($post_data['tipo_programa'] as $idProgamaA) {
                $nombre_proyecto = htmlspecialchars($post_data['nombre_programa_' . $idProgamaA]);
                $autores = htmlspecialchars($post_data['autores_programa_' . $idProgamaA]);
                $anio = (int) $post_data['anio_programa_' . $idProgamaA];
                $enlace = filter_var($post_data['enlace_programa_' . $idProgamaA], FILTER_SANITIZE_URL);
                $resultados = htmlspecialchars($post_data['descripcion_programa_' . $idProgamaA]);
                $this->insertarProgramaArticulado($id_formulario1, $idProgamaA, $nombre_proyecto, $autores, $anio, $enlace, $resultados);
            }
        }

        return $id_formulario1; // Devuelve el ID del formulario
    }
}//fin