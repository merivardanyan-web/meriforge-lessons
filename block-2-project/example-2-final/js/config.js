// Նախագծի կարգավորումներ — մեկ տեղում, առանց «կախարդական թվերի» (JS Դաս 16)
// Object.freeze — ոչ ոք չի կարող պատահաբար փոխել (JS Դաս 23)

export const CATEGORIES = Object.freeze({
    laptops: "Նոութբուքեր",
    monitors: "Մոնիտորներ",
    phones: "Սմարթֆոններ",
    tablets: "Պլանշետներ",
    accessories: "Աքսեսուարներ"
});

export const BRANDS = Object.freeze({
    arev: "Արև",
    masis: "Մասիս",
    sevan: "Սևան",
    lori: "Լոռի"
});

export const KEYS = Object.freeze({
    cart: "tm:cart",
    wishlist: "tm:wishlist",
    theme: "tm:theme",
    order: "tm:last-order"
});

export const PRODUCTS_URL = "data/products.json";
export const PAGE_SIZE = 6;
export const SEARCH_DELAY = 300;
export const TOAST_DURATION = 3000;
export const MAX_QTY = 10;
export const FREE_DELIVERY_FROM = 50000;
export const DELIVERY_PRICE = 2000;
