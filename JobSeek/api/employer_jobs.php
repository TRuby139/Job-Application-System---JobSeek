<?php
require_once 'config.php';

// Authentication and authorization checks
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'employer') {
    sendJsonResponse(false, 'Unauthorized. Please log in as an employer.');
}

$employer_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        $stmt = $pdo->prepare('SELECT id, title, location, type, status, created_at FROM jobs WHERE employer_id = ? ORDER BY created_at DESC');
        $stmt->execute([$employer_id]);
        $jobs = $stmt->fetchAll(PDO::FETCH_ASSOC);
        sendJsonResponse(true, 'Jobs fetched successfully', $jobs);
    } catch (PDOException $e) {
        sendJsonResponse(false, 'Database error: ' . $e->getMessage());
    }
} else {
    sendJsonResponse(false, 'Method not allowed.');
}
?>
