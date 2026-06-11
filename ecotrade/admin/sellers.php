<?php

require_once '../config/database.php';
require_once '../includes/auth.php';

requireAdmin();

$applications =
$pdo->query(
"SELECT *
FROM users
WHERE seller_status='pending'"
)->fetchAll();

SELECT sd.*, u.first_name, u.email
FROM seller_documents sd
JOIN users u ON sd.user_id = u.id
WHERE sd.status='pending'
?>

<h1>
Pending Seller Applications
</h1>
<div class="table-container">
<table border="1">

<tr>

<th>Name</th>
<th>Email</th>
<th>Action</th>

</tr>

<?php foreach(
$applications
as $application
): ?>

<tr>

<td>

<?= htmlspecialchars(
$application['first_name']
) ?>

<?= htmlspecialchars(
$application['last_name']
) ?>

</td>

<td>

<?= htmlspecialchars(
$application['email']
) ?>

</td>

<td>

<a href="
seller-details.php?id=
<?= $application['id'] ?>
">

Review

</a>

</td>

</tr>

<?php endforeach; ?>

</table>
</div>