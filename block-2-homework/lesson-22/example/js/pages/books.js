// «Գրքեր» էջի մուտքի կետը — Դաս 18–22
// Դարակը, գրքերը և ժամաչափը հիմա դասեր են (models/)

import { Shelf } from "../models/Shelf.js";
import { ReadingTimer } from "../models/ReadingTimer.js";
import { BOOKS_KEY, INITIAL_BOOKS, PAGES_STEP } from "../data.js";
import { renderShelf, createDetails } from "../render.js";
import { initBookForm, fillBookForm } from "../form.js";
import { initRecommended } from "../recommended.js";
import { initReview } from "../review.js";
import { initDiscover } from "../discover.js";
import { openModal, initModal } from "../modal.js";
import { showToast } from "../ui.js";
import { debounce, formatDuration } from "../utils.js";

const shelf = new Shelf(BOOKS_KEY, INITIAL_BOOKS);

const container = document.querySelector("#books");
const statsEl = document.querySelector("#stats");
const filtersBox = document.querySelector("#filters");
const searchInput = document.querySelector("#search");

const refresh = () => renderShelf(container, statsEl, shelf);

// ---------- Դարակ ----------
container.addEventListener("click", (e) => {
    const button = e.target.closest("[data-action]");
    if (!button) return;
    const id = Number(button.closest(".book").dataset.id);
    const book = shelf.find(id);

    switch (button.dataset.action) {
        case "read":    shelf.update(id, b => b.readPages(PAGES_STEP)); break;
        case "done":    shelf.update(id, b => b.finish()); break;
        case "rate":    shelf.update(id, b => b.rate(Number(button.dataset.rating))); break;
        case "details": openModal(createDetails(book)); return;
        case "delete":
            if (!confirm(`Հանե՞լ «${book.title}»-ը դարակից`)) return;
            shelf.remove(id);
            showToast(`«${book.title}»-ը հանվեց դարակից`);
            break;
    }
    refresh();
});

filtersBox.addEventListener("click", (e) => {
    const button = e.target.closest("[data-filter]");
    if (!button) return;
    shelf.setFilter(button.dataset.filter);
    filtersBox.querySelectorAll("[data-filter]").forEach(b => b.classList.toggle("is-active", b === button));
    refresh();
});

searchInput.addEventListener("input", debounce(() => {
    shelf.setQuery(searchInput.value);
    refresh();
}, 300));

document.querySelector('[data-action="clear-done"]').addEventListener("click", () => {
    if (!confirm("Հանե՞լ բոլոր կարդացած գրքերը")) return;
    shelf.clearDone();
    refresh();
});

document.querySelector('[data-action="show-stats"]').addEventListener("click", async () => {
    const { renderStats } = await import("../stats.js");
    renderStats(shelf.all, document.querySelector("#stats-details"));
});

// ---------- Ժամաչափ ----------
const timerBox = document.querySelector(".timer");
const timeEl = document.querySelector("#timer-time");
const timer = new ReadingTimer(seconds => { timeEl.textContent = formatDuration(seconds); });

timerBox.addEventListener("click", (e) => {
    const action = e.target.closest("[data-timer]")?.dataset.timer;
    if (action === "start") timer.start();
    if (action === "stop" && timer.isRunning) {
        timer.stop();
        showToast(`Կարդացիր ${Math.floor(timer.seconds / 60)} րոպե ${timer.seconds % 60} վայրկյան։ Ապրես։`);
    }
    if (action === "reset") timer.reset();
    timerBox.classList.toggle("is-running", timer.isRunning);
});

// ---------- Մնացած մասերը ----------
function addToShelf(data) {
    try {
        const book = shelf.add(data);
        refresh();
        showToast(`«${book.title}»-ը ավելացվեց`);
    } catch (error) {
        showToast(error.message);                // Book-ի constructor-ի ստուգումը
    }
}

initModal();
initBookForm(addToShelf);
initReview();
initDiscover(fillBookForm);
initRecommended({ isOnShelf: title => shelf.has(title), onAdd: book => addToShelf({ ...book, read: 0 }) });

refresh();
