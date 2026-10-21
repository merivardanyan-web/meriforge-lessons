// Անիի գրադարանը · Դաս 12
// Մեկ listener ամբողջ դարակի համար՝ դելեգացիա։ Մեկ ճշմարտության աղբյուր՝ books զանգվածը։

let books = [
    { id: 1, title: "Փոքրիկ իշխանը", author: "Անտուան դը Սենտ-Էքզյուպերի", year: 1943, pages: 96, read: 96, genre: "հեքիաթ", rating: 5, color: "#2f5d8a" },
    { id: 2, title: "Սամվել", author: "Րաֆֆի", year: 1886, pages: 560, read: 140, genre: "պատմավեպ", rating: 0, color: "#8a3b2f" },
    { id: 3, title: "Գիքորը", author: "Հովհաննես Թումանյան", year: 1907, pages: 40, read: 40, genre: "պատմվածք", rating: 5, color: "#5b7a3a" },
    { id: 4, title: "1984", author: "Ջորջ Օրուել", year: 1949, pages: 328, read: 0, genre: "վեպ", rating: 0, color: "#3d3d55" },
    { id: 5, title: "Ալքիմիկոսը", author: "Պաուլո Կոելյո", year: 1988, pages: 208, read: 208, genre: "վեպ", rating: 3, color: "#b07a1f" },
    { id: 6, title: "Վերք Հայաստանի", author: "Խաչատուր Աբովյան", year: 1858, pages: 320, read: 90, genre: "պատմավեպ", rating: 0, color: "#6b4a7a" }
];

const STATUS_TEXT = { planned: "Կարդալու եմ", reading: "Կարդում եմ", done: "Կարդացել եմ" };
const PAGES_STEP = 10;
const MAX_RATING = 5;

let currentFilter = "all";
let query = "";

const container = document.querySelector("#books");
const stats = document.querySelector("#stats");
const filtersBox = document.querySelector("#filters");
const searchInput = document.querySelector("#search");
const modal = document.querySelector("#book-modal");
const modalContent = document.querySelector("#book-modal-content");

// ---------- Տվյալներ ----------
function getStatus(book) {
    if (book.read === 0) return "planned";
    if (book.read < book.pages) return "reading";
    return "done";
}

function getVisibleBooks() {
    return books
        .filter(b => currentFilter === "all" || getStatus(b) === currentFilter)
        .filter(b => (b.title + " " + b.author).toLowerCase().includes(query));
}

// ---------- Գործողություններ․ փոխում են books-ը և կանչում render() ----------
function readPages(id) {
    const book = books.find(b => b.id === id);
    if (!book) return;
    book.read = Math.min(book.read + PAGES_STEP, book.pages);
    render();
}

function markDone(id) {
    const book = books.find(b => b.id === id);
    if (book) book.read = book.pages;
    render();
}

function rateBook(id, rating) {
    const book = books.find(b => b.id === id);
    if (book) book.rating = rating;
    render();
}

function deleteBook(id) {
    const book = books.find(b => b.id === id);
    if (!confirm(`Հանե՞լ «${book.title}»-ը դարակից`)) return;
    books = books.filter(b => b.id !== id);
    render();
}

function clearDone() {
    const doneCount = books.filter(b => getStatus(b) === "done").length;
    if (doneCount === 0 || !confirm(`Հանե՞լ ${doneCount} կարդացած գիրքը`)) return;
    books = books.filter(b => getStatus(b) !== "done");
    render();
}

// ---------- Render ----------
function createStars({ id, rating }) {
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

function render() {
    const list = getVisibleBooks();
    container.innerHTML = list.length === 0
        ? `<p class="empty">Այս պայմանով գիրք չկա։</p>`
        : list.map(createBookCard).join("");
    const done = books.filter(b => getStatus(b) === "done").length;
    stats.textContent = `${books.length} գիրք դարակում · ${done} կարդացած · ցուցադրված է ${list.length}`;
}

// ---------- Մոդալ ----------
function openModal(book) {
    const { title, author, year, pages, read, genre, rating } = book;
    modalContent.innerHTML = `
        <h2 id="book-modal-title">${title}</h2>
        <p class="meta">${author} · ${year} · ${genre}</p>
        <p>Կարդացել եմ ${read} էջ ${pages}-ից։ ${rating ? `Գնահատական՝ ${"★".repeat(rating)}` : "Դեռ չեմ գնահատել։"}</p>
    `;
    modal.classList.add("is-open");
    document.body.classList.add("no-scroll");
}

function closeModal() {
    modal.classList.remove("is-open");
    document.body.classList.remove("no-scroll");
}

// ---------- ★ Մեկ listener ամբողջ դարակի համար ----------
container.addEventListener("click", (e) => {
    const button = e.target.closest("[data-action]");
    if (!button) return;
    const id = Number(button.closest(".book").dataset.id);   // dataset-ը միշտ տող է

    switch (button.dataset.action) {
        case "read":    readPages(id); break;
        case "done":    markDone(id); break;
        case "rate":    rateBook(id, Number(button.dataset.rating)); break;
        case "delete":  deleteBook(id); break;
        case "details": openModal(books.find(b => b.id === id)); break;
    }
});

// Ֆիլտրերը՝ նույնպես դելեգացիայով
filtersBox.addEventListener("click", (e) => {
    const button = e.target.closest("[data-filter]");
    if (!button) return;
    currentFilter = button.dataset.filter;
    filtersBox.querySelectorAll("[data-filter]").forEach(b => b.classList.toggle("is-active", b === button));
    render();
});

searchInput.addEventListener("input", () => {
    query = searchInput.value.trim().toLowerCase();
    render();
});

document.querySelector('[data-action="clear-done"]').addEventListener("click", clearDone);

modal.addEventListener("click", (e) => {
    if (e.target === modal || e.target.closest('[data-action="close-modal"]')) closeModal();
});

document.addEventListener("keydown", (e) => {
    if (e.key === "Escape" && modal.classList.contains("is-open")) closeModal();
});

render();
