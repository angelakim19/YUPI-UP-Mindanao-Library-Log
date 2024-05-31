<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Borrowed Books</title>
    <style>
        /* Add a global style for body */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 10px;
        }

        /* Style the form container */
        form {
            background-color: #fff;
            padding: 20px;
            margin: 20px auto; /* Center the form horizontally */
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        /* Style form labels */
        form label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        /* Style form inputs */
        form input[type="text"] {
            width: 100%; /* Adjusted width to fit the container */
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        /* Style the submit button */
        form input[type="submit"], form button[type="button"] {
            background-color: #28a745;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            margin-right: 10px;
        }

        /* Change the button color on hover */
        form input[type="submit"]:hover, form button[type="button"]:hover {
            background-color: green;
        }

        /* Style for the cancel button */
        form button[type="button"] {
            background-color: grey;
        }

        form button[type="button"]:hover {
            background-color: maroon;
        }

        @media (max-width: 600px) {
            form {
                padding: 15px;
            }

            form input[type="text"] {
                padding: 8px;
            }

            form input[type="submit"], form button[type="button"] {
                width: 100%;
                padding: 12px;
                margin-bottom: 10px;
            }
        }
    </style>
</head>
<body>
    <?php
    session_start();
    include 'db_connection.php';

    // Check if user is logged in as admin
    if (!isset($_SESSION['admin_logged_in'])) {
        header("Location: admin_login.php");
        exit();
    }

    // Check if selected_ids are set in session
    if (!isset($_SESSION['selected_ids'])) {
        // Redirect to borrowed_books.php if no books are selected
        header("Location: borrowed_books.php");
        exit();
    }

    // Retrieve selected IDs from session and escape them for SQL query
    $selected_ids = $_SESSION['selected_ids'];
    $escaped_ids = array_map('intval', $selected_ids);  // Ensure IDs are integers

    // Fetch book information from database for the selected IDs
    $sql = "SELECT borrowed_books.id, users.studentnumber, users.lastname, users.firstname, users.middlename,
                borrowed_books.book_title, borrowed_books.author, borrowed_books.isbn, 
                borrowed_books.borrowed_date, borrowed_books.due_date, borrowed_books.returned_date
            FROM borrowed_books
            JOIN users ON borrowed_books.user_id = users.id
            WHERE borrowed_books.id IN (" . implode(',', $escaped_ids) . ")";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Display edit form for each selected book
        while ($row = $result->fetch_assoc()) {
            ?>
            <form method="post" action="update_book.php">
                <input type="hidden" name="book_id" value="<?php echo htmlspecialchars($row['id']); ?>">
                <label for="studentnumber">Student Number:</label>
                <input type="text" name="studentnumber" value="<?php echo htmlspecialchars($row['studentnumber']); ?>"><br>
                <label for="last_name">Last Name:</label>
                <input type="text" name="last_name" value="<?php echo htmlspecialchars($row['lastname']); ?>"><br>
                <label for="first_name">First Name:</label>
                <input type="text" name="first_name" value="<?php echo htmlspecialchars($row['firstname']); ?>"><br>
                <label for="middle_name">Middle Name:</label>
                <input type="text" name="middle_name" value="<?php echo htmlspecialchars($row['middlename']); ?>"><br>
                <label for="book_title">Book Title:</label>
                <input type="text" name="book_title" value="<?php echo htmlspecialchars($row['book_title']); ?>"><br>
                <label for="author">Author:</label>
                <input type="text" name="author" value="<?php echo htmlspecialchars($row['author']); ?>"><br>
                <label for="isbn">ISBN:</label>
                <input type="text" name="isbn" value="<?php echo htmlspecialchars($row['isbn']); ?>"><br>
                <label for="borrowed_date">Borrowed Date:</label>
                <input type="text" name="borrowed_date" value="<?php echo htmlspecialchars($row['borrowed_date']); ?>"><br>
                <label for="due_date">Due Date:</label>
                <input type="text" name="due_date" value="<?php echo htmlspecialchars($row['due_date']); ?>"><br>
                <input type="submit" value="Update">
                <button type="button" onclick="window.location.href='borrowedbook&reservation.html'">Cancel</button>
            </form>
            <?php
        }
    } else {
        echo "No books found.";
    }

    // Close connection
    $conn->close();
    ?>
</body>
</html>
