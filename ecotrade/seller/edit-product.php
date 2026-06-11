<?php

require_once '../config/database.php';
require_once '../includes/auth.php';
require_once '../includes/header.php';

requireSeller();

$id = intval($_GET['id']);

$product = $pdo->prepare("SELECT * FROM products WHERE id = ? AND seller_id = ?");
$product->execute([$id, $_SESSION['user_id']]);
$item = $product->fetch();

if (!$item) {
    die("Product not found.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $title       = trim($_POST['title']);
    $description = trim($_POST['description']);
    $price       = $_POST['price'];
    $stock       = $_POST['stock'];

    if (!empty($_FILES['image']['name'])) {

        $uploadDir = "../uploads/products/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $allowed   = ['jpg', 'jpeg', 'png', 'webp'];
        $extension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

        if (!in_array($extension, $allowed)) {
            die("Invalid image type.");
        }

        $filename = uniqid() . "." . $extension;
        move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $filename);

        $stmt = $pdo->prepare(
            "UPDATE products
             SET title = ?, description = ?, price = ?, stock_quantity = ?, image = ?
             WHERE id = ?"
        );
        $stmt->execute([$title, $description, $price, $stock, $filename, $id]);

    } else {

        $stmt = $pdo->prepare(
            "UPDATE products
             SET title = ?, description = ?, price = ?, stock_quantity = ?
             WHERE id = ?"
        );
        $stmt->execute([$title, $description, $price, $stock, $id]);
    }

    header("Location: products.php");
    exit;
}
?>

<div class="admin-container">

    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:25px;">
        <h1>Edit Product</h1>
        <a href="products.php" class="btn" style="background:#7EC8E3;">← Back to Products</a>
    </div>

    <div class="apply-card" style="max-width:600px;">

        <form method="POST" enctype="multipart/form-data">

            <label>Product Title</label>
            <input type="text" name="title" required
                   value="<?= htmlspecialchars($item['title']) ?>">

            <label>Description</label>
            <textarea name="description" rows="4"
                      required><?= htmlspecialchars($item['description']) ?></textarea>

            <label>Price (R)</label>
            <input type="number" step="0.01" name="price" required
                   value="<?= htmlspecialchars($item['price']) ?>">

            <label>Stock Quantity</label>
            <input type="number" name="stock" required
                   value="<?= htmlspecialchars($item['stock_quantity']) ?>">

            <label>
                Update Image
                <span style="color:#888; font-size:13px;">(leave blank to keep current)</span>
            </label>
            <input type="file" name="image" accept=".jpg,.jpeg,.png,.webp">

            <button type="submit" class="btn" style="width:100%; margin-top:15px;">
                Update Product
            </button>

        </form>

    </div>

</div>

<?php require_once '../includes/footer.php'; ?>