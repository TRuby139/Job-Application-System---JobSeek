<?php
require_once 'config.php';

// Ensure user is admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    sendJsonResponse(false, 'Unauthorized. Only admins can access this endpoint.');
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Fetch all users
    try {
        $stmt = $pdo->query('SELECT id, name, email, role, created_at FROM users ORDER BY created_at DESC');
        $users = $stmt->fetchAll();
        sendJsonResponse(true, 'Users fetched.', $users);
    } catch (PDOException $e) {
        sendJsonResponse(false, 'Failed to fetch users: ' . $e->getMessage());
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Delete user
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (!isset($data['user_id'])) {
        sendJsonResponse(false, 'Missing user_id field.');
    }
    
    $target_user_id = $data['user_id'];
    
    // Prevent self-deletion
    if ($target_user_id == $_SESSION['user_id']) {
        sendJsonResponse(false, 'Cannot delete your own admin account.');
    }
    
    try {
        $stmt = $pdo->prepare('DELETE FROM users WHERE id = ?');
        $stmt->execute([$target_user_id]);
        sendJsonResponse(true, 'User deleted successfully.');
    } catch (PDOException $e) {
        sendJsonResponse(false, 'Failed to delete user: ' . $e->getMessage());
    }
} else {
    sendJsonResponse(false, 'Method not allowed.');
}
?>
