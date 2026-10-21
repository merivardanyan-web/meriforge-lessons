// Ապրանքները — Դաս 15։ Հաստատուն տվյալներ, ուստի անունները՝ UPPER_CASE
const PRODUCTS = [
    { id: 1, image: "laptop", name: "Նոութբուք Pro 14", meta: "16 ԳԲ / 512 ԳԲ SSD", price: 315000, oldPrice: 450000, category: "laptops", brand: "arev", rating: 4.8, inStock: true, isNew: false, popularity: 98, added: "2026-03-02", alt: "Նոութբուք Pro 14 բաց վիճակում" },
    { id: 2, image: "monitor", name: "Մոնիտոր 27 4K", meta: "IPS · 60 Հց · USB-C", price: 185000, oldPrice: null, category: "monitors", brand: "masis", rating: 4.6, inStock: true, isNew: true, popularity: 91, added: "2026-09-20", alt: "27 դյույմանոց մոնիտոր բարակ շրջանակով" },
    { id: 3, image: "keyboard", name: "Ստեղնաշար մեխանիկական", meta: "RGB · հայերեն տառեր", price: 42000, oldPrice: null, category: "accessories", brand: "sevan", rating: 4.5, inStock: false, isNew: false, popularity: 77, added: "2026-05-14", alt: "Մեխանիկական ստեղնաշար մուգ ստեղներով" },
    { id: 4, image: "smartphone", name: "Սմարթֆոն X 128", meta: "6.1\" · 128 ԳԲ", price: 310000, oldPrice: null, category: "phones", brand: "lori", rating: 4.4, inStock: true, isNew: false, popularity: 88, added: "2026-06-30", alt: "Սմարթֆոն մեծ էկրանով" },
    { id: 5, image: "headphones", name: "Ականջակալ Studio", meta: "Bluetooth · աղմուկի նվազեցում", price: 80750, oldPrice: 95000, category: "accessories", brand: "arev", rating: 4.7, inStock: true, isNew: false, popularity: 95, added: "2026-04-11", alt: "Անլար ականջակալներ" },
    { id: 6, image: "tablet", name: "Պլանշետ 11", meta: "11\" · 128 ԳԲ · Wi-Fi", price: 240000, oldPrice: null, category: "tablets", brand: "masis", rating: 4.3, inStock: true, isNew: false, popularity: 70, added: "2026-02-08", alt: "Պլանշետ բարակ իրանով" },
    { id: 7, image: "mouse", name: "Մկնիկ անլար", meta: "Bluetooth · 3 կոճակ", price: 18000, oldPrice: null, category: "accessories", brand: "sevan", rating: 4.2, inStock: true, isNew: true, popularity: 83, added: "2026-10-01", alt: "Մուգ գույնի անլար մկնիկ" },
    { id: 8, image: "speaker", name: "Բարձրախոս Mini", meta: "Bluetooth · 10 ժամ", price: 35000, oldPrice: 42000, category: "accessories", brand: "lori", rating: 4.1, inStock: true, isNew: false, popularity: 64, added: "2026-07-19", alt: "Փոքր ուղղանկյուն բարձրախոս" },
    { id: 9, image: "smartwatch", name: "Ժամացույց Fit", meta: "AMOLED · GPS", price: 65000, oldPrice: null, category: "accessories", brand: "arev", rating: 4.0, inStock: false, isNew: false, popularity: 72, added: "2026-08-25", alt: "Խելացի ժամացույց մուգ ժապավենով" },
    { id: 10, image: "laptop-2", name: "Նոութբուք Air 13", meta: "8 ԳԲ / 256 ԳԲ SSD", price: 289000, oldPrice: null, category: "laptops", brand: "masis", rating: 4.6, inStock: true, isNew: false, popularity: 86, added: "2026-09-05", alt: "Բարակ նոութբուք Air 13 մոտիկից" },
    { id: 11, image: "laptop-3", name: "Նոութբուք Studio 16", meta: "32 ԳԲ / 1 ՏԲ SSD", price: 522000, oldPrice: 580000, category: "laptops", brand: "sevan", rating: 4.9, inStock: true, isNew: true, popularity: 79, added: "2026-10-09", alt: "Մեծ էկրանով նոութբուք Studio 16 երեկոյան լույսով" },
    { id: 12, image: "laptop-4", name: "Նոութբուք Lite 15", meta: "8 ԳԲ / 512 ԳԲ SSD", price: 198000, oldPrice: null, category: "laptops", brand: "lori", rating: 4.0, inStock: true, isNew: false, popularity: 68, added: "2026-01-17", alt: "Նոութբուք Lite 15 հետևի կողմից" }
];

const CATEGORIES = {
    laptops: "Նոութբուքեր",
    monitors: "Մոնիտորներ",
    phones: "Սմարթֆոններ",
    tablets: "Պլանշետներ",
    accessories: "Աքսեսուարներ"
};

const BRANDS = {
    arev: "Արև",
    masis: "Մասիս",
    sevan: "Սևան",
    lori: "Լոռի"
};
