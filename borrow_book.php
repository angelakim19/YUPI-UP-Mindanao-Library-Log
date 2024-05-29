
<?php
session_start();

// Check if necessary session variables are set
if (!isset($_SESSION['firstname']) || !isset($_SESSION['lastname']) || !isset($_SESSION['studentnumber'])) {
    header("Location: register.php");
    exit();
}

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

// Fetch user data
$studentnumber = $_SESSION['studentnumber'];
$stmt = $conn->prepare("SELECT id, firstname, middlename, lastname, email, college, program, phonenumber, position, profile_picture FROM users WHERE studentnumber = ?");
if ($stmt === false) {
    die("Prepare failed: " . $conn->error);
}
$stmt->bind_param("s", $studentnumber);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $user_id = $user['id'];

    // Check if the POST data is set for borrowing book
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['book_title'], $_POST['author'], $_POST['isbn'], $_POST['borrowing_date'], $_POST['due_date'])) {
        // Retrieve the POST data
        $book_title = $_POST['book_title'];
        $author = $_POST['author'];
        $isbn = $_POST['isbn'];
        $borrowing_date = $_POST['borrowing_date'];
        $due_date = $_POST['due_date'];

        // Prepare SQL statement for insertion into borrowed_books
        $stmt = $conn->prepare("INSERT INTO borrowed_books (user_id, book_title, author, isbn, borrowed_date, due_date) VALUES (?, ?, ?, ?, ?, ?)");
        if ($stmt === false) {
            die("Prepare failed: " . $conn->error);
        }
        $stmt->bind_param("isssss", $user_id, $book_title, $author, $isbn, $borrowing_date, $due_date);

        // Execute SQL statement
        if ($stmt->execute()) {
            echo "<script>alert('Book borrowing record created successfully!');</script>";
        } else {
            echo "Borrowing failed: " . $stmt->error;
        }

        // Close statement
        $stmt->close();
    }
} else {
    die("User not found.");
}

// Close the statement and connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Borrow Book</title>
    <style>
        body, html {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background-color: #fbc130;
            background-image: url('YUPi Student Panel.png');
            background-size: cover;
            background-repeat: no-repeat;
            color: white;
            text-align: center;
            overflow-x: hidden;
            overflow-y: auto;
        }

        .logo {
            display: flex;
            align-items: center;
        }

        .logo img {
            width: 80px;
            margin-left: 20px;
        }

        .logo-text {
            color: white;
            font-size: 24px;
            font-weight: bold;
        }

        .navigation-bar {
            background-color: #14533c;
            height: 80px;
            width: 100%;
            display: flex;
            justify-content: space-between; 
            align-items: center;
            position: fixed;
            top: 0;
            z-index: 1000;
        }

        .user-photo {
            width: 40px;
            height: 40px;
            border: 2px solid rgb(91, 88, 88);
            border-radius: 50%;
            margin-right: 30px;
            cursor: pointer;
        }

        .about-us {
            display: flex;
            align-items: center;
            margin-left: 20px;
        }

        .about-us a {
            color: white;
            text-decoration: none;
            font-size: 18px;
            margin-right: 20px;
            font-family: Arial, sans-serif;
            font-weight: bold;
        }

        .borrow-book-form {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            max-width: 600px;
            margin: 120px auto 40px;
            text-align: left;
            color: black;
        }

        .borrow-book-form h2 {
            font-family: 'Quiapo', sans-serif;
            color: #7a0422;
            font-size: 36px;
            text-align: center;
        }

        .borrow-book-form label {
            display: block;
            margin: 10px 0 5px;
        }

        .borrow-book-form input,
        .borrow-book-form textarea,
        .borrow-book-form select {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            box-sizing: border-box;
            font-family: 'Fredoka One', sans-serif;
        }

        .borrow-book-form .form-buttons {
            display: flex;
            justify-content: space-between;
        }

        .borrow-book-form .form-buttons button {
            flex: 1;
            margin: 0 5px;
            padding: 10px;
            font-size: 18px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-family: 'Fredoka One', sans-serif;
        }

        .borrow-book-form .form-buttons .record {
            background-color: #007b70;
            color: white;
        }

        .borrow-book-form .form-buttons .cancel {
            background-color: #7a0422;
            color: white;
        }

        .borrow-book-form input[type="number"] {
            width: calc(100% - 25px);
            display: inline-block;
        }

        .borrow-book-form .quantity-container {
            display: flex;
            align-items: center;
        }

        .borrow-book-form .quantity-container input {
            flex: 1;
        }

        .borrow-book-form .quantity-container span {
            margin-left: 10px;
            font-size: 20px;
            color: #7a0422;
        }
    </style>
</head>
<body>
    <div class="navigation-bar">
        <div class="logo">
            <img src="yupilogo.png" alt="YUPI Logo">
            <span class="logo-text">YUPI</span>
        </div>
        <nav>
            <ul style="list-style: none; padding: 0; margin: 0; display: flex;">
                <li class="about-us"><a href="#" id="about-us-link">ABOUT US</a></li>
                <li><img src="default_userp.png" alt="User Photo" class="user-photo" id="user-photo"></li>
            </ul>
        </nav>
    </div>
    <div class="borrow-book-form">
        <h2>Borrow Book</h2>
        <form id="borrow-book-form" method="POST" action="borrow_book.php">
            <div class="quantity-container">
                <label for="quantity">Quantity:</label>
                <input type="number" id="quantity" name="quantity" min="1" required>
            </div>
            <label for="book-title">Book Title:</label>
            <input type="text" id="book-title" name="book_title" required>
            <label for="author">Author:</label>
            <input type="text" id="author" name="author" required>
            <label for="isbn">ISBN:</label>
            <input type="text" id="isbn" name="isbn" required>
            <label for="borrowing-date">Borrowing Date:</label>
            <input type="date" id="borrowing-date" name="borrowing_date" required>
            <label for="due-date">Due Date:</label>
            <input type="date" id="due-date" name="due_date" required>
            <div class="form-buttons">
                <button type="submit" class="record">Record</button>
                <button type="button" class="cancel" onclick="window.location.href='reserve_seat.php'">Cancel</button>
            </div>
        </form>
    </div>
    <script>
        document.getElementById('borrow-book-form').addEventListener('submit', function(event) {
            event.preventDefault();
            alert('Successfully Borrowed Book!');
            document.getElementById('borrow-book-form').submit(); // Proceed with form submission
        });
    </script>
</body>
</html>