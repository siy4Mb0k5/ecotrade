<?php

$host = "sql305.infinityfree.com";
$dbname = "ecotrade";
$username = "if0_42127634";
$password = "wShNIq4ta1tY6p";

try {

    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password
    );

    $pdo->setAttribute(
        PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION
    );

} catch(PDOException $e) {

    die("Connection failed: " . $e->getMessage());
}
