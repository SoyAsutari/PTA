<?php
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

// Check if the insurance_id parameter is set in the URL
if (isset($_GET['insurance_id'])) {
    // Get the insurance_id from the URL
    $insuranceId = $_GET['insurance_id'];

    // SQL query to delete the user based on insurance_id
    $sql = "DELETE FROM users WHERE insurance_id = $insuranceId";

    if ($conn->query($sql) === TRUE) {
        // User deleted successfully
        header('Location: admin_1.php');
        exit();
    } else {
        // Error occurred while deleting user
        echo "Error: " . $conn->error;
    }
}

// Close the database connection
$conn->close();
?>
