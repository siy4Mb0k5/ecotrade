<?php

require_once '../config/database.php';

$orderId = intval($_POST['m_payment_id']);
$status  = $_POST['payment_status'] ?? '';

if ($status === 'COMPLETE') {

    // Get user_id from the order
    $stmt = $pdo->prepare("SELECT user_id FROM orders WHERE id = ?");
    $stmt->execute([$orderId]);
    $row = $stmt->fetch();

    if ($row) {
        // Mark ALL pending orders for this user as paid
        // covers multi-seller scenario where multiple orders were created
        $pdo->prepare(
            "UPDATE orders SET order_status = 'paid'
             WHERE user_id = ? AND order_status = 'pending'"
        )->execute([$row['user_id']]);
    }
}