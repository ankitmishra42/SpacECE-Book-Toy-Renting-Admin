<?php

namespace App\Http\Controllers\Web\Products;

use App\Enums\DiscountType;
use App\Http\Controllers\Controller;
use App\Http\Requests\CouponRequest;
use App\Models\AppSetting;
use App\Models\Coupon;
use App\Repositories\CouponRepository;
use App\Repositories\DeviceKeyRepository;
use App\Repositories\StoreRepository;
use App\Repositories\UserRepository;
use App\Services\NotificationServices;
use Carbon\Carbon;

class CouponController extends Controller
{
    public function index()
    {
        $currency = AppSetting::first()?->currency ?? '$';
        $user = (new UserRepository())->find(auth()->id());

        $coupons = (new CouponRepository())->query()->when($user->hasRole('store'), function ($query) use ($user) {
            $query->where('store_id', $user->store->id);
        })->get();
        $stores = (new StoreRepository())->getAll();

        return view('coupon.index', compact('coupons', 'currency', 'stores'));
    }

    public function create()
    {
        $currency = AppSetting::first()?->currency ?? '$';
        $discountTypes = DiscountType::cases();

        return view('coupon.create', compact('discountTypes', 'currency'));
    }

    public function store(CouponRequest $request)
    {
        (new CouponRepository())->storeByRequest($request);

        if ($request->notify) {

            $expiredAt = Carbon::parse($request->expired_date . ' ' . $request->expired_time . ':00')->format('M d, Y h:i a');
            $discount = $request->discount_type == 'amount' ? config('enums.currency')[0] . $request->discount : $request->discount . '%';

            $message = 'Hello Mr / Mrs. You have been given a coupon from ' . config('app.name') . '. By using this you will get ' . $discount . ' discount on your order. The order will expire' . $expiredAt;

            $keys = (new DeviceKeyRepository())->getAll()->pluck('key')->toArray();

            $title = 'Coupon discount';

            NotificationServices::sendNotification($message, $keys, $title);
        }

        return redirect()->route('coupon.index')->with('success', 'Coupon is added successfully.');
    }

    public function edit(Coupon $coupon)
    {
        $currency = AppSetting::first()?->currency ?? '$';
        $discountTypes = DiscountType::cases();

        return view('coupon.edit', compact('coupon', 'discountTypes', 'currency'));
    }

    public function update(CouponRequest $request, Coupon $coupon)
    {
        (new CouponRepository())->updateByRequest($request, $coupon);

        if ($request->notify) {

            $expiredAt = Carbon::parse($request->expired_date . ' ' . $request->expired_time . ':00')->format('M d, Y h:i a');
            $discount = $request->discount_type == 'amount' ? config('enums.currency')[0] . $request->discount : $request->discount . '%';

            $message = "Hello Mr / Mrs.\nYou have been given a coupon from" . config('app.name') . '. By using this you will get ' . $discount . " discount on your order.\nThe order will expire " . $expiredAt . ".\nYour Coupon is: " . $request->code;

            $keys = (new DeviceKeyRepository())->getAll()->pluck('key')->toArray();
            $title = 'Coupon discount';

            NotificationServices::sendNotification($message, $keys, $title);
        }

        return redirect()->route('coupon.index')->with('success', 'Coupon is updated successfully.');
    }
}
