<?php

require_once '../config/database.php';
require_once '../includes/auth.php';
require_once '../includes/header.php';

requireLogin();

$stmt = $pdo->prepare(
    "SELECT * FROM orders WHERE user_id = ? ORDER BY id DESC"
);
$stmt->execute([$_SESSION['user_id']]);
$orders = $stmt->fetchAll();
?>

<div class="orders-container">

    <h1>My Orders</h1>

    <?php if (empty($orders)): ?>
        <div class="empty-state">
            <p>You have no orders yet.</p>
            <a href="../marketplace.php" class="btn">Start Shopping</a>
        </div>
    <?php else: ?>

    <div class="table-container">
        <table class="orders-table">
            <tr>
                <th>Order #</th>
                <th>Total</th>
                <th>Status</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>

            <?php foreach ($orders as $order): ?>
            <tr>
                <td>#<?= (int) $order['id'] ?></td>
                <td>R<?= number_format($order['total_amount'], 2) ?></td>
                <td>
                    <span class="status-badge status-<?= strtolower($order['order_status']) ?>">
                        <?= ucfirst($order['order_status']) ?>
                    </span>
                </td>
                <td><?= date('d M Y', strtotime($order['created_at'])) ?></td>
                <td>
                    <a class="btn" style="background:#7EC8E3;"
                       href="../messages/chat.php?order_id=<?= (int) $order['id'] ?>">
                        💬 Chat with Seller
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>

        </table>
    </div>

    <?php endif; ?>

</div>

<?php require_once '../includes/footer.php'; ?>