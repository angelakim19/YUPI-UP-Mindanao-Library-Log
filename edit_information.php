<?php
session_start();

if (!isset($_SESSION['firstname']) || !isset($_SESSION['lastname']) || !isset($_SESSION['studentnumber'])) {
    header("Location: register.php");
    exit();
}

$studentnumber = htmlspecialchars($_SESSION['studentnumber']);
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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Fetch form data
    $email = htmlspecialchars($_POST['email']);
    $phonenumber = htmlspecialchars($_POST['phonenumber']);
    $college = htmlspecialchars($_POST['college']);
    $program = htmlspecialchars($_POST['program']);

    // Update user data in the database
    $stmt = $conn->prepare("UPDATE users SET email = ?, phonenumber = ?, college = ?, program = ? WHERE studentnumber = ?");
    $stmt->bind_param("sssss", $email, $phonenumber, $college, $program, $studentnumber);
    
    if ($stmt->execute()) {
        echo "Information updated successfully!";
        // Optionally, redirect to profile page or display a success message
    } else {
        echo "Error updating information: " . $conn->error;
    }

    $stmt->close();
}

// Fetch current user data
$stmt = $conn->prepare("SELECT email, phonenumber, college, program FROM users WHERE studentnumber = ?");
$stmt->bind_param("s", $studentnumber);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    die("User not found.");
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Information</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            margin-top: 10px;
        }
        input[type="text"], input[type="email"], input[type="tel"] {
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        input[type="submit"] {
            margin-top: 20px;
            padding: 10px;
            background-color: #14533c;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #0c3c2b;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Edit Your Information</h1>
    <form action="edit_information.php" method="post">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>

        <label for="phonenumber">Phone Number:</label>
        <input type="tel" id="phonenumber" name="phonenumber" value="<?php echo htmlspecialchars($user['phonenumber']); ?>" required>

        <label for="college">College/Department:</label>
        <input type="text" id="college" name="college" value="<?php echo htmlspecialchars($user['college']); ?>" required>

        <label for="program">Program/Course:</label>
        <input type="text" id="program" name="program" value="<?php echo htmlspecialchars($user['program']); ?>" required>

        <input type="submit" value="Update Information">
    </form>
</div>

</body>
</html>
