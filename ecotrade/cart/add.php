<?php

require_once '../config/database.php';
require_once '../includes/auth.php';

requireLogin();

$productId = intval($_POST['product_id']);
$quantity  = intval($_POST['quantity']);
$userId    = $_SESSION['user_id'];

if ($productId < 1 || $quantity < 1) {
    header("Location: ../marketplace.php");
    exit;
}

$check = $pdo->prepare("SELECT id, quantity FROM cart WHERE user_id = ? AND product_id = ?");
$check->execute([$userId, $productId]);
$existing = $check->fetch();

if ($existing) {
    $update = $pdo->prepare("UPDATE cart SET quantity = quantity + ? WHERE id = ?");
    $update->execute([$quantity, $existing['id']]);
} else {
    $insert = $pdo->prepare("INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, ?)");
    $insert->execute([$userId, $productId, $quantity]);
}

header("Location: ../cart.php");
exit;