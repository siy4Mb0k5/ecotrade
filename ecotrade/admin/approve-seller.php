<?php

require_once '../config/database.php';
require_once '../includes/auth.php';

requireAdmin();

$userId = intval($_GET['id']);

$pdo->prepare(
    "UPDATE users SET seller_status = 'approved', is_seller = 1 WHERE id = ?"
)->execute([$userId]);

// Also update seller_documents status
$pdo->prepare(
    "UPDATE seller_documents SET status = 'approved' WHERE user_id = ?"
)->execute([$userId]);

header("Location: applications.php");
exit;