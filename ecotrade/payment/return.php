<?php

require_once '../includes/session.php';
require_once '../includes/header.php';
?>

<div class="payment-success-wrapper">

    <div class="payment-success-card">

        <div class="success-icon">✅</div>

        <h1>Payment Successful!</h1>

        <p>Thank you for your order. We've received your payment and your order is now being processed.</p>

        <div class="success-details">
            <p> You'll receive a confirmation shortly.</p>
            <p> Track your order status below.</p>
        </div>

        <div class="success-actions">
            <a href="../user/orders.php" class="btn">View My Orders</a>
            <a href="../marketplace.php" class="btn" style="background:#7EC8E3;">Continue Shopping</a>
        </div>

    </div>

</div>

<?php require_once '../includes/footer.php'; ?>