<?php
session_start();

$host = 'localhost';
$username = 'root';
$password = '';
$database = 'insurance_system';

$conn = new mysqli($host, $username, $password, $database);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $enteredName = trim($_POST['username']);
    $enteredPassword = trim($_POST['password']);

    $sql = "SELECT * FROM admins WHERE username = ? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $enteredName);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();

        // Check Password Using password_verify
        if (password_verify($enteredPassword, $row['password'])) {
            $_SESSION['username'] = $enteredName;
            $_SESSION['admin_type'] = $row['admin_type'];

            // Redirect based on admin type
            if ($_SESSION['admin_type'] == 1) {
                header('Location: admin_1.php');
                exit();
            } elseif ($_SESSION['admin_type'] == 2) {
                header('Location: admin_2.php');
                exit();
            }
        } else {
            // Redirect with error message
            header('Location: login_admin.php?error=invalid');
            exit();
        }
    }

    // Redirect with error message
    header('Location: login_admin.php?error=invalid');
    exit();
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
                <p style="color: red;" class="error-message">Invalid username or password. Please try again.</p>
            <?php } ?>
            <label for="username">Username</label>
            <input type="text" id="username" name="username" placeholder="Enter your username" required>
            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Enter your password" required>
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
