<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** Դաս 17-ի Product::toArray()-ը՝ բառացի — PHP Դաս 20 */
class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'slug'        => $this->slug,
            'specs'       => $this->specs,
            'price'       => $this->price,
            'final_price' => $this->finalPrice(),
            'discount'    => $this->discount,
            'in_stock'    => $this->inStock(),       // bool, ոչ stock-ի քանակ ⭐
            'type'        => $this->type,
            'url'         => route('product.show', $this->resource),
            'image'       => $this->imageUrl(),
            'category'    => $this->whenLoaded('category', fn () => $this->category?->name),
        ];
    }
}
