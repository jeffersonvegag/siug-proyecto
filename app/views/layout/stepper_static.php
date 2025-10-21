<?php
$pasos = [
  'Datos Generales del proyecto',
  'Objetivos generales del proyecto',
  'Contexto general del proyecto',
  'Datos de la unidades académicas e institucionales',
  'Equipo del proyecto',
  'Descripción detallada del proyecto',
  'Metodología del proyecto',
  'Impacto del proyecto',
  'Evaluación del proyecto',
  'Sostenibilidad del proyecto',
  'Cronograma de actividades'
];
?>

<div id="kt_stepper" class="stepper stepper-pills stepper-row d-flex overflow-auto bg-body rounded p-4 ">
  <div class="stepper-nav d-flex flex-row flex-nowrap align-items-center gap-4 px-4 py-2">

    <?php foreach ($pasos as $index => $titulo):
      $pasoNum = $index + 1;
      $estado = '';
      $icon = $pasoNum;
      $iconClass = 'bg-light text-muted border border-gray-300 shadow-sm';

      if ($pasoNum == $pasoActual) {
        $estado = 'current';
        $iconClass = 'bg-primary text-white border border-primary shadow';
      } elseif ($pasoNum < $pasoActual) {
        $estado = 'completed';
        $icon = '<i class="bi bi-check-lg text-success fs-5"></i>';
        $iconClass = 'bg-white text-success border border-success shadow-sm';
      } else {
        $estado = 'pending';
        $iconClass = 'bg-light text-muted border border-gray-200 shadow-sm';
      }
    ?>
      <div
        class="stepper-item <?= $estado ?> d-flex align-items-center position-relative"
        data-kt-stepper-element="nav"
        style="min-width: 220px;">
        <div class="stepper-wrapper d-flex flex-column align-items-center">
          <div class="stepper-icon rounded-3 <?= $iconClass ?> d-flex justify-content-center align-items-center"
            style="width: 44px; height: 44px; font-size: 16px; transition: all 0.3s ease-in-out;">
            <?= $icon ?>
          </div>
          <div class="stepper-label text-center text-gray-700 fw-semibold mt-2 fs-8"
            style="min-height: 3.5rem; max-width: 180px; white-space: normal;">
            <?= htmlspecialchars($titulo) ?>
          </div>
        </div>

        <?php if ($pasoNum < count($pasos)): ?>
          <div class="stepper-line flex-grow-1 mx-2 border-top <?= ($pasoNum < $pasoActual) ? 'border-primary' : 'border-gray-300' ?>" style="width: 20px;"></div>
        <?php endif; ?>
      </div>
    <?php endforeach; ?>

  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    const stepperEl = document.querySelector("#kt_stepper");
    if (stepperEl) {
      new KTStepper(stepperEl);
    }
  });
</script>