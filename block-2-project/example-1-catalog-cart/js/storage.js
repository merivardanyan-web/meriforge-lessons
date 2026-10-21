// Պահպանում localStorage-ում — JS Դաս 14
// try/catch՝ որովհետև localStorage-ը կարող է անհասանելի լինել (գաղտնի ռեժիմ, լեցուն հիշողություն)

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
