<?php

namespace App\Http\Controllers\Web;

use App\Enums\OrderStatus;
use App\Enums\Roles;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Http\Resources\UserResource;
use App\Http\Resources\VariantResource;
use App\Models\Customer;
use App\Repositories\CustomerRepository;
use App\Repositories\OrderRepository;
use App\Repositories\ProductRepository;
use App\Repositories\UserRepository;
use App\Repositories\VariantRepository;
use Illuminate\Http\Request;
use PhpParser\Node\Expr\Cast\Object_;

class PosController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $services = $user->store?->services;
        $customers = Customer::all();
        return view('pos.index', compact('services', 'customers'));
    }

    public function sales()
    {
        $store = auth()->user()->store;
        $orderStatus = OrderStatus::cases();

        $orders = (new OrderRepository())->query()->withoutGlobalScope('pos')->where('store_id', $store?->id)->where('pos_order', 1)->get();

        return view('pos.sales', compact('orders', 'orderStatus'));
    }

    public function store(Request $request)
    {
        if (!$request->products) {
            return back()->with('error', 'Please select products');
        }

        (new OrderRepository())->PosStoreByRequest($request);

        return to_route('pos.index')->with('success', 'Order created successfully');
    }

    public function storeCustomer(Request $request)
    {
        $request['is_active'] = 1;

        $user = (new UserRepository())->registerUser($request);

        $user->assignRole(Roles::CUSTOMER->value);

        (new CustomerRepository())->storeByUser($user);

        return $this->json(__('Created Successfully'), [
            'user' => (object)[
                'id' => $user->customer->id,
                'name' => $user->name,
                'email' => $user->email,
                'mobile' => $user->mobile,
            ],
        ], 200);
    }

    public function fetchVariants()
    {
        $serviceId = \request('service_id');

        $store = auth()->user()->store;

        $variants = (new VariantRepository())->query()->where('service_id', $serviceId)->where('store_id', $store?->id)->orderBy('position', 'asc')->get();

        return $this->json('variant list', [
            'variants' => VariantResource::collection($variants)
        ]);
    }

    public function fetchProducts(Request $request)
    {
        $store = auth()->user()->store;

        if ($store) {
            $request->merge(['store_id' => $store?->id]);
        }

        $products = (new ProductRepository())->getByRequest($request);

        return $this->json('product list', [
            'products' => ProductResource::collection($products)
        ]);
    }
}
