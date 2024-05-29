<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Computer Laboratory Reservation</title>
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
    <div class="popup-form" id="computer-form">
        <div class="close-btn">&times;</div>
        <div class="form">
            <h2>Computer Laboratory Reservation</h2>
            <form id="computer-lab-form" onsubmit="reserveComputer(); return false;">
                <label for="reserved_by">Name:</label>
                <select id="reserved_by_computer" name="reserved_by" required>
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
                <input type="datetime-local" id="reservation_time_computer" name="reservation_time" required>
                
                <label for="computer_number">Computer Number:</label>
                <select id="computer_number" name="computer_number" required>
                    <option value="">Select computer number</option>
                    <?php
                    // Option values sorted numerically
                    for ($i = 1; $i <= 10; $i++) {
                        echo '<option value="' . $i . '">' . $i . '</option>';
                    }
                    ?>
                </select>
    
                <label for="duration">Duration (in hours):</label>
                <input type="number" id="duration_computer" name="duration" min="1" max="12" required>
                
                <button type="submit">Reserve</button>
                <button type="reset">Cancel</button>
            </form>
        </div>
    </div>

    <script>
        // JavaScript code to handle form submission via AJAX
        function reserveComputer() {
            var reservedByName = document.getElementById("reserved_by_computer").value;
            var reservationTime = document.getElementById("reservation_time_computer").value;
            var computerNumber = document.getElementById("computer_number").value;
            var duration = document.getElementById("duration_computer").value;

            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState === XMLHttpRequest.DONE) {
                    if (xhr.status === 200) {
                        alert("Reservation successful!");
                    } else {
                        alert("Reservation failed: " + xhr.responseText);
                    }
                }
            };
            xhr.open("POST", "insert_reservation.php");
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.send("reserved_by=" + encodeURIComponent(reservedByName) + "&reservation_time=" + encodeURIComponent(reservationTime) + "&computer_number=" + encodeURIComponent(computerNumber) + "&duration=" + encodeURIComponent(duration));
        }
    </script>
</body>
</html>
