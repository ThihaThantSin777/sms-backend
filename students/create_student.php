<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json");

include '../db.php';

// User data
$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$phone = $_POST['phone'] ?? '';
$password = $_POST['password'] ?? '';
$role = 'student';
$status = 'active';

// Student data
$date_of_birth = $_POST['date_of_birth'] ?? '';
$class_id = $_POST['class_id'] ?? '';
$gender = $_POST['gender'] ?? '';
$address = $_POST['address'] ?? '';
$guardian_name = $_POST['guardian_name'] ?? '';

// Validate required fields
if (empty($name) || empty($email) || empty($password) || empty($date_of_birth) || empty($class_id) || empty($gender)) {
    echo json_encode(["status" => "error", "message" => "Required fields are missing"]);
    exit;
}


// Hash password securely
$hashed_password = password_hash($password, PASSWORD_BCRYPT);

// Insert into users table
$userStmt = $conn->prepare("INSERT INTO users (name, email, phone, password, role, status) VALUES (?, ?, ?, ?, ?, ?)");
if ($userStmt) {
    $userStmt->bind_param("ssssss", $name, $email, $phone, $hashed_password, $role, $status);
    if ($userStmt->execute()) {
        $user_id = $conn->insert_id;

        // Insert into students table
        $studentStmt = $conn->prepare("INSERT INTO students (user_id, date_of_birth, class_id, gender, address, guardian_name) VALUES (?, ?, ?, ?, ?, ?)");
        if ($studentStmt) {
            // Fix bind_param: "isisss" matches correct types
            $studentStmt->bind_param("isisss", $user_id, $date_of_birth, $class_id, $gender, $address, $guardian_name);
            if ($studentStmt->execute()) {
                echo json_encode(["status" => "success", "message" => "Student created successfully", "user_id" => $user_id]);
            } else {
                echo json_encode(["status" => "error", "message" => "Student insert failed: " . $studentStmt->error]);
            }
            $studentStmt->close();
        } else {
            echo json_encode(["status" => "error", "message" => "Prepare failed (student): " . $conn->error]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "User insert failed: " . $userStmt->error]);
    }
    $userStmt->close();
} else {
    echo json_encode(["status" => "error", "message" => "Prepare failed (user): " . $conn->error]);
}
?>
