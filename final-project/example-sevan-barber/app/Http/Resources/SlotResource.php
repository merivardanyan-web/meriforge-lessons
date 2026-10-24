<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** Միայն այն, ինչ պետք է JS-ին․ ոչ մի email, ոչ մի հեռախոս */
class SlotResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'        => $this->id,
            'time'      => $this->starts_at->format('H:i'),
            'starts_at' => $this->starts_at->toIso8601String(),
            'staff'     => ['id' => $this->staff->id, 'name' => $this->staff->name],
        ];
    }
}
