// Կատալոգի էջ — JS Դաս 15 + բեռնում API-ից (20), դասեր (22), Set/Map (23)

import { initLayout } from "../ui/layout.js";
import { initCartModal } from "../ui/cart-modal.js";
import { createCard, createSkeletonCards, createErrorState } from "../ui/cards.js";
import { showToast } from "../ui/toast.js";
import { getVisibleProducts, countBy } from "../catalog/filters.js";
import { api } from "../api.js";
import { wishlist } from "../store.js";
import { getErrorMessage } from "../errors.js";
import { CATEGORIES, BRANDS, PAGE_SIZE, SEARCH_DELAY } from "../config.js";
import { debounce, paginate } from "../utils.js";

initLayout();
initCartModal();

// ---------- Վիճակ ----------
let products = [];
const params = new URLSearchParams(location.search);       // catalog.html?cat=laptops&q=մկնիկ

const state = {
    categories: params.get("cat") ? [params.get("cat")] : [],
    brands: [],
    min: null,
    max: null,
    stock: "all",
    search: params.get("q") ?? "",
    sort: "popular",
    page: 1
};

// ---------- Էլեմենտներ ----------
const filtersForm = document.querySelector("#filters");
const productsEl = document.querySelector("#products");
const statsEl = document.querySelector("#catalog-stats");
const paginationEl = document.querySelector("#pagination");
const searchInput = document.querySelector("#q");
const sortSelect = document.querySelector("#sort");
const [categoryList, brandList] = filtersForm.querySelectorAll(".filters__group ul");

// ---------- Ֆիլտրերի ցուցակները՝ տվյալներից (Set + Map, JS Դաս 23) ----------
function createOptions(name, labels, counts, checked) {
    return [...counts.keys()]
        .sort((a, b) => labels[a].localeCompare(labels[b], "hy"))
        .map(key => `
            <li><label class="check">
                <input type="checkbox" name="${name}" value="${key}" ${checked.includes(key) ? "checked" : ""}>
                ${labels[key]} <span class="text-muted">(${counts.get(key)})</span>
            </label></li>
        `).join("");
}

function renderFilterOptions() {
    categoryList.innerHTML = createOptions("cat", CATEGORIES, countBy(products, "category"), state.categories);
    brandList.innerHTML = createOptions("brand", BRANDS, countBy(products, "brand"), state.brands);
}

// ---------- Ֆորմայից վիճակ ----------
const getChecked = name =>
    [...filtersForm.querySelectorAll(`input[name="${name}"]:checked`)].map(input => input.value);
const toNumberOrNull = value => (value === "" ? null : Number(value));

function readFilters() {
    state.categories = getChecked("cat");
    state.brands = getChecked("brand");
    state.min = toNumberOrNull(filtersForm.min.value);
    state.max = toNumberOrNull(filtersForm.max.value);
    state.stock = filtersForm.querySelector('input[name="stock"]:checked').value;
}

// ---------- Render ----------
function renderPagination(page, totalPages) {
    if (totalPages <= 1) {
        paginationEl.innerHTML = "";
        return;
    }
    const pages = Array.from({ length: totalPages }, (_, i) => i + 1);
    paginationEl.innerHTML = `<ul class="flex flex-wrap gap-10">${pages.map(p => `
        <li><a href="catalog.html?page=${p}" data-page="${p}" ${p === page ? 'aria-current="page"' : ""}>${p}</a></li>
    `).join("")}</ul>`;
}

function renderEmpty() {
    productsEl.innerHTML = `
        <div class="empty-state">
            <p class="empty-state__text"></p>
            <button type="button" class="btn btn--primary" data-action="reset">Մաքրել ֆիլտրերը</button>
        </div>
    `;
    // Որոնման տեքստը օգտագործողինն է․ textContent, ոչ innerHTML (JS Դաս 10)
    productsEl.querySelector(".empty-state__text").textContent = state.search
        ? `«${state.search}» հարցումով ապրանք չգտնվեց։`
        : "Այս ֆիլտրերով ապրանք չգտնվեց։";
}

function render() {
    const visible = getVisibleProducts(products, state, wishlist);
    const { items, page, totalPages, start } = paginate(visible, state.page, PAGE_SIZE);
    state.page = page;

    if (visible.length === 0) {
        renderEmpty();
        statsEl.textContent = `Ցուցադրված է 0՝ ${products.length} ապրանքից`;
    } else {
        productsEl.innerHTML = items.map(p => createCard(p, { isFavorite: wishlist.has(p.id) })).join("");
        statsEl.textContent = `Ցուցադրված է ${start + 1}–${start + items.length}՝ ${visible.length} ապրանքից`;
    }
    renderPagination(page, visible.length === 0 ? 0 : totalPages);
}

// ---------- Բեռնում՝ երեք վիճակ (JS Դաս 20) ----------
async function loadProducts() {
    productsEl.innerHTML = createSkeletonCards(PAGE_SIZE);
    productsEl.setAttribute("aria-busy", "true");
    statsEl.textContent = "Բեռնվում է…";
    paginationEl.innerHTML = "";

    try {
        products = await api.getProducts();
        renderFilterOptions();
        readFilters();
        render();
    } catch (error) {
        productsEl.innerHTML = createErrorState(getErrorMessage(error));
        statsEl.textContent = "";
        console.error(error);
    } finally {
        productsEl.removeAttribute("aria-busy");
    }
}

function resetFilters() {
    filtersForm.reset();
    filtersForm.querySelectorAll('input[type="checkbox"]').forEach(input => { input.checked = false; });
    searchInput.value = "";
    sortSelect.value = "popular";
    state.search = "";
    state.sort = "popular";
    state.page = 1;
    readFilters();
    render();
}

// ---------- Իրադարձություններ ----------
filtersForm.addEventListener("change", () => {
    const before = JSON.stringify(state);
    readFilters();
    if (JSON.stringify(state) === before) return;     // գնի դաշտից դուրս գալը նոր change է, բայց ոչինչ չի փոխվել
    state.page = 1;
    render();
});

filtersForm.addEventListener("submit", (e) => e.preventDefault());

searchInput.addEventListener("input", debounce(() => {
    state.search = searchInput.value.trim();
    state.page = 1;
    render();
}, SEARCH_DELAY));

sortSelect.addEventListener("change", () => {
    state.sort = sortSelect.value;
    render();
});

paginationEl.addEventListener("click", (e) => {
    const link = e.target.closest("[data-page]");
    if (!link) return;
    e.preventDefault();
    state.page = Number(link.dataset.page);
    render();
    document.querySelector("#catalog").scrollIntoView({ behavior: "smooth" });
});

document.querySelector("#catalog").addEventListener("click", (e) => {
    const target = e.target.closest("[data-action]");
    if (!target) return;

    switch (target.dataset.action) {
        case "reset":
            resetFilters();
            break;
        case "retry":
            loadProducts();
            break;
        case "fav": {
            const isFav = wishlist.toggle(Number(target.dataset.id));
            showToast(isFav ? "Ավելացվեց հավանածներում" : "Հանվեց հավանածներից");
            render();
            break;
        }
    }
});

// ---------- Գործարկում ----------
searchInput.value = state.search;
loadProducts();
