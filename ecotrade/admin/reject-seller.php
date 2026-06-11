<?php

require_once '../config/database.php';
require_once '../includes/auth.php';

requireAdmin();

$userId = intval($_GET['id']);

$pdo->prepare(
    "UPDATE users SET seller_status = 'rejected' WHERE id = ?"
)->execute([$userId]);

//  update seller_documents status
$pdo->prepare(
    "UPDATE seller_documents SET status = 'rejected' WHERE user_id = ?"
)->execute([$userId]);

header("Location: applications.php");
exit;