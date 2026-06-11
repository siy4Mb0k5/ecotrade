<?php

require_once '../config/database.php';
require_once '../includes/auth.php';

requireAdmin();

$orderId = intval($_POST['order_id']);
$status  = $_POST['status'];

$allowed = ['pending','processing', 'paid', 'shipped', 'delivered', 'cancelled'];

if (!in_array($status, $allowed)) {
    header("Location: orders.php");
    exit;
}

$pdo->prepare("UPDATE orders SET order_status = ? WHERE id = ?")
    ->execute([$status, $orderId]);

header("Location: orders.php");
exit;