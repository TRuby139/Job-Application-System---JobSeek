<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    sendJsonResponse(false, 'Only GET requests are allowed.');
}

if (!isset($_SESSION['user_id'])) {
    sendJsonResponse(false, 'Unauthorized. Please log in.');
}

$user_id = $_SESSION['user_id'];
$user_role = $_SESSION['user_role'];

try {
    // Everyone sees their own applications in tracking
    $query = "
        SELECT a.*, j.title as job_title, j.location, j.salary, c.name as company_name 
        FROM applications a
        JOIN jobs j ON a.job_id = j.id
        JOIN companies c ON j.company_id = c.id
        WHERE a.seeker_id = ?
        ORDER BY a.applied_at DESC
    ";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$user_id]);
    
    $applications = $stmt->fetchAll();
    sendJsonResponse(true, 'Applications fetched.', $applications);
} catch (PDOException $e) {
    sendJsonResponse(false, 'Failed to fetch applications: ' . $e->getMessage());
}
?>
