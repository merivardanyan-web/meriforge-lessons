// Դարակի HTML-ը — Դաս 10, 12, 18
import { STATUS_TEXT, PAGES_STEP, MAX_RATING } from "./data.js";
import { getStatus } from "./shelf.js";

function createStars({ rating }) {
    let html = "";
    for (let i = 1; i <= MAX_RATING; i++) {
        html += `<button type="button" class="${i <= rating ? "is-on" : ""}" data-action="rate" data-rating="${i}" aria-label="${i} աստղ">★</button>`;
    }
    return `<div class="stars" aria-label="Գնահատական">${html}</div>`;
}

function createBookCard(book) {
    const { id, title, author, year, pages, read, color } = book;
    const status = getStatus(book);
    const percent = Math.round(read / pages * 100);
    const actions = status === "done"
        ? createStars(book)
        : `<button type="button" class="btn btn--outline btn--sm" data-action="read">+${PAGES_STEP} էջ</button>
           <button type="button" class="btn btn--ghost btn--sm" data-action="done">Կարդացի</button>`;
    return `
        <article class="book is-${status}" data-id="${id}">
            <div class="book__cover" style="--cover: ${color}"><span>${title}</span></div>
            <div class="book__body">
                <h3 class="book__title">${title}</h3>
                <p class="meta">${author} · ${year}</p>
                <div class="progress"><span class="progress__bar" style="--progress: ${percent}%"></span></div>
                <p class="meta">${read} / ${pages} էջ · ${percent}%</p>
                <span class="pill">${STATUS_TEXT[status]}</span>
                <div class="book__actions">
                    ${actions}
                    <button type="button" class="btn btn--ghost btn--sm" data-action="details">Մանրամասն</button>
                    <button type="button" class="btn btn--ghost btn--sm" data-action="delete" aria-label="Հանել «${title}»-ը">✕</button>
                </div>
            </div>
        </article>
    `;
}

export function renderShelf(container, statsEl, visible, all) {
    container.innerHTML = visible.length === 0
        ? `<p class="empty">Այս պայմանով գիրք չկա։</p>`
        : visible.map(createBookCard).join("");
    const done = all.filter(b => getStatus(b) === "done").length;
    statsEl.textContent = `${all.length} գիրք դարակում · ${done} կարդացած · ցուցադրված է ${visible.length}`;
}

export function createDetails({ title, author, year, pages, read, genre, rating }) {
    return `
        <h2 id="book-modal-title">${title}</h2>
        <p class="meta">${author} · ${year} · ${genre}</p>
        <p>Կարդացել եմ ${read} էջ ${pages}-ից։ ${rating ? `Գնահատական՝ ${"★".repeat(rating)}` : "Դեռ չեմ գնահատել։"}</p>
    `;
}
