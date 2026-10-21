// Անիի ընթերցանության օրագիրը · Դաս 4
// Ֆունկցիաների փոքր գրադարան․ ամեն ֆունկցիա՝ մեկ գործ

const DEFAULT_PER_DAY = 30;

// Քանի՞ տոկոսն է կարդացված՝ ամբողջ թիվ
function getProgress(read, total) {
    return Math.floor(read / total * 100);
}

// return առանց else-ի․ առաջին համընկած պայմանը ավարտում է ֆունկցիան
function getStatus(read, total) {
    if (read === 0) return "Կարդալու եմ";
    if (read < total) return "Կարդում եմ";
    return "Կարդացել եմ";
}

// Քանի՞ օր է մնացել․ պարամետրը լռելյայն 30 է
function getDaysLeft(read, total, perDay = DEFAULT_PER_DAY) {
    let days = 0;
    let left = total - read;
    while (left > 0) {
        left -= perDay;
        days++;
    }
    return days;
}

// Ընթերցանության ժամանակը՝ «3 ժ 12 ր»
function getReadingTime(pages, minutesPerPage = 2) {
    const minutes = pages * minutesPerPage;
    const hours = Math.floor(minutes / 60);
    return `${hours} ժ ${minutes % 60} ր`;
}

// Arrow ֆունկցիաներ՝ կարճ գործերի համար
const isLongBook = pages => pages > 300;
const formatBook = (title, author, year) => `«${title}» — ${author}, ${year}`;
const createBar = percent => "█".repeat(Math.floor(percent / 10)) + "░".repeat(10 - Math.floor(percent / 10));

// Մեկ ֆունկցիա, որ օգտագործում է մյուսները
function describeBook(title, author, year, pages, read) {
    const progress = getProgress(read, pages);
    console.log(formatBook(title, author, year));
    console.log(`  ${createBar(progress)} ${progress}% · ${getStatus(read, pages)}`);
    console.log(`  Մնաց ${getDaysLeft(read, pages)} օր · ամբողջը կարդալու համար՝ ${getReadingTime(pages)}`);
    if (isLongBook(pages)) {
        console.log("  Մեծ գիրք է");
    }
}

describeBook("Փոքրիկ իշխանը", "Անտուան դը Սենտ-Էքզյուպերի", 1943, 96, 96);
describeBook("Սամվել", "Րաֆֆի", 1886, 560, 140);
describeBook("1984", "Ջորջ Օրուել", 1949, 328, 0);

// Լռելյայն արժեքի ստուգում
console.log("Օրական 30 էջ՝", getDaysLeft(140, 560), "օր");
console.log("Օրական 60 էջ՝", getDaysLeft(140, 560, 60), "օր");
