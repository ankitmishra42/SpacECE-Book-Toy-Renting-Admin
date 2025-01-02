<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SellerProfileResource extends JsonResource
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
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'name' => $this->name,
            'email' => $this->email,
            'mobile' => $this->mobile,
            'gender' => $this->gender,
            'mobile_verified_at' => $this->mobile_verified_at,
            'profile_photo_path' => $this->profilePhotoPath,
            'date_of_birth' => $this->date_of_birth ? parse($this->date_of_birth, 'd/m/Y') : null,
            'join_date' => Carbon::parse($this->created_at)->format('d F, Y'),
            'store' => (object)[
                'is_active' => (bool) $this->is_active,
                'logo_path' => $this->store?->logoPath,
                'banner_path' => $this->store?->bannerPath,
                'prefix' => $this->store?->prifix,
                'shop_name' => $this->store?->name,
                'description' => $this->store?->description,
                'min_order_amount' => $this->store?->min_order_amount,
                'delivery_charge' => $this->store?->delivery_charge
            ]
        ];
    }
}
