<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Proyecto de Vinculación con la Sociedad</title>
    <style>
        @page {
            margin: 120px 50px 80px 50px;
        }

        header {
            position: fixed;
            top: -110px;
            left: 0px;
            right: 0px;
            height: 75px;
            text-align: center;
        }

        footer {
            position: fixed;
            bottom: -75px;
            left: -90px;
            right: -90px;
            height: 60px;
            text-align: center;
            font-size: 8pt;
            color: #555;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 9pt;
            line-height: 1.3;
            color: #333;
        }

        h1 {
            font-size: 13pt;
            text-align: center;
            margin-bottom: 20px;
            font-weight: bold;
            text-transform: uppercase;
        }

        h2 {
            font-size: 10pt;
            background-color: #DDEBF7;
            padding: 8px;
            margin-top: 20px;
            margin-bottom: 10px;
            border: 1px solid #999;
            font-weight: bold;
            text-transform: uppercase;
        }

        p {
            margin-top: 0;

        }

        #header-content {
            display: flex;
            align-items: center;
            justify-content: flex-start;
            width: 100%;
            position: relative;
            /* Necesario para que el posicionamiento absoluto del logo funcione bien */
        }

        #logo-container {
            /* Este es el marco donde vivirá tu logo */
            width: 170px;
            height: 70px;
            position: absolute;
            top: 0px;
            right: 25px;
        }

        #logo-container img {
            /* La imagen se ajustará dentro del contenedor sin distorsionarse */
            max-width: 100%;
            max-height: 100%;
            width: auto;
            height: auto;
        }

        #header-image {
            /* El ancho de la cabecera principal */
            width: 100%;
            height: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        td,
        th {
            border: 1px solid #999;
            padding: 5px 8px;
            text-align: left;
            vertical-align: top;
            font-size: 8.5pt;
            word-wrap: break-word;
            font-weight: normal;
        }


        th {
            background-color: #DDEBF7;
            font-weight: bold;
        }

        .text-normal {
            font-weight: normal !important;
        }

        .page-break {
            page-break-after: always;
        }

        .no-border-table,
        .no-border-table td {
            border: none;
        }

        .text-content {
            white-space: pre-wrap;
            text-align: justify;
        }

        /* --- CORRECCIONES PARA TABLAS FIJAS --- */
        .section-table {
            table-layout: fixed;
            /* Obliga a la tabla a respetar los anchos de columna */
            width: 100%;
        }

        .section-table>tbody>tr>td:first-child {
            width: 35%;
            /* Ancho fijo para la primera columna (etiquetas) */
            font-weight: bold;
            vertical-align: top;
        }

        .section-table>tbody>tr>td:last-child {
            width: 65%;
            /* El resto del espacio para la segunda columna (contenido) */
        }

        .inner-list p {
            margin-bottom: 10px;
        }

        .inner-list strong {
            font-weight: bold;
        }


        /* --- FIN DE CORRECCIONES --- */
    </style>
</head>

