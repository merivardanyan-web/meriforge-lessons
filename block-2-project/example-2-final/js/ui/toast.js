// Կարճ ծանուցում — JS Դաս 14
import { TOAST_DURATION } from "../config.js";

let toastEl = null;
let timer;

export function showToast(message) {
    if (!toastEl) {
        toastEl = document.createElement("div");
        toastEl.className = "toast";
        toastEl.setAttribute("role", "status");
        document.body.append(toastEl);
    }
    toastEl.textContent = message;
    toastEl.classList.add("is-visible");
    clearTimeout(timer);
    timer = setTimeout(() => toastEl.classList.remove("is-visible"), TOAST_DURATION);
}
