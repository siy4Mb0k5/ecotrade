<?php

$host = "localhost";
$dbname = "ecotrade";
$username = "root";
$password = "Siyab0ng@";

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