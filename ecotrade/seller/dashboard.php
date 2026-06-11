<?php

require_once '../config/database.php';
require_once '../includes/auth.php';
require_once '../includes/header.php';

requireSeller();

$sellerId = $_SESSION['user_id'];

$productCount = $pdo->prepare("SELECT COUNT(*) FROM products WHERE seller_id = ?");
$productCount->execute([$sellerId]);
$totalProducts = $productCount->fetchColumn();

$orderCount = $pdo->prepare("SELECT COUNT(*) FROM order_items WHERE seller_id = ?");
$orderCount->execute([$sellerId]);
$totalOrders = $orderCount->fetchColumn();

$revenue = $pdo->prepare("SELECT SUM(price * quantity) FROM order_items WHERE seller_id = ?");
$revenue->execute([$sellerId]);
$totalRevenue = $revenue->fetchColumn() ?: 0;

?>

<div class="admin-container">

    <h1>Seller Dashboard</h1>

    <!-- STATS -->
    <div class="stats-grid">

        <div class="stat-card">
            <h3>Total Products</h3>
            <p><?= (int) $totalProducts ?></p>
        </div>

        <div class="stat-card">
            <h3>Total Orders</h3>
            <p><?= (int) $totalOrders ?></p>
        </div>

        <div class="stat-card">
            <h3>Total Revenue</h3>
            <p>R<?= number_format($totalRevenue, 2) ?></p>
        </div>

    </div>

    <!-- QUICK LINKS -->
    <div class="account-section">

        <h2>Manage</h2>

        <div class="account-links">
            <a class="account-link" href="products.php">Manage Products</a>
            <a class="account-link" href="orders.php">View Orders</a>
            <a class="account-link" href="analytics.php">Analytics</a>
        </div>

    </div>

</div>

<?php require_once '../includes/footer.php'; ?>