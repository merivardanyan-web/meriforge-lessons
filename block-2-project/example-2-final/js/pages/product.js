// Ապրանքի էջ՝ product.html?id=5 — JS Դաս 15 (URLSearchParams), 20 (fetch), 23 (սխալներ)

import { initLayout } from "../ui/layout.js";
import { initCartModal, addProduct } from "../ui/cart-modal.js";
import { createCard, createPicture, createBadge, createPrice, createErrorState } from "../ui/cards.js";
import { showToast } from "../ui/toast.js";
import { api } from "../api.js";
import { wishlist } from "../store.js";
import { getErrorMessage, NotFoundError } from "../errors.js";
import { CATEGORIES, BRANDS, MAX_QTY } from "../config.js";

initLayout();
initCartModal();

const RELATED_COUNT = 3;
const PERKS = [
    ['<path d="M1 4h14v12H1zM15 9h4l4 4v3h-8z"/><circle cx="5.5" cy="18.5" r="2"/><circle cx="18.5" cy="18.5" r="2"/>', "Անվճար առաքում Երևանում՝ վաղը"],
    ['<path d="M12 2 4 5v6c0 5 3.4 9.4 8 11 4.6-1.6 8-6 8-11V5z"/><path d="m9 12 2 2 4-4"/>', "2 տարվա պաշտոնական երաշխիք"],
    ['<path d="M21 12a9 9 0 0 1-15.5 6.3L3 16"/><path d="M3 12a9 9 0 0 1 15.5-6.3L21 8"/><path d="M21 3v5h-5M3 21v-5h5"/>', "14 օր վերադարձ առանց հարցերի"]
];

const root = document.querySelector("#product-root");
const relatedSection = document.querySelector("#related");
const relatedEl = document.querySelector("#related-products");
const productId = Number(new URLSearchParams(location.search).get("id"));

let current = null;            // բեռնված ապրանքը

// ---------- Մասեր ----------
function createGallery(product) {
    const images = product.gallery ?? [product.image];
    const thumbs = images.length > 1 ? `
        <ul class="gallery__thumbs">
            ${images.map((image, i) => `
                <li><button type="button" class="gallery__thumb ${i === 0 ? "is-current" : ""}" data-action="thumb" data-image="${image}"
                            aria-label="Նկար ${i + 1}" aria-pressed="${i === 0}">
                    ${createPicture({ image, alt: "" }, "ratio-3-2")}
                </button></li>
            `).join("")}
        </ul>` : "";
    return `
        <div class="gallery">
            ${createPicture(product, "gallery__main ratio-3-2", false)}
            ${thumbs}
        </div>
    `;
}

