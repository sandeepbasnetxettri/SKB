<?php
require_once __DIR__ . '/db.php';

function getAllProducts() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM products ORDER BY id DESC");
    return $stmt->fetchAll();
}

function getProductById($id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function updateProduct($id, $name, $price, $category, $description, $image = null) {
    global $pdo;
    if ($image) {
        $stmt = $pdo->prepare("UPDATE products SET name = ?, price = ?, category = ?, description = ?, image = ? WHERE id = ?");
        return $stmt->execute([$name, $price, $category, $description, $image, $id]);
    } else {
        $stmt = $pdo->prepare("UPDATE products SET name = ?, price = ?, category = ?, description = ? WHERE id = ?");
        return $stmt->execute([$name, $price, $category, $description, $id]);
    }
}

function insertProduct($name, $price, $category, $description, $image) {
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO products (name, price, category, description, image) VALUES (?, ?, ?, ?, ?)");
    return $stmt->execute([$name, $price, $category, $description, $image]);
}

function deleteProduct($id) {
    global $pdo;
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    return $stmt->execute([$id]);
}
?>
