// Մուտքի ֆորմա — JS Դաս 13
// Գաղտնաբառը ստուգել կարող է միայն սերվերը։ Այստեղ ստուգում ենք միայն ձևաչափը,
// իսկ իսկական մուտքը կաշխատի PHP Դաս 16-ում՝ նույն դաշտերով։

import { initLayout } from "../ui/layout.js";
import { initPasswordToggles } from "../ui/password.js";
import { createValidator, required, matches, patterns } from "../validation.js";

initLayout();

const form = document.querySelector("#login-form");
const message = document.querySelector("#form-message");
initPasswordToggles(form);

const validator = createValidator(form, {
    email: [required("Գրիր էլ. հասցեն"), matches(patterns.email, "Ստուգիր հասցեն․ օրինակ՝ ani@example.com")],
    password: [required("Գրիր գաղտնաբառը")]
});

form.addEventListener("submit", (e) => {
    e.preventDefault();
    message.classList.remove("is-success");
    message.textContent = "";
    if (!validator.validateAll()) return;

    message.classList.add("is-success");
    message.textContent = "Ֆորման ճիշտ է լրացված։ Գաղտնաբառը կստուգի սերվերը՝ PHP Դաս 16-ում։";
});
