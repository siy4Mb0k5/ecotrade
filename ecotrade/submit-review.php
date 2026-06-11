<?php

require_once 'config/database.php';
require_once 'includes/auth.php';

requireLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: marketplace.php");
    exit;
}

$productId = intval($_POST['product_id']);
$rating    = intval($_POST['rating']);
$comment   = trim($_POST['comment'] ?? '');
$userId    = $_SESSION['user_id'];

// Validate rating range
if ($rating < 1 || $rating > 5) {
    header("Location: product.php?id=" . $productId);
    exit;
}

// Prevent duplicate reviews
$check = $pdo->prepare("SELECT id FROM product_reviews WHERE product_id = ? AND user_id = ?");
$check->execute([$productId, $userId]);

if ($check->fetch()) {
    header("Location: product.php?id=" . $productId . "&error=already_reviewed");
    exit;
}

$stmt = $pdo->prepare(
    "INSERT INTO product_reviews (product_id, user_id, rating, comment)
     VALUES (?, ?, ?, ?)"
);
$stmt->execute([$productId, $userId, $rating, $comment]);

header("Location: product.php?id=" . $productId . "&reviewed=1");
exit;