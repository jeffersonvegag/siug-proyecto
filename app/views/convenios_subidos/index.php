<?php include(__DIR__ . '/../layout/header.php'); ?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center bg-dark">
        <h3 class="mb-0 text-white">Convenios Subidos</h3>
        <a href="<?= $baseUrl ?>/index.php?c=ConveniosSubidos&m=subir" class="btn btn-primary">
            <i class="fa fa-upload me-2"></i>Subir Nuevo Convenio
        </a>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table id="tablaConvenios" class="table table-row-dashed gy-5 table-hover align-middle" style="width:100%;">
                <thead>
                    <tr class="fw-bolder text-muted">
                        <th class="min-w-300px">Convenio</th>
                        <th class="min-w-150px">Fecha de Subida</th>
                        <th class="min-w-150px text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($archivos as $a): ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="d-flex justify-content-start flex-column">
                                        <span class="text-dark fw-bolder fs-6 text-truncate" title="<?= htmlspecialchars($a['NombreOriginalConSub']) ?>" style="max-width: 350px;">
                                            <?= htmlspecialchars($a['NombreOriginalConSub']) ?>
                                        </span>
                                        <span class="text-muted d-block fs-7">
                                            <?= htmlspecialchars($a['DescripcionConSub']) ?>
                                        </span>
                                        <span class="text-muted d-block fs-7">
                                            ID: <?= htmlspecialchars($a['IdConSub']) ?> | Tipo: <?= htmlspecialchars($a['TipoConSub']) ?>
                                        </span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <?php
                                $fechaRaw = $a['FechaSubidaConSub'];
                                $timestamp = strtotime($fechaRaw);
                                if ($timestamp && $fechaRaw !== '0000-00-00 00:00:00') {
                                    echo '<span class="text-dark fw-bolder d-block fs-6">' . date('d/m/Y', $timestamp) . '</span>';
                                    echo '<span class="text-muted d-block fs-7">' . date('h:i A', $timestamp) . '</span>';
                                } else {
                                    echo '<span class="text-muted">-</span>';
                                }
                                ?>
                            </td>
                            <td class="text-end">
                                <a href="<?= $baseUrl ?>/index.php?c=ConveniosSubidos&m=preview&id=<?= $a['IdConSub'] ?>" class="btn btn-icon btn-light-info btn-sm me-1" target="_blank" title="Vista Previa">
                                    <i class="fa fa-eye"></i>
                                </a>

                                <a href="<?= $baseUrl ?>/index.php?c=ConveniosSubidos&m=descargar&id=<?= $a['IdConSub'] ?>" class="btn btn-icon btn-light-primary btn-sm me-1" title="Descargar">
                                    <i class="fa fa-download"></i>
                                </a>

                                <a href="<?= $baseUrl ?>/index.php?c=ConveniosSubidos&m=editar&id=<?= $a['IdConSub'] ?>" class="btn btn-icon btn-light-warning btn-sm me-1" title="Editar">
                                    <i class="fa fa-edit"></i>
                                </a>

                                <?php
                                $urlEliminar = "{$baseUrl}/index.php?c=ConveniosSubidos&m=eliminar&id=" . $a['IdConSub'];
                                ?>
                                <a href="javascript:void(0);" onclick="confirmarEliminacionConvenio('<?= $urlEliminar ?>')" class="btn btn-icon btn-light-danger btn-sm" title="Eliminar">
                                    <i class="fa fa-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#tablaConvenios').DataTable({
            language: {
                url: "https://cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json"
            },
            order: [
                [1, "desc"] // Ordenar por fecha descendente
            ],
            responsive: true,
            stateSave: true,
            columnDefs: [{
                orderable: false, // La columna de Acciones no se puede ordenar
                targets: [2]
            }]
        });
    });

    // --- FUNCIÓN AÑADIDA PARA SWEETALERT2 ---
    function confirmarEliminacionConvenio(url) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "¡No podrás revertir la eliminación de este convenio!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, ¡eliminar!',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                // Si el usuario confirma, redirige a la URL de eliminación
                window.location.href = url;
            }
        });
    }
</script>

<?php include(__DIR__ . '/../layout/footer.php'); ?>