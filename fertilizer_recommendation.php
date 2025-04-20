<!DOCTYPE html>
<html lang="en">

<?php include('header.php'); ?>

<body class="bg-light" style="font-family: 'Inter', sans-serif;">
<?php include('nav.php'); ?>

<section class="py-5">
  <div class="container">
    <div class="row mb-4">
      <div class="col-md-8 mx-auto text-center">
        <span class="badge text-white mb-2" style="background-color: #4a7c7c;">📌 Fertilizer Recommendation</span>
        <h2 class="fw-bold text-dark">Smart Fertilizer Input</h2>
        <p class="text-muted">Enter your soil and crop details to get the recommended fertilizer instantly.</p>
      </div>
    </div>

    <form action="#" method="post" class="card shadow-sm p-4 bg-white border rounded-4" style="border-color: #4a7c7c;">
      <div class="row g-4">
        <div class="col-md-4">
          <label class="form-label">🌱 Nitrogen</label>
          <input type="number" name="n" placeholder="Eg: 37" required class="form-control" style="border-color: #4a7c7c;">
        </div>
        <div class="col-md-4">
          <label class="form-label">📦 Phosphorus</label>
          <input type="number" name="p" placeholder="Eg: 0" required class="form-control" style="border-color: #4a7c7c;">
        </div>
        <div class="col-md-4">
          <label class="form-label">🧪 Potassium</label>
          <input type="number" name="k" placeholder="Eg: 0" required class="form-control" style="border-color: #4a7c7c;">
        </div>

        <div class="col-md-4">
          <label class="form-label">🌡 Temperature (°C)</label>
          <input type="number" name="t" placeholder="Eg: 26" required class="form-control" style="border-color: #4a7c7c;">
        </div>
        <div class="col-md-4">
          <label class="form-label">💧 Humidity (%)</label>
          <input type="number" name="h" placeholder="Eg: 52" required class="form-control" style="border-color: #4a7c7c;">
        </div>
        <div class="col-md-4">
          <label class="form-label">🌊 Soil Moisture (%)</label>
          <input type="number" name="soilMoisture" placeholder="Eg: 38" required class="form-control" style="border-color: #4a7c7c;">
        </div>

        <div class="col-md-6">
          <label class="form-label">🧱 Soil Type</label>
          <select name="soil" class="form-select" required style="border-color: #4a7c7c;">
            <option value="">Select Soil Type</option>
            <option value="Sandy">Sandy</option>
            <option value="Loamy">Loamy</option>
            <option value="Black">Black</option>
            <option value="Red">Red</option>
            <option value="Clayey">Clayey</option>
          </select>
        </div>

        <div class="col-md-6">
          <label class="form-label">🌾 Crop</label>
          <select name="crop" class="form-select" required style="border-color: #4a7c7c;">
            <option value="">Select Crop</option>
            <option value="Maize">Maize</option>
            <option value="Sugarcane">Sugarcane</option>
            <option value="Cotton">Cotton</option>
            <option value="Tobacco">Tobacco</option>
            <option value="Paddy">Paddy</option>
            <option value="Barley">Barley</option>
            <option value="Wheat">Wheat</option>
            <option value="Millets">Millets</option>
            <option value="Oil seeds">Oil seeds</option>
            <option value="Pulses">Pulses</option>
            <option value="Ground Nuts">Ground Nuts</option>
          </select>
        </div>

        <div class="col-12 text-center">
          <button type="submit" name="Fert_Recommend" class="btn px-4 py-2 rounded-pill shadow-sm" style="background-color: #4a7c7c; border-color: #4a7c7c; color: white;">
            🚀 Recommend Fertilizer
          </button>
        </div>
      </div>
    </form>

    <?php if (isset($_POST['Fert_Recommend'])):
      $n = trim($_POST['n']);
      $p = trim($_POST['p']);
      $k = trim($_POST['k']);
      $t = trim($_POST['t']);
      $h = trim($_POST['h']);
      $sm = trim($_POST['soilMoisture']);
      $soil = trim($_POST['soil']);
      $crop = trim($_POST['crop']);

      $Jsonn = json_encode($n);
      $Jsonp = json_encode($p);
      $Jsonk = json_encode($k);
      $Jsont = json_encode($t);
      $Jsonh = json_encode($h);
      $Jsonsm = json_encode($sm);
      $Jsonsoil = json_encode($soil);
      $Jsoncrop = json_encode($crop);

      $command = escapeshellcmd("python ML/fertilizer_recommendation/fertilizer_recommendation.py $Jsonn $Jsonp $Jsonk $Jsont $Jsonh $Jsonsm $Jsonsoil $Jsoncrop ");
      ob_start();
      passthru($command);
      $output = ob_get_clean();
    ?>

    <div class="alert mt-4 shadow-sm border rounded-4" style="background-color: #f1f8ff; border-color: #4a7c7c;">
      <h5 class="mb-1">✅ Recommended Fertilizer:</h5>
      <p class="mb-0 text-dark fw-semibold"><?php echo $output; ?></p>
    </div>
    <?php endif; ?>

  </div>
</section>

<?php require("footer.php"); ?>
</body>
</html>