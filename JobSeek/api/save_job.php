<?php
// api/save_job.php
require_once 'config.php';
if (($_SERVER['REQUEST_METHOD'] ?? '') !== 'POST' || !isset($_SESSION['user_id'])) sendJsonResponse(false, 'Unauthorized');
$data = json_decode(file_get_contents('php://input'), true);
$job_id = $data['job_id'] ?? 0;
$stmt = $pdo->prepare("SELECT id FROM saved_jobs WHERE user_id = ? AND job_id = ?");
$stmt->execute([$_SESSION['user_id'], $job_id]);
if ($stmt->fetch()) {
    $pdo->prepare("DELETE FROM saved_jobs WHERE user_id = ? AND job_id = ?")->execute([$_SESSION['user_id'], $job_id]);
    sendJsonResponse(true, 'Job unsaved');
} else {
    $pdo->prepare("INSERT INTO saved_jobs (user_id, job_id) VALUES (?, ?)")->execute([$_SESSION['user_id'], $job_id]);
    sendJsonResponse(true, 'Job saved');
}
