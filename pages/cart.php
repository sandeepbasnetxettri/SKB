<?php
$basePath = "../";
$pageTitle = "Cart";
$headerClass = "scrolled";
include $basePath . 'includes/header.php';
?>

    <main class="cart-page">
        <div class="container">
            <h1 class="page-title">Shopping Cart</h1>

            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Subtotal</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody id="cart-rows">
                    <!-- Rows injected via JS -->
                </tbody>
            </table>

            <div id="cart-empty" class="cart-empty">
                <p class="empty-msg">Your cart is empty.</p>
                <a href="shop.php" class="btn btn-primary">Start Shopping</a>
            </div>

            <div class="cart-summary" id="cart-summary">
                <div class="summary-row">
                    <span>Subtotal</span>
                    <span id="subtotal-val">Rs. 0.00</span>
                </div>
                <div class="summary-row">
                    <span>Shipping</span>
                    <span id="shipping-val">Rs. 0.00</span>
                </div>
                <div class="summary-row total">
                    <span>Total</span>
                    <span id="total-val">Rs. 0.00</span>
                </div>
                <a href="checkout.php" class="btn btn-primary btn-block cart-proceed">Proceed to Checkout</a>
            </div>
        </div>
    </main>

<?php
$extraScripts = '
    <script>
        function renderCart() {
            const rows = document.getElementById("cart-rows");
            const summary = document.getElementById("cart-summary");
            const empty = document.getElementById("cart-empty");

            if (cart.length === 0) {
                if (rows) rows.parentElement.style.display = "none";
                if (summary) summary.style.display = "none";
                if (empty) empty.style.display = "block";
                return;
            }

            if (rows) {
                rows.innerHTML = cart.map((item, index) => `
                    <tr class="cart-item">
                        <td data-label="Product">
                            <div class="cart-product-info">
                                <div class="cart-img">
                                    <img src="${window.basePath}${item.image}" alt="${item.name}">
                                </div>
                                <div>
                                    <h4 class="cart-product-title">${item.name}</h4>
                                    <p class="cart-product-size">Size: M</p>
                                </div>
                            </div>
                        </td>
                        <td data-label="Price">Rs. ${parseFloat(item.price).toFixed(2)}</td>
                        <td data-label="Quantity">
                            <div class="qty-control">
                                <button class="qty-btn" onclick="updateQty(${index}, -1)">-</button>
                                <span>${item.quantity}</span>
                                <button class="qty-btn" onclick="updateQty(${index}, 1)">+</button>
                            </div>
                        </td>
                        <td data-label="Subtotal">Rs. ${(parseFloat(item.price) * parseInt(item.quantity)).toFixed(2)}</td>
                        <td>
                            <ion-icon name="trash-outline" class="cart-remove-icon" onclick="removeItem(${index})"></ion-icon>
                        </td>
                    </tr>
                `).join("");
            }

            const subtotal = cart.reduce((acc, item) => acc + (parseFloat(item.price) * parseInt(item.quantity)), 0);
            const shipping = subtotal > 5000 ? 0 : 200;
            const total = subtotal + shipping;

            const subtotalEl = document.getElementById("subtotal-val");
            const shippingEl = document.getElementById("shipping-val");
            const totalEl = document.getElementById("total-val");

            if (subtotalEl) subtotalEl.innerText = `Rs. ${subtotal.toFixed(2)}`;
            if (shippingEl) shippingEl.innerText = shipping === 0 ? "Free" : `Rs. ${shipping.toFixed(2)}`;
            if (totalEl) totalEl.innerText = `Rs. ${total.toFixed(2)}`;
        }

        window.updateQty = async function (index, change) {
            cart[index].quantity += change;
            if (cart[index].quantity < 1) cart[index].quantity = 1;
            updateLocalStorage();
            updateCartCount();
            renderCart();

            if (window.isLoggedIn) {
                await fetch("../api/cart.php", {
                    method: "POST",
                    headers: { "Content-Type": "application/json" },
                    body: JSON.stringify({ product_id: cart[index].id, quantity: cart[index].quantity })
                });
            }
        };

        window.removeItem = async function (index) {
            const product_id = cart[index].id;
            cart.splice(index, 1);
            updateLocalStorage();
            updateCartCount();
            renderCart();

            if (window.isLoggedIn) {
                await fetch(`../api/cart.php?product_id=${product_id}`, {
                    method: "DELETE"
                });
            }
        };

        document.addEventListener("DOMContentLoaded", renderCart);
        window.addEventListener("cartUpdated", renderCart);
    </script>
';
include $basePath . 'includes/footer.php';
?>
