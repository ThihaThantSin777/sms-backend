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
$class_duration = $_POST['class_duration'] ?? '';
$teacher_id = $_POST['teacher_id'] ?? '';
$room_number = $_POST['room_number'] ?? '';
$remark = $_POST['remark'] ?? '';

if (empty($id) || empty($class_name) || empty($class_description) || empty($class_duration) || empty($teacher_id) || empty($room_number) || empty($remark)) {
    echo json_encode(["status" => "error", "message" => "All fields are required"]);
    exit;
}

$sql = "UPDATE classes SET 
        class_name = ?, 
        class_description = ?, 
        class_duration = ?, 
        teacher_id = ?, 
        room_number = ?, 
        remark = ?
        WHERE id = ?";

$stmt = $conn->prepare($sql);
if ($stmt) {
    $stmt->bind_param("sssissi", $class_name, $class_description, $class_duration, $teacher_id, $room_number, $remark, $id);
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
