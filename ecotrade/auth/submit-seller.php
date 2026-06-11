<?php

require_once '../config/database.php';
require_once '../includes/auth.php';

requireLogin();

$userId = $_SESSION['user_id'];
$uploadDir = "../uploads/seller_docs/";

if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}


$allowed = ['jpg', 'jpeg', 'png', 'pdf'];


$idExt = strtolower(pathinfo($_FILES['id_document']['name'], PATHINFO_EXTENSION));
if (!in_array($idExt, $allowed)) {
    die("Invalid file type for ID document.");
}
$idName = time() . "_id_" . basename($_FILES['id_document']['name']);
move_uploaded_file($_FILES['id_document']['tmp_name'], $uploadDir . $idName);


$addrExt = strtolower(pathinfo($_FILES['address_document']['name'], PATHINFO_EXTENSION));
if (!in_array($addrExt, $allowed)) {
    die("Invalid file type for address document.");
}
$addressName = time() . "_addr_" . basename($_FILES['address_document']['name']);
move_uploaded_file($_FILES['address_document']['tmp_name'], $uploadDir . $addressName);


$stmt = $pdo->prepare(
    "INSERT INTO seller_documents
     (user_id, id_document, address_document, notes, status)
     VALUES (?, ?, ?, ?, 'pending')"
);
$stmt->execute([
    $userId,
    $idName,
    $addressName,
    $_POST['notes'] ?? ''
]);


$update = $pdo->prepare(
    "UPDATE users SET seller_status = 'pending' WHERE id = ?"
);
$update->execute([$userId]);

header("Location: ../seller/application-status.php");
exit;