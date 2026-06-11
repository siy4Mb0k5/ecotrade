<?php

require_once '../config/database.php';
require_once '../includes/auth.php';
require_once '../includes/header.php';

requireSeller();

$uploadDir = "../uploads/products/";

if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

$categories = $pdo->query("SELECT * FROM categories")->fetchAll();

$allowed = ['jpg', 'jpeg', 'png', 'webp'];
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $title       = trim($_POST['title']);
    $description = trim($_POST['description']);
    $price       = $_POST['price'];
    $stock       = $_POST['stock'];
    $category    = $_POST['category'];
    $image       = $_FILES['image'];

    $extension = strtolower(pathinfo($image['name'], PATHINFO_EXTENSION));

    if (!in_array($extension, $allowed)) {
        $errors[] = "Invalid image type. Allowed: jpg, jpeg, png, webp.";
    }

    if (empty($errors)) {

        $filename = uniqid() . "." . $extension;
        move_uploaded_file($image['tmp_name'], "../uploads/products/" . $filename);

        $stmt = $pdo->prepare(
            "INSERT INTO products
             (seller_id, category_id, title, description, price, stock_quantity, image)
             VALUES (?, ?, ?, ?, ?, ?, ?)"
        );

        $stmt->execute([
            $_SESSION['user_id'],
            $category,
            $title,
            $description,
            $price,
            $stock,
            $filename
        ]);

        header("Location: products.php");
        exit;
    }
}
?>

<div class="admin-container">

    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:25px;">
        <h1>Add Product</h1>
        <a href="products.php" class="btn" style="background:#7EC8E3;">← Back to Products</a>
    </div>

    <div class="apply-card" style="max-width:600px;">

        <?php if (!empty($errors)): ?>
            <?php foreach ($errors as $error): ?>
                <div class="error"><?= htmlspecialchars($error) ?></div>
            <?php endforeach; ?>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">

            <label>Product Title</label>
            <input type="text" name="title" placeholder="Product Title" required
                   value="<?= htmlspecialchars($_POST['title'] ?? '') ?>">

            <label>Description</label>
            <textarea name="description" rows="4"
                      placeholder="Describe your product..."
                      required><?= htmlspecialchars($_POST['description'] ?? '') ?></textarea>

            <label>Category</label>
            <select name="category" class="search-select" style="width:100%; margin:10px 0;">
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= (int) $cat['id'] ?>"
                        <?= (isset($_POST['category']) && $_POST['category'] == $cat['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label>Price (R)</label>
            <input type="number" step="0.01" name="price" placeholder="0.00" required
                   value="<?= htmlspecialchars($_POST['price'] ?? '') ?>">

            <label>Stock Quantity</label>
            <input type="number" name="stock" placeholder="0" required
                   value="<?= htmlspecialchars($_POST['stock'] ?? '') ?>">

            <label>Product Image</label>
            <input type="file" name="image" accept=".jpg,.jpeg,.png,.webp" required>

            <button type="submit" class="btn" style="width:100%; margin-top:15px;">
                Add Product
            </button>

        </form>

    </div>

</div>

<?php require_once '../includes/footer.php'; ?>