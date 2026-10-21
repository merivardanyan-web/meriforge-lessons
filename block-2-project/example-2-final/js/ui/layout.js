// Ամեն էջի ընդհանուր մասը — կանչվում է ամեն pages/*.js-ի սկզբում
import { initHeader } from "./header.js";
import { initTheme } from "./theme.js";

export function initLayout() {
    initHeader();
    initTheme();
}
