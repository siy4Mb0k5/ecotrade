<?php

require_once 'config/database.php';
require_once 'includes/auth.php';
require_once 'includes/header.php';

requireLogin();

$userId = $_SESSION['user_id'];

$stmt = $pdo->prepare(
    "SELECT cart.*, products.title, products.price, products.image
     FROM cart
     JOIN products ON cart.product_id = products.id
     WHERE cart.user_id = ?"
);
$stmt->execute([$userId]);
$items = $stmt->fetchAll();

$total = 0;
?>

<div class="cart-container">

    <h1>Shopping Cart</h1>

    <?php if (empty($items)): ?>

        <div class="empty-state">
            <p>Your cart is empty.</p>
            <a href="marketplace.php" class="btn">Browse Products</a>
        </div>

    <?php else: ?>

        <div class="table-container">
            <table>
                <tr>
                    <th>Image</th>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Qty</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>

                <?php foreach ($items as $item):
                    $lineTotal = $item['price'] * $item['quantity'];
                    $total += $lineTotal;
                ?>
                <tr>
                    <td>
                        <img src="uploads/products/<?= htmlspecialchars($item['image']) ?>"
                             style="width:60px; height:60px; object-fit:cover; border-radius:6px;">
                    </td>
                    <td><?= htmlspecialchars($item['title']) ?></td>
                    <td>R<?= number_format($item['price'], 2) ?></td>
                    <td>
                        <form action="cart/update.php" method="POST" style="display:flex; gap:5px;">
                            <input type="hidden" name="id" value="<?= (int) $item['id'] ?>">
                            <input type="number" name="quantity" value="<?= (int) $item['quantity'] ?>"
                                   min="1" style="width:60px; padding:5px; border:2px solid #7EC8E3; border-radius:6px;">
                            <button class="btn" style="padding:5px 10px;">Update</button>
                        </form>
                    </td>
                    <td>R<?= number_format($lineTotal, 2) ?></td>
                    <td>
                        <a class="btn" style="background:#e74c3c;"
                           href="cart/remove.php?id=<?= (int) $item['id'] ?>"
                           onclick="return confirm('Remove this item?')">Remove</a>
                    </td>
                </tr>
                <?php endforeach; ?>

            </table>
        </div>

        <div class="cart-summary">
            <h2>Total: R<?= number_format($total, 2) ?></h2>
            <a href="checkout.php" class="checkout-btn">Proceed to Checkout</a>
        </div>

    <?php endif; ?>

</div>

<?php require_once 'includes/footer.php'; ?>