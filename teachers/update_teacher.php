<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json");

include '../db.php';

$id = $_POST['id'] ?? '';
$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$phone = $_POST['phone'] ?? '';
$specialization = $_POST['specialization'] ?? '';
$joined_date = $_POST['joined_date'] ?? '';

if (empty($id) || empty($name) || empty($email) || empty($phone) || empty($specialization) || empty($joined_date)) {
    echo json_encode(["status" => "error", "message" => "All fields are required"]);
    exit;
}

$sql = "UPDATE teachers SET 
        name = ?, 
        email = ?, 
        phone = ?, 
        specialization = ?, 
        joined_date = ?
        WHERE id = ?";

$stmt = $conn->prepare($sql);
if ($stmt) {
    $stmt->bind_param("sssssi", $name, $email, $phone, $specialization, $joined_date, $id);
    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Teacher updated successfully", "data" => null]);
    } else {
        echo json_encode(["status" => "error", "message" => "Execution failed: " . $stmt->error]);
    }
    $stmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "Prepare failed: " . $conn->error]);
}
?>
