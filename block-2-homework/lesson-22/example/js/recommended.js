// «Ինչ կարդալ հետո»՝ data/recommended.json-ից, երեք վիճակով — Դաս 20
import { getRecommended } from "./api.js";

const SKELETON_COUNT = 3;

export function initRecommended({ isOnShelf, onAdd }) {
    const list = document.querySelector("#recommended-list");
    const statusEl = document.querySelector("#recommended-status");
    const tipEl = document.querySelector("#quote");
    const reloadBtn = document.querySelector('[data-action="load-recommended"]');
    let books = [];

    function showLoading() {
        reloadBtn.disabled = true;
        statusEl.textContent = "Բեռնվում է…";
        statusEl.classList.remove("status-error");
        let html = "";
        for (let i = 0; i < SKELETON_COUNT; i++) {
            html += `<div class="book book--skeleton" aria-hidden="true">
                <div class="book__cover skeleton"></div>
                <div class="book__body"><div class="skeleton skeleton--title"></div><div class="skeleton skeleton--line"></div></div>
            </div>`;
        }
        list.innerHTML = html;
    }

    function render() {
        list.innerHTML = books.map((b, i) => `
            <article class="book">
                <div class="book__body">
                    <h3 class="book__title">${b.title}</h3>
                    <p class="meta">${b.author} · ${b.year} · ${b.pages} էջ</p>
                    <div class="book__actions">
                        ${isOnShelf(b.title)
                            ? `<span class="pill">Դարակում է</span>`
                            : `<button type="button" class="btn btn--outline btn--sm" data-index="${i}">Ավելացնել դարակ</button>`}
                    </div>
                </div>
            </article>
        `).join("");
    }

    function showError(message) {
        list.innerHTML = "";
        statusEl.textContent = message;
        statusEl.classList.add("status-error");
    }

    async function load() {
        showLoading();
        try {
            const data = await getRecommended();
            books = data.books;
            tipEl.textContent = `Խորհուրդ՝ ${data.tips[Math.floor(Math.random() * data.tips.length)]}`;
            if (books.length === 0) {
                showError("Առաջարկներ դեռ չկան");
                return;
            }
            statusEl.textContent = `${books.length} առաջարկ`;
            render();
        } catch (error) {
            showError("Չհաջողվեց բեռնել։ Ստուգիր ինտերնետը և սեղմիր «Թարմացնել»։");
            console.error(error);
        } finally {
            reloadBtn.disabled = false;
        }
    }

    list.addEventListener("click", (e) => {
        const button = e.target.closest("[data-index]");
        if (!button) return;
        onAdd(books[Number(button.dataset.index)]);
        render();
    });

    reloadBtn.addEventListener("click", load);
    load();
}
