<?php
declare(strict_types=1);

// Կախվածությունների հավաքում — PHP Դաս 14, 15, 16, 17
// public/index.php-ն միացնում է այս ֆայլը, հետո routes.php-ն

use App\Controllers\AccountController;
use App\Controllers\Admin\OrderController as AdminOrderController;
use App\Controllers\Admin\ProductController as AdminProductController;
use App\Controllers\Api\CartApiController;
use App\Controllers\Api\ProductApiController;
use App\Controllers\AuthController;
use App\Controllers\CartController;
use App\Controllers\CheckoutController;
use App\Controllers\ContactController;
use App\Controllers\HomeController;
use App\Controllers\ProductController;
use App\Controllers\RegisterController;
use App\Repositories\CategoryRepository;
use App\Repositories\OrderRepository;
use App\Repositories\ProductRepository;
use App\Repositories\UserRepository;
use App\Services\Auth;
use App\Services\Cart;
use App\Services\ImageUploader;
use App\Services\LoginThrottle;
use App\Support\Database;

require_once __DIR__ . '/vendor/autoload.php';       // ⭐ մեկ տող՝ բոլոր դասերի համար

// --- Կոնֆիգուրացիա .env-ից ---
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
$dotenv->required(['DB_HOST', 'DB_NAME', 'DB_USER']);

$debug = ($_ENV['APP_DEBUG'] ?? 'false') === 'true';
ini_set('display_errors', $debug ? '1' : '0');
error_reporting(E_ALL);

// --- Սեսիա — PHP Դաս 7 ---
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

$products   = new ProductRepository($pdo);
$categories = new CategoryRepository($pdo);
$orders     = new OrderRepository($pdo);
$users      = new UserRepository($pdo);

// --- Ծառայություններ ---
$_SESSION['cart'] ??= [];
$cart = new Cart($_SESSION['cart']);
$auth = new Auth($users);

// Layout-ին պետք են ամեն էջում — PHP Դաս 15-ի page()
share('auth', $auth);
share('cart', $cart);
share('categories', $categories->all());

// --- Կոնտրոլերներ ---
$homeController     = new HomeController($products);
$productController  = new ProductController($products, $categories);
$cartController     = new CartController($products, $cart);
$checkoutController = new CheckoutController($products, $orders, $cart, $auth);
$contactController  = new ContactController();
$authController     = new AuthController($auth, new LoginThrottle());
$registerController = new RegisterController($users, $auth);
$accountController  = new AccountController($orders, $auth);
$adminProducts      = new AdminProductController($products, $categories, new ImageUploader(__DIR__ . '/public/images/uploads'), $auth);
$adminOrders        = new AdminOrderController($orders);
$productApi         = new ProductApiController($products, $categories);
$cartApi            = new CartApiController($products, $cart);
