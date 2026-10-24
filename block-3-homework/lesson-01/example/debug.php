<?php
// Դեբագի փորձեր — PHP Դաս 01-ի տնային (կետ 8, 9)
ini_set('display_errors', 1);
error_reporting(E_ALL);

function dd($data): void
{
    echo "<pre style='background:#1e252a;color:#e8eced;padding:14px;border-radius:8px'>";
    var_dump($data);
    echo "</pre>";
    die();
}

$title   = "Սամվել";               // string
$pages   = 560;                    // int
$percent = 25.5;                   // float
$isDone  = false;                  // bool
$genres  = ["վեպ", "պատմավեպ", "հեքիաթ"];

echo "<h1>var_dump — տիպը և երկարությունը</h1>";
echo "<pre>";
var_dump($title);      // string(12) — 6 տառ, բայց 12 բայթ․ հայերեն տառը 2 բայթ է
var_dump($pages);
var_dump($percent);
var_dump($isDone);
echo "</pre>";

echo "<h2>print_r — զանգվածի համար ավելի ընթեռնելի</h2>";
echo "<pre>";
print_r($genres);
echo "</pre>";

echo "<h2>var_dump — նույն զանգվածը</h2>";
echo "<pre>";
var_dump($genres);
echo "</pre>";

// dd()-ն կանգնեցնում է էջը․ դրանից հետո ոչինչ չի տպվի
dd($genres);

echo "<p>Այս տողը չի երևա</p>";
