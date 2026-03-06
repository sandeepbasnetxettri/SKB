// Product Data
let products = [];

async function fetchProducts() {
    try {
        const response = await fetch(`${window.location.origin}/app/api/products.php`);
        products = await response.json();
        // Trigger generic renders if elements exist
        if (featuredGrid) renderFeatured();
        window.dispatchEvent(new CustomEvent('productsLoaded'));
    } catch (err) {
        console.error('Failed to fetch products:', err);
    }
}

// Initialize Cart
let cart = JSON.parse(localStorage.getItem('vesture_cart')) || [];

// Header Scroll Effect
window.addEventListener('scroll', () => {
    if (header) {
        if (window.scrollY > 50) {
            header.classList.add('scrolled');
        } else {
            header.classList.remove('scrolled');
        }
    }
});

// Update Cart Count Badge
function updateCartCount() {
    const countEl = document.getElementById('cart-count');
    if (!countEl) return;

    const count = cart.reduce((acc, item) => acc + item.quantity, 0);
    countEl.innerText = count;

    if (count > 0) {
        countEl.classList.add('show');
    } else {
        countEl.classList.remove('show');
    }
}

// Create Product Card
function createProductCard(product) {
    const imgBase = window.basePath || '';
    // The original logic for imagePath already correctly handles basePath and asset paths.
    // The instruction implies a change to the image src, but the provided snippet
    // seems to be for a cart item and uses 'item.image' instead of 'product.image'.
    // Assuming the intent is to ensure 'window.basePath' is always prepended to 'product.image'
    // and that 'product.image' itself might sometimes contain 'assets/' or not.
    // The most robust way is to ensure product.image is always relative to the base path.
    // Let's adjust the imagePath calculation to be more direct with basePath.
    const imagePath = `${imgBase}${product.image.startsWith('assets/') ? '' : 'assets/'}${product.image}`;
    return `
        <div class="product-card">
            <div class="product-image">
                <img src="${imagePath}" alt="${product.name}">
                <div class="product-actions">
                    ${window.isAdmin ? `
                    <a href="${window.basePath}pages/edit_product.php?id=${product.id}" class="action-btn" title="Edit Product">
                        <ion-icon name="create-outline"></ion-icon>
                    </a>
                    ` : ""}
                    <div class="action-btn" onclick="addToWishlist(${product.id})">
                        <ion-icon name="heart-outline"></ion-icon>
                    </div>
                    <div class="action-btn" onclick="addToCart(${product.id})">
                        <ion-icon name="cart-outline"></ion-icon>
                    </div>
                    <a href="${window.basePath}pages/product.php?id=${product.id}" class="action-btn">
                        <ion-icon name="eye-outline"></ion-icon>
                    </a>
                </div>
            </div>
            <div class="product-info">
                <span class="product-category">${product.category}</span>
                <h3 class="product-title">${product.name}</h3>
                <div class="product-price">Rs. ${parseFloat(product.price).toFixed(2)}</div>
            </div>
        </div>
    `;
}

// Render Featured Products
function renderFeatured() {
    if (!featuredGrid) return;
    featuredGrid.innerHTML = products.slice(0, 4).map(p => createProductCard(p)).join('');
}

// Sync Cart with DB
async function syncCartWithDB() {
    if (!cart.length) return;

    try {
        for (const item of cart) {
            await fetch(`${window.location.origin}/app/api/cart.php`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ product_id: item.id, quantity: item.quantity })
            });
        }
        // After syncing, clear local cart and fetch fresh from DB
        // localStorage.removeItem('vesture_cart'); // Keep local as fallback?
    } catch (err) {
        console.error('Failed to sync cart:', err);
    }
}

// Fetch Cart from DB
async function fetchCart() {
    try {
        const response = await fetch(`${window.location.origin}/app/api/cart.php`);
        const data = await response.json();
        if (!data.error) {
            // Map DB items to match our cart structure
            cart = data.map(item => ({
                id: parseInt(item.product_id),
                name: item.name,
                price: parseFloat(item.price),
                image: item.image,
                quantity: parseInt(item.quantity)
            }));
            updateLocalStorage();
            updateCartCount();
            window.dispatchEvent(new CustomEvent('cartUpdated'));
        }
    } catch (err) {
        console.error('Failed to fetch cart:', err);
    }
}

// Add to Cart Logic
window.addToCart = async function (id, productData = null) {
    const productId = parseInt(id);
    let product = productData || products.find(p => parseInt(p.id) === productId) || cart.find(p => parseInt(p.id) === productId);

    if (!product) {
        // Fallback: try to fetch individual product if not found
        try {
            const resp = await fetch(`${window.location.origin}/app/api/products.php`);
            const allProducts = await resp.json();
            product = allProducts.find(p => parseInt(p.id) === productId);
        } catch (e) { console.error("Could not fetch products for cart adding fallback", e); }
    }

    if (!product) return;

    const existing = cart.find(item => item.id === parseInt(id));

    if (existing) {
        existing.quantity += 1;
    } else {
        cart.push({ ...product, id: parseInt(id), quantity: 1, price: parseFloat(product.price) });
    }

    updateLocalStorage();
    updateCartCount();
    showNotification(`${product.name} added to cart!`);

    // Sync to DB if logged in
    if (window.isLoggedIn) {
        try {
            await fetch(`${window.location.origin}/app/api/cart.php`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ product_id: productId, quantity: existing ? existing.quantity : 1 })
            });
        } catch (err) {
            console.error('Failed to sync item to DB:', err);
        }
    }
};

function updateLocalStorage() {
    localStorage.setItem('vesture_cart', JSON.stringify(cart));
}

function showNotification(msg) {
    const toast = document.createElement('div');
    toast.className = 'notification show';
    toast.style.transform = 'translateY(0)';
    toast.innerText = msg;
    document.body.appendChild(toast);

    setTimeout(() => {
        toast.classList.remove('show');
        toast.style.transform = 'translateY(100px)';
        setTimeout(() => toast.remove(), 300);
    }, 2000);
}

// DOM Elements
const header = document.getElementById('header');
const featuredGrid = document.getElementById('featured-products');

// Initialize
document.addEventListener('DOMContentLoaded', () => {
    fetchProducts();
    updateCartCount();

    if (window.isLoggedIn) {
        syncCartWithDB().then(() => fetchCart());
    }

    // Mobile Menu Toggle
    const menuToggle = document.getElementById('menuToggle');
    const navLinks = document.getElementById('navLinks');
    // ... rest of the menu logic ...
    if (menuToggle && navLinks) {
        menuToggle.addEventListener('click', () => {
            navLinks.classList.toggle('active');
            const icon = menuToggle.querySelector('ion-icon');
            icon.setAttribute('name', navLinks.classList.contains('active') ? 'close-outline' : 'menu-outline');
        });

        navLinks.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', () => {
                navLinks.classList.remove('active');
                menuToggle.querySelector('ion-icon').setAttribute('name', 'menu-outline');
            });
        });
    }
});
