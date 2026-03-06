<?php
// Set base path if not already set
if (!isset($basePath)) {
    $basePath = "./";
}
include_once __DIR__ . '/auth.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle . " | VESTURE" : "VESTURE | Premium T-Shirts"; ?></title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@600;700&family=Outfit:wght@300;400;600&display=swap"
        rel="stylesheet">
    <!-- Ionicons -->
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <!-- Styles -->
    <link rel="stylesheet" href="<?php echo $basePath; ?>css/style.css">
    <?php if (isset($extraStyles)) echo $extraStyles; ?>
    <script>
        window.isAdmin = <?php echo isAdmin() ? 'true' : 'false'; ?>;
        window.isLoggedIn = <?php echo isLoggedIn() ? 'true' : 'false'; ?>;
        window.basePath = '<?php echo $basePath; ?>';
    </script>
</head>

<body>
    <header id="header" class="<?php echo isset($headerClass) ? $headerClass : ''; ?>">
        <div class="container">
            <nav>
                <div class="logo">VESTURE</div>
                <ul class="nav-links" id="navLinks">
                    <li><a href="<?php echo $basePath; ?>index.php">Home</a></li>
                    <li><a href="<?php echo $basePath; ?>pages/shop.php">Shop</a></li>
                    <li><a href="<?php echo $basePath; ?>pages/about.php">About</a></li>
                    <li><a href="<?php echo $basePath; ?>pages/contact.php">Contact</a></li>
                </ul>
                <div class="nav-icons">
                    <?php if (!isset($hideIcons)): ?>
                    <a href="<?php echo $basePath; ?>pages/cart.php" class="cart-link">
                        <ion-icon name="cart-outline"></ion-icon>
                        <span id="cart-count" class="cart-badge">0</span>
                    </a>
                    <?php if (isLoggedIn() && isset($_SESSION['user'])): ?>
                        <a href="<?php echo $basePath; ?>pages/logout.php" class="nav-link" style="margin-left: 1.5rem; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.1rem; border: 1px solid var(--color-primary); padding: 0.5rem 1rem;">Logout</a>
                    <?php else: ?>
                        <a href="<?php echo $basePath; ?>pages/login.php" class="nav-icon">
                            <ion-icon name="person-outline"></ion-icon>
                        </a>
                    <?php endif; ?>
                    <?php endif; ?>
                    <div class="menu-toggle" id="menuToggle">
                        <ion-icon name="menu-outline"></ion-icon>
                    </div>
                </div>
            </nav>
        </div>
    </header>
