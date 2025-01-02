<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Repositories\AreaRepository;
use Illuminate\Http\Request;

class AreaController extends Controller
{
    public function __construct(
        public AreaRepository $areaRepository
    ) {
    }

    public function index()
    {
        $area = auth()->user()->store->area;

        $latLng = collect([]);
        foreach ($area?->latLngs ?? [] as $latlng) {
            $latLng[] = (object) [
                'lat' => $latlng->pivot->lat,
                'lng' => $latlng->pivot->lng
            ];
        }

        return view('area.index', compact('area', 'latLng'));
    }

    public function create()
    {
        return view('area.create');
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|unique:areas,name']);

        $this->areaRepository->storeByRequest($request);

        return to_route('area.index')->with('success', 'Area added successfully');
    }

    public function edit(Area $area)
    {
        $latLongs = $area->latLngs;
        $latLng = collect([]);
        foreach ($latLongs as $latlng) {
            $latLng[] = (object) [
                'lat' => $latlng->pivot->lat,
                'lng' => $latlng->pivot->lng
            ];
        }
        return view('area.edit', compact('area', 'latLng'));
    }

    public function update(Request $request, Area $area)
    {
        $request->validate(['name' => 'required|unique:areas,name,' . $area->id]);
        $this->areaRepository->updateByRequest($area, $request);

        return to_route('area.index')->with('success', 'Update Successfully');
    }

    public function toggle(Area $area)
    {
        $this->areaRepository->toggleStaus($area);

        return redirect()->route('area.index')->with('success', 'Status Update Successfully');
    }

    public function delete(Area $area)
    {
        $area->delete();

        return back()->with('success', 'Deleted Successfully');
    }
}
