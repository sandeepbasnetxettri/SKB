<?php
$basePath = "../";
include_once $basePath . 'includes/auth.php';
include_once $basePath . 'includes/product_functions.php';

// Access Control
if (!isAdmin()) {
    header("Location: login.php");
    exit();
}

$productId = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($productId > 0) {
    if (deleteProduct($productId)) {
        header("Location: dashboard.php?msg=Product deleted successfully");
    } else {
        header("Location: dashboard.php?msg=Error deleting product&type=error");
    }
} else {
    header("Location: dashboard.php");
}
exit();
?>
