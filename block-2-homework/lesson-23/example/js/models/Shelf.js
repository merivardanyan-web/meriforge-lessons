// Դարակը որպես դաս — Դաս 22, Set/Map և անփոփոխ թարմացումներ՝ Դաս 23
// Պահպանումը ավտոմատ է՝ #save()-ը կանչվում է ամեն փոփոխությունից

import { save, load } from "../storage.js";
import { Book } from "./Book.js";

const COLORS = ["#2f5d8a", "#8a3b2f", "#5b7a3a", "#3d3d55", "#b07a1f", "#6b4a7a"];

export class Shelf {
    #books;
    #storageKey;
    #filter = "all";
    #genre = "all";
    #query = "";

    constructor(storageKey, initialData) {
        this.#storageKey = storageKey;
        this.#books = load(storageKey, initialData).map(Book.fromData);
    }

    // ---------- Կարդալու համար ----------
    get all() {
        return this.#books;
    }

    get visible() {
        return this.#books
            .filter(b => this.#filter === "all" || b.status === this.#filter)    // arrow-ը պահում է this-ը
            .filter(b => this.#genre === "all" || b.genre === this.#genre)
            .filter(b => (b.title + " " + b.author).toLowerCase().includes(this.#query));
    }

    get doneCount() {
        return this.#books.filter(b => b.status === "done").length;
    }

    // Եզակի ժանրերը՝ Set-ով, քանակները՝ Map-ով
    get genres() {
        return [...new Set(this.#books.map(b => b.genre))].sort((a, b) => a.localeCompare(b, "hy"));
    }

    get genreCounts() {
        const counts = new Map();
        this.#books.forEach(b => counts.set(b.genre, (counts.get(b.genre) ?? 0) + 1));
        return counts;
    }

    find(id) {
        return this.#books.find(b => b.id === id);
    }

    has(title) {
        return this.#books.some(b => b.title === title);
    }

    // ---------- Ֆիլտր ----------
    setFilter(filter) {
        this.#filter = filter;
        return this;
    }

    setGenre(genre) {
        this.#genre = genre;
        return this;
    }

    setQuery(query) {
        this.#query = query.trim().toLowerCase();
        return this;
    }

    // ---------- Փոփոխելու համար ----------
    add(data) {
        const book = Book.fromData({ ...data, color: COLORS[this.#books.length % COLORS.length] });
        this.#books = [...this.#books, book];       // նոր զանգված, ոչ push
        this.#save();
        return book;
    }

    update(id, change) {
        const book = this.find(id);
        if (book) change(book);                 // օրինակ՝ b => b.readPages(10)
        this.#save();
        return this;
    }

    remove(id) {
        this.#books = this.#books.filter(b => b.id !== id);
        this.#save();
        return this;
    }

    clearDone() {
        this.#books = this.#books.filter(b => b.status !== "done");
        this.#save();
        return this;
    }

    #save() {
        save(this.#storageKey, this.#books.map(b => b.toData()));
    }
}
