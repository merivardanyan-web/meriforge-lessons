<?php
declare(strict_types=1);

// Ամբողջ կայքը՝ մեկ մուտքով — PHP Դաս 15, 16

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

try {
    $request = Request::fromGlobals();

    // 1. CSRF — ամեն վիճակ փոխող հարցում, ԱՌԱՋ ուղղորդումից (Դաս 16)
    if ($request->method !== 'GET' && $request->method !== 'HEAD') {
        if (!csrf_verify($request->input('_token'))) {
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
    $response = Response::html(page('pages/error', ['code' => $e->status, 'message' => $e->getMessage()], (string) $e->status), $e->status);
} catch (Throwable $e) {
    error_log((string) $e);
    $message = $debug ? $e->getMessage() : 'Տեխնիկական խնդիր։ Փորձիր ավելի ուշ։';
    $response = Response::html(page('pages/error', ['code' => 500, 'message' => $message], '500'), 500);
}

$response->send();
