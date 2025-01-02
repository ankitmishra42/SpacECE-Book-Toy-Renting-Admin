<?php

namespace App\Http\Controllers\Web;

use App\Events\UserMailEvent;
use App\Http\Controllers\Controller;
use App\Models\AppSetting;
use App\Models\DeliveryCost;
use App\Models\StripeKey;
use App\Repositories\OrderRepository;
use Stripe\PaymentIntent;
use Stripe\Stripe;

class PaymentController extends Controller
{
    public function testIndex()
    {
        return view('web.test');
    }

    public function index($order, $cardId)
    {
        return redirect()->route('payment', [encrypt($order), encrypt($cardId)]);
    }

    public function payment($enryptedOrderId, $enryptCardId)
    {
        $order = (new OrderRepository())->findById(decrypt($enryptedOrderId));
        $customer = $order->customer;
        $deliveryCost = DeliveryCost::first();
        $card = decrypt($enryptCardId);
        $stripeKey = StripeKey::first();

        return view('web.payment', compact('customer', 'order', 'deliveryCost', 'card', 'stripeKey'));
    }

    public function intent($customer, $card, $amount, $order)
    {
        $stripe = StripeKey::first();
        $weSetting = AppSetting::first();
        Stripe::setApiKey($stripe?->secret_key);

        $intent = PaymentIntent::create([
            'customer' => 'cus_MOSRQjnGEe59NG',
            'amount' => (100 * $amount),
            'currency' => 'GBP',
            'customer' => $customer,
            'payment_method' => $card,
            'description' => $weSetting?->name.' ordre payment received from Web.',
            'automatic_payment_methods' => [
                'enabled' => 'true',
            ],
            ['metadata' => ['order_id' => $order]],
        ]);

        return $intent;
    }

    public function updatePayment($id)
    {
        $order = (new OrderRepository())->findById($id);
        $order->update([
            'payment_status' => config('enums.payment_status.paid'),
        ]);

        if ($order->customer->user->email) {
            event(new UserMailEvent($order));
        }

        return $order;
    }

    public function success($id)
    {
        $order = (new OrderRepository())->findById($id);
        $order->update([
            'payment_status' => config('enums.payment_status.paid'),
        ]);

        return $order;
    }
}
