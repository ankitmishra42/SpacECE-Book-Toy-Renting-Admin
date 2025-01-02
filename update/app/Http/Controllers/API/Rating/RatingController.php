<?php

namespace App\Http\Controllers\API\Rating;

use App\Http\Controllers\Controller;
use App\Http\Requests\RatingRequest;
use App\Http\Resources\RatingResource;
use App\Repositories\OrderRepository;
use App\Repositories\RatingRepository;

class RatingController extends Controller
{
    public function index()
    {
        $customer = auth()->user()->customer;
        $ratings = (new RatingRepository())->getByCustomer($customer);

        if ($ratings->isEmpty()) {
            return $this->json('sorry, ratings not found', []);
        }

        return $this->json('rating list', [
            'ratings' => RatingResource::collection($ratings),
        ]);
    }

    public function store(RatingRequest $request)
    {
        $order = (new OrderRepository())->findById($request->order_id);

        if ($order->order_status->value == 'Delivered') {
            $rating = (new RatingRepository())->storeByRequest($request, $order);

            return $this->json('Thank for your reting', [
                'rating' => new RatingResource($rating),
            ]);
        }

        return $this->json('sorry, You can\'t review this order', []);
    }
}
