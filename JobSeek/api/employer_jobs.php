<?php
require_once 'config.php';

// Authentication and authorization checks
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'employer') {
    sendJsonResponse(false, 'Unauthorized. Please log in as an employer.');
}

$employer_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        $stmt = $pdo->prepare('
            SELECT j.id, j.title, j.location, j.type, j.status, j.created_at,
                   COUNT(CASE WHEN a.status = \'Pending\' THEN 1 END) AS new_applicants_count
            FROM jobs j
            LEFT JOIN applications a ON j.id = a.job_id
            WHERE j.employer_id = ?
            GROUP BY j.id
            ORDER BY j.created_at DESC
        ');
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
