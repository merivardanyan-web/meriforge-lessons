// Անիի գրադարանը · Դաս 10
// Քարտերը ստեղծում ենք զանգվածից․ createBookCard → render → updateStats

const books = [
    { id: 1, title: "Փոքրիկ իշխանը", author: "Անտուան դը Սենտ-Էքզյուպերի", year: 1943, pages: 96, read: 96, genre: "հեքիաթ", color: "#2f5d8a" },
    { id: 2, title: "Սամվել", author: "Րաֆֆի", year: 1886, pages: 560, read: 140, genre: "պատմավեպ", color: "#8a3b2f" },
    { id: 3, title: "Գիքորը", author: "Հովհաննես Թումանյան", year: 1907, pages: 40, read: 40, genre: "պատմվածք", color: "#5b7a3a" },
    { id: 4, title: "1984", author: "Ջորջ Օրուել", year: 1949, pages: 328, read: 0, genre: "վեպ", color: "#3d3d55" },
    { id: 5, title: "Ալքիմիկոսը", author: "Պաուլո Կոելյո", year: 1988, pages: 208, read: 208, genre: "վեպ", color: "#b07a1f" },
    { id: 6, title: "Վերք Հայաստանի", author: "Խաչատուր Աբովյան", year: 1858, pages: 320, read: 90, genre: "պատմավեպ", color: "#6b4a7a" }
];

const container = document.querySelector("#books");
const stats = document.querySelector("#stats");

// ---------- Օգնականներ ----------
function getStatus(book) {
    if (book.read === 0) return "planned";
    if (book.read < book.pages) return "reading";
    return "done";
}

const STATUS_TEXT = { planned: "Կարդալու եմ", reading: "Կարդում եմ", done: "Կարդացել եմ" };

// ---------- Մեկ քարտի HTML-ը ----------
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
            </div>
        </article>
    `;
}

// ---------- Ամբողջ ցուցակը ----------
function render(list) {
    if (list.length === 0) {
        renderEmpty();
        return;
    }
    container.innerHTML = list.map(createBookCard).join("");
    updateStats(list);
}

function renderEmpty() {
    container.innerHTML = `<p class="empty">Այս պայմանով գիրք չկա։</p>`;
    stats.textContent = "0 գիրք";
}

// Վիճակագրություն — Դաս 6
function updateStats(list) {
    const done = list.filter(b => getStatus(b) === "done").length;
    const pagesRead = list.reduce((sum, b) => sum + b.read, 0);
    stats.textContent = `${list.length} գիրք · ${done} կարդացած · ${pagesRead} էջ`;
}

// ---------- Ժանրերի ցուցակ՝ createElement-ով (համեմատության համար) ----------
function renderGenres() {
    const genres = [];
    books.forEach(b => {
        if (!genres.includes(b.genre)) genres.push(b.genre);
    });
    const list = document.createElement("ul");
    list.className = "genres mb-20";
    genres.forEach(genre => {
        const item = document.createElement("li");
        item.className = "pill";
        item.textContent = genre;              // textContent՝ անվտանգ
        list.append(item);
    });
    container.before(list);                    // ցուցակից առաջ
}

// ---------- Գործարկում ----------
// Նախ՝ կարդում եմ հիմա, հետո՝ կարդացածները, վերջում՝ պլանները
const ORDER = { reading: 1, done: 2, planned: 3 };
render([...books].sort((a, b) => ORDER[getStatus(a)] - ORDER[getStatus(b)]));
renderGenres();

// Փորձիր Console-ում․
// render(books.filter(b => b.genre === "վեպ"));
// render(books.filter(b => b.pages > 1000));     ← դատարկ վիճակ
// render([...books].sort((a, b) => b.pages - a.pages));
