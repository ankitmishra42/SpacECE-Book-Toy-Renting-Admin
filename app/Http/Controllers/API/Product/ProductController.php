<?php

namespace App\Http\Controllers\API\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductSearchRequest;
use App\Http\Resources\ProductResource;
use App\Repositories\ProductRepository;

class ProductController extends Controller
{
    public function index(ProductSearchRequest $request)
    {
        $products = (new ProductRepository())->getByRequest($request);

        return $this->json('product list', [
            'products' => ProductResource::collection($products),
        ]);
    }
}
