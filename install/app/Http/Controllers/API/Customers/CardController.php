<?php

namespace App\Http\Controllers\API\Customers;

use App\Http\Controllers\Controller;
use App\Http\Requests\CardRequest;
use App\Http\Resources\CardResoucre;
use App\Repositories\PaymentRepository;

class CardController extends Controller
{
    public function index()
    {
        $customer = auth()->user()->customer;
        $cards = (new PaymentRepository())->getCardCustomerWise($customer->stripe_customer);
        $cardInfo = collect([]);
        foreach ($cards->data as $data) {
            $cardInfo[] = [
                'id' => $data->id,
                'brand' => $data->card->brand,
                'last4' => $data->card->last4,
                'exp_month' => $data->card->exp_month,
                'exp_year' => $data->card->exp_year,
            ];
        }

        return $this->json('last three card list', [
            'cards' => CardResoucre::collection($cardInfo),
        ]);
    }

    public function store(CardRequest $request)
    {
        $customer = auth()->user()->customer;
        $cards = (new PaymentRepository())->getCardCustomerWise($customer->stripe_customer);

        if ($cards->count() >= 3) {
            (new PaymentRepository())->deleteSource($customer->stripe_customer, $cards->first()->id);
        }

        $card = (new PaymentRepository())->cardSave($request, $customer->stripe_customer);

        $card = collect([
            'id' => $card->id,
            'last4' => $card->last4,
            'brand' => $card->brand,
            'exp_month' => $card->exp_month,
            'exp_year' => $card->exp_year,
        ]);

        return $this->json('Your card is added successfully.', [
            'card' => (new CardResoucre($card)),
        ]);
    }
}
