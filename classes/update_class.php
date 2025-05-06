<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json");

include '../db.php';

$id = $_POST['id'] ?? '';
$class_name = $_POST['class_name'] ?? '';
$class_description = $_POST['class_description'] ?? '';
$teacher_id = $_POST['teacher_id'] ?? '';
$start_time = $_POST['start_time'] ?? '';
$end_time = $_POST['end_time'] ?? '';
$duration_months = $_POST['duration_months'] ?? '';
$max_students = $_POST['max_students'] ?? '';
$class_level = $_POST['class_level'] ?? '';

if (empty($id) || empty($class_name) || empty($class_description) || empty($teacher_id) || empty($start_time) || empty($end_time) || empty($duration_months) || empty($max_students) || empty($class_level)) {
    echo json_encode(["status" => "error", "message" => "All fields are required"]);
    exit;
}

$stmt = $conn->prepare("UPDATE classes SET class_name = ?, class_description = ?, teacher_id = ?, start_time = ?, end_time = ?, duration_months = ?, max_students = ?, class_level = ? WHERE id = ?");

if ($stmt) {
    $stmt->bind_param("ssisssisi", $class_name, $class_description, $teacher_id, $start_time, $end_time, $duration_months, $max_students, $class_level, $id);
    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Class updated successfully", "data" => null]);
    } else {
        echo json_encode(["status" => "error", "message" => "Execution failed: " . $stmt->error]);
    }
    $stmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "Prepare failed: " . $conn->error]);
}
?>
