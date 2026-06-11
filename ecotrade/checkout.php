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

if (empty($items)) {
    header("Location: cart.php");
    exit;
}

$total = 0;
foreach ($items as $item) {
    $total += $item['price'] * $item['quantity'];
}
?>

<div class="checkout-container">

    <h1>Checkout</h1>

    <div class="checkout-grid">

        <div class="checkout-summary">

            <h2>Order Summary</h2>

            <?php foreach ($items as $item): ?>
            <div class="summary-item">
                <p><?= htmlspecialchars($item['title']) ?></p>
                <p>R<?= number_format($item['price'], 2) ?> x <?= (int) $item['quantity'] ?></p>
            </div>
            <?php endforeach; ?>

            <div class="summary-item" style="font-weight:bold; margin-top:10px;">
                <p>Total</p>
                <p>R<?= number_format($total, 2) ?></p>
            </div>

        </div>

        <div class="checkout-form">

       <form action="place-order.php" method="POST">

    <h2>Shipping Details</h2>

    <label>Full Name</label>
    <input type="text" name="full_name" required
           placeholder="John Smith">

    <label>Shipping Address</label>
    <textarea name="shipping_address" required
              placeholder="123 Main Street, City, Province"></textarea>

    <label>Phone Number</label>
    <input type="text" name="phone" required
           placeholder="+27 81 234 5678">

    <hr style="margin:20px 0; border:none; border-top:1px solid #eee;">

    <h2>Payment Details</h2>

    <label>Cardholder Name</label>
    <input type="text" name="card_name" required
           placeholder="John Smith">

    <label>Card Number</label>
    <input type="text" name="card_number" required
           placeholder="1234 5678 9012 3456"
           maxlength="19"
           oninput="formatCardNumber(this)">

    <div style="display:grid; grid-template-columns:1fr 1fr; gap:15px;">

        <div>
            <label>Expiry Date</label>
            <input type="text" name="expiry" required
                   placeholder="MM/YY" maxlength="5"
                   oninput="formatExpiry(this)">
        </div>

        <div>
            <label>CVV</label>
            <input type="password" name="cvv" required
                   placeholder="123" maxlength="4">
        </div>

    </div>

    <button type="submit" class="btn" style="width:100%; margin-top:15px;">
        Place Order
    </button>

</form>

<script>
function formatCardNumber(input) {
    let value = input.value.replace(/\D/g, '').substring(0, 16);
    input.value = value.replace(/(.{4})/g, '$1 ').trim();
}

function formatExpiry(input) {
    let value = input.value.replace(/\D/g, '').substring(0, 4);
    if (value.length >= 2) {
        value = value.substring(0, 2) + '/' + value.substring(2);
    }
    input.value = value;
}
</script>

        </div>

    </div>

</div>

<?php require_once 'includes/footer.php'; ?>