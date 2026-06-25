<?php
require_once 'config.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'employer') {
    sendJsonResponse(false, 'Unauthorized. Only employers can access this.');
}

$job_id = $_GET['id'] ?? null;
if (!$job_id) {
    sendJsonResponse(false, 'Job ID is required.');
}

$employer_id = $_SESSION['user_id'];

// Check if job belongs to employer's company
$stmt = $pdo->prepare('
    SELECT j.*, c.name as company_name 
    FROM jobs j 
    JOIN companies c ON j.company_id = c.id 
    WHERE j.id = ? AND c.employer_id = ?
');
$stmt->execute([$job_id, $employer_id]);
$job = $stmt->fetch();

if (!$job) {
    sendJsonResponse(false, 'Job not found or unauthorized.');
}

// Fetch applicants
$stmt = $pdo->prepare('
    SELECT a.id as application_id, a.status, a.applied_at, a.resume_path, 
           u.name as applicant_name, u.email as applicant_email
    FROM applications a
    JOIN users u ON a.seeker_id = u.id
    WHERE a.job_id = ?
    ORDER BY a.applied_at DESC
');
$stmt->execute([$job_id]);
$applicants = $stmt->fetchAll();

sendJsonResponse(true, 'Job details fetched', [
    'job' => $job,
    'applicants' => $applicants
]);
?>
