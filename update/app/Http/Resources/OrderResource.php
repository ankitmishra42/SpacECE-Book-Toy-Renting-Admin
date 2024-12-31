<?php

namespace App\Http\Resources;

use App\Enums\OrderStatus;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $quantity = collect([]);
        foreach ($this->products as $product) {
            $quantity[$product->id] = $product->pivot->quantity;
        }
        $shop = null;
        $driverOrder = null;
        if ($this->order_status->value != OrderStatus::DELIVERED->value) {
        if ($this->drivers) {
            $driverOrder = $this->drivers()->where('is_completed', 0)->first();
        }
            $shop = StoreResource::make($this->store);
        }

        if ($this->order_status->value == OrderStatus::DELIVERED->value) {
            $driverOrder = null;
        }

        $invoice = null;
        if ($this->invoice_path && Storage::exists($this->invoice_path)) {
            $invoice = Storage::url($this->invoice_path);
        }

        return [
            'id' => $this->id,
            'order_code' => $this->order_code,
            'driver_status' =>  $driverOrder ?  $driverOrder->assign_for : null,
            'drivers' => $driverOrder ? true : false,
            'discount' => $this->discount,
            'payable_amount' =>  (float) $this->payable_amount,
            'total_amount' =>  (float) $this->total_amount,
            'delivery_charge' =>  (float) $this->delivery_charge,
            'order_status' => $this->order_status,
            'payment_status' => $this->payment_status,
            'payment_type' => $this->payment_type,
            'pick_date' => Carbon::parse($this->pick_date)->format('d F, Y'),
            'pick_hour' => $this->getTime(substr($this->pick_hour, 0, 2)),
            'delivery_date' => Carbon::parse($this->delivery_date)->format('d F, Y'),
            'delivery_hour' => $this->getTime(substr($this->delivery_hour, 0, 2)),
            'ordered_at' => $this->created_at->format('Y-m-d h:i a'),
            'rating' => $this->rating ? $this->rating->rating : null,
            'item' => (int) $this->products->count(),
            'address' => AddressResource::make($this->address),
            'products' => ProductResource::collection($this->products),
            'quantity' => $quantity,
            'payment' => $this->payment ? (new PaymentResource($this->payment)) : null,
            'shop' => $shop,
            'invoice_path' => $invoice,
            'instruction' => $this->instruction,
            'rider' => $driverOrder ? (object)[
                'id' => $driverOrder->id,
                'name' => $driverOrder->driver?->user?->name,
                'phone' => $driverOrder->driver?->user?->mobile,
                'profile_photo' => $driverOrder->driver?->user?->profilePhotoPath,
            ] : null
        ];
    }

    private function getTime($time)
    {
        $times = [
            '8' => '08-09:59',
            '9' => '08-09:59',
            '10' => '10-11:59',
            '11' => '10-11:59',
            '12' => '12-13:59',
            '13' => '12-13:59',
            '14' => '14-15:59',
            '15' => '14-15:59',
            '16' => '16-17:59',
            '17' => '16-17:59',
            '18' => '18-19:59',
            '19' => '18-19:59',
            '20' => '20-21:59',
            '21' => '20-21:59',
        ];
        foreach ($times as $key => $item) {
            if ($key == $time) {
                return $item;
            }
        }
    }
}
