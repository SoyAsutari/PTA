<?php
// Connection to the MySQL database
$host = 'localhost';
$username = 'root';
$password = '';      
$database = 'insurance_system';

$conn = new mysqli($host, $username, $password, $database);


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM admins WHERE username = '$username' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result->num_rows === 1) {
        // Successful login
        header('Location: dashboard.php');
        exit();
    } else {
        header('Location: login_admin.php?error=invalid'); // Redirect with error parameter
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
<link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="login-container">
  <form class="login-form" action="authenticate.php" method="POST">
    <h2>Login</h2>
    <?php if (isset($errorMessage)) { ?>
        <p class="error-message"><?php echo $errorMessage; ?></p>
    <?php } ?>
    <label for="username">Username</label>
    <input type="text" id="username" name="username" placeholder="Enter your username" required>
    <label for="password">ID</label>
    <input type="password" id="password" name="password" placeholder="Enter your password" required>
    <button type="submit" class="btn">Login</button>
  </form>
</div>
</body>
</html>
