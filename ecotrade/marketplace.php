<?php

require_once 'config/database.php';
require_once 'includes/header.php';

$search   = $_GET['search'] ?? '';
$category = $_GET['category'] ?? '';

$sql = "
    SELECT
        products.*,
        categories.name AS category_name,
        users.first_name,
        users.last_name
    FROM products
    JOIN categories ON products.category_id = categories.id
    JOIN users ON products.seller_id = users.id
    WHERE products.status = 'active'
";

$params = [];

if (!empty($search)) {
    $sql .= " AND products.title LIKE ?";
    $params[] = "%$search%";
}

if (!empty($category)) {
    $sql .= " AND products.category_id = ?";
    $params[] = $category;
}

$sql .= " ORDER BY products.created_at DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll();

$categories = $pdo->query("SELECT * FROM categories")->fetchAll();
?>

<div class="marketplace-container">

    <h1 class="marketplace-title">Marketplace</h1>

    <!-- SEARCH BAR -->
    <form method="GET" class="search-container">

        <input
            type="text"
            name="search"
            placeholder="Search products..."
            value="<?= htmlspecialchars($search) ?>"
            class="search-input">

        <select name="category" class="search-select">
            <option value="">All Categories</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= (int) $cat['id'] ?>"
                    <?= $category == $cat['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($cat['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <button type="submit" class="search-btn">Search</button>

    </form>

    <!-- RESULTS COUNT -->
    <p class="results-count">
        <?= count($products) ?> product<?= count($products) !== 1 ? 's' : '' ?> found
    </p>

    <!-- PRODUCT GRID -->
    <?php if (empty($products)): ?>

        <div class="empty-state">
            <p>No products found. Try a different search.</p>
            <a href="marketplace.php" class="btn">Clear Search</a>
        </div>

    <?php else: ?>

        <div class="product-grid">
            <?php foreach ($products as $product): ?>

                <div class="product-card">

                    <div class="product-image">
                        <img
                            src="uploads/products/<?= htmlspecialchars($product['image']) ?>"
                            alt="<?= htmlspecialchars($product['title']) ?>">
                    </div>

                    <div class="product-body">

                        <p class="product-category">
                            <?= htmlspecialchars($product['category_name']) ?>
                        </p>

                        <h3 class="product-title">
                            <?= htmlspecialchars($product['title']) ?>
                        </h3>

                        <p class="price">
                            R<?= number_format($product['price'], 2) ?>
                        </p>

                        <p class="product-seller">
                            By <?= htmlspecialchars($product['first_name']) ?>
                            <?= htmlspecialchars($product['last_name']) ?>
                        </p>

                        <a class="btn" style="width:100%; text-align:center; display:block; margin-top:10px;"
                           href="product.php?id=<?= (int) $product['id'] ?>">
                            View Product
                        </a>

                    </div>

                </div>

            <?php endforeach; ?>
        </div>

    <?php endif; ?>

</div>

<?php require_once 'includes/footer.php'; ?>