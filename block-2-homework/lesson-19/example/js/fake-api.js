// «Սերվեր», որը պատասխանում է ուշացումով և երբեմն սխալվում է — Դաս 19
// Դաս 20-ում այն կփոխարինենք իսկական fetch-ով, իսկ մնացած կոդը չի փոխվի

const DELAY = 900;
const FAIL_RATE = 0.2;              // 20% դեպքերում՝ սխալ

const RECOMMENDED = [
    { title: "Քաոս", author: "Ալեքսանդր Շիրվանզադե", year: 1898, pages: 448, genre: "վեպ" },
    { title: "Հարի Փոթերը և փիլիսոփայական քարը", author: "Ջ. Ք. Ռոուլինգ", year: 1997, pages: 332, genre: "ֆանտաստիկա" },
    { title: "Անլռելի զանգակատուն", author: "Պարույր Սևակ", year: 1959, pages: 240, genre: "պոեզիա" },
    { title: "Մատիլդա", author: "Ռոալդ Դալ", year: 1988, pages: 240, genre: "հեքիաթ" },
    { title: "Վարպետը և Մարգարիտան", author: "Միխայիլ Բուլգակով", year: 1967, pages: 480, genre: "վեպ" },
    { title: "Ալիս Հրաշքների աշխարհում", author: "Լյուիս Քերոլ", year: 1865, pages: 160, genre: "հեքիաթ" }
];

const TIPS = [
    "Օրական 20 էջը տարեկան մոտ 24 գիրք է։",
    "Գիրքը միշտ քեզ հետ պահիր․ հերթում 10 րոպեն էլ ընթերցանություն է։",
    "Եթե գիրքը 50 էջից հետո չի գրավում, թող այն և վերցրու ուրիշը։"
];

export const delay = ms => new Promise(resolve => setTimeout(resolve, ms));

export function getRecommended() {
    return delay(DELAY).then(() => {
        if (Math.random() < FAIL_RATE) {
            return Promise.reject(new Error("Սերվերը չպատասխանեց"));   // շղթան կգնա catch
        }
        return RECOMMENDED;
    });
}

export function getTip() {
    return delay(DELAY / 2).then(() => TIPS[Math.floor(Math.random() * TIPS.length)]);
}
