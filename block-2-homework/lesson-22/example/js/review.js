// «Խորհուրդ տուր գիրք»՝ POST հարցում — Դաս 20, ստուգումը՝ Դաս 13
import { sendSuggestion } from "./api.js";

const MIN_TITLE = 2;
const MIN_BODY = 10;

export function initReview() {
    const form = document.querySelector("#review-form");
    const statusEl = document.querySelector("#review-status");
    const submitBtn = form.querySelector('[type="submit"]');
    const titleInput = form.querySelector("#review-title");
    const bodyInput = form.querySelector("#review-body");

    const rules = {
        title: value => value.trim().length < MIN_TITLE ? "Գրիր գրքի անունը" : "",
        body: value => value.trim().length < MIN_BODY ? `Առնվազն ${MIN_BODY} նիշ` : ""
    };

    function validateField(input) {
        const error = rules[input.name](input.value);
        const field = input.closest(".field");
        field.classList.toggle("has-error", error !== "");
        field.querySelector(".field__error").textContent = error;
        return error === "";
    }

    form.addEventListener("input", (e) => {
        if (e.target.closest(".field")?.classList.contains("has-error")) validateField(e.target);
    });

    form.addEventListener("submit", async (e) => {
        e.preventDefault();
        const isValid = [titleInput, bodyInput].map(validateField).every(Boolean);
        if (!isValid) return;

        submitBtn.disabled = true;
        statusEl.textContent = "Ուղարկվում է…";
        try {
            const created = await sendSuggestion(titleInput.value.trim(), bodyInput.value.trim());
            statusEl.textContent = `Շնորհակալություն։ Խորհուրդը ստացվեց, համարը՝ ${created.id}։`;
            form.reset();
        } catch (error) {
            statusEl.textContent = "Չհաջողվեց ուղարկել։ Փորձիր մի փոքր ուշ։";
            console.error(error);
        } finally {
            submitBtn.disabled = false;
        }
    });
}
