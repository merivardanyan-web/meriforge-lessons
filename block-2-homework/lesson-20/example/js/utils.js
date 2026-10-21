// Ընդհանուր օգնականներ — Դաս 16, 18
export function debounce(fn, delay = 300) {
    let timer;
    return (...args) => {
        clearTimeout(timer);
        timer = setTimeout(() => fn(...args), delay);
    };
}

// Ներքին՝ չենք արտահանում
function pad(n) {
    return String(n).padStart(2, "0");
}

export function formatDuration(totalSeconds) {
    return `${pad(Math.floor(totalSeconds / 60))}:${pad(totalSeconds % 60)}`;
}
