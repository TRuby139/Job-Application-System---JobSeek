<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendJsonResponse(false, 'Only POST requests are allowed.');
}

// Read JSON input
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['name']) || !isset($data['email']) || !isset($data['password']) || !isset($data['role'])) {
    sendJsonResponse(false, 'Missing required fields.');
}

$name = trim($data['name']);
$email = trim($data['email']);
$password = $data['password'];
$role = $data['role'];

// Validate role
if (!in_array($role, ['seeker', 'employer', 'admin'])) {
    sendJsonResponse(false, 'Invalid role.');
}

// Check if email already exists
$stmt = $pdo->prepare('SELECT id FROM users WHERE email = ?');
$stmt->execute([$email]);
if ($stmt->fetch()) {
    sendJsonResponse(false, 'Email already exists.');
}

// Hash password for security
$hashedPassword = password_hash($password, PASSWORD_BCRYPT);

try {
    // Insert new user
    $stmt = $pdo->prepare('INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)');
    $stmt->execute([$name, $email, $hashedPassword, $role]);
    sendJsonResponse(true, 'Registration successful.');
} catch (PDOException $e) {
    sendJsonResponse(false, 'Registration failed: ' . $e->getMessage());
}
?>
