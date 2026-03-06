<?php
$basePath = "../";
$pageTitle = "Add Product";
$headerClass = "scrolled";

include_once $basePath . 'includes/auth.php';
include_once $basePath . 'includes/product_functions.php';

// Access Control
if (!isAdmin()) {
    header("Location: login.php");
    exit();
}

$message = "";
$messageType = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['p-name'];
    $price = $_POST['p-price'];
    $category = $_POST['p-category'];
    $description = $_POST['p-desc'];
    $imagePath = "assets/p1.png"; // Default fallback

    // Handle Image Upload
    if (isset($_FILES['p-image']) && $_FILES['p-image']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['p-image']['tmp_name'];
        $fileName = $_FILES['p-image']['name'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
        $uploadFileDir = $basePath . 'assets/';
        
        // Ensure directory exists
        if (!is_dir($uploadFileDir)) {
            mkdir($uploadFileDir, 0777, true);
        }

        $dest_path = $uploadFileDir . $newFileName;

        if (move_uploaded_file($fileTmpPath, $dest_path)) {
            $imagePath = 'assets/' . $newFileName;
        }
    }

    if (insertProduct($name, $price, $category, $description, $imagePath)) {
        header("Location: dashboard.php?msg=Product added successfully");
        exit();
    } else {
        $message = "Error adding product.";
        $messageType = "error";
    }
}

include $basePath . 'includes/header.php';
?>

<main class="auth-page">
    <div class="container">
        <div class="auth-container fade-in-up" style="max-width: 600px;">
            <div class="auth-header">
                <h1>Add New Product</h1>
                <p>Create a fresh listing for the store.</p>
            </div>

            <?php if ($message): ?>
                <div class="notification show" style="position: static; margin-bottom: 2rem; background: <?php echo $messageType === 'success' ? '#10b981' : '#ef4444'; ?>">
                    <?php echo $message; ?>
                </div>
            <?php endif; ?>

            <form action="" method="POST" enctype="multipart/form-data" class="auth-form">
                <div class="form-group">
                    <label for="p-name">Product Name</label>
                    <input type="text" id="p-name" name="p-name" placeholder="E.g. Linen Blend Shirt" required>
                </div>
                <div class="form-group">
                    <label for="p-price">Price (Rs.)</label>
                    <input type="number" id="p-price" name="p-price" placeholder="45.00" step="0.01" required>
                </div>
                <div class="form-group">
                    <label for="p-category">Category</label>
                    <select id="p-category" name="p-category" style="width: 100%; padding: 1rem; border: 1px solid var(--color-border); font-family: var(--font-body);">
                        <option value="Men">Men</option>
                        <option value="Women">Women</option>
                        <option value="Oversized">Oversized</option>
                        <option value="Printed">Printed</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="p-desc">Description</label>
                    <textarea id="p-desc" name="p-desc" placeholder="Product details..." style="width: 100%; padding: 1rem; border: 1px solid var(--color-border); font-family: var(--font-body); min-height: 100px;"></textarea>
                </div>
                
                <div class="form-group">
                    <label for="p-image">Product Image</label>
                    <input type="file" id="p-image" name="p-image" accept="image/*" style="padding: 1rem 0;" required>
                </div>

                <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                    <button type="submit" class="btn btn-primary" style="flex: 1;">Create Product</button>
                    <a href="dashboard.php?section=products" class="btn" style="flex: 1; text-align: center;">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</main>

<?php include $basePath . 'includes/footer.php'; ?>
