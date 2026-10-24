<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** Դաս 17-ի Book::toArray()-ը — PHP Դաս 20, տնային 7 */
class BookResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'        => $this->id,
            'title'     => $this->title,
            'slug'      => $this->slug,
            'author'    => $this->author,
            'year'      => $this->year,
            'genre'     => $this->whenLoaded('genre', fn () => $this->genre?->name),
            'pages'     => $this->pages,
            'read'      => $this->read_pages,
            'progress'  => $this->progress(),
            'status'    => $this->status(),
            'format'    => $this->formatLabel(),
            'time_left' => duration($this->minutesLeft()),
            'color'     => $this->color,
            'cover'     => $this->coverUrl(),
            'url'       => route('book.show', $this->resource),
        ];
    }
}
