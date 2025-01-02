<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RiderDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'code' => str_pad($this->id, 6, '0', STR_PAD_LEFT),
            'completed_job' => (int) 0,
            'cash_collected' => number_format(0, 2),
            'user' => new UserResource($this->user),
        ];
    }
}
