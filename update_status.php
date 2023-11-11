<?php
// update_status.php

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

// Get the current date
$currentDate = date("Y-m-d");

// Update status based on expiry date
$updateQuery = "UPDATE users SET status = 'Not Active' WHERE expiry_date < '$currentDate'";
$conn->query($updateQuery);

// Close the database connection
$conn->close();
?>
