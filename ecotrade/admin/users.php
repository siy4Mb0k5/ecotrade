<?php

require_once '../config/database.php';
require_once '../includes/auth.php';
require_once '../includes/header.php';

requireAdmin();

$users = $pdo->query("SELECT * FROM users")->fetchAll();
?>

<div class="admin-container">

<h1>Users</h1>

<div class="table-container">

<table>

<tr>
<th>Name</th>
<th>Email</th>
<th>Status</th>
<th>Action</th>
</tr>

<?php foreach($users as $user): ?>

<tr>

<td><?= $user['first_name'] ?></td>

<td><?= $user['email'] ?></td>

<td>
<?= $user['is_banned'] ? 'Banned' : 'Active' ?>
</td>

<td>

<?php if(!$user['is_banned']): ?>

<a class="btn"
href="ban-user.php?id=<?= $user['id'] ?>">
Ban
</a>

<?php else: ?>

<span>Blocked</span>

<?php endif; ?>

</td>

</tr>

<?php endforeach; ?>

</table>

</div>

</div>