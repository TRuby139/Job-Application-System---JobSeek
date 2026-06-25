<?php
require_once 'config.php';

// Clear session array
session_unset();

// Destroy session data on the server
session_destroy();

sendJsonResponse(true, 'Logout successful.');
?>
