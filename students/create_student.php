<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json");

include '../db.php';

$user_id = $_POST['user_id'] ?? '';
$date_of_birth = $_POST['date_of_birth'] ?? '';
$class_id = $_POST['class_id'] ?? '';
$roll_number = $_POST['roll_number'] ?? '';
$gender = $_POST['gender'] ?? '';
$address = $_POST['address'] ?? '';
$guardian_name = $_POST['guardian_name'] ?? '';

if (empty($user_id) || empty($date_of_birth) || empty($class_id) || empty($roll_number) || empty($gender)) {
    echo json_encode(["status" => "error", "message" => "Required fields are missing"]);
    exit;
}

$stmt = $conn->prepare("INSERT INTO students (user_id, date_of_birth, class_id, roll_number, gender, address, guardian_name) VALUES (?, ?, ?, ?, ?, ?, ?)");

if ($stmt) {
    $stmt->bind_param("issssss", $user_id, $date_of_birth, $class_id, $roll_number, $gender, $address, $guardian_name);
    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Student created successfully", "data" => null]);
    } else {
        echo json_encode(["status" => "error", "message" => "Execution failed: " . $stmt->error]);
    }
    $stmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "Prepare failed: " . $conn->error]);
}
?>
