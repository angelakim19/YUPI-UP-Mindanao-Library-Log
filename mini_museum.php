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
    margin: 0;
    padding: 0;
}

/* Header styles */
h2 {
    color: #333;
    text-align: center;
    font-weight: bolder;
}

h3 {
    color: #555;
    margin-left: 50px;
    color: black;
}

/* Table styles */
table {
    width: 80%;
    border-collapse: collapse;
    margin-bottom: 20px;
    margin-left: 80px;
}

th, td {
    padding: 8px;
    text-align: left;
    border-bottom: 1px solid #ddd;
}

th {
    background-color: #f2f2f2;
}

/* Button styles */
button {
    padding: 8px 16px;
    border: none;
    background-color: #007bff;
    color: #fff;
    cursor: pointer;
    border-radius: 4px;
    margin-right: 8px;
    margin-bottom: 10px;
    background-color: darkgreen;
}

button:hover {
    background-color: maroon;
}

/* Button container styles */
.button-container {
    margin-top: 20px;
}

/* Link styles */
a {
    text-decoration: none;
    color: #007bff;
}

a:hover {
    text-decoration: underline;
}

/* Button container styles */
.button-container {
    margin-top: 10px;
    text-align: center;
}

/* Button styles */
.button-container button {
    padding: 10px 20px;
    border: none;
    background-color: #007bff;
    color: #fff;
    cursor: pointer;
    border-radius: 5px;
    margin-right: 60px; 
    transition: background-color 0.3s ease;
}

.button-container button:hover {
    background-color: maroon;
}

/* Button specific styles */
.button-container button:nth-of-type(1) {
    background-color: #28a745; /* Green for Add New Reservation */
}

.button-container button:nth-of-type(2) {
    background-color: #28a745; /* Yellow for Library Places */
}

.button-container button:nth-of-type(3) {
    background-color: #28a745; /* Red for Admin Dashboard */
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

// Define the number of tables
$numTables = 2;

// Define the number of chairs for each table
$tableChairs = array(7, 7);

// Displaying reservations for each table in the admin panel
echo "<h2>Museum Reservations</h2>";

for ($i = 1; $i <= $numTables; $i++) {
    echo "<h3>Table $i</h3>";
    echo "<table border='1'>";
    echo "<tr><th>Reservation ID</th><th>Reserved By</th><th>Reservation Time</th><th>Hours</th><th>End Time</th><th>Chairs</th><th>Occupied Seats</th><th>Available Seats</th><th>Total Seats</th><th>Action</th></tr>";

    // Fetch and calculate occupied seats for the current table
    $sqlOccupied = "SELECT SUM(chairs) AS occupied_seats FROM museum_reservations WHERE table_number = $i";
    $resultOccupied = $conn->query($sqlOccupied);

    if ($resultOccupied->num_rows > 0) {
        $rowOccupied = $resultOccupied->fetch_assoc();
        $occupiedSeats = $rowOccupied['occupied_seats'];
    } else {
        $occupiedSeats = 0;
    }

    // Calculate total seats for the current table
    $totalSeats = $tableChairs[$i - 1];

    // Calculate available seats for the current table
    $availableSeats = $totalSeats - $occupiedSeats;

    // SQL query to fetch reservations for the current table
    $sql = "SELECT museum_reservations.*, CONCAT(users.lastname, ', ', users.firstname, ' ', users.middlename) AS fullname 
            FROM museum_reservations 
            JOIN users ON museum_reservations.reserved_by = users.id 
            WHERE museum_reservations.table_number = $i";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $endTime = calculateEndTime($row["reservation_time"], $row["hour"]); // Calculate end time
            echo "<tr>";
            echo "<td>" . $row["reservation_id"] . "</td>";
            echo "<td>" . $row["fullname"] . "</td>";
            echo "<td>" . $row["reservation_time"] . "</td>";
            echo "<td>" . $row["hour"] . "</td>";
            echo "<td>" . $endTime . "</td>";
            echo "<td>" . $row["chairs"] . "</td>";
            echo "<td>" . $occupiedSeats . "</td>"; // Occupied seats
            echo "<td>" . $availableSeats . "</td>"; // Available seats
            echo "<td>" . $totalSeats . "</td>"; // Total Seats
            
            // Edit and Delete buttons
            echo "<td><a href='edit_museum_reservation.php?reservation_id=" . $row['reservation_id'] . "'><button>Edit</button></a> <br>
            <a href='delete_museum_reservation.php?reservation_id=" . $row['reservation_id'] . "'><button>Delete</button></a> <br></td>";

            echo "</tr>"; // Close the table row
        }
    } else {
        echo "<td colspan='10'>No reservations for this table</td>";
    }
    
    // Close the table
    echo "</table><br>";
}

echo "<div class='button-container'><br>";
echo "<button onclick=\"window.location.href = 'add_museum_reservation.php'\">Add New Reservation</button>";
echo "<button onclick=\"window.location.href = 'library_places.html'\">Library Places</button>";
echo "<button onclick=\"window.location.href = 'admin_dashboard.html'\">Admin Dashboard</button>";
echo "</div>";


// Close connection
$conn->close();
?>
</body>
</html>