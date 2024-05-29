<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reading Area Reservation</title>
    <style>
        .popup-form {
            
            display: block;
            position: absolute;
            z-index: 10;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 80%;
            max-width: 600px;
            background-color: white;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
            border-radius: 10px;
            overflow: hidden;
            padding: 20px;
        }

        .close-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            cursor: pointer;
        }

        .form h2 {
            margin-top: 0;
        }

        .form label {
            display: block;
            margin-bottom: 5px;
        }

        .form input[type="text"],
        .form input[type="datetime-local"],
        .form select,
        .form input[type="number"] {
            width: 100%;
            padding: 8px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .form button {
            background-color: #007b70;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .form button[type="reset"] {
            background-color: #ccc;
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <div class="popup-form" id="reading-form">
        <div class="close-btn">&times;</div>
        <div class="form">
            <h2>Reading Area Reservation</h2>
            <form id="reading-area-form" method="post" action="">
                <label for="reserved_by">Name:</label>
                <select id="reserved_by_reading" name="reserved_by" required>
                    <option value="">Select your name</option>
                    <?php
                    // Fetch users from the database and sort by full name
                    $servername = "localhost";
                    $username = "root";
                    $password = "your_password";
                    $dbname = "registration_db";

                    $conn = new mysqli($servername, $username, $password, $dbname);
                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }

                    $sql = "SELECT id, CONCAT(lastname, ', ', firstname, ' ', middlename) AS full_name FROM users ORDER BY full_name";
                    $result = $conn->query($sql);

                    if ($result === false) {
                        echo "Error: " . $conn->error;
                    } else {
                        if ($result->num_rows > 0) {
                            while($row = $result->fetch_assoc()) {
                                echo '<option value="' . $row['id'] . '">' . $row['full_name'] . '</option>';
                            }
                        } else {
                            echo '<option value="">No users found</option>';
                        }
                    }

                    $conn->close();
                    ?>
                </select>

                <label for="reservation_time">Reservation Time:</label>
                <input type="datetime-local" id="reservation_time_reading" name="reservation_time" required>
                
                <label for="table_number">Table Number:</label>
                <select id="table_number_reading" name="table_number" required>
                    <!-- Populate table options here -->
                    <?php
                    // Option values sorted numerically
                    for ($i = 1; $i <= 10; $i++) {
                        echo '<option value="' . $i . '">' . $i . '</option>';
                    }
                    ?>
                </select>
    
                <label for="chairs">Number of Chairs:</label>
                <input type="number" id="chairs_reading" name="chairs" min="1" max="1" required>
                
                <label for="hours">Hours:</label>
                <input type="number" id="hours_reading" name="hours" min="1" max="12" required>
                
                <button type="submit" name="reserve">Reserve</button>
                <button type="reset">Cancel</button>
            </form>
        </div>
    </div>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['reserve'])) {
    $servername = "localhost";  // The hostname of the database server
    $username = "root";  // The username to connect to the database
    $password = "your_password";  // The password to connect to the database
    $dbname = "registration_db";  // The name of the database

    // Create a connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check the connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $reserved_by = $_POST['reserved_by'];
    $reservation_time = $_POST['reservation_time'];
    $table_number = $_POST['table_number'];
    $chairs = $_POST['chairs'];
    $hours = $_POST['hours'];
    $end_time = date('Y-m-d H:i:s', strtotime($reservation_time . " + $hours hours"));

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO reading_area (reserved_by, reservation_time, hour, table_number, chairs, available, occupied, end_time) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $available = true;
    $occupied = false;
    $stmt->bind_param("ssiiiiis", $reserved_by, $reservation_time, $hours, $table_number, $chairs, $available, $occupied, $end_time);

    // Execute the statement
    if ($stmt->execute()) {
        echo '<script>alert("Reservation successful!");</script>';
    } else {
        echo '<script>alert("Reservation failed: ' . $stmt->error . '");</script>';
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>
</body>
</html>
