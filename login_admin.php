<?php
session_start();

// Connection to the MySQL database
$host = 'localhost';
$username = 'root';
$password = '';      
$database = 'insurance_system';

$conn = new mysqli($host, $username, $password, $database);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $enteredName = $_POST['username'];
    $enteredPassword = $_POST['password'];

    $sql = "SELECT * FROM admins WHERE username = '$enteredName' AND password = '$enteredPassword'";
    $result = $conn->query($sql);

    if ($result->num_rows === 1) {
       // Store the admin username if succesful login
        $_SESSION['username'] = $enteredName; 
        header('Location: admin_1.php');
        exit();
    } else {
        // Redirect with error graphs
        header('Location: login_admin.php?error=invalid'); 
        exit();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="styles_log1.css">
</head>
<body>
<div class="login-container">
    <form class="login-form" action="" method="POST">
        <h2>Login Admins</h2>
        <?php if (isset($_GET['error']) && $_GET['error'] === 'invalid') { ?>
            <p style="color: red;" class="error-message"> Please try again.</p>
        <?php } ?>
        <label for="tel">Usename</label>
        <input type="tel" id="username" name="username" placeholder="Enter your username" required>
        <label for="id">Password</label>
        <input type="password" id="username" name="password" placeholder="Enter your password" required>
        <br>
        <button type="submit" class="btn">Login</button>
        <div class="return-button-container">
        <a href="login_cust.php" class="return-link">User Login Here</a>
        <a href="MainPage.php" class="return-link">Return to Main Page</a>
    </div>
    </form>
</div>
</body>
</html>
