// «Գնել» կոճակը ցանկացած էջում + «Ավելացվեց զամբյուղ» մոդալը — JS Դաս 11, 12, 16, 20

import { api } from "../api.js";
import { cart } from "../store.js";
import { formatPrice } from "../utils.js";
import { createPicture } from "./cards.js";
import { showToast } from "./toast.js";

const modal = document.querySelector("#cart-modal");

function open(product, qty) {
    modal.querySelector("#cart-modal-product").innerHTML = `
        ${createPicture(product, "ratio-1-1", false)}
        <div>
            <strong>${product.name}</strong>
            <p class="text-muted">${qty} × ${formatPrice(product.price)}</p>
        </div>
    `;
    modal.classList.add("is-open");
    document.body.classList.add("no-scroll");
    modal.querySelector(".modal__close").focus();
}

function close() {
    modal.classList.remove("is-open");
    document.body.classList.remove("no-scroll");
}

export async function addProduct(id, qty = 1) {
    try {
        const product = await api.getProduct(id);       // արդեն քեշում է՝ նոր հարցում չի գնա
        if (!product.inStock) return;
        cart.add(id, qty);
        open(product, qty);
    } catch (error) {
        showToast("Չհաջողվեց ավելացնել։ Փորձիր նորից։");
        console.error(error);
    }
}

export function initCartModal() {
    if (!modal) return;

    // Դելեգացիա ամբողջ էջի վրա՝ աշխատում է նաև JS-ով ստեղծված քարտերի համար
    document.addEventListener("click", (e) => {
        const button = e.target.closest('[data-action="add"]');
        if (button) addProduct(Number(button.dataset.id));
    });

    modal.addEventListener("click", (e) => {
        if (e.target === modal || e.target.closest('[data-action="close-modal"]')) close();
    });

    document.addEventListener("keydown", (e) => {
        if (e.key === "Escape" && modal.classList.contains("is-open")) close();
    });
}
