<?php

require_once '../config/database.php';
require_once '../includes/auth.php';
require_once '../includes/header.php';

requireLogin();
?>

<div class="apply-container">

    <div class="apply-card">

        <h1>Become a Seller</h1>
        <p>Submit your documents below and we'll review your application shortly.</p>

        <form action="submit-seller.php" method="POST" enctype="multipart/form-data">

            <label>Upload ID Document</label>
            <input type="file" name="id_document" required>

            <label>Proof of Address</label>
            <input type="file" name="address_document" required>

            <label>Optional Notes</label>
            <textarea name="notes" rows="4" placeholder="Any additional information..."></textarea>

            <button type="submit" class="btn" style="width:100%; margin-top:10px;">
                Submit Application
            </button>

        </form>

    </div>

</div>

<?php require_once '../includes/footer.php'; ?>