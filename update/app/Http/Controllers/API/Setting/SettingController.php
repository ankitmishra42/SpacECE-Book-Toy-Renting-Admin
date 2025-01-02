<?php

namespace App\Http\Controllers\API\Setting;

use App\Http\Controllers\Controller;
use App\Http\Resources\SettingResource;
use App\Models\About;
use App\Models\Setting;

class SettingController extends Controller
{
    public function show(Setting $page)
    {
        return $this->json($page->title, [
            'setting' => new SettingResource($page),
        ]);
    }

    public function about()
    {
        $about = About::first();

        return $this->json('About us', [
            'title' => $about?->title,
            'phone' => $about?->phone,
            'whatsapp' => $about?->whatsapp,
            'email' => $about?->email,
            'desceiption' => $about?->description,
        ]);
    }
}
