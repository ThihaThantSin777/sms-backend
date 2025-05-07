<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET");
header("Content-Type: application/json");

include '../db.php';

$sql = "SELECT t.id, u.name, u.email, u.phone, t.specialization, t.joined_date, t.qualification, t.experience_years
        FROM teachers t
        JOIN users u ON t.user_id = u.id
        WHERE u.status = 'active'";

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
        "qualification" => $row["qualification"],
        "experience_years" => (int)$row["experience_years"],
    ];
}

echo json_encode([
    "status" => "success",
    "message" => "Active teachers retrieved successfully",
    "data" => $teachers
]);
?>
