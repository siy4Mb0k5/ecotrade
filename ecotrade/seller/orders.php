<?php

require_once '../config/database.php';
require_once '../includes/auth.php';
require_once '../includes/header.php';

requireSeller();

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'])) {
    $allowed = ['paid','processing', 'shipped', 'delivered', 'cancelled'];
    $status  = $_POST['status'];
    $oid     = intval($_POST['order_id']);

    if (in_array($status, $allowed)) {
        $pdo->prepare("UPDATE orders SET order_status = ? WHERE id = ?")
            ->execute([$status, $oid]);
    }
    header("Location: orders.php");
    exit;
}

$stmt = $pdo->prepare(
    "SELECT
        order_items.*,
        orders.order_status,
        orders.id AS order_id,
        orders.user_id AS buyer_id,
        products.title,
        buyers.first_name AS buyer_name
     FROM order_items
     JOIN orders ON order_items.order_id = orders.id
     JOIN products ON order_items.product_id = products.id
     JOIN users buyers ON orders.user_id = buyers.id
     WHERE order_items.seller_id = ?"
);
$stmt->execute([$_SESSION['user_id']]);
$orders = $stmt->fetchAll();
?>

<div class="admin-container">

    <h1>My Orders</h1>

    <div class="table-container">
        <table>
            <tr>
                <th>Order #</th>
                <th>Product</th>
                <th>Buyer</th>
                <th>Qty</th>
                <th>Price</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>

            <?php if (empty($orders)): ?>
            <tr>
                <td colspan="7" style="text-align:center; color:#888;">No orders yet.</td>
            </tr>
            <?php endif; ?>

            <?php foreach ($orders as $order): ?>
            <tr>
                <td>#<?= (int) $order['order_id'] ?></td>
                <td><?= htmlspecialchars($order['title']) ?></td>
                <td><?= htmlspecialchars($order['buyer_name']) ?></td>
                <td><?= (int) $order['quantity'] ?></td>
                <td>R<?= number_format($order['price'], 2) ?></td>
                <td>
                    <span class="status-badge status-<?= htmlspecialchars($order['order_status']) ?>">
                        <?= ucfirst($order['order_status']) ?>
                    </span>
                </td>
                <td style="display:flex; gap:8px; align-items:center;">

                    <!-- Update Status -->
                    <form method="POST" style="display:flex; gap:5px;">
                        <input type="hidden" name="order_id" value="<?= (int) $order['order_id'] ?>">
                        <select name="status" class="search-select" style="padding:6px;">
                            <?php foreach (['paid','processing','shipped','delivered','cancelled'] as $s): ?>
                                <option value="<?= $s ?>"
                                    <?= $order['order_status'] === $s ? 'selected' : '' ?>>
                                    <?= ucfirst($s) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <button type="submit" class="btn" style="padding:6px 10px;">Update</button>
                    </form>
<br>
                    <!-- Chat with Buyer -->
                    <a class="btn" style="background:#7EC8E3; white-space:nowrap;"
                       href="../messages/chat.php?order_id=<?= (int) $order['order_id'] ?>">
                        💬 Chat
                    </a>

                </td>
            </tr>
            <?php endforeach; ?>

        </table>
    </div>

</div>

<?php require_once '../includes/footer.php'; ?>