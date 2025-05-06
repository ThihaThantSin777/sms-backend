<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json");

include '../db.php';

$user_id = $_POST['user_id'] ?? '';
$specialization = $_POST['specialization'] ?? '';
$joined_date = $_POST['joined_date'] ?? '';
$qualification = $_POST['qualification'] ?? '';
$experience_years = $_POST['experience_years'] ?? '';
$status = $_POST['status'] ?? 'Active';

if (empty($user_id) || empty($specialization) || empty($joined_date) || empty($qualification) || empty($experience_years)) {
    echo json_encode(["status" => "error", "message" => "All fields are required"]);
    exit;
}

$stmt = $conn->prepare("INSERT INTO teachers (user_id, specialization, joined_date, qualification, experience_years, status) VALUES (?, ?, ?, ?, ?, ?)");

if ($stmt) {
    $stmt->bind_param("isssis", $user_id, $specialization, $joined_date, $qualification, $experience_years, $status);
    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Teacher created successfully", "data" => null]);
    } else {
        echo json_encode(["status" => "error", "message" => "Execute failed: " . $stmt->error]);
    }
    $stmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "Prepare failed: " . $conn->error]);
}
?>
