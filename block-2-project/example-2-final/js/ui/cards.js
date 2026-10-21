// Քարտեր և վիճակներ — նույն markup-ը, ինչ HTML/CSS բլոկում (JS Դաս 10, 15, 20)

import { formatPrice, getDiscountPercent } from "../utils.js";

export function createPicture({ image, alt }, className = "", lazy = true) {
    return `
        <picture class="${className}">
            <source srcset="images/${image}.webp" type="image/webp">
            <img class="img-cover" src="images/${image}.jpg" alt="${alt}" width="600" height="400"${lazy ? ' loading="lazy"' : ""}>
        </picture>
    `;
}

export function createBadge({ oldPrice, price, isNew }, className = "card__badge") {
    if (oldPrice !== null) return `<span class="${className}">-${getDiscountPercent(price, oldPrice)}%</span>`;
    return isNew ? `<span class="${className} ${className}--new">Նոր</span>` : "";
}

export function createPrice({ price, oldPrice }) {
    return formatPrice(price) + (oldPrice !== null ? ` <del>${formatPrice(oldPrice)}</del>` : "");
}

export function createCard(product, { isFavorite = false } = {}) {
    const { id, name, meta, inStock } = product;
    const button = inStock
        ? `<button type="button" class="btn btn--primary btn--sm" data-action="add" data-id="${id}">Գնել</button>`
        : `<button type="button" class="btn btn--primary btn--sm" disabled>Սպառված</button>`;
    return `
        <article class="card ${inStock ? "" : "is-out"}" data-id="${id}">
            ${createBadge(product)}
            <button type="button" class="card__fav ${isFavorite ? "is-active" : ""}" data-action="fav" data-id="${id}"
                    aria-pressed="${isFavorite}" aria-label="Հավանել՝ ${name}">${isFavorite ? "♥" : "♡"}</button>
            ${createPicture(product, "card__media ratio-3-2")}
            <div class="card__body">
                <h3 class="card__title"><a href="product.html?id=${id}">${name}</a></h3>
                <p class="card__meta">${meta}</p>
                ${inStock ? "" : `<p class="card__stock">Սպառված է</p>`}
                <div class="card__footer">
                    <p class="card__price">${createPrice(product)}</p>
                    ${button}
                </div>
            </div>
        </article>
    `;
}

// Բեռնման ընթացքում՝ կմախքային քարտեր (JS Դաս 20 · երեք վիճակ)
export function createSkeletonCards(count) {
    const card = `
        <div class="card" aria-hidden="true">
            <div class="skeleton skeleton--media"></div>
            <div class="card__body">
                <div class="skeleton skeleton--title"></div>
                <div class="skeleton skeleton--line"></div>
                <div class="skeleton skeleton--line skeleton--short"></div>
            </div>
        </div>
    `;
    return Array.from({ length: count }, () => card).join("");     // JS Դաս 21
}

export function createErrorState(message) {
    return `
        <div class="empty-state empty-state--error" role="alert">
            <p>${message}</p>
            <button type="button" class="btn btn--primary" data-action="retry">Կրկին փորձել</button>
        </div>
    `;
}
