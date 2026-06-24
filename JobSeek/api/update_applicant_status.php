<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendJsonResponse(false, 'Only POST requests are allowed.');
}

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'employer') {
    sendJsonResponse(false, 'Unauthorized.');
}

$data = json_decode(file_get_contents('php://input'), true);
$application_id = $data['application_id'] ?? null;
$status = $data['status'] ?? null;

if (!$application_id || !$status) {
    sendJsonResponse(false, 'Application ID and status are required.');
}

$employer_id = $_SESSION['user_id'];

// Verify ownership: the application belongs to a job that belongs to a company that belongs to the employer
$stmt = $pdo->prepare('
    SELECT a.id FROM applications a
    JOIN jobs j ON a.job_id = j.id
    JOIN companies c ON j.company_id = c.id
    WHERE a.id = ? AND c.employer_id = ?
');
$stmt->execute([$application_id, $employer_id]);
if (!$stmt->fetch()) {
    sendJsonResponse(false, 'Application not found or unauthorized.');
}

// Update status
$stmt = $pdo->prepare("UPDATE applications SET status = ? WHERE id = ?");
$stmt->execute([$status, $application_id]);

sendJsonResponse(true, 'Application status updated.');
?>
