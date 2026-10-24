<?php

// Գլոբալ օգնականներ — PHP Դաս 19-ի տնային
// composer.json → "autoload": { "files": ["app/helpers.php"] }, ապա composer dump-autoload

if (! function_exists('slugify')) {
    /** Դաս 11-ի հայերեն → լատինատառ քարտեզը։ Str::slug()-ը հայերենը պարզապես կջնջեր */
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
        $text = preg_replace('/[^a-z0-9]+/', '-', $text);

        return trim($text, '-');
    }
}

if (! function_exists('safe_url')) {
    /** Դաս 5-ի safeUrl()-ը։ {{ }}-ը փախցնում է, բայց javascript:-ը չի բռնում */
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

if (! function_exists('money')) {
    function money(int $amount): string
    {
        return number_format($amount, 0, '.', "\u{00A0}") . "\u{00A0}֏";
    }
}
