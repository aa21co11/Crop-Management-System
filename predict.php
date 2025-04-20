<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Validate inputs
if (!isset($_POST['region']) || !isset($_POST['month'])) {
    header("Location: index.php?error=Missing parameters");
    exit;
}

$region = $_POST['region'];
$month = $_POST['month'];

// Validate region
$validRegions = ['North', 'South', 'East', 'West', 'Central'];
if (!in_array($region, $validRegions)) {
    header("Location: index.php?error=Invalid region");
    exit;
}

// Validate month
if (!is_numeric($month) || $month < 1 || $month > 12) {
    header("Location: index.php?error=Invalid month");
    exit;
}

// Call Python script (using absolute path)
$pythonScript = __DIR__ . '/predict_rainfall.py';
$command = "python " . escapeshellarg($pythonScript) . " " . escapeshellarg($region) . " " . escapeshellarg($month);
$output = shell_exec($command);

// Handle prediction result
if ($output === null) {
    header("Location: index.php?error=Prediction failed");
    exit;
}

$rainfall = trim($output);
if (!is_numeric($rainfall)) {
    header("Location: index.php?error=Invalid prediction: " . urlencode($output));
    exit;
}

// Success - redirect with results
header("Location: index.php?result=success&region=" . urlencode($region) . 
       "&month=" . urlencode($month) . "&rainfall=" . urlencode($rainfall));
exit;
?>