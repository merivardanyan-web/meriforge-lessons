<?php
// Անիի դարակը — PHP Դաս 02, 05
// Կայքի անունը և կոնտակտները՝ functions.php-ի հաստատուններում (Դաս 05)
// 'link'՝ արտաքին հղում։ Ցուցադրվում է միայն safeUrl()-ով

$books = [
    ['id' => 1, 'title' => 'Փոքրիկ իշխանը', 'author' => 'Անտուան դը Սենտ-Էքզյուպերի', 'year' => 1943,
     'pages' => 96, 'read' => 96, 'genre' => 'հեքիաթ', 'rating' => 5, 'color' => '#2f5d8a',
     'link' => 'https://hy.wikipedia.org/w/index.php?search=Փոքրիկ+իշխանը'],
    ['id' => 2, 'title' => 'Սամվել', 'author' => 'Րաֆֆի', 'year' => 1886,
     'pages' => 560, 'read' => 140, 'genre' => 'պատմավեպ', 'rating' => 0, 'color' => '#8a3b2f',
     'link' => 'https://hy.wikipedia.org/w/index.php?search=Սամվել+Րաֆֆի'],
    ['id' => 3, 'title' => 'Գիքորը', 'author' => 'Հովհաննես Թումանյան', 'year' => 1907,
     'pages' => 40, 'read' => 40, 'genre' => 'պատմվածք', 'rating' => 5, 'color' => '#5b7a3a',
     'link' => 'https://hy.wikipedia.org/w/index.php?search=Գիքորը'],
    ['id' => 4, 'title' => '1984', 'author' => 'Ջորջ Օրուել', 'year' => 1949,
     'pages' => 328, 'read' => 0, 'genre' => 'վեպ', 'rating' => 0, 'color' => '#3d3d55',
     'link' => 'https://en.wikipedia.org/wiki/Nineteen_Eighty-Four'],
    ['id' => 5, 'title' => 'Ալքիմիկոսը', 'author' => 'Պաուլո Կոելյո', 'year' => 1988,
     'pages' => 208, 'read' => 208, 'genre' => 'վեպ', 'rating' => 3, 'color' => '#b07a1f',
     'link' => 'https://en.wikipedia.org/wiki/The_Alchemist_(novel)'],
    ['id' => 6, 'title' => 'Վերք Հայաստանի', 'author' => 'Խաչատուր Աբովյան', 'year' => 1858,
     'pages' => 320, 'read' => 90, 'genre' => 'պատմավեպ', 'rating' => 0, 'color' => '#6b4a7a',
     'link' => 'https://hy.wikipedia.org/w/index.php?search=Վերք+Հայաստանի'],
    // year չկա՝ ?? -ի համար (Դաս 02)
    ['id' => 7, 'title' => 'Հոբիտը', 'author' => 'Ջ. Ռ. Ռ. Թոլքին',
     'pages' => 310, 'read' => 120, 'genre' => 'ֆանտաստիկա', 'rating' => 0, 'color' => '#3f6b5a',
     // ⚠️ Դաս 05-ի փորձը․ e()-ն այս հղումը չի կանգնեցնի, safeUrl()-ը՝ կդարձնի «#»
     'link' => 'javascript:alert(document.cookie)'],

    // XSS-ի փորձ — Դաս 05, դասարանական 9․ բացիր մեկնաբանությունը, բաց արա էջը, ապա փակիր
    // ['id' => 8, 'title' => '<script>alert("XSS")</script>', 'author' => '<img src=x onerror=alert(1)>',
    //  'pages' => 1, 'read' => 0, 'genre' => 'վեպ', 'rating' => 0, 'color' => '#000000'],
];
