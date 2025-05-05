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
$phone = $_POST['phone'] ?? '';
$date_of_birth = $_POST['date_of_birth'] ?? '';
$class_id = $_POST['class_id'] ?? '';

if (empty($id) || empty($name) || empty($email) || empty($phone) || empty($date_of_birth) || empty($class_id)) {
    echo json_encode(["status" => "error", "message" => "All fields are required"]);
    exit;
}

$sql = "UPDATE students SET 
        name = ?, 
        email = ?, 
        phone = ?, 
        date_of_birth = ?, 
        class_id = ?
        WHERE id = ?";

$stmt = $conn->prepare($sql);
if ($stmt) {
    $stmt->bind_param("ssssii", $name, $email, $phone, $date_of_birth, $class_id, $id);
    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Student updated successfully", "data" => null]);
    } else {
        echo json_encode(["status" => "error", "message" => "Execution failed: " . $stmt->error]);
    }
    $stmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "Prepare failed: " . $conn->error]);
}
?>
