// «+ Կարդալու ցուցակ»՝ առանց էջի վերբեռնման — PHP Դաս 17
// HTML-ը նույնն է․ <form data-list-add>։ Եթե JS-ը կամ API-ն չաշխատեն՝ ֆորման կուղարկվի սովորական ձևով

import { api, ApiError } from "./api.js";
import { showToast } from "./ui.js";

function updateCount(count) {
    document.querySelectorAll("[data-list-count]").forEach((el) => {
        el.textContent = count;
        el.classList.toggle("hidden", count === 0);
    });
}

export function initList() {
    // Դելեգացիա document-ի վրա․ որոնման արդյունքների նոր քարտերը նույնպես աշխատում են — JS Դաս 12
    document.addEventListener("submit", async (event) => {
        const form = event.target.closest("form[data-list-add]");
        if (!form) return;

        event.preventDefault();
        const button = form.querySelector('button[type="submit"]');
        button.disabled = true;

        try {
            const { data } = await api.addToList(Number(form.elements.id.value));
            updateCount(data.count);
            showToast(data.message);
            button.textContent = "✓ Ցուցակում է";
            button.classList.replace("btn--outline", "btn--ghost");
        } catch (error) {
            if (error instanceof ApiError && error.status === 422) {
                showToast(error.message);
                button.disabled = false;
            } else {
                form.submit();          // ⭐ ամեն այլ դեպքում (419, ցանց)՝ սովորական ֆորման
            }
        }
    });
}
