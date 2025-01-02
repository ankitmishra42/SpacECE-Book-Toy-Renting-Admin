<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\MapApiKey;
use Illuminate\Http\Request;

class MapApiKeyUpdateController extends Controller
{
    public function index()
    {
        $mapApiKey = MapApiKey::first();

        return view('google-mapkey', compact('mapApiKey'));
    }

    public function update(Request $request, MapApiKey $mapApiKey)
    {
        $request->validate(([ 'key' => 'required|string']));

        MapApiKey::updateOrCreate(
            [
                'id' => $mapApiKey ? $mapApiKey->id : 0,
            ],
            [
                'key' => $request->key
            ]
        );

        return back()->withSuccess('Update Successfully');
    }
}
