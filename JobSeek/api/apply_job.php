<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendJsonResponse(false, 'Only POST requests are allowed.');
}

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'seeker') {
    sendJsonResponse(false, 'Unauthorized. Only job seekers can apply for jobs.');
}

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['job_id']) || !isset($data['cover_letter'])) {
    sendJsonResponse(false, 'Missing required fields.');
}

$seeker_id = $_SESSION['user_id'];
$job_id = $data['job_id'];
$cover_letter = trim($data['cover_letter']);

// Check if already applied
$stmt = $pdo->prepare('SELECT id FROM applications WHERE job_id = ? AND seeker_id = ?');
$stmt->execute([$job_id, $seeker_id]);
if ($stmt->fetch()) {
    sendJsonResponse(false, 'You have already applied for this job.');
}

try {
    $stmt = $pdo->prepare('INSERT INTO applications (job_id, seeker_id, cover_letter, status) VALUES (?, ?, ?, ?)');
    $stmt->execute([$job_id, $seeker_id, $cover_letter, 'Pending']);
    sendJsonResponse(true, 'Application submitted successfully.');
} catch (PDOException $e) {
    sendJsonResponse(false, 'Failed to submit application: ' . $e->getMessage());
}
?>
