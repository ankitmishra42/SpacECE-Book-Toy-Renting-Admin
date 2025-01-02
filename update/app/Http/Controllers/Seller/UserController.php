<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUpdateRequest;
use App\Http\Requests\UserRequest;
use App\Http\Resources\SellerProfileResource;
use App\Repositories\StoreRepository;
use App\Repositories\UserRepository;

class UserController extends Controller
{
    public function show()
    {
        $user = auth()->user();

        return $this->json('User Details', [
            'user' => SellerProfileResource::make($user),
        ]);
    }

    public function update(StoreUpdateRequest $request)
    {
        $user = auth()->user();
        $storeRepository = new StoreRepository();
        $updatedStore = $storeRepository->updateOnlyStoreByRequest($request, $user->store);

        return $this->json('Store updated successfully', [
            'user' => SellerProfileResource::make($user),
        ]);
    }

    public function profileUpdate(UserRequest $request)
    {
        $user = (new UserRepository())->updateByRequest($request, auth()->user());
        return $this->json('Profile photo is updated successfully', [
            'user' => (new SellerProfileResource($user)),
        ]);
    }
}