<body>
    <header>
        <div id="header-content">
            <img id="header-image" style="margin-top: 20px;" src="<?= ROOT_PATH . '/public/assets/img/Cabezera.png' ?>">

            <div id="logo-container">
                <?php
                $rutaLogo = $proyectoCompleto->institucion_externa->RutaImagen ?? null;
                if ($rutaLogo) {
                    $rutaAbsolutaLogo = ROOT_PATH . $rutaLogo;
                ?>
                    <img style="margin-top: 20px;" src="<?= htmlspecialchars($rutaAbsolutaLogo) ?>" alt="Logo del Proyecto">
                <?php } ?>
            </div>
        </div>
    </header>
    <footer>
        <img src="<?= ROOT_PATH . '/public/assets/img/Pie.png' ?>" style="width: 100%; height: auto;">
    </footer>
    <main>
        <?php
        $datos = $proyectoCompleto->datos_generales ?? new stdClass();
        $detalle = $proyectoCompleto->detalle_proyecto ?? new stdClass();
        $poblacion = $proyectoCompleto->poblacion_objetivo ?? new stdClass();
        $obj_met = $proyectoCompleto->objetivos_metodologia ?? new stdClass();
        $res = $proyectoCompleto->resultados_productos ?? new stdClass();
        $declaracion = $proyectoCompleto->declaracion ?? new stdClass();
        $programas_articulados_bd = $proyectoCompleto->programas_articulados ?? [];
        ?>
        <h1>PROYECTO DE VINCULACIÓN CON LA SOCIEDAD</h1>

        <h2>1. DATOS GENERALES DEL PROYECTO</h2>

        <table class="section-table">
            <tr>
                <td>1.1 Título del proyecto:</td>
                <td><?= htmlspecialchars($datos->Titulo ?? 'N/A') ?></td>
            </tr>
            <tr>
                <td>1.2 Eje estratégico de actuación:</td>
                <td><?= htmlspecialchars($datos->EjeEstrategicoTexto ?? 'N/A') ?></td>
            </tr>
            <tr>
                <td>1.3 Tipo de programa o proyecto con el que se articula:</td>
                <td>
                    <?php if (!empty($programas_articulados_bd)): ?>
                        <div class="inner-list">
                            <?php foreach ($programas_articulados_bd as $prog): ?>
                                <p>
                                    <strong>Tipo de programa o proyecto:</strong> <?= htmlspecialchars($prog->ProgramaBaseTexto ?? 'N/A') ?><br>
                                    <strong>Nombre del proyecto:</strong> <?= htmlspecialchars($prog->NombreProyecto ?? 'N/A') ?><br>
                                    <strong>Autores:</strong> <?= htmlspecialchars($prog->Autores ?? 'N/A') ?><br>
                                    <strong>Año:</strong> <?= htmlspecialchars($prog->Anio ?? 'N/A') ?><br>
                                    <strong>Enlace:</strong> <?= htmlspecialchars($prog->Enlace ?? 'N/A') ?><br>
                                    <strong>Resultados a transferir:</strong> <?= htmlspecialchars($prog->ResultadosTransferencia ?? 'N/A') ?>
                                </p>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        N/A
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <td>1.4 Área del conocimiento:</td>
                <td><?= htmlspecialchars($datos->AreaConocimientoTexto ?? 'N/A') ?> </td>
            </tr>
            <tr>
                <td>1.4.1 Subárea del conocimiento:</td>
                <td><?= htmlspecialchars($datos->SubareaConocimientoTexto ?? 'N/A') ?> </td>
            </tr>
            <tr>
                <td>1.4.2 Subárea específica:</td>
                <td><?= htmlspecialchars($datos->SubareaEspecificaTexto ?? 'N/A') ?></td>
            </tr>
            <tr>
                <td>1.6 Eje estratégico del Plan:</td>
                <td><?= htmlspecialchars($datos->EjePlanTexto ?? 'N/A') ?></td>
            </tr>
            <tr>
                <td>1.7 Objetivo del Plan Nacional de Desarrollo:</td>
                <td><?= htmlspecialchars($datos->ObjetivoNacionalTexto ?? 'N/A') ?></td>
            </tr>
            <tr>
                <td>1.8 Alineación del proyecto a los dominios científicos:</td>
                <td><?= htmlspecialchars($datos->DominioCientificoTexto ?? 'N/A') ?></td>
            </tr>
            <tr>
                <td>1.9 Líneas de investigación institucionales:</td>
                <td><?= htmlspecialchars($datos->LineaInstitucionalTexto ?? 'N/A') ?></td>
            </tr>
        </table>

        <table class="section-table">
            <tr>
                <td colspan="2" style="font-weight:bold; background-color:#F2F2F2;">
                    1.10 Perfil de egreso de la carrera
                </td>
            </tr>
        </table>

        <?php if (!empty($proyectoCompleto->perfil_egreso)): ?>
            <?php
            $total_perfiles = count($proyectoCompleto->perfil_egreso);
            $perfil_contador = 0;
            ?>
            <?php foreach ($proyectoCompleto->perfil_egreso as $perfil): ?>
                <?php $perfil_contador++; ?>

                <table class="table table-bordered" style="width: 100%; border-collapse: collapse; margin-top: 15px;">
                    <tbody>
                        <tr>
                            <td colspan="2" style="font-weight: bold; background-color: #F2F2F2; padding: 6px 8px;">
                                Perfil de egreso <?= $perfil_contador ?>
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 35%;"><strong>Carrera:</strong></td>
                            <td style="width: 65%;"><?= htmlspecialchars($perfil->CarreraTexto ?? 'N/A') ?></td>
                        </tr>
                        <tr>
                            <td><strong>Facultad:</strong></td>
                            <td><?= htmlspecialchars($perfil->FacultadTexto ?? 'N/A') ?></td>
                        </tr>
                        <tr>
                            <td><strong>Programa:</strong></td>
                            <td><?= htmlspecialchars($perfil->Programa ?? 'N/A') ?></td>
                        </tr>
                        <tr>
                            <td style="vertical-align: middle;"><strong>Aporte Perfil:</strong></td>
                            <td>
                                <p style="margin: 0; text-align: justify;"><?= nl2br(htmlspecialchars($perfil->AportePerfil ?? 'N/A')) ?></p>
                            </td>
                        </tr>
                    </tbody>
                </table>



            <?php endforeach; ?>
        <?php else: ?>
            <div style="padding: 5px 8px;">
                <p style="margin-top: 0;">N/A</p>
            </div>
        <?php endif; ?>

        <table class="section-table" style="margin-top: 15px;">
            <tr>
                <td>1.11 Cobertura de ejecución del proyecto:</td>
                <td><?= htmlspecialchars($datos->CoberturaTexto ?? 'N/A') ?></td>
            </tr>
            <tr>
                <td>1.12 Contexto de ejecución del proyecto:</td>
                <td><?= htmlspecialchars($datos->ContextoTexto ?? 'N/A') ?></td>
            </tr>
            <tr>
                <td>1.13 Duración del proyecto en meses:</td>
                <td><?= htmlspecialchars($datos->DuracionTexto ?? 'N/A') ?></td>
            </tr>
        </table>

        <h2 style="page-break-before: always;">2. DATOS DE LAS UNIDADES ACADÉMICAS E INSTITUCIONALES</h2>
        <?php if (!empty($proyectoCompleto->unidades_academicas)): ?>
            <table class="section-table">
                <tr>
                    <td colspan="2" style="font-weight:bold; background-color:#F2F2F2;">2.1 Unidad(es) académica(s) y decano de facultad:</td>
                </tr>
                <?php foreach ($proyectoCompleto->unidades_academicas as $ua): ?>
                    <tr>
                        <td style="width: 35%;"><strong>Facultad:</strong></td>
                        <td style="width: 65%;"><?= htmlspecialchars($ua->FacultadTexto ?? 'N/A') ?></td>
                    </tr>
                    <tr>
                        <td><strong>Carrera:</strong></td>
                        <td><?= htmlspecialchars($ua->CarreraTexto ?? 'N/A') ?></td>
                    </tr>
                    <tr>
                        <td>Decano:</td>
                        <td><?= htmlspecialchars($ua->Decano ?? 'N/A') ?></td>
                    </tr>
                    <tr>
                        <td><strong>Teléfono:</strong></td>
                        <td><?= htmlspecialchars($ua->Telefono ?? 'N/A') ?></td>
                    </tr>
                    <tr>
                        <td><strong>Correo:</strong></td>
                        <td><?= htmlspecialchars($ua->Correo ?? 'N/A') ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>

        <?php if (!empty($proyectoCompleto->directorProyecto)): ?>
            <?php
            // 1. Se inicializa un contador antes de empezar el bucle.
            $director_contador = 0;
            ?>
            <?php foreach ($proyectoCompleto->directorProyecto as $dp): ?>
                <?php
                // 2. Se incrementa el contador en cada vuelta.
                $director_contador++;
                ?>

                <table class="table table-bordered" style="margin-bottom: 1.5rem;">
                    <thead>
                        <tr>
                            <th colspan="2" style="font-weight:bold; background-color:#F2F2F2;">
                                Director de Proyecto <?= $director_contador ?>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td style="width: 35%;"><strong>Facultad:</strong></td>
                            <td style="width: 65%;"><?= htmlspecialchars($dp->FacultadTexto ?? 'N/A') ?></td>
                        </tr>
                        <tr>
                            <td><strong>Carrera:</strong></td>
                            <td><?= htmlspecialchars($dp->CarreraTexto ?? 'N/A') ?></td>
                        </tr>
                        <tr>
                            <td><strong>Director:</strong></td>
                            <td><?= htmlspecialchars($dp->NombreDirectorTexto ?? 'N/A') ?></td>
                        </tr>
                        <tr>
                            <td><strong>Teléfono:</strong></td>
                            <td><?= htmlspecialchars($dp->Telefono ?? 'N/A') ?></td>
                        </tr>
                        <tr>
                            <td><strong>Correo:</strong></td>
                            <td><?= htmlspecialchars($dp->Correo ?? 'N/A') ?></td>
                        </tr>
                    </tbody>
                </table>
            <?php endforeach; ?>
        <?php endif; ?>

        <?php if (isset($proyectoCompleto->institucion_externa) && !empty((array)$proyectoCompleto->institucion_externa)): $ie = $proyectoCompleto->institucion_externa; ?>
            <table class="section-table">
                <tr>
                    <td colspan="2" style="font-weight:bold; background-color:#F2F2F2;">2.2 Institución Externa:</td>
                </tr>
                <tr>
                    <td>Nombre de la institución:</td>
                    <td><?= htmlspecialchars($ie->NombreInstitucion ?? 'N/A') ?></td>
                </tr>
                <tr>
                    <td>Nombre del representante legal:</td>
                    <td><?= htmlspecialchars($ie->RepresentanteLegal ?? 'N/A') ?></td>
                </tr>
                <tr>
                    <td><strong>Dirección:</strong></td>
                    <td><?= htmlspecialchars($ie->Direccion ?? 'N/A') ?></td>
                </tr>
                <tr>
                    <td><strong>Teléfono:</strong></td>
                    <td><?= htmlspecialchars($ie->Telefono ?? 'N/A') ?></td>
                </tr>
                <tr>
                    <td>Correo electrónico:</td>
                    <td><?= htmlspecialchars($ie->Correo ?? 'N/A') ?></td>
                </tr>
                <tr>
                    <td>Página Web:</td>
                    <td><?= htmlspecialchars($ie->PaginaWeb ?? 'N/A') ?></td>
                </tr>
            </table>
        <?php endif; ?>

        <?php if (!empty($proyectoCompleto->unidades_cooperantes)): ?>
            <table class="section-table">
                <tr>
                    <td colspan="2" style="font-weight:bold; background-color:#F2F2F2;">2.3 Otras Unidades Académicas Cooperantes</td>
                </tr>
                <?php foreach ($proyectoCompleto->unidades_cooperantes as $uc): ?>
                    <tr>
                        <td><strong>Facultad:</strong></td>
                        <td><?= htmlspecialchars($uc->FacultadCoopTexto ?? 'N/A') ?></td>
                    </tr>
                    <tr>
                        <td><strong>Carrera:</strong></td>
                        <td><?= htmlspecialchars($uc->CarreraCoopTexto ?? 'N/A') ?></td>
                    </tr>
                    <tr>
                        <td>Docente Responsable:</td>
                        <td><?= htmlspecialchars($uc->DocenteCoopTexto ?? 'N/A') ?></td>
                    </tr>
                    <tr>
                        <td><strong>Teléfono:</strong></td>
                        <td><?= htmlspecialchars($uc->Telefono ?? 'N/A') ?></td>
                    </tr>
                    <tr>
                        <td><strong>Correo:</strong></td>
                        <td><?= htmlspecialchars($uc->Correo ?? 'N/A') ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>

        <?php if (!empty($proyectoCompleto->aliadoEstrategico)): ?>
            <table class="section-table">
                <tr>
                    <td colspan="2" style="font-weight:bold; background-color:#F2F2F2;">2.4 Aliado estratégico:</td>
                </tr>
                <?php foreach ($proyectoCompleto->aliadoEstrategico as $aes): ?>
                    <tr>
                        <td>Nombre de la institución:</td>
                        <td><?= htmlspecialchars($aes->NombreInstitucion ?? 'N/A') ?></td>
                    </tr>
                    <tr>
                        <td>Nombre del representante legal:</td>
                        <td><?= htmlspecialchars($aes->RepresentanteLegal ?? 'N/A') ?></td>
                    </tr>
                    <tr>
                        <td>Dirección:</td>
                        <td><?= htmlspecialchars($aes->Direccion ?? 'N/A') ?></td>
                    </tr>
                    <tr>
                        <td><strong>Teléfono:</strong></td>
                        <td><?= htmlspecialchars($aes->Telefono ?? 'N/A') ?></td>
                    </tr>
                    <tr>
                        <td><strong>Correo:</strong></td>
                        <td><?= htmlspecialchars($aes->Correo ?? 'N/A') ?></td>
                    </tr>
                    <tr>
                        <td>Página Web:</td>
                        <td><?= htmlspecialchars($aes->PaginaWeb ?? 'N/A') ?></td>
                    </tr>
                    <tr>
                        <td>Describa la contribución por parte del aliado estratégico:</td>
                        <td><?= htmlspecialchars($aes->Contribucion ?? 'N/A') ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>

        <h2 style="page-break-before: always;">3. EQUIPO DEL PROYECTO</h2>

        <table class="section-table">
            <tr>
                <td colspan="2" style="font-weight:bold; background-color:#F2F2F2;">3.1 Grupo de Docentes (Directores y Tutores):</td>
            </tr>
        </table>

        <?php if (!empty($proyectoCompleto->directores)): ?>
            <?php foreach ($proyectoCompleto->directores as $d): ?>
                <table class="table table-bordered" style="width: 100%; border-collapse: collapse; margin-top: 10px;">
                    <thead>
                        <tr>
                            <th class="nivel-3" colspan="4">3.1.1 Director de proyecto</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="label-cell" style="width: 20%;"><strong>Nombres:</strong></td>
                            <td colspan="3"><?= htmlspecialchars($d->NombreDirector ?? 'N/A') ?></td>
                        </tr>
                        <tr>
                            <td class="label-cell"><strong>Cédula o pasaporte:</strong></td>
                            <td style="width: 30%;"><?= htmlspecialchars($d->CedulaDirector ?? 'N/A') ?></td>
                            <td class="label-cell" style="width: 20%;"><strong>Acreditado:</strong></td>
                            <td style="width: 30%;"><?= htmlspecialchars($d->Acreditado ?? 'N/A') ?></td>
                        </tr>
                        <tr>
                            <td class="label-cell"><strong>Nivel de instrucción:</strong></td>
                            <td><?= htmlspecialchars($d->Categoria ?? 'N/A') ?></td>
                            <td class="label-cell"><strong>Dedicación:</strong></td>
                            <td><?= htmlspecialchars($d->Dedicacion ?? 'N/A') ?></td>
                        </tr>
                        <tr>
                            <td class="label-cell"><strong>Correo electrónico:</strong></td>
                            <td><?= htmlspecialchars($d->Correo ?? 'N/A') ?></td>
                            <td class="label-cell"><strong>Teléfono:</strong></td>
                            <td><?= htmlspecialchars($d->Telefono ?? 'N/A') ?></td>
                        </tr>
                        <tr>
                            <td class="label-cell"><strong>Facultad:</strong></td>
                            <td><?= htmlspecialchars($d->FacultadTexto ?? 'N/A') ?></td>
                            <td class="label-cell"><strong>Carrera:</strong></td>
                            <td><?= htmlspecialchars($d->CarreraTexto ?? 'N/A') ?></td>
                        </tr>
                    </tbody>
                </table>
            <?php endforeach; ?>
        <?php endif; ?>

        <?php if (!empty($proyectoCompleto->tutores)): ?>
            <?php $docente_contador = 0; ?>
            <?php foreach ($proyectoCompleto->tutores as $t): ?>
                <?php
                $docente_contador++;
                if ($docente_contador >= 4 && ($docente_contador - 4) % 4 === 0) {
                    echo '<div style="page-break-before: always;"></div>';
                }
                ?>
                <table class="table table-bordered" style="width: 100%; border-collapse: collapse; margin-top: 10px;">
                    <thead>
                        <tr>
                            <th class="nivel-3" colspan="4">3.1.2 Docente Tutor <?= $docente_contador ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="label-cell" style="width: 20%;"><strong>Nombres:</strong></td>
                            <td colspan="3"><?= htmlspecialchars($t->Nombre ?? 'N/A') ?></td>
                        </tr>
                        <tr>
                            <td class="label-cell"><strong>Cédula o pasaporte:</strong></td>
                            <td style="width: 30%;"><?= htmlspecialchars($t->Cedula ?? 'N/A') ?></td>
                            <td class="label-cell" style="width: 20%;"><strong>Acreditado:</strong></td>
                            <td style="width: 30%;"><?= htmlspecialchars($t->Acreditado ?? 'N/A') ?></td>
                        </tr>
                        <tr>
                            <td class="label-cell"><strong>Nivel de instrucción:</strong></td>
                            <td><?= htmlspecialchars($t->Categoria ?? 'N/A') ?></td>
                            <td class="label-cell"><strong>Dedicación:</strong></td>
                            <td><?= htmlspecialchars($t->Dedicacion ?? 'N/A') ?></td>
                        </tr>
                        <tr>
                            <td class="label-cell"><strong>Correo electrónico:</strong></td>
                            <td><?= htmlspecialchars($t->Correo ?? 'N/A') ?></td>
                            <td class="label-cell"><strong>Teléfono:</strong></td>
                            <td><?= htmlspecialchars($t->Telefono ?? 'N/A') ?></td>
                        </tr>
                        <tr>
                            <td class="label-cell"><strong>Facultad:</strong></td>
                            <td><?= htmlspecialchars($t->FacultadTexto ?? 'N/A') ?></td>
                            <td class="label-cell"><strong>Carrera:</strong></td>
                            <td><?= htmlspecialchars($t->CarreraTexto ?? 'N/A') ?></td>
                        </tr>
                    </tbody>
                </table>
            <?php endforeach; ?>
        <?php endif; ?>


        <table class="section-table" ">
            <tr>
                <td colspan=" 2" style="font-weight:bold; background-color:#F2F2F2;">3.3 Grupo de Estudiantes</td>
            </tr>
        </table>

        <table style="width:100%; border-collapse: collapse;">
            <tr>
                <td style="background-color: #E7F5FE; padding: 6px 8px; font-weight: bold; border: 1px solid #BDEBFF;">
                    3.3.1 Estudiantes de Grado:
                </td>
            </tr>
        </table>

        <div style="margin-left: 15px;">

            <p style="font-weight: bold; padding-bottom: 3px; border-bottom: 1px solid #DDD;">
                3.3.1.1 Número de estudiantes por ciclo académico
            </p>
            <table class="table table-bordered" style="width: 100%; border-collapse: collapse;">
                <thead class="fw-bold">
                    <tr>
                        <th>Ciclo</th>
                        <th># Estudiantes</th>
                        <th>Discapacidad</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($proyectoCompleto->estudiantes_ciclo)): ?>
                        <?php foreach ($proyectoCompleto->estudiantes_ciclo as $ec): ?>
                            <tr>
                                <td><?= htmlspecialchars($ec->Ciclo ?? 'N/A') ?></td>
                                <td><?= htmlspecialchars($ec->TotalEstudiantes ?? '0') ?></td>
                                <td><?= htmlspecialchars($ec->EstudiantesDiscapacidad ?? '0') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" class="text-center text-muted">No hay estudiantes por ciclo registrados.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <p style="font-weight: bold; padding-bottom: 3px; border-bottom: 1px solid #DDD;">
                3.3.1.2 Número de estudiantes por carrera:
            </p>
            <table class="table table-bordered" style="width: 100%; border-collapse: collapse;">
                <thead class="fw-bold">
                    <tr>
                        <th>Facultad</th>
                        <th>Carrera</th>
                        <th># Estudiantes</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($proyectoCompleto->estudiantes_carrera)): ?>
                        <?php foreach ($proyectoCompleto->estudiantes_carrera as $ec): ?>
                            <tr>
                                <td><?= htmlspecialchars($ec->FacultadTexto ?? 'N/A') ?></td>
                                <td><?= htmlspecialchars($ec->CarreraTexto ?? 'N/A') ?></td>
                                <td><?= htmlspecialchars($ec->NumeroEstudiantes ?? '0') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" class="text-center text-muted">No hay estudiantes por carrera registrados.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <table style="width:100%; border-collapse: collapse;">
            <tr>
                <td style="background-color: #E7F5FE; padding: 6px 8px; font-weight: bold; border: 1px solid #BDEBFF;">
                    3.3.2 Estudiantes de Posgrado:
                </td>
            </tr>
        </table>

        <table class="table table-bordered mt-2" style="width: 100%; border-collapse: collapse;">
            <thead class="fw-bold">
                <tr>
                    <th>Programa de Posgrado</th>
                    <th># Estudiantes</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($proyectoCompleto->estudiantes_programas)): ?>
                    <?php foreach ($proyectoCompleto->estudiantes_programas as $ep): ?>
                        <tr>
                            <td><?= htmlspecialchars($ep->ProgramaArticulacion ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($ep->NumeroEstudiantesPrograma ?? '0') ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="2" class="text-center text-muted">No hay estudiantes de posgrado registrados.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>


        <table class="section-table">
            <tr>
                <td colspan="2" style="font-weight:bold; background-color:#F2F2F2;">3.3.3 Describa brevemente las acciones...</td>
            </tr>
        </table>

        <div style="text-align: justify; ">
            <?php if (!empty($proyectoCompleto->acciones_contribucion)): ?>
                <?php foreach ($proyectoCompleto->acciones_contribucion as $ac): ?>
                    <p><?= nl2br(htmlspecialchars($ac->AccionesContribucion ?? 'N/A')) ?></p>
                <?php endforeach; ?>
            <?php else: ?>
                <p>N/A</p>
            <?php endif; ?>
        </div>

        <h2 style="page-break-before: always;">4. DESCRIPCIÓN DETALLADA DEL PROYECTO</h2>

        <table class="section-table">
            <tr>
                <td style="font-weight: bold; background-color:#F2F2F2;">4.1 Descripción del problema y línea base:</td>

            </tr>
        </table>
        <div style="text-align: justify; margin-bottom: 10px;">
            <p><?= nl2br(htmlspecialchars($detalle->Justificacion ?? 'N/A')) ?></p>
        </div>

        <table class="section-table">
            <tr>
                <td style="font-weight: bold; background-color:#F2F2F2;">4.1.1 Línea base:</td>

            </tr>
        </table>
        <div style="text-align: justify; margin-bottom: 10px;">
            <p><?= nl2br(htmlspecialchars($detalle->LineaBase ?? 'N/A')) ?></p>
        </div>

        <table class="section-table">
            <tr>
                <td style="font-weight: bold; background-color:#F2F2F2;">4.2 Fundamentación Teórica:</td>

            </tr>
        </table>
        <div style="text-align: justify; margin-bottom: 10px;">
            <p><?= nl2br(htmlspecialchars($detalle->FundamentacionTeorica ?? 'N/A')) ?></p>
        </div>

        <table class="section-table">
            <tr>
                <td colspan="2" style="font-weight: bold; background-color:#F2F2F2;">4.3 Descripción de la población objetivo:</td>

            </tr>
        </table>
        <div style="text-align: justify; margin-bottom: 10px;">
            <p><?= nl2br(htmlspecialchars($poblacion->Descripcion ?? 'N/A')) ?></p>
        </div>

        <table class="section-table">
            <tr>
                <td style="font-weight: bold; background-color:#F2F2F2;">4.3.1 Número total de la población objetivo:</td>
                <td><?= htmlspecialchars($poblacion->NumeroTotalPoblacion ?? 'N/A') ?></td>
            </tr>
        </table>


        <table class="section-table">
            <tr>
                <td colspan="2" style="font-weight: bold; background-color:#F2F2F2;">
                    4.4 Beneficiarios directos:
                </td>
            </tr>
        </table>

        <div style="padding: 5px 8px; text-align: justify;">
            <p class="text-normal"><?= htmlspecialchars($poblacion->DetalleDirecto ?? 'N/A') ?></p>
        </div>

        <table class="table table-bordered" style="width: 100%; border-collapse: collapse;">
            <thead class="fw-bold">
                <tr>
                    <th>Grupo beneficiario directo</th>
                    <th>Descripción</th>
                    <th>Número de integrantes</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($proyectoCompleto->beneficiarios_directos)): ?>
                    <?php $totalBeneficiariosDirectos = 0; ?>
                    <?php foreach ($proyectoCompleto->beneficiarios_directos as $bd): ?>
                        <?php $totalBeneficiariosDirectos += (int)($bd->NumeroBeneficiarios ?? 0); ?>
                        <tr>
                            <td><?= htmlspecialchars($bd->Grupo ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($bd->Descripcion ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($bd->NumeroBeneficiarios ?? '0') ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" class="text-center text-muted">No hay beneficiarios directos registrados.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
            <tfoot class="fw-bold">
                <tr>
                    <td colspan="2" class="text-end fw-bold">TOTAL:</td>
                    <td><?= $totalBeneficiariosDirectos ?? '0' ?></td>
                </tr>
            </tfoot>
        </table>


        <table class="section-table" style="margin-top: 15px;">
            <tr>
                <td colspan="2" style="font-weight: bold; background-color:#F2F2F2;">
                    4.5 Beneficiarios indirectos:
                </td>
            </tr>
        </table>

        <div style="padding: 5px 8px; text-align: justify; ">
            <p class="text-normal"><?= htmlspecialchars($poblacion->DetalleIndirecto ?? 'N/A') ?></p>
        </div>

        <table class="table table-bordered" style="width: 100%; border-collapse: collapse;">
            <thead class="fw-bold">
                <tr>
                    <th>Grupo beneficiario indirecto</th>
                    <th>Descripción</th>
                    <th>Número de integrantes</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($proyectoCompleto->beneficiarios_indirectos)): ?>
                    <?php $totalBeneficiarios = 0; ?>
                    <?php foreach ($proyectoCompleto->beneficiarios_indirectos as $bi): ?>
                        <?php $totalBeneficiarios += (int)($bi->NumeroBeneficiarios ?? 0); ?>
                        <tr>
                            <td><?= htmlspecialchars($bi->Grupo ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($bi->Descripcion ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($bi->NumeroBeneficiarios ?? '0') ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3" class="text-center text-muted">No hay beneficiarios registrados.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
            <tfoot class="fw-bold">
                <tr>
                    <td colspan="2" class="text-end fw-bold">TOTAL:</td>
                    <td><?= $totalBeneficiarios ?? '0' ?></td>
                </tr>
            </tfoot>
        </table>

        <table class="section-table">
            <tr>
                <td colspan="2" style="font-weight: bold; background-color:#F2F2F2;">
                    4.6 Objetivo General:
                </td>
            </tr>
        </table>
        <div style="text-align: justify; margin-bottom: 10px; padding: 5px 8px;">
            <p><?= htmlspecialchars($obj_met->ObjetivoGeneral ?? 'N/A') ?></p>
        </div>


        <table class="section-table" style="margin-top: 15px;">
            <tr>
                <td colspan="2" style="font-weight: bold; background-color:#F2F2F2;">
                    4.7 Objetivos Específicos:
                </td>
            </tr>
        </table>
        <div style="margin-bottom: 10px; padding: 5px 8px;">
            <?php if (!empty($proyectoCompleto->objetivos_especificos)): ?>
                <ul style="margin: 0; padding-left: 20px;">
                    <?php foreach ($proyectoCompleto->objetivos_especificos as $index => $oe): ?>
                        <li>
                            <strong>OE<?= $index + 1 ?>:</strong> <?= htmlspecialchars($oe->ObjetivoEspecifico ?? 'N/A') ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </div>
        <table class="section-table">
            <tr>
                <td style="font-weight: bold; background-color:#F2F2F2;">4.8 Metodología:</td>

            </tr>
        </table>
        <div style="text-align: justify; margin-bottom: 10px;">
            <p><?= nl2br(htmlspecialchars($obj_met->Metodologia ?? 'N/A')) ?></p>
        </div>

        <table class="section-table">
            <tr>
                <td style="font-weight: bold; background-color:#F2F2F2;">4.8.1 Diálogo de saberes, interculturalidad y sostenibilidad ambiental:</td>

            </tr>
        </table>
        <div style="text-align: justify; margin-bottom: 10px;">
            <p><?= nl2br(htmlspecialchars($obj_met->Dialogo ?? 'N/A')) ?></p>
            <p><?= nl2br(htmlspecialchars($obj_met->Interculturalidad ?? 'N/A')) ?></p>
            <p><?= nl2br(htmlspecialchars($obj_met->SostenibilidadAmbiental ?? 'N/A')) ?></p>
        </div>

        <table class="section-table">
            <tr>
                <td style="font-weight: bold; background-color:#F2F2F2;">4.9 Metodología de evaluación de impacto:</td>

            </tr>
        </table>
        <div style="text-align: justify; margin-bottom: 10px;">
            <p><?= nl2br(htmlspecialchars($obj_met->EvaluacionImpacto ?? 'N/A')) ?></p>
        </div>

        <table class="section-table">
            <tr>
                <td style="font-weight: bold; background-color:#F2F2F2;">4.9.1 Línea de comparación:</td>

            </tr>
        </table>
        <div style="text-align: justify; margin-bottom: 10px;">
            <p><?= nl2br(htmlspecialchars($obj_met->LineaComparacion ?? 'N/A')) ?></p>
        </div>

        <table class="section-table" style="margin-top: 15px;">
            <tr>
                <td colspan="2" style="font-weight:bold; background-color:#F2F2F2;">
                    4.11 Resultados y productos esperados
                </td>
            </tr>
        </table>
        <div style="text-align: justify; margin-bottom: 10px;">
            <p>Los resultados se entregarán en cada fase del proyecto, empezando por la planificación y análisis de las necesidades de las familias beneficiarias. <br>
                Verificación de los resultados por parte del equipo de desarrollo y los respectivos usuarios. <br>
                Plataforma sistematizada operativa, manual de usuario y acta de entrega. <br>
                Capacitación a los usuarios en el uso de la plataforma web proporcionada.
            </p>
        </div>

        <table class="table table-bordered" style="width: 100%; border-collapse: collapse; margin-top: 10px;">
            <thead>
                <tr>
                    <th style="width: 25%;">Objetivo específico</th>
                    <th style="width: 25%;">Indicadores</th>
                    <th style="width: 25%;">Resultados</th>
                    <th style="width: 25%;">Productos</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $objetivos_especificos_map = [];
                if (!empty($proyectoCompleto->objetivos_especificos)) {
                    foreach ($proyectoCompleto->objetivos_especificos as $index => $oe) {
                        $objetivos_especificos_map[$index + 1] = $oe->ObjetivoEspecifico;
                    }
                }
                ?>
                <?php if (!empty($proyectoCompleto->resultados_esperados)): ?>
                    <?php foreach ($proyectoCompleto->resultados_esperados as $rep): ?>
                        <tr>
                            <td><?= htmlspecialchars($objetivos_especificos_map[$rep->ObjetivoIndex + 1] ?? 'Objetivo no encontrado') ?></td>
                            <td><?= htmlspecialchars($rep->Indicador ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($rep->Resultado ?? 'N/A') ?></td>
                            <td>
                                <?php
                                $productos = json_decode($rep->ProductosEsperados ?? '[]', true);
                                if (!empty($productos) && is_array($productos)) {
                                    echo '<ul style="margin: 0; padding-left: 15px;">';
                                    foreach ($productos as $prod) {
                                        echo '<li>' . htmlspecialchars($prod) . '</li>';
                                    }
                                    echo '</ul>';
                                } else {
                                    echo 'N/A';
                                }
                                ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" style="text-align: center;">No hay resultados definidos.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>


        <table class="section-table" style="margin-top: 15px;">
            <tr>
                <td colspan="2" style="font-weight:bold; background-color:#F2F2F2;">
                    4.12 Ponencias / Publicaciones
                </td>
            </tr>
        </table>

        <table class="table table-bordered" style="width: 100%; border-collapse: collapse; margin-top: 10px;">
            <thead>
                <tr>
                    <th style="width: 70%;">Descripción</th>
                    <th style="width: 30%;">Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Número de ponencias nacionales indexadas con código ISBN y texto completo</td>
                    <td><?= htmlspecialchars($res->PonenciasNacionales ?? '0') ?></td>
                </tr>
                <tr>
                    <td>Número de ponencias internacionales indexadas con código ISBN y texto completo</td>
                    <td><?= htmlspecialchars($res->PonenciasInternacionales ?? '0') ?></td>
                </tr>
                <tr>
                    <td>Número de artículos científicos publicados en revistas indexadas (Scopus, Scielo, Latindex, etc.)</td>
                    <td><?= htmlspecialchars($res->ArticulosCientificos ?? '0') ?></td>
                </tr>
                <tr>
                    <td>Número de libros publicados con código ISBN</td>
                    <td><?= htmlspecialchars($res->LibrosPublicados ?? '0') ?></td>
                </tr>
                <tr>
                    <td>Número de capítulos de libros publicados con código ISBN</td>
                    <td><?= htmlspecialchars($res->CapitulosLibros ?? '0') ?></td>
                </tr>
                <tr>
                    <td>Número de publicaciones en revistas de divulgación científica o tecnológica</td>
                    <td><?= htmlspecialchars($res->RevistasDivulgacion ?? '0') ?></td>
                </tr>
                <tr>
                    <td>Número de publicaciones en otros medios (periódicos, revistas, blogs, etc.)</td>
                    <td><?= htmlspecialchars($res->OtrasPublicaciones ?? '0') ?></td>
                </tr>
            </tbody>
        </table>


        <table class="section-table" style="margin-top: 15px;">
            <tr>
                <td colspan="2" style="font-weight:bold; background-color:#F2F2F2;">
                    4.12.2 Otros productos de transferencia de conocimiento
                </td>
            </tr>
        </table>

        <table class="table table-bordered" style="width: 100%; border-collapse: collapse; margin-top: 10px;">
            <thead>
                <tr>
                    <th style="width: 70%;">Descripción</th>
                    <th style="width: 30%;">Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Número de talleres, seminarios, charlas, cursos de capacitación, etc.</td>
                    <td><?= htmlspecialchars($res->TalleresCapacitacion ?? '0') ?></td>
                </tr>
                <tr>
                    <td>Número de productos tecnológicos (software, prototipos, patentes, etc.)</td>
                    <td><?= htmlspecialchars($res->ProductosTecnologicos ?? '0') ?></td>
                </tr>
                <tr>
                    <td>Número de productos artísticos (obras de arte, exposiciones, etc.)</td>
                    <td><?= htmlspecialchars($res->ProductosArtisticos ?? '0') ?></td>
                </tr>
                <tr>
                    <td>Número de productos culturales (eventos, festivales, etc.)</td>
                    <td><?= htmlspecialchars($res->ProductosCulturales ?? '0') ?></td>
                </tr>
                <tr>
                    <td>Número de productos sociales (modelos de intervención, metodologías, etc.)</td>
                    <td><?= htmlspecialchars($res->ProductosSociales ?? '0') ?></td>
                </tr>
            </tbody>
        </table>


        <table class="section-table" style="margin-top: 15px;">
            <tr>
                <td colspan="2" style="font-weight:bold; background-color:#F2F2F2;">
                    4.13 Impactos del proyecto en la Universidad de Guayaquil
                </td>
            </tr>
        </table>

        <table class="table table-bordered" style="width: 100%; border-collapse: collapse; margin-top: 10px;">
            <thead>
                <tr>
                    <th style="width: 40%;">Efectos</th>
                    <th style="width: 60%;">Descripción</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Nuevas investigaciones</td>
                    <td><?= htmlspecialchars($res->EfectosNuevasInvestigaciones ?? 'N/A') ?></td>
                </tr>
                <tr>
                    <td>Nuevas metodologías, procesos o técnicas aplicables</td>
                    <td><?= htmlspecialchars($res->EfectosNuevasMetodologias ?? 'N/A') ?></td>
                </tr>
                <tr>
                    <td>Nuevos trabajos de titulación (grado y/o posgrado)</td>
                    <td><?= htmlspecialchars($res->EfectosNuevosTrabajosTitulacion ?? 'N/A') ?></td>
                </tr>
            </tbody>
        </table>

        <table class="section-table">
            <tr>
                <td style="font-weight: bold; background-color:#F2F2F2;">4.14 Referencias citadas:</td>

            </tr>
        </table>
        <div style="text-align: justify; margin-bottom: 10px;">
            <p><?= nl2br(htmlspecialchars($res->ReferenciasCitadas ?? 'N/A')) ?></p>
        </div>
        <h2 style="page-break-before: always;">5. SEGUIMIENTO Y CONTROL</h2>

        <table class="section-table" style="margin-bottom: 5px; border: 1px solid #999;">
            <tr>
                <td style="border: none; padding: 8px;">
                    <strong>5.1. Matriz de seguimiento y control:</strong>
                    <p class="text-normal">A continuación, se detallan los resultados que se van a ir consiguiendo durante la ejecución del proyecto con su respectivo medio de verificación que evidenciará la entrega en el tiempo establecido.
                    </p>
                </td>
            </tr>
        </table>
        <?php if (!empty($proyectoCompleto->matriz_seguimiento)): ?>
            <?php
            // Se inicializa una variable para la suma total.
            $total_cantidad = 0;
            ?>
            <table>
                <thead>
                    <tr>
                        <th>Resultados esperados</th>
                        <th>Cantidad</th>
                        <th>Medio de verificación</th>
                        <th>Fecha</th>
                        <th>Responsable</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($proyectoCompleto->matriz_seguimiento as $item): ?>
                        <?php
                        // Se suma la cantidad de la fila actual al total.
                        $total_cantidad += (int)($item->Cantidad ?? 0);
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($item->ResultadoEsperado ?? '') ?></td>
                            <td><?= htmlspecialchars($item->Cantidad ?? '') ?></td>
                            <td><?= htmlspecialchars($item->MedioVerificacion ?? '') ?></td>
                            <td><?= htmlspecialchars($item->FechaResultadoParcial ? date('d/m/Y', strtotime($item->FechaResultadoParcial)) : '') ?></td>
                            <td><?= htmlspecialchars($item->ResponsableControlVerificacion ?? '') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td style="text-align: right; font-weight: bold; background-color: #F2F2F2;">TOTAL CANTIDAD:</td>

                        <td style="font-weight: bold; background-color: #F2F2F2;"><?= $total_cantidad ?></td>

                        <td style="background-color: #F2F2F2;">&nbsp;</td>
                        <td style="background-color: #F2F2F2;">&nbsp;</td>
                        <td style="background-color: #F2F2F2;">&nbsp;</td>
                    </tr>
                </tfoot>
            </table>
        <?php else: ?>
            <p>No hay datos en la matriz de seguimiento.</p>
        <?php endif; ?>
        <h2 style="page-break-before: always;">6. CRONOGRAMA DE ACTIVIDADES</h2>
        <?php if (!empty($proyectoCompleto->cronograma)): ?>
            <?php
            $duracion_texto = $proyectoCompleto->datos_generales->DuracionTexto ?? '0 Meses';
            preg_match('/^\d+/', $duracion_texto, $matches);
            $meses_totales = (int)($matches[0] ?? 12);

            $cronograma_agrupado = [];
            foreach ($proyectoCompleto->cronograma as $actividad) {
                $cronograma_agrupado[$actividad->ObjetivoRelacionado][] = $actividad;
            }

            $objetivos_especificos_map = [];
            $count = 0;
            if (!empty($proyectoCompleto->objetivos_especificos)) {
                foreach ($proyectoCompleto->objetivos_especificos as $oe) {
                    $count++;
                    $objetivos_especificos_map[$count] = $oe->ObjetivoEspecifico;
                }
            }
            ?>


            <table style="border-collapse: collapse; width: 100%; font-size: 8pt; table-layout: fixed;">
                <thead>
                    <tr style="background-color: #DDEBF7;">
                        <th style="border: 1px solid #999; padding: 4px; width: 30%; text-align: left;" rowspan="2">Objetivo Específico / Tareas</th>
                        <th style="border: 1px solid #999; padding: 4px; width: 15%; text-align: left;" rowspan="2">Responsable(s)</th>
                        <th style="border: 1px solid #999; padding: 4px; text-align: center;" colspan="<?= $meses_totales ?>">MESES</th>
                    </tr>
                    <tr style="background-color: #DDEBF7;">
                        <?php for ($i = 1; $i <= $meses_totales; $i++): ?>
                            <th style="border: 1px solid #999; padding: 2px; text-align: center; font-size: 7pt;"><?= $i ?></th>
                        <?php endfor; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cronograma_agrupado as $objId => $actividades): ?>
                        <tr style="background-color: #F2F2F2;">
                            <td colspan="<?= 2 + $meses_totales ?>" style="border: 1px solid #999; padding: 4px; font-weight: bold;">
                                OE<?= $objId ?>: <?= htmlspecialchars($objetivos_especificos_map[$objId] ?? 'Objetivo no encontrado') ?>

                            </td>
                        </tr>
                        <?php foreach ($actividades as $actividad): ?>
                            <tr>
                                <td style="border: 1px solid #999; padding: 4px; padding-left: 15px;"><?= htmlspecialchars($actividad->Actividad ?? '') ?></td>
                                <td style="border: 1px solid #999; padding: 4px;"><?= htmlspecialchars($actividad->Responsable ?? '') ?></td>

                                <?php
                                $meses_a_marcar = !empty($actividad->MesesAsignados) ? explode(',', $actividad->MesesAsignados) : [];

                                for ($mes_actual = 1; $mes_actual <= $meses_totales; $mes_actual++):
                                    $cell_style = in_array($mes_actual, $meses_a_marcar) ? 'background-color: #f6f041ff;' : '';
                                ?>
                                    <td style="border: 1px solid #999; <?= $cell_style ?>"></td>
                                <?php endfor; ?>
                            </tr>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No hay actividades definidas en el cronograma.</p>
        <?php endif; ?>


        <h2>7. PRESUPUESTO</h2>
        <?php if (!empty($proyectoCompleto->presupuesto)): ?>
            <?php
            // 1. Inicializamos una variable para guardar el total general del presupuesto.
            $total_presupuesto = 0;
            ?>
            <table>
                <thead>
                    <tr>
                        <th>Talento Humano UG</th>
                        <th>Cantidad</th>
                        <th>Especificaciones</th>
                        <th>Horas</th>
                        <th>Meses</th>
                        <th>Valor/Hora</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($proyectoCompleto->presupuesto as $item): ?>
                        <?php
                        // 2. Calculamos el total para esta fila.
                        // Usamos la misma fórmula que en tu JavaScript: Cantidad * Horas * Meses * 4 semanas * Valor por Hora
                        $total_fila = (float)($item->Cantidad ?? 0) *
                            (float)($item->Horas ?? 0) *
                            (float)($item->Meses ?? 0) *
                            4 * // Se asumen 4 semanas por mes para ser consistente con el cálculo del formulario.
                            (float)($item->ValorHora ?? 0);

                        // 3. Sumamos el total de esta fila al total general.
                        $total_presupuesto += $total_fila;
                        ?>
                        <tr>
                            <td><?= htmlspecialchars($item->Responsable ?? '') ?></td>
                            <td><?= htmlspecialchars($item->Cantidad ?? '') ?></td>
                            <td><?= htmlspecialchars($item->Especificaciones ?? '') ?></td>
                            <td><?= htmlspecialchars($item->Horas ?? '') ?></td>
                            <td><?= htmlspecialchars($item->Meses ?? '') ?></td>
                            <td>$<?= number_format((float)($item->ValorHora ?? 0), 2) ?></td>

                            <td style="font-weight: bold;">$<?= number_format($total_fila, 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="6" style="text-align: right; font-weight: bold; background-color: #F2F2F2;">TOTAL GENERAL DEL PRESUPUESTO:</td>
                        <td style="font-weight: bold; background-color: #F2F2F2;">$<?= number_format($total_presupuesto, 2) ?></td>
                    </tr>
                </tfoot>
            </table>
        <?php else: ?>
            <p>No hay datos de presupuesto definidos.</p>
        <?php endif; ?>

        <h2 style="page-break-before: always;">8. DECLARACIÓN FINAL</h2>
        <table class="section-table">
            <tr>
                <td colspan="2">
                    <p class="mb-4 text-normal">Los abajo firmantes declaramos bajo juramento que el proyecto descrito en este documento es de nuestra autoría, no causa perjuicio a las personas involucradas y/o comunidades; ambiente, e instituciones vinculadas, y no transgrede ninguna norma ética.
                    </p>
                    <p class="text-normal">Aceptamos también, que los descubrimientos e invenciones, las mejoras en los procedimientos, así como los trabajos y resultados que se logren alcanzar dentro del proyecto; así como lo correspondiente a la titularidad de los derechos de propiedad intelectual que pudieran llegar a derivarse de la ejecución del mismo, se regirán de conformidad a lo establecido en el Código Orgánico de la Economía Social de los Conocimientos, Creatividad e Innovación.
                    </p>
                </td>
            </tr>
            <tr class="no-border-table">
                <td style="padding-top: 200px; text-align: center; width: 50%;" class="text-normal">

                    <hr style="width: 80%; border-top: 1px solid #bab7b7ff;">
                    Director(a) del Proyecto
                    <?php
                    $director = $proyectoCompleto->directores[0] ?? null;
                    if ($director) {
                        echo '<p style="margin-bottom: 0;"><strong>Nombre:</strong> ' . htmlspecialchars($director->NombreDirector ?? 'N/A') . '</p>';
                        echo '<p style="margin-top: 0;"><strong>C.C.:</strong> ' . htmlspecialchars($director->CedulaDirector ?? 'N/A') . '</p>';
                    } else {
                        // Este bloque se puede eliminar si la etiqueta ya está fija
                    }
                    ?>
                </td>
                <td style="padding-top: 200px; text-align: center; width: 50%;">

                    <hr style="width: 80%; border-top: 1px solid #bab7b7ff;">
                    Firma Decano de la Facultad <br>
                    Ing. Ind. Samaniego Zamora Manuel Israel, Mgs.
                </td>
            </tr>
        </table>
    </main>
</body>

</html>