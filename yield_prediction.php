<!-- yield prediction  -->

<!DOCTYPE html>
<html>
<?php include('header.php'); ?>

<body class="bg-white" id="top">
  
<?php include('nav.php'); ?>

<section class="py-5">
    <div class="container">
        <!-- Prediction Header -->
        <div class="row mb-4">
            <div class="col-md-8 mx-auto text-center">
                <span class="badge badge-pill mb-3" style="background-color: var(--accent); color: white;">Prediction</span>
                <h2 class="section-title">Yield Prediction</h2>
                <p class="lead">Get accurate yield predictions based on your farming parameters</p>
            </div>
        </div>
        
        <!-- Prediction Form Card -->
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-seedling text-success mr-3" style="font-size: 2rem;"></i>
                            <h4 class="mb-0 text-success">Enter Your Farming Details</h4>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <form role="form" action="#" method="post">
                            <div class="table-responsive">
                                <table class="table table-borderless">
                                    <thead>
                                        <tr class="text-center">
                                            <th><i class="fas fa-map-marked-alt text-primary"></i> State</th>
                                            <th><i class="fas fa-map-marker-alt text-primary"></i> District</th>
                                            <th><i class="fas fa-calendar-alt text-primary"></i> Season</th>
                                            <th><i class="fas fa-leaf text-primary"></i> Crop</th>
                                            <th><i class="fas fa-vector-square text-primary"></i> Area</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="text-center">
                                            <td>
                                                <div class="form-group">
                                                    <select name="state" class="form-control" required>
                                                        <option value="Karnataka">Karnataka</option>
                                                    </select>
                                                </div>
                                            </td>

                                            <td>
                                                <div class="form-group">
                                                    <select id="district" name="district" class="form-control" required>
                                                        <option value="">Select district</option>
                                                        <option value="BAGALKOT">Bagalkot</option>
                                                        <option value="BANGALORE_RURAL">Bangalore Rural</option>
                                                        <!-- Other district options -->
                                                    </select>
                                                </div>
                                            </td>
                                            
                                            <td>
                                                <div class="form-group">
                                                    <select name="Season" class="form-control" id="season" required>
                                                        <option value="">Select Season</option>
                                                        <option name="Kharif" value="Kharif">Kharif</option>
                                                        <option name="Rabi" value="Rabi">Rabi</option>
                                                        <option name="Summer" value="Summer">Summer</option>
                                                        <option name="WholeYear" value="WholeYear">Whole Year</option>
                                                    </select>
                                                </div>
                                            </td>
                                            
                                            <td>
                                                <div class="form-group">
                                                    <select id="crop" class="form-control" name="crops" required>
                                                        <option value="">Select crop</option>
                                                    </select>
                                                </div>
                                            </td>
                                            
                                            <script>
                                            document.getElementById("season").addEventListener("change", function() {  
                                                const districtDropdown = document.getElementById('district');
                                                const seasonDropdown = document.getElementById('season');
                                                const cropDropdown = document.getElementById('crop');
                                                
                                                const selectedDistrict = districtDropdown.value;
                                                const selectedSeason = seasonDropdown.value;

                                                cropDropdown.innerHTML = '<option value="">Select crop</option>';
                                                
                                                if (selectedDistrict && selectedSeason) {
                                                    const options = cropOptions[selectedDistrict][selectedSeason];
                                                    for (const option of options) {
                                                        const optionElement = document.createElement('option');
                                                        optionElement.value = option;
                                                        optionElement.text = option;
                                                        cropDropdown.appendChild(optionElement);
                                                    }
                                                }
                                            }); 
                                            </script>
                                            
                                            <td>
                                                <div class="form-group">
                                                    <input type="number" step="0.01" name="area" placeholder="Area (Hectares)" required class="form-control">
                                                </div>
                                            </td>
                                            
                                            <td>
                                                <div class="form-group">
                                                    <button type="submit" value="Yield" name="Yield_Predict" class="btn btn-custom btn-block">
                                                        <i class="fas fa-calculator mr-2"></i> Predict
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- Results Card -->
                <?php if(isset($_POST['Yield_Predict'])): ?>
                <div class="card border-0 shadow-sm mt-4">
                    <div class="card-header bg-white">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-chart-bar text-success mr-3" style="font-size: 2rem;"></i>
                            <h4 class="mb-0 text-success">Prediction Results</h4>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="p-4 text-center" style="background-color: var(--secondary); border-radius: 8px;">
                            <h3 class="text-dark mb-3">
                                <?php
                                $state = trim($_POST['state']);
                                $district = trim($_POST['district']);
                                $season = trim($_POST['Season']);
                                $crops = trim($_POST['crops']);
                                $area = trim($_POST['area']);

                                echo "Predicted yield for ".htmlspecialchars($crops).": ";
                                ?>
                            </h3>
                            <div class="display-4 font-weight-bold text-success my-4">
                                <?php
                                $Jstate = json_encode($state);
                                $Jdistrict = json_encode($district);
                                $Jseason = json_encode($season);
                                $Jcrops = json_encode($crops);
                                $Jarea = json_encode($area);

                                $command = escapeshellcmd("python ML/yield_prediction/yield_prediction.py $Jstate $Jdistrict $Jseason $Jcrops $Jarea");
                                $output = passthru($command);
                                echo htmlspecialchars($output)." Quintals";
                                ?>
                            </div>
                            <hr class="my-4">
                            <p class="text-muted">
                                <i class="fas fa-info-circle mr-2"></i>
                                Based on <?php echo htmlspecialchars($area); ?> hectares in <?php echo htmlspecialchars($district); ?> (<?php echo htmlspecialchars($season); ?> season)
                            </p>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php require("footer.php"); ?>

</body>
</html>