<?php
session_start();

$servername = "localhost";
$username = ""; // Add your database username
$password = ""; // Add your database password
$dbname = "registration_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare and bind
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND password = ?");
    $stmt->bind_param("ss", $email, $password);

    // Execute and get results
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Fetch user data
        $user = $result->fetch_assoc();

        // Store user data in session variables
        $_SESSION['loggedin'] = true;
        $_SESSION['firstname'] = $user['firstname'];
        $_SESSION['lastname'] = $user['lastname'];
        $_SESSION['studentnumber'] = $user['studentnumber'];
        // Add more session variables as needed

        // Redirect to welcome page
        header('Location: welcomepage.html');
        exit;
    } else {
        // Invalid credentials
        header('Location: login.html?error=invalid_credentials');
        exit;
    }

    $stmt->close();
}

$conn->close();
?>
