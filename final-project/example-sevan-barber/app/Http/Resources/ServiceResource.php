<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'name'         => $this->name,
            'slug'         => $this->slug,
            'duration_min' => $this->duration_min,
            'price'        => $this->price,
            'price_text'   => dram($this->price),
            'url'          => route('services.show', $this),
        ];
    }
}
