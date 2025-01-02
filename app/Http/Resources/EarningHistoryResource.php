<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EarningHistoryResource extends JsonResource
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
            'order_code' => '#' . $this->prefix . $this->order_code,
            'pick_date' => $this->pick_date,
            'delivery_date' => $this->delivery_date,
            'payable_amount' => $this->payable_amount,
            'payment_method' => $this->payment_type,
            'order_status' => $this->order_status
        ];
    }
}
