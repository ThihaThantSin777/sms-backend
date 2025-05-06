<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json");

include '../db.php';

// 1. Extract user + teacher fields
$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$phone = $_POST['phone'] ?? '';
$password = $_POST['password'] ?? '';
$specialization = $_POST['specialization'] ?? '';
$joined_date = $_POST['joined_date'] ?? '';
$qualification = $_POST['qualification'] ?? '';
$experience_years = $_POST['experience_years'] ?? '';
$status = $_POST['status'] ?? 'active';
$role = 'teacher'; // force teacher role

if (empty($name) || empty($email) || empty($password) || empty($specialization) || empty($joined_date) || empty($qualification) || empty($experience_years)) {
    echo json_encode(["status" => "error", "message" => "All fields are required"]);
    exit;
}

// 2. Hash the password securely
$hashed_password = password_hash($password, PASSWORD_BCRYPT);

// 3. Insert into users table
$userStmt = $conn->prepare("INSERT INTO users (name, email, phone, password, role, status) VALUES (?, ?, ?, ?, ?, ?)");
if (!$userStmt) {
    echo json_encode(["status" => "error", "message" => "Prepare failed (users): " . $conn->error]);
    exit;
}
$userStmt->bind_param("ssssss", $name, $email, $phone, $hashed_password, $role, $status);

if (!$userStmt->execute()) {
    echo json_encode(["status" => "error", "message" => "Execute failed (users): " . $userStmt->error]);
    $userStmt->close();
    exit;
}
$user_id = $userStmt->insert_id;
$userStmt->close();

// 4. Insert into teachers table
$teacherStmt = $conn->prepare("INSERT INTO teachers (user_id, specialization, joined_date, qualification, experience_years, status) VALUES (?, ?, ?, ?, ?, ?)");
if (!$teacherStmt) {
    echo json_encode(["status" => "error", "message" => "Prepare failed (teachers): " . $conn->error]);
    exit;
}
$teacherStmt->bind_param("isssis", $user_id, $specialization, $joined_date, $qualification, $experience_years, $status);

if ($teacherStmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Teacher created successfully"]);
} else {
    echo json_encode(["status" => "error", "message" => "Execute failed (teachers): " . $teacherStmt->error]);
}
$teacherStmt->close();
?>
