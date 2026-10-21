// Պահպանում localStorage-ում — Դաս 14
function save(key, value) {
    try {
        localStorage.setItem(key, JSON.stringify(value));
    } catch (e) {
        console.warn("Չհաջողվեց պահել", e);
    }
}

function load(key, fallback = null) {
    try {
        const raw = localStorage.getItem(key);
        return raw === null ? fallback : JSON.parse(raw);
    } catch (e) {
        return fallback;
    }
}
