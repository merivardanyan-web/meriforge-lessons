// Անիի ընթերցանության օրագիրը · Դաս 7
// Գիրքը որպես օբյեկտ՝ ներդրված օբյեկտ, մեթոդներ, this, destructuring, spread

const book = {
    title: "Վերք Հայաստանի",
    author: "Խաչատուր Աբովյան",
    year: 1858,
    pages: 320,
    pagesRead: 90,
    isRead: false,
    genres: ["պատմավեպ", "դասական"],
    publisher: {
        name: "Սովետական գրող",
        city: "Երևան"
    },

    getInfo() {
        return `«${this.title}» — ${this.author}, ${this.year}`;
    },

    read(count) {
        this.pagesRead = Math.min(this.pagesRead + count, this.pages);
        if (this.pagesRead === this.pages) {
            this.markAsRead();
        }
        return this.pagesRead;
    },

    markAsRead() {
        this.isRead = true;
        this.pagesRead = this.pages;
    }
};

// ---------- Կարդալ և փոխել ----------
console.log(book.getInfo());
console.log(`Հրատարակիչ՝ ${book.publisher.name}, ${book.publisher.city}`);
console.log(`Ժանրեր՝ ${book.genres.join(", ")}`);
console.log("Նախքան՝", book.isRead, book.pagesRead);
book.read(100);
console.log("100 էջից հետո՝", book.pagesRead);
book.read(500);
console.log("Ավարտից հետո՝", book.isRead, book.pagesRead);

// ---------- Չեղած դաշտեր՝ ?? և ?. ----------
console.log(`Գինը՝ ${book.price ?? "նշված չէ"}`);
console.log(`Երկիր՝ ${book.publisher?.country ?? "նշված չէ"}`);
console.log(`Թարգմանիչ՝ ${book.translator?.name ?? "չկա"}`);

// ---------- Անցնել բոլոր դաշտերով ----------
// Մեթոդները, զանգվածը և ներդրված օբյեկտը բաց ենք թողնում (զանգվածի typeof-ն էլ "object" է)
for (const key in book) {
    if (typeof book[key] !== "function" && typeof book[key] !== "object") {
        console.log(`  ${key}: ${book[key]}`);
    }
}

// ---------- Destructuring՝ վերանվանումով ----------
const { title, author: writer } = book;
const { city } = book.publisher;
console.log(`${title} · ${writer} · ${city}`);

// ---------- Գնահատականներ ըստ ժանրի ----------
const ratings = { "վեպ": 4.5, "պատմավեպ": 4.8, "հեքիաթ": 5, "պատմվածք": 4.2, "պոեզիա": 3.9 };
const values = Object.values(ratings);
const average = values.reduce((sum, r) => sum + r, 0) / values.length;
const [bestGenre, bestRating] = Object.entries(ratings).sort((a, b) => b[1] - a[1])[0];
console.log("Ժանրեր՝", Object.keys(ratings).join(", "));
console.log(`Միջինը՝ ${average.toFixed(2)}, ամենասիրածը՝ ${bestGenre} (${bestRating})`);

// ---------- Օբյեկտների զանգված ----------
const shelf = [
    { title: "Փոքրիկ իշխանը", genre: "հեքիաթ", pages: 96, isRead: true },
    { title: "Սամվել", genre: "պատմավեպ", pages: 560, isRead: false },
    { title: "Քաոս", genre: "վեպ", pages: 448, isRead: false },
    { title: "Գիքորը", genre: "պատմվածք", pages: 40, isRead: true },
    { title: "Վերք Հայաստանի", genre: "պատմավեպ", pages: 320, isRead: true }
];

const getRead = list => list.filter(b => b.isRead);
const getTotalPages = list => list.reduce((sum, b) => sum + b.pages, 0);
const getLongest = list => [...list].sort((a, b) => b.pages - a.pages)[0];

console.log("Կարդացած՝", getRead(shelf).map(b => b.title));
console.log(`Կարդացած էջեր՝ ${getTotalPages(getRead(shelf))}`);
console.log(`Ամենամեծը՝ ${getLongest(shelf).title}`);

// Մեկ շղթա՝ պատմավեպեր, կարճից երկար
console.log(
    shelf
        .filter(b => b.genre === "պատմավեպ")
        .sort((a, b) => a.pages - b.pages)
        .map(b => `${b.title} — ${b.pages} էջ`)
);

// ---------- Նոր օբյեկտ՝ բնօրինակը անփոփոխ ----------
function updateBook(original, changes) {
    return { ...original, ...changes };
}
const updated = updateBook(shelf[1], { isRead: true });
console.log("Նոր՝", updated.isRead, "· բնօրինակ՝", shelf[1].isRead);
