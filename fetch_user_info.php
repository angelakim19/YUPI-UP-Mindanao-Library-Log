<?php
include 'db.php';

session_start();
$user_id = $_SESSION['user_id'] ?? 1; // Replace with actual user ID retrieval logic

$sql = "SELECT firstname, middlename, lastname, studentnumber FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user_info = $result->fetch_assoc();

echo json_encode($user_info);

$stmt->close();
$conn->close();
?>
