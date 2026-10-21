// Հավանածներ — Set, որովհետև նույն ապրանքը երկու անգամ հավանել հնարավոր չէ (JS Դաս 23)

import { save, load } from "../storage.js";

export class Wishlist {
    #ids;
    #storageKey;

    constructor(storageKey) {
        this.#storageKey = storageKey;
        this.#ids = new Set(load(storageKey, []));   // JSON-ը Set չգիտի՝ պահում ենք զանգված
    }

    get size() {
        return this.#ids.size;
    }

    has(id) {
        return this.#ids.has(id);
    }

    // Վերադարձնում է նոր վիճակը՝ true, եթե հիմա հավանած է
    toggle(id) {
        if (this.#ids.has(id)) {
            this.#ids.delete(id);
        } else {
            this.#ids.add(id);
        }
        save(this.#storageKey, [...this.#ids]);
        return this.#ids.has(id);
    }
}
