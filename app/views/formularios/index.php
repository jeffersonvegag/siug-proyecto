<?php require_once ROOT_PATH . '/app/views/layout/header.php'; ?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center bg-dark">
        <h3 class="mb-0 text-white">Propuestas Registradas</h3>
        <?php if ($puede_crear): ?>
            <a href="index.php?c=formularios&m=nuevaRespuesta" id="btnNuevaPropuesta" class="btn btn-primary">
                <i class="fa fa-plus"></i> Nueva Propuesta
            </a>
        <?php endif; ?>
    </div>

    <?php
    // Notificaciones con SweetAlert2
    if (isset($_SESSION['mensaje'])) {
        $mensaje = $_SESSION['mensaje'];
        echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            Swal.fire({
                title: '¡Operación Completada!',
                text: '{$mensaje['texto']}',
                icon: '{$mensaje['tipo']}',
                confirmButtonText: 'Entendido'
            });
        });
        </script>";
        unset($_SESSION['mensaje']);
    }
    ?>

    <div class="card-body">
        <?php if (!empty($proyectos)) : ?>
            <div class="table-responsive">
                <table id="tablaProyectosDinamica" class="table table-row-dashed gy-5 table-hover align-middle" style="width:100%">
                    <thead>
                        <tr class="fw-bolder text-muted">
                            <th class="min-w-250px">Propuesta</th>
                            <th class="min-w-150px d-none d-lg-table-cell">Fecha de Registro</th>
                            <th class="min-w-120px">Estado</th>
                            <th class="min-w-100px text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($proyectos as $proyecto) : ?>
                            <?php
                            $estadoActual = isset($proyecto['EstadoPropuesta']) ? strtoupper(trim($proyecto['EstadoPropuesta'])) : 'DESCONOCIDO';
                            ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="d-flex justify-content-start flex-column">
                                            <span class="text-dark fw-bolder fs-6 text-truncate" style="max-width: 300px;"><?php echo htmlspecialchars($proyecto['NombreProyecto']); ?></span>
                                            <span class="text-muted d-block fs-7">ID: <?php echo htmlspecialchars($proyecto['IdPropuesta']); ?></span>
                                        </div>
                                    </div>
                                </td>
                                <td class="d-none d-lg-table-cell">
                                    <span class="text-dark fw-bolder d-block fs-6"><?php echo $proyecto['FechaCreacion'] ? date('d/m/Y', strtotime($proyecto['FechaCreacion'])) : '-'; ?></span>
                                    <span class="text-muted d-block fs-7"><?php echo $proyecto['FechaCreacion'] ? date('h:i A', strtotime($proyecto['FechaCreacion'])) : ''; ?></span>
                                </td>
                                <td data-search="<?= htmlspecialchars($proyecto['EstadoPropuesta'] ?? 'DESCONOCIDO') ?>">
                                    <?php
                                    $badgeMap = [
                                        'APROBADO'  => 'success',
                                        'RECHAZADO' => 'danger',
                                        'CORREGIDO' => 'warning',
                                        'REVISADO'  => 'info',
                                        'BORRADOR'  => 'secondary',
                                    ];
                                    $badgeClass = $badgeMap[$estadoActual] ?? 'secondary';
                                    ?>
                                    <span class="badge rounded-pill text-bg-<?= $badgeClass ?>"><?= htmlspecialchars($proyecto['EstadoPropuesta'] ?? 'Estado desconocido') ?></span>

                                   <?php if (!empty($puede_cambiar_estado) && !empty($proyecto['IdPropuesta']) && !in_array($estadoActual, ['APROBADO', 'RECHAZADO'])): ?>
                                        <div class="btn-group">
                                            <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown"><i class="fa fa-sync-alt"></i></button>
                                            <ul class="dropdown-menu">
                                                <?php foreach ($puede_cambiar_estado as $estadoPermitido): ?>
                                                    <li><a class="dropdown-item" href="index.php?c=formularios&m=cambiarEstado&id=<?= urlencode($proyecto['IdPropuesta']) ?>&estado=<?= urlencode($estadoPermitido) ?>"><?= htmlspecialchars($estadoPermitido) ?></a></li>
                                                <?php endforeach; ?>
                                            </ul>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end">
                                    <a href="javascript:void(0);" class="btn btn-icon btn-light-dark btn-sm me-1" title="Ver Trazabilidad" onclick="abrirModalTrazabilidadPropuesta('<?= urlencode($proyecto['IdPropuesta']) ?>')"><i class="fas fa-history"></i></a>

                                    <?php if (!in_array($estadoActual, ['APROBADO', 'RECHAZADO'])): ?>
                                        <a href="index.php?c=formularios&m=editarPaso1&id=<?php echo $proyecto['IdPropuesta']; ?>" class="btn btn-icon btn-light-warning btn-sm me-1" title="Editar Propuesta">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                    <?php endif; ?>

                                    <button type="button" class="btn btn-icon btn-light-info btn-sm me-1 btn-descargar-pdf" data-id="<?php echo $proyecto['IdPropuesta']; ?>" data-nombre="<?php echo htmlspecialchars($proyecto['NombreProyecto']); ?>" title="Descargar PDF"><i class="fa fa-file-pdf"></i></button>
                                    <button type="button" class="btn btn-icon btn-light-danger btn-sm btn-eliminar" data-id="<?php echo $proyecto['IdPropuesta']; ?>" title="Eliminar Propuesta"><i class="fa fa-trash"></i></button>

                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
<div class="modal fade" id="modalTrazabilidadPropuesta" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Historial de la Propuesta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <iframe id="iframeTrazabilidadPropuesta" src="about:blank" style="width: 100%; height: 500px; border: none;"></iframe>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#tablaProyectosDinamica').DataTable({
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

    // Esta función ahora encontrará el único modal en la página y funcionará correctamente
    function abrirModalTrazabilidadPropuesta(id) {
        const url = 'index.php?c=Formularios&m=verTrazabilidad&id=' + id;
        document.getElementById('iframeTrazabilidadPropuesta').src = url;
        const modal = new bootstrap.Modal(document.getElementById('modalTrazabilidadPropuesta'));
        modal.show();
    }
</script>

<?php require_once ROOT_PATH . '/app/views/layout/footer.php'; ?>