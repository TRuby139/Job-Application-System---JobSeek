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
    if ($user_role === 'seeker') {
        // Seekers see their own applications
        $query = "
            SELECT a.*, j.title as job_title, c.name as company_name 
            FROM applications a
            JOIN jobs j ON a.job_id = j.id
            JOIN companies c ON j.company_id = c.id
            WHERE a.seeker_id = ?
            ORDER BY a.applied_at DESC
        ";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$user_id]);
    } elseif ($user_role === 'employer') {
        // Employers see applications for their jobs
        $query = "
            SELECT a.*, j.title as job_title, u.name as seeker_name, u.email as seeker_email
            FROM applications a
            JOIN jobs j ON a.job_id = j.id
            JOIN users u ON a.seeker_id = u.id
            WHERE j.employer_id = ?
            ORDER BY a.applied_at DESC
        ";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$user_id]);
    } else {
        // Admin gets all
        $query = "
            SELECT a.*, j.title as job_title, u.name as seeker_name, c.name as company_name
            FROM applications a
            JOIN jobs j ON a.job_id = j.id
            JOIN users u ON a.seeker_id = u.id
            JOIN companies c ON j.company_id = c.id
            ORDER BY a.applied_at DESC
        ";
        $stmt = $pdo->query($query);
        $applications = $stmt->fetchAll();
        sendJsonResponse(true, 'Applications fetched.', $applications);
    }
    
    $applications = $stmt->fetchAll();
    sendJsonResponse(true, 'Applications fetched.', $applications);
} catch (PDOException $e) {
    sendJsonResponse(false, 'Failed to fetch applications: ' . $e->getMessage());
}
?>
