// Հարցումներ մեր API-ին — PHP Դաս 17, JS Դաս 20
// Բլոկ 2-ի api.js-ի նույն գաղափարը․ մնացած կոդը չգիտի, թե ինչպես է fetch-ը աշխատում

export const csrf = document.querySelector('meta[name="csrf-token"]')?.content ?? "";

export class ApiError extends Error {
    constructor(message, status) {
        super(message);
        this.name = "ApiError";
        this.status = status;
    }
}

async function request(url, options = {}) {
    const response = await fetch(url, {
        ...options,
        headers: {
            "Accept": "application/json",
            "X-CSRF-Token": csrf,                 // ⭐ առանց սրա POST-ը և DELETE-ը կստանան 419
            ...(options.body ? { "Content-Type": "application/json" } : {}),
        },
    });

    const payload = await response.json();

    // fetch-ը 4xx/5xx-ի դեպքում ՉԻ գցում սխալ․ ok-ը ստուգում ենք ինքներս
    if (!response.ok) {
        throw new ApiError(payload.error?.message ?? "Սխալ", response.status);
    }

    return payload;
}

export const api = {
    searchBooks(params, signal) {
        return request(`/api/books?${new URLSearchParams(params)}`, { signal });
    },
    addToList(id) {
        return request("/api/list/items", { method: "POST", body: JSON.stringify({ id }) });
    },
};
