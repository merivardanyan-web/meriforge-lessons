// Անիի գրադարանը · տվյալներ — Դաս 18
// Միայն հաստատուններ։ Վիճակը (books) ապրում է shelf.js-ում

export const BOOKS_KEY = "ani:books";

// Առաջին այցելության համար։ Հետո դարակը գալիս է localStorage-ից
export const INITIAL_BOOKS = [
    { id: 1, title: "Փոքրիկ իշխանը", author: "Անտուան դը Սենտ-Էքզյուպերի", year: 1943, pages: 96, read: 96, genre: "հեքիաթ", rating: 5, color: "#2f5d8a" },
    { id: 2, title: "Սամվել", author: "Րաֆֆի", year: 1886, pages: 560, read: 140, genre: "պատմավեպ", rating: 0, color: "#8a3b2f" },
    { id: 3, title: "Գիքորը", author: "Հովհաննես Թումանյան", year: 1907, pages: 40, read: 40, genre: "պատմվածք", rating: 5, color: "#5b7a3a" },
    { id: 4, title: "1984", author: "Ջորջ Օրուել", year: 1949, pages: 328, read: 0, genre: "վեպ", rating: 0, color: "#3d3d55" },
    { id: 5, title: "Ալքիմիկոսը", author: "Պաուլո Կոելյո", year: 1988, pages: 208, read: 208, genre: "վեպ", rating: 3, color: "#b07a1f" },
    { id: 6, title: "Վերք Հայաստանի", author: "Խաչատուր Աբովյան", year: 1858, pages: 320, read: 90, genre: "պատմավեպ", rating: 0, color: "#6b4a7a" }
];

export const STATUS_TEXT = { planned: "Կարդալու եմ", reading: "Կարդում եմ", done: "Կարդացել եմ" };
export const PAGES_STEP = 10;
export const MAX_RATING = 5;
export const COLORS = ["#2f5d8a", "#8a3b2f", "#5b7a3a", "#3d3d55", "#b07a1f", "#6b4a7a"];

