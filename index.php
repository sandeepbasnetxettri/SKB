<?php
$basePath = "./";
$pageTitle = "Premium T-Shirts";
include $basePath . 'includes/header.php';
?>

    <main>
        <!-- Hero Section -->
        <section class="hero" id="home">
            <div class="container">
                <div class="hero-content fade-in-up">
                    <span class="subtitle">Conscious Selection</span>
                    <h1>Modern minimalist wear <br> for the conscious individual.</h1>
                    <p>Crafted with quality and sustainability at its core. Experience the essence of ethical luxury.</p>
                    <div class="hero-btns">
                        <a href="pages/shop.php" class="btn btn-primary">Shop Collection</a>
                        <a href="pages/about.php" class="btn">Our Philosophy</a>
                    </div>
                </div>
            </div>
        </section>

        <!-- Featured Section -->
        <section class="featured" id="featured" style="padding: var(--spacing-xl) 0;">
            <div class="container">
                <div class="section-header">
                    <h2>New Arrivals</h2>
                    <a href="pages/shop.php" class="view-all">View All Products</a>
                </div>
                <div class="product-grid-horizontal" id="featured-products">
                    <!-- Products will be injected here by JS -->
                </div>
            </div>
        </section>

        <!-- Promotional Banner -->
        <section class="promo-banner" style="background: var(--color-bg-alt); padding: var(--spacing-xl) 0; margin-top: var(--spacing-xl);">
            <div class="container flex-container">
                <div class="flex-1">
                    <h2 style="font-size: 3rem; margin-bottom: 2rem;">Sustainability in <br> Every Thread</h2>
                    <p style="color: var(--color-text-muted); margin-bottom: 3rem;">We believe in fashion that lasts.
                        Our T-shirts are made from 100% GOTS certified organic cotton, ensuring minimal environmental
                        impact while providing maximum comfort.</p>
                    <a href="pages/about.php" class="btn">Read Our Story</a>
                </div>
                <div class="promo-image-container">
                    <img src="assets/p3.png" alt="Sustainability">
                </div>
            </div>
        </section>

        <!-- Newsletter -->
        <section class="newsletter" style="padding: var(--spacing-xl) 0; text-align: center; border-top: 1px solid var(--color-border);">
            <div class="container newsletter-content" style="margin: 0 auto;">
                <h3 style="font-size: 2.5rem; margin-bottom: 1rem;">Join the Club</h3>
                <p style="color: var(--color-text-muted); margin-bottom: 3rem;">Sign up for exclusive access to new
                    drops and sustainable fashion tips.</p>
                <form class="newsletter-form">
                    <input type="email" placeholder="Your email address" required>
                    <button type="submit" class="btn btn-primary" style="padding: 0.8rem 2rem;">Subscribe</button>
                </form>
            </div>
        </section>
    </main>

<?php include $basePath . 'includes/footer.php'; ?>
