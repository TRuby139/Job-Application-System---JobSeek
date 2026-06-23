<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendJsonResponse(false, 'Only POST requests are allowed.');
}

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'employer') {
    sendJsonResponse(false, 'Unauthorized. Only employers can update application status.');
}

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['application_id']) || !isset($data['status'])) {
    sendJsonResponse(false, 'Missing required fields.');
}

$application_id = $data['application_id'];
$status = $data['status'];
$employer_id = $_SESSION['user_id'];

if (!in_array($status, ['Pending', 'Accepted', 'Rejected'])) {
    sendJsonResponse(false, 'Invalid status.');
}

// Verify this application is for a job owned by this employer
$stmt = $pdo->prepare('
    SELECT a.id 
    FROM applications a 
    JOIN jobs j ON a.job_id = j.id 
    WHERE a.id = ? AND j.employer_id = ?
');
$stmt->execute([$application_id, $employer_id]);
if (!$stmt->fetch()) {
    sendJsonResponse(false, 'Invalid application or unauthorized.');
}

try {
    $stmt = $pdo->prepare('UPDATE applications SET status = ? WHERE id = ?');
    $stmt->execute([$status, $application_id]);
    sendJsonResponse(true, 'Application status updated successfully.');
} catch (PDOException $e) {
    sendJsonResponse(false, 'Failed to update application status: ' . $e->getMessage());
}
?>
