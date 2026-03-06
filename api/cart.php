<?php
header('Content-Type: application/json');
include_once '../includes/auth.php';
include_once '../includes/db.php';

$method = $_SERVER['REQUEST_METHOD'];
$user_id = $_SESSION['user_id'] ?? null;

if (!$user_id) {
    echo json_encode(['error' => 'User not logged in']);
    exit();
}

try {
    switch ($method) {
        case 'GET':
            // Fetch cart items for the user
            $stmt = $pdo->prepare("
                SELECT ci.product_id, ci.quantity, p.name, p.price, p.image 
                FROM cart_items ci
                JOIN products p ON ci.product_id = p.id
                WHERE ci.user_id = ?
            ");
            $stmt->execute([$user_id]);
            $items = $stmt->fetchAll();
            echo json_encode($items);
            break;

        case 'POST':
            // Add or update item in cart
            $data = json_decode(file_get_contents('php://input'), true);
            $product_id = $data['product_id'] ?? null;
            $quantity = $data['quantity'] ?? 1;

            if (!$product_id) {
                echo json_encode(['error' => 'Product ID is required']);
                exit();
            }

            $stmt = $pdo->prepare("INSERT INTO cart_items (user_id, product_id, quantity) 
                                   VALUES (?, ?, ?) 
                                   ON DUPLICATE KEY UPDATE quantity = ?");
            $stmt->execute([$user_id, $product_id, $quantity, $quantity]);
            
            echo json_encode(['success' => true]);
            break;

        case 'DELETE':
            // Remove item or clear cart
            $product_id = $_GET['product_id'] ?? null;
            if ($product_id) {
                $stmt = $pdo->prepare("DELETE FROM cart_items WHERE user_id = ? AND product_id = ?");
                $stmt->execute([$user_id, $product_id]);
            } else {
                $stmt = $pdo->prepare("DELETE FROM cart_items WHERE user_id = ?");
                $stmt->execute([$user_id]);
            }
            echo json_encode(['success' => true]);
            break;

        default:
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            break;
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>
