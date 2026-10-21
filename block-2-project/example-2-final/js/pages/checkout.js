// Պատվերի ձևակերպում՝ ամփոփում + ստուգում + պատվեր — JS Դաս 13, 14, 20, 23

import { initLayout } from "../ui/layout.js";
import { createPicture } from "../ui/cards.js";
import { createTotals } from "../ui/totals.js";
import { createValidator, required, minLength, matches, patterns } from "../validation.js";
import { ValidationError, getErrorMessage } from "../errors.js";
import { api } from "../api.js";
import { cart } from "../store.js";
import { save } from "../storage.js";
import { formatPrice } from "../utils.js";
import { KEYS } from "../config.js";

initLayout();

const form = document.querySelector("#checkout-form");
const itemsEl = document.querySelector("#checkout-items");
const summaryEl = document.querySelector("#checkout-summary");
const totalEl = document.querySelector("#checkout-total");
const submitBtn = form.querySelector('[type="submit"]');
const addressStar = document.querySelector("#address-req");

let products = [];

// ---------- Ամփոփում ----------
function renderSummary() {
    const items = cart.getItems(products);
    const totals = cart.getTotals(products);

    if (items.length === 0) {
        itemsEl.innerHTML = `<li>Զամբյուղը դատարկ է։ <a href="catalog.html" class="link">Անցնել կատալոգ</a></li>`;
        summaryEl.innerHTML = "";
        totalEl.textContent = formatPrice(0);
        submitBtn.disabled = true;
        return;
    }
    itemsEl.innerHTML = items.map(item => `
        <li>
            ${createPicture({ ...item, alt: "" }, "ratio-1-1")}
            <span>${item.name} <span class="text-muted">× ${item.qty}</span></span>
            <strong>${formatPrice(item.subtotal)}</strong>
        </li>
    `).join("");
    summaryEl.innerHTML = createTotals(totals);
    totalEl.textContent = formatPrice(totals.total);
}

// ---------- Ստուգման կանոններ ----------
const isPickup = () => form.querySelector('input[name="delivery"]:checked').value === "pickup";

const validator = createValidator(form, {
    name: [required("Գրիր անունդ և ազգանունդ"), minLength(3, "Առնվազն 3 նիշ")],
    phone: [required("Հեռախոսը պարտադիր է"), matches(patterns.phone, "Օրինակ՝ +374 91 23 45 67")],
    email: [matches(patterns.email, "Ստուգիր հասցեն․ օրինակ՝ ani@example.com")],
    address: [(value, field) => {
        if (!isPickup() && value.trim() === "") throw new ValidationError(field, "Գրիր առաքման հասցեն");
    }],
    terms: [required("Անհրաժեշտ է համաձայնությունը")]
});

// Խանութից վերցնելու դեպքում հասցեն պարտադիր չէ
form.addEventListener("change", (e) => {
    if (e.target.name !== "delivery") return;
    addressStar.classList.toggle("hidden", isPickup());
    validator.validateField("address");
});

// ---------- Ուղարկում ----------
form.addEventListener("submit", (e) => {
    e.preventDefault();
    if (!validator.validateAll()) return;

    const data = Object.fromEntries(new FormData(form));        // JS Դաս 13
    const totals = cart.getTotals(products);
    const order = {
        number: `TM-${String(Date.now()).slice(-5)}`,
        name: data.name.trim(),
        count: totals.count,
        total: totals.total,
        delivery: data.delivery
    };
    // Մեկ ներդիրի համար՝ sessionStorage (JS Դաս 14)։ PHP բլոկում պատվերը կգնա սերվեր։
    save(KEYS.order, order, sessionStorage);
    cart.clear();
    location.href = "thanks.html";
});

async function load() {
    try {
        products = await api.getProducts();
        renderSummary();
    } catch (error) {
        itemsEl.innerHTML = `<li>${getErrorMessage(error)}</li>`;
        submitBtn.disabled = true;
    }
}

load();
