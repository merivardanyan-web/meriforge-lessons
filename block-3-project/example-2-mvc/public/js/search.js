// Կատալոգի որոնում՝ գրելու ընթացքում — PHP Դաս 17-ի խորացված տնային, JS Դաս 14 (debounce)
// Առանց JS-ի նույն ֆորման աշխատում է GET-ով

import { api } from "./api.js";
import { debounce, formatPrice, escapeHtml, announce } from "./ui.js";
import { csrf } from "./api.js";

// Նույն markup-ը, ինչ views/partials/card.php-ում, որ CSS-ը չփոխենք
function createCard(p, back) {
    const badge = p.discount > 0 ? `<span class="card__badge">-${p.discount}%</span>` : "";
    const old = p.discount > 0 ? ` <del>${formatPrice(p.price)}</del>` : "";
    const buy = p.in_stock
        ? `<form method="post" action="/cart/add" data-cart-add>
               <input type="hidden" name="_token" value="${escapeHtml(csrf)}">
               <input type="hidden" name="id" value="${p.id}">
               <input type="hidden" name="back" value="${escapeHtml(back)}">
               <button type="submit" class="btn btn--primary btn--sm">Գնել<span class="visually-hidden"> ${escapeHtml(p.name)}</span></button>
           </form>`
        : `<button type="button" class="btn btn--primary btn--sm" disabled>Սպառված է</button>`;

    return `
        <article class="card${p.in_stock ? "" : " is-out"}">
            ${badge}
            <picture class="card__media ratio-3-2">
                <img class="img-cover" src="${escapeHtml(p.image)}" alt="${escapeHtml(p.name)}" width="600" height="400" loading="lazy">
            </picture>
            <div class="card__body">
                <h3 class="card__title"><a href="${escapeHtml(p.url)}">${escapeHtml(p.name)}</a></h3>
                <p class="card__meta">${escapeHtml(p.specs)}</p>
                <div class="card__footer">
                    <p class="card__price">${formatPrice(p.final_price)}${old}</p>
                    ${buy}
                </div>
            </div>
        </article>`;
}

export function initLiveSearch() {
    const form = document.querySelector("form[data-live-search]");
    const results = document.querySelector("[data-search-results]");
    const status = document.querySelector("[data-search-status]");
    if (!form || !results) return;

    const input = form.elements.q;
    let controller = null;

    async function run() {
        const params = Object.fromEntries(new FormData(form));
        const url = `${location.pathname}?${new URLSearchParams(params)}`;

        // Նախորդ հարցումը չեղարկում ենք, որ հին պատասխանը չգա նորից հետո — JS Դաս 21
        controller?.abort();
        controller = new AbortController();

        try {
            // /category/laptops էջում կատեգորիան ուղու մեջ է, ոչ ֆորմայում
            const category = form.dataset.category ? { category: form.dataset.category } : {};
            const { data, meta } = await api.searchProducts({ ...params, ...category, per_page: 6 }, controller.signal);

            results.innerHTML = data.length
                ? `<div class="grid-auto gap-20">${data.map((p) => createCard(p, url)).join("")}</div>`
                : `<div class="empty-state"><p>«${escapeHtml(params.q)}» հարցմամբ ապրանք չկա։</p><a href="/catalog" class="btn btn--primary">Մաքրել ֆիլտրերը</a></div>`;

            const text = meta.total > 0
                ? `Ցուցադրված է 1–${data.length}՝ ${meta.total} ապրանքից`
                : "Ոչինչ չգտնվեց";
            status.textContent = text;
            announce(text);

            history.replaceState(null, "", url);      // F5-ից հետո՝ նույն որոնումը
        } catch (error) {
            if (error.name !== "AbortError") form.submit();
        }
    }

    input.addEventListener("input", debounce(run, 300));
}
