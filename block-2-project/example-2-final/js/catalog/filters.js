// Կատալոգի pipeline՝ մաքուր ֆունկցիաներ առանց DOM-ի (JS Դաս 15)։
// Ստանում են տվյալներ, վերադարձնում են նոր զանգված, ոչինչ չեն փոխում (JS Դաս 23)։

import { CATEGORIES } from "../config.js";

const SORTERS = {
    "price-asc": (a, b) => a.price - b.price,
    "price-desc": (a, b) => b.price - a.price,
    rating: (a, b) => b.rating - a.rating,
    new: (a, b) => b.added.localeCompare(a.added),
    popular: (a, b) => b.popularity - a.popularity
};

export function getVisibleProducts(products, state, wishlist) {
    const query = state.search.toLowerCase();

    const result = products
        .filter(p => state.categories.length === 0 || state.categories.includes(p.category))
        .filter(p => state.brands.length === 0 || state.brands.includes(p.brand))
        .filter(p => state.min === null || p.price >= state.min)
        .filter(p => state.max === null || p.price <= state.max)
        .filter(p => state.stock !== "in" || p.inStock)
        .filter(p => state.stock !== "sale" || p.oldPrice !== null)
        .filter(p => state.stock !== "fav" || wishlist.has(p.id))
        .filter(p => query === "" ||
            p.name.toLowerCase().includes(query) ||
            CATEGORIES[p.category].toLowerCase().includes(query));

    return [...result].sort(SORTERS[state.sort] ?? SORTERS.popular);
}

// Քանի՞ ապրանք ամեն կատեգորիայում/ապրանքանիշում — Map (JS Դաս 23)
export function countBy(products, key) {
    const counts = new Map();
    products.forEach(p => counts.set(p[key], (counts.get(p[key]) ?? 0) + 1));
    return counts;
}
