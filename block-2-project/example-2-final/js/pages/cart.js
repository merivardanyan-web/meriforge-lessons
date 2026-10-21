// Զամբյուղի էջ — JS Դաս 16, 20, 22

import { initLayout } from "../ui/layout.js";
import { createPicture, createErrorState } from "../ui/cards.js";
import { showToast } from "../ui/toast.js";
import { createTotals } from "../ui/totals.js";
import { api } from "../api.js";
import { cart } from "../store.js";
import { getErrorMessage } from "../errors.js";
import { formatPrice } from "../utils.js";
import { MAX_QTY, FREE_DELIVERY_FROM } from "../config.js";

initLayout();

const rowsEl = document.querySelector("#cart-rows");
const summaryEl = document.querySelector("#cart-summary");
const leadEl = document.querySelector("#cart-lead");
const filledEl = document.querySelector("#cart-filled");
const emptyEl = document.querySelector("#cart-empty");
const asideEl = document.querySelector("#cart-aside");
const deliveryHint = asideEl.querySelector(".text-small");

let products = [];

function createRow(item) {
    const { id, name, meta, price, oldPrice, qty, subtotal } = item;
    const old = oldPrice !== null ? ` <del class="text-small text-muted">${formatPrice(oldPrice)}</del>` : "";
    return `
        <tr data-id="${id}">
            <td>
                <div class="cart-table__product">
                    ${createPicture(item, "ratio-1-1")}
                    <div>
                        <a href="product.html?id=${id}">${name}</a>
                        <p class="card__meta">${meta}</p>
                    </div>
                </div>
            </td>
            <td><span class="cell-label">Գին</span> <span>${formatPrice(price)}${old}</span></td>
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

function render() {
    const items = cart.getItems(products);
    const totals = cart.getTotals(products);
    const isEmpty = items.length === 0;

    filledEl.classList.toggle("hidden", isEmpty);
    asideEl.classList.toggle("hidden", isEmpty);
    emptyEl.classList.toggle("hidden", !isEmpty);

    leadEl.textContent = isEmpty ? "Զամբյուղում ապրանք չկա" : `${totals.count} ապրանք`;
    rowsEl.innerHTML = items.map(createRow).join("");
    summaryEl.innerHTML = createTotals(totals);

    const left = FREE_DELIVERY_FROM - totals.goods;
    deliveryHint.textContent = left > 0
        ? `Ավելացրու ևս ${formatPrice(left)}՝ անվճար առաքման համար`
        : "Առաքումը Երևանում անվճար է";
}

async function load() {
    leadEl.textContent = "Բեռնվում է…";
    try {
        products = await api.getProducts();
        render();
    } catch (error) {
        filledEl.innerHTML = createErrorState(getErrorMessage(error));
        asideEl.classList.add("hidden");
        leadEl.textContent = "";
    }
}

// ---------- Իրադարձություններ ----------
const getRowId = el => Number(el.closest("tr").dataset.id);

rowsEl.addEventListener("change", (e) => {
    if (e.target.dataset.action !== "qty") return;
    cart.setQty(getRowId(e.target), Number(e.target.value));
    render();
});

rowsEl.addEventListener("click", (e) => {
    const button = e.target.closest('[data-action="remove"]');
    if (!button) return;
    const id = getRowId(button);
    const { name } = products.find(p => p.id === id);
    cart.remove(id);
    render();
    showToast(`«${name}»-ը հեռացվեց`);
});

document.querySelector("#cart").addEventListener("click", (e) => {
    if (e.target.closest('[data-action="retry"]')) location.reload();
    if (!e.target.closest('[data-action="clear"]')) return;
    if (!confirm("Դատարկե՞լ զամբյուղը")) return;
    cart.clear();
    render();
});

load();
