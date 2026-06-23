<?php
require_once 'config.php';

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    sendJsonResponse(false, 'Unauthorized. Please log in.');
}

$employer_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Fetch companies for the logged-in employer
    $stmt = $pdo->prepare('SELECT * FROM companies WHERE employer_id = ? ORDER BY created_at DESC');
    $stmt->execute([$employer_id]);
    $companies = $stmt->fetchAll();
    
    sendJsonResponse(true, 'Companies fetched.', $companies);
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Register a new company
    if ($_SESSION['user_role'] !== 'employer') {
        sendJsonResponse(false, 'Only employers can register companies.');
    }
    
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($data['name'])) {
        sendJsonResponse(false, 'Company name is required.');
    }
    
    $name = trim($data['name']);
    $details = isset($data['details']) ? trim($data['details']) : '';
    
    try {
        $stmt = $pdo->prepare('INSERT INTO companies (employer_id, name, details) VALUES (?, ?, ?)');
        $stmt->execute([$employer_id, $name, $details]);
        sendJsonResponse(true, 'Company registered successfully.', ['id' => $pdo->lastInsertId()]);
    } catch (PDOException $e) {
        sendJsonResponse(false, 'Failed to register company: ' . $e->getMessage());
    }
} else {
    sendJsonResponse(false, 'Method not allowed.');
}
?>
