<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET");
header("Content-Type: application/json");

include '../db.php';

$sql = "SELECT id, name, email, phone, date_of_birth, class_id FROM students";
$result = $conn->query($sql);

$students = [];
while ($row = $result->fetch_assoc()) {
    $students[] = [
        'id' => (int)$row['id'],
        'name' => $row['name'],
        'email' => $row['email'],
        'phone' => $row['phone'],
        'date_of_birth' => $row['date_of_birth'],
        'class_id' => (int)$row['class_id']
    ];
}

echo json_encode([
    "status" => "success",
    "message" => "Students retrieved successfully",
    "data" => $students
]);
?>
