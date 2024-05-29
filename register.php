<?php
session_start();
include 'upload.php'; // Include the upload script

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname = $_POST['firstname'];
    $middlename = $_POST['middlename'];
    $lastname = $_POST['lastname'];
    $studentnumber = $_POST['studentnumber'];
    $email = $_POST['email'];
    $college = $_POST['college'];
    $program = $_POST['program'];
    $phonenumber = $_POST['phonenumber'];  
    $position = $_POST['position'];        
    $password = $_POST['password'];


    // Handle file upload
    try {
        if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == UPLOAD_ERR_OK) {
            $profile_picture_path = uploadProfilePicture($_FILES['profile_picture']);
        } else {
            $profile_picture_path = null;
        }
    } catch (Exception $e) {
        die($e->getMessage());
    }

    // Database connection
    $servername = "localhost"; 
    $username = "root"; 
    $dbpassword = ""; // Use the correct password for the MySQL root user
    $dbname = "registration_db";

    // Create connection
    $conn = new mysqli($servername, $username, $dbpassword, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Insert user data
    $stmt = $conn->prepare("INSERT INTO users (firstname, middlename, lastname, studentnumber, email, college, program, phonenumber, position, password, profile_picture) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }

    $bind = $stmt->bind_param("sssssssssss", $firstname, $middlename, $lastname, $studentnumber, $email, $college, $program, $phonenumber, $position, $password, $profile_picture_path);
    if ($bind === false) {
        die("Bind failed: " . $stmt->error);
    }

 

    if ($stmt->execute()) {
        $_SESSION['firstname'] = $firstname;
        $_SESSION['lastname'] = $lastname;
        $_SESSION['studentnumber'] = $studentnumber;

        header("Location: userprofile.php");
        exit;
    } else {
        echo "Execute failed: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
