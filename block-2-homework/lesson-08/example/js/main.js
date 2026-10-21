// Անիի ընթերցանության օրագիրը · Դաս 8
// Վերնագրերի ձևավորում, որոնում, ամսաթվեր, տարվա նպատակ

const DAY = 1000 * 60 * 60 * 24;
const YEAR_GOAL = 12;                       // գիրք տարվա ընթացքում

const books = [
    { title: "Փոքրիկ իշխանը", pages: 96, started: "2026-01-05", finished: "2026-01-09" },
    { title: "Ալքիմիկոսը", pages: 208, started: "2026-02-01", finished: "2026-02-14" },
    { title: "Գիքորը", pages: 40, started: "2026-03-20", finished: "2026-03-21" },
    { title: "Վերք Հայաստանի", pages: 320, started: "2026-04-02", finished: "2026-05-30" },
    { title: "1984", pages: 328, started: "2026-09-10", finished: null }
];

// ---------- Տողեր ----------
function capitalize(word) {
    return word.charAt(0).toUpperCase() + word.slice(1).toLowerCase();
}

function capitalizeWords(text) {
    return text.trim().split(" ").map(capitalize).join(" ");
}

function searchBooks(query) {
    const q = query.trim().toLowerCase();
    return books.filter(b => b.title.toLowerCase().includes(q)).map(b => b.title);
}

// Chrome-ը չունի «hy-AM» լոկալը, իսկ «ru-RU»-ն տալիս է Հայաստանում ընդունված ձևը՝ «1 000»
const formatNumber = n => n.toLocaleString("ru-RU");

// ---------- Ամսաթվեր ----------
const pad = n => String(n).padStart(2, "0");

function formatDate(dateString) {
    const date = new Date(dateString);
    return `${pad(date.getDate())}.${pad(date.getMonth() + 1)}.${date.getFullYear()}`;
}

function getDaysBetween(from, to) {
    return Math.round((new Date(to) - new Date(from)) / DAY) + 1;   // առաջին օրն էլ հաշվում ենք
}

function getDaysUntil(dateString) {
    const diff = new Date(dateString) - new Date();
    if (diff <= 0) return "Այդ օրն արդեն անցել է";
    return `${Math.ceil(diff / DAY)} օր`;
}

// Օգտագործողը գրել է փոքրատառով և բացատներով․ ուղղում ենք
console.log(capitalize("  ՔԱՈՍ ".trim()));
console.log(capitalizeWords("  հովհաննես թումանյան "));

// ---------- Օրագիր ----------
console.log("--- Կարդացած գրքեր ---");
const finished = books.filter(b => b.finished !== null);
finished.forEach(b => {
    const days = getDaysBetween(b.started, b.finished);
    const perDay = (b.pages / days).toFixed(1);
    console.log(`${b.title} · ${formatDate(b.started)}–${formatDate(b.finished)} · ${days} օր · ${perDay} էջ/օր`);
});

const current = books.find(b => b.finished === null);
console.log(`Հիմա կարդում եմ՝ ${current.title}, սկսել եմ ${getDaysBetween(current.started, new Date())} օր առաջ`);

// ---------- Որոնում ----------
console.log("Որոնում «  ԻՇԽԱՆ »՝", searchBooks("  ԻՇԽԱՆ "));
console.log("Որոնում «հայ»՝", searchBooks("հայ"));

// ---------- Տարվա նպատակ ----------
const totalPages = finished.reduce((sum, b) => sum + b.pages, 0);
const MINUTES_PER_PAGE = 2;
console.log(`Այս տարի՝ ${finished.length} / ${YEAR_GOAL} գիրք, ${formatNumber(totalPages * MINUTES_PER_PAGE)} րոպե ընթերցանություն`);
console.log(`Մինչև տարեվերջ՝ ${getDaysUntil("2026-12-31")}`);
const left = YEAR_GOAL - finished.length;
const monthsLeft = 12 - new Date().getMonth();          // getMonth()-ը 0-ից է՝ հոկտեմբերը 9
console.log(`Մնացել է ${left} գիրք ${monthsLeft} ամսում, այսինքն ամսական ${Math.ceil(left / monthsLeft)}`);

// ---------- Օրվա պատահական գիրք ----------
const randomBook = books[Math.floor(Math.random() * books.length)];
console.log(`Այսօր կրկին կարդա՝ ${randomBook.title} (պատահական)`);
