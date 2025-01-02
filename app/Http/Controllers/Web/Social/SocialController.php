<?php

namespace App\Http\Controllers\Web\Social;

use App\Http\Controllers\Controller;
use App\Models\SocialLink;
use App\Repositories\SocialLinkRepository;
use Illuminate\Http\Request;

class SocialController extends Controller
{
    public function index()
    {
        $socialLink = (new SocialLinkRepository())->getAll();

        return view('social.index', compact('socialLink'));
    }

    public function store(Request $request)
    {
        (new SocialLinkRepository())->storeByRequest($request);

        return redirect()->back()->with('success', 'Link added Successfully');
    }

    public function update(Request $request, SocialLink $socialLink)
    {
        (new SocialLinkRepository())->updateByRequest($request, $socialLink);

        return redirect()->back()->with('success', 'Link update Successfully');
    }

    public function delete(SocialLink $socialLink)
    {
        (new SocialLinkRepository())->deleteByRequest($socialLink);

        return redirect()->back()->with('success', 'deleted Successfully');
    }
}
