<?php

namespace App\Repositories;

use App\Models\Area;
use Illuminate\Http\Request;

class AreaRepository extends Repository
{
    public function model()
    {
        return Area::class;
    }

    public function getAllByActive()
    {
        return $this->query()->where('status', true)->get();
    }

    public function storeByRequest(Request $request): Area
    {
        $pattern = '/\(([^,]+),\s([^)]+)\)/';
        $coordinates = [];
        preg_match_all($pattern, $request->coordinates, $matches, PREG_SET_ORDER);

        foreach ($matches as $match) {
            $lat = $match[1];
            $lng = $match[2];
            $coordinates[] = array('lat' => floatval($lat), 'lng' => floatval($lng));
        }

        $area = $this->create([
            'store_id' => auth()->user()->store?->id,
            'name' => $request->name,
        ]);

        foreach ($coordinates as $coordinate) {
            $area->latLngs()->attach($area->id, [
                'lat' => $coordinate['lat'],
                'lng' => $coordinate['lng']
            ]);
        }

        return $area;
    }

    public function updateByRequest(Area $area, Request $request): Area
    {
        $pattern = '/\(([^,]+),\s([^)]+)\)/';
        $coordinates = [];
        preg_match_all($pattern, $request->coordinates, $matches, PREG_SET_ORDER);

        foreach ($matches as $match) {
            $lat = $match[1];
            $lng = $match[2];
            $coordinates[] = array('lat' => floatval($lat), 'lng' => floatval($lng));
        }

        $area->update(['name' => $request->name]);

        $area->latLngs()->detach();

        foreach ($coordinates as $coordinate) {
            $area->latLngs()->attach($area->id, [
                'lat' => $coordinate['lat'],
                'lng' => $coordinate['lng']
            ]);
        }

        return $area;
    }

    public function toggleStaus(Area $area): Area
    {
        $this->update($area, [
            'status' => !$area->status,
        ]);

        return $area;
    }
}
