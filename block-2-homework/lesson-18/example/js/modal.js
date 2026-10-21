// Մոդալ պատուհան — Դաս 11, 18
const modal = document.querySelector("#book-modal");
const content = document.querySelector("#book-modal-content");

export function openModal(html) {
    content.innerHTML = html;
    modal.classList.add("is-open");
    document.body.classList.add("no-scroll");
}

function closeModal() {
    modal.classList.remove("is-open");
    document.body.classList.remove("no-scroll");
}

export function initModal() {
    modal.addEventListener("click", (e) => {
        if (e.target === modal || e.target.closest('[data-action="close-modal"]')) closeModal();
    });
    document.addEventListener("keydown", (e) => {
        if (e.key === "Escape" && modal.classList.contains("is-open")) closeModal();
    });
}
