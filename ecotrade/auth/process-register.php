<?php

require_once '../config/database.php';
require_once '../includes/session.php';
require_once '../includes/functions.php';

if($_SERVER['REQUEST_METHOD'] !== 'POST')
{
    header("Location: register.php");
    exit;
}

$first_name = sanitize($_POST['first_name']);
$last_name = sanitize($_POST['last_name']);
$email = sanitize($_POST['email']);
$phone = sanitize($_POST['phone']);

$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];

if(
    empty($first_name) ||
    empty($last_name) ||
    empty($email) ||
    empty($password)
)
{
    $_SESSION['error'] =
    "All required fields must be completed.";

    header("Location: register.php");
    exit;
}

if(!filter_var($email, FILTER_VALIDATE_EMAIL))
{
    $_SESSION['error'] =
    "Please enter a valid email address.";

    header("Location: register.php");
    exit;
}

if(strlen($password) < 8)
{
    $_SESSION['error'] =
    "Password must be at least 8 characters.";

    header("Location: register.php");
    exit;
}

if($password !== $confirm_password)
{
    $_SESSION['error'] =
    "Passwords do not match.";

    header("Location: register.php");
    exit;
}

$checkEmail =
$pdo->prepare(
"SELECT id FROM users WHERE email = ?"
);

$checkEmail->execute([$email]);

if($checkEmail->rowCount() > 0)
{
    $_SESSION['error'] =
    "Email address already exists.";

    header("Location: register.php");
    exit;
}

$hashedPassword =
password_hash(
    $password,
    PASSWORD_DEFAULT
);

$stmt =
$pdo->prepare(
"INSERT INTO users
(
first_name,
last_name,
email,
phone,
password,
role,
is_seller,
seller_status
)
VALUES
(
?,?,?,?,?,
'user',
0,
'none'
)"
);

$stmt->execute([
    $first_name,
    $last_name,
    $email,
    $phone,
    $hashedPassword
]);

$_SESSION['success'] =
"Registration successful. Please login.";

header("Location: login.php");
exit;