<?php
require_once 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'employer') {
    sendJsonResponse(false, 'Unauthorized');
}

$employer_id = $_SESSION['user_id'];
$job_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($job_id <= 0) {
    sendJsonResponse(false, 'Invalid Job ID');
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        $stmt = $pdo->prepare('SELECT * FROM jobs WHERE id = ? AND employer_id = ?');
        $stmt->execute([$job_id, $employer_id]);
        $job = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($job) {
            sendJsonResponse(true, 'Job fetched successfully', $job);
        } else {
            sendJsonResponse(false, 'Job not found');
        }
    } catch (PDOException $e) {
        sendJsonResponse(false, 'Database error: ' . $e->getMessage());
    }
} else {
    sendJsonResponse(false, 'Method not allowed.');
}
?>
