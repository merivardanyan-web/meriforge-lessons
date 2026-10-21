// Բոլոր հարցումները՝ մեկ ֆայլում (API շերտ) — Դաս 20, 21

const RECOMMENDED_URL = "../data/recommended.json";        // էջից՝ pages/books.html
const SUGGESTIONS_URL = "https://jsonplaceholder.typicode.com/posts";
const SEARCH_URL = "https://openlibrary.org/search.json";
const COVERS_URL = "https://covers.openlibrary.org/b/id";
const SEARCH_FIELDS = "key,title,author_name,first_publish_year,number_of_pages_median,cover_i";

// Հասցե պարամետրերով․ դատարկ արժեքները դուրս են մնում, հայերենը և & -ը կոդավորվում են
export function buildUrl(base, params = {}) {
    const clean = Object.fromEntries(
        Object.entries(params).filter(([, value]) => value !== "" && value !== null && value !== undefined)
    );
    const query = new URLSearchParams(clean).toString();
    return query ? `${base}?${query}` : base;
}

async function request(url, options) {
    const response = await fetch(url, options);
    if (!response.ok) {
        throw new Error(`Սերվերը վերադարձրեց ${response.status}`);
    }
    return response.json();
}

export function getRecommended() {
    return request(RECOMMENDED_URL);
}

export function sendSuggestion(title, body) {
    return request(SUGGESTIONS_URL, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ title, body, userId: 1 })
    });
}

// Open Library-ի պատասխանը՝ մեր ձևով։ Ոչ բոլոր գրքերն ունեն հեղինակ, էջեր կամ շապիկ
function toBook(doc) {
    return {
        title: doc.title,
        author: doc.author_name ? doc.author_name[0] : "Անհայտ հեղինակ",
        year: doc.first_publish_year ?? "—",
        pages: doc.number_of_pages_median ?? null,
        cover: doc.cover_i ? `${COVERS_URL}/${doc.cover_i}-M.jpg` : null
    };
}

export async function searchBooks({ query, page, limit }, signal) {
    const url = buildUrl(SEARCH_URL, { q: query, page, limit, fields: SEARCH_FIELDS });
    const data = await request(url, { signal });
    return { books: data.docs.map(toBook), total: data.numFound };
}
