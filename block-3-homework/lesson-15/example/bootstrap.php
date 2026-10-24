<?php
declare(strict_types=1);

// Կախվածությունների հավաքում — PHP Դաս 14, 15
// Միացնում է public/index.php-ն, հետո routes.php-ն։ Էջերը (index.php, book.php...) այլևս չկան

use App\Controllers\Admin\BookController as AdminBookController;
use App\Controllers\BookController;
use App\Controllers\ListController;
use App\Controllers\PageController;
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

$debug = ($_ENV['APP_DEBUG'] ?? 'false') === 'true';
ini_set('display_errors', $debug ? '1' : '0');
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

const UPLOAD_DIR = __DIR__ . '/public/images/uploads';

// --- Կարդալու ցուցակը ---
$_SESSION['list'] ??= [];

/** Մեկ օբյեկտ ամբողջ հարցման համար․ ListController-ը և շաբլոնները ստանում են ՆՈՒՅՆԸ */
function readingList(): ReadingList
{
    static $list = null;

    return $list ??= new ReadingList($_SESSION['list']);
}

// --- Կոնտրոլերներ՝ իրենց կախվածություններով ---
$bookController = new BookController($books, $genres, $sessions);
$listController = new ListController($books, readingList());
$pageController = new PageController();
$adminBooks     = new AdminBookController($books, $genres);
