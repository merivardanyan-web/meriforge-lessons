// Ֆորմաների ստուգում — JS Դաս 13, վերաշարադրված ValidationError-ով (JS Դաս 23)
//
// Կանոնը ֆունկցիա է, որը ստանում է արժեքը և ամբողջ ֆորման։ Սխալի դեպքում նետում է
// ValidationError, ճիշտ արժեքի դեպքում ոչինչ չի անում։

import { ValidationError } from "./errors.js";

export const patterns = {
    email: /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
    phone: /^(\+374|0)\s?\d{2}(\s?\d{2}){3}$/,     // +374 91 23 45 67 կամ 091234567
    upper: /[A-ZԱ-Ֆ]/,
    digit: /\d/
};

// Կանոնների «գործարան»՝ որ ամեն կանոն չգրենք զրոյից
export const required = message => (value, field) => {
    if (value.trim() === "") throw new ValidationError(field, message);
};

export const minLength = (min, message) => (value, field) => {
    if (value.trim().length < min) throw new ValidationError(field, message);
};

export const matches = (pattern, message) => (value, field) => {
    if (value.trim() !== "" && !pattern.test(value.trim())) throw new ValidationError(field, message);
};

// ---------- Սխալների ցուցադրում ----------
function getBox(form, name) {
    const input = form[name];
    return input.closest(".field") ?? input.closest(".check");
}

function showError(form, name, message) {
    getBox(form, name).classList.add("has-error");
    form[name].setAttribute("aria-invalid", "true");
    form.querySelector(`[data-error-for="${name}"]`).textContent = message;
}

function clearError(form, name) {
    getBox(form, name).classList.remove("has-error");
    form[name].removeAttribute("aria-invalid");
    form.querySelector(`[data-error-for="${name}"]`).textContent = "";
}

// ---------- Ստուգիչ մեկ ֆորմայի համար ----------
// rules = { name: [required("..."), minLength(2, "...")], terms: [(value, field, form) => ...] }
export function createValidator(form, rules) {

    function validateField(name) {
        const input = form[name];
        const value = input.type === "checkbox" ? (input.checked ? "on" : "") : input.value;
        try {
            rules[name].forEach(rule => rule(value, name, form));
            clearError(form, name);
            return true;
        } catch (error) {
            if (!(error instanceof ValidationError)) throw error;   // ծրագրի սխալը չենք թաքցնում
            showError(form, error.field, error.message);
            return false;
        }
    }

    function validateAll() {
        // map + every՝ որ ԲՈԼՈՐ սխալները երևան միանգամից (JS Դաս 13)
        const results = Object.keys(rules).map(validateField);
        const isValid = results.every(Boolean);
        if (!isValid) form.querySelector('[aria-invalid="true"]')?.focus();
        return isValid;
    }

    // Սխալը ցույց ենք տալիս դաշտից դուրս գալիս, իսկ ուղղելիս՝ անմիջապես հանում (JS Դաս 13)
    form.addEventListener("blur", (e) => {
        if (e.target.name in rules && e.target.value !== "") validateField(e.target.name);
    }, true);

    form.addEventListener("input", (e) => {
        if (e.target.name in rules && e.target.getAttribute("aria-invalid")) validateField(e.target.name);
    });

    form.addEventListener("change", (e) => {
        if (e.target.type === "checkbox" && e.target.name in rules) validateField(e.target.name);
    });

    return { validateField, validateAll };
}
