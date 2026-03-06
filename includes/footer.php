<?php
if (!isset($basePath)) {
    $basePath = "./";
}
?>
    <footer>
        <div class="container">
            <div class="footer-grid">
                <div class="footer-col">
                    <span class="footer-logo">VESTURE</span>
                    <p class="footer-desc">Modern minimalist wear for the conscious individual. Crafted with quality and sustainability at its core.</p>
                </div>
                <div class="footer-col">
                    <h4>Explore</h4>
                    <ul class="footer-links">
                        <li><a href="<?php echo $basePath; ?>pages/shop.php">Shop All</a></li>
                        <li><a href="<?php echo $basePath; ?>pages/about.php">About Us</a></li>
                        <li><a href="<?php echo $basePath; ?>pages/contact.php">Contact</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4>Support</h4>
                    <ul class="footer-links">
                        <li><a href="<?php echo $basePath; ?>pages/dashboard.php">Account</a></li>
                        <li><a href="#">Shipping</a></li>
                        <li><a href="#">Returns</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4>Follow Us</h4>
                    <div class="social-links">
                        <a href="#"><ion-icon name="logo-instagram"></ion-icon></a>
                        <a href="#"><ion-icon name="logo-facebook"></ion-icon></a>
                        <a href="#"><ion-icon name="logo-twitter"></ion-icon></a>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                &copy; <?php echo date("Y"); ?> VESTURE. Consciously Crafted.
            </div>
        </div>
    </footer>

    <script src="<?php echo $basePath; ?>js/main.js"></script>
    <?php if (isset($extraScripts)) echo $extraScripts; ?>
</body>

</html>
