<?php
// Start the session to access session variables
session_start();

// Check if the admin is not logged in, redirect to admin_login.php
if (!isset($_SESSION['username'])) {
    header('Location: login_admin.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
    // stop the session and redirect to the login page
    session_destroy();
    header('Location: login_admin.php');
    exit();
}

// Get the admin's username from the session
$adminUsername = $_SESSION['username'];
$newAdminUsername = $newAdminPassword = $confirmAdminPassword = "";

// if the input is not correct
$errors = [];

// Database connection details
$servername = "localhost";
$db_username = "root";
$db_password = "";
$db_name = "insurance_system";

// Create a connection to the database
$conn = new mysqli($servername, $db_username, $db_password, $db_name);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize user input
    $newAdminUsername = sanitizeInput($_POST['newAdminUsername']);
    $newAdminPassword = $_POST['newAdminPassword'];
    $confirmAdminPassword = $_POST['confirmAdminPassword'];

    // Validate input fields
    if (empty($newAdminUsername) || empty($newAdminPassword) || empty($confirmAdminPassword)) {
        $errors[] = "All fields are required.";
    }

    // Check if the password and confirm password match
    if ($newAdminPassword !== $confirmAdminPassword) {
        $errors[] = "Password and Confirm Password do not match.";
    }

    // Check if the username already exists
    $checkUsernameQuery = "SELECT * FROM admins WHERE username = ?";
    $checkStmt = $conn->prepare($checkUsernameQuery);
    $checkStmt->bind_param("s", $newAdminUsername);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    if ($checkResult->num_rows > 0) {
        $errors[] = "Username already exists. Please choose a different username.";
    }

    $checkStmt->close();

    // If there are no validation errors, insert the admin into the database
    if (empty($errors)) {
        // SQL query to insert the admin into the database
        $insertQuery = "INSERT INTO admins (username, password) VALUES (?, ?)";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param("ss", $newAdminUsername, $newAdminPassword);

        if ($stmt->execute()) {
            $message = "Admin registered successfully.";
        } else {
            $errors[] = "Error registering admin. Please try again.";
        }

        $stmt->close();
    }
}

// Function to sanitize user input
function sanitizeInput($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Admin</title>
    <link rel="stylesheet" href="styles_register_admin.css">
</head>
<body>
    <header>
        <img src="gambar/LOGO.png" width="200" height="90">
        <h1>Welcome, <?php echo $adminUsername; ?>!</h1> <!-- Display the admin's username -->
        <form method="post" onsubmit="return confirm('Are you sure you want to logout?');"> 
            <button type="submit" name="logout" class="button logout-button">Logout</button>
        </form>
    </header>
    <br>
    <div class="pad">
    <nav>
        <ul>
            <li><a class="button manage-button" href="admin_1.php">Manage Users</a></li>
            <li><a class="button add-button" href="add_users.php">Add Users</a></li>
            <li><a class="button add-button" href="register_admin.php">Register Admin</a></li> <!-- Add a link to the Register Admin page -->
        </ul>
    </nav>
    </div>
    <div class="pad2">
    <main>
        <section class="register-admin-form">
            <h2>Register Admin</h2>
            <?php
            // Display validation errors
            if (!empty($errors)) {
                echo "<div class='error-box'>";
                foreach ($errors as $error) {
                    echo "<p>$error</p>";
                }
                echo "</div>";
            }

            // Display success message
            if (isset($message)) {
                echo "<div class='success-box'><p>$message</p></div>";
            }
            ?>
            <form method="post">
                <label for="newAdminUsername">Username</label>
                <input type="text" id="newAdminUsername" name="newAdminUsername" required>
                <label for="newAdminPassword">Password</label>
                <input type="password" id="newAdminPassword" name="newAdminPassword" required>
                <label for="confirmAdminPassword">Confirm Password</label>
                <input type="password" id="confirmAdminPassword" name="confirmAdminPassword" required>
                <button type="submit" class="button register-button">Register Admin</button>
            </form>
        </section>
    </main>
</div>
</body>
</html>
