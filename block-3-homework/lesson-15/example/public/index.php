<?php
declare(strict_types=1);

// Ամբողջ կայքը՝ մեկ մուտքով — PHP Դաս 15
// Laragon-ում Document Root-ը՝ public/։ .env-ը, src/-ը, views/-ը այլևս հասանելի չեն բրաուզերից

use App\Exceptions\HttpException;
use App\Http\Request;
use App\Http\Response;
use App\Http\Router;

// php -S-ի համար (Laragon-ում սա անում է .htaccess-ը)․ իրական ֆայլը՝ css, նկար, տալիս ենք ուղիղ
if (PHP_SAPI === 'cli-server' && is_file(__DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH))) {
    return false;
}

require_once __DIR__ . '/../bootstrap.php';

$router = new Router();
require __DIR__ . '/../routes.php';

try {
    $response = $router->dispatch(Request::fromGlobals());
} catch (HttpException $e) {
    $response = Response::html(page('pages/error', ['code' => $e->status, 'message' => $e->getMessage()], (string) $e->status), $e->status);
} catch (Throwable $e) {
    error_log((string) $e);                       // ⭐ մանրամասները՝ լոգում, ոչ էկրանին
    $message = $debug ? $e->getMessage() : 'Տեխնիկական խնդիր։ Փորձիր ավելի ուշ։';
    $response = Response::html(page('pages/error', ['code' => 500, 'message' => $message], '500'), 500);
}

$response->send();
