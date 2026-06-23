<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    sendJsonResponse(false, 'Only GET requests are allowed.');
}

try {
    // Fetch all open jobs, joining with companies to get company_name
    $query = "
        SELECT j.*, c.name as company_name 
        FROM jobs j 
        JOIN companies c ON j.company_id = c.id 
        WHERE j.status = 'Open'
        ORDER BY j.created_at DESC
    ";
    $stmt = $pdo->query($query);
    $jobs = $stmt->fetchAll();
    
    sendJsonResponse(true, 'Jobs fetched.', $jobs);
} catch (PDOException $e) {
    sendJsonResponse(false, 'Failed to fetch jobs: ' . $e->getMessage());
}
?>
