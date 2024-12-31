<?php

namespace App\Http\Controllers\Seller;

use App\Enums\OrderStatus;
use App\Events\OrderMailEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\CancelOrderRequest;
use App\Http\Requests\StatusUpdateRequest;
use App\Http\Resources\OrderResource;
use App\Http\Resources\SellerOrderResource;
use App\Repositories\DeviceKeyRepository;
use App\Repositories\NotificationRepository;
use App\Repositories\OrderRepository;
use App\Repositories\UserRepository;
use App\Repositories\WalletRepository;
use App\Services\NotificationServices;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $store = auth()->user()->store;
        $orderStatus = $request->order_status;
        $searchKey = $request->search;

        $page = $request->page ?? 1;
        $perPage = $request->per_page  ?? 10;
        $skip = ($page * $perPage) - $perPage;

        $orders = $store->orders()->when($orderStatus, function ($query) use ($orderStatus) {
            $query->where('order_status', $orderStatus);
        })->when($searchKey, function ($query) use ($searchKey) {
            $query->where('order_code', 'like', "%{$searchKey}%")
                ->orWhereHas('customer', function ($customer) use ($searchKey) {
                    $customer->whereHas('user', function ($user) use ($searchKey) {
                        $user->where('first_name', $searchKey)
                            ->orWhere('last_name', $searchKey)
                            ->orWhere('mobile', $searchKey);
                    });
                })
                ->orWhere('prefix', 'like', "%{$searchKey}%")
                ->orWhere('amount', 'like', "%{$searchKey}%")
                ->orWhere('payment_status', 'like', "%{$searchKey}%");
        });

        return $this->json('Orders list', [
            'total' => $orders->count(),
            'orders' => SellerOrderResource::collection($orders->skip($skip)->take($perPage)->get())
        ]);
    }

    public function cancel(CancelOrderRequest $request)
    {
        $order = (new OrderRepository())->find($request->order_id);

        if ($order->order_status != OrderStatus::PENDING->value) {
            return $this->json('Sorry, cancellation is not possible for this order at the moment.', [], Response::HTTP_BAD_REQUEST);
        }

        $order->update(['order_status' => OrderStatus::CANCELLED->value]);
        return $this->json('Order Cancelled successfully!');
    }

    public function statusUpdate(StatusUpdateRequest $request)
    {
        $order = (new OrderRepository())->find($request->order_id);

        $order->update(['order_status' => $request->order_status]);

        if ($request->order_status == OrderStatus::PENDING->value) {
            $order->drivers()->delete();
        }

        if ($order->customer->devices->count()) {
            $devices = $order->customer->devices;
            $message = "Hello {$order->customer->name}. Your order status is {$request->order_status}. OrderID: {$order->prefix}{$order->order_code}";

            $tokens = $devices->pluck('key')->toArray();
            $title = 'Order Status Update';

            NotificationServices::sendNotification($message, $tokens, $title);

            (new NotificationRepository())->storeByRequest($order->customer->id, $message, $title);
        }

        if ($order->order_status->value == OrderStatus::DELIVERED->value) {
            $commissionCost = round(($order->payable_amount / 100) * $order->store->commission, 2);
            $storeAmount = $order->payable_amount - $commissionCost;

            (new WalletRepository())->updateCredit($order->store->user->wallet, $storeAmount, "Order Delivered from {$order->store->name}", $order->id, null, $order->store->id);

            $rootUser = (new UserRepository())->query()->role('root')->first();
            (new WalletRepository())->updateCredit($rootUser->wallet, $commissionCost, "Order Delivered from {$order->store->name}", $order->id);
        }

        OrderMailEvent::dispatch($order);

        return $this->json('Order status updated successfully!', [
            'orders' => OrderResource::make($order)
        ]);
    }

    public function statusWiseOrders()
    {
        $store = auth()->user()->store;

        $statusWiseOrders = collect([]);

        foreach (OrderStatus::cases() as $status) {
            $statusWiseOrders[] = (object) [
                'status' => $status->value,
                'count' => $store->orders()->where('order_status', $status->value)->count(),
            ];
        }
        return $this->json('Status wise orders', [
            'status_wise_orders' => $statusWiseOrders
        ]);
    }

    public function show(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id'
        ]);
        $order = (new OrderRepository())->find($request->order_id);

        return $this->json('Order details', [
            'order' => SellerOrderResource::make($order)
        ]);
    }
}
