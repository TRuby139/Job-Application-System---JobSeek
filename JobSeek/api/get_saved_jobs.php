<?php
// api/get_saved_jobs.php
require_once 'config.php';
if (!isset($_SESSION['user_id'])) sendJsonResponse(false, 'Unauthorized');
$stmt = $pdo->prepare("SELECT j.*, c.name as company_name FROM saved_jobs sj JOIN jobs j ON sj.job_id = j.id JOIN companies c ON j.company_id = c.id WHERE sj.user_id = ? ORDER BY sj.saved_at DESC");
$stmt->execute([$_SESSION['user_id']]);
sendJsonResponse(true, 'Fetched saved jobs', $stmt->fetchAll());
