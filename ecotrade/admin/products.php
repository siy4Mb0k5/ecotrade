<?php

require_once '../config/database.php';
require_once '../includes/auth.php';
require_once '../includes/header.php';

requireAdmin();

$products = $pdo->query(
    "SELECT p.*, u.first_name
     FROM products p
     JOIN users u ON p.seller_id = u.id"
)->fetchAll();
?>

<div class="admin-container">

    <h1>Marketplace Products</h1>

    <div class="table-container">

        <table>
            <tr>
                <th>Product</th>
                <th>Seller</th>
                <th>Price</th>
                <th>Stock</th>
            </tr>

            <?php foreach ($products as $p): ?>
            <tr>
                <td><?= htmlspecialchars($p['title']) ?></td>
                <td><?= htmlspecialchars($p['first_name']) ?></td>
                <td>R<?= number_format($p['price'], 2) ?></td>
                <td><?= (int) $p['stock_quantity'] ?></td>
            </tr>
            <?php endforeach; ?>

        </table>

    </div>

</div>

<?php require_once '../includes/footer.php'; ?>