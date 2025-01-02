<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ReviewResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'rating' => (int) $this->rating,
            'content' => $this->content,
            'name' => $this->customer->name,
            'img' => $this->customer->profilePhotoPath,
            'date' => $this->created_at->format('F d, Y'),
        ];
    }
}
