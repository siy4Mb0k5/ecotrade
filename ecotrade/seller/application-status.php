<?php

require_once '../config/database.php';
require_once '../includes/auth.php';
require_once '../includes/header.php';

requireLogin();

$stmt =
$pdo->prepare(
"SELECT
 seller_status,
 rejection_reason
 FROM users
 WHERE id=?"
);

$stmt->execute([
$_SESSION['user_id']
]);

$user = $stmt->fetch();

?>

<link rel="stylesheet"
href="<?= BASE_URL ?>/assets/css/style.css">

<div class="status-container">




    <br>
    <br>

<h2>

Status:

<?= ucfirst(
$user['seller_status']
) ?>

</h2>

<?php if(
$user['seller_status']
=== 'rejected'
): ?>

<p>

Reason:

<?= htmlspecialchars(
$user['rejection_reason']
) ?>

</p>
        </div>
<?php endif; ?>

<?php if($user['seller_status'] === 'rejected'): ?>

<div class="rejection-box">

<h3>Application Rejected</h3>

<p>
<?= htmlspecialchars($user['rejection_reason'] ?? 'No reason provided') ?>
</p>

</div>

<?php endif; ?>