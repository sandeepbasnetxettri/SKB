<?php
$basePath = "../";
$pageTitle = "Portal";
$headerClass = "scrolled";

include_once $basePath . 'includes/auth.php';
include_once $basePath . 'includes/product_functions.php';
include_once $basePath . 'includes/portal_functions.php';

// Access Control
if (!isLoggedIn() || !isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$user = $_SESSION['user'];
$role = $_SESSION['user_role'] ?? 'user';
$products = ($role === 'admin') ? getAllProducts() : [];

$msg = $_GET['msg'] ?? '';
$msgType = $_GET['type'] ?? 'success';

$currentSection = $_GET['section'] ?? 'overview';

// Handle Profile Updates
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_profile') {
    $newName = $_POST['name'] ?? '';
    $newEmail = $_POST['email'] ?? '';
    if (updateUserProfile($user['id'], $newName, $newEmail)) {
        header("Location: dashboard.php?section=account&msg=Profile updated successfully");
        exit();
    } else {
        $msg = "Error updating profile. Email may already be in use.";
        $msgType = "error";
    }
}

// Fetch Data based on role and section
if ($role === 'admin') {
    $stats = getAnalyticsStats();
    $allOrders = getOrders();
    $recentUsers = getRecentUsers();
} else {
    $userOrders = getOrders($user['id']);
    $userWishlist = getWishlist($user['id']);
}

include $basePath . 'includes/header.php';
?>

