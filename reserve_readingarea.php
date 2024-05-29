<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    die("User not logged in");
}

// Include the database connection
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = $_SESSION['user_id'];
    $reservation_time = $_POST['reservation_time'];
    $table_number = $_POST['table_number'];
    $chairs = $_POST['chairs'];
    $hours = $_POST['hours'];

    $stmt = $conn->prepare("INSERT INTO reading_area (reserved_by, reservation_time, hour, table_number, chairs, available, occupied, end_time) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $available = 1;
    $occupied = 0;
    $end_time = NULL;
    $stmt->bind_param("isiiibis", $student_id, $reservation_time, $hours, $table_number, $chairs, $available, $occupied, $end_time);

    if ($stmt->execute()) {
        echo "Reservation successful!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
