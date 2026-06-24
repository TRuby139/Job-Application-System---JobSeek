<?php
// api/get_tracking_stats.php
require_once 'config.php';
if (!isset($_SESSION['user_id'])) sendJsonResponse(false, 'Unauthorized');

$uid = $_SESSION['user_id'];

try {
    $active = $pdo->prepare("SELECT COUNT(*) FROM applications WHERE seeker_id = ?"); 
    $active->execute([$uid]); 
    $activeCount = $active->fetchColumn();

    $interviews = $pdo->prepare("SELECT COUNT(*) FROM applications WHERE seeker_id = ? AND tracking_stage = 'Interview'"); 
    $interviews->execute([$uid]); 
    $interviewsCount = $interviews->fetchColumn();

    $saved = $pdo->prepare("SELECT COUNT(*) FROM saved_jobs WHERE user_id = ?"); 
    $saved->execute([$uid]); 
    $savedCount = $saved->fetchColumn();

    sendJsonResponse(true, 'Stats fetched', [
        'active_applications' => $activeCount, 
        'interviews' => $interviewsCount, 
        'saved_roles' => $savedCount
    ]);
} catch (PDOException $e) {
    sendJsonResponse(false, 'Failed to fetch stats: ' . $e->getMessage());
}
