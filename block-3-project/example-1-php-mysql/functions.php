<?php
declare(strict_types=1);

const SITE_NAME = 'ՏեխնոՄարկետ';
const FREE_SHIPPING_FROM = 50000;     // Բլոկ 2-ի նույն կանոնները
const REGION_SHIPPING = 2000;
const MAX_QTY = 10;

// ---------- Կոնֆիգ — PHP Դաս 10 ----------

/** Արժեք config.php-ից (տարբեր տիպեր, ուստի առանց վերադարձի տիպի) */
function config(string $key)
{
    static $config = null;
    $config ??= require __DIR__ . '/config.php';

    return $config[$key] ?? null;
}

// ---------- Ելքի փախցում — PHP Դաս 5 ----------

function e(?string $text): string
{
    return htmlspecialchars($text ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/** Միայն http, https, mailto, tel և ներքին ուղիներ — PHP Դաս 5 */
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

// ---------- Շաբլոններ — PHP Դաս 5 ----------

function render(string $view, array $data = []): string
{
    extract($data, EXTR_SKIP);

    ob_start();
    require __DIR__ . "/views/{$view}.php";

    return ob_get_clean();
}

/** Էջը layout-ի մեջ — Դաս 5-ի երկու render()-ը մեկ տեղում */
function renderPage(string $view, array $data, string $title, string $active = ''): void
{
    echo render('layout', [
        'title'   => $title,
        'active'  => $active,
        'content' => render($view, $data),
    ]);
}

function formatPrice(int $price): string
{
    return number_format($price, 0, '.', "\u{00A0}") . "\u{00A0}֏";
}

// ---------- Ֆորմայի դաշտերի սխալներ — PHP Դաս 6 ----------
// Նույն երեք կտորը ամեն դաշտում․ գրում ենք մեկ անգամ

function inputClass(array $errors, string $key, string $extra = ''): string
{
    return trim('input ' . $extra . (isset($errors[$key]) ? ' input--error' : ''));
}

function ariaError(array $errors, string $key): string
{
    return isset($errors[$key])
        ? 'aria-invalid="true" aria-describedby="' . e($key) . '-error"'
        : '';
}

function fieldError(array $errors, string $key): string
{
    return isset($errors[$key])
        ? '<p class="field__error" id="' . e($key) . '-error">' . e($errors[$key]) . '</p>'
        : '';
}

// ---------- Վերահղում և flash — PHP Դաս 6, 7 ----------

function redirect(string $to): void
{
    header('Location: ' . $to, true, 303);      // PRG — PHP Դաս 6
    exit;                                        // ⭐ առանց exit-ի կոդը կշարունակի կատարվել
}

/** Միայն ներքին ուղի — բաց վերահղման դեմ, PHP Դաս 7 */
function safeBack(?string $back, string $default = '/'): string
{
    $back = (string) $back;

    return str_starts_with($back, '/') && !str_starts_with($back, '//') ? $back : $default;
}

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

function requirePost(): void
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        redirect('/');
    }
}

function notFound(string $message = 'Էջը չի գտնվել'): void
{
    http_response_code(404);
    renderPage('pages/error', ['code' => 404, 'message' => $message], '404 — ' . SITE_NAME);
    exit;
}

// ---------- Թեմա cookie-ով — PHP Դաս 7 ----------

function theme(): ?string
{
    $theme = $_COOKIE['theme'] ?? null;

    return in_array($theme, ['light', 'dark'], true) ? $theme : null;   // սպիտակ ցուցակ
}

// ---------- Slug — PHP Դաս 11 ----------

function slugify(string $text): string
{
    $map = [
        'ու' => 'u',      // ⭐ երկտառ՝ strtr-ը նախ փորձում է ամենաերկար բանալին
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

// ---------- Նկարի վերբեռնում — PHP Դաս 7 ----------

/** @return array{0: ?string, 1: ?string}  [ֆայլի անուն, սխալ] */
function uploadImage(array $file, string $dir): array
{
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
        return [null, 'Ֆայլը չբեռնվեց'];
    }

    if ($file['size'] > 2 * 1024 * 1024) {
        return [null, 'Ֆայլը 2 ՄԲ-ից մեծ է'];
    }

    // Իրական տիպը՝ բովանդակությունից, ոչ անունից ⭐
    $mime = (new finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name']);
    $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];

    if (!isset($allowed[$mime]) || getimagesize($file['tmp_name']) === false) {
        return [null, 'Թույլատրվում են միայն JPG, PNG, WebP նկարներ'];
    }

    // Անունը տալիս ենք ՄԵՆՔ
    $name = bin2hex(random_bytes(8)) . '.' . $allowed[$mime];

    if (!move_uploaded_file($file['tmp_name'], rtrim($dir, '/') . '/' . $name)) {
        return [null, 'Չհաջողվեց պահպանել'];
    }

    return [$name, null];
}

// ---------- Դեբագ — PHP Դաս 1 ----------

function dd($data): void
{
    echo "<pre style='background:#1e252a;color:#e8eced;padding:14px;border-radius:8px'>";
    var_dump($data);
    echo '</pre>';
    exit;
}

/** Հասցե՝ query պարամետրերով։ Դատարկ արժեքները դուրս են մնում */
function urlWith(string $path, array $query = []): string
{
    $query = array_filter($query, fn($v): bool => $v !== null && $v !== '' && $v !== 'all');

    return $query === [] ? $path : $path . '?' . http_build_query($query);
}

function renderAdmin(string $view, array $data, string $title, string $active = ''): void
{
    echo render('layout-admin', [
        'title'   => $title,
        'active'  => $active,
        'content' => render($view, $data),
    ]);
}
