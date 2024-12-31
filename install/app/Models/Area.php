<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Area extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function latLngs(): BelongsToMany
    {
        return $this->belongsToMany(Area::class, 'areas_lat_lng')->withPivot('lat', 'lng');
    }
}