<style>
    /* ... existing styles ... */
    .portal-page {
        padding: 10rem 0;
        min-height: 80vh;
    }

    .portal-header {
        margin-bottom: 4rem;
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
    }

    .portal-header h1 {
        font-size: 2.5rem;
        margin-bottom: 0.5rem;
    }

    .portal-grid {
        display: grid;
        grid-template-columns: 250px 1fr;
        gap: 4rem;
    }

    .portal-sidebar ul {
        list-style: none;
    }

    .portal-sidebar li {
        padding: 1rem 0;
        border-bottom: 1px solid var(--color-border);
    }

    .portal-sidebar a {
        color: var(--color-text-muted);
        text-decoration: none;
        transition: var(--transition-fast);
        display: block;
    }

    .portal-sidebar a:hover, 
    .portal-sidebar a.active {
        color: var(--color-primary);
        padding-left: 0.5rem;
    }

    .portal-content-card {
        background: white;
        border: 1px solid var(--color-border);
        padding: 3rem;
    }

    /* Admin Table */
    .admin-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 2rem;
    }

    .admin-table th {
        text-align: left;
        padding: 1.5rem;
        border-bottom: 2px solid var(--color-border);
        text-transform: uppercase;
        font-size: 0.7rem;
        letter-spacing: 0.1rem;
    }

    .admin-table td {
        padding: 1.5rem;
        border-bottom: 1px solid var(--color-border);
        font-size: 0.9rem;
    }

    .table-img {
        width: 50px;
        height: 60px;
        object-fit: cover;
        background: var(--color-bg-alt);
    }

    .action-links {
        display: flex;
        gap: 1rem;
    }

    .action-links a {
        font-size: 0.8rem;
        text-decoration: none;
        text-transform: uppercase;
        letter-spacing: 0.05rem;
    }

    .link-edit { color: var(--color-primary); }
    .link-delete { color: #ef4444; }

    /* Stats */
    .stats-row {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 2rem;
        margin-bottom: 3rem;
    }

    .stat-card {
        padding: 2rem;
        border: 1px solid var(--color-border);
        text-align: center;
    }

    .stat-card h3 {
        font-size: 0.7rem;
        text-transform: uppercase;
        color: var(--color-text-muted);
        margin-bottom: 0.5rem;
    }

    .stat-card p {
        font-size: 2rem;
        font-weight: 700;
    }

    /* Analytics Specific */
    .analytics-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2rem;
    }
    .status-badge {
        padding: 0.25rem 0.75rem;
        font-size: 0.7rem;
        text-transform: uppercase;
        border-radius: 20px;
        background: var(--color-bg-alt);
    }
    .status-delivered { background: #d1fae5; color: #065f46; }
    .status-processing { background: #fef3c7; color: #92400e; }
    .status-shipped { background: #dbeafe; color: #1e40af; }
    .status-pending { background: #fee2e2; color: #991b1b; }
</style>

<main class="portal-page">
    <div class="container">
        <?php if ($msg): ?>
            <div class="notification show" style="position: static; margin-bottom: 2rem; background: <?php echo $msgType === 'success' ? '#10b981' : '#ef4444'; ?>">
                <?php echo htmlspecialchars($msg); ?>
            </div>
        <?php endif; ?>

        <div class="portal-header">
            <div>
                <h1><?php echo $role === 'admin' ? 'Admin Portal' : 'User Portal'; ?></h1>
                <p>Welcome back, <?php echo htmlspecialchars($user['name']); ?>.</p>
            </div>
            <?php if ($role === 'admin' && $currentSection === 'products'): ?>
                <a href="add_product.php" class="btn btn-primary">Add New Product</a>
            <?php endif; ?>
        </div>

        <div class="portal-grid">
            <aside class="portal-sidebar">
                <ul>
                    <li><a href="?section=overview" class="<?php echo $currentSection === 'overview' ? 'active' : ''; ?>">Overview</a></li>
                    <?php if ($role === 'admin'): ?>
                        <li><a href="?section=products" class="<?php echo $currentSection === 'products' ? 'active' : ''; ?>">Product Management</a></li>
                        <li><a href="?section=orders" class="<?php echo $currentSection === 'orders' ? 'active' : ''; ?>">Order History</a></li>
                        <li><a href="?section=analytics" class="<?php echo $currentSection === 'analytics' ? 'active' : ''; ?>">User Analytics</a></li>
                    <?php else: ?>
                        <li><a href="?section=orders" class="<?php echo $currentSection === 'orders' ? 'active' : ''; ?>">My Orders</a></li>
                        <li><a href="?section=wishlist" class="<?php echo $currentSection === 'wishlist' ? 'active' : ''; ?>">Wishlist</a></li>
                        <li><a href="?section=account" class="<?php echo $currentSection === 'account' ? 'active' : ''; ?>">Account Settings</a></li>
                    <?php endif; ?>
                </ul>
            </aside>

            <section class="portal-content">
                <?php if ($role === 'admin'): ?>
                    <?php if ($currentSection === 'overview'): ?>
                        <div class="stats-row">
                            <div class="stat-card">
                                <h3>Total Products</h3>
                                <p><?php echo $stats['total_products']; ?></p>
                            </div>
                            <div class="stat-card">
                                <h3>Total Orders</h3>
                                <p><?php echo $stats['total_orders']; ?></p>
                            </div>
                            <div class="stat-card">
                                <h3>New Users</h3>
                                <p><?php echo $stats['new_users']; ?></p>
                            </div>
                        </div>
                        <div class="portal-content-card">
                            <h2 style="font-size: 1.2rem; margin-bottom: 1rem;">Admin Overview</h2>
                            <p style="color: var(--color-text-muted);">Welcome to the administrative control panel. Use the sidebar to manage products, view orders, and analyze user data.</p>
                        </div>

                    <?php elseif ($currentSection === 'products'): ?>

                    <div class="portal-content-card">
                        <h2 style="font-size: 1.2rem; margin-bottom: 2rem;">Product Inventory</h2>
                        <table class="admin-table">
                            <thead>
                                <tr>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>Category</th>
                                    <th>Price</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($products as $p): ?>
                                    <tr>
                                        <td><img src="<?php echo $basePath . $p['image']; ?>" class="table-img" alt=""></td>
                                        <td><?php echo htmlspecialchars($p['name']); ?></td>
                                        <td><?php echo htmlspecialchars($p['category']); ?></td>
                                        <td>Rs. <?php echo number_format($p['price'], 2); ?></td>
                                        <td class="action-links">
                                            <a href="edit_product.php?id=<?php echo $p['id']; ?>" class="link-edit">Edit</a>
                                            <a href="delete_product.php?id=<?php echo $p['id']; ?>" class="link-delete" onclick="return confirm('Are you sure you want to delete this product?')">Delete</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <?php elseif ($currentSection === 'orders'): ?>
                        <div class="portal-content-card">
                            <h2 style="font-size: 1.2rem; margin-bottom: 2rem;">Order History</h2>
                            <table class="admin-table">
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Customer</th>
                                        <th>Date</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($allOrders as $order): ?>
                                    <tr>
                                        <td><?php echo $order['order_number']; ?></td>
                                        <td><?php echo htmlspecialchars($order['customer']); ?></td>
                                        <td><?php echo date('Y-m-d', strtotime($order['created_at'])); ?></td>
                                        <td>Rs. <?php echo number_format($order['total_amount'], 2); ?></td>
                                        <td>
                                            <span class="status-badge status-<?php echo strtolower($order['status']); ?>">
                                                <?php echo $order['status']; ?>
                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                    <?php elseif ($currentSection === 'analytics'): ?>
                        <div class="stats-row">
                            <div class="stat-card">
                                <h3>Avg. Order Value</h3>
                                <p>Rs. <?php echo number_format($stats['avg_order_value'], 2); ?></p>
                            </div>
                            <div class="stat-card">
                                <h3>Conversion Rate</h3>
                                <p>3.2%</p>
                            </div>
                            <div class="stat-card">
                                <h3>Return Rate</h3>
                                <p>1.5%</p>
                            </div>
                        </div>
                        <div class="portal-content-card">
                            <h2 style="font-size: 1.2rem; margin-bottom: 2rem;">Performance Metrics</h2>
                            <div class="analytics-grid">
                                <div>
                                    <h4 style="font-size: 0.8rem; text-transform: uppercase; color: var(--color-text-muted); margin-bottom: 1.5rem;">Traffic Sources</h4>
                                    <ul style="list-style: none;">
                                        <li style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid var(--color-border);">
                                            <span>Direct</span>
                                            <strong>45%</strong>
                                        </li>
                                        <li style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid var(--color-border);">
                                            <span>Organic Search</span>
                                            <strong>30%</strong>
                                        </li>
                                        <li style="display: flex; justify-content: space-between; padding: 0.5rem 0; border-bottom: 1px solid var(--color-border);">
                                            <span>Social Media</span>
                                            <strong>15%</strong>
                                        </li>
                                        <li style="display: flex; justify-content: space-between; padding: 0.5rem 0;">
                                            <span>Referral</span>
                                            <strong>10%</strong>
                                        </li>
                                    </ul>
                                </div>
                                <div style="background: var(--color-bg-alt); padding: 2rem; display: flex; align-items: center; justify-content: center; text-align: center;">
                                    <div>
                                        <p style="font-size: 0.8rem; color: var(--color-text-muted); margin-bottom: 1rem;">Sales Growth (Monthly)</p>
                                        <div style="font-size: 1.5rem; color: #10b981; font-weight: 700;">+24% ↑</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <?php if ($currentSection === 'overview' || $currentSection === 'account'): ?>
                        <div class="portal-content-card">
                            <h2 style="font-size: 1.2rem; margin-bottom: 1rem;">Profile Information</h2>
                            <p style="color: var(--color-text-muted); margin-bottom: 2rem;">Manage your personal details and preferences.</p>
                            
                            <form action="" method="POST" style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
                                <input type="hidden" name="action" value="update_profile">
                                <div>
                                    <label for="profile-name" style="display: block; font-size: 0.7rem; text-transform: uppercase; color: var(--color-text-muted); margin-bottom: 0.5rem;">Full Name</label>
                                    <input type="text" id="profile-name" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required style="width: 100%; padding: 0.8rem; border: 1px solid var(--color-border);">
                                </div>
                                <div>
                                    <label for="profile-email" style="display: block; font-size: 0.7rem; text-transform: uppercase; color: var(--color-text-muted); margin-bottom: 0.5rem;">Email Address</label>
                                    <input type="email" id="profile-email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required style="width: 100%; padding: 0.8rem; border: 1px solid var(--color-border);">
                                </div>
                                <div style="grid-column: span 2;">
                                    <button type="submit" class="btn btn-primary" style="font-size: 0.8rem; padding: 0.8rem 2rem;">Update Profile</button>
                                </div>
                            </form>

                            <hr style="margin: 3rem 0; border: none; border-top: 1px solid var(--color-border);">
                            
                            <h2 style="font-size: 1.2rem; margin-bottom: 1.5rem;">Security</h2>
                            <button class="btn btn-outline" style="font-size: 0.8rem; padding: 0.8rem 1.5rem; background: transparent; border: 1px solid var(--color-border);">Change Password</button>
                        </div>

                    <?php elseif ($currentSection === 'orders'): ?>
                        <div class="portal-content-card">
                            <h2 style="font-size: 1.2rem; margin-bottom: 2rem;">My Orders</h2>
                            <table class="admin-table">
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Date</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($userOrders)): ?>
                                        <tr><td colspan="4">You haven't placed any orders yet.</td></tr>
                                    <?php else: ?>
                                        <?php foreach ($userOrders as $order): ?>
                                            <tr>
                                                <td><?php echo $order['order_number']; ?></td>
                                                <td><?php echo date('Y-m-d', strtotime($order['created_at'])); ?></td>
                                                <td>Rs. <?php echo number_format($order['total_amount'], 2); ?></td>
                                                <td>
                                                    <span class="status-badge status-<?php echo strtolower($order['status']); ?>">
                                                        <?php echo $order['status']; ?>
                                                    </span>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                    <?php elseif ($currentSection === 'wishlist'): ?>
                        <div class="portal-content-card">
                            <h2 style="font-size: 1.2rem; margin-bottom: 2rem;">My Wishlist</h2>
                            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 2rem;">
                                <?php if (empty($userWishlist)): ?>
                                    <p>Your wishlist is empty.</p>
                                <?php else: ?>
                                    <?php foreach ($userWishlist as $item): ?>
                                        <div style="border: 1px solid var(--color-border); padding: 1rem; text-align: center;">
                                            <img src="<?php echo $basePath . $item['image']; ?>" alt="" style="width: 100%; height: 150px; object-fit: cover; margin-bottom: 1rem;">
                                            <h4 style="font-size: 0.9rem; margin-bottom: 0.5rem;"><?php echo htmlspecialchars($item['name']); ?></h4>
                                            <p style="color: var(--color-primary); font-weight: 600;">Rs. <?php echo number_format($item['price'], 2); ?></p>
                                            <a href="product.php?id=<?php echo $item['id']; ?>" style="display: block; margin-top: 1rem; font-size: 0.7rem; text-transform: uppercase; color: var(--color-text-muted);">View Product</a>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </section>
        </div>
    </div>
</main>

<?php include $basePath . 'includes/footer.php'; ?>
