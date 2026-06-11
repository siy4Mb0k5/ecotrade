<?php

require_once 'session.php';

function requireLogin()
{
    if(!isset($_SESSION['user_id']))
    {
        header(
        "Location: ../auth/login.php"
        );
        exit;
    }
}

function requireAdmin()
{
    requireLogin();

    if(
        !isset($_SESSION['role']) ||
        $_SESSION['role'] !== 'admin'
    )
    {
        die("Access denied.");
    }
}

function requireSeller()
{
    requireLogin();


    if(
        $_SESSION['is_seller'] != 1 ||
        $_SESSION['seller_status']
        !== 'approved'
    )
    {
        die(
        "Seller account not approved."
        );
    }
}