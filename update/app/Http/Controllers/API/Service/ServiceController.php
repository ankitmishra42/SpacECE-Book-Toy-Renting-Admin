<?php

namespace App\Http\Controllers\API\Service;

use App\Http\Controllers\Controller;
use App\Http\Resources\ServiceResource;
use App\Repositories\ServiceRepository;
use App\Repositories\StoreRepository;

class ServiceController extends Controller
{
    public function index()
    {
        $request = \request();
        $store = $request->store_id;
        $search = $request->search;
        $request->validate(['search' => 'nullable|min:2']);

        $services = (new ServiceRepository())->query()->isActive();

        if ($store) {
            $services = (new StoreRepository())->find($store)->services()
                ->when($search, function ($query, $search) {
                    return $query->where('name', 'like', "%{$search}");
                });

        }

        return $this->json('service list', [
            'services' => ServiceResource::collection($services->get()),
        ]);
    }
}