function createInfo(product) {
    const { id, name, description, inStock } = product;
    const isFav = wishlist.has(id);
    const buy = inStock ? `
        <form class="flex align-end gap-10 flex-wrap mb-30" id="product-form">
            <div class="field">
                <label for="qty" class="field__label">Քանակ</label>
                <input id="qty" name="qty" type="number" value="1" min="1" max="${MAX_QTY}" class="input input--qty">
            </div>
            <button type="submit" class="btn btn--primary">Ավելացնել զամբյուղ</button>
            <button type="button" class="btn btn--ghost" data-action="fav" aria-pressed="${isFav}">${isFav ? "♥ Հավանած է" : "♡ Հավանել"}</button>
        </form>` : `
        <p class="mb-30"><button type="button" class="btn btn--primary" disabled>Սպառված է</button></p>`;
    return `
        <div class="wrapper">
            ${createBadge(product, "badge")}
            <h1 class="mb-10">${name}</h1>
            <p class="text-muted mb-20">Կոդ՝ TM-${1000 + id} · <span class="stock">${inStock ? "Առկա է պահեստում" : "Ժամանակավորապես չկա"}</span></p>
            <p class="product__price mb-20">${createPrice(product)}</p>
            <p class="mb-20">${description}</p>
            ${buy}
            <ul class="perks">
                ${PERKS.map(([path, text]) => `<li><svg viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">${path}</svg> ${text}</li>`).join("")}
            </ul>
        </div>
    `;
}

// «Ապրանքի մասին»՝ տվյալներ, որ արդեն ունենք (ամսաթիվը՝ JS Դաս 8)
function createAbout({ category, brand, rating, added }) {
    const date = new Date(added).toLocaleDateString("ru-RU");
    return `
        <ul class="summary__list">
            <li><span>Կատեգորիա</span><a href="catalog.html?cat=${category}" class="link">${CATEGORIES[category]}</a></li>
            <li><span>Ապրանքանիշ</span><span>${BRANDS[brand]}</span></li>
            <li><span>Վարկանիշ</span><span>★ ${rating.toFixed(1)}</span></li>
            <li><span>Խանութում է</span><span>${date}-ից</span></li>
        </ul>
    `;
}

function createDetails(product) {
    const { name, specs } = product;
    const rows = specs.map(([label, value]) => `<tr><th scope="row">${label}</th><td>${value}</td></tr>`).join("");
    return `
        <section id="details">
            <div class="row flex-wrap">
                <div class="col w-60 w-md-100">
                    <h2 class="section-title mb-30">Բնութագրեր</h2>
                    <table class="table specs">
                        <caption class="visually-hidden">${name}-ի տեխնիկական բնութագրերը</caption>
                        <tbody>${rows}</tbody>
                    </table>
                </div>
                <div class="col w-40 w-md-100">
                    <h2 class="section-title mb-30">Ապրանքի մասին</h2>
                    ${createAbout(product)}
                </div>
            </div>
        </section>
    `;
}

function renderProduct(product) {
    const { category, name } = product;
    document.title = `${name} — ՏեխնոՄարկետ`;
    root.innerHTML = `
        <section id="product">
            <div class="row">
                <div class="col w-100">
                    <nav aria-label="Դուք այստեղ եք">
                        <ol class="crumbs">
                            <li><a href="index.html">Գլխավոր</a></li>
                            <li><a href="catalog.html">Կատալոգ</a></li>
                            <li><a href="catalog.html?cat=${category}">${CATEGORIES[category]}</a></li>
                            <li aria-current="page">${name}</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="row flex-wrap align-start">
                <div class="col w-50 w-md-100">${createGallery(product)}</div>
                <div class="col w-50 w-md-100">${createInfo(product)}</div>
            </div>
        </section>
        ${createDetails(product)}
    `;
}

function renderRelated(product, products) {
    const sameCategory = products.filter(p => p.category === product.category && p.id !== product.id);
    const others = products
        .filter(p => p.category !== product.category)
        .sort((a, b) => b.popularity - a.popularity);
    const related = [...sameCategory, ...others].slice(0, RELATED_COUNT);
    relatedEl.innerHTML = related.map(p => createCard(p, { isFavorite: wishlist.has(p.id) })).join("");
    relatedSection.classList.remove("hidden");
}

function renderError(error) {
    const message = error instanceof NotFoundError
        ? `${error.message} <a href="catalog.html" class="link">Անցնել կատալոգ</a>`
        : getErrorMessage(error);
    root.innerHTML = `
        <section id="product">
            <div class="row"><div class="col w-100">
                ${createErrorState(message)}
            </div></div>
        </section>
    `;
    if (error instanceof NotFoundError) root.querySelector('[data-action="retry"]').remove();
}

// ---------- Բեռնում ----------
async function loadProduct() {
    try {
        const [product, products] = await Promise.all([api.getProduct(productId), api.getProducts()]);  // JS Դաս 19
        current = product;
        renderProduct(product);
        renderRelated(product, products);
    } catch (error) {
        renderError(error);
        if (!(error instanceof NotFoundError)) console.error(error);
    }
}

// ---------- Իրադարձություններ (դելեգացիա root-ի վրա, որ աշխատի render-ից հետո) ----------
root.addEventListener("click", (e) => {
    const target = e.target.closest("[data-action]");
    if (!target) return;

    if (target.dataset.action === "thumb") {
        const main = root.querySelector(".gallery__main");
        const { image } = target.dataset;
        main.querySelector("source").setAttribute("srcset", `images/${image}.webp`);
        main.querySelector("img").setAttribute("src", `images/${image}.jpg`);
        root.querySelectorAll(".gallery__thumb").forEach(thumb => {
            thumb.classList.toggle("is-current", thumb === target);
            thumb.setAttribute("aria-pressed", thumb === target);
        });
    }

    if (target.dataset.action === "fav") {
        const isFav = wishlist.toggle(current.id);
        target.textContent = isFav ? "♥ Հավանած է" : "♡ Հավանել";
        target.setAttribute("aria-pressed", isFav);
        showToast(isFav ? "Ավելացվեց հավանածներում" : "Հանվեց հավանածներից");
    }

    if (target.dataset.action === "retry") loadProduct();
});

// Նման ապրանքների սրտիկները
relatedEl.addEventListener("click", (e) => {
    const button = e.target.closest('[data-action="fav"]');
    if (!button) return;
    const isFav = wishlist.toggle(Number(button.dataset.id));
    button.classList.toggle("is-active", isFav);
    button.setAttribute("aria-pressed", isFav);
    button.textContent = isFav ? "♥" : "♡";
});

root.addEventListener("submit", (e) => {
    if (e.target.id !== "product-form") return;
    e.preventDefault();
    const qtyInput = e.target.qty;
    const qty = Number(qtyInput.value);
    if (!Number.isInteger(qty) || qty < 1 || qty > MAX_QTY) {
        qtyInput.value = 1;
        showToast(`Քանակը՝ 1-ից ${MAX_QTY}`);
        return;
    }
    addProduct(current.id, qty);
});

loadProduct();
