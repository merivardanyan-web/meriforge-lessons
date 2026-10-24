<?php
declare(strict_types=1);

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
    extract($data);             // ['title' => 'X'] → $title = 'X'

    ob_start();
    require __DIR__ . "/views/{$view}.php";

    return ob_get_clean();
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

function redirect(string $to): void
{
    header('Location: ' . $to, true, 303);
    exit;
}

/** Միայն ներքին հասցե՝ բաց վերահղման դեմ (Դաս 07-ի տնային, կետ 10) */
function safeBack(?string $back): string
{
    $back = (string) $back;

    if ($back === '' || str_starts_with($back, '//') || str_contains($back, ':')) {
        return 'index.php';
    }

    return $back;
}

function isDark(): bool
{
    return ($_COOKIE['theme'] ?? '') === 'dark';
}

/** Շապիկ՝ վերբեռնված նկարով, եթե կա։ Դաս 10-ից՝ books.cover սյունակում, ոչ սեսիայում */
function coverOf(array $book): ?string
{
    return $book['cover'] ?? null;
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

// ---------- Գրքեր — PHP Դաս 03, 04 ----------

// Դաս 10․ ֆիլտրը, որոնումը, դասավորումը և հաշվարկները անցան SQL-ին (books.php)
// Այստեղ մնացին միայն մեկ գրքի ֆունկցիաները, որ օգտագործում են շաբլոնները

function formatPages(int $pages): string
{
    return number_format($pages, 0, ".", " ") . " էջ";
}

function bookStatus(array $book): string
{
    if ($book['read'] <= 0) {          // Դաս 04․ բացասականը նույնպես «դեռ չեմ սկսել»
        return 'planned';
    }

    return $book['read'] >= $book['pages'] ? 'done' : 'reading';
}

function statusLabel(string $status): string
{
    return match ($status) {
        'done'    => 'Կարդացել եմ',
        'reading' => 'Կարդում եմ',
        'planned' => 'Կարդալու եմ',
        default   => 'Անհայտ',
    };
}

/**
 * Դաս 04-ի վերանայումից հետո․
 *  - pages = 0 → DivisionByZeroError → վերադարձնում ենք 0
 *  - read > pages (տառասխալ ադմինում) → 120% → սահմանափակում ենք 100-ով
 *  - read < 0 → -10% → 0
 */
function progress(array $book): int
{
    if ($book['pages'] <= 0) {
        return 0;
    }

    $percent = (int) ($book['read'] * 100 / $book['pages']);

    return max(0, min(100, $percent));
}

function requirePost(): void
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        redirect('index.php');
    }
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

// ---------- Էջավորում — PHP Դաս 10 ----------

/** Նույն ֆիլտրերը + այլ էջ․ ?q=Րաֆֆի&genre=&page=2 */
function pageUrl(array $filters, string $sort, int $page): string
{
    $url = 'index.php?q=' . urlencode($filters['q'])
         . '&genre=' . urlencode($filters['genre'])
         . '&status=' . urlencode($filters['status'])
         . '&min=' . ($filters['min'] ?? '')
         . '&max=' . ($filters['max'] ?? '')
         . '&sort=' . urlencode($sort);

    return $url . '&page=' . $page;
}
