<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendJsonResponse(false, 'Only POST requests are allowed.');
}

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'employer') {
    sendJsonResponse(false, 'Unauthorized.');
}

$data = json_decode(file_get_contents('php://input'), true);
$job_id = $data['job_id'] ?? null;
$closing_date = $data['closing_date'] ?? null;
$status = $data['status'] ?? null;

if (!$job_id) {
    sendJsonResponse(false, 'Job ID is required.');
}

$employer_id = $_SESSION['user_id'];

// Verify ownership
$stmt = $pdo->prepare('
    SELECT j.id FROM jobs j 
    JOIN companies c ON j.company_id = c.id 
    WHERE j.id = ? AND c.employer_id = ?
');
$stmt->execute([$job_id, $employer_id]);
if (!$stmt->fetch()) {
    sendJsonResponse(false, 'Job not found or unauthorized.');
}

// Update settings
$updateFields = [];
$params = [];

if ($closing_date !== null) {
    $updateFields[] = "closing_date = ?";
    $params[] = $closing_date;
}

if ($status !== null) {
    $updateFields[] = "status = ?";
    $params[] = $status;
}

if (count($updateFields) > 0) {
    $params[] = $job_id;
    $stmt = $pdo->prepare("UPDATE jobs SET " . implode(", ", $updateFields) . " WHERE id = ?");
    $stmt->execute($params);
}

sendJsonResponse(true, 'Job settings updated.');
?>
