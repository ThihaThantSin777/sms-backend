<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json");

include '../db.php';

$id = $_POST['id'] ?? '';
$name = $_POST['name'] ?? '';
$phone = $_POST['phone'] ?? '';
$email = $_POST['email'] ?? '';
$role = $_POST['role'] ?? '';
$status = $_POST['status'] ?? '';

if (empty($id) || empty($name) || empty($email) || empty($phone)) {
    echo json_encode(["status" => "error", "message" => "ID, Name, Phone, and Email are required"]);
    exit;
}

$stmt = $conn->prepare("UPDATE users SET name=?, phone=?, email=?, role=?, status=? WHERE id=?");

if ($stmt) {
    $stmt->bind_param("sssssi", $name, $phone, $email, $role, $status, $id);
    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "User updated successfully", "data" => null]);
    } else {
        echo json_encode(["status" => "error", "message" => "Execute failed: " . $stmt->error]);
    }
    $stmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "Prepare failed: " . $conn->error]);
}
?>
