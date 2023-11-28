<?php
// Start the session to access session variables
session_start();

// Check if the admin is not logged in, redirect to admin_login.php
if (!isset($_SESSION['username'])) {
    header('Location: login_admin.php');
    exit();
}

// Get the admin's username from the session
$adminUsername = isset($_SESSION['username']) ? $_SESSION['username'] : '';

// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "insurance_system";

// Create a connection to the database
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the insurance ID is provided
$insuranceId = isset($_GET['id']) ? $_GET['id'] : null;

// SQL query to fetch user details based on insurance_id with dynamically calculated status
$sql = "SELECT *, 
        CASE WHEN expiry_date < CURRENT_DATE() THEN 'EXPIRED' ELSE 'ACTIVE' END AS status
        FROM users 
        WHERE insurance_id = $insuranceId";

$result = $conn->query($sql);

// Check if a user was found
if ($result->num_rows > 0) {
    $userData = $result->fetch_assoc();
} else {
    echo "User not found.";
}

// Close the database connection
$conn->close();

// Handle logout
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
    // stop the session and redirect to the login page
    session_destroy();
    header('Location: login_admin.php');
    exit();
}

// Delete user logic
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    $deleteQuery = "DELETE FROM users WHERE insurance_id = $insuranceId";
    if ($conn->query($deleteQuery) === TRUE) {
        echo '<script>alert("User deleted successfully.");</script>';
        echo '<script>window.location.href = "admin_1.php";</script>';
        exit();
    } else {
        echo "Error deleting user: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Details</title>
    <link rel="stylesheet" href="styles_details.css">
</head>
<body>
    <header>
        <img src="gambar/LOGO.png" width="200" height="90">
        <h1>Welcome, Admin <?php echo $adminUsername; ?>!</h1>
        <form method="post">
            <button type="submit" name="logout" class="logout-button">Logout</button>
        </form>
    </header>
    <main>
        <section class="user-details">
            <h2 class="merah">User Details</h2>
            <?php if (isset($userData)) { ?>
                <table class="user-table">

                    <tr>
                        <th>Insurance ID</th>
                        <td><?php echo $userData['insurance_id']; ?></td>
                    </tr>
                    <tr>
                        <th>Model</th>
                        <td><?php echo $userData['model']; ?></td>
                    </tr>
                    <tr>
                        <th>Plate</th>
                        <td><?php echo $userData['plate']; ?></td>
                    </tr>
                    <tr>
                        <th>Owner</th>
                        <td><?php echo $userData['username']; ?></td>
                    </tr>
                    <th>Type Of Insurance</th>
                        <td><?php echo $userData['type']; ?></td>
                    </tr>
                    <tr>
                        <th>Expiry Date</th>
                        <td><?php echo $userData['expiry_date']; ?></td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td style="color: <?php echo ($userData['status'] == 'EXPIRED' ? 'red' : 'green'); ?>">
                            <?php echo $userData['status']; ?>
                        </td>
                    </tr>
                    
                </table>
                <br>
                <section class="lol">
                    <a href="admin_1.php" class="return-button">Return</a>
                    <form method="post" onsubmit="return confirm('Are you sure you want to delete this user?'); ">
                    <button class="delete-button"  type="submit" name="delete" >Delete User</button>
                    </form>
                </section>
                
            <?php } ?>
        </section>
        
    </main>
    <style>
        function confirmDelete() {
            return confirm("Are you sure you want to delete this user?"); // Display a confirmation dialog
        }
    </script>
</body>
</html>
