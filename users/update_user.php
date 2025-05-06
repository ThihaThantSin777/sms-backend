<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json");

include '../db.php';

$id = $_POST['id'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($id)) {
    echo json_encode(["status" => "error", "message" => "User ID is required"]);
    exit;
}

if (!empty($password)) {
    // Only updating password
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    $stmt = $conn->prepare("UPDATE users SET password=? WHERE id=?");
    if ($stmt) {
        $stmt->bind_param("si", $hashedPassword, $id);
    }
} else {
    // Updating other fields
    $name = $_POST['name'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $email = $_POST['email'] ?? '';
    $role = $_POST['role'] ?? '';
    $status = $_POST['status'] ?? '';

    if (empty($name) || empty($email) || empty($phone)) {
        echo json_encode(["status" => "error", "message" => "Name, Phone, and Email are required if not updating password only"]);
        exit;
    }

    $stmt = $conn->prepare("UPDATE users SET name=?, phone=?, email=?, role=?, status=? WHERE id=?");
    if ($stmt) {
        $stmt->bind_param("sssssi", $name, $phone, $email, $role, $status, $id);
    }
}

if ($stmt) {
    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "User updated successfully"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Execute failed: " . $stmt->error]);
    }
    $stmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "Prepare failed: " . $conn->error]);
}
?>
