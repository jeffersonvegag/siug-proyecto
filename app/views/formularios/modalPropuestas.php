<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            /* Un fondo muy ligero para contraste */
            padding: 1rem;
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", "Liberation Sans", sans-serif;
        }

        .timeline {
            position: relative;
            padding-left: 2.5rem;
            /* Espacio para la línea y los iconos */
            border-left: 2px solid #e9ecef;
        }

        .timeline-item {
            position: relative;
            padding-bottom: 2rem;
        }

        .timeline-item:last-child {
            padding-bottom: 0;
        }

        .timeline-icon {
            position: absolute;
            left: -3.7rem;
            /* Centra el icono en la línea */
            top: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 2.5rem;
            height: 2.5rem;
            background-color: #fff;
            border-radius: 50%;
            font-size: 1.2rem;
        }
    </style>
</head>

<body>

    <div class="container-fluid">
        <?php if (!empty($trazabilidad)): ?>
            <div class="timeline">
                <?php foreach ($trazabilidad as $item): ?>
                    <?php
                    // Lógica para determinar el icono y color según la acción
                    $accionNormalizada = strtoupper($item['Accion']);
                    $iconClass = 'fa-info-circle';
                    $colorClass = 'secondary';

                    if (str_contains($accionNormalizada, 'APROBADO')) {
                        // Aprobado: Un check de éxito. Perfecto como estaba.
                        $iconClass = 'fa-check-circle';
                        $colorClass = 'success';
                    } elseif (str_contains($accionNormalizada, 'REVISADO')) {
                        // Revisado: Ícono de lupa, significa que fue inspeccionado.
                        $iconClass = 'fa-magnifying-glass';
                        $colorClass = 'info';
                    } elseif (str_contains($accionNormalizada, 'CORREGIDO')) {
                        // Corregido: Un lápiz, indica que se realizaron cambios o ediciones.
                        $iconClass = 'fa-pencil';
                        $colorClass = 'warning';
                    } elseif (str_contains($accionNormalizada, 'RECHAZADO')) {
                        // Rechazado: Símbolo de prohibido, más claro que una papelera.
                        $iconClass = 'fa-ban';
                        $colorClass = 'danger';
                    } elseif (str_contains($accionNormalizada, 'BORRADOR')) {
                        // Borrador: Un documento en edición, y un color neutro.
                        $iconClass = 'fa-file-pen';
                        $colorClass = 'secondary';
                    } elseif (str_contains($accionNormalizada, 'ENVIADO')) {
                        // Enviado: Un avión de papel, representa el envío de información.
                        $iconClass = 'fa-paper-plane';
                        $colorClass = 'primary';
                    } else {
                        // Estado por defecto o desconocido.
                        $iconClass = 'fa-info-circle';
                        $colorClass = 'dark';
                    }
                    ?>

                    <div class="timeline-item">
                        <div class="timeline-icon">
                            <i class="fas <?= $iconClass ?> text-<?= $colorClass ?>"></i>
                        </div>
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <h6 class="fw-bolder mb-0 text-<?= $colorClass ?>"><?= htmlspecialchars($item['Accion']) ?></h6>
                                    <small class="text-muted"><?= date('d/m/Y h:i A', strtotime($item['FechaAccion'])) ?></small>
                                </div>
                                <p class="mb-2 text-muted">
                                    Por: <strong><?= htmlspecialchars($item['Usuario']) ?></strong> (<?= htmlspecialchars($item['RolUsuario']) ?>)
                                </p>
                                <?php if (!empty($item['Comentario'])): ?>
                                    <div class="comentario p-2 bg-light border-start border-4 border-<?= $colorClass ?> rounded">
                                        <small class="fst-italic"><?= nl2br(htmlspecialchars($item['Comentario'])) ?></small>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                <p class="text-muted">No se encontró historial de trazabilidad para este documento.</p>
            </div>
        <?php endif; ?>
    </div>

</body>

</html>