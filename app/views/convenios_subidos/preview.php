<?php include(__DIR__ . '/../layout/header.php'); ?>

<h2>Vista Previa: <?= htmlspecialchars($doc['NombreOriginalConSub']) ?></h2>
<p><?= htmlspecialchars($doc['DescripcionConSub']) ?></p>

<?php if ($tipo === 'pdf' && $rutaPreview): ?>
    <iframe src="<?= $rutaPreview ?>" width="100%" height="600px"></iframe>
<?php else: ?>
    <div class="alert alert-warning">
        Vista previa no disponible aún para archivos Word.<br>
        (Aquí podrías implementar la conversión a PDF con PHPWord + Dompdf).
    </div>
<?php endif; ?>

<a href="<?= $baseUrl ?>/index.php?c=ConveniosSubidos&m=index" class="btn btn-secondary">Volver al listado</a>

<?php include(__DIR__ . '/../layout/footer.php'); ?>
