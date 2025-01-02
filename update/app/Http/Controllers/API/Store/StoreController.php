<?php

namespace App\Http\Controllers\API\Store;

use App\Http\Controllers\Controller;
use App\Http\Resources\ReviewResource;
use App\Http\Resources\StoreResource;
use App\Models\Store;
use App\Repositories\StoreRepository;

class StoreController extends Controller
{
    public function __construct(
        private StoreRepository $storeRepo,
    ) {
    }

    public function index()
    {
        $request = \request();
        $request->validate([
            'service_id' => 'nullable|exists:services,id',
            'search' => 'nullable|min:2',
            'latitude' => 'required',
            'longitude' => 'required',
        ]);

        $stores = $this->storeRepo->getByNearest($request);

        return $this->json('Store list', [
            'stores' => StoreResource::collection($stores),
        ]);
    }

    public function show(Store $store)
    {
        return $this->json('Store Details', [
            'store' => StoreResource::make($store),
        ]);
    }

    public function orderCondition(Store $store)
    {
        return $this->json('Order conditions for this store', [
            'delivery_charge' => number_format($store->delivery_charge, 2),
            'min_order_amount' => number_format($store->min_order_amount, 2),
            'max_order_amount' => number_format($store->max_order_amount, 2),
        ]);
    }

    public function ratings(Store $store)
    {
        $total = $store->ratings->sum('rating');
        $totalPerson = $store->ratings->count();
        $total5 = $store->ratings()->where('rating', 5)->count();
        $total4 = $store->ratings()->where('rating', 4)->count();
        $total3 = $store->ratings()->where('rating', 3)->count();
        $total2 = $store->ratings()->where('rating', 2)->count();
        $total1 = $store->ratings()->where('rating', 1)->count();

        return $this->json('Store Details', [
            'total' => (int) $totalPerson,
            'average' => (string) ($totalPerson ? round(($total / $totalPerson), 1) : 5),
            'star_5' => (string) ($total5 ? round(($total5 / $totalPerson) * 100, 2) : 0),
            'star_4' => (string) ($total4 ? round(($total4 / $totalPerson) * 100, 2) : 0),
            'star_3' => (string) ($total3 ? round(($total3 / $totalPerson) * 100, 2) : 0),
            'star_2' => (string) ($total2 ? round(($total2 / $totalPerson) * 100, 2) : 0),
            'star_1' => (string) ($total1 ? round(($total1 / $totalPerson) * 100, 2) : 0),

            'ratings' => ReviewResource::collection($store->ratings),
        ]);
    }
}
