// Ընդհանուր օգնականներ — JS Դաս 16, 18, 23

// Chrome-ը չունի «hy-AM» լոկալը և տալիս է «315,000»։ «ru-RU»-ն տալիս է «315 000»
export const formatPrice = n => n.toLocaleString("ru-RU") + " ֏";

export const getDiscountPercent = (price, oldPrice) => Math.round((1 - price / oldPrice) * 100);

// timer-ը ապրում է փակույթում․ ամեն կանչ տեսնում է նախորդի timer-ը (JS Դաս 23)
export function debounce(fn, delay = 300) {
    let timer;
    return function (...args) {
        clearTimeout(timer);
        timer = setTimeout(() => fn(...args), delay);
    };
}

// Զանգվածի մեկ էջը՝ էջավորման համար
export function paginate(list, page, size) {
    const totalPages = Math.max(Math.ceil(list.length / size), 1);
    const current = Math.min(Math.max(page, 1), totalPages);
    const start = (current - 1) * size;
    return { items: list.slice(start, start + size), page: current, totalPages, start };
}
