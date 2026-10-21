// Զամբյուղի մոդելը — JS Դաս 16
// Պահում ենք ՄԻԱՅՆ id և qty։ Անունը, գինը և նկարը վերցնում ենք PRODUCTS-ից՝ միշտ թարմ։

const CART_KEY = "tm:cart";
const MAX_QTY = 10;
const FREE_DELIVERY_FROM = 50000;
const DELIVERY_PRICE = 2000;

let cart = load(CART_KEY, []);

// ---------- Կարդալու համար ----------
function getCartItems() {
    return cart
        .map(item => {
            const product = PRODUCTS.find(p => p.id === item.id);
            if (!product) return null;                 // ապրանքը հանվել է կատալոգից
            const fullPrice = product.oldPrice ?? product.price;
            return {
                ...product,                            // JS Դաս 7
                qty: item.qty,
                subtotal: product.price * item.qty,
                fullSubtotal: fullPrice * item.qty
            };
        })
        .filter(Boolean);                              // հանում է null-երը
}

function getCartCount() {
    return cart.reduce((sum, item) => sum + item.qty, 0);
}

// Ամփոփում՝ զամբյուղի և պատվերի էջերի համար
function getCartTotals() {
    const items = getCartItems();
    const full = items.reduce((sum, i) => sum + i.fullSubtotal, 0);
    const goods = items.reduce((sum, i) => sum + i.subtotal, 0);
    const delivery = goods === 0 || goods >= FREE_DELIVERY_FROM ? 0 : DELIVERY_PRICE;
    return {
        count: items.reduce((sum, i) => sum + i.qty, 0),
        full,
        discount: full - goods,
        goods,
        delivery,
        total: goods + delivery
    };
}

// Ամփոփման տողերը՝ զամբյուղում և պատվերի էջում նույնն են, ուստի մեկ ֆունկցիա (JS Դաս 16)
function createTotalsHtml(totals) {
    const { count, full, discount, delivery, total } = totals;
    const deliveryText = delivery === 0
        ? `<span class="text-success">Անվճար</span>`
        : `<span>${formatPrice(delivery)}</span>`;
    return `
        <li><span>Ապրանքներ (${count} հատ)</span><span>${formatPrice(full)}</span></li>
        ${discount > 0 ? `<li><span>Զեղչ</span><span class="text-danger">−${formatPrice(discount)}</span></li>` : ""}
        <li><span>Առաքում</span>${deliveryText}</li>
        <li class="summary__total"><span>Ընդամենը</span><span>${formatPrice(total)}</span></li>
    `;
}

// ---------- Փոփոխելու համար ----------
function addToCart(id, qty = 1) {
    const existing = cart.find(item => item.id === id);
    if (existing) {
        existing.qty = Math.min(existing.qty + qty, MAX_QTY);
    } else {
        cart.push({ id, qty: Math.min(qty, MAX_QTY) });
    }
    saveCart();
}

function setQty(id, qty) {
    const item = cart.find(i => i.id === id);
    if (!item) return;
    item.qty = Math.min(Math.max(qty, 1), MAX_QTY);  // 1-ից 10
    saveCart();
}

function removeFromCart(id) {
    cart = cart.filter(item => item.id !== id);      // cart-ը let է, որովհետև վերագրում ենք
    saveCart();
}

function clearCart() {
    cart = [];
    saveCart();
}

// Ամեն փոփոխությունից հետո՝ պահպանում և header-ի թիվը։ Մեկ տեղ, ոչինչ չի մոռացվում։
function saveCart() {
    save(CART_KEY, cart);
    renderCartCount();
}

// Header-ի թիվը՝ բոլոր էջերում
function renderCartCount() {
    const count = getCartCount();
    document.querySelectorAll("[data-cart-count]").forEach(el => {
        el.textContent = count;
        el.classList.toggle("is-empty", count === 0);
    });
}

renderCartCount();
