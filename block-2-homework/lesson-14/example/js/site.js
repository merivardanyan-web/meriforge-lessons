// Ամբողջ կայքի համար՝ թեմա, բուրգեր, «Վեր» կոճակ, toast — Դաս 11, 14

const THEME_KEY = "ani:theme";
const SCROLL_THRESHOLD = 400;
const TOAST_DURATION = 3000;

// ---------- Մուգ/բաց թեմա՝ ընտրության պահպանմամբ ----------
const themeBtn = document.querySelector('[data-action="theme"]');

function applyTheme(theme) {
    const isDark = theme === "dark";
    document.body.classList.toggle("dark", isDark);
    themeBtn.textContent = isDark ? "☀" : "☾";
    themeBtn.setAttribute("aria-label", isDark ? "Բաց թեմա" : "Մուգ թեմա");
}

applyTheme(load(THEME_KEY, "light"));          // վերականգնում ենք բեռնման ժամանակ

themeBtn.addEventListener("click", () => {
    const next = document.body.classList.contains("dark") ? "light" : "dark";
    applyTheme(next);
    save(THEME_KEY, next);
});

// ---------- Բուրգեր ----------
const navToggle = document.querySelector(".nav-toggle");

document.querySelectorAll(".menu a").forEach(link => {
    link.addEventListener("click", () => {
        navToggle.checked = false;
    });
});

document.addEventListener("keydown", (e) => {
    if (e.key === "Escape") navToggle.checked = false;
});

// ---------- «Վեր» կոճակը՝ 400px սքրոլից հետո ----------
const toTop = document.querySelector(".to-top");

window.addEventListener("scroll", () => {
    toTop.classList.toggle("is-visible", window.scrollY > SCROLL_THRESHOLD);
});

toTop.addEventListener("click", (e) => {
    e.preventDefault();
    window.scrollTo({ top: 0, behavior: "smooth" });
});

// ---------- Toast ----------
let toastTimer;

function showToast(message) {
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
