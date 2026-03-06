<?php
$basePath = "../";
$pageTitle = "Shop";
$headerClass = "scrolled";
include $basePath . 'includes/header.php';
?>

    <main class="shop-page">
        <div class="container">
            <div class="shop-controls">
                <ul class="shop-filters">
                    <li class="filter-btn active" onclick="filterProducts('All')">All</li>
                    <li class="filter-btn" onclick="filterProducts('Men')">Men</li>
                    <li class="filter-btn" onclick="filterProducts('Women')">Women</li>
                    <li class="filter-btn" onclick="filterProducts('Oversized')">Oversized</li>
                    <li class="filter-btn" onclick="filterProducts('Printed')">Printed</li>
                </ul>
                <div class="search-box">
                    <ion-icon name="search-outline"></ion-icon>
                    <input type="text" id="productSearch" placeholder="Search for T-shirts..."
                        onkeyup="searchProducts()">
                </div>
            </div>

            <div class="product-grid" id="shop-grid">
                <!-- Products will be injected here -->
            </div>
        </div>
    </main>

<?php
$extraScripts = '
    <script>
        // Shop specific logic
        function filterProducts(category) {
            const btns = document.querySelectorAll(".filter-btn");
            btns.forEach(btn => btn.classList.remove("active"));
            if (event) {
                event.target.classList.add("active");
            }

            const filtered = category === "All"
                ? products
                : products.filter(p => p.category === category);

            renderShop(filtered);
        }

        function searchProducts() {
            const query = document.getElementById("productSearch").value.toLowerCase();
            const filtered = products.filter(p =>
                p.name.toLowerCase().includes(query) ||
                p.category.toLowerCase().includes(query)
            );
            renderShop(filtered);
        }

        function renderShop(pList = products) {
            const grid = document.getElementById("shop-grid");
            if (grid) {
                grid.innerHTML = pList.map(p => createProductCard(p)).join("");
            }
        }

        document.addEventListener("DOMContentLoaded", () => {
            if (typeof products !== "undefined" && products.length > 0) {
                renderShop();
            } else {
                window.addEventListener("productsLoaded", () => renderShop());
            }
        });
    </script>
';
include $basePath . 'includes/footer.php';
?>
