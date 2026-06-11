<?php

require_once '../config/database.php';
require_once '../includes/auth.php';
require_once '../includes/header.php';

requireLogin();


unset($_SESSION['grand_total']);
unset($_SESSION['first_order_id']);
?>

<div class="payment-success-wrapper">

    <div class="payment-success-card">

        <div class="success-icon">❌</div>

        <h1>Payment Cancelled</h1>

        <p>Your payment was cancelled. Your cart has been cleared but no charge was made.</p>

        <div class="success-details" style="background:#fff3cd; border-color:#ffeeba;">
            <p style="color:#856404;">
                 Your order was not completed. Please try again.
            </p>
        </div>

        <div class="success-actions">
            <a href="../marketplace.php" class="btn" style="background:#7EC8E3;">
                Continue Shopping
            </a>
        </div>

    </div>

</div>

<?php require_once '../includes/footer.php'; ?>