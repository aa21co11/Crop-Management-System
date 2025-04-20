<!DOCTYPE html>
<html>
<?php include('header.php'); ?>

<style>
  :root {
    --primary: #4a7c7c;
    --primary-light: #70a1a1;
    --secondary: #f8f9fa;
    --accent: #3a5f5f;
    --text: #333333;
    --text-light: #6c757d;
    --border: #e0e0e0;
  }

  body {
    background-color: #ffffff;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    color: var(--text);
    line-height: 1.6;
  }

  .container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 2rem;
  }

  .prediction-header {
    text-align: center;
    margin-bottom: 3rem;
  }

  .prediction-badge {
    display: inline-block;
    background-color: var(--primary);
    color: white;
    padding: 0.5rem 1.5rem;
    border-radius: 20px;
    font-size: 0.9rem;
    font-weight: 500;
    letter-spacing: 0.5px;
    margin-bottom: 1rem;
    text-transform: uppercase;
  }

  .prediction-title {
    font-size: 2.2rem;
    font-weight: 600;
    color: var(--primary);
    margin-bottom: 1rem;
  }

  .prediction-subtitle {
    color: var(--text-light);
    font-size: 1.1rem;
    max-width: 700px;
    margin: 0 auto;
  }

  .prediction-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    padding: 2.5rem;
    margin-bottom: 3rem;
    border: 1px solid var(--border);
  }

  .form-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    margin-top: 1.5rem;
  }

  .form-group {
    margin-bottom: 0;
  }

  .form-label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 500;
    color: var(--text);
    font-size: 0.95rem;
  }

  .form-control {
    width: 100%;
    padding: 0.8rem 1rem;
    border: 1px solid var(--border);
    border-radius: 8px;
    font-size: 1rem;
    transition: all 0.3s ease;
    background-color: white;
  }

  .form-control:focus {
    border-color: var(--primary-light);
    box-shadow: 0 0 0 3px rgba(74, 124, 124, 0.1);
    outline: none;
  }

  .submit-btn {
    display: flex;
    align-items: flex-end;
  }

  .btn-predict {
    background-color: var(--primary);
    color: white;
    border: none;
    padding: 0.9rem 2rem;
    border-radius: 8px;
    font-size: 1rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
    width: 100%;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }

  .btn-predict:hover {
    background-color: var(--accent);
    transform: translateY(-2px);
  }

  .btn-predict:active {
    transform: translateY(0);
  }

  .results-container {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    padding: 2.5rem;
    border: 1px solid var(--border);
    animation: fadeIn 0.5s ease-out;
  }

  .results-title {
    font-size: 1.5rem;
    font-weight: 600;
    color: var(--primary);
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
  }

  .results-title i {
    margin-right: 0.8rem;
    font-size: 1.8rem;
  }

  .results-content {
    background-color: var(--secondary);
    padding: 1.5rem;
    border-radius: 8px;
    margin-top: 1rem;
  }

  .results-text {
    color: var(--text);
    font-size: 1.1rem;
    line-height: 1.7;
  }

  .results-data {
    background-color: white;
    padding: 1.5rem;
    border-radius: 8px;
    margin-top: 1.5rem;
    font-family: 'Courier New', monospace;
    font-size: 1.1rem;
    color: var(--accent);
    border-left: 4px solid var(--primary);
  }

  @keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
  }

  @media (max-width: 768px) {
    .form-grid {
      grid-template-columns: 1fr;
    }
    
    .prediction-title {
      font-size: 1.8rem;
    }
    
    .prediction-card, .results-container {
      padding: 1.5rem;
    }
  }
</style>

<body>
<?php include('nav.php'); ?>

<div class="container">
  <div class="prediction-header">
    <div class="prediction-badge">Crop Prediction</div>
    <h1 class="prediction-title">AI-Powered Crop Prediction</h1>
    <p class="prediction-subtitle">Select your location and season to get personalized crop predictions based on agricultural data analysis.</p>
  </div>

  <div class="prediction-card">
    <form role="form" action="#" method="post">
      <div class="form-grid">
        <div class="form-group">
          <label for="sts" class="form-label">State</label>
          <select onchange="print_city('state', this.selectedIndex);" id="sts" name="stt" class="form-control" required>
            <option value="">Select State</option>
          </select>
          <script language="javascript">print_state("sts");</script>
        </div>

        <div class="form-group">
          <label for="state" class="form-label">District</label>
          <select id="state" name="district" class="form-control" required>
            <option value="">Select District</option>
          </select>
        </div>

        <div class="form-group">
          <label for="season" class="form-label">Season</label>
          <select name="Season" id="season" class="form-control" required>
            <option value="">Select Season</option>
            <option value="Kharif">Kharif</option>
            <option value="Whole Year">Whole Year</option>
            <option value="Autumn">Autumn</option>
            <option value="Rabi">Rabi</option>
            <option value="Summer">Summer</option>
            <option value="Winter">Winter</option>
          </select>
        </div>

        <div class="form-group submit-btn">
          <button type="submit" name="Crop_Predict" class="btn-predict">
            Get Predictions
          </button>
        </div>
      </div>
    </form>
  </div>

  <?php if (isset($_POST['Crop_Predict'])): ?>
  <div class="results-container">
    <h2 class="results-title"><i class="fas fa-seedling"></i> Recommended Crops</h2>
    
    <div class="results-content">
      <p class="results-text">
        Based on our analysis, here are the optimal crops for 
        <strong><?php echo htmlspecialchars(trim($_POST['district'])); ?></strong> 
        during the <strong><?php echo htmlspecialchars(trim($_POST['Season'])); ?></strong> season:
      </p>
      
      <div class="results-data">
        <?php
          $state = trim($_POST['stt']);
          $district = trim($_POST['district']);
          $season = trim($_POST['Season']);
          
          $JsonState = json_encode($state);
          $JsonDistrict = json_encode($district);
          $JsonSeason = json_encode($season);

          $command = escapeshellcmd("python ML/crop_prediction/ZDecision_Tree_Model_Call.py $JsonState $JsonDistrict $JsonSeason");
          $output = shell_exec($command);
          echo nl2br(htmlspecialchars($output));
        ?>
      </div>
    </div>
  </div>
  <?php endif; ?>
</div>

<?php require("footer.php"); ?>
<script src="assets/js/cities.js"></script>
</body>
</html>