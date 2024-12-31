<?php

namespace App\Repositories;

use App\Http\Requests\CouponRequest;
use App\Models\Coupon;

class CouponRepository extends Repository
{
    public function model()
    {
        return Coupon::class;
    }

    public function getAll()
    {
        return $this->query()->latest('id')->get();
    }

    public function storeByRequest(CouponRequest $request): Coupon
    {
        $startedAt = $request->start_date.' '.$request->start_time.':00';
        $expiredAt = $request->expired_date.' '.$request->expired_time.':00';

        return $this->create([
            'code' => $request->code,
            'type' => $request->discount_type,
            'discount' => $request->discount,
            'started_at' => $startedAt,
            'min_amount' => $request->min_amount,
            'expired_at' => $expiredAt,
            'store_id' => auth()->user()->store->id,
        ]);
    }

    public function updateByRequest(CouponRequest $request, Coupon $coupon): Coupon
    {
        $startedAt = $request->start_date.' '.$request->start_time.':00';
        $expiredAt = $request->expired_date.' '.$request->expired_time.':00';

        $coupon->update([
            'code' => $request->code,
            'type' => $request->discount_type,
            'discount' => $request->discount,
            'started_at' => $startedAt,
            'min_amount' => $request->min_amount,
            'expired_at' => $expiredAt,
        ]);

        return $coupon;
    }

    public function findByCoupon($coupon, $storeId, $amount)
    {
        return $this->query()->where('code', $coupon)->where('store_id', $storeId)->isValid($amount)->first();
    }

    public function findById($id, $storeId, $amount)
    {
        return $this->query()->where('id', $id)->where('store_id', $storeId)->isValid($amount)->first();
    }
}
