<nav class="navbar">

    <div class="logo">
        <a href="<?= BASE_URL ?>">EcoTrade</a>
    </div>

    <div class="nav-links">

        <a href="<?= BASE_URL ?>">Home</a>

        <a href="<?= BASE_URL ?>/marketplace.php">Marketplace</a>

        <?php if (isset($_SESSION['user_id'])): ?>

            <?php if (($_SESSION['role'] ?? '') !== 'admin'): ?>
                <a href="<?= BASE_URL ?>/cart.php">Cart</a>
            <?php endif; ?>

            <?php if (($_SESSION['role'] ?? '') === 'admin'): ?>
                <a href="<?= BASE_URL ?>/admin/dashboard.php">Dashboard</a>
            <?php elseif (($_SESSION['is_seller'] ?? 0) == 1): ?>
                <a href="<?= BASE_URL ?>/seller/dashboard.php">Dashboard</a>
                <a href="<?= BASE_URL ?>/user/dashboard.php">My Account</a>
            <?php else: ?>
                <a href="<?= BASE_URL ?>/user/dashboard.php">Dashboard</a>
            <?php endif; ?>

            <a href="<?= BASE_URL ?>/auth/logout.php">Logout</a>

        <?php else: ?>

            <a href="<?= BASE_URL ?>/auth/login.php">Login</a>
            <a href="<?= BASE_URL ?>/auth/register.php">Register</a>

        <?php endif; ?>

    </div>

</nav>