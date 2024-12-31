<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Storage;

class Store extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // --------------- Relationships ------------------
    public function user()
    {
        return $this->belongsTo(User::class, 'shop_owner');
    }

    public function services()
    {
        return $this->belongsToMany(Service::class, (new StoreService())->getTable())->withPivot(['store_id', 'service_id']);
    }

    public function variants()
    {
        return $this->hasMany(Variant::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    public function aditionalServices()
    {
        return $this->hasMany(Additional::class);
    }

    public function logo()
    {
        return $this->belongsTo(Media::class, 'logo_id');
    }

    public function banner()
    {
        return $this->belongsTo(Media::class, 'banner_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'store_id');
    }

    public function coupons()
    {
        return $this->hasMany(Coupon::class, 'store_id');
    }

    public function address()
    {
        return $this->hasOne(Address::class, 'store_id');
    }

    public function schedules()
    {
        return $this->hasMany(OrderSchedule::class);
    }

    public function drivers(): HasMany
    {
        return $this->hasMany(Driver::class, 'store_id');
    }

    public function area(): HasOne
    {
        return $this->hasOne(Area::class);
    }

    public function logoPath(): Attribute
    {
        $logo = asset('images/dummy/dummy-user.png');

        if ($this->logo && Storage::exists($this->logo->src)) {
            $logo = Storage::url($this->logo->src);
        }

        return new Attribute(
            get: fn () => $logo
        );
    }

    public function bannerPath(): Attribute
    {
        $banner = asset('images/dummy/dummy-user.png');

        if ($this->banner && Storage::exists($this->banner->src)) {
            $banner = Storage::url($this->banner->src);
        }

        return new Attribute(
            get: fn () => $banner
        );
    }
}
