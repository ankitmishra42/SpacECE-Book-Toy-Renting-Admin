<?php

namespace App\Http\Controllers\API\Variant;

use App\Http\Controllers\Controller;
use App\Http\Resources\VariantResource;
use App\Repositories\VariantRepository;

class VariantController extends Controller
{
    public function index()
    {
        $request = \request();
        $serviceId = $request->service_id;
        $storeId = $request->store_id;

        $variants = (new VariantRepository())->query()
            ->when($serviceId, function ($query) use ($serviceId) {
                $query->where('service_id', $serviceId);
            })
            ->when($storeId, function ($query) use ($storeId) {
                $query->where('store_id', $storeId);
            })->orderBy('position', 'asc')->get();

        return $this->json('variant list', [
            'variants' => VariantResource::collection($variants),
        ]);
    }
}
