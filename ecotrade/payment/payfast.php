<?php

require_once '../config/database.php';
require_once '../includes/auth.php';

requireLogin();

$orderId = intval($_GET['order_id']);

$stmt = $pdo->prepare(
    "SELECT orders.*, users.first_name, users.last_name, users.email
     FROM orders
     JOIN users ON orders.user_id = users.id
     WHERE orders.id = ? AND orders.user_id = ?"
);
$stmt->execute([$orderId, $_SESSION['user_id']]);
$order = $stmt->fetch();

if (!$order) {
    header("Location: ../cart.php");
    exit;
}

$merchantId  = '10049773';
$merchantKey = '84x7qwskvzy27';
$passphrase  = '';

// Use grand total from session if multi-seller, otherwise use order total
$paymentAmount = $_SESSION['grand_total'] ?? $order['total_amount'];
$paymentOrderId = $_SESSION['first_order_id'] ?? $orderId;

$data = [
    'merchant_id'   => $merchantId,
    'merchant_key'  => $merchantKey,
    'return_url'    => 'http://localhost/ecotrade/payment/success.php',
    'cancel_url'    => 'http://localhost/ecotrade/payment/cancel.php',
    'notify_url'    => 'http://localhost/ecotrade/payment/notify.php',
    'name_first'    => $order['first_name'],
    'name_last'     => $order['last_name'],
    'email_address' => $order['email'],
    'm_payment_id'  => (string) $paymentOrderId,
    'amount'        => number_format((float) $paymentAmount, 2, '.', ''),
    'item_name'     => 'EcoTrade Order #' . $paymentOrderId,
];

function generateSignature($data, $passphrase = '') {
    $pfOutput = '';
    foreach ($data as $key => $val) {
        if ($val !== '') {
            $pfOutput .= $key . '=' . urlencode($val) . '&';
        }
    }
    $pfOutput = rtrim($pfOutput, '&');
    if ($passphrase !== '') {
        $pfOutput .= '&passphrase=' . urlencode($passphrase);
    }
    return md5($pfOutput);
}

$data['signature'] = generateSignature($data, $passphrase);

require_once '../includes/header.php';
?>

<div class="payment-success-wrapper">

    <div class="payment-success-card">

        <div class="success-icon">🛒</div>

        <h1>Complete Your Payment</h1>

        <p>
            Order #<?= $paymentOrderId ?> —
            Total: <strong>R<?= number_format($paymentAmount, 2) ?></strong>
        </p>

        <div class="success-details" style="background:#fff3cd; border-color:#ffeeba;">
            <p style="color:#856404;">
                💳 You will be redirected to PayFast to complete your payment securely.
            </p>
        </div>

        <form action="https://sandbox.payfast.co.za/eng/process" method="POST" style="margin-top:20px;">
            <?php foreach ($data as $key => $val): ?>
                <input type="hidden" name="<?= $key ?>" value="<?= htmlspecialchars($val) ?>">
            <?php endforeach; ?>
            <button type="submit" class="btn" style="width:100%;">
                Pay R<?= number_format($paymentAmount, 2) ?> via PayFast
            </button>
        </form>

        <a href="../cart.php" style="display:block; margin-top:15px; color:#888; font-size:14px;">
            ← Back to Cart
        </a>

    </div>

</div>

<?php require_once '../includes/footer.php'; ?>