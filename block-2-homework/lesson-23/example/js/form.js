// Նոր գրքի ֆորմա — Դաս 13, վերաշարադրված ValidationError-ով (Դաս 23)
// Կանոնը նետում է ValidationError, իսկ validateField-ը catch-ում տարբերում է այն ծրագրի սխալից

import { ValidationError } from "./errors.js";

const form = document.querySelector("#book-form");
const MAX_PAGES = 3000;
const MIN_TITLE = 2;

const getStatusValue = () => form.querySelector('input[name="status"]:checked').value;

const rules = {
    title(value) {
        if (value.trim() === "") throw new ValidationError("title", "Վերնագիրը պարտադիր է");
        if (value.trim().length < MIN_TITLE) throw new ValidationError("title", `Առնվազն ${MIN_TITLE} նիշ`);
    },
    author(value) {
        if (value.trim() === "") throw new ValidationError("author", "Հեղինակը պարտադիր է");
    },
    pages(value) {
        const n = Number(value);
        if (value.trim() === "") throw new ValidationError("pages", "Գրիր էջերի քանակը");
        if (!Number.isInteger(n) || n < 1 || n > MAX_PAGES) throw new ValidationError("pages", `1-ից ${MAX_PAGES}`);
    },
    read(value) {
        if (getStatusValue() !== "reading") return;
        const n = Number(value);
        const pages = Number(form.querySelector("#pages").value);
        if (!Number.isInteger(n) || n < 1) throw new ValidationError("read", "Քանի՞ էջ ես արդեն կարդացել");
        if (pages && n >= pages) throw new ValidationError("read", `Պիտի լինի ${pages}-ից քիչ, թե չէ գիրքը կարդացած է`);
    }
};

function setError(input, message) {
    const field = input.closest(".field");
    field.classList.toggle("has-error", message !== "");
    field.querySelector(".field__error").textContent = message;
}

function validateField(input) {
    const rule = rules[input.name];
    if (!rule) return true;
    try {
        rule(input.value);
        setError(input, "");
        return true;
    } catch (error) {
        if (!(error instanceof ValidationError)) throw error;   // իսկական սխալը չենք թաքցնում
        setError(form.querySelector(`[name="${error.field}"]`), error.message);
        return false;
    }
}

export function initBookForm(onSubmit) {
    form.addEventListener("blur", (e) => {
        if (e.target.name in rules) validateField(e.target);
    }, true);

    form.addEventListener("input", (e) => {
        if (e.target.closest(".field")?.classList.contains("has-error")) validateField(e.target);
    });

    form.addEventListener("change", (e) => {
        if (e.target.name === "status") validateField(form.querySelector("#read"));
    });

    form.addEventListener("submit", (e) => {
        e.preventDefault();
        const inputs = [...form.querySelectorAll("input[name]:not([type=radio])")];
        if (!inputs.map(validateField).every(Boolean)) {
            form.querySelector(".has-error input")?.focus();
            return;
        }

        const data = Object.fromEntries(new FormData(form));
        const pages = Number(data.pages);
        const read = data.status === "done" ? pages : data.status === "reading" ? Number(data.read) : 0;
        onSubmit({ title: data.title.trim(), author: data.author.trim(), pages, genre: data.genre, read });
        form.reset();
    });
}

export function fillBookForm({ title, author, pages }) {
    form.querySelector("#title").value = title;
    form.querySelector("#author").value = author;
    form.querySelector("#pages").value = pages ?? "";
    form.scrollIntoView({ behavior: "smooth" });
    form.querySelector(pages ? 'input[name="status"]' : "#pages").focus();
}
