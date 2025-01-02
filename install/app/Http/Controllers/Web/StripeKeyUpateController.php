<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\StripeKey;
use Illuminate\Http\Request;

class StripeKeyUpateController extends Controller
{
    public function index()
    {
        $stripeKey = StripeKey::first();

        return view('stripe-key', compact('stripeKey'));
    }

    public function update(Request $request, StripeKey $stripeKey)
    {
        $request->validate(([
            'public_key' => 'required|string',
            'secret_key' => 'required|string',
        ]));

        StripeKey::updateOrCreate(
            [
                'id' => $stripeKey ? $stripeKey->id : 0,
            ],
            [
                'public_key' => $request->public_key,
                'secret_key' => $request->secret_key,
            ]
        );

        return back()->with('success', 'Update Successfully');
    }
}
