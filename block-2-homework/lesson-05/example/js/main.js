// Անիի ընթերցանության օրագիրը · Դաս 5
// Իմ դարակը՝ զանգվածներով

const shelf = ["Փոքրիկ իշխանը", "Սամվել", "Գիքորը"];
const pagesList = [96, 560, 40];             // նույն ինդեքսով՝ նույն գիրքը
const toRead = ["1984", "Ալքիմիկոսը", "Քաոս"];

// ---------- Ֆունկցիաներ ----------
function showShelf() {
    if (shelf.length === 0) {
        console.log("Դարակը դատարկ է");
        return;
    }
    console.log(`--- Դարակում ${shelf.length} գիրք ---`);
    for (let i = 0; i < shelf.length; i++) {
        console.log(`${i + 1}. ${shelf[i]} · ${pagesList[i]} էջ`);
    }
}

function addBook(title, pages) {
    if (shelf.includes(title)) {
        return `«${title}»-ն արդեն դարակում է`;
    }
    shelf.push(title);
    pagesList.push(pages);
    return `Ավելացվեց՝ «${title}»`;
}

function removeBook(title) {
    const index = shelf.indexOf(title);
    if (index === -1) {
        return "Չգտնվեց";
    }
    shelf.splice(index, 1);
    pagesList.splice(index, 1);               // երկու զանգվածից՝ նույն տեղից
    return `Հանվեց՝ «${title}»`;
}

function getTotal(list) {
    let sum = 0;
    for (const n of list) {
        sum += n;
    }
    return sum;
}

// ---------- Աշխատանք դարակի հետ ----------
showShelf();
console.log(addBook("Վերք Հայաստանի", 320));
console.log(addBook("Սամվել", 560));
console.log(removeBook("Գիքորը"));
console.log(removeBook("Հարի Փոթեր"));
showShelf();

// ---------- Հերթ՝ ինչ կարդալ հետո ----------
const next = toRead.shift();                  // հանում է առաջինը
console.log(`Հաջորդը՝ «${next}», հերթում մնաց ${toRead.length}`);
toRead.push("Հարի Փոթերը և փիլիսոփայական քարը");
console.log("Հերթ՝", toRead.join(" → "));

// ---------- Ժանրեր տողից ----------
const genres = "վեպ,հեքիաթ,պատմավեպ,պատմվածք".split(",");
genres.push("պոեզիա");
console.log("Ժանրեր՝", genres.join(" · "));

// ---------- Վիճակագրություն ----------
const total = getTotal(pagesList);
const longest = Math.max(...pagesList);
console.log(`Ընդհանուր՝ ${total} էջ, միջինը՝ ${(total / pagesList.length).toFixed(0)} էջ`);
console.log(`Ամենամեծը՝ «${shelf[pagesList.indexOf(longest)]}» (${longest} էջ)`);
console.log("Առաջին երկուսը՝", shelf.slice(0, 2));

// Շրջված պատճեն․ բնօրինակը չի փոխվում
const reversed = [...shelf].reverse();
console.log("Վերջից՝", reversed);
console.log("Բնօրինակը՝", shelf);
