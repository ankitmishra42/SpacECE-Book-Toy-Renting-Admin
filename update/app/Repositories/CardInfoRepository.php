<?php

namespace App\Repositories;

use App\Http\Requests\CardRequest;
use App\Models\CardInfo;

class CardInfoRepository extends Repository
{
    public function model()
    {
        return CardInfo::class;
    }

    public function findByCard($card)
    {
        return $this->query()->where('card', $card)->first();
    }

    public function findByID($id)
    {
        return $this->find($id);
    }

    public function satoreByCustomerRequest(CardRequest $request, $customer): CardInfo
    {
        $this->checkAndDelete($customer, $request->number);
        $card = $customer->cards()->onlyTrashed()->where('card', $request->number)->first();
        if ($card) {
            $card->restore();

            return $card;
        }

        $lenght = strlen($request->number);
        $lastNo = substr($request->number, ($lenght - 4), 16);

        return $this->model()::updateOrCreate([
            'card' => $request->number,
        ], [
            'name' => $request->name,
            'customer_id' => $customer->id,
            'cvc' => $request->cvc,
            'last_no' => $lastNo,
            'exp_month' => $request->exp_month,
            'exp_year' => $request->exp_year,
        ]);
    }

    private function checkAndDelete($customer, $newCard)
    {
        $cards = $customer->cards->pluck('card')->toArray();

        if ($cards && ! in_array($newCard, $cards) && count($cards) == 3) {
            $customer->cards->first()->delete();
        }
    }
}
