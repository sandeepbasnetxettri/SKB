<?php
$basePath = "../";
$pageTitle = "Checkout";
$headerClass = "scrolled";
$hideIcons = true;
$extraStyles = '
    <style>
        .checkout-page {
            padding: 15rem 0;
        }

        .checkout-grid {
            display: grid;
            grid-template-columns: 1.5fr 1fr;
            gap: 5rem;
        }

        .form-group {
            margin-bottom: 2rem;
        }

        label {
            display: block;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.1rem;
            margin-bottom: 0.5rem;
        }

        input,
        select {
            width: 100%;
            padding: 1rem;
            border: 1px solid var(--color-border);
            font-family: inherit;
        }

        .payment-methods {
            display: flex;
            gap: 2rem;
            margin-top: 1rem;
        }

        .payment-option {
            border: 1px solid var(--color-border);
            padding: 1.5rem;
            flex: 1;
            text-align: center;
            cursor: pointer;
            transition: var(--transition-fast);
        }

        .payment-option.active {
            border-color: var(--color-primary);
            background: var(--color-bg-alt);
        }
        
        .checkout-title {
            font-size: 2.5rem;
            margin-bottom: 3rem;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
        }
        
        .payment-title {
            margin: 2rem 0;
        }
        
        .payment-icon {
            font-size: 2rem;
        }
        
        .place-order-btn {
            margin-top: 4rem;
        }
        
        .summary-box {
            background: var(--color-bg-alt);
            padding: 3rem;
        }
        
        .summary-items-list {
            margin: 2rem 0;
        }
        
        .summary-item-row {
            border-bottom: 1px solid #ddd;
            padding: 1rem 0;
        }

        @media (max-width: 768px) {
            .form-row {
                grid-template-columns: 1fr;
                gap: 0;
            }
        }
    </style>
';
include $basePath . 'includes/header.php';
?>

    <main class="checkout-page">
        <div class="container">
            <div class="checkout-grid">
                <div class="checkout-form">
                    <h2 class="checkout-title">Checkout Information</h2>
                    <form id="orderForm" onsubmit="handleOrder(event)">
                        <div class="form-row">
                            <div class="form-group">
                                <label>First Name</label>
                                <input type="text" required>
                            </div>
                            <div class="form-group">
                                <label>Last Name</label>
                                <input type="text" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Email Address</label>
                            <input type="email" required>
                        </div>
                        <div class="form-group">
                            <label>Shipping Address</label>
                            <input type="text" required>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label>City</label>
                                <input type="text" required>
                            </div>
                            <div class="form-group">
                                <label>Postal Code</label>
                                <input type="text" required>
                            </div>
                        </div>

                        <h3 class="payment-title">Payment Method</h3>
                        <div class="payment-methods">
                            <div class="payment-option active" onclick="selectPayment(this, 'card')">
                                <ion-icon name="card-outline" class="payment-icon"></ion-icon>
                                <p>Credit Card</p>
                            </div>
                            <div class="payment-option" onclick="selectPayment(this, 'cod')">
                                <ion-icon name="cash-outline" class="payment-icon"></ion-icon>
                                <p>Cash on Delivery</p>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block place-order-btn">Place
                            Order</button>
                    </form>
                </div>

                <div class="order-summary summary-box">
                    <h3>Your Order</h3>
                    <div id="summary-items" class="summary-items-list">
                        <!-- Injected via JS -->
                    </div>
                    <div class="summary-row">
                        <span>Shipping Fee</span>
                        <span id="shipping-fee">Rs. 0.00</span>
                    </div>
                    <div class="summary-row total">
                        <span>Total Paid</span>
                        <span id="finish-total">Rs. 0.00</span>
                    </div>
                </div>
            </div>
        </div>
    </main>

<?php
$extraScripts = '
    <script>
        function renderSummary() {
            const list = document.getElementById("summary-items");
            if (list) {
                list.innerHTML = cart.map(item => `
                    <div class="summary-row summary-item-row">
                        <span>${item.name} x ${item.quantity}</span>
                        <span>Rs. ${(item.price * item.quantity).toFixed(2)}</span>
                    </div>
                `).join("");
            }

            const subtotal = cart.reduce((acc, item) => acc + (item.price * item.quantity), 0);
            const shipping = subtotal > 5000 ? 0 : 200;
            const total = subtotal + shipping;

            const shippingEl = document.getElementById("shipping-fee");
            if (shippingEl) shippingEl.innerText = `Rs. ${shipping.toFixed(2)}`;

            const totalEl = document.getElementById("finish-total");
            if (totalEl) totalEl.innerText = `Rs. ${total.toFixed(2)}`;
        }

        window.selectPayment = function (el, type) {
            document.querySelectorAll(".payment-option").forEach(opt => opt.classList.remove("active"));
            el.classList.add("active");
        };

        window.handleOrder = function (e) {
            e.preventDefault();
            alert("Order Placed Successfully! Thank you for shopping with VESTURE.");
            localStorage.removeItem("vesture_cart");
            window.location.href = "../index.php";
        };

        document.addEventListener("DOMContentLoaded", renderSummary);
    </script>
';
include $basePath . 'includes/footer.php';
?>
