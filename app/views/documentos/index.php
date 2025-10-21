<?php include(__DIR__ . '/../layout/header.php'); ?>

<style>
    /* Estilos para el iframe del modal, se mantienen */
    #iframeTrazabilidad {
        width: 100%;
        height: 500px;
        border: none;
    }
</style>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center bg-dark">
        <h3 class="mb-0 text-white">Documentos Registrados</h3>
        </div>

    <div class="card-body">
        <div class="table-responsive">
            <table id="tablaDocumentos" class="table table-row-dashed gy-5 table-hover align-middle" style="width:100%;">
                <thead>
                    <tr class="fw-bolder text-muted">
                        <th class="min-w-250px">Documento</th>
                        <th class="min-w-150px">Fecha de Generación</th>
                        <th class="min-w-120px">Estado</th>
                        <th class="min-w-200px text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($documentos)): ?>
                        <?php foreach ($documentos as $doc):
                            $estadoDocumento = $doc['EstadoDoc'] ?? 'BORRADOR';
                        ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="d-flex justify-content-start flex-column">
                                            <span class="text-dark fw-bolder fs-6 text-truncate" style="max-width: 300px;">
                                                <?= htmlspecialchars($doc['NombreArchivoDocGen']) ?>
                                            </span>
                                            <span class="text-muted d-block fs-7">
                                                Tipo: <?= htmlspecialchars($doc['TipoDocumentoDocGen']) ?> | ID: <?= htmlspecialchars($doc['IdDocGen']) ?>
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <?php
                                    $fechaRaw = $doc['FechaGeneracionDocGen'];
                                    $timestamp = strtotime($fechaRaw);
                                    if ($timestamp && $fechaRaw !== '0000-00-00 00:00:00') {
                                        echo '<span class="text-dark fw-bolder d-block fs-6">' . date('d/m/Y', $timestamp) . '</span>';
                                        echo '<span class="text-muted d-block fs-7">' . date('h:i A', $timestamp) . '</span>';
                                    } else {
                                        echo '<span class="text-muted">-</span>';
                                    }
                                    ?>
                                </td>
                                <td data-search="<?= htmlspecialchars($estadoDocumento) ?>">
                                    <?php
                                    $badgeMap = [
                                        'APROBADO'  => 'success',
                                        'PENDIENTE' => 'info',
                                        'CORREGIDO' => 'warning',
                                        'BORRADOR'  => 'secondary',
                                        'REVISADO'  => 'primary',
                                    ];
                                    $estadoActual = strtoupper(trim($estadoDocumento));
                                    $badgeClass = $badgeMap[$estadoActual] ?? 'dark';
                                    ?>
                                    <span class="badge rounded-pill text-bg-<?= $badgeClass ?>">
                                        <?= htmlspecialchars($estadoDocumento) ?>
                                    </span>
                                </td>
                                <td class="text-end">
                    <a href="javascript:void(0);" class="btn btn-icon btn-light-dark btn-sm me-1" title="Ver Trazabilidad" onclick="abrirModalTrazabilidad('<?= urlencode($doc['IdDocGen']) ?>')">
                        <i class="fas fa-history"></i>
                    </a>

                    <a href="<?= $baseUrl ?>/index.php?c=DocumentosGenerados&m=descargar&id=<?= urlencode($doc['IdDocGen']) ?>" class="btn btn-icon btn-light-primary btn-sm me-1" title="Descargar Documento">
                        <i class="fa fa-download"></i>
                    </a>

                    <?php if ($permisos['puede_editar'] && ($estadoDocumento == 'BORRADOR' || $estadoDocumento == 'REVISADO')): ?>
                        <a href="javascript:void(0);" class="btn btn-icon btn-light-warning btn-sm me-1" title="Editar Documento" onclick="editarDocumento('<?= urlencode($doc['IdDocGen']) ?>', '<?= htmlspecialchars($doc['TipoConvenioDatCon'] ?? '') ?>')">
                            <i class="fa fa-edit"></i>
                        </a>
                    <?php endif; ?>

                    <?php if ($permisos['puede_enviar'] && ($estadoDocumento == 'BORRADOR' || $estadoDocumento == 'REVISADO')): ?>
                        <a href="javascript:void(0);" class="btn btn-icon btn-light-info btn-sm me-1" title="Enviar para Revisión" onclick="enviarParaRevision('<?= urlencode($doc['IdDocGen']) ?>')">
                            <i class="fa fa-paper-plane"></i>
                        </a>
                    <?php endif; ?>
                    
                    <?php if ($permisos['puede_aprobar'] && ($estadoDocumento == 'PENDIENTE' || $estadoDocumento == 'CORREGIDO')): ?>
                        <a href="javascript:void(0);" class="btn btn-icon btn-light-success btn-sm me-1" title="Aprobar Documento" onclick="aprobarDocumento('<?= urlencode($doc['IdDocGen']) ?>')">
                            <i class="fa fa-check"></i>
                        </a>
                    <?php endif; ?>

                    <?php if ($permisos['puede_eliminar']): ?>
                        <?php
                        $urlEliminar = "{$baseUrl}/index.php?c=DocumentosGenerados&m=eliminar&id=" . urlencode($doc['IdDocGen']);
                        ?>
                        <a href="javascript:void(0);" onclick="confirmarEliminacion('<?= $urlEliminar ?>')" class="btn btn-icon btn-light-danger btn-sm" title="Eliminar Documento">
                            <i class="fa fa-trash"></i>
                        </a>
                    <?php endif; ?>
                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modalTrazabilidad" tabindex="-1" aria-labelledby="modalTrazabilidadLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTrazabilidadLabel">Historial de Trazabilidad del Documento</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <iframe id="iframeTrazabilidad" src="about:blank"></iframe>
            </div>
        </div>
    </div>
