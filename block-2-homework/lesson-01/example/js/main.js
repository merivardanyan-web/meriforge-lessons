// Անիի ընթերցանության օրագիրը · Դաս 1
// Փոփոխականներ, տիպեր, template literal, prompt/alert

// ---------- Ով է կարդում ----------
const reader = "Անի";
const currentYear = 2026;

// ---------- Գիրքը ----------
const title = "Փոքրիկ իշխանը";
const author = "Անտուան դը Սենտ-Էքզյուպերի";
const year = 1943;
const pages = 96;
let pagesRead = 30;            // let, որովհետև այսօր կփոխվի
const isFavorite = true;
const rating = null;           // դեռ չեմ գնահատել՝ դիտավորյալ դատարկ
let note;                      // undefined՝ նշում դեռ չեմ գրել

console.log(`Ընթերցանության օրագիր․ ${reader}`);
console.log(`Գիրք՝ «${title}», ${author}, ${year}`);
console.log(`Գիրքը ${currentYear - year} տարեկան է`);
console.log(`Կարդացել եմ ${pagesRead} / ${pages} էջ, մնաց ${pages - pagesRead}`);
console.log(`Առաջընթաց՝ ${pagesRead / pages * 100}%`);

// ---------- Տիպերը ----------
console.log("title →", typeof title);
console.log("pages →", typeof pages);
console.log("isFavorite →", typeof isFavorite);
console.log("note →", typeof note);
console.log("rating →", typeof rating);    // null-ի տիպը "object" է՝ JS-ի հին սխալ

// ---------- Այսօրվա ընթերցանությունը ----------
const todayText = prompt("Քանի՞ էջ կարդացիր այսօր");
const today = Number(todayText);                 // prompt-ը միշտ տող է վերադարձնում

console.log("Առանց Number-ի՝", pagesRead + todayText);   // "30" + "12" → "3012"
pagesRead = pagesRead + today;
console.log("Number-ով՝", pagesRead);

alert(`Ապրես, ${reader}։ Արդեն ${pagesRead} էջ է, մնաց ${pages - pagesRead}։`);

/*
  Ինչու է "10" + 5-ը "105", իսկ "10" - 5-ը՝ 5։
  + -ը տողերի համար նաև միացում է, ուստի JS-ը թիվը դարձնում է տող։
  - -ը տողերի համար իմաստ չունի, ուստի JS-ը տողը դարձնում է թիվ։
*/
