<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RiderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $avaiableJobs = $this->driverOrders->where('is_completed', false)->count();
        return [
            'id' => $this->id,
            'code' => str_pad($this->id, 6, '0', STR_PAD_LEFT),
            'name' => $this->user?->name,
            'mobile' => $this->user?->mobile,
            'profile_photo' => $this->user?->profilePhotoPath,
            'vechicle_type' => $this->user?->vehicle_type,
            'available_jobs' => (int) $avaiableJobs,
            'is_active' => (bool) $this->user?->is_active,
        ];
    }
}
