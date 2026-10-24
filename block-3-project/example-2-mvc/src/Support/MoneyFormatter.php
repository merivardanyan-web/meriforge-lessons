<?php
declare(strict_types=1);

namespace App\Support;

/** «Ունի», ոչ «է»․ Product-ը օգտագործում է այն, չի ժառանգում — PHP Դաս 13 */
class MoneyFormatter
{
    public function format(int $amount): string
    {
        return number_format($amount, 0, '.', "\u{00A0}") . "\u{00A0}֏";
    }
}
