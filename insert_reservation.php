<?php
// Check if the POST data is set
if (isset($_POST['reserved_by'], $_POST['reservation_time'], $_POST['computer_number'], $_POST['duration'])) {
    // Retrieve the POST data
    $reserved_by = $_POST['reserved_by'];
    $reservation_time = $_POST['reservation_time'];
    $computer_number = $_POST['computer_number'];
    $duration = $_POST['duration'];

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
    $stmt = $conn->prepare("INSERT INTO computer_laboratory (UserId, ReservationTime, ComputerNumber, Duration) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isii", $reserved_by, $reservation_time, $computer_number, $duration);

    // Execute SQL statement
    if ($stmt->execute()) {
        echo "Reservation successful!";

        // Update status to "reserved"
        $update_stmt = $conn->prepare("UPDATE computer_laboratory SET Status = 'reserved' WHERE ComputerNumber = ?");
        $update_stmt->bind_param("i", $computer_number);
        if ($update_stmt->execute()) {
            echo "Status updated successfully!";
        } else {
            echo "Error updating status: " . $update_stmt->error;
        }
        $update_stmt->close();
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close statement and database connection
    $stmt->close();
    $conn->close();
} else {
    // POST data is not set
    echo "Incomplete data provided.";
}
?>
