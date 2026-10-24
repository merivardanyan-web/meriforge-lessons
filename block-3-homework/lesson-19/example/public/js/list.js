// «+ Կարդալու ցուցակ»՝ fetch-ով — PHP Դաս 17-ի list.js-ը, Laravel-ի համար (Դաս 19)
// Երկու փոփոխություն․ հասցեն՝ ֆորմայի action-ից, header-ը՝ X-CSRF-TOKEN (Laravel-ի անունը)

const csrf = document.querySelector('meta[name="csrf-token"]')?.content ?? "";

function showToast(message) {
    const toast = document.querySelector("[data-toast]");
    if (!toast) return;
    toast.textContent = message;
    toast.classList.add("is-visible");
    setTimeout(() => toast.classList.remove("is-visible"), 3000);
}

document.addEventListener("submit", async (event) => {
    const form = event.target.closest("form[data-list-add]");
    if (!form) return;

    event.preventDefault();
    const button = form.querySelector('button[type="submit"]');
    button.disabled = true;

    try {
        const response = await fetch(form.action, {
            method: "POST",
            headers: { "Accept": "application/json", "Content-Type": "application/json", "X-CSRF-TOKEN": csrf },
            body: JSON.stringify({ id: Number(form.elements.id.value) }),
        });
        const payload = await response.json();

        if (response.status === 422) {
            showToast(payload.data?.message ?? payload.message);      // Դաս 21-ում՝ payload.errors
            button.disabled = false;
            return;
        }
        if (!response.ok) throw new Error(response.status);

        document.querySelectorAll("[data-list-count]").forEach((el) => {
            el.textContent = payload.data.count;
            el.classList.toggle("hidden", payload.data.count === 0);
        });
        showToast(payload.data.message);
        button.textContent = "✓ Ցուցակում է";
    } catch {
        form.submit();                 // ⭐ ամեն այլ դեպքում՝ սովորական ֆորման
    }
});
