<?php

namespace App\Http\Controllers\Web\Variants;

use App\Http\Controllers\Controller;
use App\Http\Requests\VariantRequest;
use App\Models\Variant;
use App\Repositories\StoreRepository;
use App\Repositories\VariantRepository;

class VariantController extends Controller
{
    private $variantRepo;

    public function __construct(VariantRepository $variantRepository)
    {
        $this->variantRepo = $variantRepository;
    }

    public function index()
    {
        $user = auth()->user();
        $variants = $user->store?->variants()->orderBy('position', 'asc')->get();
        $stores = (new StoreRepository())->getAll();

        $services = $user->store?->services;

        return view('variants.index', compact('variants', 'services', 'stores'));
    }

    public function store(VariantRequest $request)
    {
        $this->variantRepo->storeByRequest($request);

        return back()->with('success', 'Varient added Success');
    }

    public function update(VariantRequest $request, Variant $variant)
    {
        $this->variantRepo->updateByRequest($request, $variant);

        return back()->with('success', 'Variant is updated successfully');
    }

    public function productsVariant(Variant $variant)
    {
        $store = auth()->user()->store;
        $products = $store->products()->where('variant_id', $variant->id)->orderBy('order', 'asc')->get();
        $services = $store->services;

        return view('variants.products', compact('variant', 'products', 'services'));
    }
}
