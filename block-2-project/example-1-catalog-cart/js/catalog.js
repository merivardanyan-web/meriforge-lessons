// Կատալոգ՝ ֆիլտրեր, որոնում, դասավորում, էջավորում — JS Դաս 15

const PAGE_SIZE = 6;
const SEARCH_DELAY = 300;

// ---------- Վիճակ՝ մեկ ճշմարտության աղբյուր ----------
const state = {
    categories: [],      // ["laptops", "phones"]
    brands: [],
    min: null,
    max: null,
    stock: "all",        // all · in · sale
    search: "",
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

// ---------- Ֆորմայից վիճակ ----------
const getChecked = name =>
    [...filtersForm.querySelectorAll(`input[name="${name}"]:checked`)].map(input => input.value);

// Դատարկ դաշտը null է, ոչ 0 — այլապես «Մինչև» դատարկը կթաքցներ ամեն ինչ
const toNumberOrNull = value => (value === "" ? null : Number(value));

function readFilters() {
    state.categories = getChecked("cat");
    state.brands = getChecked("brand");
    state.min = toNumberOrNull(filtersForm.min.value);
    state.max = toNumberOrNull(filtersForm.max.value);
    state.stock = filtersForm.querySelector('input[name="stock"]:checked').value;
}

// ---------- Pipeline՝ ֆիլտր → որոնում → դասավորում ----------
function getVisibleProducts() {
    let result = [...PRODUCTS];                          // պատճեն — բնօրինակը չենք դիպչում

    if (state.categories.length > 0) {
        result = result.filter(p => state.categories.includes(p.category));
    }
    if (state.brands.length > 0) {
        result = result.filter(p => state.brands.includes(p.brand));
    }
    if (state.min !== null) {
        result = result.filter(p => p.price >= state.min);
    }
    if (state.max !== null) {
        result = result.filter(p => p.price <= state.max);
    }
    if (state.stock === "in") {
        result = result.filter(p => p.inStock);
    }
    if (state.stock === "sale") {
        result = result.filter(p => p.oldPrice !== null);
    }
    if (state.search !== "") {
        const query = state.search.toLowerCase();
        result = result.filter(p =>
            p.name.toLowerCase().includes(query) ||
            CATEGORIES[p.category].toLowerCase().includes(query)
        );
    }

    switch (state.sort) {
        case "price-asc":  result.sort((a, b) => a.price - b.price); break;
        case "price-desc": result.sort((a, b) => b.price - a.price); break;
        case "rating":     result.sort((a, b) => b.rating - a.rating); break;
        case "new":        result.sort((a, b) => b.added.localeCompare(a.added)); break;
        default:           result.sort((a, b) => b.popularity - a.popularity);
    }
    return result;
}

// ---------- Քարտ ----------
function createBadge({ oldPrice, price, isNew }) {
    if (oldPrice !== null) {
        return `<span class="card__badge">-${getDiscountPercent(price, oldPrice)}%</span>`;
    }
    return isNew ? `<span class="card__badge card__badge--new">Նոր</span>` : "";
}

function createCard(product) {
    const { id, image, name, meta, price, oldPrice, inStock, alt } = product;
    const oldPriceHtml = oldPrice !== null ? ` <del>${formatPrice(oldPrice)}</del>` : "";
    const button = inStock
        ? `<button type="button" class="btn btn--primary btn--sm" data-action="add" data-id="${id}">Գնել</button>`
        : `<button type="button" class="btn btn--primary btn--sm" disabled>Սպառված</button>`;

    return `
        <article class="card ${inStock ? "" : "is-out"}" data-id="${id}">
            ${createBadge(product)}
            <picture class="card__media ratio-3-2">
                <source srcset="images/${image}.webp" type="image/webp">
                <img class="img-cover" src="images/${image}.jpg" alt="${alt}" width="600" height="400" loading="lazy">
            </picture>
            <div class="card__body">
                <h3 class="card__title"><a href="product.html?id=${id}">${name}</a></h3>
                <p class="card__meta">${meta}</p>
                ${inStock ? "" : `<p class="card__stock">Սպառված է</p>`}
                <div class="card__footer">
                    <p class="card__price">${formatPrice(price)}${oldPriceHtml}</p>
                    ${button}
                </div>
            </div>
        </article>
    `;
}

// ---------- Render ----------
function renderPagination(totalPages) {
    if (totalPages <= 1) {
        paginationEl.innerHTML = "";
        return;
    }
    const links = [];
    for (let page = 1; page <= totalPages; page++) {     // JS Դաս 3
        const current = page === state.page ? ' aria-current="page"' : "";
        links.push(`<li><a href="catalog.html?page=${page}" data-page="${page}"${current}>${page}</a></li>`);
    }
    paginationEl.innerHTML = `<ul class="flex flex-wrap gap-10">${links.join("")}</ul>`;
}

function render() {
    const visible = getVisibleProducts();
    const totalPages = Math.ceil(visible.length / PAGE_SIZE);
    state.page = Math.min(state.page, Math.max(totalPages, 1));  // ֆիլտրից հետո էջը կարող է չլինել

    if (visible.length === 0) {
        productsEl.innerHTML = `
            <div class="empty-state">
                <p>Այս ֆիլտրերով ապրանք չգտնվեց։</p>
                <button type="button" class="btn btn--primary" data-action="reset">Մաքրել ֆիլտրերը</button>
            </div>
        `;
        statsEl.textContent = `Ցուցադրված է 0՝ ${PRODUCTS.length} ապրանքից`;
        renderPagination(0);
        return;
    }

    const start = (state.page - 1) * PAGE_SIZE;
    const pageItems = visible.slice(start, start + PAGE_SIZE);   // JS Դաս 5
    productsEl.innerHTML = pageItems.map(createCard).join("");
    statsEl.textContent =
        `Ցուցադրված է ${start + 1}–${start + pageItems.length}՝ ${visible.length} ապրանքից`;
    renderPagination(totalPages);
}

function resetFilters() {
    filtersForm.reset();                                 // checkbox-ները, radio-ն, գները
    searchInput.value = "";
    sortSelect.value = "popular";
    state.search = "";
    state.sort = "popular";
    state.page = 1;
    readFilters();
    render();
}

// ---------- Իրադարձություններ ----------
// Ամբողջ ֆորմայի համար մեկ listener՝ change-ը փուչիկանում է — JS Դաս 12
filtersForm.addEventListener("change", () => {
    const before = JSON.stringify(state);
    readFilters();
    // Գնի դաշտից դուրս գալիս change-ը կրկին է գալիս։ Եթե ոչինչ չի փոխվել, չենք վերագծում,
    // այլապես քարտերը կփոխարինվեին հենց սեղմման պահին, և սեղմումը կկորչեր
    if (JSON.stringify(state) === before) return;
    state.page = 1;
    render();
});

// Enter «Գին» դաշտում չպիտի վերաբեռնի էջը
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
    document.querySelector("#catalog").scrollIntoView({ behavior: "smooth" });  // JS Դաս 14
});

// «Մաքրել ֆիլտրերը»՝ և՛ ֆորմայում, և՛ դատարկ վիճակում
document.querySelector("#catalog").addEventListener("click", (e) => {
    if (e.target.closest('[data-action="reset"]')) resetFilters();
});

// ---------- Գործարկում ----------
// Header-ի ենթամենյուն բացում է catalog.html?cat=laptops — նշում ենք այդ checkbox-ը (JS Դաս 15, տնային)
const urlCategory = new URLSearchParams(location.search).get("cat");
if (urlCategory && CATEGORIES[urlCategory]) {
    filtersForm.querySelector(`input[name="cat"][value="${urlCategory}"]`).checked = true;
}

readFilters();
render();
