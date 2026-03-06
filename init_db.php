<?php
try {
    // Connect to MySQL server first without selecting database
    $temp_pdo = new PDO("mysql:host=localhost;charset=utf8mb4", "root", "", [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);

    // 1. Create Database
    $temp_pdo->exec("CREATE DATABASE IF NOT EXISTS vesture");
    $temp_pdo->exec("USE vesture");

    // 2. Create Users Table
    $temp_pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        role ENUM('user', 'admin') DEFAULT 'user',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    // 3. Create Products Table
    $temp_pdo->exec("CREATE TABLE IF NOT EXISTS products (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        category VARCHAR(100),
        price DECIMAL(10, 2) NOT NULL,
        image VARCHAR(255),
        rating DECIMAL(3, 1),
        description TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    // 4. Create Orders Table
    $temp_pdo->exec("CREATE TABLE IF NOT EXISTS orders (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT,
        order_number VARCHAR(20) NOT NULL UNIQUE,
        total_amount DECIMAL(10, 2) NOT NULL,
        status ENUM('Pending', 'Processing', 'Shipped', 'Delivered', 'Cancelled') DEFAULT 'Pending',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
    )");

    // 5. Create Order Items Table
    $temp_pdo->exec("CREATE TABLE IF NOT EXISTS order_items (
        id INT AUTO_INCREMENT PRIMARY KEY,
        order_id INT NOT NULL,
        product_id INT,
        quantity INT NOT NULL DEFAULT 1,
        price DECIMAL(10, 2) NOT NULL,
        FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
        FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE SET NULL
    )");

    // 6. Create Wishlist Table
    $temp_pdo->exec("CREATE TABLE IF NOT EXISTS wishlist (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        product_id INT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        UNIQUE(user_id, product_id),
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
    )");

    // 7. Create Cart Items Table
    $temp_pdo->exec("CREATE TABLE IF NOT EXISTS cart_items (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        product_id INT NOT NULL,
        quantity INT NOT NULL DEFAULT 1,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        UNIQUE(user_id, product_id),
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
        FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
    )");

    // 7. Seed Users
    $stmt = $temp_pdo->prepare("SELECT COUNT(*) FROM users");
    $stmt->execute();
    if ($stmt->fetchColumn() == 0) {
        $admin_pass = password_hash('password123', PASSWORD_BCRYPT);
        $user_pass = password_hash('password123', PASSWORD_BCRYPT);
        
        $insertUser = $temp_pdo->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
        $insertUser->execute(['Admin User', 'admin@vesture.com', $admin_pass, 'admin']);
        $insertUser->execute(['John Doe', 'user@example.com', $user_pass, 'user']);
    }

    // 8. Seed Products
    $stmt = $temp_pdo->prepare("SELECT COUNT(*) FROM products");
    $stmt->execute();
    if ($stmt->fetchColumn() == 0) {
        $products = [
            [
                'name' => "Essential Cropped Tee",
                'category' => "Women",
                'price' => 1200.00,
                'image' => "assets/p4.png",
                'rating' => 4.6,
                'description' => "Tailored cropped fit for a modern feminine look."
            ],
            [
                'name' => "Architectural Graphic Tee",
                'category' => "Printed",
                'price' => 1800.00,
                'image' => "assets/p3.png",
                'rating' => 4.7,
                'description' => "Minimalist geometric design printed on high-grade jersey."
            ],
            [
                'name' => "Oversized Studio Shirt",
                'category' => "Oversized",
                'price' => 2200.00,
                'image' => "assets/p2.png",
                'rating' => 4.9,
                'description' => "Relaxed silhouette with premium heavyweight fabric."
            ],
            [
                'name' => "Premium Minimalist Tee (Updated)",
                'category' => "Men",
                'price' => 1500.00,
                'image' => "assets/p1.png",
                'rating' => 4.8,
                'description' => "Pure organic cotton with a refined fit for everyday luxury."
            ]
        ];

        $insert = $temp_pdo->prepare("INSERT INTO products (name, category, price, image, rating, description) VALUES (?, ?, ?, ?, ?, ?)");
        foreach ($products as $p) {
            $insert->execute([$p['name'], $p['category'], $p['price'], $p['image'], $p['rating'], $p['description']]);
        }
    }

    // 9. Seed Orders and Wishlist
    $stmt = $temp_pdo->prepare("SELECT COUNT(*) FROM orders");
    $stmt->execute();
    if ($stmt->fetchColumn() == 0) {
        // Get user IDs
        $stmtUsers = $temp_pdo->query("SELECT id FROM users WHERE email = 'user@example.com'");
        $userId = $stmtUsers->fetchColumn();
        
        // Get some product IDs
        $stmtProds = $temp_pdo->query("SELECT id, price FROM products LIMIT 2");
        $prods = $stmtProds->fetchAll();

        if ($userId && count($prods) >= 2) {
            // Create Order
            $insertOrder = $temp_pdo->prepare("INSERT INTO orders (user_id, order_number, total_amount, status, created_at) VALUES (?, ?, ?, ?, ?)");
            $insertOrder->execute([$userId, 'ORD-1001', 3000.00, 'Delivered', '2026-03-01 10:00:00']);
            $orderId = $temp_pdo->lastInsertId();

            // Create Order Items
            $insertItem = $temp_pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
            $insertItem->execute([$orderId, $prods[0]['id'], 1, $prods[0]['price']]);
            $insertItem->execute([$orderId, $prods[1]['id'], 1, $prods[1]['price']]);

            // Add other mock orders
            $insertOrder->execute([$userId, 'ORD-1002', 1500.00, 'Processing', '2026-03-02 14:30:00']);
            
            // Seed Wishlist
            $insertWishlist = $temp_pdo->prepare("INSERT INTO wishlist (user_id, product_id) VALUES (?, ?)");
            $insertWishlist->execute([$userId, $prods[0]['id']]);
            $insertWishlist->execute([$userId, $prods[1]['id']]);
        }
    }

    echo "Database initialized and seeded successfully!";

} catch (\PDOException $e) {
    die("Error initializing database: " . $e->getMessage());
}
?>
