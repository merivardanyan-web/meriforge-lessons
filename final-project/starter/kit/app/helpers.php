<?php

// Գլոբալ օգնականներ — composer.json → "autoload": {"files": ["app/helpers.php"]}

if (! function_exists('dram')) {
    /** 7500 → «7 500 ֏» */
    function dram(int $amount): string
    {
        return number_format($amount, 0, '.', "\u{00A0}") . "\u{00A0}֏";    // չկոտրվող բացատ՝ «7 500» և «֏» նույն տողում
    }
}

if (! function_exists('safe_url')) {
    /** Միայն http, https, mailto, tel և ներքին ուղիներ — PHP Դաս 05 */
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

if (! function_exists('slugify')) {
    /** «Սամվել» → samvel — PHP Դաս 11 */
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

        return trim(preg_replace('/[^a-z0-9]+/', '-', $text), '-') ?: 'item';
    }
}
