<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    sendJsonResponse(false, 'Only GET requests are allowed.');
}

try {
    // Auto-migrate schema if columns are missing
    try {
        $pdo->query("SELECT type, experience FROM jobs LIMIT 1");
    } catch (PDOException $e) {
        $pdo->exec("ALTER TABLE jobs ADD COLUMN type ENUM('full-time', 'contract', 'remote') DEFAULT 'full-time'");
        $pdo->exec("ALTER TABLE jobs ADD COLUMN experience ENUM('entry', 'mid', 'exec') DEFAULT 'mid'");
        $pdo->exec("UPDATE jobs SET type = ELT(FLOOR(1 + (RAND() * 3)), 'full-time', 'contract', 'remote'), experience = ELT(FLOOR(1 + (RAND() * 3)), 'entry', 'mid', 'exec')");
    }

    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 8;
    $offset = ($page - 1) * $limit;

    $where = ["j.status = 'Open'"];
    $params = [];

    // Search query
    if (!empty($_GET['q'])) {
        $where[] = "(j.title LIKE ? OR c.name LIKE ? OR j.description LIKE ?)";
        $searchTerm = '%' . $_GET['q'] . '%';
        $params[] = $searchTerm;
        $params[] = $searchTerm;
        $params[] = $searchTerm;
    }

    // Location query
    if (!empty($_GET['loc'])) {
        $where[] = "j.location LIKE ?";
        $params[] = '%' . $_GET['loc'] . '%';
    }

    // Job type filter
    if (!empty($_GET['type'])) {
        $types = explode(',', $_GET['type']);
        $placeholders = implode(',', array_fill(0, count($types), '?'));
        $where[] = "j.type IN ($placeholders)";
        $params = array_merge($params, $types);
    }

    // Experience filter
    if (!empty($_GET['experience'])) {
        $exp = explode(',', $_GET['experience']);
        $placeholders = implode(',', array_fill(0, count($exp), '?'));
        $where[] = "j.experience IN ($placeholders)";
        $params = array_merge($params, $exp);
    }

    // Salary filter
    if (!empty($_GET['min_salary'])) {
        // Extract the first number from the salary string (e.g. "$80k - $100k" -> 80)
        $where[] = "CAST(SUBSTRING_INDEX(REPLACE(j.salary, '$', ''), 'k', 1) AS UNSIGNED) >= ?";
        $params[] = (int)$_GET['min_salary'];
    }

    $whereClause = implode(" AND ", $where);

    // Get total count
    $countQuery = "SELECT COUNT(*) FROM jobs j JOIN companies c ON j.company_id = c.id WHERE $whereClause";
    $countStmt = $pdo->prepare($countQuery);
    $countStmt->execute($params);
    $total = $countStmt->fetchColumn();
    $totalPages = ceil($total / $limit);

    // Fetch paginated jobs
    $query = "
        SELECT j.*, c.name as company_name 
        FROM jobs j 
        JOIN companies c ON j.company_id = c.id 
        WHERE $whereClause
        ORDER BY j.created_at DESC
        LIMIT $limit OFFSET $offset
    ";
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $jobs = $stmt->fetchAll();
    
    // sendJsonResponse only takes max 3 args in config.php. We will pass pagination inside data.
    sendJsonResponse(true, 'Jobs fetched.', [
        'jobs' => $jobs,
        'total' => $total,
        'totalPages' => $totalPages,
        'currentPage' => $page
    ]);
} catch (PDOException $e) {
    sendJsonResponse(false, 'Failed to fetch jobs: ' . $e->getMessage());
}
?>
