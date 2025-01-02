<?php

namespace App\Models;

use App\Enums\DiscountType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Coupon extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'type' => DiscountType::class,
    ];

    //========= relationships ==============
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, (new CouponUser())->getTable())
            ->withTimestamps();
    }

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id');
    }

    //----------- Scope
    public function scopeIsValid(Builder $builder, $minAmount): Builder
    {
        return $builder->where('expired_at', '>=', now())->where('started_at', '<=', now());
    }

    public static function calculate($total, $coupon)
    {
        if (! $coupon) {
            return 0;
        }
        $discount = $coupon->discount;
        if ($coupon->type->value == 'Percentage') {
            $discount = ($total / 100) * $discount;
        }

        return $discount;
    }
}
