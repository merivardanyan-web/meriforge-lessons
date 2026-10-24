<?php
declare(strict_types=1);

namespace App\Models;

/** Լիցենզիա, որ ուղարկվում է էլ. փոստով։ Shippable ՉԷ — PHP Դաս 13 */
class DigitalProduct extends Product
{
    public static function fromRow(array $row): static
    {
        return new static(...parent::baseFields($row));
    }

    public function inStock(): bool
    {
        return true;          // միշտ առկա է
    }

    public function maxQty(): int
    {
        return 5;             // մեկ պատվերում՝ առավելագույնը 5 լիցենզիա
    }

    public function typeLabel(): string
    {
        return 'Թվային լիցենզիա';
    }
}
