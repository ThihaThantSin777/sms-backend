<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json");

include '../db.php';

$id = $_POST['id'] ?? '';
$date_of_birth = $_POST['date_of_birth'] ?? '';
$class_id = $_POST['class_id'] ?? '';
$gender = $_POST['gender'] ?? '';
$address = $_POST['address'] ?? '';
$guardian_name = $_POST['guardian_name'] ?? '';

if (empty($id) || empty($date_of_birth) || empty($class_id)|| empty($gender)) {
    echo json_encode(["status" => "error", "message" => "Required fields are missing"]);
    exit;
}

$stmt = $conn->prepare("UPDATE students SET date_of_birth = ?, class_id = ?, gender = ?, address = ?, guardian_name = ? WHERE id = ?");

if ($stmt) {
    $stmt->bind_param("sissssi", $date_of_birth, $class_id, $gender, $address, $guardian_name, $id);
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
