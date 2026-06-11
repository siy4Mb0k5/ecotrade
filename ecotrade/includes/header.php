<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/session.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <meta name="theme-color" content="#FF6F61">
    <meta name="description" content="EcoTrade - Buy and sell sustainably with local traders">
    <title>EcoTrade</title>

    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
</head>
<body>

<?php require_once 'navbar.php'; ?>