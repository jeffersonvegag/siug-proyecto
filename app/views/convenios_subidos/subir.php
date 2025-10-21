<?php include(__DIR__ . '/../layout/header.php'); ?>

<div class="card">
    <div class="card-header bg-dark">
        <h3 class="card-title text-white">Subir Nuevo Convenio</h3>
    </div>
    <form method="post" enctype="multipart/form-data">
        <div class="card-body">
            
            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <div class="mb-4">
                <label for="archivo_convenio" class="form-label fw-bolder">Archivo del Convenio</label>
                <input type="file" id="archivo_convenio" name="archivo_convenio" required accept=".pdf,.doc,.docx" class="form-control">
                <small class="form-text text-muted">Formatos permitidos: PDF, DOC, DOCX. Tamaño máximo: 5MB.</small>
            </div>

            <div class="mb-3">
                <label for="descripcion" class="form-label fw-bolder">Descripción</label>
                <input type="text" id="descripcion" name="descripcion" maxlength="255" class="form-control" placeholder="Ej: Convenio marco de cooperación interinstitucional...">
            </div>

        </div>
        <div class="card-footer text-end">
            <a href="<?= $baseUrl ?>/index.php?c=ConveniosSubidos&m=index" class="btn btn-secondary me-2">Volver al Listado</a>
            <button type="submit" class="btn btn-primary">
                <i class="fa fa-upload me-2"></i>Subir Convenio
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
        const allowed = ['pdf', 'doc', 'docx'];
        const maxSize = 5 * 1024 * 1024; // 5 MB
        let errorMsg = '';

        if (!file) {
            errorMsg = 'Debe seleccionar un archivo para subir.';
        } else {
            const ext = file.name.split('.').pop().toLowerCase();
            if (!allowed.includes(ext)) {
                errorMsg = 'Formato de archivo no válido. Solo se permiten PDF, DOC y DOCX.';
            } else if (file.size > maxSize) {
                errorMsg = 'El archivo es demasiado grande. El tamaño máximo permitido es de 5MB.';
            }
        }

        // Mostrar error con SweetAlert2 y cancelar envío
        if (errorMsg) {
            e.preventDefault();
            Swal.fire({
                title: 'Error de Validación',
                text: errorMsg,
                icon: 'error',
                confirmButtonText: 'Entendido'
            });
            inputArchivo.value = ''; // Limpiar el input de archivo
        }
    });
});
</script>

<?php include(__DIR__ . '/../layout/footer.php'); ?>