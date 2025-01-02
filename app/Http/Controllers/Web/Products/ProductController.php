<?php

namespace App\Http\Controllers\Web\Products;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\AppSetting;
use App\Models\Product;
use App\Repositories\ProductRepository;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    private $productRepo;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepo = $productRepository;
    }

    public function index()
    {
        $currency = AppSetting::first()?->currency ?? '$';
        $products = $this->productRepo->getAllOrFindBySearch(true);

        return view('products.index', compact('products', 'currency'));
    }

    public function create()
    {
        $currency = AppSetting::first()?->currency ?? '$';
        $services = auth()->user()->store->services;

        return view('products.create', compact('services', 'currency'));
    }

    public function store(ProductRequest $request)
    {
        if (($request->discount_price != '') && ($request->price < $request->discount_price)) {
            return back()->with('error', 'Discount price must be less than product price');
        }
        $this->productRepo->storeByRequest($request);

        return redirect()->route('product.index')->with('success', 'Product added successsfully');
    }

    public function edit(Product $product)
    {
        $variants = $product->service->variants();
        if (auth()->user()->hasRole('store')) {
            $variants = $variants->where('store_id', auth()->user()->store->id);
        }
        $services = auth()->user()->store->services;
        $variants = $variants->get();

        return view('products.edit', compact('product', 'services', 'variants'));
    }

    public function update(ProductRequest $request, Product $product)
    {
        if (($request->discount_price != '') && ($request->price < $request->discount_price)) {
            return back()->with('error', 'Product price must be bigger than discount price');
        }
        $this->productRepo->updateByRequest($request, $product);

        return redirect()->route('product.index')->with('success', 'Product updated success');
    }

    public function toggleActivationStatus(Product $product)
    {
        $this->productRepo->updateStatusById($product);

        return back()->with('success', 'product status updated');
    }

    public function orderUpdate(Request $request, Product $product)
    {

        $product->update([
            'order' => $request->position ?? 0,
        ]);

        return back();
    }
}
