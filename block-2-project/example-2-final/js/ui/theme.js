// Մուգ/բաց թեմա ընտրության պահպանմամբ — JS Դաս 11, 14
// Նույն գաղափարը, ինչ դասերի էջերի lesson.js-ում․ եթե ընտրություն չկա, հետևում ենք համակարգին

import { KEYS } from "../config.js";

const root = document.documentElement;

function getTheme() {
    const chosen = root.getAttribute("data-theme");
    if (chosen) return chosen;
    return window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
}

function updateButton(button, theme) {
    const isDark = theme === "dark";
    button.textContent = isDark ? "☀" : "☾";
    button.setAttribute("aria-label", isDark ? "Բաց թեմա" : "Մուգ թեմա");
}

export function initTheme() {
    const button = document.querySelector('[data-action="theme"]');
    if (!button) return;
    updateButton(button, getTheme());

    button.addEventListener("click", () => {
        const next = getTheme() === "dark" ? "light" : "dark";
        root.setAttribute("data-theme", next);
        updateButton(button, next);
        try {
            localStorage.setItem(KEYS.theme, next);    // տող է, JSON պետք չէ
        } catch (e) {
            console.warn("Թեման չպահպանվեց", e);
        }
    });
}
