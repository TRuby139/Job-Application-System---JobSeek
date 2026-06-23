<?php
require_once 'api/config.php';
try {
    $pdo->exec("ALTER TABLE applications ADD COLUMN resume_path VARCHAR(255) NULL AFTER cover_letter;");
    echo "Successfully altered table.\n";
} catch (Exception $e) {
    if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
        echo "Column already exists.\n";
    } else {
        echo "Error: " . $e->getMessage() . "\n";
    }
}
