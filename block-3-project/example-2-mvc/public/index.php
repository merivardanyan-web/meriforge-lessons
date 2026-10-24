<?php
declare(strict_types=1);

// Ամբողջ ծրագիրը մեկ մուտքով — PHP Դաս 15, 16, 17

use App\Exceptions\HttpException;
use App\Exceptions\ValidationException;
use App\Http\Request;
use App\Http\Response;
use App\Http\Router;

// php -S-ի համար (Laragon-ում սա անում է .htaccess-ը)․ իրական ֆայլը՝ css, js, նկար, տալիս ենք ուղիղ
if (PHP_SAPI === 'cli-server' && is_file(__DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH))) {
    return false;
}

require_once __DIR__ . '/../bootstrap.php';

$router = new Router();
require __DIR__ . '/../routes.php';

$request = null;

try {
    $request = Request::fromGlobals();
    share('request', $request);

    // 1. CSRF — ամեն վիճակ փոխող հարցում։ Ֆորմայից՝ _token, JS-ից՝ X-CSRF-Token — Դաս 16, 17
    if (!in_array($request->method, ['GET', 'HEAD', 'OPTIONS'], true)) {
        $token = $request->input('_token') ?: $request->header('X-CSRF-Token');

        if (!csrf_verify($token)) {
            throw new HttpException('Ձևաթուղթը ժամկետանց է։ Թարմացրեք էջը և փորձեք կրկին։', 419);
        }
    }

    // 2. Ադմին գոտի — մուտք գործա՞ծ է
    $isAdminArea = $request->path === '/admin' || str_starts_with($request->path, '/admin/');

    if ($isAdminArea && !$auth->check()) {
        if ($request->method === 'GET') {
            $_SESSION['intended'] = $request->path;     // մուտքից հետո՝ հենց այստեղ
        }
        $response = Response::redirect('/login');

    // 3. Մուտք գործած է, բայց աշխատակի՞ց է ⭐
    } elseif ($isAdminArea && !$auth->isStaff()) {
        throw new HttpException('Այս էջը հասանելի է միայն աշխատակիցներին', 403);

    } else {
        $response = $router->dispatch($request);
    }

} catch (HttpException $e) {
    $json = $request === null || $request->expectsJson();

    $response = $json
        ? Response::json([
            'error' => array_filter([
                'message' => $e->getMessage(),
                'errors'  => $e instanceof ValidationException ? $e->errors : null,
            ]),
        ], $e->status)
        : Response::html(page('pages/error', [
            'code'    => $e->status,
            'message' => $e->getMessage(),
        ], (string) $e->status), $e->status);

} catch (Throwable $e) {
    error_log((string) $e);                                  // ⭐ մանրամասները՝ լոգում, ոչ էկրանին

    $message = $debug ? $e->getMessage() : 'Տեխնիկական խնդիր։ Փորձեք ավելի ուշ։';

    $response = $request?->expectsJson()
        ? Response::json(['error' => ['message' => $message]], 500)
        : Response::html(page('pages/error', ['code' => 500, 'message' => $message], '500'), 500);
}

$response->send();
