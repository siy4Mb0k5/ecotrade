<?php
require_once '../includes/session.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - EcoTrade</title>

    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<div class="auth-container">

    <div class="auth-card">

        <h2>Create Account</h2>

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

        <form action="process-register.php" method="POST">

            <input
                type="text"
                name="first_name"
                placeholder="First Name"
                required
            >

            <input
                type="text"
                name="last_name"
                placeholder="Last Name"
                required
            >

            <input
                type="email"
                name="email"
                placeholder="Email Address"
                required
            >

            <input
                type="text"
                name="phone"
                placeholder="Phone Number"
            >

            <input
                type="password"
                name="password"
                placeholder="Password"
                required
            >

            <input
                type="password"
                name="confirm_password"
                placeholder="Confirm Password"
                required
            >

            <button class="btn" type="submit">
                Register
            </button>

        </form>

        <p>
            Already have an account?
            <a href="login.php">Login</a>
        </p>

    </div>

</div>

</body>
</html>