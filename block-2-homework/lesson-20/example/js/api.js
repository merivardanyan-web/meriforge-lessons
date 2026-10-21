// Իսկական հարցումներ fetch-ով — Դաս 20
// Արտաքինից ամեն ինչ նույնն է, ինչ Դաս 19-ի fake-api.js-ում․ ֆունկցիաները վերադարձնում են Promise

const RECOMMENDED_URL = "../data/recommended.json";        // էջից՝ pages/books.html
const SUGGESTIONS_URL = "https://jsonplaceholder.typicode.com/posts";

async function request(url, options) {
    const response = await fetch(url, options);
    if (!response.ok) {                                      // 404-ը catch չի ընկնում ինքնաբերաբար
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
