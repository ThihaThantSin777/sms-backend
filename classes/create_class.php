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
$class_duration = $_POST['class_duration'] ?? '';
$teacher_id = isset($_POST['teacher_id']) ? intval($_POST['teacher_id']) : 0;
$room_number = $_POST['room_number'] ?? '';
$remark = $_POST['remark'] ?? '';

// 🔍 Detailed field-by-field validation
if (empty($class_name)) {
    echo json_encode(["status" => "error", "message" => "Class name is required"]);
    exit;
}
if (empty($class_description)) {
    echo json_encode(["status" => "error", "message" => "Class description is required"]);
    exit;
}
if (empty($class_duration)) {
    echo json_encode(["status" => "error", "message" => "Class duration is required"]);
    exit;
}
if (empty($teacher_id)) {
    echo json_encode(["status" => "error", "message" => "Teacher ID is required"]);
    exit;
}
if (empty($room_number)) {
    echo json_encode(["status" => "error", "message" => "Room number is required"]);
    exit;
}
if (empty($remark)) {
    echo json_encode(["status" => "error", "message" => "Remark is required"]);
    exit;
}

// ✅ Optional: check if teacher_id exists in teachers table
$check = $conn->prepare("SELECT id FROM teachers WHERE id = ?");
$check->bind_param("i", $teacher_id);
$check->execute();
$check->store_result();

if ($check->num_rows === 0) {
    echo json_encode(["status" => "error", "message" => "Teacher ID does not exist"]);
    exit;
}
$check->close();

// ✅ Insert class
$sql = "INSERT INTO classes (class_name, class_description, class_duration, teacher_id, room_number, remark)
        VALUES (?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
if ($stmt) {
    $stmt->bind_param("sssiss", $class_name, $class_description, $class_duration, $teacher_id, $room_number, $remark);
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
