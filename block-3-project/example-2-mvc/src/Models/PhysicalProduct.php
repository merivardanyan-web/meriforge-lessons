<?php
declare(strict_types=1);

namespace App\Models;

class PhysicalProduct extends Product implements Shippable
{
    private const MAX_QTY = 10;

    public function __construct(
        int $id,
        string $name,
        string $slug,
        int $price,
        int $discount = 0,
        ?string $specs = null,
        ?string $description = null,
        ?string $image = null,
        ?Category $category = null,
        string $createdAt = '',
        bool $isActive = true,
        public readonly int $stock = 0,
        public readonly int $weightGrams = 0,
    ) {
        // ⭐ առանց սրա $name-ը, $price-ը... չեն լրացվի — PHP Դաս 13
        parent::__construct($id, $name, $slug, $price, $discount, $specs, $description, $image, $category, $createdAt, $isActive);
    }

    public static function fromRow(array $row): static
    {
        // [...] → անվանված արգումենտներ․ բանալին = պարամետրի անունը
        return new static(...parent::baseFields($row) + [
            'stock'       => (int) ($row['stock'] ?? 0),
            'weightGrams' => (int) ($row['weight'] ?? 0),
        ]);
    }

    public function inStock(): bool
    {
        return $this->stock > 0;
    }

    public function isLowStock(): bool
    {
        return $this->stock > 0 && $this->stock <= 3;
    }

    public function maxQty(): int
    {
        return min(self::MAX_QTY, $this->stock);
    }

    public function typeLabel(): string
    {
        return 'Ապրանք';
    }

    public function weight(): int
    {
        return $this->weightGrams;
    }

    /** Ծանր ապրանքը (5 կգ-ից) առաքվում է թանկ — PHP Դաս 13 */
    public function shippingCost(): int
    {
        return $this->weightGrams > 5000 ? 3000 : 1000;
    }
}
