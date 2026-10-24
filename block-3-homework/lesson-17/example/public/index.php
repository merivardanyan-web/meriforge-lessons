<?php
declare(strict_types=1);

// Ամբողջ կայքը՝ մեկ մուտքով — PHP Դաս 15, 16, 17

use App\Exceptions\HttpException;
use App\Http\Request;
use App\Http\Response;
use App\Http\Router;

if (PHP_SAPI === 'cli-server' && is_file(__DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH))) {
    return false;
}

require_once __DIR__ . '/../bootstrap.php';

$router = new Router();
require __DIR__ . '/../routes.php';

$request = null;

try {
    $request = Request::fromGlobals();

    // 1. CSRF — ամեն վիճակ փոխող հարցում։ Ֆորմայից՝ _token, fetch-ից՝ X-CSRF-Token (Դաս 16, 17)
    if ($request->method !== 'GET' && $request->method !== 'HEAD') {
        if (!csrf_verify($request->input('_token') ?: $request->header('X-CSRF-Token'))) {
            throw new HttpException('Ձևաթուղթը ժամկետանց է։ Թարմացրու էջը և փորձիր կրկին։', 419);
        }
    }

    // 2. Ադմին գոտի — մուտք գործա՞ծ է
    $isAdminArea = $request->path === '/admin' || str_starts_with($request->path, '/admin/');

    if ($isAdminArea && !auth()->check()) {
        if ($request->method === 'GET') {
            $_SESSION['intended'] = $request->path;      // մուտքից հետո՝ հենց այստեղ
        }
        $response = Response::redirect('/login');

    // 3. Մուտք գործած է, բայց անձնակազմի՞ց է ⭐ (մուտքը ≠ իրավունք)
    } elseif ($isAdminArea && !auth()->isStaff()) {
        throw new HttpException('Այս էջը միայն ակումբի կազմակերպիչների համար է', 403);

    } else {
        $response = $router->dispatch($request);
    }
} catch (HttpException $e) {
    // API-ն ստանում է JSON, բրաուզերը՝ HTML էջ (Դաս 17)
    $response = $request === null || $request->expectsJson()
        ? Response::json(['error' => ['message' => $e->getMessage()]], $e->status)
        : Response::html(page('pages/error', ['code' => $e->status, 'message' => $e->getMessage()], (string) $e->status), $e->status);
} catch (Throwable $e) {
    error_log((string) $e);
    $message = $debug ? $e->getMessage() : 'Տեխնիկական խնդիր։ Փորձիր ավելի ուշ։';
    $response = $request?->expectsJson()
        ? Response::json(['error' => ['message' => $message]], 500)
        : Response::html(page('pages/error', ['code' => 500, 'message' => $message], '500'), 500);
}

$response->send();
