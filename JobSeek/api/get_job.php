<?php
require_once 'config.php';
$id = $_GET['id'] ?? null;
if (!$id) {
    sendJsonResponse(false, 'Job ID required');
    exit;
}
try {
    $stmt = $pdo->prepare("SELECT j.*, c.name as company_name, c.details as company_details 
                           FROM jobs j 
                           JOIN companies c ON j.company_id = c.id 
                           WHERE j.id = ?");
    $stmt->execute([$id]);
    $job = $stmt->fetch();
    if ($job) {
        sendJsonResponse(true, 'Job details fetched', $job);
        exit;
    } else {
        sendJsonResponse(false, 'Job not found');
        exit;
    }
} catch (PDOException $e) {
    sendJsonResponse(false, 'A database error occurred');
    exit;
}
?>
