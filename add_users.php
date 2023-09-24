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
    header('Location: login_cust.php');
    exit();
}

// Get the admin username from the session
$adminUsername = $_SESSION['username'];
$username = $id = $tel = $email = $address = $model = $plate = $type = $plans = $expiry_date = $status = "";
// Define an array to store validation errors
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
    $username = sanitizeInput($_POST['username']);
    $id = sanitizeInput($_POST['id']);
    $tel = sanitizeInput($_POST['tel']);
    $email = sanitizeInput($_POST['email']);
    $address = sanitizeInput($_POST['address']);
    $model = sanitizeInput($_POST['model']);
    $plate = sanitizeInput($_POST['plate']);
    $type = sanitizeInput($_POST['type']);
    $plans = sanitizeInput($_POST['plans']);
    $expiry_date = sanitizeInput($_POST['expiry_date']);
    $status = sanitizeInput($_POST['status']);

    // Validate input fields (you can add more specific validation as needed)
    if (empty($username) || empty($id) || empty($tel) || empty($email) || empty($address) || empty($model) || empty($plate) || empty($type) || empty($plans) || empty($expiry_date) || empty($status)) {
        $errors[] = "All fields are required.";
    }

    // If there are no validation errors, insert the user into the database
    if (empty($errors)) {
        // SQL query to insert the user into the database
         $insertQuery = "INSERT INTO users (username, id, tel, email, address, model, plate, type, plans, expiry_date, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param("sssssssssss", $username, $id, $tel, $email, $address, $model, $plate, $type, $plans, $expiry_date, $status);

        if ($stmt->execute()) {
            $message = "User added successfully.";
        } else {
            $errors[] = "Error adding user. Please try again.";
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
    <title>Add User</title>
    <link rel="stylesheet" href="styles_add_users.css">

</head>
<body>
    <header>
       <img src="gambar/LOGO.png" width="200" height="90">
        <h1 >Welcome, <?php echo $adminUsername; ?>!</h1> <!-- Display the admin's username -->
    <form method="post">
            <button type="submit" name="logout" class="button logout-button">Logout</button>
    </form>
    
    </header>
    
    <main>
    <nav >
        <!-- Navigation links for managing users and adding users -->
        <ul>
            <li><a class="button manage-button" href="admin_1.php">Manage Users</a></li>
            <li><a class="button add-button" href="add_users.php">Add Users</a></li>
            <li><a class="button add-button" href="register_admin.php">Register Admin</a></li> 
        </ul>
    </nav>
    <br>
        <section class="add-user-form">
            <h2>Add User</h2>
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
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
                <label for="id">ID</label>
                <input type="text" id="id" name="id" required>
                <label for="tel">Phone Number</label>
                <input type="tel" id="tel" name="tel" required>
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
                <label for="address">Address</label>
                <input type="text" id="address" name="address" required>
                <label for="model">Model</label>
                <input type="text" id="model" name="model" required>
                <label for="plate">Plate</label>
                <input type="text" id="plate" name="plate" required>
                <label for="type">Type</label>
                <input type="text" id="type" name="type" required>
                <label for="plans">Plans</label>
                <input type="text" id="plans" name="plans" required>
                <label for="expiry_date">Expiry Date</label>
                <input type="date" id="expiry_date" name="expiry_date" required>
                <label for="status">Status</label>
                <input type="text" id="status" name="status" required>
                <button type="submit" class="button add-button">Add User</button>
            </form>
        </section>
    </main>
</body>
</html>
