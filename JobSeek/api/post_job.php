<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendJsonResponse(false, 'Only POST requests are allowed.');
}

// Only logged in employers can post jobs
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'employer') {
    sendJsonResponse(false, 'Unauthorized. Only employers can post jobs.');
}

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['company_id']) || !isset($data['title']) || !isset($data['description']) || !isset($data['location'])) {
    sendJsonResponse(false, 'Missing required fields.');
}

$employer_id = $_SESSION['user_id'];
$company_id = $data['company_id'];
$title = trim($data['title']);
$description = trim($data['description']);
$location = trim($data['location']);
$salary = isset($data['salary']) ? trim($data['salary']) : null;

// Verify company belongs to this employer for security
$stmt = $pdo->prepare('SELECT id FROM companies WHERE id = ? AND employer_id = ?');
$stmt->execute([$company_id, $employer_id]);
if (!$stmt->fetch()) {
    sendJsonResponse(false, 'Invalid company selected.');
}

try {
    $stmt = $pdo->prepare('INSERT INTO jobs (employer_id, company_id, title, description, location, salary) VALUES (?, ?, ?, ?, ?, ?)');
    $stmt->execute([$employer_id, $company_id, $title, $description, $location, $salary]);
    sendJsonResponse(true, 'Job posted successfully.', ['id' => $pdo->lastInsertId()]);
} catch (PDOException $e) {
    sendJsonResponse(false, 'Failed to post job: ' . $e->getMessage());
}
?>
