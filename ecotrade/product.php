<?php

require_once 'config/database.php';
require_once 'includes/header.php';

$id = intval($_GET['id']);

$stmt = $pdo->prepare(
    "SELECT
        products.*,
        users.first_name,
        users.last_name,
        categories.name AS category_name
     FROM products
     JOIN users ON products.seller_id = users.id
     JOIN categories ON products.category_id = categories.id
     WHERE products.id = ?"
);
$stmt->execute([$id]);
$product = $stmt->fetch();

if (!$product) {
    die("Product not found");
}

$rating = $pdo->prepare(
    "SELECT AVG(rating) AS avg_rating FROM product_reviews WHERE product_id = ?"
);
$rating->execute([$id]);
$average = $rating->fetch();

$reviews = $pdo->prepare(
    "SELECT product_reviews.*, users.first_name
     FROM product_reviews
     JOIN users ON product_reviews.user_id = users.id
     WHERE product_reviews.product_id = ?
     ORDER BY product_reviews.created_at DESC"
);
$reviews->execute([$id]);
$allReviews = $reviews->fetchAll();

$isAdmin = ($_SESSION['role'] ?? '') === 'admin';
?>

<!-- PRODUCT DETAILS -->
<div class="product-page-wrapper">
    <div class="product-page">

        <div class="product-image-box">
            <img src="uploads/products/<?= htmlspecialchars($product['image']) ?>"
                 alt="<?= htmlspecialchars($product['title']) ?>">
        </div>

        <div class="product-details">

            <p class="product-category-tag">
                <?= htmlspecialchars($product['category_name']) ?>
            </p>

            <h1><?= htmlspecialchars($product['title']) ?></h1>

            <p class="product-price">
                R<?= number_format($product['price'], 2) ?>
            </p>

            <p class="product-rating">
                <?php
                    $avg = round($average['avg_rating'] ?? 0, 1);
                    $stars = str_repeat('⭐', (int) round($avg));
                ?>
                <?= $stars ?> <?= $avg > 0 ? "({$avg})" : 'No reviews yet' ?>
            </p>

            <p class="product-description">
                <?= nl2br(htmlspecialchars($product['description'])) ?>
            </p>

            <p class="product-stock">
                <?= $product['stock_quantity'] > 0
                    ? '✅ ' . (int) $product['stock_quantity'] . ' in stock'
                    : '❌ Out of stock' ?>
            </p>

            <?php if (isset($_SESSION['user_id']) && !$isAdmin): ?>
                <form action="cart/add.php" method="POST" class="add-to-cart-form">
                    <input type="hidden" name="product_id" value="<?= (int) $product['id'] ?>">
                    <input type="number" name="quantity" value="1" min="1"
                           max="<?= (int) $product['stock_quantity'] ?>"
                           class="qty-input">
                    <button class="btn" <?= $product['stock_quantity'] < 1 ? 'disabled' : '' ?>>
                        Add to Cart
                    </button>
                </form>
            <?php elseif (!isset($_SESSION['user_id'])): ?>
                <a href="auth/login.php" class="btn">Login to Purchase</a>
            <?php endif; ?>

            <p class="product-seller">
                Sold by
                <a href="seller-profile.php?id=<?= (int) $product['seller_id'] ?>">
                    <?= htmlspecialchars($product['first_name']) ?>
                    <?= htmlspecialchars($product['last_name']) ?>
                </a>
            </p>

        </div>

    </div>
</div>
<!-- END PRODUCT DETAILS -->

<!-- REVIEW ERRORS -->
<?php if (isset($_GET['error']) && $_GET['error'] === 'already_reviewed'): ?>
    <div class="error" style="max-width:1100px; margin:20px auto; padding: 0 30px;">
        You have already reviewed this product.
    </div>
<?php endif; ?>

<!-- REVIEW FORM -->
<?php if (isset($_SESSION['user_id']) && !$isAdmin): ?>
<div class="review-form-wrapper">

    <h2>Leave a Review</h2>

    <?php if (isset($_GET['reviewed'])): ?>
        <div class="success">Review submitted successfully!</div>
    <?php endif; ?>

    <form action="submit-review.php" method="POST" class="review-form">
        <input type="hidden" name="product_id" value="<?= (int) $product['id'] ?>">

        <label>Rating</label>
        <div class="star-rating">
            <?php for ($i = 5; $i >= 1; $i--): ?>
                <input type="radio" name="rating" id="star<?= $i ?>" value="<?= $i ?>" required>
                <label for="star<?= $i ?>">★</label>
            <?php endfor; ?>
        </div>

        <label>Comment</label>
        <textarea name="comment" rows="3" placeholder="Share your experience..."></textarea>

        <button type="submit" class="btn" style="margin-top:10px;">Submit Review</button>
    </form>

</div>
<?php endif; ?>

<!-- EXISTING REVIEWS -->
<?php if (!empty($allReviews)): ?>
<div class="reviews-wrapper">

    <h2>Customer Reviews (<?= count($allReviews) ?>)</h2>

    <?php foreach ($allReviews as $review): ?>
    <div class="review-card">
        <div class="review-header">
            <span class="review-author"><?= htmlspecialchars($review['first_name']) ?></span>
            <span class="review-stars">
                <?= str_repeat('★', (int) $review['rating']) ?>
                <?= str_repeat('☆', 5 - (int) $review['rating']) ?>
            </span>
            <span class="review-date"><?= date('d M Y', strtotime($review['created_at'])) ?></span>
        </div>
        <?php if (!empty($review['comment'])): ?>
            <p class="review-comment"><?= nl2br(htmlspecialchars($review['comment'])) ?></p>
        <?php endif; ?>
    </div>
    <?php endforeach; ?>

</div>
<?php endif; ?>

<?php require_once 'includes/footer.php'; ?>

