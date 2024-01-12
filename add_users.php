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
    
    // Calculate the status based on the expiry date and current date
    $expiryDate = new DateTime($expiry_date);
    $currentDate = new DateTime();
    $status = ($expiryDate < $currentDate) ? 'EXPIRED' : 'ACTIVE';

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
        <li><a class="button add-button" href="register_admin.php">Register Admin</a></li> 
        </ul>
    </nav>
    </div>
    <div class="pad2">
        
            
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

        <section class="add-user-form">    
            
            <div class="mainf">
                <div>
                    <h2>Add User</h2>
                </div>
                
            <form method="post">
                <div class="baris">
                <div class="nama">
                <label for="username">Name</label>
                </div>
                <div class="kotak">
                <input type="text" id="username" name="username" required>
                </div>
                </div>

                <div class="baris">
                <div class="nama">
                <label for="id">Ic Number</label>
                </div>
                <div class="kotak">
                <input type="text" id="id" name="id" required>
                </div>
                </div>

                <div class="baris">
                <div class="nama">
                <label for="tel">Phone Number</label>
                </div>
                <div class="kotak">
                <input type="tel" id="tel" name="tel" required>
                </div>
                </div>

                <div class="baris">
                <div class="nama">
                <label for="email">Email</label>
                </div>
                <div class="kotak">
                <input type="email" id="email" name="email" required>
                </div>
                </div>

                <div class="baris">
                <div class="nama">
                <label for="address">Address</label>
                </div>
                <div class="kotak">
                <input type="text" id="address" name="address" required>
                </div>
                </div>

                <div class="baris">
                <div class="nama">
                <label for="model">Model</label>
                </div>
                <div class="kotak">
                <input type="text" id="model" name="model" required>
                </div>
                </div>

                <div class="baris">
                <div class="nama">
                <label for="plate">Plate</label>
                </div>
                <div class="kotak">
                <input type="text" id="plate" name="plate" required>
                </div>
                </div>

                <div class="baris">
                <div class="nama">
                <label for="type">Type</label>
                </div>
                <div class="kotak">
                <select id="type" name="type" required>
                 <option value="CAR">CAR</option>
                 <option value="MOTORCYCLE">MOTORCYCLE</option>
                </select>
                </div>
                </div>
                <div class="baris">
               <div class="nama">
                <label for="plans">Plans</label>
                </div>
                <div class="kotak">
                <input type="text" id="plans" name="plans" value="YEARLY" readonly>
                </div>
                </div>
                <div class="baris">
    <div class="nama">
        <label for="expiry_date">Expiry Date</label>
    </div>
       
    <div class="kotak">
        <?php
        // Calculate expiry date as one year from the current date
        $oneYearLater = date('Y-m-d', strtotime('+1 year'));
        ?>
        <input type="date" id="expiry_date" name="expiry_date" value="<?php echo $oneYearLater; ?>" required readonly>
    </div>
</div>

                <div class="baris">
                <div class="nama">
                <label for="status">Status</label>
                </div>
                <div class="kotak">
                <input type="text" id="status" name="status" value="Leave this section"<?php echo $status; ?>" readonly>
                </div>
                </div>

                <button type="submit" class="button add-button">Add User</button>
            </form>
            </div>
            <div></div>
            <div class="pad3">
            <div class="karkulator">
            <!-- Calculator -->
            <h2>Calculator</h2>
            <label for="amount">Amount:</label>
            <input type="number" id="amount" name="amount" step="any" placeholder="Enter amount" required>
            <button type="button" onclick="calculate()">Calculate 1%</button>
            <p id="result"></p>
            </div>
            </div>
            <script>
                function calculate() {
                    var amount = document.getElementById('amount').value;
                    var result = amount * 0.01;
                    document.getElementById('result').innerText = "1% of the amount is: " + result;
                }
            </script>
           
        </section>
    </div>
    </main>
</body>
</html>
