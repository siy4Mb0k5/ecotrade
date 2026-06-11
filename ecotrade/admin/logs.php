<?php

require_once '../config/database.php';
require_once '../includes/auth.php';

requireAdmin();

$logs =
$pdo->query(
"
SELECT *
FROM admin_logs
ORDER BY id DESC
"
)->fetchAll();
?>