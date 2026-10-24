<?php
// Անիի դարակը — PHP Դաս 02
// Բլոկ 2-ի js/data.js-ի նույն գրքերը՝ PHP զանգվածով։ Դաս 10-ում դրանք կգան բազայից

$siteName = "Անի Սարգսյան";
$email    = "ani@example.com";
$city     = "Սևան";
$phone    = "+374 91 000000";

$books = [
    ['id' => 1, 'title' => 'Փոքրիկ իշխանը', 'author' => 'Անտուան դը Սենտ-Էքզյուպերի', 'year' => 1943,
     'pages' => 96, 'read' => 96, 'genre' => 'հեքիաթ', 'rating' => 5, 'color' => '#2f5d8a'],
    ['id' => 2, 'title' => 'Սամվել', 'author' => 'Րաֆֆի', 'year' => 1886,
     'pages' => 560, 'read' => 140, 'genre' => 'պատմավեպ', 'rating' => 0, 'color' => '#8a3b2f'],
    ['id' => 3, 'title' => 'Գիքորը', 'author' => 'Հովհաննես Թումանյան', 'year' => 1907,
     'pages' => 40, 'read' => 40, 'genre' => 'պատմվածք', 'rating' => 5, 'color' => '#5b7a3a'],
    ['id' => 4, 'title' => '1984', 'author' => 'Ջորջ Օրուել', 'year' => 1949,
     'pages' => 328, 'read' => 0, 'genre' => 'վեպ', 'rating' => 0, 'color' => '#3d3d55'],
    ['id' => 5, 'title' => 'Ալքիմիկոսը', 'author' => 'Պաուլո Կոելյո', 'year' => 1988,
     'pages' => 208, 'read' => 208, 'genre' => 'վեպ', 'rating' => 3, 'color' => '#b07a1f'],
    ['id' => 6, 'title' => 'Վերք Հայաստանի', 'author' => 'Խաչատուր Աբովյան', 'year' => 1858,
     'pages' => 320, 'read' => 90, 'genre' => 'պատմավեպ', 'rating' => 0, 'color' => '#6b4a7a'],
    // year չկա՝ ?? -ի համար (Դաս 02)
    ['id' => 7, 'title' => 'Հոբիտը', 'author' => 'Ջ. Ռ. Ռ. Թոլքին',
     'pages' => 310, 'read' => 120, 'genre' => 'ֆանտաստիկա', 'rating' => 0, 'color' => '#3f6b5a'],
];
