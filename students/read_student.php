<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET");
header("Content-Type: application/json");

include '../db.php';

$sql = "SELECT s.id, s.user_id, u.name, u.email, u.phone, s.date_of_birth, s.class_id, s.gender, s.address, s.guardian_name 
        FROM students s 
        JOIN users u ON s.user_id = u.id
        WHERE u.status = 'active'";

$result = $conn->query($sql);

$students = [];
while ($row = $result->fetch_assoc()) {
    $students[] = [
        'id' => (int)$row['id'],
        'user_id' => (int)$row['user_id'],
        'name' => $row['name'],
        'email' => $row['email'],
        'phone' => $row['phone'],
        'date_of_birth' => $row['date_of_birth'],
        'class_id' => (int)$row['class_id'],
        'gender' => $row['gender'],
        'address' => $row['address'],
        'guardian_name' => $row['guardian_name']
    ];
}

echo json_encode([
    "status" => "success",
    "message" => "Active students retrieved successfully",
    "data" => $students
]);
?>
