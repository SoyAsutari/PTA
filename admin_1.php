<?php
session_start();

// Function to sanitize user input
function sanitizeInput($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

// Check if the admin is not logged in, redirect to admin_login.php
if (!isset($_SESSION['username'])) {
    header('Location: login_admin.php');
    exit();
}

// Logout logic
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
    // Stop the session and redirect to the login page
    session_destroy();
    header('Location: login_admin.php');
    exit();
}

$adminUsername = $_SESSION['username'];

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

// Initialize variables
$username = $id = $tel = $email = $address = $model = $plate = $type = $plans = $expiry_date = $status = "";
$errors = [];

// Add user logic
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
    $plans = 'Yearly'; // Fixed to yearly
    $expiry_date = date('Y-m-d', strtotime('+1 year')); // Fixed to 1 year from the current date

    // Calculate the status based on the expiry date and current date
    $expiryDate = new DateTime($expiry_date);
    $currentDate = new DateTime();
    $status = ($expiryDate < $currentDate) ? 'EXPIRED' : 'ACTIVE';

    // Validate input fields (you can add more specific validation as needed)
    if (empty($username) || empty($id) || empty($tel) || empty($email) || empty($address) || empty($model) || empty($plate) || empty($type)) {
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

// Admin dashboard code
// SQL query to fetch insurance data with dynamically calculated status
$sql = "SELECT insurance_id, username, tel, expiry_date,
        CASE WHEN expiry_date < CURRENT_DATE() THEN 'EXPIRED' ELSE 'ACTIVE' END AS status
        FROM users";

// Execute the query
$result = $conn->query($sql);

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="style_admin_1.css">
</head>
<body>
    <header>
        <img src="gambar/LOGO.png" width="200" height="90">
        <h1>Welcome, Admin <?php echo $adminUsername; ?>!</h1>
        <form method="post">
            <button type="submit" name="logout" class="button logout-button">Logout</button>
        </form>
    </header>
    <br>
    <div class="pad">
        <nav>
            <ul>
                <li><a class="button manage-button" href="admin_1.php">Manage Users</a></li>
                <li><a class="button add-button" href="add_users.php">Add Users</a></li>
                <li><a class="button add-button" href="register_admin.php">Register Admin</a></li>
            </ul>
        </nav>
    </div>
    <div class="pad2">
        <section class="insurance-table">
            <center><h2>Insurance Information</h2></center>
            <table>
                <thead>
                    <tr>
                        <th>Insurance ID</th>
                        <th>Username</th>
                        <th>Phone Number</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Loop through the database results and display each row
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row['insurance_id'] . "</td>";
                        echo "<td>" . $row['username'] . "</td>";
                        echo "<td>" . $row['tel'] . "</td>";
                        echo "<td style='color: " . ($row['status'] == 'EXPIRED' ? 'red' : 'green') . "'>" . $row['status'] . "</td>";
                        echo "<td><a href='manage.php?id=" . $row['insurance_id'] . "' class='manage-button'>Manage</a></td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </section>

        <br>

        <footer>

        </footer>
    </div>

    <script>
        function confirmDelete() {
            return confirm("Are you sure you want to delete this user?"); // Display a confirmation dialog
        }
    </script>
</body>
</html>