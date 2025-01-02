<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class SellerOrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $invoice = null;
        if ($this->invoice_path && Storage::exists($this->invoice_path)) {
            $invoice = Storage::url($this->invoice_path);
        }

        $address = $this->address?->house_no . ', ' . $this->address?->road_no . ', ' . $this->address?->address_line . ', ' . $this->address?->area . ($this->address?->post_code ? ', ' . $this->address?->post_code : '');

        $groupedProducts = $this->products->map(function ($product) {
            return [
                'service_name' => $product->service->name,
                'quantity' => $product->pivot->quantity,
                'name' => $product->name,
            ];
        })->groupBy('service_name')->values()->map(function ($serviceProducts) {
            return [
                'service_name' => $serviceProducts[0]['service_name'],
                'items' => $serviceProducts->map(function ($product) {
                    return (object)[
                        'quantity' => $product['quantity'],
                        'name' => $product['name'],
                    ];
                }),
            ];
        });

        $driverOrder = null;
        if ($this->drivers) {
            $driverOrder = $this->drivers()->where('is_completed', 0)->first();
        }

        return [
            'id' => $this->id,
            'order_code' => '#' . $this->prefix . $this->order_code,
            'payable_amount' => $this->payable_amount,
            'order_status' => $this->order_status,
            'payment_type' => $this->payment_type,
            'payment_status' => $this->payment_status,
            'pick_date' => Carbon::parse($this->pick_date)->format('d F, Y'),
            'delivery_date' => Carbon::parse($this->delivery_date)->format('d F, Y'),
            'ordered_at' => $this->created_at->format('Y-m-d h:i a'),
            'items' =>  $this->products->sum('pivot.quantity'),
            'user_name' => $this->customer?->user?->name,
            'user_mobile' => $this->customer?->user?->mobile,
            'user_profile' => $this->customer?->user?->profile_photo_path,
            'address' => $address,
            'products' => $groupedProducts,
            'rider' => RiderResource::make($driverOrder?->driver),
            'invoice_path' => $invoice,
        ];
    }
}
