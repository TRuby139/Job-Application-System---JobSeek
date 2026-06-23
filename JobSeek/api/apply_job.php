<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendJsonResponse(false, 'Only POST requests are allowed.');
}

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'seeker') {
    sendJsonResponse(false, 'Unauthorized. Only job seekers can apply for jobs.');
}

$job_id = $_POST['job_id'] ?? null;
$cover_letter = isset($_POST['cover_letter']) ? trim($_POST['cover_letter']) : '';

if (!$job_id || empty($cover_letter)) {
    sendJsonResponse(false, 'Missing required fields.');
}

$seeker_id = $_SESSION['user_id'];

// Handle file upload
$resume_path = null;
if (isset($_FILES['resume']) && $_FILES['resume']['error'] === UPLOAD_ERR_OK) {
    $upload_dir = '../uploads/';
    $file_name = uniqid() . '_' . basename($_FILES['resume']['name']);
    $target_file = $upload_dir . $file_name;
    
    // Create directory if it doesn't exist
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    if (move_uploaded_file($_FILES['resume']['tmp_name'], $target_file)) {
        $resume_path = 'uploads/' . $file_name;
    } else {
        sendJsonResponse(false, 'Failed to upload resume.');
    }
}

// Check if already applied
$stmt = $pdo->prepare('SELECT id FROM applications WHERE job_id = ? AND seeker_id = ?');
$stmt->execute([$job_id, $seeker_id]);
if ($stmt->fetch()) {
    sendJsonResponse(false, 'You have already applied for this job.');
}

try {
    $stmt = $pdo->prepare('INSERT INTO applications (job_id, seeker_id, cover_letter, resume_path, status) VALUES (?, ?, ?, ?, ?)');
    $stmt->execute([$job_id, $seeker_id, $cover_letter, $resume_path, 'Pending']);
    sendJsonResponse(true, 'Application submitted successfully.');
} catch (PDOException $e) {
    sendJsonResponse(false, 'Failed to submit application: ' . $e->getMessage());
}
?>
