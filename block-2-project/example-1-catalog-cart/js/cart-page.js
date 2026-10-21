// Զամբյուղի էջ — JS Դաս 16
// Ամեն գործողություն՝ փոխում ենք cart-ը (cart.js) → renderCartPage()

const rowsEl = document.querySelector("#cart-rows");
const summaryEl = document.querySelector("#cart-summary");
const leadEl = document.querySelector("#cart-lead");
const filledEl = document.querySelector("#cart-filled");
const emptyEl = document.querySelector("#cart-empty");
const asideEl = document.querySelector("#cart-aside");
const deliveryHint = asideEl.querySelector(".text-small");

function createCartRow(item) {
    const { id, image, name, meta, price, oldPrice, qty, subtotal, alt } = item;
    const oldPriceHtml = oldPrice !== null ? ` <del class="text-small text-muted">${formatPrice(oldPrice)}</del>` : "";
    return `
        <tr data-id="${id}">
            <td>
                <div class="cart-table__product">
                    <picture class="ratio-1-1">
                        <source srcset="images/${image}.webp" type="image/webp">
                        <img class="img-cover" src="images/${image}.jpg" alt="${alt}" width="600" height="400" loading="lazy">
                    </picture>
                    <div>
                        <a href="product.html?id=${id}">${name}</a>
                        <p class="card__meta">${meta}</p>
                    </div>
                </div>
            </td>
            <td><span class="cell-label">Գին</span> <span>${formatPrice(price)}${oldPriceHtml}</span></td>
            <td>
                <span class="cell-label" aria-hidden="true">Քանակ</span>
                <label for="q${id}" class="visually-hidden">Քանակ՝ ${name}</label>
                <input id="q${id}" type="number" value="${qty}" min="1" max="${MAX_QTY}" class="input input--sm input--qty" data-action="qty">
            </td>
            <td><span class="cell-label">Գումար</span> <strong>${formatPrice(subtotal)}</strong></td>
            <td><button type="button" class="link-danger" data-action="remove">Հեռացնել<span class="visually-hidden"> ${name}</span></button></td>
        </tr>
    `;
}

function renderCartPage() {
    const items = getCartItems();
    const totals = getCartTotals();
    const isEmpty = items.length === 0;

    // Դատարկ վիճակ՝ միայն դասերով — JS Դաս 10
    filledEl.classList.toggle("hidden", isEmpty);
    asideEl.classList.toggle("hidden", isEmpty);
    emptyEl.classList.toggle("hidden", !isEmpty);

    leadEl.textContent = isEmpty ? "Զամբյուղում ապրանք չկա" : `${totals.count} ապրանք`;
    rowsEl.innerHTML = items.map(createCartRow).join("");
    summaryEl.innerHTML = createTotalsHtml(totals);

    const left = FREE_DELIVERY_FROM - totals.goods;
    deliveryHint.textContent = left > 0
        ? `Ավելացրու ևս ${formatPrice(left)}՝ անվճար առաքման համար`
        : "Առաքումը Երևանում անվճար է";
}

// ---------- Իրադարձություններ (դելեգացիա) ----------
const getRowId = el => Number(el.closest("tr").dataset.id);

rowsEl.addEventListener("change", (e) => {
    if (e.target.dataset.action !== "qty") return;
    setQty(getRowId(e.target), Number(e.target.value));
    renderCartPage();
});

rowsEl.addEventListener("click", (e) => {
    const btn = e.target.closest('[data-action="remove"]');
    if (!btn) return;
    const id = getRowId(btn);
    const product = PRODUCTS.find(p => p.id === id);
    removeFromCart(id);
    renderCartPage();
    showToast(`«${product.name}»-ը հեռացվեց`);
});

document.querySelector('[data-action="clear"]').addEventListener("click", () => {
    if (!confirm("Դատարկե՞լ զամբյուղը")) return;        // JS Դաս 12
    clearCart();
    renderCartPage();
});

renderCartPage();
