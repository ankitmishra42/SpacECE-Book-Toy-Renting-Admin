<?php

namespace App\Http\Controllers\API\Order;

use App\Enums\OrderStatus;
use App\Events\OrderMailEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Http\Requests\ReorderRequest;
use App\Http\Resources\OrderResource;
use App\Http\Resources\ScheduleResource;
use App\Models\AdminDeviceKey;
use App\Models\AppSetting;
use App\Models\Store;
use App\Repositories\DeviceKeyRepository;
use App\Repositories\NotificationRepository;
use App\Repositories\OrderRepository;
use App\Services\NotificationServices;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Response;
use PDF;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{
    public function index()
    {
        $status = config('enums.order_status.' . request('status'));

        $orders = (new OrderRepository())->orderListByStatus($status);

        return $this->json('customer order list', [
            'orders' => OrderResource::collection($orders),
        ]);
    }

    public function store(OrderRequest $request)
    {
        $availablePickTime = $this->checkPickTime($request->pick_date, $request->store_id);
        $availableDeliveryTime = $this->checkDeliveryTime($request->delivery_date, $request->store_id);

        if ($availablePickTime && $availableDeliveryTime) {

            $order = (new OrderRepository())->storeByRequest($request);

            if ($request->has('additional_service_id')) {
                $order->additionals()->sync($request->additional_service_id);
            }

            $quantity = $order->products->sum('pivot.quantity');
            $store = $order->store;
            $filePath = 'pdf/order' . $order->id . $order->prefix . $order->order_code . rand(10000, 99999) . '.pdf';
            $invoiceName = $store->user?->invoice?->invoice_name ?? 'invoice1';
            $appSetting = AppSetting::first();
            $pdf = PDF::loadView('pdf.'.$invoiceName, compact('order', 'quantity', 'store', 'appSetting'));

            Storage::put($filePath, $pdf->output());

            $order->update([
                'invoice_path' => $filePath,
            ]);

            $deviceKeys = AdminDeviceKey::all();

            $message = "Hello,\r" . 'New order added from ' . $order->customer->name . ".\r" . "Total amount :   $order->total_amount \r" . 'Pick Date: ' . Carbon::parse($order->pick_date)->format('d F Y') . ' - ' . $order->getTime($order->pick_hour) . "\r" . 'Delivery Date: ' . Carbon::parse($order->delivery_date)->format('d F Y') . ' - ' . $order->getTime($order->delivery_hour);

            $keys = $deviceKeys->pluck('key')->toArray();
            $title = 'New Order Added';

            NotificationServices::sendNotification($message, $keys, $title);

            (new NotificationRepository())->storeByRequest($order->customer->id, $message, $title);

            OrderMailEvent::dispatch($order);

            return $this->json('order is added successfully', [
                'order' => new OrderResource($order),
            ]);
        }

        return $this->json('Pickup or Delivery schedule is not available', [], Response::HTTP_BAD_REQUEST);
    }

    public function show($id)
    {
        $order = (new OrderRepository())->findById($id);
        try {
            return $this->json('order details', [
                'order' => new OrderResource($order),
            ]);
        } catch (Exception $e) {
            return $this->json('Sorry, Order not found');
        }
    }

    public function cancle($id)
    {
        $order = (new OrderRepository())->findById($id);
        try {
            if ($order->order_status->value != OrderStatus::PENDING->value) {
                return $this->json('Sorry, order cancle is not possible', [], Response::HTTP_BAD_REQUEST);
            }
            $order->update([
                'order_status' => OrderStatus::CANCELLED->value,
            ]);

            return $this->json('Order cancle successfully', [
                'order' => OrderResource::make($order),
            ]);
        } catch (\Throwable $th) {
            return $this->json('Sorry, Order not found', [], Response::HTTP_BAD_REQUEST);
        }
    }

    public function reorder(ReorderRequest $request)
    {
        $order = (new OrderRepository())->find($request->order_id);
        if ($order->order_status->value != OrderStatus::DELIVERED->value) {
            return $this->json('Sorry, Order is not Delivered', [], Response::HTTP_BAD_REQUEST);
        }
        $newOrder = (new OrderRepository())->storeByOrderRequest($request, $order);

        return $this->json('order is added successfully', [
            'order' => new OrderResource($newOrder),
        ]);
    }

    private function checkPickTime($date, $storeId)
    {
        $store = Store::find($storeId);
        $day = Carbon::parse($date)->format('l');
        $schedule = $store->schedules()->where('is_active', true)->where('day', $day)->where('type', 'pickup')->first();

        if ($schedule) {
            $perOrder = $store->orders()->where('pick_date', Carbon::parse($date)->format('Y-m-d'))->where('pick_hour', now()->format('H:00:00'))->count();

            return ($schedule->per_hour > $perOrder) ? true : false;
        }
        return false;
    }

    private function checkDeliveryTime($date, $storeId)
    {
        $store = Store::find($storeId);
        $day = Carbon::parse($date)->format('l');
        $schedule = $store->schedules()->where('is_active', true)->where('day', $day)->where('type', 'delivery')->first();

        if ($schedule) {
            $perOrder = $store->orders()->where('delivery_date', Carbon::parse($date)->format('Y-m-d'))->where('delivery_hour', now()->format('H:00:00'))->count();

            return ($schedule->per_hour > $perOrder) ? true : false;
        }
        return false;
    }

    public function pickSchedule(Store $store, $date)
    {
        $hours = $this->getAvailableTimes($store, $date, 'pickup');
        if ($hours->isEmpty()) {
            return $this->json('Sorry, Our service is not abailable', [
                'schedules' => [],
            ]);
        }

        $hours = collect($hours);

        return $this->json('picked scheduls', [
            'schedules' => ScheduleResource::collection($hours),
        ]);
    }

    public function deliverySchedule(Store $store, $date)
    {
        $hours = $this->getAvailableTimes($store, $date, 'delivery');
        if ($hours->isEmpty()) {
            return $this->json('Sorry, Our service is not abailable', [
                'schedules' => [],
            ]);
        }

        return $this->json('Delivery scheduls', [
            'schedules' => ScheduleResource::collection($hours),
        ]);
    }

    private function getAvailableTimes($store, $date, $type)
    {
        $day = Carbon::parse($date)->format('l');
        $schedule = $store->schedules()
            ->where('is_active', true)
            ->where('day', $day)
            ->where('type', $type)
            ->first();

        $today = ($type === 'pickup') ? date('Y-m-d') : now()->addDay()->format('Y-m-d');

        if (!$schedule || $date < $today) {
            return [];
        }

        $i = ($type === 'pickup' && $today == Carbon::parse($date)->format('Y-m-d')) ? date('H') + 1 : $schedule->start_time;

        $orders = (new OrderRepository())->getByDatePickOrDelivery($date);
        $hours = collect([]);

        for ($i; $i < ($schedule->end_time - 1); $i += 2) {
            $per = 0;
            foreach ($orders as $order) {
                $hour = Carbon::parse($order->pick_hour)->format('H');
                if ($i == $hour) {
                    $per++;
                }
            }
            if ($per < ($schedule->per_hour * 2)) {
                $hours[] = [
                    'hour' => (string) $i . '-' . (string) ($i + 1),
                    'title' => sprintf('%02s', $i) . ':00' . ' - ' . sprintf('%02s', $i + 1) . ':59',
                ];
            }
        }

        return $hours;
    }
}
