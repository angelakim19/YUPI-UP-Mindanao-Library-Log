<!DOCTYPE html>
<html>
<head>
    <title>Add Book Borrowed</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 50px;
            background-color: #f0f0f0;
        }
        h2 {
            text-align: center;
        }
        .container {
            width: 50%;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            margin-bottom: 5px;
            font-weight: bold;
        }
        input[type="text"],
        input[type="date"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border-radius: 4px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }
        .button-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        input[type="submit"],
        button {
            width: 48%;
            padding: 8px;
            border-radius: 4px;
            border: none;
            cursor: pointer;
            box-sizing: border-box;
        }
        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
        }
        input[type="submit"]:hover {
            background-color: green;
        }
        button {
            background-color: grey;
            color: white;
        }
        button:hover {
            background-color: maroon;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Add Book Borrowed</h2>
        <?php
        session_start();
        include 'db_connection.php';

        // Check if the form is submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
          // Retrieve form data
          $book_title = $_POST['book_title'];
          $author = $_POST['author'];
          $isbn = $_POST['isbn'];
          $borrowed_date = $_POST['borrowed_date'];
          $due_date = $_POST['due_date'];

          // Insert book information into the database
          $sql = "INSERT INTO borrowed_books (book_title, author, isbn, borrowed_date, due_date) 
                  VALUES ('$book_title', '$author', '$isbn', '$borrowed_date', '$due_date')";

          if ($conn->query($sql) === TRUE) {
              // Alert success message
              echo "<script>alert('Book added successfully');</script>";

              // Redirect to borrowed_books.php after successful insertion
              echo "<script>window.location.href = 'borrowed_books.php';</script>";
              exit();
          } else {
              // Handle errors
              echo "Error adding book: " . $conn->error;
          }
      }
      ?>
      <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
          <label for="book_title">Book Title:</label>
          <input type="text" name="book_title" required>
          <label for="author">Author:</label>
          <input type="text" name="author" required>
          <label for="isbn">ISBN:</label>
          <input type="text" name="isbn" required>
          <label for="borrowed_date">Borrowed Date:</label>
          <input type="date" name="borrowed_date" required>
          <label for="due_date">Due Date:</label>
          <input type="date" name="due_date" required>
          <div class="button-container">
            <input type="submit" value="Add Book">
            <button onclick="location.href='borrowedbook&reservation.html'">Cancel</button>
          </div>
        </form>
    </div>
</body>
</html>
