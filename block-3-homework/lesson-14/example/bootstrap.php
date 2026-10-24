<?php
declare(strict_types=1);

// Մեկ մուտք ամեն էջի համար — PHP Դաս 07, 10, 14
// Դաս 14․ ոչ մի require_once դասերի համար․ Composer-ը բեռնում է դրանք, երբ առաջին անգամ պետք են

use App\Repositories\BookRepository;
use App\Repositories\GenreRepository;
use App\Repositories\SessionRepository;
use App\Services\ReadingList;
use App\Support\Database;

require_once __DIR__ . '/vendor/autoload.php';       // ⭐ մեկ տող՝ բոլոր դասերի և helpers.php-ի համար

// --- Կոնֆիգուրացիա .env-ից (config.php-ի փոխարեն) ---
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
$dotenv->required(['DB_HOST', 'DB_NAME', 'DB_USER']);

ini_set('display_errors', ($_ENV['APP_DEBUG'] ?? 'false') === 'true' ? '1' : '0');
error_reporting(E_ALL);

session_start([
    'cookie_httponly' => true,
    'cookie_samesite' => 'Lax',
    'use_strict_mode' => true,
]);

// --- Բազա և Repository-ներ ---
$pdo = Database::connect([
    'host'     => $_ENV['DB_HOST'],
    'name'     => $_ENV['DB_NAME'],
    'user'     => $_ENV['DB_USER'],
    'password' => $_ENV['DB_PASS'] ?? '',
]);

$books    = new BookRepository($pdo);
$genres   = new GenreRepository($pdo);
$sessions = new SessionRepository($pdo);

// --- Կարդալու ցուցակը ---
$_SESSION['list'] ??= [];

function readingList(): ReadingList
{
    static $list = null;

    return $list ??= new ReadingList($_SESSION['list']);
}
