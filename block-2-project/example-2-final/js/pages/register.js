// Գրանցման ֆորմա՝ գաղտնաբառերի համընկնում և ուժի ցուցիչ — JS Դաս 13, 23

import { initLayout } from "../ui/layout.js";
import { initPasswordToggles } from "../ui/password.js";
import { createValidator, required, minLength, matches, patterns } from "../validation.js";
import { ValidationError } from "../errors.js";

initLayout();

const form = document.querySelector("#register-form");
const message = document.querySelector("#form-message");
const strength = document.querySelector("#strength");
const MIN_PASSWORD = 8;
initPasswordToggles(form);

const strongPassword = (value, field) => {
    if (value.length < MIN_PASSWORD) throw new ValidationError(field, `Առնվազն ${MIN_PASSWORD} նիշ`);
    if (!patterns.upper.test(value)) throw new ValidationError(field, "Ավելացրու մեծատառ");
    if (!patterns.digit.test(value)) throw new ValidationError(field, "Ավելացրու թիվ");
};

const sameAsPassword = (value, field, form) => {
    if (value !== form.password.value) throw new ValidationError(field, "Գաղտնաբառերը չեն համընկնում");
};

const validator = createValidator(form, {
    name: [required("Գրիր անունդ"), minLength(2, "Առնվազն 2 նիշ")],
    email: [required("Գրիր էլ. հասցեն"), matches(patterns.email, "Ստուգիր հասցեն․ օրինակ՝ ani@example.com")],
    password: [required("Մտածիր գաղտնաբառ"), strongPassword],
    password_confirmation: [required("Կրկնիր գաղտնաբառը"), sameAsPassword],
    terms: [required("Անհրաժեշտ է համաձայնությունը")]
});

// Ուժը՝ 0–4․ երկարություն, մեծատառ, թիվ, 12+ նիշ
function getStrength(value) {
    if (value === "") return 0;
    const checks = [value.length >= MIN_PASSWORD, patterns.upper.test(value), patterns.digit.test(value), value.length >= 12];
    return Math.max(checks.filter(Boolean).length, 1);
}

form.password.addEventListener("input", () => {
    strength.setAttribute("data-level", getStrength(form.password.value));
    // Եթե կրկնության դաշտն արդեն լրացված է, ստուգում ենք նորից
    if (form.password_confirmation.value !== "") validator.validateField("password_confirmation");
});

form.addEventListener("submit", (e) => {
    e.preventDefault();
    message.classList.remove("is-success");
    message.textContent = "";
    if (!validator.validateAll()) return;

    message.classList.add("is-success");
    // Անունը օգտագործողինն է՝ textContent (ոչ innerHTML)
    message.textContent = `Շնորհակալություն, ${form.name.value.trim()}։ Ֆորման ճիշտ է։ Հաշիվը կստեղծվի PHP Դաս 16-ում։`;
    form.reset();
    strength.setAttribute("data-level", 0);
});
