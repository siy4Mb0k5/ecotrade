<?php

require_once '../config/database.php';
require_once '../includes/auth.php';

requireLogin();

$id = intval($_GET['id']);

$stmt = $pdo->prepare("DELETE FROM cart WHERE id = ? AND user_id = ?");
$stmt->execute([$id, $_SESSION['user_id']]);

header("Location: ../cart.php");
exit;