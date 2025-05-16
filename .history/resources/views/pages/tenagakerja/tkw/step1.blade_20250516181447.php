<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Stepper Example</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

  <style>
    .stepper {
      display: flex;
      justify-content: space-between;
      position: relative;
      margin-bottom: 2rem;
    }
    .stepper::before {
      content: '';
      position: absolute;
      top: 50%;
      left: 8%;
      right: 8%;
      height: 2px;
      background-color: #dee2e6;
      z-index: 0;
    }
    .step {
      position: relative;
      z-index: 1;
      width: 40px;
      height: 40px;
      background-color: #fff;
      border: 2px solid #6c757d;
      border-radius: 50%;
      text-align: center;
      line-height: 36px;
      font-weight: bold;
      color: #6c757d;
      cursor: pointer;
      transition: all 0.3s;
    }
    .step.active {
      background-color: #007bff;
      color: #fff;
      border-color: #007bff;
    }
    .step-label {
      text-align: center;
      margin-top: 8px;
      font-size: 14px;
    }
  </style>
</head>
<body class="p-4">

  <div class="container">
    <div class="stepper mb-3">
      <div class="text-center">
        <div class="step active" data-step="1">1</div>
        <div class="step-label">Informasi</div>
      </div>
      <div class="text-center">
        <div class="step" data-step="2">2</div>
        <div class="step-label">Step 2</div>
      </div>
      <div class="text-center">
        <div class="step" data-step="3">3</div>
        <div class="step-label">Step 3</div>
      </div>
    </div>

    <div id="step-content">
      <div class="step-pane" id="step-1">
        <h4>Formulir Informasi</h4>
        <p>Isi data provinsi, kabupaten, dst...</p>
      </div>
      <div class="step-pane d-none" id="step-2">
        <h4>Formulir Step 2</h4>
        <p>Isi data lanjutan...</p>
      </div>
      <div class="step-pane d-none" id="step-3">
        <h4>Formulir Step 3</h4>
        <p>Konfirmasi dan kirim.</p>
      </div>
    </div>
  </div>

  <script>
    const steps = document.querySelectorAll('.step');
    const panes = document.querySelectorAll('.step-pane');

    steps.forEach(step => {
      step.addEventListener('click', () => {
        const stepNumber = step.dataset.step;

        steps.forEach(s => s.classList.remove('active'));
        step.classList.add('active');

        panes.forEach(p => p.classList.add('d-none'));
        document.getElementById('step-' + stepNumber).classList.remove('d-none');
      });
    });
  </script>

</body>
</html>
