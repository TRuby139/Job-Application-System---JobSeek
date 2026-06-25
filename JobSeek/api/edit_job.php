<?php
require_once 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'employer') {
    sendJsonResponse(false, 'Unauthorized');
}

$employer_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $job_id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $location = trim($_POST['location'] ?? '');
    $salary = trim($_POST['salary'] ?? '');
    $type = trim($_POST['type'] ?? 'full-time');
    $experience = trim($_POST['experience'] ?? 'mid');
    $status = trim($_POST['status'] ?? 'Open');

    if ($job_id <= 0 || empty($title) || empty($description) || empty($location)) {
        sendJsonResponse(false, 'Please fill in all required fields.');
    }

    try {
        // Verify job exists and belongs to employer
        $checkStmt = $pdo->prepare('SELECT id FROM jobs WHERE id = ? AND employer_id = ?');
        $checkStmt->execute([$job_id, $employer_id]);
        if (!$checkStmt->fetch()) {
            sendJsonResponse(false, 'Job not found or you do not have permission to edit it.');
        }

        $stmt = $pdo->prepare('
            UPDATE jobs 
            SET title = ?, description = ?, location = ?, salary = ?, type = ?, experience = ?, status = ?
            WHERE id = ? AND employer_id = ?
        ');
        $stmt->execute([$title, $description, $location, $salary, $type, $experience, $status, $job_id, $employer_id]);
        
        if ($stmt->rowCount() > 0 || $stmt->errorCode() === '00000') {
            sendJsonResponse(true, 'Job updated successfully.');
        } else {
            sendJsonResponse(false, 'Failed to update job or no changes made.');
        }
    } catch (PDOException $e) {
        sendJsonResponse(false, 'Database error: ' . $e->getMessage());
    }
} else {
    sendJsonResponse(false, 'Method not allowed.');
}
?>
