<?php
declare(strict_types=1);

namespace App\Models;

// Ինչ կարելի է տալ ակումբի անդամին — PHP Դաս 13
// Ինտերֆեյսը պայմանագիր է․ «ես ունեմ այս մեթոդները»։ Իրագործումը՝ դասում
interface Lendable
{
    public function isLent(): bool;

    public function lentTo(): ?string;
}
