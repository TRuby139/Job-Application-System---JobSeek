<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendJsonResponse(false, 'Only POST requests are allowed.');
}

// Read JSON input
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['email']) || !isset($data['password'])) {
    sendJsonResponse(false, 'Missing email or password.');
}

$email = trim($data['email']);
$password = $data['password'];

// Find user by email
$stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
$stmt->execute([$email]);
$user = $stmt->fetch();

// Verify password and login
if ($user && password_verify($password, $user['password'])) {
    // Store user data in session
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_name'] = $user['name'];
    $_SESSION['user_role'] = $user['role'];
    
    sendJsonResponse(true, 'Login successful.', [
        'id' => $user['id'],
        'name' => $user['name'],
        'role' => $user['role']
    ]);
} else {
    sendJsonResponse(false, 'Invalid email or password.');
}
?>
