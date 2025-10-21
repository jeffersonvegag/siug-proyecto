<?php include(__DIR__ . '/../layout/header.php'); ?>

<div class="card">
    <div class="card-header bg-dark">
        <h3 class="card-title text-white">Editar Convenio Subido</h3>
    </div>
    <form method="post" enctype="multipart/form-data">
        <div class="card-body">
            
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <div class="mb-4">
                <label for="archivo_convenio" class="form-label fw-bolder">Reemplazar Archivo (Opcional)</label>
                
                <div class="alert alert-info py-2">
                    <i class="fa fa-file-alt me-2"></i>
                    <strong>Archivo actual:</strong> <?= htmlspecialchars($archivo['NombreOriginalConSub'] ?? 'No disponible') ?>
                </div>

                <input type="file" id="archivo_convenio" name="archivo_convenio" accept=".pdf,.doc,.docx" class="form-control">
                <small class="form-text text-muted">Si no seleccionas un nuevo archivo, se conservará el actual. Máximo 5MB.</small>
            </div>

            <div class="mb-3">
                <label for="descripcion" class="form-label fw-bolder">Descripción</label>
                <input type="text" id="descripcion" name="descripcion" maxlength="255" class="form-control" value="<?= htmlspecialchars($archivo['DescripcionConSub'] ?? '') ?>">
            </div>

        </div>
        <div class="card-footer text-end">
            <a href="<?= $baseUrl ?>/index.php?c=ConveniosSubidos&m=index" class="btn btn-secondary me-2">Cancelar y Volver</a>
            <button type="submit" class="btn btn-primary">
                <i class="fa fa-save me-2"></i>Actualizar Convenio
            </button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const inputArchivo = document.querySelector('input[name="archivo_convenio"]');
    const form = document.querySelector('form');

    form.addEventListener('submit', function (e) {
        const file = inputArchivo.files[0];
        
        // La validación solo se ejecuta si el usuario selecciona un archivo nuevo
        if (file) {
            const allowed = ['pdf', 'doc', 'docx'];
            const maxSize = 5 * 1024 * 1024; // 5 MB
            let errorMsg = '';

            const ext = file.name.split('.').pop().toLowerCase();
            if (!allowed.includes(ext)) {
                errorMsg = 'Formato no válido. Solo se permiten archivos PDF, DOC y DOCX.';
            } else if (file.size > maxSize) {
                errorMsg = 'El archivo es demasiado grande. El máximo permitido es 5MB.';
            }

            if (errorMsg) {
                e.preventDefault();
                // Usamos SweetAlert2 para mostrar el error
                Swal.fire({
                    title: 'Error de Validación',
                    text: errorMsg,
                    icon: 'error',
                    confirmButtonText: 'Entendido'
                });
                inputArchivo.value = '';
            }
        }
    });
});
</script>

<?php include(__DIR__ . '/../layout/footer.php'); ?>