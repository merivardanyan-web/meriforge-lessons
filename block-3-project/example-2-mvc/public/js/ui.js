// Ընդհանուր փոքր գործիքներ — JS Դաս 14, 16

let toastEl = null;
let timer;

export function showToast(message) {
    if (!toastEl) {
        toastEl = document.createElement("div");
        toastEl.className = "toast";
        toastEl.setAttribute("aria-hidden", "true");      // կարդում է #cart-status-ը, ոչ toast-ը
        document.body.append(toastEl);
    }
    toastEl.textContent = message;
    toastEl.classList.add("is-visible");
    clearTimeout(timer);
    timer = setTimeout(() => toastEl.classList.remove("is-visible"), 2500);
}

/** Էկրանընթերցիչի համար — role="status" (PHP Դաս 17) */
export function announce(message) {
    const status = document.querySelector("#cart-status");
    if (status) status.textContent = message;
}

export function debounce(fn, delay = 300) {
    let timer;
    return (...args) => {
        clearTimeout(timer);
        timer = setTimeout(() => fn(...args), delay);
    };
}

export function formatPrice(amount) {
    // Chrome-ը hy-AM-ի համար տալիս է «315,000», ուստի բացատով տարբերակը՝ ru-RU
    return `${amount.toLocaleString("ru-RU")} ֏`;
}

/** Տեքստը HTML-ի մեջ դնելուց առաջ — նույնը, ինչ PHP-ի e()-ը */
export function escapeHtml(text) {
    return String(text ?? "").replace(/[&<>"']/g, (ch) => ({ "&": "&amp;", "<": "&lt;", ">": "&gt;", '"': "&quot;", "'": "&#39;" }[ch]));
}
