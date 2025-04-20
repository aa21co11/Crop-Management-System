<?php
// Include the database connection file
include('db.php');

// Initialize variables for error and success messages
$error = "";
$success = "";

// Check if the form is submitted
if (isset($_POST['register'])) {
    // Retrieve user input
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Validate input
    if (empty($username) || empty($password)) {
        $error = "Please fill in all fields.";
    } else {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Check if the username already exists
        $query = "SELECT * FROM users WHERE username='$username'";
        $result = mysqli_query($conn, $query);

        if (mysqli_num_rows($result) > 0) {
            $error = "Username already taken!";
        } else {
            // Insert the new user into the database
            $query = "INSERT INTO users (username, password) VALUES ('$username', '$hashed_password')";
            if (mysqli_query($conn, $query)) {
                // Redirect to the login page after successful registration
                header("Location: login.php");
                exit;
            } else {
                $error = "Error: " . mysqli_error($conn);
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - AgriAid</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="auth-container">
        <div class="auth-box">
            <h2>Register</h2>
            <!-- Display error or success messages -->
            <?php if (!empty($error)) { echo "<p class='error'>$error</p>"; } ?>
            <?php if (!empty($success)) { echo "<p class='success'>$success</p>"; } ?>

            <!-- Registration form -->
            <form method="POST" action="">
                <label for="username">Username:</label>
                <input type="text" name="username" id="username" required>
                
                <label for="password">Password:</label>
                <input type="password" name="password" id="password" required>
                
                <button type="submit" name="register">Register</button>
            </form>
            <p>Already have an account? <a href="login.php">Login here</a></p>
        </div>
    </div>
</body>
</html>
