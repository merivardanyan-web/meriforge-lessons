// Ժամաչափը որպես դաս — Դաս 22 (տնային՝ Timer դաս)
const SECOND = 1000;

export class ReadingTimer {
    #seconds = 0;
    #intervalId = null;
    #onTick;

    constructor(onTick) {
        this.#onTick = onTick;
    }

    get seconds() {
        return this.#seconds;
    }

    get isRunning() {
        return this.#intervalId !== null;
    }

    start() {
        if (this.isRunning) return this;
        // Arrow ֆունկցիա․ ներսում this-ը նույն ժամաչափն է։
        // Սովորական function-ի ներսում this-ը կկորչեր (Դաս 22, «this — խորը»)
        this.#intervalId = setInterval(() => {
            this.#seconds++;
            this.#onTick(this.#seconds);
        }, SECOND);
        return this;
    }

    stop() {
        clearInterval(this.#intervalId);
        this.#intervalId = null;
        return this;
    }

    reset() {
        this.stop();
        this.#seconds = 0;
        this.#onTick(0);
        return this;
    }
}
