// «Ցույց տալ» կոճակ գաղտնաբառի դաշտում — JS Դաս 13 (տնային)
export function initPasswordToggles(form) {
    form.addEventListener("click", (e) => {
        const button = e.target.closest('[data-action="toggle-password"]');
        if (!button) return;
        const input = form.querySelector(`#${button.getAttribute("aria-controls")}`);
        const isHidden = input.type === "password";
        input.type = isHidden ? "text" : "password";
        button.textContent = isHidden ? "Թաքցնել" : "Ցույց տալ";
    });
}
