<?php

require_once '../config/database.php';
require_once '../includes/auth.php';
require_once '../includes/header.php';

requireAdmin();

$orders = $pdo->query(
    "SELECT orders.*, users.first_name, users.last_name
     FROM orders
     JOIN users ON orders.user_id = users.id
     ORDER BY orders.id DESC"
)->fetchAll();
?>

<div class="admin-container">

    <h1>All Orders</h1>

    <div class="table-container">
        <table>
            <tr>
                <th>Order #</th>
                <th>Customer</th>
                <th>Total</th>
                <th>Payment</th>
                <th>Status</th>
            </tr>

            <?php if (empty($orders)): ?>
            <tr>
                <td colspan="5" style="text-align:center; color:#888;">No orders yet.</td>
            </tr>
            <?php endif; ?>

            <?php foreach ($orders as $order): ?>
            <tr>
                <td>#<?= (int) $order['id'] ?></td>
                <td>
                    <?= htmlspecialchars($order['first_name']) ?>
                    <?= htmlspecialchars($order['last_name']) ?>
                </td>
                <td>R<?= number_format($order['total_amount'], 2) ?></td>
                <td>
                    <span class="status-badge status-<?= strtolower($order['payment_status']) ?>">
                        <?= ucfirst($order['payment_status']) ?>
                    </span>
                </td>
                <td>
                    <form action="update-order.php" method="POST" style="display:flex; gap:5px;">
                        <input type="hidden" name="order_id" value="<?= (int) $order['id'] ?>">
                        <select name="status" class="search-select" style="padding:6px;">
                            <?php foreach (['pending','paid','processing','shipped','delivered','cancelled'] as $s): ?>
                                <option value="<?= $s ?>" <?= $order['order_status'] === $s ? 'selected' : '' ?>>
                                    <?= ucfirst($s) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <button type="submit" class="btn" style="padding:6px 10px;">Update</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>

        </table>
    </div>

</div>

<?php require_once '../includes/footer.php'; ?>