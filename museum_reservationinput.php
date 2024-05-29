<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mini Museum Reservation</title>
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
    <div class="popup-form" id="mini-museum-form">
        <div class="close-btn">&times;</div>
        <div class="form">
            <h2>Mini Museum Reservation</h2>
            <form id="mini-museum-reservation-form" method="post">
                <label for="reserved_by_museum">Name:</label>
                <select id="reserved_by_museum" name="reserved_by" required>
                    <option value="">Select your name</option>
                    <?php
                    // Database connection parameters
                    $servername = "localhost";
                    $username = "root";
                    $password = "your_password"; // Replace with your actual password
                    $dbname = "registration_db";

                    // Create connection
                    $conn = new mysqli($servername, $username, $password, $dbname);

                    // Check connection
                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }

                    // Fetch users from the database and populate dropdown
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

                <label for="reservation_time_museum">Reservation Time:</label>
                <input type="datetime-local" id="reservation_time_museum" name="reservation_time" required>
                
                <label for="table_number_museum">Table Number:</label>
                <select id="table_number_museum" name="table_number" required>
                    <!-- Limit table choices to a maximum of 2 -->
                    <?php
                    for ($i = 1; $i <= 2; $i++) {
                        echo '<option value="' . $i . '">' . $i . '</option>';
                    }
                    ?>
                </select>
                
                <label for="chairs_museum">Number of Chairs:</label>
                <input type="number" id="chairs_museum" name="chairs" min="1" max="1" required>
                
                <label for="hour_museum">Hour:</label>
                <input type="number" id="hour_museum" name="hour" min="1" max="12" required>
                
                <button type="submit" name="reserve">Reserve</button>
                <button type="reset">Cancel</button>
            </form>
        </div>
    </div>

    <?php
// Check if the POST data is set
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reserved_by'], $_POST['reservation_time'], $_POST['table_number'], $_POST['chairs'], $_POST['hour'])) {
    // Retrieve the POST data
    $reserved_by = $_POST['reserved_by'];
    $reservation_time = $_POST['reservation_time'];
    $table_number = $_POST['table_number'];
    $chairs = $_POST['chairs'];
    $hour = $_POST['hour'];

    // Calculate end time
    $end_time = date('Y-m-d H:i:s', strtotime($reservation_time . " + $hour hours"));

    // Database connection parameters
    $servername = "localhost";
    $username = "root";
    $password = "your_password"; // Replace with your actual password
    $dbname = "registration_db";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare SQL statement for insertion
    $stmt = $conn->prepare("INSERT INTO museum_reservations (reserved_by, reservation_time, table_number, chairs, hour, end_time) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isiiis", $reserved_by, $reservation_time, $table_number, $chairs, $hour, $end_time);

    // Execute SQL statement
    if ($stmt->execute()) {
        echo "<script>alert('Reservation successful!');</script>";
    } else {
        echo "<script>alert('Reservation failed: " . $stmt->error . "');</script>";
    }

    // Close statement and database connection
    $stmt->close();
    $conn->close();
}
?>

</body>
</html>
