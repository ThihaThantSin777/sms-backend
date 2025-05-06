<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json");

include '../db.php';

$name = $_POST['name'] ?? '';
$phone = $_POST['phone'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';
$role = $_POST['role'] ?? 'staff';
$status = $_POST['status'] ?? 'active';

if (empty($name) || empty($email) || empty($password) || empty($phone)) {
    echo json_encode(["status" => "error", "message" => "All fields are required"]);
    exit;
}

$hashed_password = password_hash($password, PASSWORD_BCRYPT);

$stmt = $conn->prepare("INSERT INTO users (name, phone, email, password, role, status, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");

if ($stmt) {
    $stmt->bind_param("ssssss", $name, $phone, $email, $hashed_password, $role, $status);
    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "User created successfully", "data" => null]);
    } else {
        echo json_encode(["status" => "error", "message" => "Execute failed: " . $stmt->error]);
    }
    $stmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "Prepare failed: " . $conn->error]);
}
?>
