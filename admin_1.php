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

// SQL query to fetch insurance data
$sql = "SELECT insurance_id, username, tel, status FROM users";


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
        <h1 >Welcome, <?php echo $adminUsername; ?>!</h1> <!-- Display the admin's username -->
    <form method="post">
            <button type="submit" name="logout" class="button logout-button">Logout</button>
    </form>
    
    </header>
   <main > 
    <br>
    <nav> 
        <ul>
        <li><a class="button manage-button" href="admin_1.php">Manage Users</a></li>
        <li><a class="button add-button" href="add_users.php">Add Users</a></li>
        <li><a class="button add-button" href="register_admin.php">Register Admin</a></li> 
        </ul>
    </nav>
    
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
                        echo "<td>" . $row['status'] . "</td>";
                        echo "<td><a href='delete_user.php?insurance_id=" . $row['insurance_id'] . "' class='delete-button' onclick='return confirmDelete()'>Delete</a></td>"; 
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </section>
        <br>
    </main>
    <footer>
       
    </footer>

    <script>
        function confirmDelete() {
            return confirm("Are you sure you want to delete this user?"); // Display a confirmation dialog
        }
    </script>
</body>
</html>
