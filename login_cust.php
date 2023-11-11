<?php
session_start(); // Start the session to access session variables

// Connection to the MySQL database
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'insurance_system';

$conn = new mysqli($host, $username, $password, $database);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $enteredInsuranceId = $_POST['insurance_id']; // Updated from 'username' to 'insurance_id'
    $enteredID = $_POST['id'];

    $sql = "SELECT * FROM users WHERE insurance_id = '$enteredInsuranceId' AND id = '$enteredID'";
    $result = $conn->query($sql);

    if ($result->num_rows === 1) {
        // Store the user's ID in a session variable if successful login
        $_SESSION['id'] = $enteredID;
        header('Location: user_1.php');
        exit();
    } else {
        // Redirect with error message
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
    <link rel="stylesheet" href="styles_log1.css">
</head>
<body>

<div class="login-container">
    <form class="login-form" action="" method="POST">
        <h2>Login Pengguna</h2>
        <?php if (isset($_GET['error']) && $_GET['error'] === 'invalid') { ?>
            <p style="color: red;" class="error-message">Please try again.</p>
        <?php } ?>
        <label for="insurance_id">Insurance ID</label> <!-- Updated from 'username' to 'insurance_id' -->
        <input type="text" id="insurance_id" name="insurance_id" placeholder="Enter your insurance ID" required>
        <label for="id">ID</label>
        <input type="text" id="id" name="id" placeholder="Enter your ID" required>
        <button type="submit" class="btn">Login</button>
        <br>
        <div class="return-button-container">
            <a href="login_admin.php" class="return-link">Admin Login Here</a>
            <a href="MainPage.php" class="return-link">Return to Main Page</a>
        </div>
    </form>
</div>
</body>
</html>
