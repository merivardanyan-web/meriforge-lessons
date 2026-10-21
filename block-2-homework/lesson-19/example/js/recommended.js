// «Ինչ կարդալ հետո»՝ բեռնում, սխալ, «Կրկին փորձել» — Դաս 19
import { getRecommended, getTip } from "./fake-api.js";

const SKELETON_COUNT = 3;

export function initRecommended({ isOnShelf, onAdd }) {
    const list = document.querySelector("#recommended-list");
    const statusEl = document.querySelector("#recommended-status");
    const tipEl = document.querySelector("#quote");
    const reloadBtn = document.querySelector('[data-action="load-recommended"]');
    let books = [];

    function showLoading() {
        reloadBtn.setAttribute("disabled", "");
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
        statusEl.textContent = `${message}։ Փորձիր նորից։`;
        statusEl.classList.add("status-error");
    }

    function load() {
        showLoading();
        // Երկու «հարցում» զուգահեռ։ allSettled՝ որ մեկի ձախողումը չկորցնի մյուսը
        Promise.allSettled([getRecommended(), getTip()])
            .then(([recommended, tip]) => {
                tipEl.textContent = tip.status === "fulfilled" ? `Խորհուրդ՝ ${tip.value}` : "";
                if (recommended.status === "rejected") {
                    showError(recommended.reason.message);
                    return;
                }
                books = recommended.value;
                statusEl.textContent = `${books.length} առաջարկ`;
                render();
            })
            .finally(() => {
                reloadBtn.removeAttribute("disabled");      // հաջողության և ձախողման դեպքում էլ
            });
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
