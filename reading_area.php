<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reading Area Reservations</title>
    <style>
    /* Global styles */
    body {
        font-family: Arial, sans-serif;
        background-color: whitesmoke;
        margin: 0;
        padding: 10px;
    }

    h2 {
        text-align: center;
        color: black;
        font-weight: bold;
        
    }

    h3 {
        margin-left: 90px;
        color: black;
        font-weight: bold;
        
    }

    /* Table styles */
    table {
        width: 80%;
        border-collapse: collapse;
        margin-bottom: 20px;
        text-align: center;
        margin-left: 100px;
    }

    th, td {
        border: 1px solid #ddd;
        padding: 6px; /* Adjusted padding */
        text-align: center;
        font-size: 14px; /* Adjusted font size */
    }

    th {
        background-color: white;
    }

    /* Button styles */
    button {
        background-color: #28a745;
        color: white;
        border: none;
        border-radius: 4px;
        padding: 10px 20px;
        cursor: pointer;
        margin-bottom: 10px;
    }

    button:hover {
        background-color: maroon;
    }

   
    .center {
        text-align: center;
    }
    </style>

</head>
<body>
    <?php
    // Database connection parameters
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "registration_db";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Function to calculate the time when the user will leave
    function calculateEndTime($startTime, $hours) {
        $endTime = strtotime($startTime) + ($hours * 3600); // Convert hours to seconds
        return date('Y-m-d H:i:s', $endTime);
    }

    // Function to release expired reservations and update available seats
    function releaseExpiredReservations($conn) {

        // Get current date and time
        $currentDateTime = date('Y-m-d H:i:s');

        // SQL query to identify expired reservations
        $sql = "SELECT * FROM reading_area WHERE end_time < '$currentDateTime'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                // Get the number of chairs reserved in the expired reservation
                $reservedChairs = $row["chairs"];

                // Get the table number for the expired reservation
                $tableNumber = $row["table_number"];

                // Update the available seats by releasing the chairs
                $sqlUpdate = "UPDATE reading_area SET available = available + $reservedChairs WHERE table_number = $tableNumber";
                $conn->query($sqlUpdate);

                // Remove the expired reservation from the database
                $reservationId = $row['reservation_id'];
                $sqlDelete = "DELETE FROM reading_area WHERE reservation_id = $reservationId";
                $conn->query($sqlDelete);
            }
        }
    }

    // Call the function to release expired reservations
    releaseExpiredReservations($conn);

    // Define the number of tables
    $numTables = 10;

    // Define the number of chairs for each table
    $tableChairs = array(6, 2, 8, 6, 8, 3, 1, 1, 1, 1);

    // Initialize arrays to store occupied and available seats for each table
    $occupiedSeats = array();
    $availableSeats = array();

    // Fetch and calculate occupied and available seats for each table
    for ($i = 1; $i <= $numTables; $i++) {
        $occupiedSeats[$i] = 0;
        $availableSeats[$i] = $tableChairs[$i - 1]; // Subtracting 1 to match array index

        // SQL query to fetch reservations for the current table
        $sql = "SELECT * FROM reading_area WHERE table_number = $i";
        $result = $conn->query($sql);

        // Calculate occupied seats for the current table
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $occupiedSeats[$i] += $row["chairs"];
            }
            // Calculate available seats for the current table
            $availableSeats[$i] -= $occupiedSeats[$i];
        }
    }

    // Displaying reservations for each table in the admin panel
    echo "<h2>READING AREA RESERVATIONS</h2>";

    // Display all tables vertically
    for ($i = 1; $i <= $numTables; $i++) {
        echo "<h3>Table $i</h3>";
        echo "<table border='1'>";
        echo "<tr><th>Reservation ID</th><th>Reserved By</th><th>Reservation Time</th><th>Hours</th><th>End Time</th><th>Chairs</th><th>Occupied Seats</th><th>Available Seats</th><th>Total Seat</th><th>Action</th></tr>";

        // Calculate total seats for the current table
        $totalSeats = $tableChairs[$i - 1];

        // SQL query to fetch reservations for the current table
        $sql = "SELECT r.*, CONCAT(u.lastname, ', ', u.firstname, ' ', u.middlename) as fullname FROM reading_area r JOIN users u ON r.reserved_by = u.id WHERE table_number = $i";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $endTime = calculateEndTime($row["reservation_time"], $row["hour"]); // Calculate end time
                echo "<tr>";
                echo "<td>" . $row["reservation_id"] . "</td>";
                echo "<td>" . $row["fullname"] . "</td>";
                echo "<td>" . $row["reservation_time"] . "</td>";            
                echo "<td>" . $row["hour"] . "</td>";
                echo "<td>" . $endTime . "</td>";
                echo "<td>" . $row["chairs"] . "</td>";
                echo "<td>" . $occupiedSeats[$i] . "</td>"; // Occupied seats
                echo "<td>" . $availableSeats[$i] . "</td>"; // Available seats
                echo "<td>" . $totalSeats . "</td>"; // Total Seats
            

                // Edit and Delete buttons
                echo "<td><a href='edit_reading_area.php?reservation_id=" . $row['reservation_id'] . "'><button>Edit</button></a> 
                <br>
                <a href='delete_reading_area.php?reservation_id=" . $row['reservation_id'] . "'><button>Delete</button></a> 
                <br>";

                echo "</tr>"; // Close the table row
            }
        } else {
            echo "<tr><td colspan='8'>No reservations for this table</td></tr>";
        }

        // Close the table
        echo "</table><br>";
    }

    // Close connection
    $conn->close();
    ?>
    
    <div style='display: flex; justify-content: center; gap: 60px; color:maroon;'>
        <a href='add_reading_area.php'><button>Add</button></a>
        <a href='Library_places.html'><button>Back to Library Spaces</button></a>
    </div>



</body>
</html>

