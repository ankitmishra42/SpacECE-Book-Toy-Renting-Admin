<?php

namespace App\Repositories;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use App\Enums\PaymentType;
use App\Http\Requests\OrderRequest;
use App\Http\Requests\ReorderRequest;
use App\Models\Additional;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;

class OrderRepository extends Repository
{
    public function model()
    {
        return Order::class;
    }

    public function getByStatus($status)
    {
        $orders = $this->query()->where('order_status', $status);
        $user = (new UserRepository())->find(auth()->id());
        if ($user->hasRole('store')) {
            $orders = $orders->where('store_id', auth()->user()->store->id);
        }

        return $orders->get();
    }

    public function getByTodays()
    {
        return $this->model()::whereDate('created_at', Carbon::today())->get();
    }

    public function storeByRequest(OrderRequest $request): Order
    {
        $store = (new StoreRepository())->find($request->store_id);
        $lastOrder = $this->query()->latest('id')->first();

        $customer = auth()->user()->customer;
        $getAmount = $this->getAmount($request);

        $paymentType = $request->payment_type == 'online' ? PaymentType::ONLINEPAYMENT->value : PaymentType::CASHONDELIVERY->value;

        $order = $this->create([
            'store_id' => $store->id,
            'customer_id' => $customer->id,
            'order_code' => str_pad($lastOrder ? $lastOrder->id + 1 : 1, 6, '0', STR_PAD_LEFT),
            'prefix' => $store->prifix ?? 'MS',
            'coupon_id' => $request->coupon_id,
            'pick_date' => $request->pick_date,
            'delivery_date' => $request->delivery_date,
            'pick_hour' => now()->format('H:00:00'),
            'delivery_hour' => now()->format('H:00:00'),
            'payable_amount' => $getAmount['payableAmount'],
            'total_amount' => $getAmount['total'],
            'delivery_charge' => $store->delivery_charge ?? 0,
            'discount' => $getAmount['discount'],
            'payment_status' => PaymentStatus::PENDING->value,
            'payment_type' => $paymentType,
            'order_status' => OrderStatus::PENDING->value,
            'address_id' => $request->address_id,
            'instruction' => $request->instruction,
        ]);

        foreach ($request->products as $product) {
            $order->products()->attach($product['id'], ['quantity' => $product['quantity']]);
        }

        return $order;
    }

    public function storeByOrderRequest(ReorderRequest $request, Order $order): Order
    {
        $lastOrder = $this->query()->latest('id')->first();

        $newOrder = $this->create([
            'store_id' => $order->store_id,
            'customer_id' => $order->customer_id,
            'order_code' => str_pad($lastOrder ? $lastOrder->id + 1 : 1, 6, '0', STR_PAD_LEFT),
            'prefix' => 'MV',
            'coupon_id' => $order->coupon_id,
            'pick_date' => $request->pick_date,
            'delivery_date' => $request->delivery_date,
            'pick_hour' => $this->setPickOrDeliveryTime($request->pick_date, $request->pick_hour),
            'delivery_hour' => $this->setPickOrDeliveryTime($request->delivery_date, $request->delivery_hour, 'delivery'),
            'payable_amount' => $order->payable_amount,
            'total_amount' => $order->total_amount,
            'delivery_charge' => $order->delivery_charge,
            'discount' => $order->discount,
            'payment_status' => PaymentStatus::PENDING->value,
            'payment_type' => $order->payment_type,
            'order_status' => OrderStatus::PENDING->value,
            'address_id' => $order->address_id,
            'instruction' => $order->instruction,
        ]);

        foreach ($order->products as $product) {
            $newOrder->products()->attach($product->id, ['quantity' => $product->pivot->quantity]);
        }

        return $newOrder;
    }

    public function PosStoreByRequest(Request $request): Order
    {
        $lastOrder = $this->query()->max('id');
        $store = auth()->user()->store;

        $products = $request->products;
        $totalAmount = $request->total_amount;
        $grandTotal = $request->grand_total;

        $order = $this->create([
            'store_id' => $store?->id,
            'customer_id' => $request->customer_id ?? null,
            'order_code' => str_pad($lastOrder + 1, 6, '0', STR_PAD_LEFT),
            'pos_order' => true,
            'prefix' => $store->prifix ?? 'LM',
            'pick_date' => now()->format('Y-m-d'),
            'pick_hour' => now()->format('H:00:00'),
            'payable_amount' => $grandTotal,
            'total_amount' => $totalAmount,
            'payment_status' => PaymentStatus::PAID->value,
            'payment_type' => PaymentType::CASHONDELIVERY->value,
            'order_status' => OrderStatus::CONFIRM->value,
            'address_id' => $request->address_id ?? null,
            'instruction' => $request->instruction,
        ]);

        foreach ($products as $product) {
            $order->products()->attach($product['id'], ['quantity' => $product['quantity']]);
        }

        return $order;
    }

