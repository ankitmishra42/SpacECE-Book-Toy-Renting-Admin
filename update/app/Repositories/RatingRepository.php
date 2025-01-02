<?php

namespace App\Repositories;

use App\Http\Requests\RatingRequest;
use App\Models\Rating;

class RatingRepository extends Repository
{
    private $path = 'images/banners/';

    public function model()
    {
        return Rating::class;
    }

    public function getByCustomer($customer)
    {
        return $this->query()->where('customer_id', $customer->id)->get();
    }

    public function storeByRequest(RatingRequest $request, $order): Rating
    {
        return $this->create([
            'order_id' => $request->order_id,
            'store_id' => $order->store_id,
            'customer_id' => auth()->user()->customer->id,
            'rating' => $request->rating,
            'content' => $request->content,
        ]);
    }
}
