// Պատվերի ձևակերպում՝ ամփոփումը զամբյուղից — JS Դաս 16
// Ֆորմայի ստուգումը (JS Դաս 13) կավելացնենք Առաջադրանք 2-ում։

const itemsEl = document.querySelector("#checkout-items");
const checkoutSummaryEl = document.querySelector("#checkout-summary");
const totalEl = document.querySelector("#checkout-total");
const submitBtn = document.querySelector('#checkout-form [type="submit"]');

function createSummaryItem({ image, name, qty, subtotal }) {
    return `
        <li>
            <picture class="ratio-1-1">
                <source srcset="images/${image}.webp" type="image/webp">
                <img class="img-cover" src="images/${image}.jpg" alt="" width="600" height="400" loading="lazy">
            </picture>
            <span>${name} <span class="text-muted">× ${qty}</span></span>
            <strong>${formatPrice(subtotal)}</strong>
        </li>
    `;
}

function renderCheckout() {
    const items = getCartItems();
    const totals = getCartTotals();

    if (items.length === 0) {
        itemsEl.innerHTML = `<li>Զամբյուղը դատարկ է։ <a href="catalog.html" class="link">Անցնել կատալոգ</a></li>`;
        checkoutSummaryEl.innerHTML = "";
        totalEl.textContent = formatPrice(0);
        submitBtn.setAttribute("disabled", "");             // դատարկ պատվեր չենք ընդունում
        return;
    }

    itemsEl.innerHTML = items.map(createSummaryItem).join("");
    checkoutSummaryEl.innerHTML = createTotalsHtml(totals);
    totalEl.textContent = formatPrice(totals.total);
}

renderCheckout();
