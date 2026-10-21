// Զամբյուղը որպես դաս — JS Դաս 22
// Ինչպես Դաս 16-ում, պահում ենք միայն { id, qty }։ Ապրանքները գալիս են API-ից ավելի ուշ,
// ուստի getItems()-ը և getTotals()-ը դրանք ստանում են պարամետրով։

import { save, load } from "../storage.js";
import { MAX_QTY, FREE_DELIVERY_FROM, DELIVERY_PRICE } from "../config.js";

export class Cart {
    #items;                      // փակ — դրսից ոչ ոք չի փչացնի (JS Դաս 22)
    #storageKey;
    #listeners = [];

    constructor(storageKey) {
        this.#storageKey = storageKey;
        this.#items = load(storageKey, []);
    }

    // ---------- Կարդալու համար ----------
    get count() {
        return this.#items.reduce((sum, item) => sum + item.qty, 0);
    }

    get isEmpty() {
        return this.#items.length === 0;
    }

    getItems(products) {
        return this.#items
            .map(item => {
                const product = products.find(p => p.id === item.id);
                if (!product) return null;
                const fullPrice = product.oldPrice ?? product.price;
                return {
                    ...product,
                    qty: item.qty,
                    subtotal: product.price * item.qty,
                    fullSubtotal: fullPrice * item.qty
                };
            })
            .filter(Boolean);
    }

    getTotals(products) {
        const items = this.getItems(products);
        const full = items.reduce((sum, i) => sum + i.fullSubtotal, 0);
        const goods = items.reduce((sum, i) => sum + i.subtotal, 0);
        const delivery = goods === 0 || goods >= FREE_DELIVERY_FROM ? 0 : DELIVERY_PRICE;
        return {
            count: items.reduce((sum, i) => sum + i.qty, 0),
            full,
            discount: full - goods,
            goods,
            delivery,
            total: goods + delivery
        };
    }

    // ---------- Փոփոխելու համար․ ամեն մեթոդ վերադարձնում է this՝ շղթայի համար ----------
    add(id, qty = 1) {
        const existing = this.#items.find(item => item.id === id);
        // Անփոփոխ թարմացում՝ նոր զանգված, ոչ push (JS Դաս 23)
        this.#items = existing
            ? this.#items.map(item => item.id === id ? { ...item, qty: Math.min(item.qty + qty, MAX_QTY) } : item)
            : [...this.#items, { id, qty: Math.min(qty, MAX_QTY) }];
        this.#save();
        return this;
    }

    setQty(id, qty) {
        const safeQty = Math.min(Math.max(qty, 1), MAX_QTY);
        this.#items = this.#items.map(item => item.id === id ? { ...item, qty: safeQty } : item);
        this.#save();
        return this;
    }

    remove(id) {
        this.#items = this.#items.filter(item => item.id !== id);
        this.#save();
        return this;
    }

    clear() {
        this.#items = [];
        this.#save();
        return this;
    }

    // Բաժանորդագրություն՝ header-ի թիվը թարմացվում է ինքնաբերաբար
    onChange(listener) {
        this.#listeners.push(listener);
    }

    // ---------- Ներքին ----------
    #save() {
        save(this.#storageKey, this.#items);
        this.#listeners.forEach(listener => listener(this));
    }
}
