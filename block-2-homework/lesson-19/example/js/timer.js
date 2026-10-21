// «Կարդում եմ հիմա» ժամաչափ — Դաս 14, մոդուլ՝ Դաս 18
import { formatDuration } from "./utils.js";

const SECOND = 1000;

export function initTimer(onStop) {
    const box = document.querySelector(".timer");
    const timeEl = document.querySelector("#timer-time");
    let seconds = 0;
    let intervalId = null;

    const renderTime = () => { timeEl.textContent = formatDuration(seconds); };

    function start() {
        if (intervalId !== null) return;
        intervalId = setInterval(() => {
            seconds++;
            renderTime();
        }, SECOND);
        box.classList.add("is-running");
    }

    function stop() {
        if (intervalId === null) return;
        clearInterval(intervalId);
        intervalId = null;
        box.classList.remove("is-running");
        onStop(seconds);
    }

    function reset() {
        stop();
        seconds = 0;
        renderTime();
    }

    box.addEventListener("click", (e) => {
        const button = e.target.closest("[data-timer]");
        if (!button) return;
        switch (button.dataset.timer) {
            case "start": start(); break;
            case "stop":  stop(); break;
            case "reset": reset(); break;
        }
    });
}
