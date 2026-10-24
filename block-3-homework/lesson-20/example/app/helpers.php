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

if (! function_exists('slugify')) {
    /** Դաս 11-ի slugify()-ը․ «Սամվել» → samvel */
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

        $text = strtr(mb_strtolower(trim($text)), $map);

        return trim(preg_replace('/[^a-z0-9]+/', '-', $text), '-') ?: 'book';
    }
}

if (! function_exists('duration')) {
    /** Դաս 13-ի DurationFormatter-ը․ 200 → «3 ժ 20 ր» */
    function duration(int $minutes): string
    {
        if ($minutes <= 0) {
            return '0 ր';
        }

        $hours = intdiv($minutes, 60);
        $rest = $minutes % 60;

        return $hours === 0 ? "{$rest} ր" : ($rest === 0 ? "{$hours} ժ" : "{$hours} ժ {$rest} ր");
    }
}
