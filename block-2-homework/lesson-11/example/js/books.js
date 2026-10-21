// Անիի գրադարանը · Դաս 11
// Ֆիլտրի կոճակներ, կենդանի որոնում, մանրամասների մոդալ

const books = [
    { id: 1, title: "Փոքրիկ իշխանը", author: "Անտուան դը Սենտ-Էքզյուպերի", year: 1943, pages: 96, read: 96, genre: "հեքիաթ", color: "#2f5d8a" },
    { id: 2, title: "Սամվել", author: "Րաֆֆի", year: 1886, pages: 560, read: 140, genre: "պատմավեպ", color: "#8a3b2f" },
    { id: 3, title: "Գիքորը", author: "Հովհաննես Թումանյան", year: 1907, pages: 40, read: 40, genre: "պատմվածք", color: "#5b7a3a" },
    { id: 4, title: "1984", author: "Ջորջ Օրուել", year: 1949, pages: 328, read: 0, genre: "վեպ", color: "#3d3d55" },
    { id: 5, title: "Ալքիմիկոսը", author: "Պաուլո Կոելյո", year: 1988, pages: 208, read: 208, genre: "վեպ", color: "#b07a1f" },
    { id: 6, title: "Վերք Հայաստանի", author: "Խաչատուր Աբովյան", year: 1858, pages: 320, read: 90, genre: "պատմավեպ", color: "#6b4a7a" }
];

const STATUS_TEXT = { planned: "Կարդալու եմ", reading: "Կարդում եմ", done: "Կարդացել եմ" };

// Ինչ է ընտրված հիմա
let currentFilter = "all";
let query = "";

const container = document.querySelector("#books");
const stats = document.querySelector("#stats");
const filterButtons = document.querySelectorAll("[data-filter]");
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

// ---------- Render ----------
function createBookCard(book) {
    const { id, title, author, year, pages, read, color } = book;
    const status = getStatus(book);
    const percent = Math.round(read / pages * 100);
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
                    <button type="button" class="btn btn--ghost btn--sm" data-details="${id}">Մանրամասն</button>
                </div>
            </div>
        </article>
    `;
}

function render() {
    const list = getVisibleBooks();
    if (list.length === 0) {
        container.innerHTML = `<p class="empty">Այս պայմանով գիրք չկա։</p>`;
    } else {
        container.innerHTML = list.map(createBookCard).join("");
    }
    stats.textContent = `Ցուցադրված է ${list.length} / ${books.length} գիրք`;

    // innerHTML-ը ստեղծեց նոր կոճակներ, ուստի մշակիչները կախում ենք նորից։
    // Դաս 12-ում կտեսնենք ավելի լավ ձևը՝ դելեգացիա
    bindDetailsButtons();
}

// ---------- Մոդալ ----------
function openModal(book) {
    const { title, author, year, pages, read, genre } = book;
    modalContent.innerHTML = `
        <h2 id="book-modal-title">${title}</h2>
        <p class="meta">${author} · ${year} · ${genre}</p>
        <p>Կարդացել եմ ${read} էջ ${pages}-ից։ Մնաց ${pages - read} էջ։</p>
    `;
    modal.classList.add("is-open");
    document.body.classList.add("no-scroll");
}

function closeModal() {
    modal.classList.remove("is-open");
    document.body.classList.remove("no-scroll");
}

function bindDetailsButtons() {
    container.querySelectorAll("[data-details]").forEach(button => {
        button.addEventListener("click", () => {
            const id = Number(button.dataset.details);
            openModal(books.find(b => b.id === id));
        });
    });
}

// ---------- Իրադարձություններ ----------
// «Հանել բոլորից → ավելացնել ընտրվածին»
filterButtons.forEach(button => {
    button.addEventListener("click", () => {
        currentFilter = button.dataset.filter;
        filterButtons.forEach(b => b.classList.remove("is-active"));
        button.classList.add("is-active");
        render();
    });
});

// input-ը աշխատում է ամեն տառի հետ
searchInput.addEventListener("input", () => {
    query = searchInput.value.trim().toLowerCase();
    render();
});

// Մոդալը փակվում է երեք եղանակով
modal.addEventListener("click", (e) => {
    if (e.target === modal || e.target.closest('[data-action="close-modal"]')) {
        closeModal();
    }
});

document.addEventListener("keydown", (e) => {
    if (e.key === "Escape" && modal.classList.contains("is-open")) {
        closeModal();
    }
});

render();
