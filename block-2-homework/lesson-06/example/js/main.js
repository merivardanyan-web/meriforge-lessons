// Անիի ընթերցանության օրագիրը · Դաս 6
// Դարակի վիճակագրություն՝ map, filter, reduce, find, some, every, sort
// Ամեն գիրք օբյեկտ է․ դրանց մանրամասն կծանոթանանք Դաս 7-ում, այսօր միայն կարդում ենք b.title, b.pages

const books = [
    { title: "Փոքրիկ իշխանը", author: "Էքզյուպերի", pages: 96, read: 96, rating: 5 },
    { title: "Սամվել", author: "Րաֆֆի", pages: 560, read: 140, rating: 0 },
    { title: "Գիքորը", author: "Թումանյան", pages: 40, read: 40, rating: 5 },
    { title: "1984", author: "Օրուել", pages: 328, read: 0, rating: 0 },
    { title: "Ալքիմիկոսը", author: "Կոելյո", pages: 208, read: 208, rating: 3 },
    { title: "Վերք Հայաստանի", author: "Աբովյան", pages: 320, read: 90, rating: 0 }
];

// 1. Բոլոր վերնագրերը
console.log(books.map(b => b.title));

// 2. Կարդացածները
const finished = books.filter(b => b.read === b.pages);
console.log("Կարդացել եմ՝", finished.map(b => b.title).join(", "));

// 3. Քանի՞ էջ եմ կարդացել ընդհանուր
const totalRead = books.reduce((sum, b) => sum + b.read, 0);
console.log(`Կարդացած էջեր՝ ${totalRead}`);

// 4. Միջին գնահատականը՝ միայն կարդացածների
const average = finished.reduce((sum, b) => sum + b.rating, 0) / finished.length;
console.log(`Միջին գնահատական՝ ${average.toFixed(1)}`);

// 5. Գտնել գիրք
const found = books.find(b => b.title === "Սամվել");
console.log(`«Սամվել»՝ ${found.read} / ${found.pages}`);
const missing = books.find(b => b.title === "Քաոս");
console.log(missing ?? "«Քաոս» դարակում չկա");

// 6. Կա՞ 5 աստղանի գիրք, բոլո՞րն ունեն էջեր
console.log("Կա՞ 5 աստղանի՝", books.some(b => b.rating === 5));
console.log("Բոլորը սկսե՞լ եմ՝", books.every(b => b.read > 0));

// 7. Ամենամեծից փոքր՝ առանց բնօրինակը փոխելու
const byPages = [...books].sort((a, b) => b.pages - a.pages);
console.log("Ըստ էջերի՝", byPages.map(b => `${b.title} (${b.pages})`));
console.log("Բնօրինակի առաջինը՝", books[0].title);

// 8. Մեկ շղթա․ չավարտածները, քիչ մնացածից շատ մնացած
const plan = books
    .filter(b => b.read < b.pages)
    .sort((a, b) => (a.pages - a.read) - (b.pages - b.read))   // filter-ը արդեն նոր զանգված է
    .map(b => `${b.title} — ${b.pages - b.read} էջ`);
console.log("Ինչ ավարտել նախ՝", plan);
