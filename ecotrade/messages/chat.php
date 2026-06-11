<?php

require_once '../config/database.php';
require_once '../includes/auth.php';
require_once '../includes/header.php';

requireLogin();

$orderId  = intval($_GET['order_id']);
$userId   = $_SESSION['user_id'];

// Fetch order and verify user is buyer or seller of this order
$stmt = $pdo->prepare(
    "SELECT orders.*, order_items.seller_id,
            buyers.first_name AS buyer_name,
            sellers.first_name AS seller_name
     FROM orders
     JOIN order_items ON orders.id = order_items.order_id
     JOIN users buyers ON orders.user_id = buyers.id
     JOIN users sellers ON order_items.seller_id = sellers.id
     WHERE orders.id = ?
     LIMIT 1"
);
$stmt->execute([$orderId]);
$order = $stmt->fetch();

if (!$order) {
    die("Order not found.");
}

// Only buyer or seller can access this chat
$isBuyer  = $userId === (int) $order['user_id'];
$isSeller = $userId === (int) $order['seller_id'];

if (!$isBuyer && !$isSeller) {
    die("Access denied.");
}

$receiverId = $isBuyer ? $order['seller_id'] : $order['user_id'];

// Handle message send
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['message'])) {
    $msg = $pdo->prepare(
        "INSERT INTO messages (order_id, sender_id, receiver_id, message)
         VALUES (?, ?, ?, ?)"
    );
    $msg->execute([$orderId, $userId, $receiverId, trim($_POST['message'])]);
    header("Location: chat.php?order_id=" . $orderId);
    exit;
}

// Fetch messages
$messages = $pdo->prepare(
    "SELECT messages.*, users.first_name
     FROM messages
     JOIN users ON messages.sender_id = users.id
     WHERE messages.order_id = ?
     ORDER BY messages.created_at ASC"
);
$messages->execute([$orderId]);
$chats = $messages->fetchAll();
?>

<div class="chat-wrapper">

    <div class="chat-card">

        <div class="chat-header">
            <h2>Order #<?= $orderId ?> — Chat</h2>
            <p>
                <?php if ($isBuyer): ?>
                    Chatting with seller: <strong><?= htmlspecialchars($order['seller_name']) ?></strong>
                <?php else: ?>
                    Chatting with buyer: <strong><?= htmlspecialchars($order['buyer_name']) ?></strong>
                <?php endif; ?>
            </p>
        </div>

        <div class="chat-messages" id="chatMessages">
            <?php if (empty($chats)): ?>
                <p class="chat-empty">No messages yet. Start the conversation!</p>
            <?php endif; ?>

            <?php foreach ($chats as $chat): ?>
                <div class="chat-bubble <?= $chat['sender_id'] == $userId ? 'chat-mine' : 'chat-theirs' ?>">
                    <span class="chat-name"><?= htmlspecialchars($chat['first_name']) ?></span>
                    <p><?= nl2br(htmlspecialchars($chat['message'])) ?></p>
                    <span class="chat-time">
                        <?= date('d M, H:i', strtotime($chat['created_at'])) ?>
                    </span>
                </div>
            <?php endforeach; ?>
        </div>

        <form method="POST" class="chat-form">
            <textarea name="message" placeholder="Type your message..." required rows="2"></textarea>
            <button type="submit" class="btn">Send</button>
        </form>

    </div>

</div>

<script>
    // Auto scroll to bottom of chat
    const chatBox = document.getElementById('chatMessages');
    chatBox.scrollTop = chatBox.scrollHeight;
</script>

<?php require_once '../includes/footer.php'; ?>