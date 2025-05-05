<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET");
header("Content-Type: application/json");

include '../db.php';

$sql = "SELECT id, name, email, phone, specialization, joined_date FROM teachers";
$result = $conn->query($sql);

$teachers = [];
while ($row = $result->fetch_assoc()) {
    $teachers[] = [
        "id" => (int)$row["id"],
        "name" => $row["name"],
        "email" => $row["email"],
        "phone" => $row["phone"],
        "specialization" => $row["specialization"],
        "joined_date" => $row["joined_date"],
    ];
}

echo json_encode([
    "status" => "success",
    "message" => "Teachers retrieved successfully",
    "data" => $teachers
]);
?>
