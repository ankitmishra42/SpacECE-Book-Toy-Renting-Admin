<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Order;
use App\Repositories\OrderRepository;
use App\Repositories\ProductRepository;
use App\Repositories\ServiceRepository;
use App\Repositories\StoreRepository;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $customers = Customer::all();
        $services = (new ServiceRepository())->getAll();
        $products = (new ProductRepository())->getAll();
        $income = 0;
        if ($user->hasRole('store')) {
           $income = Order::withoutGlobalScopes()->where('store_id', $user->store?->id)->where('order_status', 'Delivered')->sum('total_amount');
        }

        $revenues = (new OrderRepository())->getRevenueReport();

        $confirmOrder = (new OrderRepository())->getByStatus('Order confirmed')->count();
        $completeOrder = (new OrderRepository())->getByStatus('Delivered')->count();
        $pendingOrder = (new OrderRepository())->getByStatus('Pending')->count();
        $onPregressOrder = (new OrderRepository())->getByStatus('Processing')->count();
        $cancelledOrder = (new OrderRepository())->getByStatus('Cancelled')->count();

        $shop = (new StoreRepository())->getAll()->count();
        $orders = Order::all();

        if ($user->hasRole('store')) {
            $store = $user->store;
            $products = $store->products;
            $services = $store->services;
            $orders = $store->orders;
        }
        if ($user->hasRole('root|admin')) {
            return view('dashboard.root', compact(
                'customers', 'services', 'products', 'revenues', 'confirmOrder', 'completeOrder', 'pendingOrder', 'onPregressOrder', 'cancelledOrder', 'shop'
            ));
        }

        return view('dashboard.index', compact(
            'customers', 'services', 'products', 'revenues', 'income', 'confirmOrder', 'completeOrder', 'pendingOrder', 'onPregressOrder', 'cancelledOrder', 'orders'
        ));
    }
}
