<?php
header('Content-Type: application/json');
require_once '../includes/product_functions.php';

try {
    $products = getAllProducts();
    echo json_encode($products);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>
