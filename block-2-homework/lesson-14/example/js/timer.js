// «Կարդում եմ հիմա» ժամաչափ — Դաս 14 (setInterval, clearInterval)

const timerBox = document.querySelector(".timer");
const timeEl = document.querySelector("#timer-time");
const SECOND = 1000;

let seconds = 0;
let intervalId = null;            // null՝ ժամաչափը կանգնած է

const pad = n => String(n).padStart(2, "0");

function renderTime() {
    timeEl.textContent = `${pad(Math.floor(seconds / 60))}:${pad(seconds % 60)}`;
}

function startTimer() {
    if (intervalId !== null) return;          // երկրորդ սեղմումը չպիտի երկու ժամաչափ միացնի
    intervalId = setInterval(() => {
        seconds++;
        renderTime();
    }, SECOND);
    timerBox.classList.add("is-running");
}

function stopTimer() {
    if (intervalId === null) return;
    clearInterval(intervalId);
    intervalId = null;
    timerBox.classList.remove("is-running");
    showToast(`Կարդացիր ${Math.floor(seconds / 60)} րոպե ${seconds % 60} վայրկյան։ Ապրես։`);
}

function resetTimer() {
    stopTimer();
    seconds = 0;
    renderTime();
}

timerBox.addEventListener("click", (e) => {
    const button = e.target.closest("[data-timer]");
    if (!button) return;
    switch (button.dataset.timer) {
        case "start": startTimer(); break;
        case "stop":  stopTimer(); break;
        case "reset": resetTimer(); break;
    }
});
