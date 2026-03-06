<?php
$basePath = "../";
$pageTitle = "Product Details";
$headerClass = "scrolled";
$extraStyles = '
    <style>
        .product-details {
            padding: 15rem 0;
        }

        .details-wrapper {
            display: flex;
            gap: 5rem;
            align-items: flex-start;
        }

        .product-gallery {
            flex: 1;
        }

        .main-img {
            width: 100%;
            aspect-ratio: 3/4;
            background: var(--color-bg-alt);
            margin-bottom: 2rem;
        }

        .main-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .product-info-side {
            flex: 1;
        }

        .product-info-side h1 {
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        .price-row {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 2rem;
            color: var(--color-accent);
        }

        .description-text {
            font-size: 1.1rem;
            color: var(--color-text-muted);
            margin-bottom: 3rem;
        }

        .selection-label {
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 0.1rem;
            margin-bottom: 1rem;
            display: block;
        }

        .size-options {
            display: flex;
            gap: 1rem;
            margin-bottom: 3rem;
        }

        .size-box {
            width: 50px;
            height: 50px;
            border: 1px solid var(--color-border);
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: var(--transition-fast);
        }

        .size-box:hover,
        .size-box.active {
            border-color: var(--color-primary);
            background: var(--color-primary);
            color: white;
        }
    </style>
';
include_once $basePath . 'includes/product_functions.php';

$productId = isset($_GET['id']) ? intval($_GET['id']) : 0;
$product = getProductById($productId);

if (!$product) {
    header("Location: shop.php");
    exit();
}

include $basePath . 'includes/header.php';
?>


    <main class="product-details">
        <div class="container">
            <div class="details-wrapper" id="product-content">
                <!-- Data will be loaded via script -->
            </div>
        </div>
    </main>

<?php
$extraScripts = '
    <script>
        const product = ' . json_encode($product) . ';

        function renderProduct() {
            if (!product) return;
            const content = document.getElementById("product-content");
            if (content) {
                content.innerHTML = `
                    <div class="product-gallery">
                        <div class="main-img">
                            <img src="../${product.image}" alt="${product.name}">
                        </div>
                    </div>
                    <div class="product-info-side">
                        <span class="product-category">${product.category}</span>
                        <h1>${product.name}</h1>
                        <div class="price-row">Rs. ${parseFloat(product.price).toFixed(2)}</div>
                        <p class="description-text">${product.description}</p>
                        
                        <span class="selection-label">Select Size</span>
                        <div class="size-options">
                            <div class="size-box">S</div>
                            <div class="size-box active">M</div>
                            <div class="size-box">L</div>
                            <div class="size-box">XL</div>
                        </div>

                        <button class="btn btn-primary" style="width: 100%" onclick="addToCart(${product.id})">Add To Cart</button>
                        
                        ${window.isAdmin ? `
                        <a href="edit_product.php?id=${product.id}" class="btn" style="width: 100%; margin-top: 1rem; text-align: center; display: block;">Edit Product</a>
                        ` : ""}
                        
                        <div class="accordion" style="margin-top: 4rem; border-top: 1px solid var(--color-border);">
                            <div class="accordion-item" style="border-bottom: 1px solid var(--color-border);">
                                <div class="accordion-header" style="padding: 1.5rem 0; cursor: pointer; display: flex; justify-content: space-between; align-items: center;" onclick="toggleAccordion(this)">
                                    <h4 style="text-transform: uppercase; font-size: 0.8rem; letter-spacing: 0.1rem;">Shipping & Returns</h4>
                                    <ion-icon name="add-outline"></ion-icon>
                                </div>
                                <div class="accordion-content" style="max-height: 0; overflow: hidden; transition: 0.3s ease;">
                                    <p style="font-size: 0.9rem; color: var(--color-text-muted); padding-bottom: 1.5rem;">Free standard shipping on orders over Rs. 5000. Easy 30-day returns. Delivered in carbon-neutral packaging.</p>
                                </div>
                            </div>
                            <div class="accordion-item" style="border-bottom: 1px solid var(--color-border);">
                                <div class="accordion-header" style="padding: 1.5rem 0; cursor: pointer; display: flex; justify-content: space-between; align-items: center;" onclick="toggleAccordion(this)">
                                    <h4 style="text-transform: uppercase; font-size: 0.8rem; letter-spacing: 0.1rem;">Customer Reviews (${product.rating})</h4>
                                    <ion-icon name="add-outline"></ion-icon>
                                </div>
                                <div class="accordion-content" style="max-height: 0; overflow: hidden; transition: 0.3s ease;">
                                    <p style="font-size: 0.9rem; color: var(--color-text-muted); padding-bottom: 1.5rem;">"Best quality T-shirt I\'ve ever owned. The fit is perfect." - Sam R.<br><br>"Material feels amazing. Worth every penny." - Elena M.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            }
        }

        function toggleAccordion(header) {
            const content = header.nextElementSibling;
            const icon = header.querySelector("ion-icon");
            const isOpen = content.style.maxHeight !== "0px" && content.style.maxHeight !== "";

            // Close all
            document.querySelectorAll(".accordion-content").forEach(c => c.style.maxHeight = "0px");
            document.querySelectorAll(".accordion-header ion-icon").forEach(i => i.setAttribute("name", "add-outline"));

            if (!isOpen) {
                content.style.maxHeight = content.scrollHeight + "px";
                icon.setAttribute("name", "remove-outline");
            }
        }

        if (product) {
            product.price = parseFloat(product.price);
            renderProduct();
        }
    </script>
';
include $basePath . 'includes/footer.php';
?>
