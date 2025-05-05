<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET");
header("Content-Type: application/json");

include '../db.php';

$sql = "SELECT id, class_name, class_description, class_duration, teacher_id, room_number, remark FROM classes";
$result = $conn->query($sql);

$classes = [];
while ($row = $result->fetch_assoc()) {
    $classes[] = [
        'id' => (int)$row['id'],
        'class_name' => $row['class_name'],
        'class_description' => $row['class_description'],
        'class_duration' => $row['class_duration'],
        'teacher_id' => (int)$row['teacher_id'],
        'room_number' => $row['room_number'],
        'remark' => $row['remark'],
    ];
}

echo json_encode([
    "status" => "success",
    "message" => "Classes retrieved successfully",
    "data" => $classes
]);
?>
