<!DOCTYPE html>
<html>
<?php include('header.php'); ?>

<head>
  <title>Rainfall Prediction</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  
  <style>
    body { 
      font-family: 'Inter', sans-serif; 
      background-color: #f8f9fa;
      padding-top: 20px;
      display: flex;
      flex-direction: column;
      min-height: 100vh;
    }
    .main-content {
      flex: 1;
    }
    .card-glass {
      border-radius: 12px;
      background: white;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
      border: none;
    }
    .form-icon select { 
      padding-left: 2.5rem;
      border-radius: 8px;
      border: 1px solid #e0e0e0;
      height: 45px;
    }
    .form-icon { 
      position: relative;
    }
    .form-icon i {
      position: absolute;
      top: 50%;
      left: 12px;
      transform: translateY(-50%);
      color: #0d6efd;
    }
    .result-card { 
      border-left: 4px solid #0d6efd;
    }
    .btn-predict {
      background-color:rgb(78, 140, 151);
      border: none;
      border-radius: 8px;
      padding: 8px 10px;
      font-weight: 600;
      height: 45px;
      font-size: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 6px;
    }
    .section-title {
      color: #212529;
      font-weight: 700;
    }
    footer {
      padding: 1.5rem 0;
      background-color: #f8f9fa;
      border-top: 1px solid #dee2e6;
      margin-top: 2rem;
    }
    .footer-content {
      max-width: 1200px;
      margin: 0 auto;
      padding: 0 15px;
    }
  </style>
</head>

<body>
  <?php include('nav.php'); ?>

  <div class="main-content">
    <div class="container py-4">
      <div class="text-center mb-5">
        <h2 class="section-title">🌧️ Rainfall Prediction</h2>
      </div>

      <div class="row justify-content-center">
        <div class="col-lg-8">
          <div class="card card-glass p-4 mb-4">
            <form method="post" action="">
              <div class="row g-3 align-items-center">
                <div class="col-md-5">
                  <div class="form-icon">
                    <i class="bi bi-geo-alt"></i>
                    <select id="region-select" name="region" class="form-control" required>
                      <option value="">Select Region...</option>
                    </select>
                    <script language="javascript">print_region("region-select");</script>
                  </div>
                </div>

                <div class="col-md-5">
                  <div class="form-icon">
                    <i class="bi bi-calendar-month"></i>
                    <select id="month-select" name="month" class="form-control" required>
                      <option value="">Select Month...</option>
                    </select>
                    <script language="javascript">print_months("month-select");</script>
                  </div>
                </div>

                <div class="col-md-2">
                  <button type="submit" name="predict" class="btn btn-predict w-100">
                    <i class="bi bi-cloud-rain-heavy"></i> <span>Predict</span>
                  </button>
                </div>
              </div>
            </form>
          </div>

          <?php 
          if(isset($_POST['predict'])) {
            $region = $_POST['region'];
            $month = $_POST['month'];
            
            if(empty($region) || empty($month)) {
              echo '<div class="alert alert-danger">Please select both region and month</div>';
            } else {
              $command = "python ML/rainfall_prediction/rainfall_prediction.py " . escapeshellarg($region) . " " . escapeshellarg($month);
              $output = shell_exec($command);
              
              if(is_numeric($output)) {
          ?>
                <div class="card card-glass p-4 result-card">
                  <div class="card-body">
                    <h4 class="mb-3"><i class="bi bi-cloud-rain text-primary"></i> Prediction Result</h4>
                    <div class="alert alert-success">
                      Predicted rainfall for <strong><?= htmlspecialchars($region) ?></strong> 
                      in <strong><?= htmlspecialchars($month) ?></strong>: 
                      <strong><?= htmlspecialchars($output) ?> mm</strong>
                    </div>
                    <canvas id="rainfallChart" height="120"></canvas>
                  </div>
                </div>

                <script>
                  document.addEventListener('DOMContentLoaded', function() {
                    const ctx = document.getElementById('rainfallChart').getContext('2d');
                    new Chart(ctx, {
                      type: 'bar',
                      data: {
                        labels: ['<?= $month ?>'],
                        datasets: [{
                          label: 'Rainfall',
                          data: [<?= $output ?>],
                          backgroundColor: '#0d6efd',
                          borderColor: '#0a58ca',
                          borderWidth: 1
                        }]
                      },
                      options: {
                        responsive: true,
                        scales: { 
                          y: { 
                            beginAtZero: true,
                            title: { display: true, text: 'Rainfall (mm)' }
                          },
                          x: {
                            title: { display: true, text: 'Month' }
                          }
                        },
                        plugins: {
                          legend: { display: false },
                          tooltip: {
                            callbacks: {
                              label: function(context) {
                                return context.parsed.y + ' mm';
                              }
                            }
                          }
                        }
                      }
                    });
                  });
                </script>
          <?php
              } else {
                echo '<div class="alert alert-danger">Error: Could not get valid prediction result</div>';
              }
            }
          }
          ?>
        </div>
      </div>
    </div>
  </div>

  <footer>
    <div class="footer-content text-center">
      <p class="mb-0">© <?= date('Y') ?> Rainfall Prediction System. All rights reserved.</p>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>