    private function getAmount(OrderRequest $request): array
    {
        $store = (new StoreRepository())->find($request->store_id);
        $totalAmount = 0;
        foreach ($request->products as $item) {
            $product = (new ProductRepository())->find($item['id']);
            $price = $product->discount_price ?? $product->price;
            $totalAmount += (int) $item['quantity'] * $price;
        }

        $totalServiceAmount = 0;
        if ($request->has('additional_service_id')) {
            $totalServiceAmount = Additional::whereIn('id', $request->additional_service_id)->get()->sum('price');
        }

        $total = ($totalAmount + $totalServiceAmount);
        $coupon = (new CouponRepository())->find($request->coupon_id, $request->store_id, $total);
        $couponDiscount = $coupon ? $coupon->calculate($total, $coupon) : 0;

        $total = $total - $couponDiscount + ($store->delivery_charge ?? 0);

        return [
            'payableAmount' => $total,
            'discount' => $couponDiscount,
            'total' => ($totalAmount + $totalServiceAmount),
        ];
    }

    public function getSortedByRequest(Request $request)
    {
        $status = $request->status;
        $searchKey = $request->search;

        $orders = $this->query();

        $user = (new UserRepository())->find(auth()->id());
        if ($user->hasRole('store')) {
            $orders = $orders->where('store_id', $user->store->id);
        }

        if ($status) {
            $orders = $orders->where('order_status', $status);
        }

        if ($searchKey) {
            $orders = $orders->where(function ($query) use ($searchKey) {
                $query->orWhere('order_code', 'like', "%{$searchKey}%")
                    ->orWhereHas('customer', function ($customer) use ($searchKey) {
                        $customer->whereHas('user', function ($user) use ($searchKey) {
                            $user->where('first_name', $searchKey)
                                ->orWhere('last_name', $searchKey)
                                ->orWhere('mobile', $searchKey);
                        });
                    })
                    ->orWhere('prefix', 'like', "%{$searchKey}%")
                    ->orWhere('amount', 'like', "%{$searchKey}%")
                    ->orWhere('payment_status', 'like', "%{$searchKey}%")
                    ->orWhere('order_status', 'like', "%{$searchKey}%");
            });
        }

        return $orders->latest()->get();
    }

    public function orderListByStatus($status = null)
    {
        $customer = auth()->user()->customer;
        $orders = $this->query()->where('customer_id', $customer->id);

        if ($status) {
            $orders = $orders->where('order_status', $status);
        }

        return $orders->latest()->get();
    }

    public function statusUpdateByRequest(Order $order, $status): Order
    {
        $order->update([
            'order_status' => $status,
        ]);

        if ($order->drivers && $status == OrderStatus::DELIVERED->value || $status == OrderStatus::PICKED_UP->value) {
            $order->drivers()->update(['is_completed' => true]);
        }

        return $order;
    }

    public function getRevenueReportByBetweenDate($form, $to)
    {
        $storeId = auth()->user()->store?->id;

        return $this->query()->whereBetween('delivery_date', [$form, $to])
            ->where('order_status', OrderStatus::DELIVERED->value)
            ->when($storeId, function ($query) use ($storeId) {
                $query->where('store_id', $storeId);
            })
            ->get();
    }

    public function getRevenueReport()
    {
        $year = now()->format('Y');
        $month = now()->format('m');

        $orders = $this->query()->where('order_status', OrderStatus::DELIVERED->value);
        if (auth()->user()->store) {
            $orders = $orders->where('store_id', auth()->user()->store?->id);
        }
        if (request()->type == 'month') {
            $orders = $orders->whereMonth('delivery_date', $month)
                ->whereYear('delivery_date', $year);
        } elseif (request()->type == 'year') {
            $orders = $orders->whereYear('delivery_date', $year);
        } elseif (request()->type == 'week') {
            $end = now()->format('Y-m-d');
            $start = now()->subWeek()->format('Y-m-d');
            $orders = $orders->whereBetween('delivery_date', [$start, $end]);
        } else {
            $date = now()->format('Y-m-d');
            $orders = $orders->where('delivery_date', $date);
        }

        return $orders->get();
    }

    public function getByDatePickOrDelivery($date, $type = 'picked')
    {
        $orders = $this->model()::query();

        if ($type == 'picked') {
            $orders = $orders->where('pick_date', $date);
        }

        if ($type == 'delivery') {
            $orders = $orders->where('delivery_date', $date);
        }

        return $orders->get();
    }

    public function findById($id)
    {
        return $this->find($id);
    }

    public function setPickOrDeliveryTime($date, $times, $type = 'picked')
    {
        $times = explode('-', $times);

        foreach ($times as $time) {
            $orders = $this->query();
            if ($type == 'picked') {
                $orders = $orders->where('pick_date', $date)->where('pick_hour', 'LIKE', "%$time%");
            }

            if ($type == 'delivery') {
                $orders = $orders->where('delivery_date', $date)->where('delivery_hour', 'LIKE', "%$time%");
            }

            if ($orders->count() < 2) {
                return sprintf('%02s', $time) . ':' . sprintf('%02s', ($orders->count() * 30)) . ':00';
            }
        }
    }
}
