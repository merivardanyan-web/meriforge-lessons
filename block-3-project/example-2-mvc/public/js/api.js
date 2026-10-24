// Հարցումներ մեր API-ին — PHP Դաս 17, JS Դաս 20
// Բլոկ 2-ի api.js-ի նույն գաղափարը․ մնացած կոդը չգիտի, թե ինչպես է fetch-ը աշխատում

const csrf = document.querySelector('meta[name="csrf-token"]')?.content ?? "";

export class ApiError extends Error {
    constructor(message, status, errors = {}) {
        super(message);
        this.name = "ApiError";
        this.status = status;
        this.errors = errors;       // 422-ի դեպքում՝ { qty: "Քանակը՝ 1-ից 10" }
    }
}

async function request(url, options = {}) {
    const response = await fetch(url, {
        ...options,
        headers: {
            "Accept": "application/json",
            "X-CSRF-Token": csrf,          // ⭐ առանց սրա POST-ը կստանա 419
            ...(options.body ? { "Content-Type": "application/json" } : {}),
        },
    });

    const payload = await response.json();

    // fetch-ը 4xx/5xx-ի դեպքում ՉԻ գցում սխալ — պիտի ստուգել ok-ը ⭐
    if (!response.ok) {
        throw new ApiError(payload.error?.message ?? "Սխալ", response.status, payload.error?.errors);
    }

    return payload;
}

export const api = {
    searchProducts(params, signal) {
        return request(`/api/products?${new URLSearchParams(params)}`, { signal });
    },
    addToCart(id, qty = 1) {
        return request("/api/cart/items", { method: "POST", body: JSON.stringify({ id, qty }) });
    },
};

export { csrf };
