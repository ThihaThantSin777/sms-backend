<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
session_start();
include 'db.php';

header('Content-Type: application/json');

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

// Basic validation
if (empty($email) || empty($password)) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Email and password are required.'
    ]);
    exit;
}

$sql = "SELECT * FROM users WHERE email = ? LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows === 1) {
    $user = $result->fetch_assoc();

    if ($user['status'] !== 'active') {
        echo json_encode([
            'status' => 'error',
            'message' => 'Your account is inactive. Please contact the administrator.'
        ]);
        exit;
    }

    if (password_verify($password, $user['password'])) {
        $_SESSION['user'] = [
            'id' => $user['id'],
            'name' => $user['name'],
            'role' => $user['role'],
            'phone' => $user['phone'],
        ];

        echo json_encode([
            'status' => 'success',
            'message' => 'Login successful',
            'data' => [
                'id' => $user['id'],
                'name' => $user['name'],
                'email' => $user['email'],
                'phone' => $user['phone'],
                'role' => $user['role'],
                'status' => $user['status'], // ✅ included status
                'created_at' => $user['created_at'],
            ]
        ]);
        exit;
    }
}

echo json_encode([
    'status' => 'error',
    'message' => 'Invalid email or password.'
]);
?>
