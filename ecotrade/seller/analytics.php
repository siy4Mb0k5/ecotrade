<?php

require_once '../config/database.php';
require_once '../includes/auth.php';
require_once '../includes/header.php';

requireSeller();

$stmt = $pdo->prepare(
    "SELECT
        products.title,
        SUM(order_items.quantity) AS sold
     FROM order_items
     JOIN products ON products.id = order_items.product_id
     WHERE order_items.seller_id = ?
     GROUP BY order_items.product_id, products.title
     ORDER BY sold DESC"
);

$stmt->execute([$_SESSION['user_id']]);
$stats = $stmt->fetchAll();
?>

<div class="admin-container">

    <h1>Sales Analytics</h1>

    <div class="table-container">

        <table>
            <tr>
                <th>Product</th>
                <th>Total Sold</th>
            </tr>

            <?php if (empty($stats)): ?>
            <tr>
                <td colspan="2" style="text-align:center; color:#888;">
                    No sales data yet.
                </td>
            </tr>
            <?php endif; ?>

            <?php foreach ($stats as $row): ?>
            <tr>
                <td><?= htmlspecialchars($row['title']) ?></td>
                <td><?= (int) $row['sold'] ?></td>
            </tr>
            <?php endforeach; ?>

        </table>

    </div>

</div>

<?php require_once '../includes/footer.php'; ?>