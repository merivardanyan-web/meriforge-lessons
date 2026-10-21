// Անիի ընթերցանության օրագիրը · Դաս 2
// Կարգավիճակ, նպատակ, գնահատական, զեղչ գրախանութում

const title = "Սամվել";
const pages = 560;
const pagesRead = Number(prompt(`Քանի՞ էջ ես կարդացել «${title}»-ից (0–${pages})`));

// ---------- 1. Մուտքի ստուգում ----------
if (pagesRead < 0 || pagesRead > pages) {
    console.log(`Սխալ թիվ։ Գրքում ${pages} էջ է։`);
} else {
    // ---------- 2. Կարգավիճակ ----------
    let status;
    if (pagesRead === 0) {
        status = "Կարդալու եմ";
    } else if (pagesRead < pages) {
        status = "Կարդում եմ";
    } else {
        status = "Կարդացել եմ ✓";
    }
    console.log(`«${title}»՝ ${status}`);
    console.log(`Առաջընթաց՝ ${pagesRead / pages * 100}%`);

    // ---------- 3. Մեծ գիրք՞ ----------
    const isLong = pages > 300;
    console.log(isLong ? "Մեծ գիրք է, կարդա քիչ-քիչ" : "Կարճ գիրք է");
}

// ---------- 4. Օրվա նպատակը ----------
const dailyGoal = 20;
const day = 6;                                   // 1 — երկուշաբթի, 7 — կիրակի
const isWeekend = day === 6 || day === 7;
const goal = isWeekend ? dailyGoal * 2 : dailyGoal;
const todayRead = 35;

console.log(`Այսօրվա նպատակը՝ ${goal} էջ`);
if (todayRead >= goal) {
    console.log("Նպատակը կատարված է ✓");
} else {
    console.log(`Մնաց ${goal - todayRead} էջ`);
}

// ---------- 5. Գնահատական ----------
const stars = 4;
switch (stars) {
    case 5: console.log("★★★★★ Գերազանց"); break;
    case 4: console.log("★★★★☆ Լավ"); break;
    case 3: console.log("★★★☆☆ Միջին"); break;
    case 2:
    case 1: console.log("★☆☆☆☆ Չհավանեցի"); break;
    default: console.log("Դեռ չեմ գնահատել");
}

// ---------- 6. Նշում՝ ?? ----------
const note = undefined;
const pagesToday = 0;
console.log(`Նշում՝ ${note ?? "չկա"}`);
console.log(`Այսօր՝ ${pagesToday || "ոչինչ չեմ կարդացել"}`);   // 0-ն falsy է
console.log(`Այսօր՝ ${pagesToday ?? "անհայտ"} էջ`);           // ?? -ը 0-ն պահում է

// ---------- 7. Գրախանութ ----------
const price = 6500;
const count = 4;
const sum = price * count;
let discount = 0;
if (sum > 50000) {
    discount = 20;
} else if (sum > 20000) {
    discount = 10;
}
const total = sum - sum * discount / 100;
console.log(`Գնում՝ ${sum} դրամ, զեղչ՝ ${discount}%, վճարել՝ ${total} դրամ`);
