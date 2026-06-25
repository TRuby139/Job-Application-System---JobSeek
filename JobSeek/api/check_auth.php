<?php
require_once 'config.php';

if (isset($_SESSION['user_id'])) {
    sendJsonResponse(true, 'Authenticated', [
        'id' => $_SESSION['user_id'],
        'name' => $_SESSION['user_name'],
        'role' => $_SESSION['user_role']
    ]);
} else {
    sendJsonResponse(false, 'Not authenticated');
}
?>
