<?php
require_once 'config.php';

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    sendJsonResponse(false, 'Unauthorized. Please log in.');
}

// Ensure user is an employer
if ($_SESSION['user_role'] !== 'employer') {
    sendJsonResponse(false, 'Forbidden. Only employers can post jobs.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    // Required fields check
    $required_fields = ['title', 'company_id', 'description', 'location', 'type', 'experience'];
    foreach ($required_fields as $field) {
        if (empty($data[$field])) {
            sendJsonResponse(false, "Field '$field' is required.");
        }
    }

    $employer_id = $_SESSION['user_id'];
    $company_id = intval($data['company_id']);
    $title = trim($data['title']);
    $description = trim($data['description']);
    $location = trim($data['location']);
    $salary = isset($data['salary']) ? trim($data['salary']) : '';
    $type = $data['type']; // 'full-time', 'contract', 'remote'
    $experience = $data['experience']; // 'entry', 'mid', 'exec'
    $status = 'Open';

    try {
        // Verify the company belongs to the employer
        $stmtCheck = $pdo->prepare('SELECT id FROM companies WHERE id = ? AND employer_id = ?');
        $stmtCheck->execute([$company_id, $employer_id]);
        if (!$stmtCheck->fetch()) {
            sendJsonResponse(false, 'Invalid company selected.');
        }

        $stmt = $pdo->prepare('INSERT INTO jobs (employer_id, company_id, title, description, location, salary, type, experience, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)');
        $stmt->execute([$employer_id, $company_id, $title, $description, $location, $salary, $type, $experience, $status]);
        
        $jobId = $pdo->lastInsertId();
        sendJsonResponse(true, 'Job posted successfully.', ['id' => $jobId]);
    } catch (PDOException $e) {
        sendJsonResponse(false, 'Database error: ' . $e->getMessage());
    }
} else {
    sendJsonResponse(false, 'Method not allowed.');
}
?>
