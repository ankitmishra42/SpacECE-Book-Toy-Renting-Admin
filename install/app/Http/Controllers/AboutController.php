<?php

namespace App\Http\Controllers;

use App\Http\Requests\AboutRequest;
use App\Models\About;

class AboutController extends Controller
{
    public function index()
    {
        $about = About::first();

        return view('about.index', compact('about'));
    }

    public function edit()
    {
        $about = About::first();

        return view('about.edit', compact('about'));
    }

    public function update(AboutRequest $request, About $about)
    {
        About::updateOrCreate(
            ['id' => $about?->id ?? 0],
            [
                'title' => $request->title,
                'phone' => $request->phone,
                'email' => $request->email,
                'whatsapp' => $request->whatsapp,
                'description' => $request->description,
            ]
        );

        return to_route('about.index')->withSuccess('Updated Successfully');
    }
}
