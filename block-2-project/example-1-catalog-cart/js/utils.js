// Ընդհանուր օգնականներ — JS Դաս 16, ռեֆակտորինգ

const TOAST_DURATION = 3000;

// 315000 → "315 000 ֏" — JS Դաս 8
// Ուշադրություն․ Chrome-ը չունի «hy-AM» լոկալը և դրա փոխարեն տալիս է «315,000»։
// «ru-RU»-ն տալիս է այն ձևը, որ ընդունված է Հայաստանում՝ բացատով։
const formatPrice = n => n.toLocaleString("ru-RU") + " ֏";

// Զեղչի տոկոսը՝ 315000 / 450000 → 30
const getDiscountPercent = (price, oldPrice) => Math.round((1 - price / oldPrice) * 100);

// Կանչում է fn-ը միայն այն ժամանակ, երբ օգտագործողը դադարեց գրել — JS Դաս 14, 16
function debounce(fn, delay = 300) {
    let timer;
    return function (...args) {
        clearTimeout(timer);
        timer = setTimeout(() => fn(...args), delay);
    };
}

// Կարճ ծանուցում էկրանի ներքևում — JS Դաս 14
let toastTimer;

function showToast(message) {
    let toast = document.querySelector(".toast");
    if (!toast) {                                  // առաջին կանչին ստեղծում ենք — JS Դաս 10
        toast = document.createElement("div");
        toast.className = "toast";
        toast.setAttribute("role", "status");
        document.body.append(toast);
    }
    toast.textContent = message;
    toast.classList.add("is-visible");

    clearTimeout(toastTimer);                      // նոր ծանուցումը «երկարացնում» է ժամանակը
    toastTimer = setTimeout(() => toast.classList.remove("is-visible"), TOAST_DURATION);
}
