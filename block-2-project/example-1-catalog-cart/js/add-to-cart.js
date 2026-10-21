// «Գնել» կոճակը ցանկացած էջում + «Ավելացվեց զամբյուղ» մոդալը — JS Դաս 11, 12, 16

const cartModal = document.querySelector("#cart-modal");
const cartModalProduct = document.querySelector("#cart-modal-product");

function openCartModal(product, qty) {
    const { image, name, price, alt } = product;
    cartModalProduct.innerHTML = `
        <picture class="ratio-1-1">
            <source srcset="images/${image}.webp" type="image/webp">
            <img class="img-cover" src="images/${image}.jpg" alt="${alt}" width="600" height="400">
        </picture>
        <div>
            <strong>${name}</strong>
            <p class="text-muted">${qty} × ${formatPrice(price)}</p>
        </div>
    `;
    cartModal.classList.add("is-open");
    document.body.classList.add("no-scroll");
    cartModal.querySelector(".modal__close").focus();   // ստեղնաշարով աշխատողի համար
}

function closeCartModal() {
    cartModal.classList.remove("is-open");
    document.body.classList.remove("no-scroll");
}

// Ավելացնում է ապրանքը և ցույց տալիս մոդալը
function addProduct(id, qty = 1) {
    const product = PRODUCTS.find(p => p.id === id);
    if (!product || !product.inStock) return;
    addToCart(id, qty);
    openCartModal(product, qty);
}

// ★ Դելեգացիա ամբողջ էջի վրա։ Աշխատում է նաև այն քարտերի համար,
// որոնք JS-ը կստեղծի ավելի ուշ (կատալոգ) — JS Դաս 12
document.addEventListener("click", (e) => {
    const addBtn = e.target.closest('[data-action="add"]');
    if (!addBtn) return;
    addProduct(Number(addBtn.dataset.id));               // dataset-ը միշտ տող է
});

// Մոդալը փակվում է երեք եղանակով — JS Դաս 11
cartModal.addEventListener("click", (e) => {
    if (e.target === cartModal || e.target.closest('[data-action="close-modal"]')) {
        closeCartModal();
    }
});

document.addEventListener("keydown", (e) => {
    if (e.key === "Escape" && cartModal.classList.contains("is-open")) {
        closeCartModal();
    }
});
