<?php
require_once '../includes/session.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - EcoTrade</title>

    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<div class="auth-container">

    <div class="auth-card">

        <h2>Login</h2>

        <?php if(isset($_SESSION['error'])): ?>
            <div class="error">
                <?= $_SESSION['error']; ?>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <?php if(isset($_SESSION['success'])): ?>
            <div class="success">
                <?= $_SESSION['success']; ?>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <form action="process-login.php" method="POST">

            <input
                type="email"
                name="email"
                placeholder="Email Address"
                required
            >

            <input
                type="password"
                name="password"
                placeholder="Password"
                required
            >

            <button class="btn" type="submit">
                Login
            </button>

        </form>

        <p>
            Don't have an account?
            <a href="register.php">Register</a>
        </p>

    </div>

</div>

</body>
</html>