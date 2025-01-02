<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Service extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // ----------Relations
    public function thumbnail()
    {
        return $this->belongsTo(Media::class, 'thumbnail_id');
    }

    public function stores()
    {
        return $this->belongsToMany(Store::class, 'store_services')->withPivot(['service_id', 'store_id']);
    }

    public function variants()
    {
        return $this->hasMany(Variant::class, 'service_id');
    }

    public function aditionalServices()
    {
        return $this->hasMany(Additional::class);
    }

    public function additionals()
    {
        return $this->belongsToMany(Additional::class, AdditionalService::class);
    }

    // --------- Attributes
    public function getThumbnailPathAttribute()
    {
        if ($this->thumbnail && Storage::exists($this->thumbnail->src)) {
            return Storage::url($this->thumbnail->src);
        }

        return asset('images/dummy/dummy-placeholder.png');

    }

    //---------- Scopes
    public function scopeIsActive(Builder $builder)
    {
        return $builder->where('is_active', true);
    }
}