</div>



<script>
    $(document).ready(function() {
        $('#tablaDocumentos').DataTable({
            language: {
                url: "https://cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json"
            },
            order: [
                [1, "desc"]
            ],
            responsive: true,
            stateSave: true,
            columnDefs: [{
                orderable: false,
                targets: [2, 3]
            }]
        });
    });

    // Función para abrir el modal (sin cambios)
    function abrirModalTrazabilidad(id) {
        const url = '<?= $baseUrl ?>/index.php?c=DocumentosGenerados&m=verTrazabilidad&id=' + id;
        $('#iframeTrazabilidad').attr('src', url);
        const modal = new bootstrap.Modal(document.getElementById('modalTrazabilidad'));
        modal.show();
    }
    
    // --- INICIO DE FUNCIONES ACTUALIZADAS CON SWEETALERT2 ---

    function aprobarDocumento(id) {
        const url = `<?= $baseUrl ?>/index.php?c=DocumentosGenerados&m=aprobar&id=${id}`;
        Swal.fire({
            title: '¿Estás seguro?',
            text: "Vas a aprobar este documento y se marcará como finalizado.",
            icon: 'success',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, ¡aprobar!',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = url;
            }
        });
    }

    function enviarParaRevision(id) {
        const url = `<?= $baseUrl ?>/index.php?c=DocumentosGenerados&m=enviar&id=${id}`;
        Swal.fire({
            title: '¿Enviar para Revisión?',
            text: "El documento cambiará su estado a 'Pendiente'.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, ¡enviar!',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = url;
            }
        });
    }

    // Nueva función para la confirmación de eliminación
    function confirmarEliminacion(url) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "¡No podrás revertir esta acción!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, ¡eliminar!',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = url;
            }
        });
    }

    // Función de editar (sin cambios, no necesita confirmación)
    function editarDocumento(id, tipoConvenio) {
        let metodo = '';
        switch (tipoConvenio.toLowerCase()) {
            case 'adendum': metodo = 'editarAdendum'; break;
            case 'marco': metodo = 'editarMarco'; break;
            case 'especifico': metodo = 'editarEspecifico'; break;
            default:
                Swal.fire('Error', 'No se puede editar este tipo de documento.', 'error');
                return;
        }
        window.location.href = `<?= $baseUrl ?>/index.php?c=DocumentosGenerados&m=${metodo}&id=${id}`;
    }
</script>
<?php include(__DIR__ . '/../layout/footer.php'); ?>