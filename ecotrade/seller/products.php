<?php

require_once '../config/database.php';
require_once '../includes/auth.php';
require_once '../includes/header.php';

requireSeller();

$stmt = $pdo->prepare("SELECT * FROM products WHERE seller_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$products = $stmt->fetchAll();
?>

<div class="admin-container">

    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:25px;">
        <h1>My Products</h1>
        <a href="add-product.php" class="btn">+ Add Product</a>
    </div>

    <div class="table-container">

        <table class="seller-products-table">
            <tr>
                <th>Title</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Actions</th>
            </tr>

            <?php if (empty($products)): ?>
            <tr>
                <td colspan="4" style="text-align:center; color:#888;">
                    No products yet. Add your first one!
                </td>
            </tr>
            <?php endif; ?>

            <?php foreach ($products as $product): ?>
            <tr>
                <td><?= htmlspecialchars($product['title']) ?></td>
                <td>R<?= number_format($product['price'], 2) ?></td>
                <td><?= (int) $product['stock_quantity'] ?></td>
                <td>
                    <a class="btn" href="edit-product.php?id=<?= (int) $product['id'] ?>">Edit</a>
                    <a class="btn" style="background:#e74c3c;"
                       href="delete-product.php?id=<?= (int) $product['id'] ?>"
                       onclick="return confirm('Delete this product?')">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>

        </table>

    </div>

</div>

<?php require_once '../includes/footer.php'; ?>