<?php
declare(strict_types=1);

namespace App\Models;

/** Պայմանագիր՝ այն, ինչ առաքվում է — PHP Դաս 13 */
interface Shippable
{
    public function weight(): int;

    public function shippingCost(): int;
}
