<?php

require_once '../config/database.php';
require_once '../includes/auth.php';
require_once '../includes/header.php';

requireAdmin();

/* STATS */
$totalUsers = $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn();

$totalProducts = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();

$totalApplications = $pdo->query("SELECT COUNT(*) FROM seller_documents WHERE status='pending'")->fetchColumn();

$bannedUsers = $pdo->query("SELECT COUNT(*) FROM users WHERE is_banned=1")->fetchColumn();
?>

<div class="admin-container">

<h1>Admin Control Panel</h1>

<div class="stats-grid">

<div class="stat-card">
<h3>Users</h3>
<p><?= $totalUsers ?></p>
</div>

<div class="stat-card">
<h3>Products</h3>
<p><?= $totalProducts ?></p>
</div>

<div class="stat-card">
<h3>Pending Applications</h3>
<p><?= $totalApplications ?></p>
</div>

<div class="stat-card">
<h3>Banned Accounts</h3>
<p><?= $bannedUsers ?></p>
</div>

</div>

<div class="admin-actions">

<a href="applications.php" class="admin-btn">View Applications</a>

<a href="users.php" class="admin-btn">Manage Users</a>

<a href="products.php" class="admin-btn">Marketplace Products</a>

</div>

</div>

<?php require_once '../includes/footer.php'; ?>