<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $discount = null;
        if ($this->price > $this->discount_price && $this->discount_price > 0) {
            $discount = round((($this->price - $this->discount_price) * 100) / $this->price, 2);
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'name_bn' => $this->name_bn,
            'slug' => $this->slug,
            'current_price' =>  (float) $this->discount_price ? $this->discount_price : $this->price,
            'old_price' =>  (float) $this->discount_price ? $this->price : null,
            'description' => $this->description,
            'image_path' => $this->thumbnailPath,
            'discount_percentage' => $discount,
            'qrcode_url' => $this->qrcode_url ? Storage::url($this->qrcode_url) : null,
            'service' => (new ServiceResource($this->service)),
            'variant' => (new VariantResource($this->variant)),
        ];
    }
}
