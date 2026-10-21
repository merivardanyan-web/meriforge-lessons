// Նոր գրքի ֆորմա՝ ստուգումով — Դաս 13

const form = document.querySelector("#book-form");
const success = document.querySelector("#form-success");
const MAX_PAGES = 3000;

// Ամեն դաշտի համար՝ ֆունկցիա, որ վերադարձնում է սխալի տեքստ կամ ""
const rules = {
    title(value) {
        if (value.trim() === "") return "Վերնագիրը պարտադիր է";
        if (value.trim().length < 2) return "Առնվազն 2 նիշ";
        return "";
    },
    author(value) {
        if (value.trim() === "") return "Հեղինակը պարտադիր է";
        return "";
    },
    pages(value) {
        const n = Number(value);
        if (value.trim() === "") return "Գրիր էջերի քանակը";
        if (!Number.isInteger(n) || n < 1 || n > MAX_PAGES) return `1-ից ${MAX_PAGES}`;
        return "";
    },
    read(value) {
        const status = form.querySelector('input[name="status"]:checked').value;
        if (status !== "reading") return "";                  // մյուս դեպքերում հաշվում ենք ինքներս
        const n = Number(value);
        const pages = Number(form.pages.value);
        if (!Number.isInteger(n) || n < 1) return "Քանի՞ էջ ես արդեն կարդացել";
        if (pages && n >= pages) return `Պիտի լինի ${pages}-ից քիչ, թե չէ գիրքը կարդացած է`;
        return "";
    }
};

function showError(input, message) {
    const field = input.closest(".field");
    field.classList.add("has-error");
    field.querySelector(".field__error").textContent = message;
}

function clearError(input) {
    const field = input.closest(".field");
    field.classList.remove("has-error");
    field.querySelector(".field__error").textContent = "";
}

function validateField(input) {
    const rule = rules[input.name];
    if (!rule) return true;
    const error = rule(input.value);
    if (error) {
        showError(input, error);
        return false;
    }
    clearError(input);
    return true;
}

// Սխալը՝ դաշտից դուրս գալիս (blur-ը չի փուչիկանում → որսման փուլ)
form.addEventListener("blur", (e) => {
    if (e.target.name in rules) validateField(e.target);
}, true);

// Ուղղելիս սխալը վերանում է անմիջապես
form.addEventListener("input", (e) => {
    if (e.target.closest(".field")?.classList.contains("has-error")) validateField(e.target);
});

// Կարգավիճակը փոխելիս «Կարդացած էջեր» դաշտը ստուգում ենք նորից
form.addEventListener("change", (e) => {
    if (e.target.name === "status") validateField(form.read);
});

form.addEventListener("submit", (e) => {
    e.preventDefault();
    success.textContent = "";

    // Ստուգում ենք ԲՈԼՈՐ դաշտերը, որ բոլոր սխալները երևան միանգամից
    const inputs = [...form.querySelectorAll("input[name]:not([type=radio])")];
    const isValid = inputs.map(validateField).every(r => r);
    if (!isValid) {
        form.querySelector(".has-error input")?.focus();
        return;
    }

    const data = Object.fromEntries(new FormData(form));
    const pages = Number(data.pages);
    let read = 0;
    if (data.status === "reading") read = Number(data.read);
    if (data.status === "done") read = pages;

    addBook({ title: data.title.trim(), author: data.author.trim(), pages, genre: data.genre, read });
    success.textContent = `«${data.title.trim()}»-ը ավելացվեց դարակում`;
    form.reset();
});
