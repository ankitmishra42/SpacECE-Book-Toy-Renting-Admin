<?php

namespace App\Http\Controllers\Web\Services;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdditionalRequest;
use App\Models\Additional;
use App\Repositories\AdditionalRepository;

class AdditionalServiceController extends Controller
{
    public $additionalRepo;

    public function __construct(AdditionalRepository $additionalRepository)
    {
        $this->additionalRepo = $additionalRepository;
    }

    public function index()
    {
        $additionals = $this->additionalRepo->getAllByShop(true);

        return view('additional-services.index', compact('additionals'));
    }

    public function create()
    {
        $services = auth()->user()->store->services;

        return view('additional-services.create', compact('services'));
    }

    public function store(AdditionalRequest $request)
    {
        $this->additionalRepo->storeByRequest($request);

        return to_route('additional.index')->with('success', 'Created Successfully');
    }

    public function edit(Additional $additional)
    {
        $services = auth()->user()->store->services;

        return view('additional-services.edit', compact('additional', 'services'));
    }

    public function update(AdditionalRequest $request, Additional $additional)
    {
        $this->additionalRepo->updateByRequest($request, $additional);

        return to_route('additional.index')->with('success', 'Updated Successfully');
    }

    public function toggleActivationStatus(Additional $additional)
    {
        $this->additionalRepo->updateStatusById($additional);

        return to_route('additional.index')->with('success', 'Status Updated Successfully');
    }
}
