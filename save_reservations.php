<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reservations = json_decode(file_get_contents('php://input'), true);

    $response = [
        'success' => true,
        'message' => 'Reservations saved successfully!'
    ];

    try {
        $conn->begin_transaction();

        foreach ($reservations as $reservation) {
            $stmt = $conn->prepare("INSERT INTO library_places (student_id, place_name, reservation_date, reserved_chairs, reserved_hours) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param(
                "issii",
                $_SESSION['user_id'],
                $reservation['area'],
                $reservation['reservation_time'],
                $reservation['chairs'],
                $reservation['hours']
            );

            if (!$stmt->execute()) {
                throw new Exception($stmt->error);
            }

            $stmt->close();
        }

        $conn->commit();
    } catch (Exception $e) {
        $conn->rollback();
        $response['success'] = false;
        $response['message'] = 'Error saving reservations: ' . $e->getMessage();
    }

    $conn->close();

    header('Content-Type: application/json');
    echo json_encode($response);
}
?>
