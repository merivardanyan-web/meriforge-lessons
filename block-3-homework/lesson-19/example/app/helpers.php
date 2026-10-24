<?php

// Մեր գլոբալ օգնականները — PHP Դաս 19, տնային 6
// composer.json → "autoload": {"files": ["app/helpers.php"]}, ապա composer dump-autoload

if (! function_exists('safe_url')) {
    /** Դաս 05-ի safeUrl()-ը․ միայն http, https, mailto, tel և ներքին ուղիներ */
    function safe_url(?string $url, string $fallback = '#'): string
    {
        $url = trim($url ?? '');

        if ($url === '') {
            return $fallback;
        }

        if (str_starts_with($url, '/') && ! str_starts_with($url, '//')) {
            return $url;
        }

        $scheme = strtolower((string) parse_url($url, PHP_URL_SCHEME));

        return in_array($scheme, ['http', 'https', 'mailto', 'tel'], true) ? $url : $fallback;
    }
}

if (! function_exists('pages')) {
    /** 1 132 էջ — Դաս 03-ի formatPages() */
    function pages(int $pages): string
    {
        return number_format($pages, 0, '.', ' ') . ' էջ';
    }
}
