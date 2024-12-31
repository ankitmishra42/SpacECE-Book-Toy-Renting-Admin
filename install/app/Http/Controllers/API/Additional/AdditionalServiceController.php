<?php

namespace App\Http\Controllers\API\Additional;

use App\Http\Controllers\Controller;
use App\Http\Resources\AdditionalServiceResource;
use App\Repositories\AdditionalRepository;

class AdditionalServiceController extends Controller
{
    public function index()
    {
        $request = \request();
        $service = $request->service_id;
        $store = $request->store_id;

        $additionalServices = (new AdditionalRepository())->query()
            ->when($service, function ($query) use ($service) {
                $query->where('service_id', $service);
            })
            ->when($store, function ($query) use ($store) {
                $query->where('store_id', $store);
            })->where('is_active', true)->get();

        return $this->json('Additional service list', [
            'additional_services' => AdditionalServiceResource::collection($additionalServices),
        ]);
    }
}
