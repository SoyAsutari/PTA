<?php
// Start the session to access session variables
session_start();

// Check if the user is authenticated
if (!isset($_SESSION['id'])) {
    // Redirect the user to the login page if user is not authenticate
    header('Location: login_cust.php');
    exit(); 
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
  // stop the session and redirect to the login page
  session_destroy();
  header('Location: login_cust.php');
  exit();
}

// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "insurance_system";


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// fetch the user ID from the session
$userId = $_SESSION['id'];

// SQL query to fetch insurance data specific to the user
$sql = "SELECT insurance_id, model, plate, username, expiry_date, status FROM users WHERE id = '$userId'";

// Execute the query
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="styles_user_1.css">
</head>
<body>
    <header>
       <img src="gambar/LOGO.png" width="200" height="90">
       <h2>Welcome, User <?php echo $userId; ?></h2>
            <form method="post">
                <button type="submit" name="logout" class="logout-button">Logout</button>
            </form>
    </header>
    <main>
        <section class="user-dashboard">
            
            <center><h3>Your Insurance Data</h3></center>
            <table>
                <thead>
                    <tr>
                        <th>Insurance ID</th>
                        <th>Model</th>
                        <th>Plate</th>
                        <th>Owner</th>
                        <th>Expiry Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<tr data-id='" . $row['insurance_id'] . "'>";
                        echo "<td>" . $row['insurance_id'] . "</td>";
                        echo "<td>" . $row['model'] . "</td>";
                        echo "<td>" . $row['plate'] . "</td>";
                        echo "<td>" . $row['username'] . "</td>";
                        echo "<td>" . $row['expiry_date'] . "</td>";
                        echo "<td>" . $row['status'] . "</td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
            
        </section>
        
    </main>
    <footer>
       
    </footer>
     <script>
        // Get all the table rows
        const rows = document.querySelectorAll('.user-dashboard tbody tr');

        // Add a click event listener to each row
        rows.forEach(row => {
            row.addEventListener('click', () => {
                // Get the data-id attribute of the clicked row
                const insuranceId = row.getAttribute('data-id');

                // Redirect to details.php with the insurance ID
                window.location.href = 'details.php?id=' + insuranceId;
            });
        });
    </script>
     <style>
        /* Style for the Logout button */
        .logout-button {
            background-color: #f44336; 
            color: white;
            border: none; 
            padding: 10px 20px;
            cursor: pointer; 
        }

        /* Style for the Logout button on hover */
        .logout-button:hover {
            background-color: #d32f2f; 
        }
    </style>
</body>
</html>
