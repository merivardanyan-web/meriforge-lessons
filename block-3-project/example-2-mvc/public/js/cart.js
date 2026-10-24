// «Գնել»՝ առանց էջի վերբեռնման — PHP Դաս 17
// HTML-ը նույնն է․ <form data-cart-add>։ Եթե JS-ը կամ API-ն չաշխատեն՝ ֆորման կուղարկվի սովորական ձևով

import { api, ApiError } from "./api.js";
import { showToast, announce } from "./ui.js";

function updateCount(count) {
    document.querySelectorAll("[data-cart-count]").forEach((el) => {
        el.textContent = count;
        el.classList.toggle("is-empty", count === 0);
    });
}

export function initCart() {
    // Դելեգացիա document-ի վրա․ որոնման արդյունքների նոր քարտերը նույնպես աշխատում են — JS Դաս 12
    document.addEventListener("submit", async (event) => {
        const form = event.target.closest("form[data-cart-add]");
        if (!form) return;

        event.preventDefault();
        const button = form.querySelector('button[type="submit"]');
        button.disabled = true;

        try {
            const qty = Number(form.elements.qty?.value ?? 1);
            const data = await api.addToCart(Number(form.elements.id.value), qty);
            updateCount(data.data.count);
            showToast(data.data.message);
            announce(data.data.message);
        } catch (error) {
            if (error instanceof ApiError && error.status === 422) {
                // Սերվերի սխալը՝ օգտագործողին, ոչ Console-ին
                const message = Object.values(error.errors).join(" ") || error.message;
                showToast(message);
                announce(message);
            } else {
                form.submit();        // ⭐ ամեն այլ դեպքում՝ սովորական ֆորման (419, ցանց...)
            }
        } finally {
            button.disabled = false;
        }
    });
}
