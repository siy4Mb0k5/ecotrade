<?php

require_once '../config/database.php';
require_once '../includes/session.php';
require_once '../includes/functions.php';

if($_SERVER['REQUEST_METHOD'] !== 'POST')
{
    header("Location: login.php");
    exit;
}

$email = sanitize($_POST['email']);
$password = $_POST['password'];

if(empty($email) || empty($password))
{
    $_SESSION['error'] =
    "Please enter email and password.";

    header("Location: login.php");
    exit;
}

$stmt = $pdo->prepare(
"SELECT * FROM users WHERE email = ?"
);

$stmt->execute([$email]);

$user = $stmt->fetch(PDO::FETCH_ASSOC);

if(!$user)
{
    $_SESSION['error'] =
    "Invalid email or password.";

    header("Location: login.php");
    exit;
}

if(!password_verify(
    $password,
    $user['password']
))
{
    $_SESSION['error'] =
    "Invalid email or password.";

    header("Location: login.php");
    exit;
}

session_regenerate_id(true);

$_SESSION['user_id'] = $user['id'];

$_SESSION['first_name'] =
$user['first_name'];

$_SESSION['last_name'] =
$user['last_name'];

$_SESSION['email'] =
$user['email'];

$_SESSION['role'] =
$user['role'];

$_SESSION['is_seller'] =
$user['is_seller'];

$_SESSION['seller_status'] =
$user['seller_status'];

if($user['role'] === 'admin')
{
    header("Location: ../admin/dashboard.php");
    exit;
}

header("Location: ../user/dashboard.php");
exit;