<?php
declare(strict_types=1);

// Գլոբալ օգնականներ — Composer-ը միացնում է "files"-ով (PHP Դաս 14)
// Դասերի մեջ չենք դնում, որովհետև շաբլոններում կարճ անուն է պետք․ e($x), ոչ Html::escape($x)

const SITE_NAME = 'ՏեխնոՄարկետ';

// ---------- Ելք — PHP Դաս 5 ----------

function e(?string $text): string
{
    return htmlspecialchars($text ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function safeUrl(?string $url, string $fallback = '#'): string
{
    $url = trim($url ?? '');

    if ($url === '') {
        return $fallback;
    }

    if (str_starts_with($url, '/') && !str_starts_with($url, '//')) {
        return $url;
    }

    $scheme = strtolower((string) parse_url($url, PHP_URL_SCHEME));

    return in_array($scheme, ['http', 'https', 'mailto', 'tel'], true) ? $url : $fallback;
}

// ---------- Շաբլոններ — PHP Դաս 5, 15 ----------

/** Ամեն էջի layout-ին պետք եկող տվյալներ (զամբյուղ, օգտագործող)։ Laravel-ում՝ View::share (Դաս 19) */
function share(string $key, $value = null)
{
    static $shared = [];

    if (func_num_args() === 2) {
        $shared[$key] = $value;
    }

    return $key === '*' ? $shared : ($shared[$key] ?? null);
}

function render(string $view, array $data = []): string
{
    extract($data, EXTR_SKIP);          // չի վերագրում $view-ը — Դաս 15

    ob_start();

    try {
        require __DIR__ . "/../views/{$view}.php";
    } catch (Throwable $e) {
        ob_end_clean();                   // կիսատ HTML չենք թողնում
        throw $e;
    }

    return ob_get_clean();
}

function page(string $view, array $data = [], string $title = SITE_NAME, string $layout = 'layout'): string
{
    return render($layout, share('*') + [
        'title'   => $title,
        'content' => render($view, $data + share('*')),
    ]);
}

function url(string $path, array $query = []): string
{
    $url = '/' . ltrim($path, '/');
    $query = array_filter($query, fn($v): bool => $v !== null && $v !== '' && $v !== 'all');

    return $query === [] ? $url : $url . '?' . http_build_query($query);
}

/** Ընթացիկ էջ — aria-current-ի համար (HTML Դաս 18) */
function isCurrent(string $path, bool $prefix = false): bool
{
    $current = '/' . trim(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/', '/');
    $path = '/' . trim($path, '/');

    return $prefix ? str_starts_with($current, $path) : $current === $path;
}

function ariaCurrent(string $path, bool $prefix = false): string
{
    return isCurrent($path, $prefix) ? ' aria-current="page"' : '';
}

function formatPrice(int $amount): string
{
    return number_format($amount, 0, '.', "\u{00A0}") . "\u{00A0}֏";
}

// ---------- Ֆորմայի դաշտեր — PHP Դաս 6 ----------

function inputClass(array $errors, string $key, string $extra = ''): string
{
    return trim('input ' . $extra . (isset($errors[$key]) ? ' input--error' : ''));
}

function ariaError(array $errors, string $key): string
{
    return isset($errors[$key]) ? 'aria-invalid="true" aria-describedby="' . e($key) . '-error"' : '';
}

function fieldError(array $errors, string $key): string
{
    return isset($errors[$key])
        ? '<p class="field__error" id="' . e($key) . '-error">' . e($errors[$key]) . '</p>'
        : '';
}

// ---------- Սեսիա — PHP Դաս 7 ----------

function flash(string $key, ?string $message = null): ?string
{
    if ($message !== null) {
        $_SESSION['_flash'][$key] = $message;
        return null;
    }

    $value = $_SESSION['_flash'][$key] ?? null;
    unset($_SESSION['_flash'][$key]);

    return $value;
}

function safeBack(?string $back, string $default = '/'): string
{
    $back = (string) $back;

    return str_starts_with($back, '/') && !str_starts_with($back, '//') ? $back : $default;
}

function theme(): ?string
{
    $theme = $_COOKIE['theme'] ?? null;

    return in_array($theme, ['light', 'dark'], true) ? $theme : null;
}

// ---------- CSRF — PHP Դաս 16 ----------

function csrf_token(): string
{
    return $_SESSION['_csrf_token'] ??= bin2hex(random_bytes(32));
}

function csrf_field(): string
{
    return '<input type="hidden" name="_token" value="' . e(csrf_token()) . '">';
}

function csrf_verify(?string $token): bool
{
    $stored = $_SESSION['_csrf_token'] ?? '';

    if ($stored === '' || $token === null || $token === '') {
        return false;
    }

    return hash_equals($stored, $token);        // ⭐ ոչ ===
}

// ---------- Slug — PHP Դաս 11 ----------

function slugify(string $text): string
{
    $map = [
        'ու' => 'u',
        'ա' => 'a', 'բ' => 'b', 'գ' => 'g', 'դ' => 'd', 'ե' => 'e', 'զ' => 'z',
        'է' => 'e', 'ը' => 'y', 'թ' => 't', 'ժ' => 'zh', 'ի' => 'i', 'լ' => 'l',
        'խ' => 'kh', 'ծ' => 'ts', 'կ' => 'k', 'հ' => 'h', 'ձ' => 'dz', 'ղ' => 'gh',
        'ճ' => 'ch', 'մ' => 'm', 'յ' => 'y', 'ն' => 'n', 'շ' => 'sh', 'ո' => 'o',
        'չ' => 'ch', 'պ' => 'p', 'ջ' => 'j', 'ռ' => 'r', 'ս' => 's', 'վ' => 'v',
        'տ' => 't', 'ր' => 'r', 'ց' => 'ts', 'ւ' => 'w', 'փ' => 'p', 'ք' => 'q',
        'օ' => 'o', 'ֆ' => 'f', 'և' => 'ev',
    ];

    $text = mb_strtolower(trim($text));
    $text = strtr($text, $map);
    $text = preg_replace('/[^a-z0-9]+/', '-', $text);

    return trim($text, '-') ?: 'item';
}
