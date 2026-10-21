// Ընդհանուր օգնականներ — Դաս 16, 18, 23 (փակույթներ)
// timer-ը ապրում է փակույթում․ ամեն կանչ տեսնում է նախորդի timer-ը
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

// Ֆունկցիան կկատարվի միայն մեկ անգամ․ called-ը «հիշվում» է փակույթում
export function once(fn) {
    let called = false;
    return (...args) => {
        if (called) return;
        called = true;
        fn(...args);
    };
}
