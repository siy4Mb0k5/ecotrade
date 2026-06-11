<?php

require_once 'config/database.php';
require_once 'includes/header.php';

$id =
intval($_GET['id']);

$user =
$pdo->prepare(
"SELECT *
FROM users
WHERE id=?"
);

$user->execute([$id]);

$seller =
$user->fetch();

if(!$seller)
{
    die("Seller not found");
}

$products =
$pdo->prepare(
"SELECT *
FROM products
WHERE seller_id=?"
);

$products->execute([$id]);

$listings =
$products->fetchAll();
?>

<h1>

<?= htmlspecialchars(
$seller['first_name']
) ?>

<?= htmlspecialchars(
$seller['last_name']
) ?>

</h1>

<h2>Products</h2>

<?php foreach(
$listings
as $item
): ?>

<div>

<a href="
product.php?id=
<?= $item['id'] ?>
">

<?= htmlspecialchars(
$item['title']
) ?>

</a>

—

R<?= number_format(
$item['price'],
2
) ?>

</div>

<?php endforeach; ?>

<?php require_once 'includes/footer.php'; ?>