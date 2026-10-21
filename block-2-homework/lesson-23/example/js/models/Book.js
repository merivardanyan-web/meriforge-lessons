// Գիրքը որպես դաս — Դաս 22
// #read-ը փակ է․ փոխել կարելի է միայն setter-ով, որը ստուգում է արժեքը

const STATUS_TEXT = { planned: "Կարդալու եմ", reading: "Կարդում եմ", done: "Կարդացել եմ" };

export class Book {
    #read = 0;

    constructor(data) {
        if (!data.title) throw new Error("Գրքի վերնագիրը պարտադիր է");
        if (!(data.pages > 0)) throw new Error("Էջերի քանակը պիտի լինի դրական");

        this.id = data.id ?? Date.now();
        this.title = data.title;
        this.author = data.author;
        this.year = data.year ?? new Date().getFullYear();
        this.pages = data.pages;
        this.genre = data.genre;
        this.rating = data.rating ?? 0;
        this.color = data.color;
        this.read = data.read ?? 0;              // ← անցնում է setter-ով
    }

    // ---------- get և set ----------
    get read() {
        return this.#read;
    }

    set read(value) {
        if (value < 0 || value > this.pages) {
            throw new Error(`Էջը պիտի լինի 0-ից ${this.pages}`);
        }
        this.#read = value;
    }

    get percent() {
        return Math.round(this.#read / this.pages * 100);
    }

    get status() {
        if (this.#read === 0) return "planned";
        if (this.#read < this.pages) return "reading";
        return "done";
    }

    get statusText() {
        return STATUS_TEXT[this.status];
    }

    get label() {
        return `${this.author} · ${this.year}`;
    }

    // ---------- Գործողություններ․ return this՝ շղթայի համար ----------
    readPages(count) {
        this.read = Math.min(this.#read + count, this.pages);
        return this;
    }

    finish() {
        this.read = this.pages;
        return this;
    }

    rate(stars) {
        this.rating = stars;
        return this;
    }

    // Պահպանելու համար՝ սովորական օբյեկտ (JSON-ը փակ դաշտերը չի տեսնում)
    toData() {
        const { id, title, author, year, pages, genre, rating, color } = this;
        return { id, title, author, year, pages, read: this.#read, genre, rating, color };
    }

    // Օբյեկտ ստեղծելու այլընտրանքային ձև
    static fromData(data) {
        return data.type === "audio" ? new AudioBook(data) : new Book(data);
    }
}

// Ժառանգում՝ աուդիոգիրքը նույն գիրքն է, պլյուս տևողություն
export class AudioBook extends Book {
    constructor(data) {
        super(data);                             // պարտադիր՝ առաջին տողում
        this.minutes = data.minutes;
    }

    get label() {
        const hours = Math.floor(this.minutes / 60);
        return `${super.label} · 🎧 ${hours} ժ ${this.minutes % 60} ր`;
    }

    toData() {
        return { ...super.toData(), type: "audio", minutes: this.minutes };
    }
}
