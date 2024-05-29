<?php
$servername = "localhost";
$username = "root";
$dbpassword = "your_password"; // Use the correct password here
$dbname = "registration_db";

$conn = new mysqli($servername, $username, $dbpassword, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";
$conn->close();
?>