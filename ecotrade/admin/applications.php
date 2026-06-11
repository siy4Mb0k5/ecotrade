<?php

require_once '../config/database.php';
require_once '../includes/auth.php';
require_once '../includes/header.php';

requireAdmin();

$applications = $pdo->query(
    "SELECT sd.*, u.first_name, u.email
     FROM seller_documents sd
     JOIN users u ON sd.user_id = u.id
     WHERE sd.status = 'pending'"
)->fetchAll();
?>

<div class="admin-container">

    <h1>Seller Applications</h1>

    <?php if (empty($applications)): ?>
        <div class="empty-state">
            <p>No pending applications.</p>
        </div>
    <?php else: ?>

    <div class="table-container">
        <table>
            <tr>
                <th>User</th>
                <th>Email</th>
                <th>Documents</th>
                <th>Action</th>
            </tr>

            <?php foreach ($applications as $app): ?>
            <tr>
                <td><?= htmlspecialchars($app['first_name']) ?></td>
                <td><?= htmlspecialchars($app['email']) ?></td>
                <td>
                    <?php if (!empty($app['id_document'])): ?>
                        <a href="../uploads/seller_docs/<?= htmlspecialchars(basename($app['id_document'])) ?>" target="_blank">ID</a>
                    <?php else: ?>
                        <span>No ID</span>
                    <?php endif; ?>
                    |
                    <?php if (!empty($app['address_document'])): ?>
                        <a href="../uploads/seller_docs/<?= htmlspecialchars(basename($app['address_document'])) ?>" target="_blank">Proof</a>
                    <?php else: ?>
                        <span>No Proof</span>
                    <?php endif; ?>
                </td>
                <td>
                    <a class="btn" href="approve-seller.php?id=<?= (int) $app['user_id'] ?>">Approve</a>
                    <a class="btn" style="background:#e74c3c;"
                       href="reject-seller.php?id=<?= (int) $app['user_id'] ?>"
                       onclick="return confirm('Reject this application?')">Reject</a>
                </td>
            </tr>
            <?php endforeach; ?>

        </table>
    </div>

    <?php endif; ?>

</div>

<?php require_once '../includes/footer.php'; ?>