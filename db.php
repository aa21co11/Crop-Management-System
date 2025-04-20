<?php
// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "farm_management";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Add new crop
    if (isset($_POST['add_crop'])) {
        $crop_name = $conn->real_escape_string($_POST['crop_name']);
        $crop_type = $conn->real_escape_string($_POST['crop_type']);
        $planting_date = $conn->real_escape_string($_POST['planting_date']);
        $harvest_date = isset($_POST['harvest_date']) ? $conn->real_escape_string($_POST['harvest_date']) : null;
        $area = isset($_POST['area']) ? floatval($_POST['area']) : null;
        $status = $conn->real_escape_string($_POST['status']);
        $notes = isset($_POST['notes']) ? $conn->real_escape_string($_POST['notes']) : null;
        
        $stmt = $conn->prepare("INSERT INTO crops (crop_name, crop_type, planting_date, harvest_date, area, status, notes) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssdss", $crop_name, $crop_type, $planting_date, $harvest_date, $area, $status, $notes);
        $stmt->execute();
        $crop_id = $stmt->insert_id;
        $stmt->close();
        
        // Add planting activity
        $activity_desc = "Planted $crop_name ($crop_type) in " . ($area ? "$area acres" : "unspecified area");
        $conn->query("INSERT INTO activities (crop_id, activity_type, description, activity_date) VALUES ($crop_id, 'Irrigation', '$activity_desc', NOW())");
        
        header("Location: management.php");
        exit();
    }
    
    // Update crop
    if (isset($_POST['update_crop'])) {
        $id = intval($_POST['crop_id']);
        $crop_name = $conn->real_escape_string($_POST['crop_name']);
        $crop_type = $conn->real_escape_string($_POST['crop_type']);
        $planting_date = $conn->real_escape_string($_POST['planting_date']);
        $harvest_date = isset($_POST['harvest_date']) ? $conn->real_escape_string($_POST['harvest_date']) : null;
        $area = isset($_POST['area']) ? floatval($_POST['area']) : null;
        $status = $conn->real_escape_string($_POST['status']);
        $notes = isset($_POST['notes']) ? $conn->real_escape_string($_POST['notes']) : null;
        
        $stmt = $conn->prepare("UPDATE crops SET crop_name=?, crop_type=?, planting_date=?, harvest_date=?, area=?, status=?, notes=? WHERE id=?");
        $stmt->bind_param("ssssdssi", $crop_name, $crop_type, $planting_date, $harvest_date, $area, $status, $notes, $id);
        $stmt->execute();
        $stmt->close();
        
        header("Location: management.php");
        exit();
    }
    
    // Add pest alert
    if (isset($_POST['add_pest_alert'])) {
        $crop_id = intval($_POST['crop_id']);
        $pest_name = $conn->real_escape_string($_POST['pest_name']);
        $severity = $conn->real_escape_string($_POST['severity']);
        $detected_date = $conn->real_escape_string($_POST['detected_date']);
        $notes = isset($_POST['notes']) ? $conn->real_escape_string($_POST['notes']) : null;
        
        $conn->query("INSERT INTO pest_alerts (crop_id, pest_name, severity, detected_date, notes) VALUES ($crop_id, '$pest_name', '$severity', '$detected_date', '$notes')");
        
        // Add activity log
        $crop = $conn->query("SELECT crop_name FROM crops WHERE id = $crop_id")->fetch_assoc();
        $activity_desc = "Pest detected: $pest_name (Severity: $severity) in {$crop['crop_name']}";
        $conn->query("INSERT INTO activities (crop_id, activity_type, description, activity_date) VALUES ($crop_id, 'Pest Control', '$activity_desc', NOW())");
        
        header("Location: management.php");
        exit();
    }
    
    // Resolve pest alert
    if (isset($_POST['resolve_pest'])) {
        $pest_id = intval($_POST['pest_id']);
        $conn->query("UPDATE pest_alerts SET status='Resolved' WHERE id=$pest_id");
        
        header("Location: management.php");
        exit();
    }
}

