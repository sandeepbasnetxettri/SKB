<?php
$basePath = "../";
$pageTitle = "About Us";
$headerClass = "scrolled";
$extraStyles = '
    <style>
        .about-hero {
            height: 60vh;
            background: var(--color-bg-alt);
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }

        .about-hero h1 {
            font-size: 4rem;
        }

        .about-section {
            padding: var(--spacing-xl) 0;
        }

        .about-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 5rem;
            align-items: center;
        }

        .about-image {
            aspect-ratio: 1/1;
            background: #eee;
            overflow: hidden;
        }

        .about-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .value-card {
            padding: 3rem;
            border: 1px solid var(--color-border);
            text-align: center;
            transition: var(--transition-fast);
        }

        .value-card:hover {
            border-color: var(--color-primary);
            background: var(--color-bg-alt);
        }

        .value-card ion-icon {
            font-size: 3rem;
            margin-bottom: 1.5rem;
            color: var(--color-accent);
        }

        .about-section-title {
            font-size: 3rem; 
            margin-bottom: 2rem;
        }

        .about-text {
            color: var(--color-text-muted); 
            margin-bottom: 2rem; 
            font-size: 1.1rem;
        }
        
        .values-section {
            padding: var(--spacing-xl) 0; 
            background: var(--color-bg-alt);
        }

        .values-header {
            text-align: center; 
            margin-bottom: var(--spacing-lg);
        }
        
        .values-header h2 {
            font-size: 2.5rem;
        }

        .values-grid {
            display: grid; 
            grid-template-columns: repeat(3, 1fr); 
            gap: 2rem;
        }

        @media (max-width: 768px) {
            .values-grid {
                grid-template-columns: 1fr;
            }
            .about-section-title {
                font-size: 2.2rem;
            }
        }
    </style>
';
include $basePath . 'includes/header.php';
?>

    <main>
        <section class="about-hero">
            <div class="container fade-in-up">
                <span class="subtitle">Our Story</span>
                <h1>Crafting the <br> Future of Fashion</h1>
            </div>
        </section>

        <section class="about-section">
            <div class="container">
                <div class="about-grid">
                    <div class="about-content">
                        <h2 class="about-section-title">Minimalism Meet <br> Exceptional Quality</h2>
                        <p class="about-text">Founded in
                            2026, VESTURE was born out of a desire for simplicity and longevity. In a world of fast
                            fashion, we chose to slow down and focus on what truly matters: the perfect fit, the finest
                            organic materials, and a design that never goes out of style.</p>
                        <p style="color: var(--color-text-muted); font-size: 1.1rem;">Every T-shirt we produce is a
                            testament to our commitment to craftsmanship. We work with local artisans who share our
                            vision of a sustainable and ethical fashion industry.</p>
                    </div>
                    <div class="about-image">
                        <img src="../assets/hero-bg.png" alt="Our Studio">
                    </div>
                </div>
            </div>
        </section>

        <section class="values values-section">
            <div class="container">
                <div class="values-header">
                    <h2>Core Values</h2>
                </div>
                <div class="values-grid">
                    <div class="value-card">
                        <ion-icon name="leaf-outline"></ion-icon>
                        <h3>Sustainability</h3>
                        <p>100% organic cotton and eco-friendly dyes in every garment.</p>
                    </div>
                    <div class="value-card">
                        <ion-icon name="diamond-outline"></ion-icon>
                        <h3>Quality</h3>
                        <p>Double-stitched hems and pre-shrunk fabric for lasting wear.</p>
                    </div>
                    <div class="value-card">
                        <ion-icon name="people-outline"></ion-icon>
                        <h3>Ethics</h3>
                        <p>Fair wages and safe working conditions for all our partners.</p>
                    </div>
                </div>
            </div>
        </section>
    </main>

<?php include $basePath . 'includes/footer.php'; ?>
