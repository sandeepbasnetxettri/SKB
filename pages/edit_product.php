<?php
$basePath = "../";
$pageTitle = "Edit Product";
$headerClass = "scrolled";

include_once $basePath . 'includes/auth.php';
include_once $basePath . 'includes/product_functions.php';

// Access Control
if (!isAdmin()) {
    header("Location: login.php");
    exit();
}

$productId = isset($_GET['id']) ? intval($_GET['id']) : 0;
$product = getProductById($productId);

if (!$product) {
    header("Location: shop.php");
    exit();
}

$message = "";
$messageType = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['p-name'];
    $price = $_POST['p-price'];
    $category = $_POST['p-category'];
    $description = $_POST['p-desc'];
    $imagePath = null;

    // Handle Image Upload
    if (isset($_FILES['p-image']) && $_FILES['p-image']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['p-image']['tmp_name'];
        $fileName = $_FILES['p-image']['name'];
        $fileSize = $_FILES['p-image']['size'];
        $fileType = $_FILES['p-image']['type'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
        $uploadFileDir = $basePath . 'assets/';
        $dest_path = $uploadFileDir . $newFileName;

        if (move_uploaded_file($fileTmpPath, $dest_path)) {
            $imagePath = 'assets/' . $newFileName;
        }
    }

    if (updateProduct($productId, $name, $price, $category, $description, $imagePath)) {
        $message = "Product updated successfully!";
        $messageType = "success";
        // Refresh product data
        $product = getProductById($productId);
    } else {
        $message = "Error updating product.";
        $messageType = "error";
    }
}

include $basePath . 'includes/header.php';
?>

<main class="auth-page">
    <div class="container">
        <div class="auth-container fade-in-up" style="max-width: 600px;">
            <div class="auth-header">
                <h1>Edit Product</h1>
                <p>Modify details for: <?php echo htmlspecialchars($product['name']); ?></p>
            </div>

            <?php if ($message): ?>
                <div class="notification show" style="position: static; margin-bottom: 2rem; background: <?php echo $messageType === 'success' ? '#10b981' : '#ef4444'; ?>">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <form action="" method="POST" enctype="multipart/form-data" class="auth-form">
                <div class="form-group">
                    <label for="p-name">Product Name</label>
                    <input type="text" id="p-name" name="p-name" value="<?php echo htmlspecialchars($product['name']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="p-price">Price (Rs.)</label>
                    <input type="number" id="p-price" name="p-price" value="<?php echo $product['price']; ?>" step="0.01" required>
                </div>
                <div class="form-group">
                    <label for="p-category">Category</label>
                    <select id="p-category" name="p-category" style="width: 100%; padding: 1rem; border: 1px solid var(--color-border); font-family: var(--font-body);">
                        <option value="Men" <?php echo $product['category'] === 'Men' ? 'selected' : ''; ?>>Men</option>
                        <option value="Women" <?php echo $product['category'] === 'Women' ? 'selected' : ''; ?>>Women</option>
                        <option value="Oversized" <?php echo $product['category'] === 'Oversized' ? 'selected' : ''; ?>>Oversized</option>
                        <option value="Printed" <?php echo $product['category'] === 'Printed' ? 'selected' : ''; ?>>Printed</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="p-desc">Description</label>
                    <textarea id="p-desc" name="p-desc" style="width: 100%; padding: 1rem; border: 1px solid var(--color-border); font-family: var(--font-body); min-height: 100px;"><?php echo htmlspecialchars($product['description']); ?></textarea>
                </div>
                
                <div class="form-group">
                    <label for="p-image">Product Image (Leave blank to keep current)</label>
                    <input type="file" id="p-image" name="p-image" accept="image/*" style="padding: 1rem 0;">
                    <div style="margin-top: 1rem;">
                        <small>Current image:</small><br>
                        <img src="<?php echo $basePath . $product['image']; ?>" alt="" style="width: 100px; margin-top: 0.5rem; border: 1px solid var(--color-border);">
                    </div>
                </div>

                <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                    <button type="submit" class="btn btn-primary" style="flex: 1;">Save Changes</button>
                    <a href="dashboard.php?section=products" class="btn" style="flex: 1; text-align: center;">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</main>

<?php include $basePath . 'includes/footer.php'; ?>
