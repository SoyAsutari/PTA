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

// Check if the insurance ID is provided
if (isset($_GET['id'])) {
    $insuranceId = $_GET['id'];
    
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
} else {
    // Handle the case where no insurance ID was provided
    echo "Invalid request.";
}

$conn->close();
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
     
    </header>
    <main>
        <section class="user-details">
            <h2>User Details</h2>
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
            <?php } ?>
        </section>
        <section class="insurance-details">

         
            <br>
            <a href="user_1.php" class="return-button">Return</a>
        </section>
    </main>
    <footer>
        
    </footer>
    <style>
        /* Style for the Return button */
        .return-button {
            background-color: #FF0000;
            color: white; 
            border: none; 
            padding: 5px 10px; 
            cursor: pointer; 
            text-decoration: none; 
        }

        /* Style for the Return button on hover */
        .return-button:hover {
            background-color: #BCBCBC; 
        }
    </style>
</body>
</html>
