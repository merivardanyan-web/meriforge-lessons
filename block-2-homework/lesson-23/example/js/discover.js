// «Գտնել նոր գիրք»՝ որոնում Open Library-ում — Դաս 21, քեշ՝ Դաս 23
// Պարամետրեր, էջավորում, debounce, AbortController (մրցավազքի դեմ)

import { searchBooks } from "./api.js";
import { debounce } from "./utils.js";
import { getErrorMessage } from "./errors.js";

const MIN_QUERY = 3;
const SEARCH_DELAY = 400;
const PAGE_WINDOW = 2;          // ընթացիկ էջից աջ և ձախ քանի՞ կոճակ

export function initDiscover(onPick) {
    const input = document.querySelector("#discover-query");
    const limitSelect = document.querySelector("#discover-limit");
    const statusEl = document.querySelector("#discover-status");
    const results = document.querySelector("#discover-results");
    const pager = document.querySelector("#discover-pager");

    const state = { query: "", page: 1, limit: Number(limitSelect.value), total: 0 };
    let books = [];
    let controller = null;
    let fromCache = false;

    // ---------- Ցուցադրում ----------
    function createResult(book, index) {
        const cover = book.cover
            ? `<div class="book__cover book__cover--img"><img src="${book.cover}" alt="" loading="lazy" width="300" height="150"></div>`
            : `<div class="book__cover"><span>${book.title}</span></div>`;
        return `
            <article class="book">
                ${cover}
                <div class="book__body">
                    <h3 class="book__title">${book.title}</h3>
                    <p class="meta">${book.author} · ${book.year}${book.pages ? ` · ${book.pages} էջ` : ""}</p>
                    <div class="book__actions">
                        <button type="button" class="btn btn--outline btn--sm" data-index="${index}">Ավելացնել դարակ</button>
                    </div>
                </div>
            </article>
        `;
    }

    function renderPager() {
        const totalPages = Math.ceil(state.total / state.limit);
        if (totalPages <= 1) {
            pager.innerHTML = "";
            return;
        }
        const from = Math.max(1, state.page - PAGE_WINDOW);
        const to = Math.min(totalPages, state.page + PAGE_WINDOW);
        let buttons = `<button type="button" class="btn btn--ghost btn--sm" data-page="${state.page - 1}" ${state.page === 1 ? "disabled" : ""}>← Նախորդ</button>`;
        for (let p = from; p <= to; p++) {
            buttons += `<button type="button" class="btn btn--outline btn--sm" data-page="${p}" ${p === state.page ? 'aria-current="page"' : ""}>${p}</button>`;
        }
        buttons += `<button type="button" class="btn btn--ghost btn--sm" data-page="${state.page + 1}" ${state.page === totalPages ? "disabled" : ""}>Հաջորդ →</button>`;
        buttons += `<span class="meta">Էջ ${state.page} / ${totalPages}</span>`;
        pager.innerHTML = buttons;
    }

    function render() {
        if (books.length === 0) {
            statusEl.textContent = "";
            results.innerHTML = `<p class="empty"></p>`;
            results.querySelector(".empty").textContent = `«${state.query}» հարցումով ոչինչ չգտնվեց`;   // օգտագործողի տեքստ՝ textContent
            pager.innerHTML = "";
            return;
        }
        const start = (state.page - 1) * state.limit;
        statusEl.textContent = `Ցուցադրված է ${start + 1}–${start + books.length} / ${state.total.toLocaleString("ru-RU")}${fromCache ? " · քեշից" : ""}`;
        results.innerHTML = books.map(createResult).join("");
        renderPager();
    }

    // ---------- Բեռնում ----------
    async function load() {
        if (controller) controller.abort();          // նախորդ հարցումը այլևս պետք չէ
        controller = new AbortController();

        statusEl.textContent = "Որոնում ենք…";
        statusEl.classList.remove("status-error");
        try {
            const data = await searchBooks(state, controller.signal);
            books = data.books;
            state.total = data.total;
            fromCache = data.fromCache;
            render();
        } catch (error) {
            if (error.name === "AbortError") return;    // չեղարկումը սխալ չէ
            statusEl.textContent = `Որոնումը չհաջողվեց։ ${getErrorMessage(error)}`;
            statusEl.classList.add("status-error");
            results.innerHTML = "";
            pager.innerHTML = "";
        }
    }

    // ---------- Իրադարձություններ ----------
    input.addEventListener("input", debounce(() => {
        state.query = input.value.trim();
        state.page = 1;                                 // նոր որոնում → առաջին էջ
        if (state.query.length < MIN_QUERY) {
            if (controller) controller.abort();
            statusEl.textContent = `Գրիր առնվազն ${MIN_QUERY} նիշ`;
            results.innerHTML = "";
            pager.innerHTML = "";
            return;
        }
        load();
    }, SEARCH_DELAY));

    limitSelect.addEventListener("change", () => {
        state.limit = Number(limitSelect.value);
        state.page = 1;
        if (state.query.length >= MIN_QUERY) load();
    });

    pager.addEventListener("click", (e) => {
        const button = e.target.closest("[data-page]");
        if (!button || button.disabled) return;
        state.page = Number(button.dataset.page);
        load();
        document.querySelector("#discover").scrollIntoView({ behavior: "smooth" });
    });

    results.addEventListener("click", (e) => {
        const button = e.target.closest("[data-index]");
        if (button) onPick(books[Number(button.dataset.index)]);
    });
}
