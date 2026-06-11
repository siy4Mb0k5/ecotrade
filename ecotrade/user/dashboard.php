<?php

require_once '../includes/header.php';
require_once '../config/database.php';
require_once '../includes/auth.php';
requireLogin();

$userId = $_SESSION['user_id'];

$stmt = $pdo->prepare(
    "SELECT *
     FROM users
     WHERE id = ?"
);

$stmt->execute([$userId]);

$user = $stmt->fetch();

$sellerStatus = $user['seller_status'] ?? '';

?>

<div class="user-dashboard">

    <div class="welcome-card">
        <div class="welcome-header">

            <div>
                <h1>Welcome, <?= htmlspecialchars($user['first_name'] ?? 'User') ?></h1>
                <p class="welcome-text">Manage your account, orders, and seller applications.</p>
            </div>

            <div class="seller-status">
                <?php if ($sellerStatus === 'approved'): ?>
                    <span class="status approved">Approved Seller</span>
                <?php elseif ($sellerStatus === 'pending'): ?>
                    <span class="status pending">Application Pending</span>
                <?php else: ?>
                    <span class="status normal">Buyer Account</span>
                <?php endif; ?>
            </div>

        </div>
    </div>

<div class="account-section">

    <h2>Account Menu</h2>

    <div class="account-links">

        <a class="account-link" href="orders.php">My Orders</a>

        <a class="account-link" href="../cart.php">My Shopping Cart</a>

        <?php if ($sellerStatus === 'approved'): ?>
            <a class="account-link" href="../seller/dashboard.php">Seller Dashboard</a>
        <?php elseif ($sellerStatus === 'pending'): ?>
            <a class="account-link" href="../seller/application-status.php">Application Status</a>
        <?php else: ?>
            <a class="account-link" href="../auth/apply-seller.php">Become a Seller</a>
        <?php endif; ?>

    </div>

</div>

</div>  