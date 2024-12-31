<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    //------------ Relations
    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function variant()
    {
        return $this->belongsTo(Variant::class);
    }

    public function thumbnail()
    {
        return $this->belongsTo(Media::class);
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class, (new OrderProduct())->getTable())
            ->withPivot('quantity')
            ->withTimestamps();
    }

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id');
    }

    //------------ Attributes
    public function getThumbnailPathAttribute()
    {
        if ($this->thumbnail && Storage::exists($this->thumbnail->src)) {
            return Storage::url($this->thumbnail->src);
        }

        return asset('images/dummy/dummy-placeholder.png');
    }

    //----------- Scopes
    public function scopeIsActive(Builder $builder, bool $activity = true)
    {
        return $builder->where('is_active', $activity);
    }
}
