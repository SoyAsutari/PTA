<?php
// Connection to the MySQL database
$host = 'localhost';
$username = 'root';
$password = '';      
$database = 'insurance_system';

$conn = new mysqli($host, $username, $password, $database);


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $enteredPhone = $_POST['tel'];
    $enteredID = $_POST['id'];

    $sql = "SELECT * FROM users WHERE tel = '$enteredPhone' AND id = '$enteredID'";
    $result = $conn->query($sql);

    if ($result->num_rows === 1) {
        // Successful login
        header('Location: user_1.php');
        exit();
    } else {
       // Redirect with error graphs
        header('Location: login_cust.php?error=invalid');
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
  <form class="login-form" action="authenticate_cust.php" method="POST">
    <h2>Login</h2>
    <?php if (isset($errorMessage)) { ?>
        <p class="error-message"><?php echo $errorMessage; ?></p>
    <?php } ?>
    <label for="tel">Phone Number</label>
    <input type="tel" id="tel" name="tel" placeholder="Enter your phone number" required>
    <label for="id">ID</label>
    <input type="text" id="id" name="id" placeholder="Enter your ID" required>
    <button type="submit" class="btn">Login</button>
  </form>
</div>
</body>
</html>
