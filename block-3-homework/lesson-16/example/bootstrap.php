<?php
declare(strict_types=1);

// Կախվածությունների հավաքում — PHP Դաս 14, 15, 16
// Միացնում է public/index.php-ն, հետո routes.php-ն։ Էջերը (index.php, book.php...) այլևս չկան

use App\Controllers\Admin\BookController as AdminBookController;
use App\Controllers\AuthController;
use App\Controllers\RegisterController;
use App\Controllers\BookController;
use App\Controllers\ListController;
use App\Controllers\PageController;
use App\Repositories\BookRepository;
use App\Repositories\GenreRepository;
use App\Repositories\SessionRepository;
use App\Repositories\UserRepository;
use App\Services\Auth;
use App\Services\LoginThrottle;
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
$users    = new UserRepository($pdo);

const UPLOAD_DIR = __DIR__ . '/public/images/uploads';

// --- Կարդալու ցուցակը ---
$_SESSION['list'] ??= [];

/** Մեկ օբյեկտ ամբողջ հարցման համար․ ListController-ը և շաբլոնները ստանում են ՆՈՒՅՆԸ */
function readingList(): ReadingList
{
    static $list = null;

    return $list ??= new ReadingList($_SESSION['list']);
}

// --- Մուտք — Դաս 16 ---
/** Ընթացիկ անդամը՝ շաբլոնների համար․ նույն օբյեկտը, ինչ կոնտրոլերներում */
function auth(?Auth $instance = null): Auth
{
    static $auth = null;

    if ($instance !== null) {
        $auth = $instance;           // bootstrap-ը դնում է մեկ անգամ՝ ներքևում
    }

    return $auth;
}

auth(new Auth($users));

// --- Կոնտրոլերներ՝ իրենց կախվածություններով ---
$bookController = new BookController($books, $genres, $sessions);
$listController = new ListController($books, readingList());
$pageController = new PageController();
$adminBooks     = new AdminBookController($books, $genres, auth());
$authController = new AuthController(auth(), new LoginThrottle());
$registerController = new RegisterController($users, auth());
