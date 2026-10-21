// Ամբողջ կայքի UI՝ թեմա, բուրգեր, «Վեր», toast — Դաս 11, 14, 18
import { save, load } from "./storage.js";

const THEME_KEY = "ani:theme";
const SCROLL_THRESHOLD = 400;
const TOAST_DURATION = 3000;

export function initTheme() {
    const button = document.querySelector('[data-action="theme"]');
    if (!button) return;

    function apply(theme) {
        const isDark = theme === "dark";
        document.body.classList.toggle("dark", isDark);
        button.textContent = isDark ? "☀" : "☾";
        button.setAttribute("aria-label", isDark ? "Բաց թեմա" : "Մուգ թեմա");
    }

    apply(load(THEME_KEY, "light"));
    button.addEventListener("click", () => {
        const next = document.body.classList.contains("dark") ? "light" : "dark";
        apply(next);
        save(THEME_KEY, next);
    });
}

export function initBurger() {
    const toggle = document.querySelector(".nav-toggle");
    document.querySelector(".menu").addEventListener("click", (e) => {
        if (e.target.closest("a")) toggle.checked = false;     // դելեգացիա՝ մեկ listener
    });
    document.addEventListener("keydown", (e) => {
        if (e.key === "Escape") toggle.checked = false;
    });
}

export function initToTop() {
    const button = document.querySelector(".to-top");
    if (!button) return;
    window.addEventListener("scroll", () => {
        button.classList.toggle("is-visible", window.scrollY > SCROLL_THRESHOLD);
    });
    button.addEventListener("click", (e) => {
        e.preventDefault();
        window.scrollTo({ top: 0, behavior: "smooth" });
    });
}

let toastTimer;
export function showToast(message) {
    let toast = document.querySelector(".toast");
    if (!toast) {
        toast = document.createElement("div");
        toast.className = "toast";
        toast.setAttribute("role", "status");
        document.body.append(toast);
    }
    toast.textContent = message;
    toast.classList.add("is-visible");
    clearTimeout(toastTimer);
    toastTimer = setTimeout(() => toast.classList.remove("is-visible"), TOAST_DURATION);
}
