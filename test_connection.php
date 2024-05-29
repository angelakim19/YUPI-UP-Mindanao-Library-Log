<?php
$servername = "localhost";
$username = "root";
$password = "";  // Use your actual password if you have set one
$dbname = "registration_db";  // The database you just created

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";

// Test if the table exists and fetch some data
$sql = "SELECT * FROM users";
if ($result = $conn->query($sql)) {
    echo "Table exists and data can be retrieved.";
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>
