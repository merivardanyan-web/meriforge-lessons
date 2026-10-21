// Շնորհակալության էջ՝ պատվերի համարը sessionStorage-ից — JS Դաս 14
import { initLayout } from "../ui/layout.js";
import { load, remove } from "../storage.js";
import { formatPrice } from "../utils.js";
import { KEYS } from "../config.js";

initLayout();

const order = load(KEYS.order, null, sessionStorage);

if (order) {
    document.querySelector("#order-number").textContent = order.number;
    const deliveryText = order.delivery === "pickup" ? "Վերցնելու ես խանութից" : "Կառաքենք";
    document.querySelector("#order-items").textContent =
        `${order.name}, ${order.count} ապրանք՝ ${formatPrice(order.total)}։ ${deliveryText}։`;
    remove(KEYS.order, sessionStorage);       // թարմացնելիս նույն պատվերը կրկին չի ցուցադրվի
} else {
    document.querySelector("#order-number").closest("p").classList.add("hidden");
}