// Handle delete actions
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM crops WHERE id=$id");
    
    header("Location: management.php");
    exit();
}

if (isset($_GET['delete_pest'])) {
    $id = intval($_GET['delete_pest']);
    $conn->query("DELETE FROM pest_alerts WHERE id=$id");
    
    header("Location: management.php");
    exit();
}

// Get all crops
$crops = $conn->query("SELECT * FROM crops ORDER BY planting_date DESC");

// Get counts for summary cards
$active_crops = $conn->query("SELECT COUNT(*) as count FROM crops WHERE status='Growing'")->fetch_assoc()['count'];
$harvests_this_month = $conn->query("SELECT COUNT(*) as count FROM crops WHERE status='Harvested' AND MONTH(harvest_date) = MONTH(CURRENT_DATE())")->fetch_assoc()['count'];
$irrigation_due = $conn->query("SELECT COUNT(*) as count FROM crops WHERE status='Growing' AND (last_irrigation IS NULL OR DATEDIFF(CURRENT_DATE(), last_irrigation) > 7)")->fetch_assoc()['count'];
$pest_alerts_count = $conn->query("SELECT COUNT(*) as count FROM pest_alerts WHERE status='Unresolved'")->fetch_assoc()['count'];

// Get recent activities
$recent_activities = $conn->query("
    SELECT a.*, c.crop_name 
    FROM activities a
    LEFT JOIN crops c ON a.crop_id = c.id
    ORDER BY activity_date DESC
    LIMIT 4
");

// Get unresolved pest alerts
$unresolved_pests = $conn->query("
    SELECT p.*, c.crop_name 
    FROM pest_alerts p
    JOIN crops c ON p.crop_id = c.id
    WHERE p.status = 'Unresolved'
    ORDER BY detected_date DESC
    LIMIT 3
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Crop Management Dashboard | AI-Powered Farming Solutions</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <!-- FontAwesome -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
  <!-- Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    :root {
      --primary: #70a1a1;
      --secondary: #f0f8f7;
      --accent: #4a7c7c;
    }
    body {
      background-color: #fdfefe;
      color: #333;
      font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
    }
    .navbar {
      background-color: var(--accent);
    }
    .navbar-brand, .nav-link {
      color: white !important;
    }
    .dashboard-header {
      background: linear-gradient(to right, var(--secondary), #ffffff);
      padding: 40px 20px;
      margin-bottom: 30px;
    }
    .dashboard-header h1 {
      color: var(--accent);
      font-size: 2.5rem;
      font-weight: bold;
    }
    .card {
      border: none;
      border-radius: 12px;
      box-shadow: 0 6px 20px rgba(0, 0, 0, 0.05);
      transition: transform 0.3s ease;
      margin-bottom: 20px;
      height: 100%;
    }
    .card:hover {
      transform: translateY(-5px);
    }
    .card-header {
      background-color: var(--primary);
      color: white;
      border-radius: 12px 12px 0 0 !important;
      font-weight: bold;
    }
    .btn-custom {
      background-color: var(--primary);
      color: white;
      border-radius: 30px;
      padding: 8px 20px;
    }
    .btn-custom:hover {
      background-color: var(--accent);
    }
    .btn-danger {
      border-radius: 30px;
    }
    .table-responsive {
      border-radius: 12px;
      overflow: hidden;
    }
    .table thead {
      background-color: var(--primary);
      color: white;
    }
    .status-badge {
      padding: 5px 10px;
      border-radius: 20px;
      font-size: 0.8rem;
      font-weight: bold;
    }
    .status-active {
      background-color: #d4edda;
      color: #155724;
    }
    .status-harvested {
      background-color: #fff3cd;
      color: #856404;
    }
    .status-planned {
      background-color: #d1ecf1;
      color: #0c5460;
    }
    .severity-low {
      background-color: #d4edda;
      color: #155724;
    }
    .severity-medium {
      background-color: #fff3cd;
      color: #856404;
    }
    .severity-high {
      background-color: #f8d7da;
      color: #721c24;
    }
    .chart-container {
      position: relative;
      height: 300px;
      width: 100%;
    }
    .action-btn {
      margin: 0 3px;
    }
    .chart-color-1 { background-color: #4a7c7c; }
    .chart-color-2 { background-color: #70a1a1; }
    .chart-color-3 { background-color: #9abfbf; }
    .chart-color-4 { background-color: #c4dddd; }
    @media (max-width: 767px) {
      .dashboard-header h1 {
        font-size: 2rem;
      }
    }
  </style>
</head>

<body>
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
      <a class="navbar-brand" href="#">Farm Management</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item">
            <a class="nav-link active" href="#">Dashboard</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Reports</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Settings</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Dashboard Header -->
  <section class="dashboard-header">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-md-8">
          <h1>Crop Management Dashboard</h1>
          <p class="lead">Monitor and manage all your crops in one place</p>
        </div>
        <div class="col-md-4 text-md-end">
          <button class="btn btn-custom me-2" data-bs-toggle="modal" data-bs-target="#addCropModal">
            <i class="fas fa-plus me-2"></i>Add Crop
          </button>
          <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#addPestModal">
            <i class="fas fa-bug me-2"></i>Report Pest
          </button>
        </div>
      </div>
    </div>
  </section>

  <!-- Dashboard Content -->
  <section class="dashboard-content mb-5">
    <div class="container">
      <div class="row">
        <!-- Summary Cards -->
        <div class="col-md-6 col-lg-3">
          <div class="card">
            <div class="card-body text-center">
              <i class="fas fa-seedling fa-3x mb-3" style="color: var(--primary);"></i>
              <h3 class="mb-2"><?= $active_crops ?></h3>
              <p class="mb-0">Active Crops</p>
            </div>
          </div>
        </div>
        <div class="col-md-6 col-lg-3">
          <div class="card">
            <div class="card-body text-center">
              <i class="fas fa-calendar-check fa-3x mb-3" style="color: var(--primary);"></i>
              <h3 class="mb-2"><?= $harvests_this_month ?></h3>
              <p class="mb-0">Harvests This Month</p>
            </div>
          </div>
        </div>
        <div class="col-md-6 col-lg-3">
          <div class="card">
            <div class="card-body text-center">
              <i class="fas fa-tint fa-3x mb-3" style="color: var(--primary);"></i>
              <h3 class="mb-2"><?= $irrigation_due ?></h3>
              <p class="mb-0">Irrigation Due</p>
            </div>
          </div>
        </div>
        <div class="col-md-6 col-lg-3">
          <div class="card">
            <div class="card-body text-center">
              <i class="fas fa-bug fa-3x mb-3" style="color: var(--primary);"></i>
              <h3 class="mb-2"><?= $pest_alerts_count ?></h3>
              <p class="mb-0">Pest Alerts</p>
            </div>
          </div>
        </div>
      </div>

      <div class="row mt-4">
        <!-- Main Content -->
        <div class="col-lg-8">
          <div class="card">
            <div class="card-header">
              <i class="fas fa-table me-2"></i> Your Crops
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th>Crop Name</th>
                      <th>Type</th>
                      <th>Planting Date</th>
                      <th>Harvest Date</th>
                      <th>Status</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php while($crop = $crops->fetch_assoc()): ?>
                    <tr>
                      <td><?= htmlspecialchars($crop['crop_name']) ?></td>
                      <td><?= htmlspecialchars($crop['crop_type']) ?></td>
                      <td><?= date('d M Y', strtotime($crop['planting_date'])) ?></td>
                      <td><?= $crop['harvest_date'] ? date('d M Y', strtotime($crop['harvest_date'])) : 'N/A' ?></td>
                      <td>
                        <?php 
                          $status_class = '';
                          if ($crop['status'] === 'Growing') $status_class = 'status-active';
                          elseif ($crop['status'] === 'Harvested') $status_class = 'status-harvested';
                          else $status_class = 'status-planned';
                        ?>
                        <span class="status-badge <?= $status_class ?>"><?= $crop['status'] ?></span>
                      </td>
                      <td>
                        <button class="btn btn-sm btn-custom action-btn view-crop" 
                                data-id="<?= $crop['id'] ?>"
                                data-name="<?= htmlspecialchars($crop['crop_name']) ?>"
                                data-type="<?= htmlspecialchars($crop['crop_type']) ?>"
                                data-planting="<?= $crop['planting_date'] ?>"
                                data-harvest="<?= $crop['harvest_date'] ?>"
                                data-area="<?= $crop['area'] ?>"
                                data-status="<?= $crop['status'] ?>"
                                data-notes="<?= htmlspecialchars($crop['notes']) ?>">
                          <i class="fas fa-eye"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-primary action-btn edit-crop" 
                                data-bs-toggle="modal" 
                                data-bs-target="#editCropModal"
                                data-id="<?= $crop['id'] ?>"
                                data-name="<?= htmlspecialchars($crop['crop_name']) ?>"
                                data-type="<?= htmlspecialchars($crop['crop_type']) ?>"
                                data-planting="<?= $crop['planting_date'] ?>"
                                data-harvest="<?= $crop['harvest_date'] ?>"
                                data-area="<?= $crop['area'] ?>"
                                data-status="<?= $crop['status'] ?>"
                                data-notes="<?= htmlspecialchars($crop['notes']) ?>">
                          <i class="fas fa-edit"></i>
                        </button>
                        <a href="management.php?delete=<?= $crop['id'] ?>" class="btn btn-sm btn-outline-danger action-btn" onclick="return confirm('Are you sure you want to delete this crop?')">
                          <i class="fas fa-trash"></i>
                        </a>
                      </td>
                    </tr>
                    <?php endwhile; ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>

          <!-- Pest Alerts Section -->
          <?php if ($unresolved_pests->num_rows > 0): ?>
          <div class="card mt-4">
            <div class="card-header bg-danger text-white">
              <i class="fas fa-exclamation-triangle me-2"></i> Active Pest Alerts
            </div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-hover">
                  <thead class="bg-danger text-white">
                    <tr>
                      <th>Pest Name</th>
                      <th>Crop Affected</th>
                      <th>Severity</th>
                      <th>Detected On</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php while($pest = $unresolved_pests->fetch_assoc()): ?>
                    <tr>
                      <td><?= htmlspecialchars($pest['pest_name']) ?></td>
                      <td><?= htmlspecialchars($pest['crop_name']) ?></td>
                      <td>
                        <span class="status-badge severity-<?= strtolower($pest['severity']) ?>">
                          <?= $pest['severity'] ?>
                        </span>
                      </td>
                      <td><?= date('d M Y', strtotime($pest['detected_date'])) ?></td>
                      <td>
                        <form method="POST" action="management.php" style="display: inline;">
                          <input type="hidden" name="pest_id" value="<?= $pest['id'] ?>">
                          <button type="submit" name="resolve_pest" class="btn btn-sm btn-success action-btn">
                            <i class="fas fa-check"></i> Resolve
                          </button>
                        </form>
                        <a href="management.php?delete_pest=<?= $pest['id'] ?>" class="btn btn-sm btn-outline-danger action-btn" onclick="return confirm('Are you sure you want to delete this pest alert?')">
                          <i class="fas fa-trash"></i>
                        </a>
                      </td>
                    </tr>
                    <?php endwhile; ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <?php endif; ?>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
          <!-- Growth Progress -->
          <div class="card">
            <div class="card-header">
              <i class="fas fa-chart-line me-2"></i> Crop Growth Progress
            </div>
            <div class="card-body">
              <div class="chart-container">
                <canvas id="growthChart"></canvas>
              </div>
            </div>
          </div>

          <!-- Recent Activities -->
          <div class="card mt-4">
            <div class="card-header">
              <i class="fas fa-bell me-2"></i> Recent Activities
            </div>
            <div class="card-body">
              <ul class="list-group list-group-flush">
                <?php while($activity = $recent_activities->fetch_assoc()): ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                  <span><?= htmlspecialchars($activity['description']) ?></span>
                  <small class="text-muted"><?= date('d M H:i', strtotime($activity['activity_date'])) ?></small>
                </li>
                <?php endwhile; ?>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Add Crop Modal -->
  <div class="modal fade" id="addCropModal" tabindex="-1" aria-labelledby="addCropModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addCropModalLabel">Add New Crop</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form method="POST" action="management.php">
          <div class="modal-body">
            <div class="mb-3">
              <label for="crop_name" class="form-label">Crop Name *</label>
              <input type="text" class="form-control" id="crop_name" name="crop_name" required>
            </div>
            <div class="mb-3">
              <label for="crop_type" class="form-label">Crop Type *</label>
              <select class="form-select" id="crop_type" name="crop_type" required>
                <option value="">Select crop type</option>
                <option value="Cereal">Cereal</option>
                <option value="Pulse">Pulse</option>
                <option value="Vegetable">Vegetable</option>
                <option value="Fruit">Fruit</option>
                <option value="Other">Other</option>
              </select>
            </div>
            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="planting_date" class="form-label">Planting Date *</label>
                <input type="date" class="form-control" id="planting_date" name="planting_date" required>
              </div>
              <div class="col-md-6 mb-3">
                <label for="harvest_date" class="form-label">Expected Harvest Date</label>
                <input type="date" class="form-control" id="harvest_date" name="harvest_date">
              </div>
            </div>
            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="area" class="form-label">Area (acres)</label>
                <input type="number" class="form-control" id="area" name="area" step="0.1">
              </div>
              <div class="col-md-6 mb-3">
                <label for="status" class="form-label">Status *</label>
                <select class="form-select" id="status" name="status" required>
                  <option value="Planned">Planned</option>
                  <option value="Growing" selected>Growing</option>
                  <option value="Harvested">Harvested</option>
                </select>
              </div>
            </div>
            <div class="mb-3">
              <label for="notes" class="form-label">Notes</label>
              <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-custom" name="add_crop">Save Crop</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Edit Crop Modal -->
  <div class="modal fade" id="editCropModal" tabindex="-1" aria-labelledby="editCropModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editCropModalLabel">Edit Crop</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form method="POST" action="management.php">
          <input type="hidden" id="edit_crop_id" name="crop_id">
          <div class="modal-body">
            <div class="mb-3">
              <label for="edit_crop_name" class="form-label">Crop Name *</label>
              <input type="text" class="form-control" id="edit_crop_name" name="crop_name" required>
            </div>
            <div class="mb-3">
              <label for="edit_crop_type" class="form-label">Crop Type *</label>
              <select class="form-select" id="edit_crop_type" name="crop_type" required>
                <option value="Cereal">Cereal</option>
                <option value="Pulse">Pulse</option>
                <option value="Vegetable">Vegetable</option>
                <option value="Fruit">Fruit</option>
                <option value="Other">Other</option>
              </select>
            </div>
            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="edit_planting_date" class="form-label">Planting Date *</label>
                <input type="date" class="form-control" id="edit_planting_date" name="planting_date" required>
              </div>
              <div class="col-md-6 mb-3">
                <label for="edit_harvest_date" class="form-label">Expected Harvest Date</label>
                <input type="date" class="form-control" id="edit_harvest_date" name="harvest_date">
              </div>
            </div>
            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="edit_area" class="form-label">Area (acres)</label>
                <input type="number" class="form-control" id="edit_area" name="area" step="0.1">
              </div>
              <div class="col-md-6 mb-3">
                <label for="edit_status" class="form-label">Status *</label>
                <select class="form-select" id="edit_status" name="status" required>
                  <option value="Planned">Planned</option>
                  <option value="Growing">Growing</option>
                  <option value="Harvested">Harvested</option>
                </select>
              </div>
            </div>
            <div class="mb-3">
              <label for="edit_notes" class="form-label">Notes</label>
              <textarea class="form-control" id="edit_notes" name="notes" rows="3"></textarea>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-custom" name="update_crop">Update Crop</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- Add Pest Alert Modal -->
  <div class="modal fade" id="addPestModal" tabindex="-1" aria-labelledby="addPestModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header bg-danger text-white">
          <h5 class="modal-title" id="addPestModalLabel">Report New Pest</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form method="POST" action="management.php">
          <div class="modal-body">
            <div class="mb-3">
              <label for="pest_crop" class="form-label">Affected Crop *</label>
              <select class="form-select" id="pest_crop" name="crop_id" required>
                <option value="">Select crop</option>
                <?php 
                $crops_list = $conn->query("SELECT id, crop_name FROM crops ORDER BY crop_name");
                while($crop = $crops_list->fetch_assoc()): ?>
                  <option value="<?= $crop['id'] ?>"><?= htmlspecialchars($crop['crop_name']) ?></option>
                <?php endwhile; ?>
              </select>
            </div>
            <div class="mb-3">
              <label for="pest_name" class="form-label">Pest Name *</label>
              <input type="text" class="form-control" id="pest_name" name="pest_name" required>
            </div>
            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="severity" class="form-label">Severity *</label>
                <select class="form-select" id="severity" name="severity" required>
                  <option value="Low">Low</option>
                  <option value="Medium" selected>Medium</option>
                  <option value="High">High</option>
                </select>
              </div>
              <div class="col-md-6 mb-3">
                <label for="detected_date" class="form-label">Detected Date *</label>
                <input type="date" class="form-control" id="detected_date" name="detected_date" required value="<?= date('Y-m-d') ?>">
              </div>
            </div>
            <div class="mb-3">
              <label for="pest_notes" class="form-label">Notes</label>
              <textarea class="form-control" id="pest_notes" name="notes" rows="3"></textarea>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-danger" name="add_pest_alert">Report Pest</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <!-- View Crop Modal -->
  <div class="modal fade" id="viewCropModal" tabindex="-1" aria-labelledby="viewCropModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="viewCropModalLabel">Crop Details</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row mb-3">
            <div class="col-md-6">
              <p><strong>Crop Name:</strong></p>
              <p id="view_crop_name"></p>
            </div>
            <div class="col-md-6">
              <p><strong>Crop Type:</strong></p>
              <p id="view_crop_type"></p>
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-md-6">
              <p><strong>Planting Date:</strong></p>
              <p id="view_planting_date"></p>
            </div>
            <div class="col-md-6">
              <p><strong>Harvest Date:</strong></p>
              <p id="view_harvest_date"></p>
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-md-6">
              <p><strong>Area:</strong></p>
              <p id="view_area"></p>
            </div>
            <div class="col-md-6">
              <p><strong>Status:</strong></p>
              <p id="view_status"></p>
            </div>
          </div>
          <div class="mb-3">
            <p><strong>Notes:</strong></p>
            <p id="view_notes" class="text-muted"></p>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-custom" data-bs-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Footer -->
  <footer class="bg-dark text-white py-4 mt-5">
    <div class="container">
      <div class="row">
        <div class="col-md-6">
          <h5>Farm Management System</h5>
          <p class="text-muted">AI-powered solutions for modern agriculture</p>
          <div class="social-icons mt-3">
            <a href="#" class="text-white me-3"><i class="fab fa-facebook-f"></i></a>
            <a href="#" class="text-white me-3"><i class="fab fa-twitter"></i></a>
            <a href="#" class="text-white me-3"><i class="fab fa-instagram"></i></a>
            <a href="#" class="text-white"><i class="fab fa-linkedin-in"></i></a>
          </div>
        </div>
        <div class="col-md-3">
          <h5>Quick Links</h5>
          <ul class="list-unstyled">
            <li><a href="#" class="text-muted">Dashboard</a></li>
            <li><a href="#" class="text-muted">Reports</a></li>
            <li><a href="#" class="text-muted">Crops</a></li>
            <li><a href="#" class="text-muted">Pest Alerts</a></li>
          </ul>
        </div>
        <div class="col-md-3">
          <h5>Contact</h5>
          <ul class="list-unstyled text-muted">
            <li><i class="fas fa-map-marker-alt me-2"></i> 123 Farm St, Agritown</li>
            <li><i class="fas fa-phone me-2"></i> (123) 456-7890</li>
            <li><i class="fas fa-envelope me-2"></i> info@farmmgmt.com</li>
          </ul>
        </div>
      </div>
      <hr class="my-4 bg-secondary">
      <div class="row">
        <div class="col-md-6">
          <p class="text-muted mb-0">&copy; 2023 Farm Management System. All rights reserved.</p>
        </div>
        <div class="col-md-6 text-md-end">
          <a href="#" class="text-muted me-3">Privacy Policy</a>
          <a href="#" class="text-muted me-3">Terms of Service</a>
          <a href="#" class="text-muted">Help Center</a>
        </div>
      </div>
    </div>
  </footer>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  
  <!-- Custom Scripts -->
  <script>
    // Initialize growth chart
    const growthCtx = document.getElementById('growthChart').getContext('2d');
    const growthChart = new Chart(growthCtx, {
      type: 'bar',
      data: {
        labels: ['Wheat', 'Corn', 'Soybeans', 'Tomatoes', 'Potatoes'],
        datasets: [{
          label: 'Growth Progress (%)',
          data: [75, 60, 45, 85, 30],
          backgroundColor: [
            '#4a7c7c',
            '#70a1a1',
            '#9abfbf',
            '#c4dddd',
            '#e8f4f4'
          ],
          borderWidth: 0
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
          y: {
            beginAtZero: true,
            max: 100,
            ticks: {
              callback: function(value) {
                return value + '%';
              }
            }
          }
        },
        plugins: {
          legend: {
            display: false
          }
        }
      }
    });

    // Edit crop modal handler
    document.querySelectorAll('.edit-crop').forEach(button => {
      button.addEventListener('click', function() {
        document.getElementById('edit_crop_id').value = this.getAttribute('data-id');
        document.getElementById('edit_crop_name').value = this.getAttribute('data-name');
        document.getElementById('edit_crop_type').value = this.getAttribute('data-type');
        document.getElementById('edit_planting_date').value = this.getAttribute('data-planting');
        document.getElementById('edit_harvest_date').value = this.getAttribute('data-harvest');
        document.getElementById('edit_area').value = this.getAttribute('data-area');
        document.getElementById('edit_status').value = this.getAttribute('data-status');
        document.getElementById('edit_notes').value = this.getAttribute('data-notes');
      });
    });

    // View crop modal handler
    document.querySelectorAll('.view-crop').forEach(button => {
      button.addEventListener('click', function() {
        document.getElementById('view_crop_name').textContent = this.getAttribute('data-name');
        document.getElementById('view_crop_type').textContent = this.getAttribute('data-type');
        document.getElementById('view_planting_date').textContent = formatDate(this.getAttribute('data-planting'));
        
        const harvestDate = this.getAttribute('data-harvest');
        document.getElementById('view_harvest_date').textContent = harvestDate ? formatDate(harvestDate) : 'Not specified';
        
        const area = this.getAttribute('data-area');
        document.getElementById('view_area').textContent = area ? area + ' acres' : 'Not specified';
        
        document.getElementById('view_status').textContent = this.getAttribute('data-status');
        document.getElementById('view_notes').textContent = this.getAttribute('data-notes') || 'No notes available';
        
        // Show the modal
        const viewModal = new bootstrap.Modal(document.getElementById('viewCropModal'));
        viewModal.show();
      });
    });

    // Format date for display
    function formatDate(dateString) {
      if (!dateString) return '';
      const date = new Date(dateString);
      return date.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
    }

    // Set today's date as default for planting date in add crop form
    document.addEventListener('DOMContentLoaded', function() {
      const today = new Date().toISOString().split('T')[0];
      document.getElementById('planting_date').value = today;
    });
  </script>
</body>
</html>
<?php $conn->close(); ?>