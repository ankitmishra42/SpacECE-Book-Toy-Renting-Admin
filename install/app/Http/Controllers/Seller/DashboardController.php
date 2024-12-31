<?php

namespace App\Http\Controllers\Seller;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Http\Resources\EarningHistoryResource;
use App\Http\Resources\SellerOrderResource;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $store = auth()->user()->store;

        $today = date('Y-m-d');
        $todayOrders = $store->orders()->whereDate('created_at', $today)->count();

        $todayEarnings = $store->orders()->where('delivery_date', $today)->where('order_status', OrderStatus::DELIVERED)->sum('payable_amount');

        $thisMonthEarnings = $store->orders()->whereMonth('created_at', now()->month)->where('order_status', OrderStatus::DELIVERED)->sum('payable_amount');

        $processing = $store->orders()->where('order_status', OrderStatus::PROCESSING)->count();

        $newOrders = $store->orders()->where('order_status', OrderStatus::PENDING->value)->latest()->take(10)->get();

        return $this->json('dashboard item list', [
            'today_orders' => $todayOrders,
            'today_earning' => number_format($todayEarnings, 1),
            'this_month_earnings' => number_format($thisMonthEarnings, 1),
            'processing_orders' => $processing,
            'orders' => SellerOrderResource::collection($newOrders)
        ]);
    }

    public function orderHistory()
    {
        $request = request();
        $store = auth()->user()->store;

        $date = $request->date ? Carbon::parse($request->date)->format('Y-m-d') : null;

        $page = $request->page ?? 1;
        $perPage = $request->per_page  ?? 10;
        $skip = ($page * $perPage) - $perPage;

        $type = $request->type;
        $paymentMethod = $request->payment_method;

        $currentStartOfMonth = now()->startOfMonth()->format('Y-m-d');
        $currentEndOfMonth = now()->endOfMonth()->format('Y-m-d');

        $startOfMonth = now()->subMonth()->startOfMonth()->format('Y-m-d');
        $endOfMonth = now()->subMonth()->endOfMonth()->format('Y-m-d');

        $weekStartDate = Carbon::now()->startOfWeek()->format('Y-m-d');
        $weekEndDate = Carbon::now()->endOfWeek()->format('Y-m-d');

        $lastWeekStart = now()->subWeek()->startOfWeek()->format('Y-m-d');
        $lastWeekEnd = now()->subWeek()->endOfWeek()->format('Y-m-d');

        $currentYearStartDate = now()->startOfYear()->format('Y-m-d');
        $currentYearEndDate = now()->endOfYear()->format('Y-m-d');

        $lastYearStartDate = now()->subYear()->startOfYear()->format('Y-m-d');
        $lastYearEndDate = now()->subYear()->endOfYear()->format('Y-m-d');

        $orders = $store->orders()->where('order_status', OrderStatus::DELIVERED->value)
            ->when($date, function ($query) use ($date) {
                $query->where('delivery_date', $date);
            })->when($type == 'today', function ($query) {
                $query->where('delivery_date', now()->format('Y-m-d'));
            })->when($type == 'this_month', function ($query) use ($currentStartOfMonth, $currentEndOfMonth) {
                $query->whereBetween('delivery_date', [$currentStartOfMonth, $currentEndOfMonth]);
            })->when($type == 'last_month', function ($query) use ($startOfMonth, $endOfMonth) {
                $query->whereBetween('delivery_date', [$startOfMonth, $endOfMonth]);
            })->when($type == 'this_week', function ($query) use ($weekStartDate, $weekEndDate) {
                $query->whereBetween('delivery_date', [$weekStartDate, $weekEndDate]);
            })->when($type == 'last_week', function ($query) use ($lastWeekStart, $lastWeekEnd) {
                $query->whereBetween('delivery_date', [$lastWeekStart, $lastWeekEnd]);
            })->when($paymentMethod, function ($query) use ($paymentMethod) {
                $query->where('payment_type', $paymentMethod);
            })->when($type == 'this_year', function ($query) use ($currentYearStartDate, $currentYearEndDate) {
                $query->whereBetween('delivery_date', [$currentYearStartDate, $currentYearEndDate]);
            })->when($type == 'last_year', function ($query) use ($lastYearStartDate, $lastYearEndDate) {
                $query->whereBetween('delivery_date', [$lastYearStartDate, $lastYearEndDate]);
            });

            $thisMonthEarning = $store->orders()->where('order_status', OrderStatus::DELIVERED->value)->whereBetween('delivery_date', [$currentStartOfMonth, $currentEndOfMonth])->sum('payable_amount');

        return $this->json('Earning histories', [
            'this_month_earnings' => number_format($thisMonthEarning, 1),
            'total_items' => $orders->count(),
            'total_earning' => number_format($orders->sum('payable_amount'), 1),
            'orders' => EarningHistoryResource::collection($orders->skip($skip)->take($perPage)->get())
        ]);
    }
}
