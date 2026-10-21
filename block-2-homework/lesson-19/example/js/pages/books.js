// «Գրքեր» էջի մուտքի կետը — Դաս 18, 19
// Այստեղ միայն կապակցում ենք մոդուլները․ տրամաբանությունը նրանց ներսում է

import * as shelf from "../shelf.js";
import { renderShelf, createDetails } from "../render.js";
import { initBookForm } from "../form.js";
import { initTimer } from "../timer.js";
import { initRecommended } from "../recommended.js";
import { openModal, initModal } from "../modal.js";
import { showToast } from "../ui.js";
import { debounce } from "../utils.js";

const container = document.querySelector("#books");
const statsEl = document.querySelector("#stats");
const filtersBox = document.querySelector("#filters");
const searchInput = document.querySelector("#search");

function refresh() {
    renderShelf(container, statsEl, shelf.getVisibleBooks(), shelf.getBooks());
}

// ---------- Դարակի կոճակներ՝ մեկ listener ----------
container.addEventListener("click", (e) => {
    const button = e.target.closest("[data-action]");
    if (!button) return;
    const id = Number(button.closest(".book").dataset.id);
    const book = shelf.getBook(id);

    switch (button.dataset.action) {
        case "read":    shelf.readPages(id); break;
        case "done":    shelf.markDone(id); break;
        case "rate":    shelf.rateBook(id, Number(button.dataset.rating)); break;
        case "details": openModal(createDetails(book)); return;
        case "delete":
            if (!confirm(`Հանե՞լ «${book.title}»-ը դարակից`)) return;
            shelf.deleteBook(id);
            showToast(`«${book.title}»-ը հանվեց դարակից`);
            break;
    }
    initRecommended({
    isOnShelf: title => shelf.getBooks().some(b => b.title === title),
    onAdd: (book) => {
        shelf.addBook({ ...book, read: 0 });
        refresh();
        showToast(`«${book.title}»-ը ավելացվեց «Կարդալու եմ» ցուցակում`);
    }
});

refresh();
});

filtersBox.addEventListener("click", (e) => {
    const button = e.target.closest("[data-filter]");
    if (!button) return;
    shelf.setFilter(button.dataset.filter);
    filtersBox.querySelectorAll("[data-filter]").forEach(b => b.classList.toggle("is-active", b === button));
    initRecommended({
    isOnShelf: title => shelf.getBooks().some(b => b.title === title),
    onAdd: (book) => {
        shelf.addBook({ ...book, read: 0 });
        refresh();
        showToast(`«${book.title}»-ը ավելացվեց «Կարդալու եմ» ցուցակում`);
    }
});

refresh();
});

searchInput.addEventListener("input", debounce(() => {
    shelf.setQuery(searchInput.value);
    initRecommended({
    isOnShelf: title => shelf.getBooks().some(b => b.title === title),
    onAdd: (book) => {
        shelf.addBook({ ...book, read: 0 });
        refresh();
        showToast(`«${book.title}»-ը ավելացվեց «Կարդալու եմ» ցուցակում`);
    }
});

refresh();
}, 300));

document.querySelector('[data-action="clear-done"]').addEventListener("click", () => {
    if (!confirm("Հանե՞լ բոլոր կարդացած գրքերը")) return;
    shelf.clearDone();
    initRecommended({
    isOnShelf: title => shelf.getBooks().some(b => b.title === title),
    onAdd: (book) => {
        shelf.addBook({ ...book, read: 0 });
        refresh();
        showToast(`«${book.title}»-ը ավելացվեց «Կարդալու եմ» ցուցակում`);
    }
});

refresh();
});

// ---------- Վիճակագրություն՝ դինամիկ import ----------
// stats.js-ը ներբեռնվում է միայն առաջին սեղմումից (async/await-ը՝ Դաս 20-ում)
document.querySelector('[data-action="show-stats"]').addEventListener("click", async () => {
    const { renderStats } = await import("../stats.js");
    renderStats(shelf.getBooks(), document.querySelector("#stats-details"));
});

// ---------- Մնացած մասերը ----------
initModal();
initBookForm((book) => {
    shelf.addBook(book);
    initRecommended({
    isOnShelf: title => shelf.getBooks().some(b => b.title === title),
    onAdd: (book) => {
        shelf.addBook({ ...book, read: 0 });
        refresh();
        showToast(`«${book.title}»-ը ավելացվեց «Կարդալու եմ» ցուցակում`);
    }
});

refresh();
    showToast(`«${book.title}»-ը ավելացվեց`);
});
initTimer((seconds) => {
    showToast(`Կարդացիր ${Math.floor(seconds / 60)} րոպե ${seconds % 60} վայրկյան։ Ապրես։`);
});

initRecommended({
    isOnShelf: title => shelf.getBooks().some(b => b.title === title),
    onAdd: (book) => {
        shelf.addBook({ ...book, read: 0 });
        refresh();
        showToast(`«${book.title}»-ը ավելացվեց «Կարդալու եմ» ցուցակում`);
    }
});

refresh();
