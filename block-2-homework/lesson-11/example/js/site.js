// Ամբողջ կայքի համար՝ թեմա և բուրգեր — Դաս 11
// Ամեն էջում միացված է, որովհետև header-ը ամեն էջում է

// ---------- Մուգ/բաց թեմա ----------
const themeBtn = document.querySelector('[data-action="theme"]');

themeBtn.addEventListener("click", () => {
    document.body.classList.toggle("dark");
    const isDark = document.body.classList.contains("dark");
    themeBtn.textContent = isDark ? "☀" : "☾";
    themeBtn.setAttribute("aria-label", isDark ? "Բաց թեմա" : "Մուգ թեմա");
});

// ---------- Բուրգեր ----------
// HTML/CSS բլոկի բուրգերը checkbox է։ JS-ը ավելացնում է այն, ինչ CSS-ը չէր կարող․
// փակել մենյուն հղման սեղմելիս և Escape-ով
const navToggle = document.querySelector(".nav-toggle");
const menuLinks = document.querySelectorAll(".menu a");

menuLinks.forEach(link => {
    link.addEventListener("click", () => {
        navToggle.checked = false;
    });
});

document.addEventListener("keydown", (e) => {
    if (e.key === "Escape") {
        navToggle.checked = false;
    }
});
