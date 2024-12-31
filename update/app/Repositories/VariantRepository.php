<?php

namespace App\Repositories;

use App\Http\Requests\VariantRequest;
use App\Models\Variant;

class VariantRepository extends Repository
{
    public function model()
    {
        return Variant::class;
    }

    public function getAll()
    {
        return $this->model()::orderBy('position', 'asc')->get();
    }

    public function storeByRequest(VariantRequest $request): Variant
    {
        return $this->create([
            'name' => $request->name,
            'store_id' => auth()->user()->store?->id,
            'service_id' => $request->service_id,
            'name_bn' => $request->name_bn,
            'position' => $request->position,
        ]);
    }

    public function updateByRequest(VariantRequest $request, Variant $variant): Variant
    {
        $variant->update([
            'name' => $request->name,
            'store_id' => auth()->user()->store?->id,
            'name_bn' => $request->name_bn,
            'service_id' => $request->service_id,
            'position' => $request->position,
        ]);

        return $variant;
    }
}
