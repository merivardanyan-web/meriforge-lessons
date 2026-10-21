// Ամփոփման տողերը՝ զամբյուղում և պատվերի էջում նույնն են
import { formatPrice } from "../utils.js";

export function createTotals({ count, full, discount, delivery, total }) {
    const deliveryText = delivery === 0
        ? `<span class="text-success">Անվճար</span>`
        : `<span>${formatPrice(delivery)}</span>`;
    return `
        <li><span>Ապրանքներ (${count} հատ)</span><span>${formatPrice(full)}</span></li>
        ${discount > 0 ? `<li><span>Զեղչ</span><span class="text-danger">−${formatPrice(discount)}</span></li>` : ""}
        <li><span>Առաքում</span>${deliveryText}</li>
        <li class="summary__total"><span>Ընդամենը</span><span>${formatPrice(total)}</span></li>
    `;
}
