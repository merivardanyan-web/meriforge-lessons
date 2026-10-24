<?php
declare(strict_types=1);

// Գլոբալ օգնականներ — PHP Դաս 05–12, Դաս 14-ից՝ Composer-ի "files"-ով
// Դասերը՝ src/-ի թղթապանակներում, իսկ այս փոքր ֆունկցիաները մնում են ֆունկցիաներ․
// e()-ն ամեն շաբլոնում է, \App\Support\Html::escape()-ը կլիներ ավելորդ երկար

use App\Models\Book;
use App\Models\Listenable;

const SITE_NAME  = 'Անի Սարգսյան';
const SITE_EMAIL = 'ani@example.com';
const SITE_CITY  = 'Սևան';

// ---------- Ելք — PHP Դաս 05 ----------

function e(?string $text): string
{
    return htmlspecialchars($text ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/** Միայն http, https, mailto, tel և ներքին ուղիներ */
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

function render(string $view, array $data = []): string
{
    extract($data, EXTR_SKIP);          // EXTR_SKIP․ ['view' => ...]-ը չի վերագրում $view-ը (Դաս 15)

    ob_start();
    require __DIR__ . "/../views/{$view}.php";     // src/-ից մեկ մակարդակ վեր

    return ob_get_clean();
}

/** Էջ՝ layout-ի մեջ․ կոնտրոլերները կանչում են սա — PHP Դաս 15 */
function page(string $view, array $data, string $title, string $active = '', string $layout = 'layout'): string
{
    return render($layout, [
        'title'   => $title,
        'active'  => $active,
        'content' => render($view, $data),
    ]);
}

/** url('/', ['q' => 'Րաֆֆի', 'page' => 2]) → /?q=%D5%90...&page=2։ Դատարկները դուրս են մնում */
function url(string $path, array $query = []): string
{
    $query = array_filter($query, fn($v): bool => $v !== null && $v !== '');

    return $query === [] ? $path : $path . '?' . http_build_query($query);
}

/** Ընթացիկ էջ՝ aria-current-ի համար (տնային 4, HTML Դաս 18) */
function isCurrent(string $path, bool $prefix = false): bool
{
    $current = '/' . trim(parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/', '/');

    return $prefix ? str_starts_with($current, $path) : $current === $path;
}

function ariaCurrent(string $path, bool $prefix = false): string
{
    return isCurrent($path, $prefix) ? ' aria-current="page"' : '';
}

// ---------- Սեսիա և cookie — PHP Դաս 07 ----------

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

/** Միայն ներքին հասցե՝ /...-ով, բայց ոչ //evil.com (Դաս 07, Դաս 15-ից՝ բացարձակ ուղիներ) */
function safeBack(?string $back, string $default = '/'): string
{
    $back = (string) $back;

    return str_starts_with($back, '/') && !str_starts_with($back, '//') ? $back : $default;
}

function isDark(): bool
{
    return ($_COOKIE['theme'] ?? '') === 'dark';
}

/** @return array{0: ?string, 1: ?string}  [ֆայլի անուն, սխալ] */
function uploadImage(array $file, string $dir): array
{
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
        return [null, 'Ընտրիր ֆայլ'];
    }

    if ($file['size'] > 2 * 1024 * 1024) {
        return [null, 'Ֆայլը 2 ՄԲ-ից մեծ է'];
    }

    // ԻՐԱԿԱՆ տիպը՝ բովանդակությունից, ոչ անունից ⭐
    $mime = (new finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name']);
    $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'];

    if (!isset($allowed[$mime]) || getimagesize($file['tmp_name']) === false) {
        return [null, 'Միայն JPG, PNG կամ WebP նկար'];
    }

    $name = bin2hex(random_bytes(8)) . '.' . $allowed[$mime];      // անունը տալիս ենք ՄԵՆՔ

    if (!move_uploaded_file($file['tmp_name'], rtrim($dir, '/') . '/' . $name)) {
        return [null, 'Չհաջողվեց պահպանել'];
    }

    return [$name, null];
}

// ---------- Գրքեր — PHP Դաս 03 ----------
// Դաս 12․ bookStatus(), statusLabel(), progress()-ը դարձան Book-ի մեթոդներ

function formatPages(int $pages): string
{
    return number_format($pages, 0, ".", " ") . " էջ";
}

// ---------- Slug — PHP Դաս 11 ----------

/** «Սամվել» → samvel, «Գիքորը» → giqory */
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

    return trim($text, '-') ?: 'book';
}

// ---------- Էջավորում — PHP Դաս 10, 15 ----------

/** Նույն ֆիլտրերը + այլ էջ։ Դաս 15-ից՝ url()-ով և ուղով (/, /genre/..., /search) */
function pageUrl(string $path, array $filters, string $sort, int $page): string
{
    return url($path, [
        'q'      => $filters['q'],
        'genre'  => str_starts_with($path, '/genre/') ? null : $filters['genre'],
        'status' => $filters['status'],
        'min'    => $filters['min'],
        'max'    => $filters['max'],
        'sort'   => $sort !== 'progress' ? $sort : null,
        'page'   => $page > 1 ? $page : null,
    ]);
}

/** Ցուցակի աուդիոգրքերի ընդհանուր տևողությունը — Դաս 13, տնային 5
 *  @param Book[] $books */
function totalListening(array $books): int
{
    $total = 0;

    foreach ($books as $book) {
        if ($book instanceof Listenable) {
            $total += $book->durationMinutes();
        }
    }

    return $total;
}
