// Դարակի որոնում՝ գրելու ընթացքում — PHP Դաս 17-ի խորացված տնային (7), JS Դաս 14 (debounce)
// Առանց JS-ի նույն ֆորման աշխատում է GET-ով

import { api, csrf } from "./api.js";
import { debounce, escapeHtml } from "./ui.js";

// Նույն markup-ը, ինչ views/partials/book-card.php-ում (կարճացված), որ CSS-ը չփոխենք
function createCard(b, back) {
    const cover = b.cover
        ? `<div class="book__cover book__cover--img"><img src="${escapeHtml(b.cover)}" alt="«${escapeHtml(b.title)}»-ի շապիկը" width="400" height="200" loading="lazy"></div>`
        : `<div class="book__cover" style="--cover: ${escapeHtml(b.color)}"><span>${escapeHtml(b.title)}</span></div>`;
    const add = b.status === "done" ? "" : `
        <form method="post" action="/list/add" data-list-add>
            <input type="hidden" name="_token" value="${escapeHtml(csrf)}">
            <input type="hidden" name="id" value="${b.id}">
            <input type="hidden" name="back" value="${escapeHtml(back)}">
            <button type="submit" class="btn btn--outline btn--sm">+ Կարդալու ցուցակ</button>
        </form>`;

    return `
        <article class="book is-${escapeHtml(b.status)}">
            ${cover}
            <div class="book__body">
                <h3 class="book__title"><a href="${escapeHtml(b.url)}">${escapeHtml(b.title)}</a></h3>
                <p class="meta">${escapeHtml(b.author)} · ${escapeHtml(b.year ?? "տարին անհայտ")}</p>
                <p class="meta">${escapeHtml(b.format)}</p>
                <div class="progress"><span class="progress__bar" style="--progress: ${b.progress}%"></span></div>
                <p class="meta">${b.read} / ${b.pages} էջ · ${b.progress}%</p>
                <div class="book__actions">${add}</div>
            </div>
        </article>`;
}

export function initLiveSearch() {
    const form = document.querySelector("form[data-live-search]");
    const results = document.querySelector("[data-search-results]");
    if (!form || !results) return;

    let controller = null;

    async function run() {
        const params = Object.fromEntries(new FormData(form));
        if (form.dataset.genre) params.genre = form.dataset.genre;      // /genre/... էջում ժանրը ուղու մեջ է
        const url = `${location.pathname}?${new URLSearchParams(params)}`;

        // Նախորդ հարցումը չեղարկում ենք, որ հին պատասխանը չգա նորից հետո — JS Դաս 21
        controller?.abort();
        controller = new AbortController();

        try {
            const { data, meta } = await api.searchBooks({ ...params, per_page: 6 }, controller.signal);
            results.innerHTML = `<p class="meta" role="status">Գտնվեց ${meta.total} գիրք${meta.last_page > 1 ? ` · էջ 1 / ${meta.last_page}` : ""}</p>`
                + (data.length
                    ? `<div class="grid-auto gap-20">${data.map((b) => createCard(b, url)).join("")}</div>`
                    : `<p class="empty">«${escapeHtml(params.q)}» հարցմամբ գիրք չկա։</p>`);
            history.replaceState(null, "", url);          // հասցեն՝ արդյունքի հետ համաձայն
            document.querySelector(".pager")?.remove();   // հին էջավորումը այլևս ճիշտ չէ
        } catch (error) {
            if (error.name !== "AbortError") form.submit();
        }
    }

    form.elements.q.addEventListener("input", debounce(run, 300));
}
