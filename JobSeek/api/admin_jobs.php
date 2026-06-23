<?php
require_once 'config.php';

// Ensure user is admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    sendJsonResponse(false, 'Unauthorized. Only admins can access this endpoint.');
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Fetch all jobs including employer name
    try {
        $query = "
            SELECT j.*, c.name as company_name, u.name as employer_name 
            FROM jobs j 
            JOIN companies c ON j.company_id = c.id
            JOIN users u ON j.employer_id = u.id
            ORDER BY j.created_at DESC
        ";
        $stmt = $pdo->query($query);
        $jobs = $stmt->fetchAll();
        sendJsonResponse(true, 'Jobs fetched.', $jobs);
    } catch (PDOException $e) {
        sendJsonResponse(false, 'Failed to fetch jobs: ' . $e->getMessage());
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Delete job
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($data['job_id'])) {
        sendJsonResponse(false, 'Missing job_id field.');
    }
    
    $target_job_id = $data['job_id'];
    
    try {
        $stmt = $pdo->prepare('DELETE FROM jobs WHERE id = ?');
        $stmt->execute([$target_job_id]);
        sendJsonResponse(true, 'Job deleted successfully.');
    } catch (PDOException $e) {
        sendJsonResponse(false, 'Failed to delete job: ' . $e->getMessage());
    }
} else {
    sendJsonResponse(false, 'Method not allowed.');
}
?>
