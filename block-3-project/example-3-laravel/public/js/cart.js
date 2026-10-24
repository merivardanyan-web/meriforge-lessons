// «Գնել»՝ առանց էջի վերբեռնման — PHP Դաս 17, 19
// Առաջադրանք 2-ի cart.js-ը, հասցեն՝ /cart/items, սխալները՝ Laravel-ի ձևաչափով (Դաս 21-ի տնային)

const csrf = document.querySelector('meta[name="csrf-token"]')?.content ?? "";
const status = document.querySelector("#cart-status");

// Տեսանելի հաղորդագրություն (CSS-ը՝ Բլոկ 2-ի .toast) + role="status" էկրանընթերցիչի համար
let toast;
function notify(message) {
    status.textContent = message;
    toast ??= Object.assign(document.createElement("div"), { className: "toast" });
    toast.setAttribute("aria-hidden", "true");
    toast.textContent = message;
    document.body.append(toast);
    toast.classList.add("is-visible");
    clearTimeout(notify.timer);
    notify.timer = setTimeout(() => toast.classList.remove("is-visible"), 2500);
}

async function addToCart(id, qty = 1) {
    const response = await fetch("/cart/items", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "Accept": "application/json",          // ⭐ expectsJson() → JSON, ոչ redirect
            "X-CSRF-TOKEN": csrf,                  // Laravel-ի անունը
        },
        body: JSON.stringify({ id, qty }),
    });

    const payload = await response.json();

    if (!response.ok) {
        // 422՝ { message, errors: { qty: ["Քանակը..."] } }
        const error = new Error(payload.message ?? "Սխալ");
        error.status = response.status;
        error.details = payload.errors ?? {};
        throw error;
    }

    return payload.data;
}

document.addEventListener("submit", async (event) => {
    const form = event.target.closest("form[data-cart-add]");
    if (!form) return;

    event.preventDefault();
    const button = form.querySelector("button");
    button.disabled = true;

    try {
        const data = await addToCart(Number(form.elements.id.value), Number(form.elements.qty?.value ?? 1));

        document.querySelectorAll("[data-cart-count]").forEach((el) => {
            el.textContent = data.count;
            el.classList.toggle("is-empty", data.count === 0);
        });
        notify(data.message);
    } catch (error) {
        if (error.status === 422) {
            notify(Object.values(error.details).flat().join(" ") || error.message);
        } else {
            form.submit();          // ամեն այլ դեպքում՝ սովորական ֆորման
        }
    } finally {
        button.disabled = false;
    }
});
