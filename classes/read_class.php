<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET");
header("Content-Type: application/json");

include '../db.php';

$sql = "SELECT id, class_name, class_description, teacher_id, start_time, end_time, duration_months, max_students, class_level FROM classes";
$result = $conn->query($sql);

$classes = [];
while ($row = $result->fetch_assoc()) {
    $classes[] = [
        'id' => (int)$row['id'],
        'class_name' => $row['class_name'],
        'class_description' => $row['class_description'],
        'teacher_id' => (int)$row['teacher_id'],
        'start_time' => $row['start_time'],
        'end_time' => $row['end_time'],
        'duration_months' => (int)$row['duration_months'],
        'max_students' => (int)$row['max_students'],
        'class_level' => $row['class_level']
    ];
}

echo json_encode([
    "status" => "success",
    "message" => "Classes retrieved successfully",
    "data" => $classes
]);
?>
