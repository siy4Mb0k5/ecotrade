<?php

require_once '../config/database.php';
require_once '../includes/auth.php';

requireAdmin();

$id =
intval($_GET['id']);

$user =
$pdo->prepare(
"SELECT *
 FROM users
 WHERE id=?"
);

$user->execute([$id]);

$documents =
$pdo->prepare(
"SELECT *
 FROM seller_documents
 WHERE user_id=?"
);

$documents->execute([$id]);

$docs =
$documents->fetchAll();
?>

<h1>

Review Seller

</h1>

<?php foreach($docs as $doc): ?>

<p>

<?= $doc['document_type'] ?>

</p>

<a target="_blank"
href="../uploads/documents/
<?= $doc['file_path'] ?>">

View Document

</a>

<hr>

<?php endforeach; ?>

<a href="
approve-seller.php?id=
<?= $id ?>
">

Approve

</a>

|

<a href="
reject-seller.php?id=
<?= $id ?>
">

Reject

</a>