<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: GET");
header("Content-Type: application/json");

include '../db.php';

$sql = "SELECT id, name, email, role, created_at FROM users";
$result = $conn->query($sql);

$users = [];
while ($row = $result->fetch_assoc()) {
    $users[] = [
        "id" => (int)$row["id"], // Cast to integer
        "name" => $row["name"],
        "email" => $row["email"],
        "role" => $row["role"],
        "created_at" => $row["created_at"]
    ];
}

echo json_encode([
    "status" => "success",
    "message" => "Users retrieved successfully",
    "data" => $users
]);
?>
