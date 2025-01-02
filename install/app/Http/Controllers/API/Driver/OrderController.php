<?php

namespace App\Http\Controllers\API\Driver;

use App\Enums\OrderStatus;
use App\Events\OrderMailEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\CancelOrderRequest;
use App\Http\Requests\StatusUpdateRequest;
use App\Http\Resources\RiderOrderDetailsResource;
use App\Http\Resources\RiderOrderResource;
use App\Repositories\DeviceKeyRepository;
use App\Repositories\DriverOrderRepository;
use App\Repositories\NotificationRepository;
use App\Repositories\OrderRepository;
use App\Repositories\UserRepository;
use App\Repositories\WalletRepository;
use App\Services\NotificationServices;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $AllStatus = array_column(OrderStatus::cases(), 'value');
        $request->validate([
            'order_status' => ['nullable', Rule::in($AllStatus)],
        ]);

        $date = $request->date ? parse($request->date, 'Y-m-d') : null;
        $search = $request->search;
        $isComplated = $request->is_complated;

        $page = $request->page ?? 1;
        $perPage = $request->per_page  ?? 10;
        $skip = ($page * $perPage) - $perPage;

        $orders = auth()->user()->driver->orders()
            ->when($request->order_status, function ($query) use ($request) {
                $query->whereHas('order', function ($order) use ($request) {
                    $order->where('order_status', $request->order_status);
                });
            })->when($date, function ($query) use ($date) {
                $query->whereHas('order', function ($order) use ($date) {
                    $order->where('pick_date', $date)->orWhere('delivery_date', $date);
                });
            })->when($search, function ($query) use ($search) {
                $query->whereHas('order', function ($order) use ($search) {
                    $order->where('order_code', 'like', "%{$search}%");
                });
            })->latest();

        if ($isComplated) {
            $orders = $orders->where('is_completed', $isComplated);
        } elseif (($isComplated == 0) && ($isComplated != null)) {
            $orders = $orders->where('is_completed', false);
        }

        return $this->json('Orders list', [
            'total' => $orders->count(),
            'orders' => RiderOrderResource::collection($orders->skip($skip)->take($perPage)->get())
        ]);
    }

    public function show(CancelOrderRequest $request)
    {
        $order = (new OrderRepository())->find($request->order_id);
        return $this->json('Order details', [
            'order' => RiderOrderDetailsResource::make($order)
        ]);
    }

    public function statusWiseOrders(Request $request)
    {
    }

    public function statusUpdate(StatusUpdateRequest $request)
    {
        $driverOrder = (new DriverOrderRepository())->query()->where('order_id', $request->order_id)->where('is_completed', false)->first();

        $orderStatus = $request->order_status;

        if (($orderStatus == OrderStatus::PICKED_UP->value) || ($orderStatus == OrderStatus::DELIVERED->value)) {
            $driverOrder->update(['is_completed' => true]);
        }
        $driverOrder->order->update(['order_status' => $orderStatus]);

        $order = $driverOrder->order;

        if ($order->customer->devices->count()) {
            $devices = $order->customer->devices;
            $message = "Hello {$order->customer->name}. Your order status is {$request->order_status}. OrderID: {$order->prefix}{$order->order_code}";

            $deviceKeys = $devices->pluck('key')->toArray();
            $title = 'Order Status Update';

            NotificationServices::sendNotification($message, $deviceKeys, $title);

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

        return $this->json("Order $orderStatus successfully!", [
            'order' => RiderOrderDetailsResource::make($driverOrder->order)
        ]);
    }
}
