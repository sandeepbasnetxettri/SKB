<?php
include_once 'db.php';

/**
 * Portal Data Fetching Functions
 */

function getOrders($user_id = null) {
    global $pdo;
    if ($user_id) {
        $stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
        $stmt->execute([$user_id]);
    } else {
        // For admin: join with users to get customer name
        $stmt = $pdo->query("SELECT o.*, u.name as customer FROM orders o LEFT JOIN users u ON o.user_id = u.id ORDER BY o.created_at DESC");
    }
    return $stmt->fetchAll();
}

function getWishlist($user_id) {
    global $pdo;
    $stmt = $pdo->prepare("
        SELECT p.* FROM wishlist w 
        JOIN products p ON w.product_id = p.id 
        WHERE w.user_id = ?
    ");
    $stmt->execute([$user_id]);
    return $stmt->fetchAll();
}

function getAnalyticsStats() {
    global $pdo;
    $stats = [];

    // Total Products
    $stats['total_products'] = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();

    // Total Orders
    $stats['total_orders'] = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();

    // New Users (last 30 days)
    $stats['new_users'] = $pdo->query("SELECT COUNT(*) FROM users WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)")->fetchColumn();

    // Avg Order Value
    $stats['avg_order_value'] = $pdo->query("SELECT AVG(total_amount) FROM orders")->fetchColumn() ?: 0;

    return $stats;
}

function getRecentUsers($limit = 5) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT name, email, role, created_at FROM users ORDER BY created_at DESC LIMIT ?");
    $stmt->execute([$limit]);
    return $stmt->fetchAll();
}
?>
