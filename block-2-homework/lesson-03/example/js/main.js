// Անիի ընթերցանության օրագիրը · Դաս 3
// Ընթերցանության պլան, առաջընթացի գիծ, ամսվա օրացույց

const title = "Վերք Հայաստանի";
const pages = 320;
const perDay = 45;

// ---------- 1. Պլան օրերով՝ for ----------
console.log(`Պլան՝ «${title}», օրական ${perDay} էջ`);
let day = 1;
for (let from = 1; from <= pages; from += perDay) {
    let to = from + perDay - 1;
    if (to > pages) {
        to = pages;                        // վերջին օրը՝ մնացածը
    }
    console.log(`Օր ${day}՝ էջ ${from}–${to}`);
    day++;
}

// ---------- 2. Ամեն օր 5 էջ ավելի՝ while ----------
let read = 0;
let today = 20;
let days = 0;
while (read < pages) {
    read += today;
    today += 5;
    days++;
}
console.log(`Եթե սկսեմ 20 էջից և ամեն օր ավելացնեմ 5, կավարտեմ ${days} օրում`);

// ---------- 3. Առաջընթացի գիծ ----------
for (let percent = 0; percent <= 100; percent += 25) {
    const filled = Math.floor(percent / 10);
    console.log(`${"█".repeat(filled)}${"░".repeat(10 - filled)} ${percent}%`);
}

// ---------- 4. Ամսվա օրացույց՝ ներդրված ցիկլ ----------
// Կարդում եմ ամեն օր, բացի ամեն երրորդից։ ■ — կարդացել եմ, □ — ոչ
console.log("Հոկտեմբեր․ ե ե չ հ ու շ կ");
let readDays = 0;
for (let week = 1; week <= 4; week++) {
    let line = "";
    for (let weekDay = 1; weekDay <= 7; weekDay++) {
        const date = (week - 1) * 7 + weekDay;
        if (date % 3 === 0) {
            line += "□ ";
            continue;                      // այս օրը չենք հաշվում
        }
        line += "■ ";
        readDays++;
    }
    console.log(`Շաբաթ ${week}․ ${line}`);
}
console.log(`Կարդացել եմ ${readDays} օր 28-ից`);

// ---------- 5. Երբ կանցնեմ 100 էջը՝ break ----------
let total = 0;
for (let d = 1; d <= 30; d++) {
    total += perDay;
    if (total >= 100) {
        console.log(`100 էջը կանցնեմ ${d}-րդ օրը (${total} էջ)`);
        break;
    }
}
