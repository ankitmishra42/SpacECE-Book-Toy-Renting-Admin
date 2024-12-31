<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddressRequest;
use App\Http\Requests\StoreUpdateRequest;
use App\Http\Requests\UserRequest;
use App\Models\Store;
use App\Models\User;
use App\Repositories\AddressRepository;
use App\Repositories\StoreRepository;
use App\Repositories\UserRepository;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class StoreProfileController extends Controller
{
    public function index()
    {
        $store = auth()->user()->store;

        return view('store.index', compact('store'));
    }

    public function edit()
    {
        $store = auth()->user()->store;

        return view('store.edit', compact('store'));
    }

    public function update(StoreUpdateRequest $request, Store $store)
    {
        $thumbnailLogo = (new StoreRepository())->logoUpdate($request, $store);
        $thumbnailBanner = (new StoreRepository())->bannerUpdate($request, $store);
        (new StoreRepository())->update($store, [
            'name' => $request->name,
            'delivery_charge' => $request->delivery_charge,
            'min_order_amount' => $request->min_order_amount ?? 0,
            'logo_id' => $thumbnailLogo ? $thumbnailLogo->id : null,
            'banner_id' => $thumbnailBanner ? $thumbnailBanner->id : null,
            'description' => $request->description,
            'prifix' => $request->prefix,
        ]);

        return to_route('store.index')->with('success', 'Shop Updated Successfully');
    }

    public function userUpdate(UserRequest $request, User $user)
    {
        (new UserRepository())->updateByRequest($request, $user);

        return back()->with('success', 'Profile Updated Successfully');
    }

    public function location(Request $request)
    {
        $request->validate([
            'lat' => 'required',
            'lng' => 'required',
        ]);

        $url = 'https://maps.googleapis.com/maps/api/geocode/json?latlng='.$request->lat.','.$request->lng.'&key='.mapApiKey();
        $client = new Client(['verify' => false]);
        $response = json_decode($client->get($url)->getBody()->getContents())->results;
        $store = auth()->user()->store;
        $store->update([
            'latitude' => $request->lat,
            'longitude' => $request->lng,
        ]);

        $request['address_name'] = $response[4]->formatted_address;
        $request['road_no'] = $response[4]->address_components[0]->long_name;
        $request['area'] = $response[4]->address_components[1]->long_name;
        $request['latitude'] = $request->lat;
        $request['longitude'] = $request->lng;

        (new AddressRepository())->updateOrCreate($request, $store);

        return back()->with('success', 'Location is updated successfully.');
    }

    public function updateAddress(AddressRequest $request, Store $store)
    {
        $store->update([
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);
        (new AddressRepository())->updateOrCreate($request, $store);

        return to_route('store.index')->with('success', 'Address updated succesfully');
    }
}
