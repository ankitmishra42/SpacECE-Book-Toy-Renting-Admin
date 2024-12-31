<?php

namespace App\Repositories;

use App\Http\Requests\AdditionalRequest;
use App\Models\Additional;

class AdditionalRepository extends Repository
{
    public function model()
    {
        return Additional::class;
    }

    public function findById($id)
    {
        return $this->model()::where('id', $id)->first();
    }

    public function getAllByShop($isLatest = false)
    {
        $store = auth()->user()->store;
        $additional = $this->query()->where('store_id', $store?->id);
        if ($isLatest) {
            $additional->latest('id');
        }

        return $additional->get();
    }

    public function getAllByActive($isLatest = false)
    {
        $additional = $this->model()::query();
        if ($isLatest) {
            $additional->latest('id');
        }

        return $additional->where('is_active', true)->get();
    }

    public function storeByRequest(AdditionalRequest $request)
    {
        $service = $this->create([
            'title' => $request->title,
            'title_bn' => $request->title_bn,
            'price' => $request->price,
            'description' => $request->description,
            'description_bn' => $request->description_bn,
            'service_id' => $request->service_id,
            'store_id' => auth()->user()->store->id,
        ]);

        return $service;
    }

    public function updateByRequest(AdditionalRequest $request, Additional $additional): Additional
    {
        $additional->update([
            'title' => $request->title,
            'title_bn' => $request->title_bn,
            'price' => $request->price,
            'description' => $request->description,
            'description_bn' => $request->description_bn,
            'service_id' => $request->service_id,
        ]);

        return $additional;
    }

    public function updateStatusById(Additional $additional): Additional
    {
        $additional->update([
            'is_active' => ! $additional->is_active,
        ]);

        return $additional;
    }

    public function getByService($serviceId)
    {
        $additionals = (new ServiceRepository())->findOrFailById($serviceId)
            ->additionals->where('is_active', true);

        return $additionals;
    }
}
