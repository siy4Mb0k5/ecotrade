<?php

require_once 'config/database.php';
require_once 'includes/auth.php';

requireLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['shipping_address'])) {
    header("Location: checkout.php");
    exit;
}

$userId  = $_SESSION['user_id'];
$address = trim($_POST['shipping_address']);

$cart = $pdo->prepare(
    "SELECT cart.*, products.price, products.seller_id
     FROM cart
     JOIN products ON cart.product_id = products.id
     WHERE cart.user_id = ?"
);
$cart->execute([$userId]);
$items = $cart->fetchAll();


if (empty($items)) {
    header("Location: cart.php");
    exit;
}

$pdo->beginTransaction();

try {

    // Group cart items by seller
    $sellers = [];
    foreach ($items as $item) {
        $sellers[$item['seller_id']][] = $item;
    }

    $firstOrderId = null;
    $grandTotal   = 0;

    foreach ($sellers as $sellerId => $sellerItems) {

        // Calculate total for this seller only
        $sellerTotal = 0;
        foreach ($sellerItems as $item) {
            $sellerTotal += $item['price'] * $item['quantity'];
            $grandTotal  += $item['price'] * $item['quantity'];
        }

        // Create a separate order per seller
        $order = $pdo->prepare(
            "INSERT INTO orders (user_id, total_amount, shipping_address)
             VALUES (?, ?, ?)"
        );
        $order->execute([$userId, $sellerTotal, $address]);
        $orderId = $pdo->lastInsertId();

        if (!$firstOrderId) {
            $firstOrderId = $orderId;
        }

        // Insert order items for this seller
        $insertItem = $pdo->prepare(
            "INSERT INTO order_items (order_id, product_id, seller_id, quantity, price)
             VALUES (?, ?, ?, ?, ?)"
        );

        foreach ($sellerItems as $item) {
            $insertItem->execute([
                $orderId,
                $item['product_id'],
                $sellerId,
                $item['quantity'],
                $item['price']
            ]);

            // Reduce stock
            $pdo->prepare(
                "UPDATE products SET stock_quantity = stock_quantity - ? WHERE id = ?"
            )->execute([$item['quantity'], $item['product_id']]);
        }
    }

    // Clear cart
    $pdo->prepare("DELETE FROM cart WHERE user_id = ?")
        ->execute([$userId]);

    $pdo->commit();

    // Store grand total and first order ID in session for PayFast
    $_SESSION['grand_total']    = $grandTotal;
    $_SESSION['first_order_id'] = $firstOrderId;

    header("Location: payment/payfast.php?order_id=" . $firstOrderId);
    exit;

} catch (Exception $e) {
    $pdo->rollBack();
    die("Error: " . $e->getMessage());
}