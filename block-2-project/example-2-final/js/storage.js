// Պահպանում — JS Դաս 14, մոդուլ՝ JS Դաս 18
// Երրորդ պարամետրը թույլ է տալիս օգտագործել նաև sessionStorage-ը (պատվերի համարի համար)

export function save(key, value, area = localStorage) {
    try {
        area.setItem(key, JSON.stringify(value));
    } catch (e) {
        console.warn("Չհաջողվեց պահել", e);
    }
}

export function load(key, fallback = null, area = localStorage) {
    try {
        const raw = area.getItem(key);
        return raw === null ? fallback : JSON.parse(raw);
    } catch (e) {
        return fallback;                 // փչացած JSON → լռելյայնը (JS Դաս 23)
    }
}

export function remove(key, area = localStorage) {
    try {
        area.removeItem(key);
    } catch (e) {
        console.warn("Չհաջողվեց ջնջել", e);
    }
}
