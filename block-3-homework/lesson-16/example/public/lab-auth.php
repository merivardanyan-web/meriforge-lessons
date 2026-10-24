<?php
declare(strict_types=1);

// ⚠️ ՓՈՐՁՆԱԿԱՆ ֆայլ — PHP Դաս 16, տնային 3, 4, 6․ փորձից հետո ՋՆՋԻՐ
// Բացվում է ուղիղ (/lab-auth.php), որովհետև ֆայլը իրականում կա public/-ում
require_once __DIR__ . '/../vendor/autoload.php';
header('Content-Type: text/plain; charset=utf-8');

// ---------- Տնային 3 · նույն գաղտնաբառ, տարբեր հեշեր ----------
$a = password_hash('Ani-12345', PASSWORD_DEFAULT);
$b = password_hash('Ani-12345', PASSWORD_DEFAULT);
echo "== Նույն գաղտնաբառը երկու անգամ\n{$a}\n{$b}\n";
echo '$a === $b             → ' . var_export($a === $b, true) . "   (ամեն անգամ նոր salt)\n";
echo "password_verify(..., \$a) → " . var_export(password_verify('Ani-12345', $a), true) . "\n";
echo "password_verify(..., \$b) → " . var_export(password_verify('Ani-12345', $b), true) . "\n\n";

// ---------- Տնային 4 · արագություն ----------
$start = microtime(true);
password_hash('Ani-12345', PASSWORD_DEFAULT);
$bcrypt = (microtime(true) - $start) * 1000;

$start = microtime(true);
for ($i = 0; $i < 1000; $i++) {
    md5('Ani-12345');
}
$md5 = (microtime(true) - $start) * 1000 / 1000;

echo "== Ժամանակ\n";
printf("password_hash  %.1f մվ\nmd5            %.4f մվ\n", $bcrypt, $md5);
echo "→ Դանդաղը այստեղ լավ է․ հարձակվողը վայրկյանում միլիոնավոր md5 կփորձի, բայց ընդամենը տասնյակ bcrypt\n\n";

// ---------- Տնային 6 · session_regenerate_id ----------
session_start();
$before = session_id();
session_regenerate_id(true);
echo "== Սեսիա\nմինչև   {$before}\nհետո    " . session_id() . "\n";
echo "→ Մուտքից հետո հին id-ն այլևս չի աշխատում (session fixation)\n";
