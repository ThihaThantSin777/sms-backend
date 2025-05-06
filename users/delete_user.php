<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json");

include '../db.php';

$id = $_POST['id'] ?? '';
if (empty($id)) {
    echo json_encode(["status" => "error", "message" => "ID is required"]);
    exit;
}

// Check role
$stmt = $conn->prepare("SELECT role FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if (!$user) {
    echo json_encode(["status" => "error", "message" => "User not found"]);
    exit;
}

// Delete from foreign tables
if ($user['role'] === 'student') {
    $stmt = $conn->prepare("DELETE FROM students WHERE user_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
} elseif ($user['role'] === 'teacher') {
    $stmt = $conn->prepare("DELETE FROM teachers WHERE user_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
}

// Delete user itself
$stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
$stmt->bind_param("i", $id);
if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "User and related data deleted"]);
} else {
    echo json_encode(["status" => "error", "message" => "User delete failed: " . $stmt->error]);
}
$stmt->close();
?>
