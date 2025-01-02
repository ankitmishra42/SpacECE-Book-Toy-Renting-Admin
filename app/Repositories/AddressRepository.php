<?php

namespace App\Repositories;

use App\Http\Requests\AddressRequest;
use App\Models\Address;
use App\Models\Store;

class AddressRepository extends Repository
{
    public function model()
    {
        return Address::class;
    }

    public function getAll()
    {
        $customer = auth()->user()->customer;

        $hasDefault = $customer->addresses()->where('is_default', true)->get();
        if ($hasDefault->isEmpty()) {
            if ($customer->addresses()->first()) {
                $customer->addresses()->first()->update(['is_default' => true]);
            }
        }

        return $this->query()->where('customer_id', $customer->id)->orderBy('is_default', 'desc')->get();
    }

    public function storeByRequest(AddressRequest $request): Address
    {
        return $this->create([
            'customer_id' => auth()->user()->customer->id,
            'address_name' => $request->address_name,
            'road_no' => $request->road_no,
            'house_no' => $request->house_no,
            'house_name' => $request->house_name,
            'flat_no' => $request->flat_no,
            'block' => $request->block,
            'area' => $request->area,
            'sub_district_id' => $request->sub_district_id,
            'district_id' => $request->district_id,
            'address_line' => $request->address_line,
            'address_line2' => $request->address_line2,
            'delivery_note' => $request->delivery_note,
            'post_code' => $request->post_code,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'is_default' => auth()->user()->customer->addresses->isEmpty() ? true : false,
        ]);
    }

    public function updateByRequest(Address $address, AddressRequest $request): Address
    {
        $address->update([
            'customer_id' => auth()->user()->customer->id,
            'address_name' => $request->address_name,
            'road_no' => $request->road_no,
            'house_no' => $request->house_no,
            'house_name' => $request->house_name,
            'flat_no' => $request->flat_no,
            'block' => $request->block,
            'area' => $request->area,
            'sub_district_id' => $request->sub_district_id,
            'district_id' => $request->district_id,
            'address_line' => $request->address_line,
            'address_line2' => $request->address_line2,
            'delivery_note' => $request->delivery_note,
            'post_code' => $request->post_code,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        return $address;
    }

    public function updateOrCreate($request, Store $store): Address
    {
        $address = $this->query()->updateOrCreate([
            'id' => $store->address->id ?? 0,
        ], [
            'store_id' => $store->id,
            'address_name' => $request->address_name,
            'road_no' => $request->road_no,
            'house_no' => $request->house_no,
            'flat_no' => $request->flat_no,
            'block' => $request->block,
            'area' => $request->area,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        return $address;
    }
}
