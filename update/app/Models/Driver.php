<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Driver extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orders():HasMany
    {
        return $this->hasMany(DriverOrder::class, 'driver_id');
    }

    public function driverDevices()
    {
        return $this->hasMany(DriverDeviceKey::class);
    }

    public function driverOrders(): HasMany
    {
        return $this->hasMany(DriverOrder::class, 'driver_id');
    }

    // --------- scope ---------------
    public function scopeIsApprove(Builder $builder, $isApprove = true): Builder
    {
        return $builder->where('is_approve', $isApprove);
    }
}
