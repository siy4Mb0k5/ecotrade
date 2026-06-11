<?php

require_once '../config/database.php';
require_once '../includes/auth.php';

requireSeller();

$id =
intval($_GET['id']);

$stmt =
$pdo->prepare(
"DELETE FROM products
 WHERE id=?
 AND seller_id=?"
);

$stmt->execute([
$id,
$_SESSION['user_id']
]);

header(
"Location: products.php"
);