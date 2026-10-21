// API շերտ — JS Դաս 20, 21։ Բոլոր հարցումները անցնում են այստեղով։
// Մեր «սերվերը» data/products.json ֆայլն է․ PHP բլոկում այն կփոխարինվի իսկական API-ով,
// և կփոխվի միայն այս ֆայլը։ Մնացած կոդը նույնիսկ չի իմանա։

import { PRODUCTS_URL } from "./config.js";
import { ApiError, NotFoundError } from "./errors.js";

// Քեշում պահում ենք ոչ թե պատասխանը, այլ խոստումը (Promise, JS Դաս 19)։
// Եթե էջի երկու մաս միաժամանակ ուզեն ապրանքները, հարցումը կգնա մեկ անգամ։
const cache = new Map();

async function fetchJson(url) {
    const response = await fetch(url);
    if (!response.ok) {                              // 404-ը catch չի ընկնում ինքնաբերաբար (JS Դաս 20)
        throw new ApiError(response.status, `Հարցումը ձախողվեց՝ ${url}`);
    }
    return response.json();
}

function request(url) {
    if (!cache.has(url)) {
        const promise = fetchJson(url).catch(error => {
            cache.delete(url);                       // ձախողվածը չենք պահում՝ «Կրկին փորձել»-ը նորից կհարցնի
            throw error;
        });
        cache.set(url, promise);
    }
    return cache.get(url);
}

export const api = {
    getProducts() {
        return request(PRODUCTS_URL);
    },

    async getProduct(id) {
        const products = await request(PRODUCTS_URL);
        const product = products.find(p => p.id === id);
        if (!product) throw new NotFoundError("Այդպիսի ապրանք չկա։");
        return product;
    }
};
