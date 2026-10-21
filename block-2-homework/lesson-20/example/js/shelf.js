// Դարակի վիճակը և գործողությունները — Դաս 12, 14, 18
// books-ը արտահանված չէ․ դրսից կարելի է միայն կարդալ (getBooks) և փոխել ֆունկցիաներով

import { save, load } from "./storage.js";
import { BOOKS_KEY, INITIAL_BOOKS, COLORS, PAGES_STEP } from "./data.js";

let books = load(BOOKS_KEY, INITIAL_BOOKS);
const view = { filter: "all", query: "" };

function persist() {
    save(BOOKS_KEY, books);
}

const findBook = id => books.find(b => b.id === id);

export function getStatus(book) {
    if (book.read === 0) return "planned";
    if (book.read < book.pages) return "reading";
    return "done";
}

export const getBooks = () => books;
export const getBook = id => findBook(id);

export function getVisibleBooks() {
    return books
        .filter(b => view.filter === "all" || getStatus(b) === view.filter)
        .filter(b => (b.title + " " + b.author).toLowerCase().includes(view.query));
}

export function setFilter(filter) {
    view.filter = filter;
}

export function setQuery(query) {
    view.query = query.trim().toLowerCase();
}

export function addBook({ title, author, pages, genre, read }) {
    books.push({ id: Date.now(), title, author, year: new Date().getFullYear(), pages, read, genre, rating: 0, color: COLORS[books.length % COLORS.length] });
    persist();
}

export function readPages(id) {
    const book = findBook(id);
    if (book) book.read = Math.min(book.read + PAGES_STEP, book.pages);
    persist();
}

export function markDone(id) {
    const book = findBook(id);
    if (book) book.read = book.pages;
    persist();
}

export function rateBook(id, rating) {
    const book = findBook(id);
    if (book) book.rating = rating;
    persist();
}

export function deleteBook(id) {
    books = books.filter(b => b.id !== id);
    persist();
}

export function clearDone() {
    books = books.filter(b => getStatus(b) !== "done");
    persist();
}
