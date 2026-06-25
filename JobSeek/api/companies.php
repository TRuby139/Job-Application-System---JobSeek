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
    // Any logged-in user can register a company.
    // We will update their role to 'employer' after successful registration.
    
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($data['name'])) {
        sendJsonResponse(false, 'Company name is required.');
    }
    
    $name = trim($data['name']);
    $location = isset($data['location']) ? trim($data['location']) : '';
    $sector = isset($data['sector']) ? trim($data['sector']) : '';
    $details = isset($data['details']) ? trim($data['details']) : '';
    
    try {
        $pdo->beginTransaction();

        $stmt = $pdo->prepare('INSERT INTO companies (employer_id, name, location, sector, details) VALUES (?, ?, ?, ?, ?)');
        $stmt->execute([$employer_id, $name, $location, $sector, $details]);
        $companyId = $pdo->lastInsertId();

        // Update user role to employer
        $updateStmt = $pdo->prepare("UPDATE users SET role = 'employer' WHERE id = ?");
        $updateStmt->execute([$employer_id]);
        $_SESSION['user_role'] = 'employer';

        $pdo->commit();
        sendJsonResponse(true, 'Company registered successfully.', ['id' => $companyId]);
    } catch (PDOException $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }
        sendJsonResponse(false, 'Failed to register company: ' . $e->getMessage());
    }
} else {
    sendJsonResponse(false, 'Method not allowed.');
}
?>
