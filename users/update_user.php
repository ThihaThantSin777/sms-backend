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
$email = $_POST['email'] ?? '';
$role = $_POST['role'] ?? '';

if (empty($id) || empty($name) || empty($email)) {
    echo json_encode(["status" => "error", "message" => "ID, Name, and Email are required"]);
    exit;
}

$sql = "UPDATE users SET name='$name', email='$email', role='$role' WHERE id='$id'";

if ($conn->query($sql)) {
    echo json_encode(["status" => "success", "message" => "User updated successfully", "data" => null]);
} else {
    echo json_encode(["status" => "error", "message" => $conn->error]);
}
?>
