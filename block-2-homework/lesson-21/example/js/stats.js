// Մանրամասն վիճակագրություն — բեռնվում է միայն կոճակը սեղմելիս (դինամիկ import, Դաս 18)
import { getStatus } from "./shelf.js";

export function renderStats(books, el) {
    const done = books.filter(b => getStatus(b) === "done");
    const pagesRead = books.reduce((sum, b) => sum + b.read, 0);
    const rated = done.filter(b => b.rating > 0);
    const average = rated.length ? (rated.reduce((sum, b) => sum + b.rating, 0) / rated.length).toFixed(1) : "—";
    const longest = [...books].sort((a, b) => b.pages - a.pages)[0];

    el.innerHTML = `
        <ul class="check-list mb-20">
            <li>Կարդացել եմ ${done.length} գիրք ${books.length}-ից</li>
            <li>Ընդհանուր՝ ${pagesRead} էջ</li>
            <li>Միջին գնահատական՝ ${average}</li>
            <li>Ամենամեծը՝ «${longest?.title ?? "—"}»</li>
        </ul>
    `;
}
