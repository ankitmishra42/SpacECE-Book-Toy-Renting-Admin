<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RiderOrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $address = $this->order?->address?->house_no . ', ' . $this->order?->address?->road_no . ', ' . $this->order?->address?->address_line . ', ' . $this->order?->address?->area . ($this->order?->address?->post_code ? ', ' . $this->order?->address?->post_code : '');

        return [
            'id' => $this->id,
            'assign_for' => $this->assign_for,
            'pickup_date' => $this->order->pick_date,
            'delivery_date' => $this->order->delivery_date,
            'payment_method' => $this->order->payment_type,
            'payable_amount' => $this->order->payable_amount ,
            'user_name' => $this->order->customer?->user?->name,
            'address' => $address,
        ];
    }
}
