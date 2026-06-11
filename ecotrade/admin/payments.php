<?php

require_once '../config/database.php';
require_once '../includes/auth.php';

requireAdmin();

$payments =
$pdo->query(
"
SELECT *
FROM payments
ORDER BY id DESC
"
)->fetchAll();
?>