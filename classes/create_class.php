<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json");

include '../db.php';

$class_name = $_POST['class_name'] ?? '';
$class_description = $_POST['class_description'] ?? '';
$teacher_id = isset($_POST['teacher_id']) ? intval($_POST['teacher_id']) : 0;
$start_time = $_POST['start_time'] ?? '';
$end_time = $_POST['end_time'] ?? '';
$duration_months = $_POST['duration_months'] ?? '';
$max_students = $_POST['max_students'] ?? '';
$class_level = $_POST['class_level'] ?? '';

if (empty($class_name) || empty($class_description) || empty($teacher_id) || empty($start_time) || empty($end_time) || empty($duration_months) || empty($max_students) || empty($class_level)) {
    echo json_encode(["status" => "error", "message" => "All fields are required"]);
    exit;
}

// Check if teacher exists
$check = $conn->prepare("SELECT id FROM teachers WHERE id = ?");
$check->bind_param("i", $teacher_id);
$check->execute();
$check->store_result();

if ($check->num_rows === 0) {
    echo json_encode(["status" => "error", "message" => "Teacher ID does not exist"]);
    exit;
}
$check->close();

$stmt = $conn->prepare("INSERT INTO classes (class_name, class_description, teacher_id, start_time, end_time, duration_months, max_students, class_level) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

if ($stmt) {
    $stmt->bind_param("ssisssis", $class_name, $class_description, $teacher_id, $start_time, $end_time, $duration_months, $max_students, $class_level);
    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Class created successfully", "data" => null]);
    } else {
        echo json_encode(["status" => "error", "message" => "Execution failed: " . $stmt->error]);
    }
    $stmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "Prepare failed: " . $conn->error]);
}
?>
