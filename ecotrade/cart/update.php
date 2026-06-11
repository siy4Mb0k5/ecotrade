<?php

require_once '../config/database.php';
require_once '../includes/auth.php';

requireLogin();

$id       = intval($_POST['id']);
$quantity = intval($_POST['quantity']);

if ($quantity < 1) {
    header("Location: ../cart.php");
    exit;
}

$stmt = $pdo->prepare("UPDATE cart SET quantity = ? WHERE id = ? AND user_id = ?");
$stmt->execute([$quantity, $id, $_SESSION['user_id']]);

header("Location: ../cart.php");
exit;