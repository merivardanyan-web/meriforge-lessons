<?php
declare(strict_types=1);

namespace App\Models;

use App\Support\MoneyFormatter;

/**
 * Ապրանք — PHP Դաս 12, 13, 17
 * abstract․ «ուղղակի ապրանք» չկա, կա ֆիզիկական կամ թվային
 */
abstract class Product
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly string $slug,
        public readonly int $price,
        public readonly int $discount = 0,
        public readonly ?string $specs = null,
        public readonly ?string $description = null,
        public readonly ?string $image = null,
        public readonly ?Category $category = null,
        public readonly string $createdAt = '',
        public readonly bool $isActive = true,
    ) {}

    // ---------- Ֆաբրիկա՝ ընտրում է դասը ----------

    public static function fromRow(array $row): static
    {
        return ($row['type'] ?? 'physical') === 'digital'
            ? DigitalProduct::fromRow($row)
            : PhysicalProduct::fromRow($row);
    }

    /** Ընդհանուր դաշտերը․ բանալիները՝ կոնստրուկտորի պարամետրերի անունները — PHP Դաս 12 */
    protected static function baseFields(array $row): array
    {
        return [
            'id'           => (int) $row['id'],
            'name'         => (string) $row['name'],
            'slug'         => (string) $row['slug'],
            'price'        => (int) $row['price'],                 // DECIMAL → int ⭐ Դաս 10
            'discount'     => (int) ($row['discount'] ?? 0),
            'specs'        => $row['specs'] ?? null,
            'description'  => $row['description'] ?? null,
            'image'        => $row['image'] ?? null,
            'category'     => Category::fromJoinedRow($row),
            'createdAt'    => (string) ($row['created_at'] ?? ''),
            'isActive'     => (bool) ($row['is_active'] ?? true),
        ];
    }

    // ---------- Ընդհանուր տրամաբանություն ----------

    public function finalPrice(): int
    {
        return (int) round($this->price * (100 - $this->discount) / 100);
    }

    public function hasDiscount(): bool
    {
        return $this->discount > 0;
    }

    public function savings(): int
    {
        return $this->price - $this->finalPrice();
    }

    /** 30 օրից նոր — PHP Դաս 12-ի տնային */
    public function isNew(): bool
    {
        return $this->createdAt !== '' && strtotime($this->createdAt) > strtotime('-30 days');
    }

    // ---------- Ներկայացում ----------

    public function formattedPrice(): string
    {
        return (new MoneyFormatter())->format($this->finalPrice());     // կոմպոզիցիա — Դաս 13
    }

    public function formattedOldPrice(): string
    {
        return (new MoneyFormatter())->format($this->price);
    }

    public function url(): string
    {
        return '/product/' . $this->slug;
    }

    public function imageUrl(): string
    {
        return '/images/' . ($this->image ?? 'placeholder.jpg');
    }

    /** Այն, ինչ թույլ ենք տալիս տեսնել դրսից — բացահայտ ցանկ ⭐ PHP Դաս 17 */
    public function toArray(): array
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'slug'        => $this->slug,
            'specs'       => $this->specs,
            'price'       => $this->price,
            'final_price' => $this->finalPrice(),
            'discount'    => $this->discount,
            'in_stock'    => $this->inStock(),
            'type'        => $this->typeLabel(),
            'category'    => $this->category?->name,
            'url'         => $this->url(),
            'image'       => $this->imageUrl(),
        ];
    }

    // ---------- Պարտադիր ժառանգներում ----------

    abstract public function inStock(): bool;

    /** Քանի՞ հատ կարելի է ավելացնել զամբյուղ */
    abstract public function maxQty(): int;

    abstract public function typeLabel(): string;
}
