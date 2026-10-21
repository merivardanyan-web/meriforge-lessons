// Header՝ զամբյուղի թիվը և բուրգեր մենյուն — JS Դաս 10, 11

import { cart } from "../store.js";

function renderCartCount() {
    const count = cart.count;
    document.querySelectorAll("[data-cart-count]").forEach(el => {
        el.textContent = count;
        el.classList.toggle("is-empty", count === 0);
    });
}

// HTML/CSS բլոկի բուրգերը checkbox է։ JS-ը ավելացնում է այն, ինչ CSS-ը չէր կարող․
// փակել Escape-ով և ցանկացած հղման սեղմելիս
function initBurger() {
    const toggle = document.querySelector(".nav-toggle");
    const nav = document.querySelector(".header nav");
    if (!toggle || !nav) return;

    document.addEventListener("keydown", (e) => {
        if (e.key === "Escape" && toggle.checked) {
            toggle.checked = false;
            toggle.focus();                      // ֆոկուսը վերադարձնում ենք մենյուի կոճակին
        }
    });

    nav.addEventListener("click", (e) => {
        if (e.target.closest("a")) toggle.checked = false;
    });
}

export function initHeader() {
    renderCartCount();
    cart.onChange(renderCartCount);          // ամեն փոփոխությունից հետո՝ ինքնաբերաբար
    initBurger();
}
