<?php

function sanitize($data)
{
    return htmlspecialchars(
        trim($data),
        ENT_QUOTES,
        'UTF-8'
    );
}

function redirect($url)
{
    header("Location: $url");
    exit;
}

function isLoggedIn()
{
    return isset($_SESSION['user_id']);
}