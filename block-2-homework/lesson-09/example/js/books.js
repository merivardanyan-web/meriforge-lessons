// Անիի գրադարանը · Դաս 9
// Կարդում ենք էջը JS-ով՝ առանց որևէ բան փոխելու։ Ամեն պատասխան՝ Console-ում։

// 1. Էջի գլխավոր վերնագիրը
const heading = document.querySelector("h1");
console.log(`Էջ՝ ${heading.textContent}`);

// 2. Քանի՞ բաժին, քանի՞ գիրք
console.log(`Բաժիններ՝ ${document.querySelectorAll("section").length}, .row՝ ${document.querySelectorAll(".row").length}`);
const books = document.querySelectorAll(".book");
console.log(`Գրքեր դարակում՝ ${books.length}`);

// 3. Բոլոր վերնագրերը զանգվածով — NodeList-ը զանգված չէ, ուստի [...]
const titles = [...document.querySelectorAll(".book__title")].map(t => t.textContent);
console.log("Վերնագրեր՝", titles);

// 4. Էջերը՝ թվերով (textContent-ը միշտ տող է)
const pages = [...document.querySelectorAll(".book__pages")].map(el => Number(el.textContent));
const read = [...document.querySelectorAll(".book__read")].map(el => Number(el.textContent));
const totalPages = pages.reduce((sum, n) => sum + n, 0);
const totalRead = read.reduce((sum, n) => sum + n, 0);
console.log(`Ընդհանուր՝ ${totalPages} էջ, կարդացել եմ՝ ${totalRead} (${Math.round(totalRead / totalPages * 100)}%)`);
console.log(`Ամենամեծը՝ ${titles[pages.indexOf(Math.max(...pages))]}`);

// 5. Ամեն քարտի ներսում՝ իր տվյալները
books.forEach((book, i) => {
    const title = book.querySelector(".book__title").textContent;
    const status = book.querySelector(".pill").textContent;
    console.log(`${i + 1}. ${title} — ${status}`);
});

// 6. Կարդացածները՝ ըստ պիտակի տեքստի
const finished = [...books].filter(book => book.querySelector(".pill").textContent === "Կարդացել եմ");
console.log(`Կարդացել եմ ${finished.length} գիրք`);

// 7. Ծառով՝ վեր և վար
const firstBody = document.querySelector(".book__body");
console.log(`Առաջին քարտի մարմնում՝ ${firstBody.children.length} էլեմենտ՝`, [...firstBody.children].map(el => el.tagName));
const itsSection = firstBody.closest("section");
console.log(`Քարտը գտնվում է section#${itsSection.id}-ում`);
console.log(`Երկրորդ քարտը՝ ${books[0].nextElementSibling.querySelector(".book__title").textContent}`);

// 8. Գոյություն չունեցող էլեմենտ — null, ոչ սխալ
const rating = document.querySelector(".book__rating");
console.log("Գնահատական՝", rating);
console.log(`Գնահատականի տեքստը՝ ${rating?.textContent ?? "դեռ չկա (կավելացնենք Դաս 12-ում)"}`);
